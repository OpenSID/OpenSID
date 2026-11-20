<?php

namespace Yajra\DataTables\Html\Editor;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Yajra\DataTables\Html\Editor\Fields\Field;
use Yajra\DataTables\Html\Fluent;
use Yajra\DataTables\Html\HasAuthorizations;
use Yajra\DataTables\Utilities\Helper;

/**
 * @property string $instance
 * @property string|null $table
 * @property string|array|null $ajax
 * @property array $fields
 * @property string|null $template
 * @property string $idSrc
 * @property string $display
 * @property string $scripts
 * @property array $formOptions
 */
class Editor extends Fluent
{
    use HasAuthorizations;
    use HasEvents, Macroable {
        Macroable::__call as macroCall;
    }

    final public const DISPLAY_LIGHTBOX = 'lightbox';

    final public const DISPLAY_ENVELOPE = 'envelope';

    final public const DISPLAY_BOOTSTRAP = 'bootstrap';

    final public const DISPLAY_FOUNDATION = 'foundation';

    final public const DISPLAY_JQUERYUI = 'jqueryui';

    public array $events = [];

    /**
     * Editor constructor.
     *
     * @param  string|array  $instance
     */
    public function __construct($instance = 'editor')
    {
        $attributes = [];
        $attributes['instance'] = $instance;

        parent::__construct($attributes);
    }

    /**
     * Make new Editor instance.
     */
    public static function make(array|string $instance = 'editor'): static
    {
        if (is_array($instance)) {
            $instance = $instance['editor'] ?? 'editor';
        }

        return new static($instance);
    }

    /**
     * Append raw scripts.
     *
     * @return $this
     */
    public function scripts(string $scripts): static
    {
        $this->attributes['scripts'] = $scripts;

        return $this;
    }

    /**
     * Set Editor's variable name / instance.
     *
     * @return $this
     */
    public function instance(string $instance): static
    {
        $this->attributes['instance'] = $instance;

        return $this;
    }

    /**
     * Set Editor's ajax parameter.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/ajax
     */
    public function ajax(array|string $ajax): static
    {
        $this->attributes['ajax'] = $ajax;

        return $this;
    }

    /**
     * Set Editor's table source.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/table
     */
    public function table(string $table): static
    {
        $this->attributes['table'] = $table;

        return $this;
    }

    /**
     * Set Editor's idSrc option.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/idSrc
     */
    public function idSrc(string $idSrc = 'DT_RowId'): static
    {
        $this->attributes['idSrc'] = $idSrc;

        return $this;
    }

    /**
     * Set Editor's display option.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/display
     */
    public function display(string $display): static
    {
        $this->attributes['display'] = $display;

        return $this;
    }

    /**
     * Set Editor's fields.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/fields
     */
    public function fields(array $fields): static
    {
        $this->attributes['fields'] = $fields;

        return $this;
    }

    /**
     * Set Editor's bubble formOptions.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/formOptions.bubble
     */
    public function formOptionsBubble(array|FormOptions $formOptions): static
    {
        return $this->formOptions(['bubble' => Helper::castToArray($formOptions)]);
    }

    /**
     * Set Editor's formOptions.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/formOptions
     * @see https://editor.datatables.net/reference/type/form-options
     */
    public function formOptions(array $formOptions): static
    {
        $this->attributes['formOptions'] = $formOptions;

        return $this;
    }

    /**
     * Set Editor's inline formOptions.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/formOptions.inline
     */
    public function formOptionsInline(array|FormOptions $formOptions): static
    {
        return $this->formOptions(['inline' => Helper::castToArray($formOptions)]);
    }

    /**
     * Set Editor's main formOptions.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/formOptions.main
     */
    public function formOptionsMain(array|FormOptions $formOptions): static
    {
        return $this->formOptions(['main' => Helper::castToArray($formOptions)]);
    }

    /**
     * Set Editor's language.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/i18n
     */
    public function language(array $language): static
    {
        $this->attributes['i18n'] = $language;

        return $this;
    }

    /**
     * Set Editor's template.
     *
     * @return $this
     *
     * @see https://editor.datatables.net/reference/option/template
     */
    public function template(string $template): static
    {
        $this->attributes['template'] = $template;

        return $this;
    }

    /**
     * Convert the fluent instance to an array.
     */
    public function toArray(): array
    {
        if (! $this->isAuthorized()) {
            return [];
        }

        $array = parent::toArray();

        unset($array['events']);

        foreach ((array) Arr::get($array, 'fields', []) as $key => &$field) {
            if ($field instanceof Field) {
                Arr::set($array['fields'], $key, $field->toArray());
            }
        }

        return $array;
    }

    /**
     * Convert the fluent instance to JSON.
     *
     * @param  int  $options
     */
    public function toJson($options = 0): string
    {
        $parameters = $this->jsonSerialize();

        unset($parameters['events']);

        return Helper::toJsonScript($parameters, $options);
    }

    /**
     * Hide fields on create action.
     *
     * @return $this
     */
    public function hiddenOnCreate(array $fields): static
    {
        return $this->hiddenOn('create', $fields);
    }

    /**
     * Hide fields on specific action.
     *
     * @return $this
     */
    public function hiddenOn(string $action, array $fields): static
    {
        $script = 'function(e, mode, action) {';
        $script .= "if (action === '$action') {";
        foreach ($fields as $field) {
            $script .= "this.hide('$field');";
        }
        $script .= '} else {';
        foreach ($fields as $field) {
            $script .= "this.show('$field');";
        }
        $script .= '}';
        $script .= 'return true;';
        $script .= '}';

        $this->onPreOpen($script);

        return $this;
    }

    /**
     * Hide fields on edit action.
     *
     * @return $this
     */
    public function hiddenOnEdit(array $fields): static
    {
        return $this->hiddenOn('edit', $fields);
    }

    public function __call($method, $parameters): static
    {
        if (Str::startsWith($method, 'on')) {
            $event = Str::camel(substr($method, 2, strlen($method) - 2));

            return $this->on($event, $parameters[0]);
        }

        $macroCall = $this->macroCall($method, $parameters);

        if (! $macroCall instanceof Editor) {
            abort(500, sprintf('Method %s::%s must return an Editor instance.', static::class, $method));
        }

        return $this;
    }
}
