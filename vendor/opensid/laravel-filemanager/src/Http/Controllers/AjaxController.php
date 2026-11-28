<?php

namespace OpenSID\LaravelFilemanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use OpenSID\LaravelFilemanager\Exceptions\UnsafePathException;
use OpenSID\LaravelFilemanager\Services\ClipboardManager;
use OpenSID\LaravelFilemanager\Services\FilesystemManager;
use OpenSID\LaravelFilemanager\Services\PathGuard;
use OpenSID\LaravelFilemanager\Support\ResolvesFilemanagerContext;

/**
 * Replaces rfm/ajax_calls.php. The legacy frontend bundle (rfm/js/include.js,
 * unchanged) treats an EMPTY response body as success and any non-empty body
 * as an error message to alert() — every handler below follows that exact
 * contract (always HTTP 200; jQuery's .done() never fires for the message
 * on a non-2xx response, so a "real" 4xx would silently swallow the error
 * message rather than showing it, unlike the legacy behaviour we're matching).
 *
 * Dropped vs. upstream rfm (per agreed scope trim): extract (zip/tar/gz),
 * cad_preview, media_preview (jPlayer), get_lang/change_lang (host app
 * drives locale via app()->setLocale()), save_img (was TUI-editor-only),
 * new_file_form (TUI-editor-only), chmod (already a dead stub upstream).
 */
class AjaxController extends Controller
{
    use ResolvesFilemanagerContext;

    public function __construct(
        protected FilesystemManager $files,
        protected ClipboardManager $clipboard,
    ) {}

    public function handle(Request $request): Response
    {
        return match ($request->input('action')) {
            'view' => $this->view($request),
            'filter' => $this->filter($request),
            'sort' => $this->sort($request),
            'copy_cut' => $this->copyCut($request),
            'clear_clipboard' => $this->clearClipboard(),
            'get_file' => $this->getFile($request),
            default => $this->ok(trans('filemanager::filemanager.no action passed')),
        };
    }

    protected function view(Request $request): Response
    {
        if (! $request->has('type') || $request->input('type') === null || $request->input('type') === '') {
            return $this->ok(trans('filemanager::filemanager.view type number missing'));
        }

        Session::put('filemanager.view_type', (int) $request->input('type'));

        return $this->ok();
    }

    protected function filter(Request $request): Response
    {
        $filter = (string) $request->input('type', '');
        Session::put('filemanager.filter', $filter);

        return $this->ok();
    }

    protected function sort(Request $request): Response
    {
        if ($request->filled('sort_by')) {
            Session::put('filemanager.sort_by', $request->input('sort_by'));
        }

        if ($request->filled('descending')) {
            Session::put('filemanager.descending', $request->boolean('descending'));
        }

        return $this->ok();
    }

    protected function copyCut(Request $request): Response
    {
        $subAction = $request->input('sub_action');

        if (! in_array($subAction, ['copy', 'cut'], true)) {
            return $this->ok(trans('filemanager::filemanager.wrong sub-action'));
        }

        if (! Gate::allows('filemanager.delete', $this->filemanagerContext())) {
            return $this->ok(trans('filemanager::filemanager.File_Permission_Not_Allowed'));
        }

        $path = trim((string) $request->input('path', ''));

        if ($path === '') {
            return $this->ok(trans('filemanager::filemanager.no path'));
        }

        try {
            PathGuard::assertSafe($path);
            PathGuard::assertInsideBaseFolder($path, $this->files->config()->baseFolder());
        } catch (UnsafePathException) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }

        $this->clipboard->set($path, $subAction);

        return $this->ok();
    }

    protected function clearClipboard(): Response
    {
        $this->clipboard->clear();

        return $this->ok();
    }

    protected function getFile(Request $request): Response
    {
        if (! config('filemanager.text_editing_enabled')) {
            return $this->ok(trans('filemanager::filemanager.File_Open_Edit_Not_Allowed'));
        }

        $subAction = $request->input('sub_action');
        if (! in_array($subAction, ['preview', 'edit'], true)) {
            return $this->ok(trans('filemanager::filemanager.wrong action'));
        }

        $path = (string) $request->input('path', $request->query('file', ''));

        try {
            PathGuard::assertSafe($path);
            PathGuard::assertInsideBaseFolder($path, $this->files->config()->baseFolder());
        } catch (UnsafePathException) {
            return $this->ok(trans('filemanager::filemanager.wrong path'));
        }

        if (! $this->files->exists($path)) {
            return $this->ok(trans('filemanager::filemanager.File_Not_Found'));
        }

        $extension = mb_strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! $this->files->config()->isEditableTextExtension($extension)) {
            return $this->ok(trans('filemanager::filemanager.File_Open_Edit_Not_Allowed'));
        }

        $content = e($this->files->get($path));

        return $this->ok('<textarea id="textfile_edit_area" style="width:100%;height:300px;">'.$content.'</textarea>');
    }

    protected function ok(string $body = ''): Response
    {
        return response($body, 200)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
