<?php

namespace App\Services\DataTables;

use App\Services\DataTables\Traits\RenderTrait;

class EloquentDataTable extends \Yajra\DataTables\EloquentDataTable
{
    use RenderTrait;
}
