<?php

namespace Yajra\DataTables\Contracts;

use Yajra\DataTables\Html\Builder;

interface DataTableHtmlBuilder
{
    /**
     * Handle building of dataTables html.
     *
     * @return Builder
     */
    public function handle();
}
