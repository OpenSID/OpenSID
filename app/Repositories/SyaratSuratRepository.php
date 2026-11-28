<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SyaratSurat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SyaratSuratRepository
{
    public function queryWithFormatSuratExist(): Builder
    {
        $sql = <<<'EOD'
                json_contains(tweb_surat_format.syarat_surat, concat('"', ref_syarat_surat.ref_syarat_id, '"'), '$' )
            EOD;

        return SyaratSurat::select(['ref_syarat_id', 'ref_syarat_nama', DB::raw('count(syarat_surat) as jumlah_format_surat')])
            ->leftJoin('tweb_surat_format', DB::raw($sql), '=', DB::raw('1'))
            ->groupBy(['ref_syarat_id', 'ref_syarat_nama']);
    }

    public function deleteWithFormatSuratExist(mixed $id): int
    {
        if (! is_numeric($id)) {
            return 0;
        }

        $sql = <<<'EOD'
                json_contains(tweb_surat_format.syarat_surat, concat('"', ref_syarat_surat.ref_syarat_id, '"'), '$' )
            EOD;

        return SyaratSurat::select(['ref_syarat_id', 'ref_syarat_nama', DB::raw('count(syarat_surat) as jumlah_format_surat')])
            ->leftJoin('tweb_surat_format', DB::raw($sql), '=', DB::raw('1'))
            ->groupBy(['ref_syarat_id', 'ref_syarat_nama'])
            ->where('ref_syarat_surat.ref_syarat_id', $id)
            ->whereNull('tweb_surat_format.id')
            ->delete();
    }
}
