<?php

namespace OpenSID\LaravelFilemanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenSID\LaravelFilemanager\Exceptions\UnsafePathException;
use OpenSID\LaravelFilemanager\Services\FilesystemManager;
use OpenSID\LaravelFilemanager\Services\PathGuard;

/**
 * Replaces rfm/force_download.php. Storage::download() already streams with
 * correct headers (and Range support, for the "local" driver) — no more
 * manual mime detection / chunked fread loop.
 */
class DownloadController extends Controller
{
    public function __construct(protected FilesystemManager $files) {}

    public function stream(Request $request)
    {
        $path = trim((string) $request->input('path', ''), '/');
        $name = (string) $request->input('name', '');

        try {
            PathGuard::assertSafe($path);
            PathGuard::assertSafe($name);
            PathGuard::assertInsideBaseFolder($path, $this->files->config()->baseFolder());
        } catch (UnsafePathException) {
            abort(400, trans('filemanager::filemanager.wrong path'));
        }

        if (str_contains($name, '/')) {
            abort(400, trans('filemanager::filemanager.wrong path'));
        }

        $fullPath = trim($path.'/'.$name, '/');

        if (! $this->files->exists($fullPath)) {
            abort(404, trans('filemanager::filemanager.File_Not_Found'));
        }

        $extension = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($this->files->config()->isHiddenFile($name)
            || ($extension !== '' && $this->files->config()->isHiddenExtension($extension))
            || ($extension !== '' && $this->files->config()->isExtensionBlacklisted($extension))
            || ! $this->files->config()->isFilenameSafe($name)
        ) {
            abort(403, trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        return $this->files->disk()->download($fullPath, basename($fullPath));
    }
}
