<?php

namespace Yajra\DataTables\Transformers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Yajra\DataTables\Html\Column;

class DataArrayTransformer
{
    /**
     * Transform row data by column's definition.
     *
     * @param  array|Collection<array-key, Column>  $columns
     */
    public function transform(array $row, array|Collection $columns, string $type = 'printable'): array
    {
        if ($columns instanceof Collection) {
            return $this->buildColumnByCollection($row, $columns, $type);
        }

        return Arr::only($row, $columns);
    }

    /**
     * Transform row column by collection.
     *
     * @param  Collection<array-key, Column>  $columns
     */
    protected function buildColumnByCollection(array $row, Collection $columns, string $type = 'printable'): array
    {
        $results = [];
        $columns->each(function (Column $column) use ($row, $type, &$results) {
            if ($column[$type]) {
                $title = $column->title;
                if (is_array($column->data)) {
                    $key = $column->data['filter'] ?? $column->name ?? '';
                } else {
                    $key = $column->data ?? $column->name;
                }

                $data = Arr::get($row, $key) ?? '';

                if ($type == 'exportable') {
                    $title = $this->decodeContent($title);
                    $data = is_array($data) ? json_encode($data, JSON_THROW_ON_ERROR) : $this->decodeContent($data);
                }

                if (isset($column->exportRender)) {
                    $callback = $column->exportRender;
                    $results[$title] = $callback($row, $data);
                } else {
                    $results[$title] = $data;
                }
            }
        });

        return $results;
    }

    /**
     * Decode content to a readable text value.
     */
    protected function decodeContent(mixed $data): mixed
    {
        if (is_bool($data)) {
            return $data ? 'True' : 'False';
        }

        if (is_string($data)) {
            $decoded = html_entity_decode(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');

            return (string) str_replace("\xc2\xa0", ' ', $decoded);
        }

        return $data;
    }
}
