<?php

namespace Yajra\DataTables\Html\Columns;

use Yajra\DataTables\Html\Column;

trait Action
{
    /**
     * Add an action column.
     *
     * @return $this
     */
    public function addAction(array $attributes = [], bool $prepend = false): static
    {
        $attributes = array_merge([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'render' => null,
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'footer' => '',
        ], $attributes);

        if ($prepend) {
            $this->collection->prepend(new Column($attributes));
        } else {
            $this->collection->push(new Column($attributes));
        }

        return $this;
    }
}
