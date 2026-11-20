<?php

namespace Tests;

use App\Bootstrap\CodeIgniterBootstrap;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Reset the CodeIgniter pre_system flag before the request is dispatched.
     *
     * In the normal lifecycle, a process only handles a single request, so the flag
     * never leaks to subsequent requests. In the testing lifecycle, a single process
     * handles multiple requests (the console kernel during setUp and the HTTP kernel
     * during each request), so the flag must be reset for every request.
     */
    protected function createTestRequest($symfonyRequest)
    {
        CodeIgniterBootstrap::$preSystemCalled = false;

        return parent::createTestRequest($symfonyRequest);
    }
}
