<?php

namespace Orchestra\Testbench\Contracts\Attributes;

interface BeforeAll extends TestingFeature
{
    /**
     * Handle the attribute.
     *
     * @return void
     */
    public function beforeAll();
}
