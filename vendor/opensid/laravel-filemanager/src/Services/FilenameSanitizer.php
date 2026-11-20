<?php

namespace OpenSID\LaravelFilemanager\Services;

/**
 * Port of rfm's fix_filename()/sanitize(). Strips tags/HTML entities, quotes,
 * slashes, optionally transliterates and converts spaces, per config.
 */
class FilenameSanitizer
{
    public function sanitize(string $name, bool $isFolder = false): string
    {
        // Strip null bytes, control characters, and Windows Alternate Data Streams
        $name = str_replace(["\0", '%00', '::$DATA'], '', $name);
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name) ?? $name;

        // Surrounding whitespace goes first, before space->replace_with
        // conversion can turn a trailing space into a "_" that trim can't
        // reach later.
        $name = trim($name);

        $name = $this->stripDangerousMarkup($name);

        if (config('filemanager.convert_spaces')) {
            $name = str_replace(' ', (string) config('filemanager.replace_with', '_'), $name);
        }

        if (config('filemanager.transliterate')) {
            $name = $this->transliterate($name);
        }

        $name = str_replace(['"', "'", '/', '\\'], '', $name);
        $name = strip_tags($name);

        if (config('filemanager.lower_case')) {
            $name = mb_strtolower($name);
        }

        // A name that transliterated down to just an extension (e.g. an
        // unsupported-script filename becoming ".jpg") gets a "file" prefix
        // instead of silently producing a hidden dotfile.
        if (! $isFolder && str_starts_with($name, '.')) {
            $name = 'file'.$name;
        }

        // Strip TRAILING dots/whitespace only (Windows silently drops them,
        // which turns "shell.php." into an executable "shell.php"). A
        // leading dot is deliberately kept here — for files it was already
        // neutralised by the "file" prefix above; for folders ".git"-style
        // names stay intact.
        $name = rtrim($name, " .\t\n\r\0\x0B");

        return $this->clampLength($name);
    }

    /**
     * Keep the whole name within the common 255-byte filesystem limit
     * (ext4/APFS/NTFS component cap) so an over-long name surfaces as a
     * clean rejection upstream, not a filesystem write exception. The
     * extension is preserved.
     */
    protected function clampLength(string $name, int $max = 255): string
    {
        if (strlen($name) <= $max) {
            return $name;
        }

        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $suffix = $extension !== '' ? '.'.$extension : '';
        $stem = substr($name, 0, strlen($name) - strlen($suffix));

        $stem = substr($stem, 0, max(1, $max - strlen($suffix)));

        return rtrim($stem, " .\t\n\r\0\x0B").$suffix;
    }

    protected function stripDangerousMarkup(string $str): string
    {
        return strip_tags(htmlspecialchars($str));
    }

    protected function transliterate(string $str): string
    {
        if (! mb_detect_encoding($str, 'UTF-8', true)) {
            $str = mb_convert_encoding($str, 'UTF-8');
        }

        if (function_exists('transliterator_transliterate')) {
            $str = transliterator_transliterate('Any-Latin; Latin-ASCII', $str) ?: $str;
        } elseif (function_exists('iconv')) {
            $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str) ?: $str;
        }

        return preg_replace('/[^a-zA-Z0-9.\[\]_| -]/', '', $str) ?? $str;
    }
}
