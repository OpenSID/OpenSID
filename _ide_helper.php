<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 13.15.0.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */

namespace  {

    /**
     * @template TCollection of static
     * @template TModel of static
     * @template TValue of static
     * @template TValue of static
     */
    class Eloquent extends \Illuminate\Database\Eloquent\Model {        /**
         * Create and return an un-saved model instance.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function make($attributes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->make($attributes);
        }

        /**
         * Register a new global scope.
         *
         * @param string $identifier
         * @param \Illuminate\Database\Eloquent\Scope|\Closure $scope
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withGlobalScope($identifier, $scope)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withGlobalScope($identifier, $scope);
        }

        /**
         * Remove a registered global scope.
         *
         * @param \Illuminate\Database\Eloquent\Scope|string $scope
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withoutGlobalScope($scope)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withoutGlobalScope($scope);
        }

        /**
         * Remove all or passed registered global scopes.
         *
         * @param array|null $scopes
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withoutGlobalScopes($scopes = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withoutGlobalScopes($scopes);
        }

        /**
         * Remove all global scopes except the given scopes.
         *
         * @param array $scopes
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withoutGlobalScopesExcept($scopes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withoutGlobalScopesExcept($scopes);
        }

        /**
         * Get an array of global scopes that were removed from the query.
         *
         * @return array
         * @static
         */
        public static function removedScopes()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->removedScopes();
        }

        /**
         * Add a where clause on the primary key to the query.
         *
         * @param mixed $id
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereKey($id)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereKey($id);
        }

        /**
         * Add a where clause on the primary key to the query.
         *
         * @param mixed $id
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereKeyNot($id)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereKeyNot($id);
        }

        /**
         * Add a basic where clause to the query.
         *
         * @param (\Closure(static): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function where($column, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->where($column, $operator, $value, $boolean);
        }

        /**
         * Add a basic where clause to the query, and return the first result.
         *
         * @param (\Closure(static): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return TModel|null
         * @static
         */
        public static function firstWhere($column, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->firstWhere($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where" clause to the query.
         *
         * @param (\Closure(static): mixed)|array|string|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhere($column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhere($column, $operator, $value);
        }

        /**
         * Add a basic "where not" clause to the query.
         *
         * @param (\Closure(static): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNot($column, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereNot($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where not" clause to the query.
         *
         * @param (\Closure(static): mixed)|array|string|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNot($column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereNot($column, $operator, $value);
        }

        /**
         * Add an "order by" clause for a timestamp to the query.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function latest($column = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->latest($column);
        }

        /**
         * Add an "order by" clause for a timestamp to the query.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function oldest($column = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->oldest($column);
        }

        /**
         * Create a collection of models from plain arrays.
         *
         * @param array $items
         * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
         * @static
         */
        public static function hydrate($items)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->hydrate($items);
        }

        /**
         * Insert into the database after merging the model's default attributes, setting timestamps, and casting values.
         *
         * @param array<int, array<string, mixed>> $values
         * @return bool
         * @static
         */
        public static function fillAndInsert($values)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->fillAndInsert($values);
        }

        /**
         * Insert (ignoring errors) into the database after merging the model's default attributes, setting timestamps, and casting values.
         *
         * @param array<int, array<string, mixed>> $values
         * @return int
         * @static
         */
        public static function fillAndInsertOrIgnore($values)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->fillAndInsertOrIgnore($values);
        }

        /**
         * Insert a record into the database and get its ID after merging the model's default attributes, setting timestamps, and casting values.
         *
         * @param array<string, mixed> $values
         * @return int
         * @static
         */
        public static function fillAndInsertGetId($values)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->fillAndInsertGetId($values);
        }

        /**
         * Enrich the given values by merging in the model's default attributes, adding timestamps, and casting values.
         *
         * @param array<int, array<string, mixed>> $values
         * @return array<int, array<string, mixed>>
         * @static
         */
        public static function fillForInsert($values)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->fillForInsert($values);
        }

        /**
         * Create a collection of models from a raw query.
         *
         * @param string $query
         * @param array $bindings
         * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
         * @static
         */
        public static function fromQuery($query, $bindings = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->fromQuery($query, $bindings);
        }

        /**
         * Find a model by its primary key.
         *
         * @param mixed $id
         * @param array|string $columns
         * @return ($id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>) ? \Illuminate\Database\Eloquent\Collection<int, TModel> : TModel|null)
         * @static
         */
        public static function find($id, $columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->find($id, $columns);
        }

        /**
         * Find a sole model by its primary key.
         *
         * @param mixed $id
         * @param array|string $columns
         * @return TModel
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @throws \Illuminate\Database\MultipleRecordsFoundException
         * @static
         */
        public static function findSole($id, $columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->findSole($id, $columns);
        }

        /**
         * Find multiple models by their primary keys.
         *
         * @param \Illuminate\Contracts\Support\Arrayable|array $ids
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
         * @static
         */
        public static function findMany($ids, $columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->findMany($ids, $columns);
        }

        /**
         * Find a model by its primary key or throw an exception.
         *
         * @param mixed $id
         * @param array|string $columns
         * @return ($id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>) ? \Illuminate\Database\Eloquent\Collection<int, TModel> : TModel)
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @static
         */
        public static function findOrFail($id, $columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->findOrFail($id, $columns);
        }

        /**
         * Find a model by its primary key or return fresh model instance.
         *
         * @param mixed $id
         * @param array|string $columns
         * @return ($id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>) ? \Illuminate\Database\Eloquent\Collection<int, TModel> : TModel)
         * @static
         */
        public static function findOrNew($id, $columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->findOrNew($id, $columns);
        }

        /**
         * Find a model by its primary key or call a callback.
         *
         * @template TValue
         * @param mixed $id
         * @param (\Closure(): TValue)|list<string>|string $columns
         * @param (\Closure(): TValue)|null $callback
         * @return ( $id is (\Illuminate\Contracts\Support\Arrayable<array-key, mixed>|array<mixed>)
         *     ? \Illuminate\Database\Eloquent\Collection<int, TModel>
         *     : TModel|TValue
         * )
         * @static
         */
        public static function findOr($id, $columns = [], $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->findOr($id, $columns, $callback);
        }

        /**
         * Get the first record matching the attributes or instantiate it.
         *
         * @param array $attributes
         * @param (\Closure(): array)|array $values
         * @return TModel
         * @static
         */
        public static function firstOrNew($attributes = [], $values = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->firstOrNew($attributes, $values);
        }

        /**
         * Get the first record matching the attributes. If the record is not found, create it.
         *
         * @param array $attributes
         * @param (\Closure(): array)|array $values
         * @return TModel
         * @static
         */
        public static function firstOrCreate($attributes = [], $values = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->firstOrCreate($attributes, $values);
        }

        /**
         * Attempt to create the record. If a unique constraint violation occurs, attempt to find the matching record.
         *
         * @param array $attributes
         * @param (\Closure(): array)|array $values
         * @return TModel
         * @throws \Illuminate\Database\UniqueConstraintViolationException
         * @static
         */
        public static function createOrFirst($attributes = [], $values = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->createOrFirst($attributes, $values);
        }

        /**
         * Create or update a record matching the attributes, and fill it with values.
         *
         * @param array $attributes
         * @param (\Closure(): array)|array $values
         * @return TModel
         * @static
         */
        public static function updateOrCreate($attributes, $values = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->updateOrCreate($attributes, $values);
        }

        /**
         * Create a record matching the attributes, or increment the existing record.
         *
         * @param array $attributes
         * @param string $column
         * @param int|float $default
         * @param int|float $step
         * @param array $extra
         * @return TModel
         * @static
         */
        public static function incrementOrCreate($attributes, $column = 'count', $default = 1, $step = 1, $extra = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->incrementOrCreate($attributes, $column, $default, $step, $extra);
        }

        /**
         * Execute the query and get the first result or throw an exception.
         *
         * @param array|string $columns
         * @return TModel
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @static
         */
        public static function firstOrFail($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->firstOrFail($columns);
        }

        /**
         * Execute the query and get the first result or call a callback.
         *
         * @template TValue
         * @param (\Closure(): TValue)|list<string> $columns
         * @param (\Closure(): TValue)|null $callback
         * @return TModel|TValue
         * @static
         */
        public static function firstOr($columns = [], $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->firstOr($columns, $callback);
        }

        /**
         * Execute the query and get the first result if it's the sole matching record.
         *
         * @param array|string $columns
         * @return TModel
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @throws \Illuminate\Database\MultipleRecordsFoundException
         * @static
         */
        public static function sole($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->sole($columns);
        }

        /**
         * Get a single column's value from the first result of a query.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @return mixed
         * @static
         */
        public static function value($column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->value($column);
        }

        /**
         * Get a single column's value from the first result of a query if it's the sole matching record.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @return mixed
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @throws \Illuminate\Database\MultipleRecordsFoundException
         * @static
         */
        public static function soleValue($column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->soleValue($column);
        }

        /**
         * Get a single column's value from the first result of the query or throw an exception.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @return mixed
         * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<TModel>
         * @static
         */
        public static function valueOrFail($column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->valueOrFail($column);
        }

        /**
         * Execute the query as a "select" statement.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Collection<int, TModel>
         * @static
         */
        public static function get($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->get($columns);
        }

        /**
         * Get the hydrated models without eager loading.
         *
         * @param array|string $columns
         * @return array<int, TModel>
         * @static
         */
        public static function getModels($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getModels($columns);
        }

        /**
         * Eager load the relationships for the models.
         *
         * @param array<int, TModel> $models
         * @return array<int, TModel>
         * @static
         */
        public static function eagerLoadRelations($models)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->eagerLoadRelations($models);
        }

        /**
         * Register a closure to be invoked after the query is executed.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function afterQuery($callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->afterQuery($callback);
        }

        /**
         * Invoke the "after query" modification callbacks.
         *
         * @param mixed $result
         * @return mixed
         * @static
         */
        public static function applyAfterQueryCallbacks($result)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->applyAfterQueryCallbacks($result);
        }

        /**
         * Get a lazy collection for the given query.
         *
         * @return \Illuminate\Support\LazyCollection<int, TModel>
         * @static
         */
        public static function cursor()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->cursor();
        }

        /**
         * Get a collection with the values of a given column.
         *
         * @param string|\Illuminate\Contracts\Database\Query\Expression $column
         * @param string|null $key
         * @return \Illuminate\Support\Collection<array-key, mixed>
         * @static
         */
        public static function pluck($column, $key = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->pluck($column, $key);
        }

        /**
         * Paginate the given query.
         *
         * @param int|null|\Closure $perPage
         * @param array|string $columns
         * @param string $pageName
         * @param int|null $page
         * @param \Closure|int|null $total
         * @return \Illuminate\Pagination\LengthAwarePaginator<int, TModel>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function paginate($perPage = null, $columns = [], $pageName = 'page', $page = null, $total = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->paginate($perPage, $columns, $pageName, $page, $total);
        }

        /**
         * Paginate the given query into a simple paginator.
         *
         * @param int|null $perPage
         * @param array|string $columns
         * @param string $pageName
         * @param int|null $page
         * @return \Illuminate\Pagination\Paginator<int, TModel>
         * @static
         */
        public static function simplePaginate($perPage = null, $columns = [], $pageName = 'page', $page = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->simplePaginate($perPage, $columns, $pageName, $page);
        }

        /**
         * Paginate the given query into a cursor paginator.
         *
         * @param int|null $perPage
         * @param array|string $columns
         * @param string $cursorName
         * @param \Illuminate\Pagination\Cursor|string|null $cursor
         * @return \Illuminate\Pagination\CursorPaginator<int, TModel>
         * @static
         */
        public static function cursorPaginate($perPage = null, $columns = [], $cursorName = 'cursor', $cursor = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->cursorPaginate($perPage, $columns, $cursorName, $cursor);
        }

        /**
         * Save a new model and return the instance.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function create($attributes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->create($attributes);
        }

        /**
         * Save a new model and return the instance without raising model events.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function createQuietly($attributes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->createQuietly($attributes);
        }

        /**
         * Save a new model and return the instance. Allow mass-assignment.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function forceCreate($attributes)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->forceCreate($attributes);
        }

        /**
         * Save a new model instance with mass assignment without raising model events.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function forceCreateQuietly($attributes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->forceCreateQuietly($attributes);
        }

        /**
         * Insert new records or update the existing ones.
         *
         * @param array $values
         * @param array|string $uniqueBy
         * @param array|null $update
         * @return int
         * @static
         */
        public static function upsert($values, $uniqueBy, $update = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->upsert($values, $uniqueBy, $update);
        }

        /**
         * Increment the given column's values by the given amounts.
         *
         * @param array<string, float|int|numeric-string> $columns
         * @param array<string, mixed> $extra
         * @return int
         * @static
         */
        public static function incrementEach($columns, $extra = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->incrementEach($columns, $extra);
        }

        /**
         * Decrement the given column's values by the given amounts.
         *
         * @param array<string, float|int|numeric-string> $columns
         * @param array<string, mixed> $extra
         * @return int
         * @static
         */
        public static function decrementEach($columns, $extra = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->decrementEach($columns, $extra);
        }

        /**
         * Register a replacement for the default delete function.
         *
         * @param \Closure $callback
         * @return void
         * @static
         */
        public static function onDelete($callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            $instance->onDelete($callback);
        }

        /**
         * Call the given local model scopes.
         *
         * @param array|string $scopes
         * @return static|mixed
         * @static
         */
        public static function scopes($scopes)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->scopes($scopes);
        }

        /**
         * Apply the scopes to the Eloquent builder instance and return it.
         *
         * @return static
         * @static
         */
        public static function applyScopes()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->applyScopes();
        }

        /**
         * Prevent the specified relations from being eager loaded.
         *
         * @param mixed $relations
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function without($relations)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->without($relations);
        }

        /**
         * Set the relationships that should be eager loaded while removing any previously added eager loading specifications.
         *
         * @param array<array-key, array|(\Closure(\Illuminate\Database\Eloquent\Relations\Relation<*,*,*>): mixed)|string>|string $relations
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withOnly($relations)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withOnly($relations);
        }

        /**
         * Create a new instance of the model being queried.
         *
         * @param array $attributes
         * @return TModel
         * @static
         */
        public static function newModelInstance($attributes = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->newModelInstance($attributes);
        }

        /**
         * Specify attributes that should be added to any new models created by this builder.
         *
         * The given key / value pairs will also be added as where conditions to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|array|string $attributes
         * @param mixed $value
         * @param bool $asConditions
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withAttributes($attributes, $value = null, $asConditions = true)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withAttributes($attributes, $value, $asConditions);
        }

        /**
         * Apply query-time casts to the model instance.
         *
         * @param array $casts
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withCasts($casts)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withCasts($casts);
        }

        /**
         * Execute the given Closure within a transaction savepoint if needed.
         *
         * @template TModelValue
         * @param \Closure():  TModelValue  $scope
         * @return TModelValue
         * @static
         */
        public static function withSavepointIfNeeded($scope)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withSavepointIfNeeded($scope);
        }

        /**
         * Get the underlying query builder instance.
         *
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function getQuery()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getQuery();
        }

        /**
         * Set the underlying query builder instance.
         *
         * @param \Illuminate\Database\Query\Builder $query
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function setQuery($query)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->setQuery($query);
        }

        /**
         * Get a base query builder instance.
         *
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function toBase()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->toBase();
        }

        /**
         * Get the relationships being eagerly loaded.
         *
         * @return array
         * @static
         */
        public static function getEagerLoads()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getEagerLoads();
        }

        /**
         * Set the relationships being eagerly loaded.
         *
         * @param array $eagerLoad
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function setEagerLoads($eagerLoad)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->setEagerLoads($eagerLoad);
        }

        /**
         * Indicate that the given relationships should not be eagerly loaded.
         *
         * @param array $relations
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withoutEagerLoad($relations)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withoutEagerLoad($relations);
        }

        /**
         * Flush the relationships being eagerly loaded.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withoutEagerLoads()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withoutEagerLoads();
        }

        /**
         * Get the "limit" value from the query or null if it's not set.
         *
         * @return mixed
         * @static
         */
        public static function getLimit()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getLimit();
        }

        /**
         * Get the "offset" value from the query or null if it's not set.
         *
         * @return mixed
         * @static
         */
        public static function getOffset()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getOffset();
        }

        /**
         * Get the model instance being queried.
         *
         * @return TModel
         * @static
         */
        public static function getModel()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getModel();
        }

        /**
         * Set a model instance for the model being queried.
         *
         * @template TModelNew of \Illuminate\Database\Eloquent\Model
         * @param TModelNew $model
         * @return static<TModelNew>
         * @static
         */
        public static function setModel($model)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->setModel($model);
        }

        /**
         * Get the given macro by name.
         *
         * @param string $name
         * @return \Closure
         * @static
         */
        public static function getMacro($name)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->getMacro($name);
        }

        /**
         * Checks if a macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->hasMacro($name);
        }

        /**
         * Get the given global macro by name.
         *
         * @param string $name
         * @return \Closure
         * @static
         */
        public static function getGlobalMacro($name)
        {
            return \Illuminate\Database\Eloquent\Builder::getGlobalMacro($name);
        }

        /**
         * Checks if a global macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasGlobalMacro($name)
        {
            return \Illuminate\Database\Eloquent\Builder::hasGlobalMacro($name);
        }

        /**
         * Clone the Eloquent query builder.
         *
         * @return static
         * @static
         */
        public static function clone()
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->clone();
        }

        /**
         * Register a closure to be invoked on a clone.
         *
         * @param \Closure $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function onClone($callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->onClone($callback);
        }

        /**
         * Chunk the results of the query.
         *
         * @param int $count
         * @param callable(\Illuminate\Support\Collection<int, TValue>, int):  mixed  $callback
         * @return bool
         * @static
         */
        public static function chunk($count, $callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->chunk($count, $callback);
        }

        /**
         * Run a map over each item while chunking.
         *
         * @template TReturn
         * @param callable(TValue):  TReturn  $callback
         * @param int $count
         * @return \Illuminate\Support\Collection<int, TReturn>
         * @static
         */
        public static function chunkMap($callback, $count = 1000)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->chunkMap($callback, $count);
        }

        /**
         * Execute a callback over each item while chunking.
         *
         * @param callable(TValue, int):  mixed  $callback
         * @param int $count
         * @return bool
         * @throws \RuntimeException
         * @static
         */
        public static function each($callback, $count = 1000)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->each($callback, $count);
        }

        /**
         * Chunk the results of a query by comparing IDs.
         *
         * @param int $count
         * @param callable(\Illuminate\Support\Collection<int, TValue>, int):  mixed  $callback
         * @param string|null $column
         * @param string|null $alias
         * @return bool
         * @static
         */
        public static function chunkById($count, $callback, $column = null, $alias = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->chunkById($count, $callback, $column, $alias);
        }

        /**
         * Chunk the results of a query by comparing IDs in descending order.
         *
         * @param int $count
         * @param callable(\Illuminate\Support\Collection<int, TValue>, int):  mixed  $callback
         * @param string|null $column
         * @param string|null $alias
         * @return bool
         * @static
         */
        public static function chunkByIdDesc($count, $callback, $column = null, $alias = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->chunkByIdDesc($count, $callback, $column, $alias);
        }

        /**
         * Chunk the results of a query by comparing IDs in a given order.
         *
         * @param int $count
         * @param callable(\Illuminate\Support\Collection<int, TValue>, int):  mixed  $callback
         * @param string|null $column
         * @param string|null $alias
         * @param \SortDirection|bool $descending
         * @return bool
         * @throws \RuntimeException
         * @static
         */
        public static function orderedChunkById($count, $callback, $column = null, $alias = null, $descending = false)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orderedChunkById($count, $callback, $column, $alias, $descending);
        }

        /**
         * Execute a callback over each item while chunking by ID.
         *
         * @param callable(TValue, int):  mixed  $callback
         * @param int $count
         * @param string|null $column
         * @param string|null $alias
         * @return bool
         * @static
         */
        public static function eachById($callback, $count = 1000, $column = null, $alias = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->eachById($callback, $count, $column, $alias);
        }

        /**
         * Query lazily, by chunks of the given size.
         *
         * @param int $chunkSize
         * @return \Illuminate\Support\LazyCollection<int, TValue>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function lazy($chunkSize = 1000)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->lazy($chunkSize);
        }

        /**
         * Query lazily, by chunking the results of a query by comparing IDs.
         *
         * @param int $chunkSize
         * @param string|null $column
         * @param string|null $alias
         * @return \Illuminate\Support\LazyCollection<int, TValue>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function lazyById($chunkSize = 1000, $column = null, $alias = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->lazyById($chunkSize, $column, $alias);
        }

        /**
         * Query lazily, by chunking the results of a query by comparing IDs in descending order.
         *
         * @param int $chunkSize
         * @param string|null $column
         * @param string|null $alias
         * @return \Illuminate\Support\LazyCollection<int, TValue>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function lazyByIdDesc($chunkSize = 1000, $column = null, $alias = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->lazyByIdDesc($chunkSize, $column, $alias);
        }

        /**
         * Execute the query and get the first result.
         *
         * @param array|string $columns
         * @return TValue|null
         * @static
         */
        public static function first($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->first($columns);
        }

        /**
         * Execute the query and get the first result if it's the sole matching record.
         *
         * @param array|string $columns
         * @return TValue
         * @throws \Illuminate\Database\RecordsNotFoundException
         * @throws \Illuminate\Database\MultipleRecordsFoundException
         * @static
         */
        public static function baseSole($columns = [])
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->baseSole($columns);
        }

        /**
         * Pass the query to a given callback and then return it.
         *
         * @param callable($this):  mixed  $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function tap($callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->tap($callback);
        }

        /**
         * Pass the query to a given callback and return the result.
         *
         * @template TReturn
         * @param (callable($this): TReturn) $callback
         * @return (TReturn is null|void ? $this : TReturn)
         * @static
         */
        public static function pipe($callback)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->pipe($callback);
        }

        /**
         * Apply the callback if the given "value" is (or resolves to) truthy.
         *
         * @template TWhenParameter
         * @template TWhenReturnType
         * @param (\Closure($this): TWhenParameter)|TWhenParameter|null $value
         * @param (callable($this, TWhenParameter): TWhenReturnType)|null $callback
         * @param (callable($this, TWhenParameter): TWhenReturnType)|null $default
         * @return $this|TWhenReturnType
         * @static
         */
        public static function when($value = null, $callback = null, $default = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->when($value, $callback, $default);
        }

        /**
         * Apply the callback if the given "value" is (or resolves to) falsy.
         *
         * @template TUnlessParameter
         * @template TUnlessReturnType
         * @param (\Closure($this): TUnlessParameter)|TUnlessParameter|null $value
         * @param (callable($this, TUnlessParameter): TUnlessReturnType)|null $callback
         * @param (callable($this, TUnlessParameter): TUnlessReturnType)|null $default
         * @return $this|TUnlessReturnType
         * @static
         */
        public static function unless($value = null, $callback = null, $default = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->unless($value, $callback, $default);
        }

        /**
         * Add a relationship count / exists condition to the query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @param string $boolean
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \RuntimeException
         * @static
         */
        public static function has($relation, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->has($relation, $operator, $count, $boolean, $callback);
        }

        /**
         * Add a relationship count / exists condition to the query with an "or".
         *
         * @param \Illuminate\Database\Eloquent\Relations\Relation<*, *, *>|string $relation
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHas($relation, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orHas($relation, $operator, $count);
        }

        /**
         * Add a relationship count / exists condition to the query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param string $boolean
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function doesntHave($relation, $boolean = 'and', $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->doesntHave($relation, $boolean, $callback);
        }

        /**
         * Add a relationship count / exists condition to the query with an "or".
         *
         * @param \Illuminate\Database\Eloquent\Relations\Relation<*, *, *>|string $relation
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orDoesntHave($relation)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orDoesntHave($relation);
        }

        /**
         * Add a relationship count / exists condition to the query with where clauses.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereHas($relation, $callback = null, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereHas($relation, $callback, $operator, $count);
        }

        /**
         * Add a relationship count / exists condition to the query with where clauses.
         *
         * Also load the relationship with the same condition.
         *
         * @param string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Database\Eloquent\Relations\Relation<*, *, *>): mixed)|null $callback
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withWhereHas($relation, $callback = null, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withWhereHas($relation, $callback, $operator, $count);
        }

        /**
         * Add a relationship count / exists condition to the query with where clauses and an "or".
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereHas($relation, $callback = null, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereHas($relation, $callback, $operator, $count);
        }

        /**
         * Add a relationship count / exists condition to the query with where clauses.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereDoesntHave($relation, $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereDoesntHave($relation, $callback);
        }

        /**
         * Add a relationship count / exists condition to the query with where clauses and an "or".
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereDoesntHave($relation, $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereDoesntHave($relation, $callback);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @param string $boolean
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function hasMorph($relation, $types, $operator = '>=', $count = 1, $boolean = 'and', $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->hasMorph($relation, $types, $operator, $count, $boolean, $callback);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with an "or".
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param string|array<int, string> $types
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHasMorph($relation, $types, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orHasMorph($relation, $types, $operator, $count);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param string $boolean
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function doesntHaveMorph($relation, $types, $boolean = 'and', $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->doesntHaveMorph($relation, $types, $boolean, $callback);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with an "or".
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param string|array<int, string> $types
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orDoesntHaveMorph($relation, $types)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orDoesntHaveMorph($relation, $types);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with where clauses.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereHasMorph($relation, $types, $callback = null, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereHasMorph($relation, $types, $callback, $operator, $count);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|int $count
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereHasMorph($relation, $types, $callback = null, $operator = '>=', $count = 1)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereHasMorph($relation, $types, $callback, $operator, $count);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with where clauses.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereDoesntHaveMorph($relation, $types, $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereDoesntHaveMorph($relation, $types, $callback);
        }

        /**
         * Add a polymorphic relationship count / exists condition to the query with where clauses and an "or".
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>, string): mixed)|null $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereDoesntHaveMorph($relation, $types, $callback = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereDoesntHaveMorph($relation, $types, $callback);
        }

        /**
         * Add a basic where clause to a relationship query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereRelation($relation, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereRelation($relation, $column, $operator, $value);
        }

        /**
         * Add a basic where clause to a relationship query and eager-load the relationship with the same conditions.
         *
         * @param \Illuminate\Database\Eloquent\Relations\Relation<*, *, *>|string $relation
         * @param \Closure|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withWhereRelation($relation, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withWhereRelation($relation, $column, $operator, $value);
        }

        /**
         * Add an "or where" clause to a relationship query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereRelation($relation, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereRelation($relation, $column, $operator, $value);
        }

        /**
         * Add a basic count / exists condition to a relationship query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereDoesntHaveRelation($relation, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereDoesntHaveRelation($relation, $column, $operator, $value);
        }

        /**
         * Add an "or where" clause to a relationship query.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\Relation<TRelatedModel, *, *>|string $relation
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereDoesntHaveRelation($relation, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereDoesntHaveRelation($relation, $column, $operator, $value);
        }

        /**
         * Add a polymorphic relationship condition to the query with a where clause.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereMorphRelation($relation, $types, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereMorphRelation($relation, $types, $column, $operator, $value);
        }

        /**
         * Add a polymorphic relationship condition to the query with an "or where" clause.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereMorphRelation($relation, $types, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereMorphRelation($relation, $types, $column, $operator, $value);
        }

        /**
         * Add a polymorphic relationship condition to the query with a doesn't have clause.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereMorphDoesntHaveRelation($relation, $types, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereMorphDoesntHaveRelation($relation, $types, $column, $operator, $value);
        }

        /**
         * Add a polymorphic relationship condition to the query with an "or doesn't have" clause.
         *
         * @template TRelatedModel of \Illuminate\Database\Eloquent\Model
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<TRelatedModel, *>|string $relation
         * @param string|array<int, string> $types
         * @param (\Closure(\Illuminate\Database\Eloquent\Builder<TRelatedModel>): mixed)|string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereMorphDoesntHaveRelation($relation, $types, $column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereMorphDoesntHaveRelation($relation, $types, $column, $operator, $value);
        }

        /**
         * Add a morph-to relationship condition to the query.
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param \Illuminate\Database\Eloquent\Model|iterable<int, \Illuminate\Database\Eloquent\Model>|string|null $model
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function whereMorphedTo($relation, $model, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereMorphedTo($relation, $model, $boolean);
        }

        /**
         * Add a not morph-to relationship condition to the query.
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param \Illuminate\Database\Eloquent\Model|iterable<int, \Illuminate\Database\Eloquent\Model>|string $model
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function whereNotMorphedTo($relation, $model, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereNotMorphedTo($relation, $model, $boolean);
        }

        /**
         * Add a morph-to relationship condition to the query with an "or where" clause.
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param \Illuminate\Database\Eloquent\Model|iterable<int, \Illuminate\Database\Eloquent\Model>|string|null $model
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereMorphedTo($relation, $model)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereMorphedTo($relation, $model);
        }

        /**
         * Add a not morph-to relationship condition to the query with an "or where" clause.
         *
         * @param \Illuminate\Database\Eloquent\Relations\MorphTo<*, *>|string $relation
         * @param \Illuminate\Database\Eloquent\Model|iterable<int, \Illuminate\Database\Eloquent\Model>|string $model
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotMorphedTo($relation, $model)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereNotMorphedTo($relation, $model);
        }

        /**
         * Add a "belongs to" relationship where clause to the query.
         *
         * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model> $related
         * @param string|null $relationshipName
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @throws \Illuminate\Database\Eloquent\RelationNotFoundException
         * @static
         */
        public static function whereBelongsTo($related, $relationshipName = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereBelongsTo($related, $relationshipName, $boolean);
        }

        /**
         * Add a "BelongsTo" relationship with an "or where" clause to the query.
         *
         * @param \Illuminate\Database\Eloquent\Model $related
         * @param string|null $relationshipName
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereBelongsTo($related, $relationshipName = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereBelongsTo($related, $relationshipName);
        }

        /**
         * Add a "belongs to many" relationship where clause to the query.
         *
         * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model> $related
         * @param string|null $relationshipName
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @throws \Illuminate\Database\Eloquent\RelationNotFoundException
         * @static
         */
        public static function whereAttachedTo($related, $relationshipName = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->whereAttachedTo($related, $relationshipName, $boolean);
        }

        /**
         * Add a "belongs to many" relationship with an "or where" clause to the query.
         *
         * @param \Illuminate\Database\Eloquent\Model $related
         * @param string|null $relationshipName
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \RuntimeException
         * @static
         */
        public static function orWhereAttachedTo($related, $relationshipName = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->orWhereAttachedTo($related, $relationshipName);
        }

        /**
         * Add subselect queries to include an aggregate value for a relationship.
         *
         * @param mixed $relations
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string|null $function
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withAggregate($relations, $column, $function = null)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withAggregate($relations, $column, $function);
        }

        /**
         * Add subselect queries to count the relations.
         *
         * @param mixed $relations
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withCount($relations)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withCount($relations);
        }

        /**
         * Add subselect queries to include the max of the relation's column.
         *
         * @param string|array $relation
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withMax($relation, $column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withMax($relation, $column);
        }

        /**
         * Add subselect queries to include the min of the relation's column.
         *
         * @param string|array $relation
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withMin($relation, $column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withMin($relation, $column);
        }

        /**
         * Add subselect queries to include the sum of the relation's column.
         *
         * @param string|array $relation
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withSum($relation, $column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withSum($relation, $column);
        }

        /**
         * Add subselect queries to include the average of the relation's column.
         *
         * @param string|array $relation
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withAvg($relation, $column)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withAvg($relation, $column);
        }

        /**
         * Add subselect queries to include the existence of related models.
         *
         * @param string|array $relation
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function withExists($relation)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->withExists($relation);
        }

        /**
         * Merge the where constraints from another query to the current query.
         *
         * @param \Illuminate\Database\Eloquent\Builder<*> $from
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function mergeConstraintsFrom($from)
        {
            /** @var \Illuminate\Database\Eloquent\Builder $instance */
            return $instance->mergeConstraintsFrom($from);
        }

        /**
         * @see \App\Providers\JsonApiPaginateServiceProvider::registerMacro()
         * @param int|null $maxResults
         * @param int|null $defaultSize
         * @static
         */
        public static function jsonPaginate($maxResults = null, $defaultSize = null)
        {
            return \Illuminate\Database\Eloquent\Builder::jsonPaginate($maxResults, $defaultSize);
        }

        /**
         * Set the columns to be selected.
         *
         * @param mixed $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function select($columns = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->select($columns);
        }

        /**
         * Add a subselect expression to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function selectSub($query, $as)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->selectSub($query, $as);
        }

        /**
         * Add a select expression to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|literal-string $expression
         * @param string $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function selectExpression($expression, $as)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->selectExpression($expression, $as);
        }

        /**
         * Add a new "raw" select expression to the query.
         *
         * @param literal-string $expression
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function selectRaw($expression, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->selectRaw($expression, $bindings);
        }

        /**
         * Makes "from" fetch from a subquery.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function fromSub($query, $as)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->fromSub($query, $as);
        }

        /**
         * Add a raw "from" clause to the query.
         *
         * @param literal-string $expression
         * @param mixed $bindings
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function fromRaw($expression, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->fromRaw($expression, $bindings);
        }

        /**
         * Add a new select column to the query.
         *
         * @param mixed $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function addSelect($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->addSelect($column);
        }

        /**
         * Add a vector-similarity selection to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Support\Collection<int, float>|\Illuminate\Contracts\Support\Arrayable|array<int, float>|string $vector
         * @param string|null $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \JsonException
         * @static
         */
        public static function selectVectorDistance($column, $vector, $as = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->selectVectorDistance($column, $vector, $as);
        }

        /**
         * Force the query to only return distinct results.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function distinct()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->distinct();
        }

        /**
         * Set the table which the query is targeting.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $table
         * @param string|null $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function from($table, $as = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->from($table, $as);
        }

        /**
         * Add an index hint to suggest a query index.
         *
         * @param string $index
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function useIndex($index)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->useIndex($index);
        }

        /**
         * Add an index hint to force a query index.
         *
         * @param string $index
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function forceIndex($index)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->forceIndex($index);
        }

        /**
         * Add an index hint to ignore a query index.
         *
         * @param string $index
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function ignoreIndex($index)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->ignoreIndex($index);
        }

        /**
         * Add a "join" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @param string $type
         * @param bool $where
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->join($table, $first, $operator, $second, $type, $where);
        }

        /**
         * Add a "join where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string $second
         * @param string $type
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function joinWhere($table, $first, $operator, $second, $type = 'inner')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->joinWhere($table, $first, $operator, $second, $type);
        }

        /**
         * Add a "subquery join" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @param string $type
         * @param bool $where
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function joinSub($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->joinSub($query, $as, $first, $operator, $second, $type, $where);
        }

        /**
         * Add a "lateral join" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function joinLateral($query, $as, $type = 'inner')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->joinLateral($query, $as, $type);
        }

        /**
         * Add a lateral left join to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function leftJoinLateral($query, $as)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->leftJoinLateral($query, $as);
        }

        /**
         * Add a left join to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function leftJoin($table, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->leftJoin($table, $first, $operator, $second);
        }

        /**
         * Add a "join where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function leftJoinWhere($table, $first, $operator, $second)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->leftJoinWhere($table, $first, $operator, $second);
        }

        /**
         * Add a subquery left join to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function leftJoinSub($query, $as, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->leftJoinSub($query, $as, $first, $operator, $second);
        }

        /**
         * Add a right join to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function rightJoin($table, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->rightJoin($table, $first, $operator, $second);
        }

        /**
         * Add a "right join where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function rightJoinWhere($table, $first, $operator, $second)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->rightJoinWhere($table, $first, $operator, $second);
        }

        /**
         * Add a subquery right join to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function rightJoinSub($query, $as, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->rightJoinSub($query, $as, $first, $operator, $second);
        }

        /**
         * Add a "cross join" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string|null $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function crossJoin($table, $first = null, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->crossJoin($table, $first, $operator, $second);
        }

        /**
         * Add a subquery cross join to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function crossJoinSub($query, $as)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->crossJoinSub($query, $as);
        }

        /**
         * Add a straight join to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function straightJoin($table, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->straightJoin($table, $first, $operator, $second);
        }

        /**
         * Add a "straight join where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $table
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function straightJoinWhere($table, $first, $operator, $second)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->straightJoinWhere($table, $first, $operator, $second);
        }

        /**
         * Add a subquery straight join to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @param string $as
         * @param \Closure|\Illuminate\Contracts\Database\Query\Expression|string $first
         * @param string|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function straightJoinSub($query, $as, $first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->straightJoinSub($query, $as, $first, $operator, $second);
        }

        /**
         * Merge an array of "where" clauses and bindings.
         *
         * @param array $wheres
         * @param array $bindings
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function mergeWheres($wheres, $bindings)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->mergeWheres($wheres, $bindings);
        }

        /**
         * Prepare the value and operator for a where clause.
         *
         * @param string $value
         * @param string $operator
         * @param bool $useDefault
         * @return array
         * @throws \InvalidArgumentException
         * @static
         */
        public static function prepareValueAndOperator($value, $operator, $useDefault = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->prepareValueAndOperator($value, $operator, $useDefault);
        }

        /**
         * Add a "where" clause comparing two columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string|array $first
         * @param string|null $operator
         * @param string|null $second
         * @param string|null $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereColumn($first, $operator = null, $second = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereColumn($first, $operator, $second, $boolean);
        }

        /**
         * Add an "or where" clause comparing two columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string|array $first
         * @param string|null $operator
         * @param string|null $second
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereColumn($first, $operator = null, $second = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereColumn($first, $operator, $second);
        }

        /**
         * Add a vector similarity clause to the query, filtering by minimum similarity and ordering by similarity.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Support\Collection<int, float>|\Illuminate\Contracts\Support\Arrayable|array<int, float>|string $vector
         * @param float $minSimilarity A value between 0.0 and 1.0, where 1.0 is identical.
         * @param bool $order
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereVectorSimilarTo($column, $vector, $minSimilarity = 0.6, $order = true)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereVectorSimilarTo($column, $vector, $minSimilarity, $order);
        }

        /**
         * Add a vector distance "where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Support\Collection<int, float>|\Illuminate\Contracts\Support\Arrayable|array<int, float>|string $vector
         * @param float $maxDistance
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \JsonException
         * @static
         */
        public static function whereVectorDistanceLessThan($column, $vector, $maxDistance, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereVectorDistanceLessThan($column, $vector, $maxDistance, $boolean);
        }

        /**
         * Add a vector distance "or where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Support\Collection<int, float>|\Illuminate\Contracts\Support\Arrayable|array<int, float>|string $vector
         * @param float $maxDistance
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereVectorDistanceLessThan($column, $vector, $maxDistance)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereVectorDistanceLessThan($column, $vector, $maxDistance);
        }

        /**
         * Add a raw "where" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|literal-string $sql
         * @param mixed $bindings
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereRaw($sql, $bindings = [], $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereRaw($sql, $bindings, $boolean);
        }

        /**
         * Add a raw "or where" clause to the query.
         *
         * @param literal-string $sql
         * @param mixed $bindings
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereRaw($sql, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereRaw($sql, $bindings);
        }

        /**
         * Add a "where like" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $value
         * @param bool $caseSensitive
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereLike($column, $value, $caseSensitive = false, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereLike($column, $value, $caseSensitive, $boolean, $not);
        }

        /**
         * Add an "or where like" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $value
         * @param bool $caseSensitive
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereLike($column, $value, $caseSensitive = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereLike($column, $value, $caseSensitive);
        }

        /**
         * Add a "where not like" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $value
         * @param bool $caseSensitive
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotLike($column, $value, $caseSensitive = false, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotLike($column, $value, $caseSensitive, $boolean);
        }

        /**
         * Add an "or where not like" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $value
         * @param bool $caseSensitive
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotLike($column, $value, $caseSensitive = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotLike($column, $value, $caseSensitive);
        }

        /**
         * Add a "where null safe equals" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNullSafeEquals($column, $value, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNullSafeEquals($column, $value, $boolean);
        }

        /**
         * Add an "or where null safe equals" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNullSafeEquals($column, $value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNullSafeEquals($column, $value);
        }

        /**
         * Add a "where in" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function whereIn($column, $values, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereIn($column, $values, $boolean, $not);
        }

        /**
         * Add an "or where in" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereIn($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereIn($column, $values);
        }

        /**
         * Add a "where not in" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $values
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotIn($column, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotIn($column, $values, $boolean);
        }

        /**
         * Add an "or where not in" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param mixed $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotIn($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotIn($column, $values);
        }

        /**
         * Add a "where in raw" clause for integer values to the query.
         *
         * @param string $column
         * @param \Illuminate\Contracts\Support\Arrayable|array $values
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereIntegerInRaw($column, $values, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereIntegerInRaw($column, $values, $boolean, $not);
        }

        /**
         * Add an "or where in raw" clause for integer values to the query.
         *
         * @param string $column
         * @param \Illuminate\Contracts\Support\Arrayable|array $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereIntegerInRaw($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereIntegerInRaw($column, $values);
        }

        /**
         * Add a "where not in raw" clause for integer values to the query.
         *
         * @param string $column
         * @param \Illuminate\Contracts\Support\Arrayable|array $values
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereIntegerNotInRaw($column, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereIntegerNotInRaw($column, $values, $boolean);
        }

        /**
         * Add an "or where not in raw" clause for integer values to the query.
         *
         * @param string $column
         * @param \Illuminate\Contracts\Support\Arrayable|array $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereIntegerNotInRaw($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereIntegerNotInRaw($column, $values);
        }

        /**
         * Add a "where null" clause to the query.
         *
         * @param string|array|\Illuminate\Contracts\Database\Query\Expression $columns
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNull($columns, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNull($columns, $boolean, $not);
        }

        /**
         * Add an "or where null" clause to the query.
         *
         * @param string|array|\Illuminate\Contracts\Database\Query\Expression $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNull($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNull($column);
        }

        /**
         * Add a "where not null" clause to the query.
         *
         * @param string|array|\Illuminate\Contracts\Database\Query\Expression $columns
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotNull($columns, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotNull($columns, $boolean);
        }

        /**
         * Add a "where between" statement to the query.
         *
         * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereBetween($column, $values, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereBetween($column, $values, $boolean, $not);
        }

        /**
         * Add a "where between" statement using columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereBetweenColumns($column, $values, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereBetweenColumns($column, $values, $boolean, $not);
        }

        /**
         * Add an "or where between" statement to the query.
         *
         * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereBetween($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereBetween($column, $values);
        }

        /**
         * Add an "or where between" statement using columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereBetweenColumns($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereBetweenColumns($column, $values);
        }

        /**
         * Add a "where not between" statement to the query.
         *
         * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotBetween($column, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotBetween($column, $values, $boolean);
        }

        /**
         * Add a "where not between" statement using columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotBetweenColumns($column, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotBetweenColumns($column, $values, $boolean);
        }

        /**
         * Add an "or where not between" statement to the query.
         *
         * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotBetween($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotBetween($column, $values);
        }

        /**
         * Add an "or where not between" statement using columns to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotBetweenColumns($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotBetweenColumns($column, $values);
        }

        /**
         * Add a "where between columns" statement using a value to the query.
         *
         * @param mixed $value
         * @param array{\Illuminate\Contracts\Database\Query\Expression|string, \Illuminate\Contracts\Database\Query\Expression|string} $columns
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereValueBetween($value, $columns, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereValueBetween($value, $columns, $boolean, $not);
        }

        /**
         * Add an "or where between columns" statement using a value to the query.
         *
         * @param mixed $value
         * @param array{\Illuminate\Contracts\Database\Query\Expression|string, \Illuminate\Contracts\Database\Query\Expression|string} $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereValueBetween($value, $columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereValueBetween($value, $columns);
        }

        /**
         * Add a "where not between columns" statement using a value to the query.
         *
         * @param mixed $value
         * @param array{\Illuminate\Contracts\Database\Query\Expression|string, \Illuminate\Contracts\Database\Query\Expression|string} $columns
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereValueNotBetween($value, $columns, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereValueNotBetween($value, $columns, $boolean);
        }

        /**
         * Add an "or where not between columns" statement using a value to the query.
         *
         * @param mixed $value
         * @param array{\Illuminate\Contracts\Database\Query\Expression|string, \Illuminate\Contracts\Database\Query\Expression|string} $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereValueNotBetween($value, $columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereValueNotBetween($value, $columns);
        }

        /**
         * Add an "or where not null" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotNull($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotNull($column);
        }

        /**
         * Add a "where date" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|null $operator
         * @param \DateTimeInterface|string|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereDate($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereDate($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where date" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|null $operator
         * @param \DateTimeInterface|string|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereDate($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereDate($column, $operator, $value);
        }

        /**
         * Add a "where time" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|null $operator
         * @param \DateTimeInterface|string|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereTime($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereTime($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where time" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|null $operator
         * @param \DateTimeInterface|string|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereTime($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereTime($column, $operator, $value);
        }

        /**
         * Add a "where day" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereDay($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereDay($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where day" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereDay($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereDay($column, $operator, $value);
        }

        /**
         * Add a "where month" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereMonth($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereMonth($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where month" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereMonth($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereMonth($column, $operator, $value);
        }

        /**
         * Add a "where year" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereYear($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereYear($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where year" statement to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \DateTimeInterface|string|int|null $operator
         * @param \DateTimeInterface|string|int|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereYear($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereYear($column, $operator, $value);
        }

        /**
         * Add a nested "where" statement to the query.
         *
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNested($callback, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNested($callback, $boolean);
        }

        /**
         * Create a new query instance for nested where condition.
         *
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function forNestedWhere()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->forNestedWhere();
        }

        /**
         * Add another query builder as a nested where to the query builder.
         *
         * @param \Illuminate\Database\Query\Builder $query
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function addNestedWhereQuery($query, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->addNestedWhereQuery($query, $boolean);
        }

        /**
         * Add an "exists" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $callback
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereExists($callback, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereExists($callback, $boolean, $not);
        }

        /**
         * Add an "or where exists" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $callback
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereExists($callback, $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereExists($callback, $not);
        }

        /**
         * Add a "where not exists" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $callback
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNotExists($callback, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNotExists($callback, $boolean);
        }

        /**
         * Add an "or where not exists" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $callback
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNotExists($callback)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNotExists($callback);
        }

        /**
         * Add an "exists" clause to the query.
         *
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function addWhereExistsQuery($query, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->addWhereExistsQuery($query, $boolean, $not);
        }

        /**
         * Adds a where condition using row values.
         *
         * @param array $columns
         * @param string $operator
         * @param array $values
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function whereRowValues($columns, $operator, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereRowValues($columns, $operator, $values, $boolean);
        }

        /**
         * Adds an or where condition using row values.
         *
         * @param array $columns
         * @param string $operator
         * @param array $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereRowValues($columns, $operator, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereRowValues($columns, $operator, $values);
        }

        /**
         * Add a "where JSON contains" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonContains($column, $value, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonContains($column, $value, $boolean, $not);
        }

        /**
         * Add an "or where JSON contains" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonContains($column, $value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonContains($column, $value);
        }

        /**
         * Add a "where JSON not contains" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonDoesntContain($column, $value, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonDoesntContain($column, $value, $boolean);
        }

        /**
         * Add an "or where JSON not contains" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonDoesntContain($column, $value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonDoesntContain($column, $value);
        }

        /**
         * Add a "where JSON overlaps" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonOverlaps($column, $value, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonOverlaps($column, $value, $boolean, $not);
        }

        /**
         * Add an "or where JSON overlaps" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonOverlaps($column, $value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonOverlaps($column, $value);
        }

        /**
         * Add a "where JSON not overlap" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonDoesntOverlap($column, $value, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonDoesntOverlap($column, $value, $boolean);
        }

        /**
         * Add an "or where JSON not overlap" clause to the query.
         *
         * @param string $column
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonDoesntOverlap($column, $value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonDoesntOverlap($column, $value);
        }

        /**
         * Add a clause that determines if a JSON path exists to the query.
         *
         * @param string $column
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonContainsKey($column, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonContainsKey($column, $boolean, $not);
        }

        /**
         * Add an "or" clause that determines if a JSON path exists to the query.
         *
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonContainsKey($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonContainsKey($column);
        }

        /**
         * Add a clause that determines if a JSON path does not exist to the query.
         *
         * @param string $column
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonDoesntContainKey($column, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonDoesntContainKey($column, $boolean);
        }

        /**
         * Add an "or" clause that determines if a JSON path does not exist to the query.
         *
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonDoesntContainKey($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonDoesntContainKey($column);
        }

        /**
         * Add a "where JSON length" clause to the query.
         *
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereJsonLength($column, $operator, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereJsonLength($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or where JSON length" clause to the query.
         *
         * @param string $column
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereJsonLength($column, $operator, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereJsonLength($column, $operator, $value);
        }

        /**
         * Handles dynamic "where" clauses to the query.
         *
         * @param string $method
         * @param array $parameters
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function dynamicWhere($method, $parameters)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->dynamicWhere($method, $parameters);
        }

        /**
         * Add a "where fulltext" clause to the query.
         *
         * @param string|string[] $columns
         * @param string $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereFullText($columns, $value, $options = [], $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereFullText($columns, $value, $options, $boolean);
        }

        /**
         * Add an "or where fulltext" clause to the query.
         *
         * @param string|string[] $columns
         * @param string $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereFullText($columns, $value, $options = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereFullText($columns, $value, $options);
        }

        /**
         * Add a "where" clause to the query for multiple columns with "and" conditions between them.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereAll($columns, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereAll($columns, $operator, $value, $boolean);
        }

        /**
         * Add an "or where" clause to the query for multiple columns with "and" conditions between them.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereAll($columns, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereAll($columns, $operator, $value);
        }

        /**
         * Add a "where" clause to the query for multiple columns with "or" conditions between them.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereAny($columns, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereAny($columns, $operator, $value, $boolean);
        }

        /**
         * Add an "or where" clause to the query for multiple columns with "or" conditions between them.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereAny($columns, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereAny($columns, $operator, $value);
        }

        /**
         * Add a "where not" clause to the query for multiple columns where none of the conditions should be true.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNone($columns, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNone($columns, $operator, $value, $boolean);
        }

        /**
         * Add an "or where not" clause to the query for multiple columns where none of the conditions should be true.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression[]|\Closure[]|string[] $columns
         * @param mixed $operator
         * @param mixed $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNone($columns, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNone($columns, $operator, $value);
        }

        /**
         * Add a "group by" clause to the query.
         *
         * @param array|\Illuminate\Contracts\Database\Query\Expression|string $groups
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function groupBy(...$groups)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->groupBy(...$groups);
        }

        /**
         * Add a raw "groupBy" clause to the query.
         *
         * @param literal-string $sql
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function groupByRaw($sql, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->groupByRaw($sql, $bindings);
        }

        /**
         * Add a "having" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|\Closure|string $column
         * @param \DateTimeInterface|string|int|float|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|\DateTimeInterface|string|int|float|null $value
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function having($column, $operator = null, $value = null, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->having($column, $operator, $value, $boolean);
        }

        /**
         * Add an "or having" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|\Closure|string $column
         * @param \DateTimeInterface|string|int|float|null $operator
         * @param \Illuminate\Contracts\Database\Query\Expression|\DateTimeInterface|string|int|float|null $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHaving($column, $operator = null, $value = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHaving($column, $operator, $value);
        }

        /**
         * Add a nested "having" statement to the query.
         *
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingNested($callback, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingNested($callback, $boolean);
        }

        /**
         * Add another query builder as a nested having to the query builder.
         *
         * @param \Illuminate\Database\Query\Builder $query
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function addNestedHavingQuery($query, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->addNestedHavingQuery($query, $boolean);
        }

        /**
         * Add a "having null" clause to the query.
         *
         * @param array|string $columns
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingNull($columns, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingNull($columns, $boolean, $not);
        }

        /**
         * Add an "or having null" clause to the query.
         *
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHavingNull($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHavingNull($column);
        }

        /**
         * Add a "having not null" clause to the query.
         *
         * @param array|string $columns
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingNotNull($columns, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingNotNull($columns, $boolean);
        }

        /**
         * Add an "or having not null" clause to the query.
         *
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHavingNotNull($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHavingNotNull($column);
        }

        /**
         * Add a "having between" clause to the query.
         *
         * @param string $column
         * @param string $boolean
         * @param bool $not
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingBetween($column, $values, $boolean = 'and', $not = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingBetween($column, $values, $boolean, $not);
        }

        /**
         * Add a "having not between" clause to the query.
         *
         * @param string $column
         * @param iterable $values
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingNotBetween($column, $values, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingNotBetween($column, $values, $boolean);
        }

        /**
         * Add an "or having between" clause to the query.
         *
         * @param string $column
         * @param iterable $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHavingBetween($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHavingBetween($column, $values);
        }

        /**
         * Add an "or having not between" clause to the query.
         *
         * @param string $column
         * @param iterable $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHavingNotBetween($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHavingNotBetween($column, $values);
        }

        /**
         * Add a raw "having" clause to the query.
         *
         * @param literal-string $sql
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function havingRaw($sql, $bindings = [], $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->havingRaw($sql, $bindings, $boolean);
        }

        /**
         * Add a raw "or having" clause to the query.
         *
         * @param literal-string $sql
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orHavingRaw($sql, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orHavingRaw($sql, $bindings);
        }

        /**
         * Add an "order by" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \SortDirection|'asc'|'desc' $direction
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function orderBy($column, $direction = \SortDirection::Ascending)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orderBy($column, $direction);
        }

        /**
         * Add a descending "order by" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|\Illuminate\Contracts\Database\Query\Expression|string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orderByDesc($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orderByDesc($column);
        }

        /**
         * Add a vector-distance "order by" clause to the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Support\Collection<int, float>|\Illuminate\Contracts\Support\Arrayable|array<int, float> $vector
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \JsonException
         * @static
         */
        public static function orderByVectorDistance($column, $vector)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orderByVectorDistance($column, $vector);
        }

        /**
         * Put the query's results in random order.
         *
         * @param string|int $seed
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function inRandomOrder($seed = '')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->inRandomOrder($seed);
        }

        /**
         * Add an "order by" clause to order results by a given sequence of values.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @param \Illuminate\Contracts\Support\Arrayable|array $values
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function inOrderOf($column, $values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->inOrderOf($column, $values);
        }

        /**
         * Add a raw "order by" clause to the query.
         *
         * @param literal-string $sql
         * @param array $bindings
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orderByRaw($sql, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orderByRaw($sql, $bindings);
        }

        /**
         * Alias to set the "offset" value of the query.
         *
         * @param int $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function skip($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->skip($value);
        }

        /**
         * Set the "offset" value of the query.
         *
         * @param int $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function offset($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->offset($value);
        }

        /**
         * Alias to set the "limit" value of the query.
         *
         * @param int $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function take($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->take($value);
        }

        /**
         * Set the "limit" value of the query.
         *
         * @param int $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function limit($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->limit($value);
        }

        /**
         * Add a "group limit" clause to the query.
         *
         * @param int $value
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function groupLimit($value, $column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->groupLimit($value, $column);
        }

        /**
         * Set the limit and offset for a given page.
         *
         * @param int $page
         * @param int $perPage
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function forPage($page, $perPage = 15)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->forPage($page, $perPage);
        }

        /**
         * Constrain the query to the previous "page" of results before a given ID.
         *
         * @param int $perPage
         * @param int|null $lastId
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function forPageBeforeId($perPage = 15, $lastId = 0, $column = 'id')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->forPageBeforeId($perPage, $lastId, $column);
        }

        /**
         * Constrain the query to the next "page" of results after a given ID.
         *
         * @param int $perPage
         * @param int|null $lastId
         * @param string $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function forPageAfterId($perPage = 15, $lastId = 0, $column = 'id')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->forPageAfterId($perPage, $lastId, $column);
        }

        /**
         * Remove all existing orders and optionally add a new order.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Contracts\Database\Query\Expression|string|null $column
         * @param \SortDirection|'asc'|'desc' $direction
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function reorder($column = null, $direction = \SortDirection::Ascending)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->reorder($column, $direction);
        }

        /**
         * Add descending "reorder" clause to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Contracts\Database\Query\Expression|string|null $column
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function reorderDesc($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->reorderDesc($column);
        }

        /**
         * Add a "union" statement to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $query
         * @param bool $all
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function union($query, $all = false)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->union($query, $all);
        }

        /**
         * Add a "union all" statement to the query.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*> $query
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function unionAll($query)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->unionAll($query);
        }

        /**
         * Lock the selected rows in the table.
         *
         * @param string|bool $value
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function lock($value = true)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->lock($value);
        }

        /**
         * Lock the selected rows in the table for updating.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function lockForUpdate()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->lockForUpdate();
        }

        /**
         * Share lock the selected rows in the table.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function sharedLock()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->sharedLock();
        }

        /**
         * Set a query execution timeout in seconds.
         *
         * @param int|null $seconds
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws InvalidArgumentException
         * @static
         */
        public static function timeout($seconds)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->timeout($seconds);
        }

        /**
         * Register a closure to be invoked before the query is executed.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function beforeQuery($callback)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->beforeQuery($callback);
        }

        /**
         * Invoke the "before query" modification callbacks.
         *
         * @return void
         * @static
         */
        public static function applyBeforeQueryCallbacks()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            $instance->applyBeforeQueryCallbacks();
        }

        /**
         * Get the SQL representation of the query.
         *
         * @return string
         * @static
         */
        public static function toSql()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->toSql();
        }

        /**
         * Get the raw SQL representation of the query with embedded bindings.
         *
         * @return string
         * @static
         */
        public static function toRawSql()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->toRawSql();
        }

        /**
         * Get a single expression value from the first result of a query.
         *
         * @param literal-string $expression
         * @return mixed
         * @static
         */
        public static function rawValue($expression, $bindings = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->rawValue($expression, $bindings);
        }

        /**
         * Get the count of the total records for the paginator.
         *
         * @param array<string|\Illuminate\Contracts\Database\Query\Expression> $columns
         * @return int<0, max>
         * @static
         */
        public static function getCountForPagination($columns = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getCountForPagination($columns);
        }

        /**
         * Concatenate values of a given column as a string.
         *
         * @param string $column
         * @param string $glue
         * @return string
         * @static
         */
        public static function implode($column, $glue = '')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->implode($column, $glue);
        }

        /**
         * Determine if any rows exist for the current query.
         *
         * @return bool
         * @static
         */
        public static function exists()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->exists();
        }

        /**
         * Determine if no rows exist for the current query.
         *
         * @return bool
         * @static
         */
        public static function doesntExist()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->doesntExist();
        }

        /**
         * Execute the given callback if no rows exist for the current query.
         *
         * @return mixed
         * @static
         */
        public static function existsOr($callback)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->existsOr($callback);
        }

        /**
         * Execute the given callback if rows exist for the current query.
         *
         * @return mixed
         * @static
         */
        public static function doesntExistOr($callback)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->doesntExistOr($callback);
        }

        /**
         * Retrieve the "count" result of the query.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $columns
         * @return int<0, max>
         * @static
         */
        public static function count($columns = '*')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->count($columns);
        }

        /**
         * Retrieve the minimum value of a given column.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return mixed
         * @static
         */
        public static function min($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->min($column);
        }

        /**
         * Retrieve the maximum value of a given column.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return mixed
         * @static
         */
        public static function max($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->max($column);
        }

        /**
         * Retrieve the sum of the values of a given column.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return mixed
         * @static
         */
        public static function sum($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->sum($column);
        }

        /**
         * Retrieve the average of the values of a given column.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return mixed
         * @static
         */
        public static function avg($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->avg($column);
        }

        /**
         * Alias for the "avg" method.
         *
         * @param \Illuminate\Contracts\Database\Query\Expression|string $column
         * @return mixed
         * @static
         */
        public static function average($column)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->average($column);
        }

        /**
         * Execute an aggregate function on the database.
         *
         * @param string $function
         * @param array $columns
         * @return mixed
         * @static
         */
        public static function aggregate($function, $columns = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->aggregate($function, $columns);
        }

        /**
         * Execute a numeric aggregate function on the database.
         *
         * @param string $function
         * @param array $columns
         * @return float|int
         * @static
         */
        public static function numericAggregate($function, $columns = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->numericAggregate($function, $columns);
        }

        /**
         * Insert new records into the database.
         *
         * @return bool
         * @static
         */
        public static function insert($values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insert($values);
        }

        /**
         * Insert new records into the database while ignoring errors.
         *
         * @return int<0, max>
         * @static
         */
        public static function insertOrIgnore($values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insertOrIgnore($values);
        }

        /**
         * Insert new records into the database and returning specified columns with optional ignoring specific conflicts.
         *
         * @param non-empty-array<non-empty-string> $returning
         * @param non-empty-string|non-empty-array<non-empty-string>|null $uniqueBy
         * @return \Illuminate\Support\Collection
         * @static
         */
        public static function insertOrIgnoreReturning($values, $returning = [], $uniqueBy = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insertOrIgnoreReturning($values, $returning, $uniqueBy);
        }

        /**
         * Insert a new record and get the value of the primary key.
         *
         * @param string|null $sequence
         * @return int
         * @static
         */
        public static function insertGetId($values, $sequence = null)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insertGetId($values, $sequence);
        }

        /**
         * Insert new records into the table using a subquery.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @return int
         * @static
         */
        public static function insertUsing($columns, $query)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insertUsing($columns, $query);
        }

        /**
         * Insert new records into the table using a subquery while ignoring errors.
         *
         * @param \Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder<*>|string $query
         * @return int
         * @static
         */
        public static function insertOrIgnoreUsing($columns, $query)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->insertOrIgnoreUsing($columns, $query);
        }

        /**
         * Update records in a PostgreSQL database using the update from syntax.
         *
         * @return int
         * @throws \LogicException
         * @static
         */
        public static function updateFrom($values)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->updateFrom($values);
        }

        /**
         * Insert or update a record matching the attributes, and fill it with values.
         *
         * @return bool
         * @static
         */
        public static function updateOrInsert($attributes, $values = [])
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->updateOrInsert($attributes, $values);
        }

        /**
         * Run a "truncate" statement on the table.
         *
         * @return void
         * @static
         */
        public static function truncate()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            $instance->truncate();
        }

        /**
         * Get all of the query builder's columns in a text-only array with all expressions evaluated.
         *
         * @return list<string>
         * @static
         */
        public static function getColumns()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getColumns();
        }

        /**
         * Create a raw database expression.
         *
         * @param literal-string|int|float $value
         * @return \Illuminate\Contracts\Database\Query\Expression
         * @static
         */
        public static function raw($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->raw($value);
        }

        /**
         * Get the current query value bindings in a flattened array.
         *
         * @return list<mixed>
         * @static
         */
        public static function getBindings()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getBindings();
        }

        /**
         * Get the raw array of bindings.
         *
         * @return \Illuminate\Database\Query\array{ select: list<mixed>,
         *      from: list<mixed>,
         *      join: list<mixed>,
         *      where: list<mixed>,
         *      groupBy: list<mixed>,
         *      having: list<mixed>,
         *      order: list<mixed>,
         *      union: list<mixed>,
         *      unionOrder: list<mixed>,
         * }
         * @static
         */
        public static function getRawBindings()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getRawBindings();
        }

        /**
         * Set the bindings on the query builder.
         *
         * @param list<mixed> $bindings
         * @param "select"|"from"|"join"|"where"|"groupBy"|"having"|"order"|"union"|"unionOrder" $type
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function setBindings($bindings, $type = 'where')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->setBindings($bindings, $type);
        }

        /**
         * Add a binding to the query.
         *
         * @param mixed $value
         * @param "select"|"from"|"join"|"where"|"groupBy"|"having"|"order"|"union"|"unionOrder" $type
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @throws \InvalidArgumentException
         * @static
         */
        public static function addBinding($value, $type = 'where')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->addBinding($value, $type);
        }

        /**
         * Cast the given binding value.
         *
         * @param mixed $value
         * @return mixed
         * @static
         */
        public static function castBinding($value)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->castBinding($value);
        }

        /**
         * Merge an array of bindings into our bindings.
         *
         * @param self $query
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function mergeBindings($query)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->mergeBindings($query);
        }

        /**
         * Remove all of the expressions from a list of bindings.
         *
         * @param array<mixed> $bindings
         * @return list<mixed>
         * @static
         */
        public static function cleanBindings($bindings)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->cleanBindings($bindings);
        }

        /**
         * Get the database query processor instance.
         *
         * @return \Illuminate\Database\Query\Processors\Processor
         * @static
         */
        public static function getProcessor()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getProcessor();
        }

        /**
         * Get the query grammar instance.
         *
         * @return \Illuminate\Database\Query\Grammars\Grammar
         * @static
         */
        public static function getGrammar()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->getGrammar();
        }

        /**
         * Use the "write" PDO connection when executing the query.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function useWritePdo()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->useWritePdo();
        }

        /**
         * Specify arguments for the PDOStatement::fetchAll / fetch functions.
         *
         * @param mixed $fetchUsing
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function fetchUsing(...$fetchUsing)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->fetchUsing(...$fetchUsing);
        }

        /**
         * Clone the query without the given properties.
         *
         * @return static
         * @static
         */
        public static function cloneWithout($properties)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->cloneWithout($properties);
        }

        /**
         * Clone the query without the given bindings.
         *
         * @return static
         * @static
         */
        public static function cloneWithoutBindings($except)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->cloneWithoutBindings($except);
        }

        /**
         * Dump the current SQL and bindings.
         *
         * @param mixed $args
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function dump(...$args)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->dump(...$args);
        }

        /**
         * Dump the raw current SQL with embedded bindings.
         *
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function dumpRawSql()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->dumpRawSql();
        }

        /**
         * Die and dump the current SQL and bindings.
         *
         * @return never
         * @static
         */
        public static function dd()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->dd();
        }

        /**
         * Die and dump the current SQL with embedded bindings.
         *
         * @return never
         * @static
         */
        public static function ddRawSql()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->ddRawSql();
        }

        /**
         * Add a where clause to determine if a "date" column is in the past to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function wherePast($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->wherePast($columns);
        }

        /**
         * Add a where clause to determine if a "date" column is in the past or now to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNowOrPast($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNowOrPast($columns);
        }

        /**
         * Add an "or where" clause to determine if a "date" column is in the past to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWherePast($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWherePast($columns);
        }

        /**
         * Add a where clause to determine if a "date" column is in the past or now to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNowOrPast($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNowOrPast($columns);
        }

        /**
         * Add a where clause to determine if a "date" column is in the future to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereFuture($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereFuture($columns);
        }

        /**
         * Add a where clause to determine if a "date" column is in the future or now to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereNowOrFuture($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereNowOrFuture($columns);
        }

        /**
         * Add an "or where" clause to determine if a "date" column is in the future to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereFuture($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereFuture($columns);
        }

        /**
         * Add an "or where" clause to determine if a "date" column is in the future or now to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereNowOrFuture($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereNowOrFuture($columns);
        }

        /**
         * Add a "where date" clause to determine if a "date" column is today to the query.
         *
         * @param array|string $columns
         * @param string $boolean
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereToday($columns, $boolean = 'and')
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereToday($columns, $boolean);
        }

        /**
         * Add a "where date" clause to determine if a "date" column is before today.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereBeforeToday($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereBeforeToday($columns);
        }

        /**
         * Add a "where date" clause to determine if a "date" column is today or before to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereTodayOrBefore($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereTodayOrBefore($columns);
        }

        /**
         * Add a "where date" clause to determine if a "date" column is after today.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereAfterToday($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereAfterToday($columns);
        }

        /**
         * Add a "where date" clause to determine if a "date" column is today or after to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function whereTodayOrAfter($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->whereTodayOrAfter($columns);
        }

        /**
         * Add an "or where date" clause to determine if a "date" column is today to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereToday($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereToday($columns);
        }

        /**
         * Add an "or where date" clause to determine if a "date" column is before today.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereBeforeToday($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereBeforeToday($columns);
        }

        /**
         * Add an "or where date" clause to determine if a "date" column is today or before to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereTodayOrBefore($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereTodayOrBefore($columns);
        }

        /**
         * Add an "or where date" clause to determine if a "date" column is after today.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereAfterToday($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereAfterToday($columns);
        }

        /**
         * Add an "or where date" clause to determine if a "date" column is today or after to the query.
         *
         * @param array|string $columns
         * @return \Illuminate\Database\Eloquent\Builder<static>
         * @static
         */
        public static function orWhereTodayOrAfter($columns)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->orWhereTodayOrAfter($columns);
        }

        /**
         * Explains the query.
         *
         * @return \Illuminate\Support\Collection
         * @static
         */
        public static function explain()
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->explain();
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param object|callable $macro
         * @param-closure-this static  $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro)
        {
            \Illuminate\Database\Query\Builder::macro($name, $macro);
        }

        /**
         * Mix another object into the class.
         *
         * @param object $mixin
         * @param bool $replace
         * @return void
         * @throws \ReflectionException
         * @static
         */
        public static function mixin($mixin, $replace = true)
        {
            \Illuminate\Database\Query\Builder::mixin($mixin, $replace);
        }

        /**
         * Flush the existing macros.
         *
         * @return void
         * @static
         */
        public static function flushMacros()
        {
            \Illuminate\Database\Query\Builder::flushMacros();
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed
         * @throws \BadMethodCallException
         * @static
         */
        public static function macroCall($method, $parameters)
        {
            /** @var \Illuminate\Database\Query\Builder $instance */
            return $instance->macroCall($method, $parameters);
        }

}
}





