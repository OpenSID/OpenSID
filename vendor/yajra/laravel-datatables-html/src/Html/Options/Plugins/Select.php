<?php

namespace Yajra\DataTables\Html\Options\Plugins;

use Yajra\DataTables\Html\Builder;

/**
 * DataTables - Select plugin option builder.
 *
 * @see https://datatables.net/extensions/select
 * @see https://datatables.net/reference/option/select
 */
trait Select
{
    /**
     * Set select blurable option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.blurable
     */
    public function selectBlurable(bool $value = true): static
    {
        return $this->select(['blurable' => $value]);
    }

    /**
     * Set select className option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.className
     */
    public function selectClassName(string $value = 'selected'): static
    {
        return $this->select(['className' => $value]);
    }

    /**
     * Append a class name to className option value.
     *
     * @return $this
     */
    public function selectAddClassName(string $class): static
    {
        if (! isset($this->attributes['select']['className'])) {
            $this->attributes['select']['className'] = $class;
        } else {
            $this->attributes['select']['className'] .= " $class";
        }

        return $this;
    }

    /**
     * Set select info option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.info
     */
    public function selectInfo(bool $value = true): static
    {
        return $this->select(['info' => $value]);
    }

    /**
     * Set select items option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.items
     */
    public function selectItems(string $value = 'row'): static
    {
        return $this->select(['items' => $value]);
    }

    /**
     * Set select items option value to row.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.items
     */
    public function selectItemsRow(): static
    {
        return $this->select(['items' => Builder::SELECT_ITEMS_ROW]);
    }

    /**
     * Set select items option value to column.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.items
     */
    public function selectItemsColumn(): static
    {
        return $this->select(['items' => Builder::SELECT_ITEMS_COLUMN]);
    }

    /**
     * Set select items option value to cell.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.items
     */
    public function selectItemsCell(): static
    {
        return $this->select(['items' => Builder::SELECT_ITEMS_CELL]);
    }

    /**
     * Set select selector option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.selector
     */
    public function selectSelector(string $value = 'td'): static
    {
        return $this->select(['selector' => $value]);
    }

    /**
     * Set select style option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyle(string $value = 'os'): static
    {
        return $this->select(['style' => $value]);
    }

    /**
     * Set select style option value to api.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyleApi(): static
    {
        return $this->select(['style' => Builder::SELECT_STYLE_API]);
    }

    /**
     * Set select style option value to single.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyleSingle(): static
    {
        return $this->select(['style' => Builder::SELECT_STYLE_SINGLE]);
    }

    /**
     * Set select option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select
     */
    public function select(bool|array $value = true): static
    {
        return $this->setPluginAttribute('select', $value);
    }

    /**
     * Set select style option value to multi.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyleMulti(): static
    {
        return $this->select(['style' => Builder::SELECT_STYLE_MULTI]);
    }

    /**
     * Set select style option value to os.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyleOS(): static
    {
        return $this->select(['style' => Builder::SELECT_STYLE_OS]);
    }

    /**
     * Set select style option value to multi+shift.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/select.style
     */
    public function selectStyleMultiShift(): static
    {
        return $this->select(['style' => Builder::SELECT_STYLE_MULTI_SHIFT]);
    }

    /**
     * Select keyboard navigation and selection.
     *
     * @return $this
     *
     * @see https://datatables.net/extensions/select/examples/initialisation/keys
     */
    public function selectKeys(bool $enabled = true): static
    {
        return $this->select(['keys' => $enabled]);
    }

    public function getSelect(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['select'] ?? true;
        }

        return $this->attributes['select'][$key] ?? false;
    }
}
