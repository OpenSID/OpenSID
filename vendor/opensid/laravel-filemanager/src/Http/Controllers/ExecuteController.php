<?php

namespace OpenSID\LaravelFilemanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use OpenSID\LaravelFilemanager\Exceptions\UnsafePathException;
use OpenSID\LaravelFilemanager\Services\ClipboardManager;
use OpenSID\LaravelFilemanager\Services\FileContentValidator;
use OpenSID\LaravelFilemanager\Services\FilenameSanitizer;
use OpenSID\LaravelFilemanager\Services\FilesystemManager;
use OpenSID\LaravelFilemanager\Services\PathGuard;
use OpenSID\LaravelFilemanager\Services\ThumbnailGenerator;
use OpenSID\LaravelFilemanager\Support\FilemanagerConfig;
use OpenSID\LaravelFilemanager\Support\ResolvesFilemanagerContext;

/**
 * Replaces rfm/execute.php. Same "empty body = success, non-empty body =
 * error message" contract as AjaxController — see its docblock.
 *
 * Dropped vs. upstream rfm: chmod (already a dead stub upstream).
 *
 * Permission mapping preserves upstream's (slightly odd but intentional)
 * scheme: copy/cut/paste and delete all require filemanager.delete
 * (upstream tied both to the "hapus_gambar_rfm" flag); create/rename/
 * duplicate/upload/text-edit all require filemanager.upload (upstream's
 * "ubah_tambah_gambar_rfm" flag).
 */
class ExecuteController extends Controller
{
    use ResolvesFilemanagerContext;

    public function __construct(
        protected FilesystemManager $files,
        protected FilenameSanitizer $names,
        protected ClipboardManager $clipboard,
        protected FilemanagerConfig $config,
    ) {}

    public function handle(Request $request): Response
    {
        try {
            return match ($request->input('action')) {
                'delete_file' => $this->deleteFile($request),
                'delete_files' => $this->deleteFiles($request),
                'delete_folder' => $this->deleteFolder($request),
                'create_folder' => $this->createFolder($request),
                'rename_folder' => $this->renameFolder($request),
                'create_file' => $this->createFile($request),
                'rename_file' => $this->renameFile($request),
                'duplicate_file' => $this->duplicateFile($request),
                'crop_image' => $this->cropImage($request),
                'paste_clipboard' => $this->pasteClipboard($request),
                'save_text_file' => $this->saveTextFile($request),
                default => $this->ok(trans('filemanager::filemanager.wrong action')),
            };
        } catch (UnsafePathException) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }
    }

    protected function deleteFile(Request $request): Response
    {
        if (! Gate::allows('filemanager.delete', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));
        $name = basename($path);

        if ($this->config->isHiddenFile($name)) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $this->files->deleteFile($path);

        return $this->ok();
    }

    protected function deleteFiles(Request $request): Response
    {
        if (! Gate::allows('filemanager.delete', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        foreach ((array) $request->input('paths', []) as $path) {
            $safePath = $this->safePath((string) $path);
            if (! $this->config->isHiddenFile(basename($safePath))) {
                $this->files->deleteFile($safePath);
            }
        }

        return $this->ok();
    }

    protected function deleteFolder(Request $request): Response
    {
        if (! Gate::allows('filemanager.delete', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));

        if (trim($path, '/') === '' || $path === $this->config->baseFolder() || $this->config->isHiddenFolder(basename($path))) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }

        $this->files->deleteDirectory($path);

        return $this->ok();
    }

    protected function createFolder(Request $request): Response
    {
        if (! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $containingDir = $this->safePath($request->input('path', ''));
        $name = $this->names->sanitize((string) $request->input('name', ''), isFolder: true);

        if ($name === '') {
            return $this->ok(trans('filemanager::filemanager.Empty_name'));
        }

        $newPath = $this->join($containingDir, $name);

        if (! $this->files->makeDirectory($newPath)) {
            return $this->ok(trans('filemanager::filemanager.Rename_existing_folder'));
        }

        return $this->ok();
    }

    protected function renameFolder(Request $request): Response
    {
        if (! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));
        $name = $this->names->sanitize((string) $request->input('name', ''), isFolder: true);

        if ($name === '') {
            return $this->ok(trans('filemanager::filemanager.Empty_name'));
        }

        if (! $this->files->isDirectory($path) || $path === $this->config->baseFolder()) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }

        $newPath = $this->join(dirname($path), $name);
        PathGuard::assertInsideBaseFolder($newPath, $this->config->baseFolder());

        if (! $this->files->move($path, $newPath)) {
            return $this->ok(trans('filemanager::filemanager.Rename_existing_folder'));
        }

        return $this->ok();
    }

    protected function createFile(Request $request): Response
    {
        if (! config('filemanager.text_editing_enabled') || ! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Open_Edit_Not_Allowed'));
        }

        $containingDir = $this->safePath($request->input('path', ''));
        $name = $this->names->sanitize((string) $request->input('name', ''));

        if ($name === '' || ! str_contains($name, '.')) {
            return $this->ok(trans('filemanager::filemanager.No_Extension'));
        }

        $extension = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (! $this->config->isEditableTextExtension($extension)
            || ! $this->config->isFilenameSafe($name)
            || $this->config->isHiddenFile($name)
            || $this->config->isHiddenExtension($extension)
        ) {
            return $this->ok(trans('filemanager::filemanager.Error_extension'));
        }

        $newPath = $this->join($containingDir, $name);

        if ($this->files->exists($newPath)) {
            return $this->ok(trans('filemanager::filemanager.Rename_existing_file'));
        }

        $this->files->put($newPath, (string) $request->input('new_content', ''));

        return $this->ok();
    }

    protected function renameFile(Request $request): Response
    {
        if (! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));
        $name = $this->names->sanitize((string) $request->input('name', ''));

        if ($name === '') {
            return $this->ok(trans('filemanager::filemanager.Empty_name'));
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newName = $extension !== '' ? "{$name}.{$extension}" : $name;
        $targetExtension = pathinfo($newName, PATHINFO_EXTENSION);

        if (! $this->config->isFilenameSafe($newName)
            || ($targetExtension !== '' && ! $this->config->isExtensionAllowed($targetExtension))
            || $this->config->isHiddenFile(basename($path))
            || $this->config->isHiddenFile($newName)
            || ($targetExtension !== '' && $this->config->isHiddenExtension($targetExtension))
        ) {
            return $this->ok(trans('filemanager::filemanager.wrong extension'));
        }

        $newPath = $this->join(dirname($path), $newName);
        PathGuard::assertInsideBaseFolder($newPath, $this->config->baseFolder());

        if (! $this->files->move($path, $newPath)) {
            return $this->ok(trans('filemanager::filemanager.Rename_existing_file'));
        }

        return $this->ok();
    }

    protected function duplicateFile(Request $request): Response
    {
        if (! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));
        $name = $this->names->sanitize((string) $request->input('name', ''));

        if ($name === '') {
            return $this->ok(trans('filemanager::filemanager.Empty_name'));
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newName = $extension !== '' ? "{$name}.{$extension}" : $name;
        $targetExtension = pathinfo($newName, PATHINFO_EXTENSION);

        if (! $this->config->isFilenameSafe($newName)
            || ($targetExtension !== '' && ! $this->config->isExtensionAllowed($targetExtension))
            || $this->config->isHiddenFile(basename($path))
            || $this->config->isHiddenFile($newName)
            || ($targetExtension !== '' && $this->config->isHiddenExtension($targetExtension))
        ) {
            return $this->ok(trans('filemanager::filemanager.wrong extension'));
        }

        $newPath = $this->join(dirname($path), $newName);
        PathGuard::assertInsideBaseFolder($newPath, $this->config->baseFolder());

        if (! $this->files->copy($path, $newPath)) {
            return $this->ok(trans('filemanager::filemanager.Rename_existing_file'));
        }

        return $this->ok();
    }

    protected function pasteClipboard(Request $request): Response
    {
        if (! Gate::allows('filemanager.delete', $this->filemanagerContext()) || ! $this->clipboard->hasContent()) {
            return $this->ok();
        }

        $destinationDir = $this->safePath($request->input('path', ''));
        $sourcePath = $this->clipboard->path();
        $action = $this->clipboard->action();

        $newPath = $this->join($destinationDir, basename($sourcePath));
        PathGuard::assertInsideBaseFolder($newPath, $this->config->baseFolder());

        // pasting into the same directory, or a folder into its own
        // subtree, is a silent no-op — matches upstream behaviour
        $sourceDir = dirname($sourcePath);
        $sourceDir = $sourceDir === '.' ? '' : $sourceDir;
        $destinationTrimmed = trim($destinationDir, '/');

        if ($sourceDir === $destinationTrimmed
            || str_starts_with($destinationTrimmed.'/', trim($sourcePath, '/').'/')
        ) {
            return $this->ok();
        }

        $moved = $action === 'copy'
            ? $this->files->copy($sourcePath, $newPath)
            : $this->files->move($sourcePath, $newPath);

        $this->clipboard->clear();

        return $moved ? $this->ok() : $this->ok(trans('filemanager::filemanager.wrong action'));
    }

    protected function saveTextFile(Request $request): Response
    {
        if (! config('filemanager.text_editing_enabled') || ! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Open_Edit_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));

        if (! $this->files->exists($path)) {
            return $this->ok(trans('filemanager::filemanager.File_Not_Found'));
        }

        $name = basename($path);
        $extension = mb_strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (! $this->config->isEditableTextExtension($extension)
            || ! $this->config->isFilenameSafe($name)
            || $this->config->isHiddenFile($name)
            || $this->config->isHiddenExtension($extension)
        ) {
            return $this->ok(trans('filemanager::filemanager.File_Open_Edit_Not_Allowed'));
        }

        $this->files->put($path, (string) $request->input('new_content', ''));

        return $this->ok();
    }

    /**
     * Accepts mixed, not string: Laravel's ConvertEmptyStringsToNull
     * middleware (part of the default "web" stack) turns an empty-string
     * "path" field — the very common "root folder" case — into null
     * before it ever reaches here, which a strict `string $path` signature
     * would only discover as a fatal TypeError, i.e. a 500, the very first
     * time someone operated on the root folder.
     */
    protected function safePath(mixed $path): string
    {
        $baseFolder = $this->config->baseFolder();
        $path = (string) $path;

        if ($path === '' && $baseFolder !== '') {
            $path = $baseFolder;
        }

        PathGuard::assertSafe($path);
        PathGuard::assertInsideBaseFolder($path, $baseFolder);

        return trim($path, '/');
    }

    protected function join(string $dir, string $name): string
    {
        $dir = trim($dir, '/');

        return $dir === '' ? $name : "{$dir}/{$name}";
    }

    protected function cropImage(Request $request): Response
    {
        if (! Gate::allows('filemanager.upload', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = $this->safePath($request->input('path', ''));
        $dataUrl = (string) $request->input('image_data', '');
        $nameNew = (string) $request->input('name_new', '');

        if ($dataUrl === '' || ! $this->files->exists($path)) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, config('filemanager.extensions.image', []), true) || in_array($extension, ['svg', 'ico'], true)) {
            return $this->ok(trans('filemanager::filemanager.wrong extension'));
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl)) {
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $decoded = base64_decode(preg_replace('/\s+/', '', $data), true);
            if ($decoded === false || $decoded === '') {
                return $this->ok(trans('filemanager::filemanager.Upload_error'));
            }
        } else {
            return $this->ok(trans('filemanager::filemanager.Upload_error'));
        }

        $targetPath = $path;
        if ($nameNew !== '') {
            $sanitizedName = $this->names->sanitize($nameNew);
            $newExt = strtolower(pathinfo($sanitizedName, PATHINFO_EXTENSION));
            if ($newExt === '') {
                $sanitizedName .= '.'.$extension;
                $newExt = $extension;
            }

            if (! in_array($newExt, config('filemanager.extensions.image', []), true)
                || in_array($newExt, ['svg', 'ico'], true)
                || ! $this->config->isExtensionAllowed($newExt)
                || ! $this->config->isFilenameSafe($sanitizedName)
            ) {
                return $this->ok(trans('filemanager::filemanager.wrong extension'));
            }

            $dir = dirname($path);
            $dir = ($dir === '.' || $dir === '/') ? '' : $dir;
            $targetPath = $this->join($dir, $sanitizedName);
        }

        PathGuard::assertInsideBaseFolder($targetPath, $this->config->baseFolder());

        $tmp = tempnam(sys_get_temp_dir(), 'rfmCrop');
        file_put_contents($tmp, $decoded);

        $validator = app(FileContentValidator::class);
        $targetExt = pathinfo($targetPath, PATHINFO_EXTENSION) ?: $extension;
        if (! $validator->isValidUpload($tmp, $targetExt)) {
            @unlink($tmp);

            return $this->ok('File is dangerous');
        }

        $this->files->put($targetPath, $decoded);
        @unlink($tmp);

        try {
            app(ThumbnailGenerator::class)->make(
                $this->config->disk(),
                $targetPath,
                $this->config->thumbsDisk(),
                $targetPath
            );
        } catch (\Throwable) {
            // best-effort thumbnail recreation
        }

        return $this->ok();
    }

    protected function ok(string $body = ''): Response
    {
        return response($body, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
