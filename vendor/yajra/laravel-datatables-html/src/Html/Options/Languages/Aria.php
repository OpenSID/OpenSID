<?php

namespace Yajra\DataTables\Html\Options\Languages;

trait Aria
{
    /**
     * Set language aria option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria
     */
    public function languageAria(array $value): static
    {
        return $this->language(['aria' => $value]);
    }

    /**
     * Set language aria paginate option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.paginate
     */
    public function languageAriaPaginate(array $value): static
    {
        return $this->languageAria(['paginate' => $value]);
    }

    /**
     * Set language aria paginate first option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.paginate.first
     */
    public function languageAriaPaginateFirst(string $value): static
    {
        return $this->languageAriaPaginate(['first' => $value]);
    }

    /**
     * Set language aria paginate last option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.paginate.last
     */
    public function languageAriaPaginateLast(string $value): static
    {
        return $this->languageAriaPaginate(['last' => $value]);
    }

    /**
     * Set language aria paginate next option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.paginate.next
     */
    public function languageAriaPaginateNext(string $value): static
    {
        return $this->languageAriaPaginate(['next' => $value]);
    }

    /**
     * Set language aria paginate previous option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.paginate.previous
     */
    public function languageAriaPaginatePrevious(string $value): static
    {
        return $this->languageAriaPaginate(['previous' => $value]);
    }

    /**
     * Set language aria sortAscending option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.sortAscending
     */
    public function languageAriaSortAscending(string $value): static
    {
        return $this->languageAria(['sortAscending' => $value]);
    }

    /**
     * Set language aria sortDescending option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.aria.sortDescending
     */
    public function languageAriaSortDescending(string $value): static
    {
        return $this->languageAria(['sortDescending' => $value]);
    }
}
