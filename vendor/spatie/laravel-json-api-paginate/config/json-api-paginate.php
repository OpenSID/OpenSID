<?php

return [

    /*
     * The maximum number of results that will be returned
     * when using the JSON API paginator.
     */
    'max_results' => 30,

    /*
     * The default number of results that will be returned
     * when using the JSON API paginator.
     */
    'default_size' => 30,

    /*
     * The key of the page[x] query string parameter for page number.
     */
    'number_parameter' => 'number',

    /*
     * The key of the page[x] query string parameter for page size.
     */
    'size_parameter' => 'size',

    /*
     * The key of the page[x] query string parameter for cursor.
     */
    'cursor_parameter' => 'cursor',

    /*
     * The name of the macro that is added to the Eloquent query builder.
     */
    'method_name' => 'jsonPaginate',

    /*
     * If you only need to display Next and Previous links, you may use
     * simple pagination to perform a more efficient query.
     */
    'use_simple_pagination' => false,

    /*
     * If you want to use cursor pagination, set this to true.
     * This would override use_simple_pagination.
     */
    'use_cursor_pagination' => false,

    /*
     * use simpleFastPaginate() or fastPaginate from https://github.com/hammerstonedev/fast-paginate
     * use may installed it via `composer require hammerstone/fast-paginate`
     */
    'use_fast_pagination' => false,

    /*
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,

    /*
     * The name of the query parameter used for pagination
     */
    'pagination_parameter' => 'page',
];
