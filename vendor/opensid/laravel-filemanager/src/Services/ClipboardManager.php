<?php

namespace OpenSID\LaravelFilemanager\Services;

use Illuminate\Support\Facades\Session;

/**
 * Copy/cut/paste clipboard, backed by Laravel's session() instead of the
 * legacy $_SESSION['RF']['clipboard'] / $_SESSION['RF']['clipboard_action'].
 */
class ClipboardManager
{
    protected const PATH_KEY = 'filemanager.clipboard.path';

    protected const ACTION_KEY = 'filemanager.clipboard.action';

    public function set(string $path, string $action): void
    {
        Session::put(self::PATH_KEY, $path);
        Session::put(self::ACTION_KEY, $action);
    }

    public function path(): ?string
    {
        return Session::get(self::PATH_KEY);
    }

    public function action(): ?string
    {
        return Session::get(self::ACTION_KEY);
    }

    public function hasContent(): bool
    {
        return filled($this->path()) && filled($this->action());
    }

    public function clear(): void
    {
        Session::forget([self::PATH_KEY, self::ACTION_KEY]);
    }
}
