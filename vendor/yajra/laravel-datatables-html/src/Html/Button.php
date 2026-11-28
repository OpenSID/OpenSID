<?php

namespace Yajra\DataTables\Html;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;

class Button extends Fluent implements Arrayable
{
    use HasAuthorizations;
    use Macroable;

    /**
     * Make a new button instance.
     */
    public static function make(array|string $options = []): static
    {
        if (is_string($options)) {
            return new static(['extend' => $options]);
        }

        return new static($options);
    }

    /**
     * Make a raw button that does not extend anything.
     */
    public static function raw(array|string $options = []): static
    {
        if (is_string($options)) {
            return new static(['text' => $options]);
        }

        return new static($options);
    }

    /**
     * Set attr option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.attr
     */
    public function attr(array $value): static
    {
        $this->attributes['attr'] = $value;

        return $this;
    }

    /**
     * Set available option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.available
     */
    public function available(string $value): static
    {
        if ($this->isFunction($value)) {
            $this->attributes['available'] = $value;
        } else {
            $this->attributes['available'] = "function(dt, config) { $value }";
        }

        return $this;
    }

    /**
     * Check if a given value is a function.
     */
    protected function isFunction(string $value): bool
    {
        return str_starts_with($value, 'function');
    }

    /**
     * Set enabled option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.enabled
     */
    public function enabled(bool $value = true): static
    {
        $this->attributes['enabled'] = $value;

        return $this;
    }

    /**
     * Set init option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.init
     */
    public function init(string $value): static
    {
        if ($this->isFunction($value)) {
            $this->attributes['init'] = $value;
        } else {
            $this->attributes['init'] = "function(dt, node, config) { $value }";
        }

        return $this;
    }

    /**
     * Set key option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.key
     */
    public function key(array|string $value): static
    {
        $this->attributes['key'] = $value;

        return $this;
    }

    /**
     * Set extend option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.extend
     */
    public function extend(string $value): static
    {
        $this->attributes['extend'] = $value;

        return $this;
    }

    /**
     * Set editor option value.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/button
     */
    public function editor(string $value): static
    {
        $this->attributes['editor'] = $value;

        return $this;
    }

    /**
     * Set buttons option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons
     */
    public function buttons(array $buttons): static
    {
        foreach ($buttons as $key => $button) {
            if ($button instanceof Arrayable) {
                $buttons[$key] = $button->toArray();
            }
        }

        $this->attributes['buttons'] = $buttons;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/api/cancelButton
     */
    public function formButtons(array $buttons): static
    {
        foreach ($buttons as $key => $button) {
            if ($button instanceof Arrayable) {
                $buttons[$key] = $button->toArray();
            }
        }

        $this->attributes['formButtons'] = $buttons;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/examples/api/removeMessage
     * @see https://editor.datatables.net/reference/button/create
     * @see https://editor.datatables.net/reference/button/edit
     * @see https://editor.datatables.net/reference/button/remove
     */
    public function formMessage(string $message): static
    {
        $this->attributes['formMessage'] = $message;

        return $this;
    }

    /**
     * @return $this
     *
     * @see https://editor.datatables.net/reference/button/create
     * @see https://editor.datatables.net/reference/button/edit
     * @see https://editor.datatables.net/reference/button/remove
     */
    public function formTitle(string $title): static
    {
        $this->attributes['formTitle'] = $title;

        return $this;
    }

    /**
     * Set className option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.className
     */
    public function className(string $value): static
    {
        $this->attributes['className'] = $value;

        return $this;
    }

    /**
     * Set destroy option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.destroy
     */
    public function destroy(string $value): static
    {
        if ($this->isFunction($value)) {
            $this->attributes['destroy'] = $value;
        } else {
            $this->attributes['destroy'] = "function(dt, node, config) { $value }";
        }

        return $this;
    }

    /**
     * Set customize option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/button/excelHtml5
     */
    public function customize(string $value): static
    {
        $this->attributes['customize'] = $value;

        return $this;
    }

    /**
     * Append a class name to column.
     *
     * @return $this
     */
    public function addClass(string $class): static
    {
        if (! isset($this->attributes['className'])) {
            $this->attributes['className'] = $class;

            return $this;
        }

        $this->attributes['className'] = $this->attributes['className']." $class";

        return $this;
    }

    /**
     * Set text option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.text
     */
    public function text(string $value): static
    {
        $this->attributes['text'] = $value;

        return $this;
    }

    /**
     * Set titleAttr option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.titleAttr
     */
    public function titleAttr(string $value): static
    {
        $this->attributes['titleAttr'] = $value;

        return $this;
    }

    /**
     * Set name option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.name
     */
    public function name(string $value): static
    {
        $this->attributes['name'] = $value;

        return $this;
    }

    /**
     * Set namespace option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.namespace
     */
    public function namespace(string $value): static
    {
        $this->attributes['namespace'] = $value;

        return $this;
    }

    /**
     * Set tag option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/buttons.buttons.tag
     */
    public function tag(string $value): static
    {
        $this->attributes['tag'] = $value;

        return $this;
    }

    /**
     * Set columns option value.
     *
     * @return $this
     */
    public function columns(array|string $value): static
    {
        $this->attributes['columns'] = $value;

        return $this;
    }

    /**
     * Set exportOptions option value.
     *
     * @return $this
     */
    public function exportOptions(array|string $value): static
    {
        $this->attributes['exportOptions'] = $value;

        return $this;
    }

    /**
     * Set action to submit the form.
     *
     * @return $this
     */
    public function actionSubmit(): static
    {
        return $this->action('function() { this.submit(); }');
    }

    /**
     * Set action option value.
     *
     * @return $this
     */
    public function action(string $value): static
    {
        if (str_starts_with($value, 'function')) {
            $this->attributes['action'] = $value;
        } else {
            $this->attributes['action'] = "function(e, dt, node, config) { $value }";
        }

        return $this;
    }

    /**
     * Set editor class action handler.
     *
     * @return $this
     */
    public function actionHandler(string $action): static
    {
        return $this->action("function() { this.submit(null, null, function(data) { data.action = '{$action}'; return data; }) }");
    }

    /**
     * Set action to close the form.
     *
     * @return $this
     */
    public function actionClose(): static
    {
        return $this->action('function() { this.close(); }');
    }

    /**
     * Set button alignment.
     *
     * @return $this
     */
    public function align(string $align = 'button-left'): static
    {
        $this->attributes['align'] = $align;

        return $this;
    }

    /**
     * Handle dynamic calls to the fluent instance or macroable methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        // Check if the method is a macro (Macroable functionality).
        if (static::hasMacro($method)) {
            $macro = static::$macros[$method];

            if ($macro instanceof Closure) {
                $macro = $macro->bindTo($this, static::class);
            }

            return $macro(...$parameters);
        }

        // Fallback to Fluent behavior if it's not a macro.
        $this->attributes[$method] = count($parameters) > 0 ? reset($parameters) : true;

        return $this;
    }

    /**
     * Request that the data be refreshed from the server when starting an edit.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/type/form-options#refresh
     */
    public function refresh(bool $value = true): static
    {
        $this->attributes['refresh'] = $value;

        return $this;
    }
}
