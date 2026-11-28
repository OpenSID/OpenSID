<?php

namespace Yajra\DataTables\Generators;

use Illuminate\Console\GeneratorCommand;

class DataTablesScopeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'datatables:scope';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable Scope class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable Scope';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\DataTables\Scopes';
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        if ($stubFolder = config('datatables-buttons.stub')) {
            return base_path($stubFolder.'/scopes.stub');
        }

        return __DIR__.'/stubs/scopes.stub';
    }
}
