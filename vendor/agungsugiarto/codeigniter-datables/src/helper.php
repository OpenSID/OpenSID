<?php

use Fluent\DataTables\DataTables;

if (! function_exists('datatables')) {
    /**
     * Helper to make a new DataTable instance from source.
     * Or return the factory if source is not set.
     *
     * @param  mixed  $source
     * @return \Fluent\DataTables\DataTableAbstract|\Fluent\DataTables\DataTables
     */
    function datatables($source = null)
    {
        if (is_null($source)) {
            return new DataTables();
        }

        return (new DataTables())->make($source);
    }
}
