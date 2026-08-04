<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AvatarService
{
    private static array $existsCache = [];

    public function hasAvatar(?string $url): bool
    {
        if ($url === null || trim($url) === '') {
            return false;
        }

        if (str_starts_with($url, 'data:image/')) {
            return true;
        }

        $path = $this->storagePath($url);

        if ($path === null) {
            return false;
        }

        if (array_key_exists($path, self::$existsCache)) {
            return self::$existsCache[$path];
        }

        return self::$existsCache[$path] = Storage::disk('public')->exists($path);
    }

    public function initials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name)) ?: [];

        $words = array_values(array_filter($words, fn (string $word) => $word !== ''));

        if ($words === []) {
            return '';
        }

        $first = mb_strtoupper(mb_substr($words[0], 0, 1));
        $last = count($words) > 1 ? mb_strtoupper(mb_substr(end($words), 0, 1)) : '';

        return $first.$last;
    }

    public function color(string $name): string
    {
        $hue = abs(crc32(mb_strtolower($name))) % 360;

        return 'hsl('.$hue.', 65%, 42%)';
    }

    public static function flushExistsCache(): void
    {
        self::$existsCache = [];
    }

    private function storagePath(string $url): ?string
    {
        if (preg_match('#^https?://[^/]+/storage/(.+)$#', $url, $matches) || preg_match('#^/storage/(.+)$#', $url, $matches)) {
            return rtrim($matches[1], '/');
        }

        if (preg_match('#^(uploads|media)/.+$#', $url)) {
            return rtrim($url, '/');
        }

        return null;
    }
}
