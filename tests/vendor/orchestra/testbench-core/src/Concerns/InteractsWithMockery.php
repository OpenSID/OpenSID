<?php

namespace Orchestra\Testbench\Concerns;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

trait InteractsWithMockery
{
    /**
     * Teardown the testing environment.
     *
     * @return void
     */
    protected function tearDownTheTestEnvironmentUsingMockery(): void
    {
        if (class_exists(Mockery::class) && $this instanceof PHPUnitTestCase) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }
    }
}
