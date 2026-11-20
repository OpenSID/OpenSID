<?php

declare(strict_types=1);

namespace App\DataTables\Admin;

use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Services\DataTable;

class SyaratSuratDataTable extends DataTable
{
    public function __construct(
        private readonly SyaratSuratRepository $repository,
    ) {
    }

    /**
     * Parameter $model wajib sesuai signature Yajra, walau tidak dipakai
     * karena query diambil dari Repository.
     */
    public function query(SyaratSurat $model)
    {
        return $this->repository->queryWithFormatSuratExist();
    }

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('ceklist', fn ($row) => $this->renderCeklist($row))
            ->addIndexColumn()
            ->addColumn('aksi', fn ($row) => $this->renderAksi($row))
            ->rawColumns(['ceklist', 'aksi']);
    }

    private function renderCeklist(SyaratSurat $row): ?string
    {
        return Gate::allows('layanan-surat:h')
            ? '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '"/>'
            : null;
    }

    private function renderAksi(SyaratSurat $row): string
    {
        $aksi = View::make('admin.layouts.components.buttons.edit', [
            'url' => "surat_mohon/form/{$row->ref_syarat_id}",
        ])->render();

        if ($row->jumlah_format_surat == '0') {
            $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                'url'           => "surat_mohon/delete/{$row->ref_syarat_id}",
                'confirmDelete' => true,
            ])->render();
        }

        return $aksi;
    }
}
