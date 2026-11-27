<?php

namespace Yajra\DataTables\Html;

class Parameters extends Fluent
{
    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'serverSide' => true,
        'processing' => true,
        'ajax' => '',
        'columns' => [],
    ];
}
