<?php

namespace Yajra\DataTables\Html\Options\Languages;

trait Select
{
    /**
     * Set language select option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.select
     */
    public function languageSelect(array $value): static
    {
        return $this->language(['select' => $value]);
    }

    /**
     * Set language select cells option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.select.cells
     */
    public function languageSelectCells(array|string $value): static
    {
        return $this->languageSelect(['cells' => $value]);
    }

    /**
     * Set language select columns option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.select.columns
     */
    public function languageSelectColumns(array|string $value): static
    {
        return $this->languageSelect(['columns' => $value]);
    }

    /**
     * Set language select rows option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.select.rows
     */
    public function languageSelectRows(array|string $value): static
    {
        return $this->languageSelect(['rows' => $value]);
    }
}
