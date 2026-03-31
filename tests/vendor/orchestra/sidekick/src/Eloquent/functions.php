<?php

namespace Orchestra\Sidekick\Eloquent;

use BackedEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use JsonSerializable;
use Orchestra\Sidekick\SensitiveValue;
use Stringable;
use Throwable;

if (! \function_exists('Orchestra\Sidekick\Eloquent\column_name')) {
    /**
     * Get qualify column name from Eloquent model.
     *
     * @api
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function column_name(Model|string $model, string $attribute): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        return $model->qualifyColumn($attribute);
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\is_pivot_model')) {
    /**
     * Determine if the given model is a pivot model.
     *
     * @api
     *
     * @template TPivotModel of (\Illuminate\Database\Eloquent\Model&\Illuminate\Database\Eloquent\Relations\Concerns\AsPivot)|\Illuminate\Database\Eloquent\Relations\Pivot
     *
     * @param  TPivotModel|class-string<TPivotModel>  $model
     *
     * @throws \InvalidArgumentException
     */
    function is_pivot_model(Pivot|Model|string $model): bool
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s|%s].', Model::class, Pivot::class));
        }

        if ($model instanceof Pivot) {
            return true;
        }

        return \in_array(AsPivot::class, class_uses_recursive($model), true);
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_exists')) {
    /**
     * Check whether given $model exists.
     *
     * @api
     */
    function model_exists(mixed $model): bool
    {
        return $model instanceof Model && $model->exists === true;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_from')) {
    /**
     * Check whether given $model exists.
     *
     * @api
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  array<string, mixed>  $attributes
     */
    function model_from(Model|string $model, array $attributes): Model
    {
        return $model::unguarded(function () use ($model, $attributes) {
            if (\is_string($model)) {
                $model = new $model;
            }

            return $model->newInstance()->setRawAttributes($attributes);
        });
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_key_type')) {
    /**
     * Check whether given $model key type.
     *
     * @api
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function model_key_type(Model|string $model): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        $uses = class_uses_recursive($model);

        if (\in_array(HasUlids::class, $uses, true)) {
            return 'ulid';
        } elseif (\in_array(HasUuids::class, $uses, true)) {
            return 'uuid';
        }

        return $model->getKeyType();
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_diff')) {
    /**
     * Get attributes diff state from a model.
     *
     * @api
     *
     * @param  array<int, string>  $excludes
     * @return array<string, mixed>
     */
    function model_diff(Model $model, array $excludes = [], bool $withTimestamps = true): array
    {
        $hiddens = $model->getHidden();
        $excludedAttributes = array_merge($excludes, $model->getAppends());
        $timestamps = [$model->getCreatedAtColumn(), $model->getUpdatedAtColumn()];

        if (! model_exists($model) || $model->wasRecentlyCreated === true) {
            $rawChanges = $model->getAttributes();

            $changes = model_from($model, $rawChanges)->setHidden($excludedAttributes)->attributesToArray();

            return Arr::except(
                summarize_changes($changes, hiddens: $hiddens),
                $withTimestamps === false ? $timestamps : [$model->getUpdatedAtColumn()]
            );
        }

        $rawChanges = $model->isDirty() ? $model->getDirty() : $model->getChanges();

        $changes = array_intersect_key(
            model_from($model, $rawChanges)->setHidden($excludedAttributes)->setAppends([])->attributesToArray(),
            $rawChanges
        );

        return Arr::except(
            summarize_changes($changes, hiddens: $hiddens),
            $withTimestamps === false ? $timestamps : []
        );
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_snapshot')) {
    /**
     * Store a snapshot for a model and return the original attributes.
     *
     * @api
     *
     * @return array<string, mixed>|null
     */
    function model_snapshot(Model $model): ?array
    {
        return Watcher::snapshot($model);
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\model_state')) {
    /**
     * Get attributes original and changed state from a model.
     *
     * @api
     *
     * @param  array<int, string>  $excludes
     * @return array{0: array<string, mixed>|null, 1: array<string, mixed>}
     */
    function model_state(Model $model, array $excludes = [], bool $withTimestamps = true): array
    {
        $changes = model_diff($model, $excludes, $withTimestamps);
        $excludedAttributes = array_merge($excludes, $model->getAppends());

        if (! model_exists($model) || $model->wasRecentlyCreated == true) {
            return [null, $changes];
        }

        /** @phpstan-ignore function.alreadyNarrowedType */
        $previous = method_exists($model, 'getPrevious') ? $model->getPrevious() : null;

        if (empty($previous)) {
            $previous = Watcher::snapshot($model);
        }

        $original = summarize_changes(
            array_intersect_key(model_from($model, $previous ?? [])->setHidden($excludedAttributes)->setAppends([])->attributesToArray(), $changes),
            hiddens: $model->getHidden(),
        );

        return [$original, $changes];
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\normalize_value')) {
    /**
     * Normalize the given value to be store to database as scalar.
     *
     * @api
     *
     * @return scalar
     */
    function normalize_value(mixed $value): mixed
    {
        $value = match (true) {
            $value instanceof CarbonInterface => $value->toISOString(),
            $value instanceof JsonSerializable => $value->jsonSerialize(),
            $value instanceof BackedEnum => $value->value,
            default => $value,
        };

        if (\is_object($value) && $value instanceof Stringable) {
            return (string) $value;
        } elseif (\is_object($value) || \is_array($value)) {
            try {
                return json_encode($value);
            } catch (Throwable $e) { // @phpstan-ignore catch.neverThrown
                return $value;
            }
        }

        return $value;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\summarize_changes')) {
    /**
     * Get table name from Eloquent model.
     *
     * @api
     *
     * @param  array<string, mixed>  $changes
     * @param  array<int, string>  $hiddens
     * @return array<string, \Orchestra\Sidekick\SensitiveValue|scalar>
     */
    function summarize_changes(array $changes, array $hiddens = []): array
    {
        $summaries = [];

        foreach ($changes as $attribute => $value) {
            $summaries[$attribute] = \in_array($attribute, $hiddens, true)
                ? new SensitiveValue($value)
                : normalize_value($value);
        }

        return $summaries;
    }
}

if (! \function_exists('Orchestra\Sidekick\Eloquent\table_name')) {
    /**
     * Get table name from Eloquent model.
     *
     * @api
     *
     * @param  \Illuminate\Database\Eloquent\Model|class-string<\Illuminate\Database\Eloquent\Model>  $model
     *
     * @throws \InvalidArgumentException
     */
    function table_name(Model|string $model): string
    {
        if (\is_string($model)) {
            $model = new $model;
        }

        if (! $model instanceof Model) {
            throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
        }

        return $model->getTable();
    }
}
