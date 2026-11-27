<?php

namespace Yajra\DataTables\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

interface DataTableScope
{
    /**
     * Apply a query scope.
     *
     * @param  Builder|\Illuminate\Database\Eloquent\Builder|Relation|Collection|AnonymousResourceCollection  $query
     * @return mixed
     */
    public function apply($query);
}
