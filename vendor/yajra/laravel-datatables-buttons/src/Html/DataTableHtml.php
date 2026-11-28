<?php

namespace Yajra\DataTables\Html;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionClass;
use ReflectionFunction;
use Yajra\DataTables\Contracts\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Editor\Editor;

abstract class DataTableHtml implements DataTableHtmlBuilder
{
    use ForwardsCalls;

    protected ?Builder $htmlBuilder = null;

    protected string $tableId = 'dataTable';

    public static function make(): Builder
    {
        $arguments = func_get_args();

        /** @var static $html */
        $html = self::hasVariadicConstructor()
            ? self::resolveVariadic($arguments)
            : app(static::class, self::normalizeParameters($arguments));

        return $html->handle();
    }

    private static function hasVariadicConstructor(): bool
    {
        foreach (self::constructorParameters() as $parameter) {
            if ($parameter->isVariadic()) {
                return true;
            }
        }

        return false;
    }

    private static function resolveVariadic(array $arguments): static
    {
        $container = app();
        $reflection = new ReflectionClass(self::concreteClass());
        $resolver = fn (): object => $reflection->newInstanceArgs($arguments);
        $abstract = static::class.'@'.spl_object_hash($resolver);

        $container->bind($abstract, $resolver);

        try {
            /** @var static */
            return $container->make($abstract);
        } finally {
            unset($container[$abstract]);
        }
    }

    private static function normalizeParameters(array $arguments): array
    {
        $constructorParameters = self::constructorParameters();
        $parameters = [];

        foreach ($arguments as $index => $argument) {
            if (! isset($constructorParameters[$index])) {
                break;
            }

            $parameters[$constructorParameters[$index]->getName()] = $argument;
        }

        return $parameters;
    }

    private static function constructorParameters(): array
    {
        return (new ReflectionClass(self::concreteClass()))->getConstructor()?->getParameters() ?? [];
    }

    private static function concreteClass(): string
    {
        $binding = app()->getBindings()[static::class]['concrete'] ?? null;
        $concrete = $binding instanceof Closure
            ? (new ReflectionFunction($binding))->getStaticVariables()['concrete'] ?? static::class
            : static::class;

        return is_string($concrete) && is_a($concrete, static::class, true)
            ? $concrete
            : static::class;
    }

    /**
     * @return Builder
     *
     * @throws \Exception
     */
    public function __call(string $method, mixed $parameters)
    {
        return $this->forwardCallTo($this->htmlBuilder ?? $this->getHtmlBuilder(), $method, $parameters);
    }

    protected function getHtmlBuilder(): Builder
    {
        if ($this->htmlBuilder) {
            return $this->htmlBuilder;
        }

        $this->htmlBuilder = app(Builder::class);

        $this->htmlBuilder
            ->postAjax($this->ajax())
            ->setTableId($this->tableId)
            ->selectSelector()
            ->selectStyleOs()
            ->addScript('datatables::functions.batch_remove');

        $this->options($this->htmlBuilder);

        if ($this->buttons()) {
            $this->htmlBuilder->buttons($this->buttons());
        }

        if ($this->columns()) {
            $this->htmlBuilder->columns($this->columns());
        }

        if ($this->editors()) {
            $this->htmlBuilder->editors($this->editors());
        }

        return $this->htmlBuilder;
    }

    public function handle(): Builder
    {
        return $this->getHtmlBuilder();
    }

    public function setHtmlBuilder(Builder $builder): static
    {
        $this->htmlBuilder = $builder;

        return $this;
    }

    /**
     * @return array{url: string, data: array<string, string>}|string
     */
    public function ajax(): array|string
    {
        return Request::url();
    }

    public function options(Builder $builder): void {}

    /**
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [];
    }

    /**
     * @return array<int, Button>
     */
    public function buttons(): array
    {
        return [];
    }

    /**
     * @return array<int, Editor>
     */
    public function editors(): array
    {
        return [];
    }
}
