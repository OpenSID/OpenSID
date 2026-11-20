<?php

namespace Yajra\DataTables\Html\Columns;

use Yajra\DataTables\Html\Column;

trait Checkbox
{
    /**
     * Add a checkbox column.
     *
     * @param  bool|int  $position  true to prepend, false to append or a zero-based index for positioning
     * @return $this
     */
    public function addCheckbox(array $attributes = [], bool|int $position = false): static
    {
        $attributes = array_merge([
            'defaultContent' => '<input type="checkbox" '.$this->html->attributes($attributes).'/>',
            'title' => '<input type="checkbox" '.$this->html->attributes($attributes + ['id' => 'dataTablesCheckbox']).'/>',
            'data' => 'checkbox',
            'name' => 'checkbox',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'width' => '10px',
        ], $attributes);

        $column = new Column($attributes);

        if ($position === true) {
            $this->collection->prepend($column);
        } elseif ($position === false || $position >= $this->collection->count()) {
            $this->collection->push($column);
        } else {
            $this->collection->splice($position, 0, [$column]);
        }

        return $this;
    }
}
