<?php

namespace Yajra\DataTables\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

abstract class DataTablesCollectionExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected LazyCollection|Collection $collection;

    public function __construct(Collection|LazyCollection|null $collection = null)
    {
        $this->collection = $collection ?? new Collection;
    }

    public function collection(): Collection|LazyCollection
    {
        return $this->collection;
    }

    public function headings(): array
    {
        /** @var array $first */
        $first = $this->collection->first();
        if ($first) {
            return array_keys($first);
        }

        return [];
    }
}
