<?php

namespace Yajra\DataTables\Generators;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

class DataTablesHtmlCommand extends DataTablesMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datatables:html
                            {name : The name of the DataTable html.}
                            {--dom= : The dom of the DataTable.}
                            {--buttons= : The buttons of the DataTable.}
                            {--table= : Scaffold columns from the table.}
                            {--builder : Ignore, added to work with parent generator.}
                            {--columns= : The columns of the DataTable.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable html class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTableHtml';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        $this->replaceBuilder($stub)
            ->replaceColumns($stub)
            ->replaceButtons($stub)
            ->replaceDOM($stub)
            ->replaceTableId($stub);

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return config('datatables-buttons.stub')
            ? base_path().config('datatables-buttons.stub').'/html.stub'
            : __DIR__.'/stubs/html.stub';
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     */
    protected function qualifyClass($name): string
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        if (! Str::contains(Str::lower($name), 'datatablehtml')) {
            $name .= 'DataTableHtml';
        } else {
            $name = preg_replace('#datatablehtml$#i', 'DataTableHtml', $name);
        }

        return $this->getDefaultNamespace(trim((string) $rootNamespace, '\\')).'\\'.$name;
    }
}
