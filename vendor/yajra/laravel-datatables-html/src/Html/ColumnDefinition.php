<?php

namespace Yajra\DataTables\Html;

/**
 * @see https://datatables.net/reference/option/columnDefs
 */
class ColumnDefinition extends Column
{
    /**
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes = $attributes;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columnDefs.targets
     */
    public function targets(array|string|int $value): static
    {
        $this->attributes['targets'] = $value;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns
     */
    public function columns(array $value): static
    {
        $this->attributes['columns'] = $value;

        return $this;
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        unset($array['attributes']);

        return $array;
    }
}
