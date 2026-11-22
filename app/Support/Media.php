<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Uploaded menu images and table QR codes.
 *
 * The database stores whatever the client sent back from POST /api/upload-image
 * (a full URL), plus bare filenames for QR codes. This class resolves both to
 * whichever disk is active: local `storage/app/public` in development, or the
 * S3-compatible bucket (Supabase Storage) in production.
 */
class Media
{
    public const DISK = 'media';

    /**
     * Store an uploaded file and return its public URL.
     */
    public static function store($file, string $directory): string
    {
        $path = $file->store(trim($directory, '/'), self::DISK);

        return self::url($path);
    }

    /**
     * Public URL for a stored key or an already-absolute URL.
     */
    public static function url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk(self::DISK)->url(ltrim($path, '/'));
    }

    /**
     * Delete a stored file. Absolute URLs are ignored: they may point at the
     * frontend's static assets, which this app does not own.
     */
    public static function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            // Only touch files this app uploaded: match its own public base.
            $base = rtrim((string) config('filesystems.disks.media.url'), '/');

            if ($base === '' || ! str_starts_with($path, $base)) {
                return;
            }

            $path = ltrim(substr($path, strlen($base)), '/');
        }

        $disk = Storage::disk(self::DISK);
        $key = ltrim($path, '/');

        if ($disk->exists($key)) {
            $disk->delete($key);
        }
    }
}
