<?php

namespace Yajra\DataTables\Html;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use Yajra\DataTables\Utilities\Helper;

class Builder
{
    use Columns\Action;
    use Columns\Checkbox;
    use Columns\Index;
    use HasEditor;
    use HasOptions;
    use HasTable;
    use Macroable;

    // Select plugin constants.
    final public const SELECT_STYLE_API = 'api';

    final public const SELECT_STYLE_SINGLE = 'single';

    final public const SELECT_STYLE_MULTI = 'multi';

    final public const SELECT_STYLE_OS = 'os';

    final public const SELECT_STYLE_MULTI_SHIFT = 'multi+shift';

    final public const SELECT_ITEMS_ROW = 'row';

    final public const SELECT_ITEMS_COLUMN = 'column';

    final public const SELECT_ITEMS_CELL = 'cell';

    /**
     * The default type to use for the DataTables javascript.
     */
    protected static string $jsType = 'text/javascript';

    /**
     * @var Collection<int, Column>
     */
    public Collection $collection;

    /**
     * @var array<string, string|null>
     */
    protected array $tableAttributes = [];

    protected string $template = '';

    protected array $attributes = [
        'serverSide' => true,
        'processing' => true,
    ];

    protected string|array $ajax = '';

    protected array $additionalScripts = [];

    protected array $templateData = [];

    public function __construct(public Repository $config, public Factory $view, public HtmlBuilder $html)
    {
        /** @var array $defaults */
        $defaults = $this->config->get('datatables-html.table', []);

        $this->collection = new Collection;
        $this->tableAttributes = $defaults;
    }

    /**
     * Set the default type to module for the DataTables javascript.
     */
    public static function useVite(): void
    {
        static::$jsType = 'module';
    }

    /**
     * Set the default type to text/javascript for the DataTables javascript.
     */
    public static function useWebpack(): void
    {
        static::$jsType = 'text/javascript';
    }

    /**
     * Generate DataTable javascript.
     */
    public function scripts(?string $script = null, array $attributes = []): HtmlString
    {
        $script = $script ?: $this->generateScripts();
        $attributes = $this->html->attributes(
            array_merge($attributes, ['type' => $attributes['type'] ?? static::$jsType])
        );

        return new HtmlString("<script{$attributes}>$script</script>");
    }

    /**
     * Get generated raw scripts.
     */
    public function generateScripts(): HtmlString
    {
        $parameters = $this->generateJson();

        return new HtmlString(
            trim(sprintf($this->template(), $this->getTableAttribute('id'), $parameters))
        );
    }

    /**
     * Get generated json configuration.
     */
    public function generateJson(): string
    {
        return $this->parameterize($this->getOptions());
    }

    /**
     * Generate DataTables js parameters.
     */
    public function parameterize(array $attributes = []): string
    {
        $parameters = (new Parameters($attributes))->toArray();

        return Helper::toJsonScript($parameters);
    }

    /**
     * Get DataTable options array.
     */
    public function getOptions(): array
    {
        return array_merge(
            $this->attributes, [
                'ajax' => $this->ajax,
                'columns' => $this->collection->map(function (Column $column) {
                    $column = $column->toArray();
                    unset($column['attributes']);

                    return $column;
                })->toArray(),
            ]
        );
    }

    /**
     * Get javascript template to use.
     */
    protected function template(): string
    {
        /** @var view-string $configTemplate */
        $configTemplate = $this->config->get('datatables-html.script', 'datatables::script');

        $template = $this->template ?: $configTemplate;

        return $this->view->make(
            $template,
            array_merge(
                ['editors' => $this->editors, 'scripts' => $this->additionalScripts],
                $this->templateData,
            )
        )->render();
    }

    /**
     * Generate DataTable's table html.
     */
    public function table(array $attributes = [], bool $drawFooter = false, bool $drawSearch = false): HtmlString
    {
        $this->setTableAttributes($attributes);

        $th = $this->compileTableHeaders();
        $htmlAttr = $this->html->attributes($this->tableAttributes);

        $tableHtml = '<table'.$htmlAttr.'>';
        $searchHtml = $drawSearch
                ? '<tr class="search-filter">'.implode('', $this->compileTableSearchHeaders()).'</tr>'
                : '';

        $tableHtml .= '<thead'.($this->getTheadClass() ?? '').'>';
        $tableHtml .= '<tr>'.implode('', $th).'</tr>'.$searchHtml.'</thead>';

        if ($drawFooter) {
            $tf = $this->compileTableFooter();
            $tableHtml .= '<tfoot><tr>'.implode('', $tf).'</tr></tfoot>';
        }

        $tableHtml .= '</table>';

        return new HtmlString($tableHtml);
    }

    /**
     * Configure DataTable's parameters.
     *
     * @return $this
     */
    public function parameters(array $attributes = []): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Generate scripts that set the dataTables options into a variable.
     *
     * @return $this
     */
    public function asOptions(): static
    {
        return $this->setTemplate('datatables::options');
    }

    /**
     * Set custom javascript template.
     *
     * @return $this
     */
    public function setTemplate(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Wrap dataTable scripts with a function.
     *
     * @return $this
     */
    public function asFunction(): static
    {
        return $this->setTemplate('datatables::function');
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key, mixed $default = ''): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function getAjax(?string $key = null): array|string
    {
        if (! is_null($key)) {
            return $this->ajax[$key] ?? '';
        }

        return $this->ajax;
    }

    /**
     * Add additional scripts to the DataTables JS initialization.
     *
     * @return $this
     */
    public function addScript(string $view): static
    {
        $this->additionalScripts[] = $view;

        return $this;
    }

    public function addScriptIfCan(string $ability, string $view): static
    {
        if (Gate::allows($ability)) {
            $this->addScript($view);
        }

        return $this;
    }

    public function addScriptIf(bool $condition, string $view): static
    {
        if ($condition) {
            $this->addScript($view);
        }

        return $this;
    }

    public function addScriptIfCannot(string $ability, string $view): static
    {
        if (Gate::denies($ability)) {
            $this->addScript($view);
        }

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getAdditionalScripts(): array
    {
        return $this->additionalScripts;
    }

    public function setTemplateData(array|\Closure $data = []): static
    {
        if ($data instanceof \Closure) {
            $data = $data($this) ?? [];
        }

        $this->templateData = $data;

        return $this;
    }
}
