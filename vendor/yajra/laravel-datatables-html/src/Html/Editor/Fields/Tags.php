<?php

namespace Yajra\DataTables\Html\Editor\Fields;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @see https://editor.datatables.net/reference/field/tags
 */
class Tags extends Field
{
    protected string $type = 'tags';

    /**
     * @see https://editor.datatables.net/reference/field/tags#ajax
     */
    public function ajax(bool|string|null $url = true): static
    {
        $this->attributes['ajax'] = $url;

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#display
     */
    public function display(string $display): static
    {
        $this->attributes['display'] = $display;

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#escapeLabelHtml
     */
    public function escapeLabelHtml(bool $escape): static
    {
        $this->attributes['escapeLabelHtml'] = $escape;

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function i18n(array $i18n): static
    {
        $options = isset($this->attributes['i18n'])
            ? (array) $this->attributes['i18n']
            : [];

        $this->attributes['i18n'] = array_merge($options, $i18n);

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function addButton(string $text): static
    {
        return $this->i18n(['addButton' => $text]);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function inputPlaceholder(string $text): static
    {
        return $this->i18n(['inputPlaceholder' => $text]);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function noResults(string $text): static
    {
        return $this->i18n(['noResults' => $text]);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function title(string $text): static
    {
        return $this->i18n(['title' => $text]);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#i18n
     */
    public function placeholder(string $text): static
    {
        return $this->i18n(['placeholder' => $text]);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#limit
     */
    public function limit(int $limit): static
    {
        $this->attributes['limit'] = $limit;

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#multiple
     */
    public function multiple(bool $multiple = true): static
    {
        $this->attributes['multiple'] = $multiple;

        return $this;
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#options
     */
    public function options(array|Arrayable $options): static
    {
        return parent::options($options);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#separator
     */
    public function separator(string $separator = ','): static
    {
        return parent::separator($separator);
    }

    /**
     * @see https://editor.datatables.net/reference/field/tags#unique
     */
    public function unique(bool $unique = true): static
    {
        $this->attributes['unique'] = $unique;

        return $this;
    }
}
