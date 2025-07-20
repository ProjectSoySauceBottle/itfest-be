@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Manajemen Menu</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Menu</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($menu->gambar)
                            <img src="{{ asset('storage/' . $menu->gambar) }}" width="80" height="60" style="object-fit:cover;">
                        @else
                            <small class="text-muted">Tidak ada</small>
                        @endif
                    </td>
                    <td>{{ $menu->nama_menu }}</td>
                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                    <td>{{ Str::limit($menu->deskripsi, 50) }}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $menu->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $menu->id }}">Hapus</button>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="editModal{{ $menu->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Menu: {{ $menu->nama_menu }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nama Menu</label>
                                    <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Harga</label>
                                    <input type="number" name="harga" value="{{ $menu->harga }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" class="form-control">{{ $menu->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Gambar (Opsional)</label>
                                    <input type="file" name="gambar" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Delete --}}
                <div class="modal fade" id="deleteModal{{ $menu->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="modal-content">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus menu <strong>{{ $menu->nama_menu }}</strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data menu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $menus->links() }}
</div>

{{-- Modal Tambah Menu --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Menu</label>
                    <input type="text" name="nama_menu" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
