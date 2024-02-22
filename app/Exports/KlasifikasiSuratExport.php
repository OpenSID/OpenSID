<?php

namespace App\Exports;

use App\Models\KlasifikasiSurat;
use Rap2hpoutre\FastExcel\FastExcel;

class KlasifikasiSuratExport
{
    protected $fields = [
        'kode',
        'nama',
        'uraian',
    ];

    public function filename($name = null)
    {
        return $name ?? namafile('klasifikasi_surat_' . date('d-m-Y')) . '.xlsx';
    }

    public function data()
    {
        $dataExport = KlasifikasiSurat::get($this->fields)->toArray();

        if (empty($dataExport)) {
            $dataExport = [['kode' => '', 'nama' => '', 'uraian' => '']];
        }

        return $dataExport;
    }

    public function download()
    {
        return (new FastExcel)->data($this->data())->download($this->filename());
    }
}