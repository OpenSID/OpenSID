<?php

namespace Yajra\DataTables\Generators;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DataTablesMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datatables:make
                            {name : The name of the DataTable.}
                            {--model= : The name of the model to be used.}
                            {--model-namespace= : The namespace of the model to be used.}
                            {--action= : The path of the action view.}
                            {--table= : Scaffold columns from the table.}
                            {--builder : Extract html() to a Builder class.}
                            {--dom= : The dom of the DataTable.}
                            {--buttons= : The buttons of the DataTable.}
                            {--columns= : The columns of the DataTable.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable service class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable';

    public function handle()
    {
        parent::handle();

        if ($this->option('builder')) {
            $columns = config('datatables-buttons.generator.columns', 'id,add your columns,created_at,updated_at');
            $buttons = config('datatables-buttons.generator.buttons', 'create,export,print,reset,reload');
            $dom = config('datatables-buttons.generator.dom', 'Bfrtip');

            $this->call('datatables:html', [
                'name' => $this->getDataTableBaseName(),
                '--columns' => $this->option('columns') ?: $columns,
                '--buttons' => $this->option('buttons') ?: $buttons,
                '--dom' => $this->option('dom') ?: $dom,
                '--table' => $this->option('table'),
            ]);
        }
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        $this->replaceModelImport($stub)
            ->replaceModel($stub)
            ->replaceBuilder($stub)
            ->replaceColumns($stub)
            ->replaceButtons($stub)
            ->replaceDOM($stub)
            ->replaceTableId($stub)
            ->replaceAction($stub)
            ->replaceFilename($stub);

        return $stub;
    }

    /**
     * Get DataTable class base name without the suffix.
     */
    protected function getDataTableBaseName(): string
    {
        return (string) preg_replace('#datatable$#i', '', (string) $this->getNameInput());
    }

    /**
     * Prepare model name from input.
     */
    protected function prepareModelName(): string
    {
        return basename($this->getDataTableBaseName());
    }

    /**
     * Replace the filename.
     *
     * @return $this
     */
    protected function replaceFilename(string &$stub): static
    {
        $stub = str_replace('DummyFilename', $this->prepareModelName(), $stub);

        return $this;
    }

    /**
     * Replace the action.
     */
    protected function replaceAction(string &$stub): static
    {
        $stub = str_replace('DummyAction', $this->getAction(), $stub);

        return $this;
    }

    /**
     * Set the action view to be used.
     */
    protected function getAction(): string
    {
        /** @var string $action */
        $action = $this->option('action');

        if ($action) {
            return $action;
        }

        return Str::lower($this->prepareModelName()).'.action';
    }

    /**
     * Replace columns.
     *
     * @return $this
     */
    protected function replaceTableId(string &$stub): static
    {
        $stub = str_replace('DummyTableId', Str::lower($this->prepareModelName()).'-table', $stub);

        return $this;
    }

    /**
     * Replace dom.
     *
     * @return $this
     */
    protected function replaceDOM(string &$stub): static
    {
        /** @var string $dom */
        $dom = $this->option('dom') ?: config('datatables-buttons.generator.dom', 'Bfrtip');

        $stub = str_replace('DummyDOM', $dom, $stub);

        return $this;
    }

    /**
     * Replace buttons.
     *
     * @return $this
     */
    protected function replaceButtons(string &$stub): static
    {
        $stub = str_replace('DummyButtons', $this->getButtons(), $stub);

        return $this;
    }

    /**
     * Get the columns to be used.
     */
    protected function getButtons(): string
    {
        /** @var string $buttons */
        $buttons = $this->option('buttons');

        if ($buttons) {
            return $this->parseButtons($buttons);
        }

        /** @var string $buttons */
        $buttons = config('datatables-buttons.generator.buttons', 'create,export,print,reset,reload');

        return $this->parseButtons($buttons);
    }

    /**
     * Parse array from definition.
     */
    protected function parseButtons(string $definition, int $indentation = 12): string
    {
        $columns = explode(',', $definition);
        $stub = '';
        foreach ($columns as $key => $column) {
            $indent = '';
            $separator = ',';

            if ($key < count($columns) - 1) {
                $indent = PHP_EOL.str_repeat(' ', $indentation);
            }

            if ($key == count($columns) - 1) {
                $separator = '';
            }

            $stub .= "Button::make('{$column}')".$separator.$indent;
        }

        return $stub;
    }

    /**
     * Replace columns.
     *
     * @return $this
     */
    protected function replaceColumns(string &$stub): static
    {
        $stub = str_replace('DummyColumns', $this->getColumns(), $stub);

        return $this;
    }

    /**
     * Get the columns to be used.
     */
    protected function getColumns(): string
    {
        /** @var string $table */
        $table = $this->option('table');

        if ($table) {
            return $this->parseColumns(Schema::getColumnListing($table));
        }

        /** @var string $columns */
        $columns = $this->option('columns');

        if ($columns) {
            return $this->parseColumns($columns);
        }

        /** @var string $columns */
        $columns = config('datatables-buttons.generator.columns', 'id,add your columns,created_at,updated_at');

        return $this->parseColumns($columns);
    }

    /**
     * Parse array from definition.
     */
    protected function parseColumns(array|string $definition, int $indentation = 12): string
    {
        $columns = is_array($definition) ? $definition : explode(',', $definition);
        $stub = '';
        foreach ($columns as $key => $column) {
            $stub .= "Column::make('{$column}'),";

            if ($key < count($columns) - 1) {
                $stub .= PHP_EOL.str_repeat(' ', $indentation);
            }
        }

        return $stub;
    }

    /**
     * Replace builder name.
     *
     * @param  string  $stub
     * @return DataTablesMakeCommand
     */
    protected function replaceBuilder(&$stub)
    {
        $name = $this->qualifyClass($this->getNameInput());
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $stub = str_replace('DummyBuilder', $class.'Html', $stub);

        return $this;
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        if (! Str::contains(Str::lower($name), 'datatable')) {
            $name .= 'DataTable';
        } else {
            $name = preg_replace('#datatable$#i', 'DataTable', $name);
        }

        return $this->getDefaultNamespace(trim((string) $rootNamespace, '\\')).'\\'.$name;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\'.config('datatables-buttons.namespace.base', 'DataTables');
    }

    /**
     * Replace model name.
     */
    protected function replaceModel(string &$stub): static
    {
        $model = explode('\\', $this->getModel());
        $model = array_pop($model);
        $stub = str_replace('ModelName', $model, $stub);

        return $this;
    }

    /**
     * Get model name to use.
     */
    protected function getModel(): string
    {
        /** @var string $modelFromOption */
        $modelFromOption = $this->option('model');
        $modelNamespaceFromOption = $this->option('model-namespace') ?: config('datatables-buttons.namespace.model');

        $name = $modelFromOption ?: $this->prepareModelName();
        $modelNamespace = $modelNamespaceFromOption ?: $this->laravel->getNamespace();

        if (empty($modelNamespaceFromOption) && is_dir(app_path('Models'))) {
            $modelNamespace = $modelNamespace.'\\Models\\';
        }

        return $modelNamespace.'\\'.Str::singular($name);
    }

    /**
     * Replace model import.
     *
     * @return $this
     */
    protected function replaceModelImport(string &$stub): static
    {
        $stub = str_replace('DummyModel', str_replace('\\\\', '\\', $this->getModel()), $stub);

        return $this;
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        $stub = 'datatables.stub';

        if ($this->option('builder')) {
            $stub = 'builder.stub';
        }

        return config('datatables-buttons.stub')
            ? base_path().config('datatables-buttons.stub')."/$stub"
            : __DIR__."/stubs/{$stub}";
    }
}
