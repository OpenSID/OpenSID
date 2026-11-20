<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - RowReorder plugin option builder.
 *
 * @see https://datatables.net/extensions/rowreorder
 * @see https://datatables.net/reference/option/rowReorder
 * @see https://datatables.net/reference/option/#rowReorder
 */
trait RowReorder
{
    /**
     * Set rowReorder dataSrc option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.dataSrc
     */
    public function rowReorderDataSrc(array|int $value = 0): static
    {
        return $this->rowReorder(['dataSrc' => $value]);
    }

    /**
     * Set rowReorder option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder
     */
    public function rowReorder(array|bool $value = true): static
    {
        return $this->setPluginAttribute('rowReorder', $value);
    }

    /**
     * Set rowReorder editor option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.editor
     */
    public function rowReorderEditor(?string $value = null): static
    {
        return $this->rowReorder(['editor' => $value]);
    }

    /**
     * Set rowReorder enable option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.enable
     */
    public function rowReorderEnable(bool $value = true): static
    {
        return $this->rowReorder(['enable' => $value]);
    }

    /**
     * Set rowReorder formOptions option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.formOptions
     */
    public function rowReorderFormOptions(array $value): static
    {
        return $this->rowReorder(['formOptions' => $value]);
    }

    /**
     * Set rowReorder selector option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.selector
     */
    public function rowReorderSelector(string $value = 'td:first-child'): static
    {
        return $this->rowReorder(['selector' => $value]);
    }

    /**
     * Set rowReorder snapX option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.snapX
     */
    public function rowReorderSnapX(bool|int $value = true): static
    {
        return $this->rowReorder(['snapX' => $value]);
    }

    /**
     * Set rowReorder update option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowReorder.update
     */
    public function rowReorderUpdate(bool $value = true): static
    {
        return $this->rowReorder(['update' => $value]);
    }

    public function getRowReorder(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['rowReorder'] ?? true;
        }

        return $this->attributes['rowReorder'][$key] ?? false;
    }
}
