<?php

namespace Yajra\DataTables\Html\Editor\Fields;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @template TKey of array-key
 * @template TValue
 */
class Options extends Collection
{
    /**
     * Return a Yes/No options.
     */
    public static function yesNo(): static
    {
        $data = [
            ['label' => __('Yes'), 'value' => 1],
            ['label' => __('No'), 'value' => 0],
        ];

        return new static($data);
    }

    /**
     * Create options from an array.
     *
     * @param  array<array-key, string|int|float|array{label: string, value: mixed}>  $data
     */
    public static function from(array $data): static
    {
        $items = [];
        foreach ($data as $key => $value) {
            $items[] = is_array($value) ? $value : ['label' => $value, 'value' => $key];
        }

        return new static($items);
    }

    /**
     * Get options from a model.
     *
     * @param  class-string<Model>|\Illuminate\Database\Eloquent\Builder  $model
     */
    public static function model(string|EloquentBuilder $model, string $value, string $key = 'id'): Collection
    {
        if (! $model instanceof EloquentBuilder) {
            $model = $model::query();
        }

        return $model->get()->map(fn ($model) => [
            'value' => $model->{$key},
            'label' => $model->{$value},
        ]);
    }

    /**
     * Get options from a table.
     */
    public static function table(
        Closure|Builder|string $table,
        string $value,
        string $key = 'id',
        ?Closure $callback = null,
        ?string $connection = null
    ): Collection {
        $query = DB::connection($connection)
            ->table($table)
            ->select("$value as label", "$key as value");

        if (is_callable($callback)) {
            $callback($query);
        }

        return $query->get()->map(fn ($row) => (array) $row);
    }

    /**
     * Return a True/False options.
     */
    public static function trueFalse(): static
    {
        $data = [
            ['label' => __('True'), 'value' => 1],
            ['label' => __('False'), 'value' => 0],
        ];

        return new static($data);
    }

    /**
     * Push an item onto the end of the collection.
     */
    public function append(string $value, int|string $key): static
    {
        return $this->push(['label' => $value, 'value' => $key]);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  TValue  $value
     * @param  TKey  $key
     */
    public function prepend($value, $key = null): static
    {
        $data = ['label' => $value, 'value' => $key];

        return parent::prepend($data);
    }
}
