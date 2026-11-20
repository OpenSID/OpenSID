<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - FixedColumns plugin option builder.
 *
 * @see https://datatables.net/extensions/fixedcolumns/
 * @see https://datatables.net/reference/option/fixedColumns
 * @see https://datatables.net/reference/option/#fixedColumns
 */
trait FixedColumns
{
    /**
     * Set fixedColumns heightMatch option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/fixedColumns.heightMatch
     */
    public function fixedColumnsHeightMatch(string $value = 'semiauto'): static
    {
        return $this->fixedColumns(['heightMatch' => $value]);
    }

    /**
     * Set fixedColumns option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/fixedColumns
     */
    public function fixedColumns(array|bool $value = true): static
    {
        return $this->setPluginAttribute('fixedColumns', $value);
    }

    /**
     * Set fixedColumns leftColumns option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/fixedColumns.leftColumns
     */
    public function fixedColumnsLeftColumns(int $value = 1): static
    {
        return $this->fixedColumns(['leftColumns' => $value]);
    }

    /**
     * Set fixedColumns rightColumns option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/fixedColumns.rightColumns
     */
    public function fixedColumnsRightColumns(int $value = 0): static
    {
        return $this->fixedColumns(['rightColumns' => $value]);
    }

    public function getFixedColumns(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['fixedColumns'] ?? true;
        }

        return $this->attributes['fixedColumns'][$key] ?? false;
    }
}
