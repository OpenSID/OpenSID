<?php

namespace Yajra\DataTables\Html\Options\Languages;

trait Paginate
{
    /**
     * Set language aria paginate option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.paginate
     */
    public function languagePaginate(array $value): static
    {
        return $this->language(['paginate' => $value]);
    }

    /**
     * Set language aria paginate first option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.paginate.first
     */
    public function languagePaginateFirst(string $value): static
    {
        return $this->languagePaginate(['first' => $value]);
    }

    /**
     * Set language aria paginate last option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.paginate.last
     */
    public function languagePaginateLast(string $value): static
    {
        return $this->languagePaginate(['last' => $value]);
    }

    /**
     * Set language aria paginate next option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.paginate.next
     */
    public function languagePaginateNext(string $value): static
    {
        return $this->languagePaginate(['next' => $value]);
    }

    /**
     * Set language aria paginate previous option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.paginate.previous
     */
    public function languagePaginatePrevious(string $value): static
    {
        return $this->languagePaginate(['previous' => $value]);
    }
}
