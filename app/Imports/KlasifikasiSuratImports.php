<?php

namespace App\Imports;

use Exception;
use App\Models\KlasifikasiSurat;
use Rap2hpoutre\FastExcel\FastExcel;

class KlasifikasiSuratImports
{
    protected $path;

    protected $fields = [
        'kode',
        'nama',
        'uraian',
    ];

    // constructor with a parameter
    public function __construct($path = null)
    {
        $this->path = $path ?? DEFAULT_LOKASI_IMPOR . 'klasifikasi_surat.xlsx';
    }

    public function import()
    {
        $configId = identitas('id');

        try {
            $dataImport = [];

            (new FastExcel)->import($this->path, function ($line) use ($configId, &$dataImport) {
                $dataUpdate = [
                    'kode'      => alfanumerik_titik($line['kode']),
                    'nama'      => alfa_spasi($line['nama']),
                    'uraian'    => strip_tags($line['uraian']),
                    'config_id' => $configId,
                ];

                $dataImport[] = $dataUpdate;
            });

            KlasifikasiSurat::upsert($dataImport, ['kode', 'config_id']);
        } catch (Exception $e) {
            log_message('error', $e);
            return false;
        }

        return true;
    }
}