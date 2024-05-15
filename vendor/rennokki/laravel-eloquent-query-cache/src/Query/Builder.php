<?php

namespace Rennokki\QueryCache\Query;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;
use Rennokki\QueryCache\Contracts\QueryCacheModuleInterface;
use Rennokki\QueryCache\Traits\QueryCacheModule;

class Builder extends BaseBuilder implements QueryCacheModuleInterface
{
    use QueryCacheModule;

    /**
     * {@inheritdoc}
     */
    public function get($columns = ['*'])
    {
        return $this->shouldAvoidCache()
            ? parent::get($columns)
            : $this->getFromQueryCache('get', Arr::wrap($columns));
    }

    /**
     * {@inheritdoc}
     */
    public function useWritePdo()
    {
        // Do not cache when using the write pdo for query.
        $this->dontCache();

        // Call parent method
        parent::useWritePdo();

        return $this;
    }

    /**
     * Add a subselect expression to the query.
     *
     * @param  \Closure|$this|string  $query
     * @param  string  $as
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function selectSub($query, $as)
    {
        if (! is_string($query) && get_class($query) == self::class) {
            $this->appendCacheTags($query->getCacheTags() ?? []);
        }

        return parent::selectSub($query, $as);
    }
}
