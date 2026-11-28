<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - Responsive plugin option builder.
 *
 * @see https://datatables.net/extensions/responsive
 * @see https://datatables.net/reference/option/responsive
 * @see https://datatables.net/reference/option/#responsive
 */
trait Responsive
{
    /**
     * Set responsive breakpoints option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.breakpoints
     */
    public function responsiveBreakpoints(array $value): static
    {
        return $this->responsive(['breakpoints' => $value]);
    }

    /**
     * Set responsive option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive
     */
    public function responsive(array|bool $value = true): static
    {
        return $this->setPluginAttribute('responsive', $value);
    }

    /**
     * Set responsive details display option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.details.display
     */
    public function responsiveDetailsDisplay(array|string $value): static
    {
        return $this->responsiveDetails(['display' => $value]);
    }

    /**
     * Set responsive details option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.details
     */
    public function responsiveDetails(bool|array $value): static
    {
        $responsive = (array) $this->getResponsive();
        if (is_array($value)) {
            $responsive['details'] = array_merge((array) ($responsive['details'] ?? []), $value);
        } else {
            $responsive['details'] = $value;
        }

        return $this->responsive($responsive);
    }

    /**
     * Set responsive details renderer option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.details.renderer
     */
    public function responsiveDetailsRenderer(string $value): static
    {
        return $this->responsiveDetails(['renderer' => $value]);
    }

    /**
     * Set responsive details target option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.details.target
     */
    public function responsiveDetailsTarget(int|string $value): static
    {
        return $this->responsiveDetails(['target' => $value]);
    }

    /**
     * Set responsive details type option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.details.type
     */
    public function responsiveDetailsType(string $value): static
    {
        return $this->responsiveDetails(['type' => $value]);
    }

    /**
     * Set responsive orthogonal option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/responsive.orthogonal
     */
    public function responsiveOrthogonal(string $value): static
    {
        return $this->responsive(['orthogonal' => $value]);
    }

    public function getResponsive(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['responsive'] ?? true;
        }

        return $this->attributes['responsive'][$key] ?? false;
    }
}
