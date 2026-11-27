<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - Scroller plugin option builder.
 *
 * @see https://datatables.net/extensions/scroller
 * @see https://datatables.net/reference/option/scroller
 * @see https://datatables.net/reference/option/#scroller
 */
trait Scroller
{
    /**
     * Set scroller boundaryScale option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller.boundaryScale
     */
    public function scrollerBoundaryScale(float $value = 0.5): static
    {
        return $this->scroller(['boundaryScale' => $value]);
    }

    /**
     * Set scroller option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller
     */
    public function scroller(array|bool $value = true): static
    {
        return $this->setPluginAttribute('scroller', $value);
    }

    /**
     * Set scroller displayBuffer option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller.displayBuffer
     */
    public function scrollerDisplayBuffer(int $value = 9): static
    {
        return $this->scroller(['displayBuffer' => $value]);
    }

    /**
     * Set scroller loadingIndicator option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller.loadingIndicator
     */
    public function scrollerLoadingIndicator(bool $value = true): static
    {
        return $this->scroller(['loadingIndicator' => $value]);
    }

    /**
     * Set scroller rowHeight option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller.rowHeight
     */
    public function scrollerRowHeight(int|string $value = 'auto'): static
    {
        return $this->scroller(['rowHeight' => $value]);
    }

    /**
     * Set scroller serverWait option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/scroller.serverWait
     */
    public function scrollerServerWait(int $value = 200): static
    {
        return $this->scroller(['serverWait' => $value]);
    }

    public function getScroller(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['scroller'] ?? true;
        }

        return $this->attributes['scroller'][$key] ?? false;
    }
}
