<?php

namespace Yajra\DataTables\Html;

/**
 * DataTables - Options builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasOptions
{
    use Options\HasAjax;
    use Options\HasCallbacks;
    use Options\HasColumns;
    use Options\HasFeatures;
    use Options\HasInternationalisation;
    use Options\Plugins\AutoFill;
    use Options\Plugins\Buttons;
    use Options\Plugins\ColReorder;
    use Options\Plugins\ColumnControl;
    use Options\Plugins\FixedColumns;
    use Options\Plugins\FixedHeader;
    use Options\Plugins\KeyTable;
    use Options\Plugins\Responsive;
    use Options\Plugins\RowGroup;
    use Options\Plugins\RowReorder;
    use Options\Plugins\Scroller;
    use Options\Plugins\SearchPanes;
    use Options\Plugins\Select;

    /**
     * Set deferLoading option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/deferLoading
     */
    public function deferLoading(array|int|null $value = null): static
    {
        $this->attributes['deferLoading'] = $value;

        return $this;
    }

    /**
     * Set destroy option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/destroy
     */
    public function destroy(bool $value = false): static
    {
        $this->attributes['destroy'] = $value;

        return $this;
    }

    /**
     * Set displayStart option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/displayStart
     */
    public function displayStart(int $value = 0): static
    {
        $this->attributes['displayStart'] = $value;

        return $this;
    }

    /**
     * Set dom option value.
     *
     * @return $this
     *
     * @deprecated Use layout() method instead.
     * @see https://datatables.net/reference/option/dom
     */
    public function dom(string $value): static
    {
        $this->attributes['dom'] = $value;

        return $this;
    }

    /**
     * Set layout option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/layout
     */
    public function layout(array|Layout|callable $value): static
    {
        if ($value instanceof Layout) {
            $value = $value->toArray();
        }

        if (is_callable($value)) {
            $layout = new Layout;
            $value($layout);
            $value = $layout->toArray();
        }

        $this->attributes['layout'] = $value;

        return $this;
    }

    /**
     * Set lengthMenu option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/lengthMenu
     */
    public function lengthMenu(array $value = [10, 25, 50, 100]): static
    {
        $this->attributes['lengthMenu'] = $value;

        return $this;
    }

    /**
     * Set orders option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/order
     */
    public function orders(array $value): static
    {
        $this->attributes['order'] = $value;

        return $this;
    }

    /**
     * Set orderCellsTop option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/orderCellsTop
     */
    public function orderCellsTop(bool $value = false): static
    {
        $this->attributes['orderCellsTop'] = $value;

        return $this;
    }

    /**
     * Set orderClasses option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/orderClasses
     */
    public function orderClasses(bool $value = true): static
    {
        $this->attributes['orderClasses'] = $value;

        return $this;
    }

    /**
     * Order option builder.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/order
     */
    public function orderBy(array|int $index, string $direction = 'desc'): static
    {
        if ($direction != 'desc') {
            $direction = 'asc';
        }

        if (is_array($index)) {
            $this->attributes['order'][] = $index;
        } else {
            $this->attributes['order'][] = [$index, $direction];
        }

        return $this;
    }

    /**
     * Order Fixed option builder.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/orderFixed
     */
    public function orderByFixed(array|int $index, string $direction = 'desc'): static
    {
        if ($direction != 'desc') {
            $direction = 'asc';
        }

        if (is_array($index)) {
            $this->attributes['orderFixed'][] = $index;
        } else {
            $this->attributes['orderFixed'][] = [$index, $direction];
        }

        return $this;
    }

    /**
     * Set orderMulti option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/orderMulti
     */
    public function orderMulti(bool $value = true): static
    {
        $this->attributes['orderMulti'] = $value;

        return $this;
    }

    /**
     * Set pageLength option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/pageLength
     */
    public function pageLength(int $value = 10): static
    {
        $this->attributes['pageLength'] = $value;

        return $this;
    }

    /**
     * Set pagingType option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/pagingType
     */
    public function pagingType(string $value = 'simple_numbers'): static
    {
        $this->attributes['pagingType'] = $value;

        return $this;
    }

    /**
     * Set renderer option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/renderer
     */
    public function renderer(string $value = 'bootstrap'): static
    {
        $this->attributes['renderer'] = $value;

        return $this;
    }

    /**
     * Set retrieve option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/retrieve
     */
    public function retrieve(bool $value = false): static
    {
        $this->attributes['retrieve'] = $value;

        return $this;
    }

    /**
     * Set rowId option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowId
     */
    public function rowId(string $value = 'DT_RowId'): static
    {
        $this->attributes['rowId'] = $value;

        return $this;
    }

    /**
     * Set scrollCollapse option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scrollCollapse
     */
    public function scrollCollapse(bool $value = false): static
    {
        $this->attributes['scrollCollapse'] = $value;

        return $this;
    }

    /**
     * Set search option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/search
     */
    public function search(array $value): static
    {
        $this->attributes['search'] = $value;

        return $this;
    }

    /**
     * Set searchCols option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchCols
     */
    public function searchCols(array $value): static
    {
        $this->attributes['searchCols'] = $value;

        return $this;
    }

    /**
     * Set searchDelay option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/searchDelay
     */
    public function searchDelay(int $value): static
    {
        $this->attributes['searchDelay'] = $value;

        return $this;
    }

    /**
     * Set stateDuration option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateDuration
     */
    public function stateDuration(int $value): static
    {
        $this->attributes['stateDuration'] = $value;

        return $this;
    }

    /**
     * Set stripeClasses option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stripeClasses
     */
    public function stripeClasses(array $value): static
    {
        $this->attributes['stripeClasses'] = $value;

        return $this;
    }

    /**
     * Set tabIndex option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/tabIndex
     */
    public function tabIndex(int $value = 0): static
    {
        $this->attributes['tabIndex'] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setPluginAttribute(string $key, array|bool $value): static
    {
        if (is_array($value)) {
            $this->attributes[$key] = array_merge((array) ($this->attributes[$key] ?? []), $value);
        } else {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function getPluginAttribute(string $plugin, ?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes[$plugin] ?? true;
        }

        return $this->attributes[$plugin][$key] ?? false;
    }
}
