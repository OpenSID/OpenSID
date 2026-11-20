<?php

namespace Yajra\DataTables\Html\Columns;

use Yajra\DataTables\Html\Column;

trait Index
{
    /**
     * Add a index column.
     *
     * @return $this
     */
    public function addIndex(array $attributes = []): static
    {
        $indexColumn = $this->config->get('datatables.index_column', 'DT_RowIndex');

        $attributes = array_merge([
            'defaultContent' => '',
            'data' => $indexColumn,
            'name' => $indexColumn,
            'title' => '',
            'render' => null,
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'footer' => '',
        ], $attributes);

        $this->collection->push(new Column($attributes));

        return $this;
    }
}
