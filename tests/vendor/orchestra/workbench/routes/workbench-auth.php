<?php

use Illuminate\Support\Facades\Route;

use function Orchestra\Testbench\join_paths;

Route::middleware('web')
    ->group(join_paths(__DIR__, 'web.php'));
