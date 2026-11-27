<?php

namespace Yajra\DataTables\Html\Options\Plugins;

/**
 * DataTables - KeyTable plugin option builder.
 *
 * @see https://datatables.net/extensions/keytable/
 * @see https://datatables.net/reference/option/keys
 * @see https://datatables.net/reference/option/#keys
 */
trait KeyTable
{
    /**
     * Set keys blurable option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.blurable
     */
    public function keysBlurable(bool $value = true): static
    {
        return $this->keys(['blurable' => $value]);
    }

    /**
     * Set keys option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys
     */
    public function keys(array|bool $value = true): static
    {
        return $this->setPluginAttribute('keys', $value);
    }

    /**
     * Set keys className option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.className
     */
    public function keysClassName(string $value = 'focus'): static
    {
        return $this->keys(['className' => $value]);
    }

    /**
     * Set keys clipboard option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.clipboard
     */
    public function keysClipboard(bool $value = true): static
    {
        return $this->keys(['clipboard' => $value]);
    }

    /**
     * Set keys clipboardOrthogonal option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.clipboardOrthogonal
     */
    public function keysClipboardOrthogonal(string $value = 'display'): static
    {
        return $this->keys(['clipboardOrthogonal' => $value]);
    }

    /**
     * Set keys columns option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.columns
     */
    public function keysColumns(array|string $value): static
    {
        return $this->keys(['columns' => $value]);
    }

    /**
     * Set keys editAutoSelect option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.editAutoSelect
     */
    public function keysEditAutoSelect(bool $value = true): static
    {
        return $this->keys(['editAutoSelect' => $value]);
    }

    /**
     * Set keys editOnFocus option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.editOnFocus
     */
    public function keysEditOnFocus(bool $value = true): static
    {
        return $this->keys(['editOnFocus' => $value]);
    }

    /**
     * Set keys editor option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.editor
     */
    public function keysEditor(string $value): static
    {
        return $this->keys(['editor' => $value]);
    }

    /**
     * Set keys editorKeys option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.editorKeys
     */
    public function keysEditorKeys(string $value = 'navigation-only'): static
    {
        return $this->keys(['editorKeys' => $value]);
    }

    /**
     * Set keys focus option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.focus
     */
    public function keysFocus(string $value): static
    {
        return $this->keys(['focus' => $value]);
    }

    /**
     * Set key's keys option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.keys
     */
    public function keysKeys(?array $value = null): static
    {
        return $this->keys(['keys' => $value]);
    }

    /**
     * Set keys tabIndex option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/keys.tabIndex
     */
    public function keysTabIndex(int $value): static
    {
        return $this->keys(['tabIndex' => $value]);
    }

    public function getKeys(?string $key = null): mixed
    {
        if (is_null($key)) {
            return $this->attributes['keys'] ?? true;
        }

        return $this->attributes['keys'][$key] ?? false;
    }
}
