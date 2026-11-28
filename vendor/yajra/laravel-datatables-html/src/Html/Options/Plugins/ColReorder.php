<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - ColReorder plugin option builder.
 *
 * @see https://datatables.net/extensions/colreorder/
 * @see https://datatables.net/reference/option/colReorder
 * @see https://datatables.net/reference/option/#colReorder
 */
trait ColReorder
{
    /**
     * Set colReorder enable option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder.enable
     */
    public function colReorderEnable(bool $value = true): static
    {
        return $this->colReorder(['enable' => $value]);
    }

    /**
     * Set colReorder option value.
     * Enable and configure the AutoFill extension for DataTables.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder
     */
    public function colReorder(array|bool $value = true): static
    {
        return $this->setPluginAttribute('colReorder', $value);
    }

    /**
     * Set colReorder fixedColumnsLeft option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder.fixedColumnsLeft
     */
    public function colReorderFixedColumnsLeft(int $value = 0): static
    {
        return $this->colReorder(['fixedColumnsLeft' => $value]);
    }

    /**
     * Set colReorder fixedColumnsRight option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder.fixedColumnsRight
     */
    public function colReorderFixedColumnsRight(int $value = 0): static
    {
        return $this->colReorder(['fixedColumnsRight' => $value]);
    }

    /**
     * Set colReorder order option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder.order
     */
    public function colReorderOrder(array $value = []): static
    {
        return $this->colReorder(['order' => $value]);
    }

    /**
     * Set colReorder realtime option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/colReorder.realtime
     */
    public function colReorderRealtime(bool $value = true): static
    {
        return $this->colReorder(['realtime' => $value]);
    }

    public function getColReorder(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['colReorder'] ?? true;
        }

        return $this->attributes['colReorder'][$key] ?? false;
    }
}
