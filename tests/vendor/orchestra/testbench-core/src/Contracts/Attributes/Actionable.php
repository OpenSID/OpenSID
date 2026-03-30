<?php

namespace Orchestra\Testbench\Contracts\Attributes;

use Closure;

interface Actionable extends TestingFeature
{
    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Closure(string, array<int, mixed>):void  $action
     * @return mixed
     */
    public function handle($app, Closure $action);
}
