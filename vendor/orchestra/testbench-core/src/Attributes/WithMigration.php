<?php

namespace Orchestra\Testbench\Attributes;

use Attribute;
use Illuminate\Support\Collection;
use Orchestra\Testbench\Contracts\Attributes\Invokable as InvokableContract;

use function Orchestra\Testbench\default_migration_path;
use function Orchestra\Testbench\load_migration_paths;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class WithMigration implements InvokableContract
{
    /**
     * The target types.
     *
     * @var array<int, string>
     */
    public array $types = [];

    /**
     * Construct a new attribute.
     *
     * @no-named-arguments
     */
    public function __construct()
    {
        $this->types = \func_num_args() > 0 ? \func_get_args() : ['laravel'];
    }

    /**
     * Handle the attribute.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __invoke($app): void
    {
        /** @var array<int, string> $types */
        $types = (new Collection($this->types))
            ->transform(static fn ($type) => default_migration_path($type !== 'laravel' ? $type : null))
            ->all();

        load_migration_paths($app, $types);
    }
}
