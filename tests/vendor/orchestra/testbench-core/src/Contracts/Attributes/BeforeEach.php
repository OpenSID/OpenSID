<?php

namespace Orchestra\Testbench\Contracts\Attributes;

interface BeforeEach extends TestingFeature
{
    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function beforeEach($app);
}
