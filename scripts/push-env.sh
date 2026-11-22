#!/bin/sh
# Push .env.production keys to a Vercel environment.
#
# Two traps this script exists to avoid:
#  1. `vercel env add` reads the VALUE from stdin, so the loop's own stdin is
#     consumed by the first call. Each call gets its value from a temp file.
#  2. .env files written on Windows carry CRLF; a trailing \r ends up inside the
#     stored value and silently corrupts it (e.g. an APP_KEY that no longer
#     decrypts). Values are stripped of \r before being written.
set -e

TARGET="${1:-production}"
FILE="${2:-.env.production}"
TMP="$(mktemp)"

while IFS= read -r line || [ -n "$line" ]; do
    case "$line" in
        ''|'#'*) continue ;;
    esac

    key=$(printf '%s' "$line" | cut -d= -f1 | tr -d '\r')
    val=$(printf '%s' "$line" | cut -d= -f2- | tr -d '\r')

    # Strip a single layer of surrounding quotes if present.
    case "$val" in
        \"*\") val=${val#\"}; val=${val%\"} ;;
    esac

    [ -z "$key" ] && continue

    printf '%s' "$val" > "$TMP"

    if vercel env add "$key" "$TARGET" < "$TMP" >/dev/null 2>&1; then
        printf '  %-26s ok\n' "$key"
    elif vercel env add "$key" "$TARGET" --force < "$TMP" >/dev/null 2>&1; then
        printf '  %-26s ok (overwrote)\n' "$key"
    else
        printf '  %-26s FAILED\n' "$key"
    fi
done < "$FILE"

rm -f "$TMP"
