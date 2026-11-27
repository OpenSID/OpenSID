<?php

namespace Yajra\DataTables\Html\Options;

use Illuminate\Support\Arr;
use Yajra\DataTables\Utilities\Helper;

/**
 * DataTables - Data option builder.
 *
 * @see https://datatables.net/reference/option/
 */
trait HasAjax
{
    /**
     * Setup "ajax" parameter with POST method.
     *
     * @return $this
     */
    public function postAjax(array|string $attributes = ''): static
    {
        if (! is_array($attributes)) {
            $attributes = ['url' => $attributes];
        }

        unset($attributes['method']);
        Arr::set($attributes, 'type', 'POST');
        Arr::set($attributes, 'headers.X-HTTP-Method-Override', 'GET');

        return $this->ajax($attributes);
    }

    /**
     * Setup ajax parameter.
     *
     * @return $this
     */
    public function ajax(array|string $attributes = ''): static
    {
        $this->ajax = $attributes;

        return $this;
    }

    /**
     * @return $this
     */
    public function postAjaxWithForm(string $url, string $formSelector): static
    {
        $attributes = ['url' => $url];

        Arr::set($attributes, 'type', 'POST');
        Arr::set($attributes, 'headers.X-HTTP-Method-Override', 'GET');

        $script = $this->getScriptWithFormSelector($formSelector);

        $attributes['data'] = "function(data) { $script }";

        return $this->ajax($attributes);
    }

    protected function getScriptWithFormSelector(string $formSelector): string
    {
        return <<<CDATA
var formData = _.groupBy($("$formSelector").find("input, select, textarea").serializeArray(), function(d) { return d.name; } );
$.each(formData, function(i, group){
    if (group.length > 1) {
        data[group[0].name] = [];
        $.each(group, function(i, obj) {
            data[obj.name].push(obj.value)
        })
    } else {
        data[group[0].name] = group[0].value;
    }
});
CDATA;
    }

    /**
     * Setup ajax parameter for datatables pipeline plugin.
     *
     * @return $this
     *
     * @see https://datatables.net/examples/server_side/pipeline.html
     */
    public function pipeline(string $url, int $pages = 5): static
    {
        return $this->ajax("$.fn.dataTable.pipeline({ url: '$url', pages: $pages })");
    }

    /**
     * Get ajax url.
     */
    public function getAjaxUrl(): string
    {
        if (is_array($this->ajax)) {
            return $this->ajax['url'] ?: url()->current();
        }

        return $this->ajax ?: url()->current();
    }

    /**
     * Set ajax url with data added from form.
     *
     * @return $this
     */
    public function ajaxWithForm(string $url, string $formSelector): static
    {
        return $this->minifiedAjax($url, $this->getScriptWithFormSelector($formSelector));
    }

    /**
     * Minify ajax url generated when using get request
     * by deleting unnecessary url params.
     *
     * @return $this
     */
    public function minifiedAjax(
        string $url = '',
        ?string $script = null,
        array $data = [],
        array $ajaxParameters = []
    ): static {
        $this->ajax = [];
        $appendData = $this->makeDataScript($data);

        $this->ajax['url'] = empty($url) ? url()->full() : $url;
        $this->ajax['type'] = 'GET';
        if (! isset($this->attributes['serverSide']) || $this->attributes['serverSide']) {
            $this->ajax['data'] = 'function(data) {
            for (var i = 0, len = data.columns.length; i < len; i++) {
                if (!data.columns[i].search.value) delete data.columns[i].search;
                if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
            }
            delete data.search.regex;';
        } else {
            $this->ajax['data'] = 'function(data){';
        }

        if ($appendData) {
            $this->ajax['data'] .= $appendData;
        }

        if ($script) {
            $this->ajax['data'] .= $script;
        }

        $this->ajax['data'] .= '}';

        $this->ajax = array_merge($this->ajax, $ajaxParameters);

        return $this;
    }

    /**
     * Make a data script to be appended on ajax request of dataTables.
     */
    protected function makeDataScript(array $data): string
    {
        $script = '';
        foreach ($data as $key => $value) {
            $dataValue = Helper::isJavascript($value, $key) ? $value : (is_string($value) ? "'$value'" : $value);
            $script .= PHP_EOL."data.$key = $dataValue;";
        }

        return $script;
    }
}
