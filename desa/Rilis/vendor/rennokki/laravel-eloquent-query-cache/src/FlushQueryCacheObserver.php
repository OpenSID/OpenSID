<?php

namespace Rennokki\QueryCache;

use Exception;
use Illuminate\Database\Eloquent\Model;

class FlushQueryCacheObserver
{
    /**
     * Handle the Model "created" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function created(Model $model)
    {
        $this->invalidateCache($model);
    }

    /**
     * Handle the Model "updated" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updated(Model $model)
    {
        $this->invalidateCache($model);
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleted(Model $model)
    {
        $this->invalidateCache($model);
    }

    /**
     * Handle the Model "forceDeleted" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        $this->invalidateCache($model);
    }

    /**
     * Handle the Model "restored" event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function restored(Model $model)
    {
        $this->invalidateCache($model);
    }

    /**
     * Invalidate attach for belongsToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function belongsToManyAttached($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate detach for belongsToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function belongsToManyDetached($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate update pivot for belongsToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function belongsToManyUpdatedExistingPivot($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate attach for morphToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function morphToManyAttached($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate detach for morphToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function morphToManyDetached($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate update pivot for morphToMany.
     *
     * @param  string  $relation
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  array  $ids
     * @return void
     */
    public function morphToManyUpdatedExistingPivot($relation, Model $model, $ids)
    {
        $this->invalidateCache($model, $relation, $model->{$relation}()->findMany($ids));
    }

    /**
     * Invalidate the cache for a model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $relation
     * @param  \Illuminate\Database\Eloquent\Collection|null  $pivotedModels
     * @return void
     *
     * @throws Exception
     */
    protected function invalidateCache(Model $model, $relation = null, $pivotedModels = null): void
    {
        $class = get_class($model);

        $tags = $model->getCacheTagsToInvalidateOnUpdate($relation, $pivotedModels);

        if (! $tags) {
            throw new Exception('Automatic invalidation for '.$class.' works only if at least one tag to be invalidated is specified.');
        }

        $class::flushQueryCache($tags);
    }
}
