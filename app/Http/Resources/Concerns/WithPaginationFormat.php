<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Http\Resources\JsonApi\AnonymousResourceCollection;
use Illuminate\Support\Arr;

trait WithPaginationFormat
{
    /**
     * {@inheritDoc}
     */
    protected static function newCollection($resource)
    {
        return new class($resource, static::class) extends AnonymousResourceCollection {
            /**
             * {@inheritDoc}
             */
            public function paginationInformation($request, $paginated, $default): array
            {
                return [
                    'links' => [
                        'first' => $paginated['first_page_url'] ?? null,
                        'last'  => $paginated['last_page_url'] ?? null,
                        'prev'  => $paginated['prev_page_url'] ?? null,
                        'next'  => $paginated['next_page_url'] ?? null,
                    ],
                    'meta' => [
                        ...Arr::except($paginated, ['data', 'first_page_url', 'last_page_url', 'prev_page_url', 'next_page_url', 'links', 'path']),
                        'pagination' => [
                            'total'        => (int) $paginated['total'],
                            'count'        => count($paginated['data']),
                            'per_page'     => (int) $paginated['per_page'],
                            'current_page' => (int) $paginated['current_page'],
                            'total_pages'  => (int) $paginated['last_page'],
                        ],
                        'deprecated' => [
                            'pagination' => 'Use meta.current_page, meta.last_page, meta.per_page, meta.total, and links.first/last/prev/next instead.',
                        ],
                    ],
                ];
            }
        };
    }
}
