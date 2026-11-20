<?php

namespace OpenSID\LaravelFilemanager\Support;

use Illuminate\Support\Facades\Session;

/**
 * Reads the calling module's slug that DialogController stashed in the
 * session (see its resolveContext()) so ajax_calls.php/execute.php/
 * upload.php — which never receive ?modul=... directly — can still pass
 * the right context into Gate::allows('filemanager.*', $context) checks.
 */
trait ResolvesFilemanagerContext
{
    protected function filemanagerContext(): ?string
    {
        return Session::get('filemanager.context');
    }
}
