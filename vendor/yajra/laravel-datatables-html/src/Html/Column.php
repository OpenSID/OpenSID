<?php

namespace Yajra\DataTables\Html;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Options\Plugins;

/**
 * @property array|string $data
 * @property string $name
 * @property string $title
 * @property string $titleAttr
 * @property bool $orderable
 * @property bool $searchable
 * @property bool $printable
 * @property bool $exportable
 * @property array|string $footer
 * @property array $attributes
 * @property string $render
 * @property string $className
 * @property string $editField
 * @property int|array $orderData
 * @property string $orderDataType
 * @property string $orderSequence
 * @property string $cellType
 * @property string $type
 * @property string $contentPadding
 * @property string $createdCell
 * @property string $exportFormat
 * @property callable $exportRender
 *
 * @see https://datatables.net/reference/option/#columns
 */
class Column extends Fluent
{
    use HasAuthorizations;
    use Plugins\ColumnControl;
    use Plugins\SearchPanes;

    /**
     * @param  array  $attributes
     */
    public function __construct($attributes = [])
    {
        $attributes['title'] ??= self::titleFormat($attributes['data'] ?? '');
        $attributes['orderable'] ??= true;
        $attributes['searchable'] ??= true;
        $attributes['exportable'] ??= true;
        $attributes['printable'] ??= true;
        $attributes['footer'] ??= '';
        $attributes['attributes'] ??= [];

        // Allow methods override attribute value
        foreach ($attributes as $attribute => $value) {
            $method = 'parse'.ucfirst(strtolower($attribute));
            if (! is_null($value) && method_exists($this, $method)) {
                $attributes[$attribute] = $this->$method($value);
            }
        }

        if (! isset($attributes['name']) && isset($attributes['data'])) {
            $attributes['name'] = $attributes['data'];
        }

        parent::__construct($attributes);
    }

    /**
     * Format string to title case.
     */
    public static function titleFormat(string $value): string
    {
        return Str::title(str_replace(['.', '_'], ' ', Str::snake($value)));
    }

    /**
     * Set column title.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.title
     */
    public function title(string $value): static
    {
        $this->attributes['title'] = $value;

        return $this;
    }

    /**
     * Set column style.
     *
     * @return $this
     */
    public function style(string $css): static
    {
        $this->attributes['style'] = $css;

        return $this;
    }

    /**
     * Create a computed column that is not searchable/orderable.
     */
    public static function computed(string $data, ?string $title = null): Column
    {
        if (is_null($title)) {
            $title = self::titleFormat($data);
        }

        return static::make($data)->title($title)->orderable(false)->searchable(false);
    }

    /**
     * Set column searchable flag.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.searchable
     */
    public function searchable(bool $flag = true): static
    {
        $this->attributes['searchable'] = $flag;

        return $this;
    }

    /**
     * Set column orderable flag.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.orderable
     */
    public function orderable(bool $flag = true): static
    {
        $this->attributes['orderable'] = $flag;

        return $this;
    }

    /**
     * Make a new column instance.
     */
    public static function make(array|string $data = [], string $name = ''): static
    {
        $attr = $data;
        if (is_string($data)) {
            $attr = [
                'data' => $data,
                'name' => $name ?: $data,
            ];
        }

        return new static($attr);
    }

    /**
     * Make a new formatted column instance.
     */
    public static function formatted(string $name): static
    {
        $attr = [
            'data' => $name,
            'name' => $name,
            'title' => self::titleFormat($name),
            'render' => 'full.'.$name.'_formatted',
        ];

        return new static($attr);
    }

    /**
     * Create a checkbox column.
     */
    public static function checkbox(string $title = ''): static
    {
        return static::make('')
            ->content('')
            ->title($title)
            ->className('select-checkbox')
            ->orderable(false)
            ->exportable(false)
            ->searchable(false);
    }

    /**
     * Set column exportable flag.
     *
     * @return $this
     */
    public function exportable(bool $flag = true): static
    {
        $this->attributes['exportable'] = $flag;

        return $this;
    }

    /**
     * Set column class name.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.className
     */
    public function className(string $class): static
    {
        $this->attributes['className'] = $class;

        return $this;
    }

    /**
     * Set column default content.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.defaultContent
     */
    public function content(string $value): static
    {
        $this->attributes['defaultContent'] = $value;

        return $this;
    }

    /**
     * Set column responsive priority.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.responsivePriority
     */
    public function responsivePriority(int|string $value): static
    {
        $this->attributes['responsivePriority'] = $value;

        return $this;
    }

    /**
     * Set column hidden state.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.visible
     */
    public function hidden(): static
    {
        return $this->visible(false);
    }

    /**
     * Set column visible flag.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.visible
     */
    public function visible(bool $flag = true): static
    {
        $this->attributes['visible'] = $flag;

        return $this;
    }

    /**
     * Append a class name to field.
     *
     * @return $this
     */
    public function addClass(string $class): static
    {
        if (! isset($this->attributes['className'])) {
            $this->attributes['className'] = $class;
        } else {
            $this->attributes['className'] .= " $class";
        }

        return $this;
    }

    /**
     * Set column printable flag.
     *
     * @return $this
     */
    public function printable(bool $flag = true): static
    {
        $this->attributes['printable'] = $flag;

        return $this;
    }

    /**
     * Set column width value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.width
     */
    public function width(int|string $value): static
    {
        $this->attributes['width'] = $value;

        return $this;
    }

    /**
     * Set column data option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.data
     * @see https://datatables.net/manual/data/orthogonal-data
     */
    public function data(array|string $value): static
    {
        $this->attributes['data'] = $value;

        return $this;
    }

    /**
     * Set column name option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.name
     */
    public function name(string $value): static
    {
        $this->attributes['name'] = $value;

        return $this;
    }

    /**
     * Set column edit field option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.editField
     */
    public function editField(array|string $value): static
    {
        $this->attributes['editField'] = $value;

        return $this;
    }

    /**
     * Set column orderData option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.orderData
     */
    public function orderData(array|int $value): static
    {
        $this->attributes['orderData'] = $value;

        return $this;
    }

    /**
     * Set column orderDataType option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.orderDataType
     */
    public function orderDataType(string $value): static
    {
        $this->attributes['orderDataType'] = $value;

        return $this;
    }

    /**
     * Set column orderSequence option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.orderSequence
     */
    public function orderSequence(array $value): static
    {
        $this->attributes['orderSequence'] = $value;

        return $this;
    }

    /**
     * Set column cellType option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.cellType
     */
    public function cellType(string $value = 'th'): static
    {
        $this->attributes['cellType'] = $value;

        return $this;
    }

    /**
     * Set column type option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.type
     */
    public function type(string $value): static
    {
        $this->attributes['type'] = $value;

        return $this;
    }

    /**
     * Set column contentPadding option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.contentPadding
     */
    public function contentPadding(string $value): static
    {
        $this->attributes['contentPadding'] = $value;

        return $this;
    }

    /**
     * Set column createdCell option value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.createdCell
     */
    public function createdCell(string $value): static
    {
        $this->attributes['createdCell'] = $value;

        return $this;
    }

    /**
     * Use the js renderer "$.fn.dataTable.render.".
     *
     * @param  int|string|null  ...$params
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.render
     */
    public function renderJs(string $value, ...$params): static
    {
        if ($params) {
            $value .= '(';
            foreach ($params as $param) {
                $value .= sprintf("'%s',", $param);
            }
            $value = mb_substr($value, 0, -1);
            $value .= ')';
        }

        $renderer = '$.fn.dataTable.render.'.$value;

        return $this->render($renderer);
    }

    /**
     * Set column renderer.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.render
     */
    public function render(mixed $value): static
    {
        $this->attributes['render'] = $this->parseRender($value);

        return $this;
    }

    /**
     * Set Callback function to render column for Print + Export
     *
     * @return $this
     */
    public function exportRender(callable $callback): static
    {
        $this->attributes['exportRender'] = $callback;

        return $this;
    }

    /**
     * Parse render attribute.
     */
    public function parseRender(mixed $value): ?string
    {
        /** @var Factory $view */
        $view = app('view');
        $parameters = [];

        if (is_array($value)) {
            $parameters = Arr::except($value, 0);
            $value = $value[0];
        }

        if (is_callable($value)) {
            return $value($parameters);
        } elseif ($this->isBuiltInRenderFunction($value)) {
            return $value;
        } elseif (is_string($value) && strlen($value) < 256 && $view->exists($value)) {
            return $view->make($value)->with($parameters)->render();
        }

        return $value ? $this->parseRenderAsString($value) : null;
    }

    /**
     * Check if given key & value is a valid datatables built-in renderer function.
     */
    private function isBuiltInRenderFunction(string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        return Str::startsWith(trim($value), ['$.fn.dataTable.render', '[']);
    }

    /**
     * Display render value as is.
     */
    private function parseRenderAsString(string $value): string
    {
        return "function(data,type,full,meta){return $value;}";
    }

    /**
     * Set column renderer with give raw value.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.render
     */
    public function renderRaw(mixed $value): static
    {
        $this->attributes['render'] = $value;

        return $this;
    }

    /**
     * Set column footer.
     *
     * @return $this
     */
    public function footer(mixed $value): static
    {
        $this->attributes['footer'] = $value;

        return $this;
    }

    /**
     * Set custom html title instead default label.
     *
     * @return $this
     */
    public function titleAttr(mixed $value): static
    {
        $this->attributes['titleAttr'] = $value;

        return $this;
    }

    /**
     * Set excel column format when exporting.
     *
     * @return $this
     *
     * @see https://github.com/yajra/laravel-datatables-export
     */
    public function exportFormat(string|callable $format): static
    {
        $this->attributes['exportFormat'] = $format;

        return $this;
    }

    /**
     * Set column default content.
     *
     * @return $this
     *
     * @see https://datatables.net/reference/option/columns.defaultContent
     */
    public function defaultContent(string $content): static
    {
        $this->attributes['defaultContent'] = $content;

        return $this;
    }

    public function toArray(): array
    {
        if (! $this->isAuthorized()) {
            return [];
        }

        return Arr::except($this->attributes, [
            'printable',
            'exportable',
            'footer',
        ]);
    }
}
