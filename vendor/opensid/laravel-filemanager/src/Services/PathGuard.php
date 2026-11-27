<?php

namespace OpenSID\LaravelFilemanager\Services;

use OpenSID\LaravelFilemanager\Exceptions\UnsafePathException;

/**
 * Path-traversal guard. Port of rfm's checkRelativePath()/checkRelativePathPartial(),
 * checked against both the raw value and its rawurldecode()'d form since a path
 * segment can smuggle an encoded ".." past a naive check.
 */
class PathGuard
{
    public static function isSafe(?string $path): bool
    {
        if ($path === null) {
            return true;
        }

        $decoded = $path;
        $maxLoops = 10;
        while ($maxLoops-- > 0) {
            if (! self::isSafePartial($decoded)) {
                return false;
            }

            $next = rawurldecode($decoded);
            if ($next === $decoded) {
                break;
            }
            $decoded = $next;
        }

        return self::isSafePartial($decoded);
    }

    public static function assertSafe(?string $path): void
    {
        if (! self::isSafe($path)) {
            throw UnsafePathException::forPath($path ?? '');
        }
    }

    public static function isInsideBaseFolder(?string $path, ?string $baseFolder): bool
    {
        $baseFolder = trim((string) $baseFolder, '/');
        if ($baseFolder === '') {
            return true;
        }

        $path = trim((string) $path, '/');
        if ($path === '' || $path === $baseFolder) {
            return true;
        }

        return str_starts_with($path, $baseFolder.'/');
    }

    public static function assertInsideBaseFolder(?string $path, ?string $baseFolder): void
    {
        if (! self::isInsideBaseFolder($path, $baseFolder)) {
            throw UnsafePathException::forPath($path ?? '');
        }
    }

    protected static function isSafePartial(string $path): bool
    {
        if ($path === '..' || $path === '.') {
            return false;
        }

        // Null bytes or control characters
        if (str_contains($path, "\0") || preg_match('/[\x00-\x1F\x7F]/', $path)) {
            return false;
        }

        // Windows Alternate Data Streams (ADS)
        if (str_contains($path, '::$DATA') || (str_contains($path, ':') && ! preg_match('/^[a-zA-Z]:[\\\\\/]/', $path))) {
            return false;
        }

        foreach (['../', './', '/..', '..\\', '\\..', '.\\'] as $needle) {
            if (str_contains($path, $needle)) {
                return false;
            }
        }

        return true;
    }
}
