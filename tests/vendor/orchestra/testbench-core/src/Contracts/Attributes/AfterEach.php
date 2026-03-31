<?php

namespace Orchestra\Testbench\Contracts\Attributes;

interface AfterEach extends TestingFeature
{
    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function afterEach($app);
}
