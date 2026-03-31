<?php

namespace Orchestra\Testbench\Contracts\Attributes;

interface Resolvable
{
    /**
     * Resolve the actual attribute class.
     *
     * @return mixed
     */
    public function resolve();
}
