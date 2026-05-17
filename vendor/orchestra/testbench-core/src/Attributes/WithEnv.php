<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Closure;
use Orchestra\Sidekick\Env;
use Orchestra\Sidekick\UndefinedValue;
use Orchestra\Testbench\Contracts\Attributes\Invokable as InvokableContract;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class WithEnv implements InvokableContract
{
    /**
     * Construct a new attribute.
     *
     * @param  string  $key
     * @param  string|null  $value
     */
    public function __construct(
        public string $key,
        public ?string $value
    ) {}

    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return \Closure():void
     */
    public function __invoke($app): Closure
    {
        $key = $this->key;
        $value = Env::get($key, new UndefinedValue);

        Env::set($key, $this->value ?? '(null)');

        return static function () use ($key, $value) {
            if ($value instanceof UndefinedValue) {
                Env::forget($key);
            } else {
                Env::set($key, Env::encode($value));
            }
        };
    }
}
