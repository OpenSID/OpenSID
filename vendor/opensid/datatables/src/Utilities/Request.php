<?php

namespace Fluent\DataTables\Utilities;

class Request
{
    /**
     * @var CI_Input
     */
    protected $request;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->request = get_instance()->input;
    }

    /**
     * Proxy non existing method calls to request class.
     *
     * @param  mixed  $name
     * @param  mixed  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->request->{$name}(...$arguments);
    }

    /**
     * Get attributes from request instance.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->request->get($name);
    }

    /**
     * Get all columns request input.
     *
     * @return array
     */
    public function columns()
    {
        return (array) $this->request->get('columns');
    }

    /**
     * Check if DataTables is searchable.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return $this->request->get('search[value]') != '';
    }

    /**
     * Check if DataTables must uses regular expressions.
     *
     * @param  int  $index
     * @return bool
     */
    public function isRegex($index)
    {
        return $this->request->get("columns[{$index}][search][regex]") === 'true';
    }

    /**
     * Get orderable columns.
     *
     * @return array
     */
    public function orderableColumns()
    {
        if (! $this->isOrderable()) {
            return [];
        }

        $orderable = [];
        for ($i = 0, $c = count($this->request->get('order')); $i < $c; $i++) {
            $order_col = (int) $this->request->get("order[{$i}][column]");
            $order_dir = strtolower($this->request->get("order[{$i}][dir]")) === 'asc' ? 'asc' : 'desc';
            if ($this->isColumnOrderable($order_col)) {
                $orderable[] = ['column' => $order_col, 'direction' => $order_dir];
            }
        }

        return $orderable;
    }

    /**
     * Check if DataTables ordering is enabled.
     *
     * @return bool
     */
    public function isOrderable()
    {
        return $this->request->get('order') && count($this->request->get('order')) > 0;
    }

    /**
     * Check if a column is orderable.
     *
     * @param  int  $index
     * @return bool
     */
    public function isColumnOrderable($index)
    {
        return $this->request->get("columns[{$index}][orderable]") == 'true';
    }

    /**
     * Get searchable column indexes.
     *
     * @return array
     */
    public function searchableColumnIndex()
    {
        $searchable = [];
        for ($i = 0, $c = count($this->request->get('columns')); $i < $c; $i++) {
            if ($this->isColumnSearchable($i, false)) {
                $searchable[] = $i;
            }
        }

        return $searchable;
    }

    /**
     * Check if a column is searchable.
     *
     * @param  int  $i
     * @param  bool  $column_search
     * @return bool
     */
    public function isColumnSearchable($i, $column_search = true)
    {
        if ($column_search) {
            return
                (
                    $this->request->get("columns[{$i}][searchable]") === 'true'
                    ||
                    $this->request->get("columns[{$i}[searchable]") === true
                )
                && $this->columnKeyword($i) != '';
        }

        return
            $this->request->get("columns[{$i}][searchable]") === 'true'
            ||
            $this->request->get("columns[{$i}][searchable]") === true;
    }

    /**
     * Get column's search value.
     *
     * @param  int  $index
     * @return string
     */
    public function columnKeyword($index)
    {
        $keyword = $this->request->get("columns[{$index}][search][value]") ?? '';

        return $this->prepareKeyword($keyword);
    }

    /**
     * Prepare keyword string value.
     *
     * @param  string|array  $keyword
     * @return string
     */
    protected function prepareKeyword($keyword)
    {
        if (is_array($keyword)) {
            return implode(' ', $keyword);
        }

        return $keyword;
    }

    /**
     * Get global search keyword.
     *
     * @return string
     */
    public function keyword()
    {
        $keyword = $this->request->get('search[value]') ?? '';

        return $this->prepareKeyword($keyword);
    }

    /**
     * Get column identity from input or database.
     *
     * @param  int  $i
     * @return string
     */
    public function columnName($i)
    {
        $column = $this->request->get("columns[{$i}]");

        return isset($column['name']) && $column['name'] != '' ? $column['name'] : $column['data'];
    }

    /**
     * Check if DataTables allow pagination.
     *
     * @return bool
     */
    public function isPaginationable()
    {
        return ! is_null($this->request->get('start')) &&
            ! is_null($this->request->get('length')) &&
            $this->request->get('length') != -1;
    }
}
