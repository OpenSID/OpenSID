<?php

namespace Yajra\DataTables\Html\Options;

/**
 * DataTables - Features option builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasFeatures
{
    /**
     * Set autoWidth option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/autoWidth
     */
    public function autoWidth(bool $value = true): static
    {
        $this->attributes['autoWidth'] = $value;

        return $this;
    }

    /**
     * Set deferRender option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/deferRender
     */
    public function deferRender(bool $value = true): static
    {
        $this->attributes['deferRender'] = $value;

        return $this;
    }

    /**
     * Set info option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/info
     */
    public function info(bool $value = true): static
    {
        $this->attributes['info'] = $value;

        return $this;
    }

    /**
     * Set lengthChange option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/lengthChange
     */
    public function lengthChange(bool $value = true): static
    {
        $this->attributes['lengthChange'] = $value;

        return $this;
    }

    /**
     * Set ordering option value.
     *
     * @param  bool|array{indicators: bool, handler: bool}  $value
     * @return $this
     *
     * @see https://datatables.net/reference/option/ordering
     */
    public function ordering(bool|array $value = true): static
    {
        $this->attributes['ordering'] = $value;

        return $this;
    }

    /**
     * Set processing option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/processing
     */
    public function processing(bool $value = true): static
    {
        $this->attributes['processing'] = $value;

        return $this;
    }

    /**
     * Set scrollX option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scrollX
     */
    public function scrollX(bool $value = true): static
    {
        $this->attributes['scrollX'] = $value;

        return $this;
    }

    /**
     * Set scrollY option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scrollY
     */
    public function scrollY(bool|string $value = true): static
    {
        $this->attributes['scrollY'] = $value;

        return $this;
    }

    /**
     * Set paging option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/paging
     */
    public function paging(bool $value = true): static
    {
        $this->attributes['paging'] = $value;

        return $this;
    }

    /**
     * Set searching option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/searching
     */
    public function searching(bool $value = true): static
    {
        $this->attributes['searching'] = $value;

        return $this;
    }

    /**
     * Set serverSide option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/serverSide
     */
    public function serverSide(bool $value = true): static
    {
        $this->attributes['serverSide'] = $value;

        return $this;
    }

    /**
     * Set stateSave option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateSave
     */
    public function stateSave(bool $value = true): static
    {
        $this->attributes['stateSave'] = $value;

        return $this;
    }
}
