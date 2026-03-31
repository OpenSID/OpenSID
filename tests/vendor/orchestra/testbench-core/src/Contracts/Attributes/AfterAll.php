<?php

namespace Orchestra\Testbench\Contracts\Attributes;

interface AfterAll extends TestingFeature
{
    /**
     * Handle the attribute.
     *
     * @return void
     */
    public function afterAll();
}
