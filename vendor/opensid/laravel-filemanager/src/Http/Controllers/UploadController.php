<?php

namespace OpenSID\LaravelFilemanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use OpenSID\LaravelFilemanager\Exceptions\UnsafePathException;
use OpenSID\LaravelFilemanager\Services\FileContentValidator;
use OpenSID\LaravelFilemanager\Services\FilenameSanitizer;
use OpenSID\LaravelFilemanager\Services\FilesystemManager;
use OpenSID\LaravelFilemanager\Services\PathGuard;
use OpenSID\LaravelFilemanager\Services\ThumbnailGenerator;
use OpenSID\LaravelFilemanager\Support\FilemanagerConfig;
use OpenSID\LaravelFilemanager\Support\ResolvesFilemanagerContext;

/**
 * Replaces rfm/upload.php + rfm/UploadHandler.php (the 1600-line blueimp
 * jQuery-File-Upload PHP handler). Returns the same blueimp-compatible JSON
 * shape ({"files": [{name, size, url, thumbnailUrl, error?}]}) so the
 * existing jquery.fileupload-ui.js widget (unchanged) renders results as-is.
 *
 * The legacy JS bundle initialises the widget with maxChunkSize:2097152
 * (rfm/js/include.js, makeUploader()) — any file over 2MB arrives as a
 * sequence of Content-Range chunked requests, not a single upload. Since
 * that bundle is kept unmodified (see routes/filemanager.php), this
 * controller has to speak that chunk protocol: chunks are appended to a
 * local staging file keyed by folder+filename and only handed to
 * Storage/ThumbnailGenerator once the final chunk lands.
 *
 * Dropped vs. upstream: URL-based upload is only wired up if a host app
 * opts in via config('filemanager.url_upload_enabled') — it defaults to
 * false, matching this app's existing rfm/config/config.php ('url_upload'
 * was already false there), and the upload panel view doesn't render the
 * URL-upload tab unless it's enabled.
 */
class UploadController extends Controller
{
    use ResolvesFilemanagerContext;

    public function __construct(
        protected FilesystemManager $files,
        protected FilenameSanitizer $names,
        protected ThumbnailGenerator $thumbnails,
        protected FilemanagerConfig $config,
        protected FileContentValidator $contentValidator,
    ) {}

    public function index(Request $request)
    {
        return response()->json(['files' => []]);
    }

    public function store(Request $request)
    {
        Gate::authorize('filemanager.upload', $this->filemanagerContext());

        try {
            $folder = trim(str_replace('\\', '/', (string) $request->input('fldr', '')), '/');

            // Strip absolute disk root / media path prefix if mistakenly passed
            $diskConfig = config('filesystems.disks.'.$this->config->disk(), []);
            $diskRoot = trim(str_replace('\\', '/', (string) ($diskConfig['root'] ?? '')), '/');
            $basePath = trim(str_replace('\\', '/', (string) base_path()), '/');
            $relativeDiskRoot = str_starts_with($diskRoot, $basePath)
                ? trim(substr($diskRoot, strlen($basePath)), '/')
                : $diskRoot;

            if ($relativeDiskRoot !== '' && str_starts_with($folder, $relativeDiskRoot)) {
                $folder = trim(substr($folder, strlen($relativeDiskRoot)), '/');
            }

            PathGuard::assertSafe($folder);
            PathGuard::assertInsideBaseFolder($folder, $this->config->baseFolder());
        } catch (UnsafePathException) {
            return response()->json(['files' => [['error' => trans('filemanager::filemanager.wrong path')]]]);
        }

        $uploaded = $request->file('files', []);
        $results = [];

        foreach ((is_array($uploaded) ? $uploaded : [$uploaded]) as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $results[] = $this->handleUpload($request, $file, $folder);
        }

        return response()->json(['files' => $results]);
    }

    protected function handleUpload(Request $request, UploadedFile $file, string $folder): array
    {
        $originalName = $this->names->sanitize($file->getClientOriginalName());
        $extension = mb_strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $type = $request->filled('type') ? (int) $request->input('type') : null;
        $allowedExtensions = $this->config->allowedExtensionsForType($type);

        if (! in_array($extension, $allowedExtensions, true) || ! $this->config->isExtensionAllowed($extension) || ! $this->config->isFilenameSafe($originalName)) {
            return ['name' => $originalName, 'error' => trans('filemanager::filemanager.wrong extension')];
        }

        $range = $this->parseContentRange($request);

        if ($range === null) {
            if ($file->getSize() > $this->config->maxUploadSizeBytes()) {
                return ['name' => $originalName, 'error' => trans('filemanager::filemanager.max_size_reached', ['size' => $this->config->maxUploadSizeMb()])];
            }

            return $this->finalize($file->getRealPath(), $originalName, $folder);
        }

        [$start, $end, $total] = $range;

        if ($total > $this->config->maxUploadSizeBytes()) {
            return ['name' => $originalName, 'error' => trans('filemanager::filemanager.max_size_reached', ['size' => $this->config->maxUploadSizeMb()])];
        }

        $stagingPath = $this->stagingPath($folder, $originalName);
        File::ensureDirectoryExists(dirname($stagingPath));

        // Chunks must arrive in order and be contiguous: the new chunk has
        // to start exactly where the staging file currently ends. This
        // stops a client from (a) piling far more bytes into staging than
        // `total` claims — an 8 MB "total" streamed as 50×8 MB chunks would
        // otherwise fill the disk — and (b) rewinding/overlapping an
        // already-validated region.
        clearstatcache(true, $stagingPath);
        $currentSize = ($start > 0 && is_file($stagingPath)) ? (int) filesize($stagingPath) : 0;

        if ($start !== $currentSize) {
            @unlink($stagingPath);

            return ['name' => $originalName, 'error' => trans('filemanager::filemanager.wrong path')];
        }

        $chunk = fopen($stagingPath, $start === 0 ? 'wb' : 'ab');
        fwrite($chunk, file_get_contents($file->getRealPath()));
        fclose($chunk);

        clearstatcache(true, $stagingPath);
        if (filesize($stagingPath) > min($total, $this->config->maxUploadSizeBytes())) {
            @unlink($stagingPath);

            return ['name' => $originalName, 'error' => trans('filemanager::filemanager.max_size_reached', ['size' => $this->config->maxUploadSizeMb()])];
        }

        if ($end + 1 < $total) {
            return ['name' => $originalName, 'size' => $end + 1];
        }

        $result = $this->finalize($stagingPath, $originalName, $folder);
        @unlink($stagingPath);

        return $result;
    }

    protected function finalize(string $localPath, string $originalName, string $folder): array
    {
        $extension = mb_strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // The filename-only checks in handleUpload() happen before a
        // chunked upload's bytes even exist locally — this is the one
        // place both the single-shot and chunked paths converge with the
        // full file on disk, so it's the only place content can actually
        // be verified against what the extension claims.
        if (! $this->contentValidator->isValidUpload($localPath, $extension)) {
            return ['name' => $originalName, 'error' => trans('filemanager::filemanager.wrong extension')];
        }

        $disk = $this->files->disk();
        $path = $this->uniquePath($disk, trim($folder.'/'.$originalName, '/'));

        $stream = fopen($localPath, 'r');
        $disk->put($path, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        $entry = [
            'name' => basename($path),
            'size' => filesize($localPath),
            'url' => $this->files->url($path),
        ];

        if ($this->thumbnails->isImage($path)
            && $this->thumbnails->make($this->config->disk(), $path, $this->config->thumbsDisk(), $path)
        ) {
            $entry['thumbnailUrl'] = $this->files->thumbUrl($path);
        }

        return $entry;
    }

    protected function parseContentRange(Request $request): ?array
    {
        $header = $request->header('Content-Range');

        if (! $header || ! preg_match('/bytes\s+(\d+)-(\d+)\/(\d+)/', $header, $matches)) {
            return null;
        }

        return [(int) $matches[1], (int) $matches[2], (int) $matches[3]];
    }

    protected function stagingPath(string $folder, string $name): string
    {
        return storage_path('app/filemanager-chunks/'.sha1($folder.'|'.$name));
    }

    protected function uniquePath($disk, string $path): string
    {
        if (! $disk->exists($path)) {
            return $path;
        }

        $info = pathinfo($path);
        $dir = $info['dirname'] === '.' ? '' : $info['dirname'].'/';
        $extension = isset($info['extension']) ? '.'.$info['extension'] : '';
        $i = 1;

        do {
            $candidate = "{$dir}{$info['filename']}_{$i}{$extension}";
            $i++;
        } while ($disk->exists($candidate));

        return $candidate;
    }
}
