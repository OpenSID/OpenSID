<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Closure;
use Orchestra\Testbench\Contracts\Attributes\Actionable as ActionableContract;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class DefineRoute implements ActionableContract
{
    /**
     * Construct a new attribute.
     *
     * @param  string  $method
     */
    public function __construct(
        public string $method
    ) {}

    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Closure(string, array<int, mixed>):void  $action
     */
    public function handle($app, Closure $action): void
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $app->make('router');

        \call_user_func($action, $this->method, [$router]);
    }
}
