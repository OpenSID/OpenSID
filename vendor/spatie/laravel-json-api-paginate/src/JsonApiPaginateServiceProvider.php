<?php

namespace Spatie\JsonApiPaginate;

use Composer\InstalledVersions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/json-api-paginate.php' => config_path('json-api-paginate.php'),
            ], 'config');
        }

        $this->registerMacro();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        $macro = function (?int $maxResults = null, ?int $defaultSize = null, ?int $totalResults = null) {
            $maxResults = $maxResults ?? config('json-api-paginate.max_results');
            $defaultSize = $defaultSize ?? config('json-api-paginate.default_size');
            $numberParameter = config('json-api-paginate.number_parameter');
            $cursorParameter = config('json-api-paginate.cursor_parameter');
            $sizeParameter = config('json-api-paginate.size_parameter');
            $paginationParameter = config('json-api-paginate.pagination_parameter');
            $paginationMethod = config('json-api-paginate.use_cursor_pagination')
                ? 'cursorPaginate'
                : (
                    config('json-api-paginate.use_simple_pagination')
                        ? (config('json-api-paginate.use_fast_pagination') ? 'simpleFastPaginate' : 'simplePaginate')
                        : (config('json-api-paginate.use_fast_pagination') ? 'fastPaginate' : 'paginate')
                );

            if (config('json-api-paginate.use_fast_pagination') && ! InstalledVersions::isInstalled('hammerstone/fast-paginate')) {
                abort(500, 'You need to install hammerstone/fast-paginate to use fast pagination.');
            }

            $size = (int) request()->input($paginationParameter.'.'.$sizeParameter, $defaultSize);
            $cursor = (string) request()->input($paginationParameter.'.'.$cursorParameter);

            if ($size <= 0) {
                $size = $defaultSize;
            }

            if ($size > $maxResults) {
                $size = $maxResults;
            }

            if ($paginationMethod === 'cursorPaginate') {
                $paginator = $this->{$paginationMethod}($size, ['*'], $paginationParameter.'['.$cursorParameter.']', $cursor)
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$cursorParameter));
            } else {
                if (version_compare(app()->version(), '11.0.0') >= 0) {
                    $paginator = $this->{$paginationMethod}($size, ['*'], $paginationParameter.'.'.$numberParameter, null, $totalResults);
                } else {
                    $paginator = $this->{$paginationMethod}($size, ['*'], $paginationParameter.'.'.$numberParameter);
                }

                $paginator->setPageName($paginationParameter.'['.$numberParameter.']')
                    ->appends(Arr::except(request()->input(), $paginationParameter.'.'.$numberParameter));
            }

            if (! is_null(config('json-api-paginate.base_url'))) {
                $paginator->setPath(config('json-api-paginate.base_url'));
            }

            return $paginator;
        };

        EloquentBuilder::macro(config('json-api-paginate.method_name'), $macro);
        BaseBuilder::macro(config('json-api-paginate.method_name'), $macro);
        BelongsToMany::macro(config('json-api-paginate.method_name'), $macro);
        HasManyThrough::macro(config('json-api-paginate.method_name'), $macro);
    }
}
