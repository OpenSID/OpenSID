<?php

namespace Yajra\DataTables\Html\Options;

/**
 * DataTables - Callbacks option builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasCallbacks
{
    /**
     * Set createdRow option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/createdRow
     */
    public function createdRow(string $script): static
    {
        $this->attributes['createdRow'] = $script;

        return $this;
    }

    /**
     * Set drawCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/drawCallback
     */
    public function drawCallback(string $script): static
    {
        $this->attributes['drawCallback'] = $script;

        return $this;
    }

    /**
     * Set drawCallback option value with Livewire integration.
     * Solution as per issue https://github.com/yajra/laravel-datatables/issues/2401.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/drawCallback
     */
    public function drawCallbackWithLivewire(?string $script = null): static
    {
        $js = "function(settings) {
            if (window.livewire) {
                window.livewire.rescan();
            }

            $script
        }";

        $this->attributes['drawCallback'] = $js;

        return $this;
    }

    /**
     * Set footerCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/footerCallback
     */
    public function footerCallback(string $script): static
    {
        $this->attributes['footerCallback'] = $script;

        return $this;
    }

    /**
     * Set formatNumber option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/formatNumber
     */
    public function formatNumber(string $script): static
    {
        $this->attributes['formatNumber'] = $script;

        return $this;
    }

    /**
     * Set headerCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/headerCallback
     */
    public function headerCallback(string $script): static
    {
        $this->attributes['headerCallback'] = $script;

        return $this;
    }

    /**
     * Set infoCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/infoCallback
     */
    public function infoCallback(string $script): static
    {
        $this->attributes['infoCallback'] = $script;

        return $this;
    }

    /**
     * Set initComplete option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/initComplete
     */
    public function initComplete(string $script): static
    {
        $this->attributes['initComplete'] = $script;

        return $this;
    }

    /**
     * Set preDrawCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/preDrawCallback
     */
    public function preDrawCallback(string $script): static
    {
        $this->attributes['preDrawCallback'] = $script;

        return $this;
    }

    /**
     * Set rowCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/rowCallback
     */
    public function rowCallback(string $script): static
    {
        $this->attributes['rowCallback'] = $script;

        return $this;
    }

    /**
     * Set stateLoadCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateLoadCallback
     */
    public function stateLoadCallback(string $script): static
    {
        $this->attributes['stateLoadCallback'] = $script;

        return $this;
    }

    /**
     * Set stateLoaded option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateLoaded
     */
    public function stateLoaded(string $script): static
    {
        $this->attributes['stateLoaded'] = $script;

        return $this;
    }

    /**
     * Set stateLoadParams option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateLoadParams
     */
    public function stateLoadParams(string $script): static
    {
        $this->attributes['stateLoadParams'] = $script;

        return $this;
    }

    /**
     * Set stateSaveCallback option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateSaveCallback
     */
    public function stateSaveCallback(string $script): static
    {
        $this->attributes['stateSaveCallback'] = $script;

        return $this;
    }

    /**
     * Set stateSaveParams option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/stateSaveParams
     */
    public function stateSaveParams(string $script): static
    {
        $this->attributes['stateSaveParams'] = $script;

        return $this;
    }
}
