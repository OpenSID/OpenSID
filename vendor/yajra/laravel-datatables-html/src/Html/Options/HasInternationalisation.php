<?php

namespace Yajra\DataTables\Html\Options;

/**
 * DataTables - Internationalisation option builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasInternationalisation
{
    use Languages\Aria;
    use Languages\AutoFill;
    use Languages\Paginate;
    use Languages\Select;

    /**
     * Set language decimal option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.decimal
     */
    public function languageDecimal(string $value): static
    {
        return $this->language(['decimal' => $value]);
    }

    /**
     * Set language option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language
     */
    public function language(array|string $value): static
    {
        if (is_array($value)) {
            $this->attributes['language'] = array_merge((array) ($this->attributes['language'] ?? []), $value);
        } else {
            $this->attributes['language']['url'] = $value;
        }

        return $this;
    }

    /**
     * Set language emptyTable option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.emptyTable
     */
    public function languageEmptyTable(string $value): static
    {
        return $this->language(['emptyTable' => $value]);
    }

    /**
     * Set language info option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.info
     */
    public function languageInfo(string $value): static
    {
        return $this->language(['info' => $value]);
    }

    /**
     * Set language infoEmpty option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.infoEmpty
     */
    public function languageInfoEmpty(string $value): static
    {
        return $this->language(['infoEmpty' => $value]);
    }

    /**
     * Set language infoFiltered option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.infoFiltered
     */
    public function languageInfoFiltered(string $value): static
    {
        return $this->language(['infoFiltered' => $value]);
    }

    /**
     * Set language infoPostFix option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.infoPostFix
     */
    public function languageInfoPostFix(string $value): static
    {
        return $this->language(['infoPostFix' => $value]);
    }

    /**
     * Set language lengthMenu option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.lengthMenu
     */
    public function languageLengthMenu(string $value): static
    {
        return $this->language(['lengthMenu' => $value]);
    }

    /**
     * Set language loadingRecords option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.loadingRecords
     */
    public function languageLoadingRecords(string $value): static
    {
        return $this->language(['loadingRecords' => $value]);
    }

    /**
     * Set language processing option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.processing
     */
    public function languageProcessing(string $value): static
    {
        return $this->language(['processing' => $value]);
    }

    /**
     * Set language search option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.search
     */
    public function languageSearch(string $value): static
    {
        return $this->language(['search' => $value]);
    }

    /**
     * Set language searchPlaceholder option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.searchPlaceholder
     */
    public function languageSearchPlaceholder(string $value): static
    {
        return $this->language(['searchPlaceholder' => $value]);
    }

    /**
     * Set language thousands option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.thousands
     */
    public function languageThousands(string $value): static
    {
        return $this->language(['thousands' => $value]);
    }

    /**
     * Set language url option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.url
     */
    public function languageUrl(string $value): static
    {
        return $this->language(['url' => $value]);
    }

    /**
     * Set language zeroRecords option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/language.zeroRecords
     */
    public function languageZeroRecords(string $value): static
    {
        return $this->language(['zeroRecords' => $value]);
    }

    public function getLanguage(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['language'] ?? [];
        }

        return $this->attributes['language'][$key] ?? '';
    }
}
