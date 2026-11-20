<?php

namespace Yajra\DataTables\Html;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Html\Editor\Fields\Options;

class SearchPane extends Fluent
{
    public function __construct($attributes = [])
    {
        parent::__construct(['show' => true] + $attributes);
    }

    public static function make(array $options = []): static
    {
        return new static($options);
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.cascadePanes
     */
    public function cascadePanes(bool $value = true): static
    {
        $this->attributes['cascadePanes'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.clear
     */
    public function clear(bool $value = true): static
    {
        $this->attributes['clear'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.columns
     */
    public function columns(array $value = []): static
    {
        $this->attributes['columns'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.controls
     */
    public function controls(bool $value = true): static
    {
        $this->attributes['controls'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.dtOpts
     * @see https://datatables.net/reference/option/columns.searchPanes.dtOpts
     */
    public function dtOpts(array $value = []): static
    {
        $this->attributes['dtOpts'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.emptyMessage
     */
    public function emptyMessage(string $value): static
    {
        $this->attributes['emptyMessage'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.filterChanged
     */
    public function filterChanged(string $value): static
    {
        $this->attributes['filterChanged'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.hideCount
     */
    public function hideCount(bool $value = true): static
    {
        $this->attributes['hideCount'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.layout
     */
    public function layout(string $value): static
    {
        $this->attributes['layout'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.order
     */
    public function order(array $value): static
    {
        $this->attributes['order'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.orderable
     */
    public function orderable(bool $value = true): static
    {
        $this->attributes['orderable'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.panes
     */
    public function panes(array $value): static
    {
        $panes = collect($value)->map(function ($pane) {
            if ($pane instanceof Arrayable) {
                return $pane->toArray();
            }

            return $pane;
        })->toArray();

        $this->attributes['panes'] = $panes;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.threshold
     */
    public function threshold(float $value): static
    {
        $this->attributes['threshold'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.viewTotal
     */
    public function viewTotal(bool $value = true): static
    {
        $this->attributes['viewTotal'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.viewTotal
     */
    public function hideTotal(bool $value = true): static
    {
        $this->attributes['viewTotal'] = ! $value;

        return $this;
    }

    /**
     * Get options from a model.
     *
     * @param  class-string<Model>|EloquentBuilder  $model
     */
    public function modelOptions(EloquentBuilder|string $model, string $value, string $key = 'id'): SearchPane
    {
        return $this->options(Options::model($model, $value, $key));
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchPanes.options
     */
    public function options(array|Arrayable $value): static
    {
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        $this->attributes['options'] = $value;

        return $this;
    }

    /**
     * Get options from a table.
     *
     * @return $this
     */
    public function tableOptions(
        string $table,
        string $value,
        string $key = 'id',
        ?Closure $callback = null,
        ?string $connection = null
    ): static {
        return $this->options(Options::table($table, $value, $key, $callback, $connection));
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchPanes.className
     */
    public function className(string $value): static
    {
        $this->attributes['className'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.panes.header
     */
    public function header(string $value): static
    {
        $this->attributes['header'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchPanes.show
     */
    public function show(bool $value = true): static
    {
        $this->attributes['show'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchPanes.name
     */
    public function name(string $value): static
    {
        $this->attributes['name'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchPanes.orthogonal
     */
    public function orthogonal(array|string $value): static
    {
        $this->attributes['orthogonal'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.collapse
     */
    public function collapse(bool $value = true): static
    {
        $this->attributes['collapse'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchPanes.initCollapsed
     */
    public function initCollapsed(bool $value = false): static
    {
        $this->attributes['initCollapsed'] = $value;

        return $this;
    }
}
