<?php

namespace App\Services;

use App\Models\LogSurat;
use App\Models\SuratMasuk;
use App\Models\FormatSurat;
use App\Models\SuratKeluar;
use App\Models\SyaratSurat;
use App\Models\DokumenHidup;
use Illuminate\Support\Facades\DB;

class ArsipFisikSurat
{
    public function arsipDesaQuery(): \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
    {
        $dokumenDesaQuery  = DokumenHidup::arsipFisikDokumenDesa();
        $suratMasukQuery   = SuratMasuk::arsipFisikSuratMasuk();
        $suratKeluarQuery  = SuratKeluar::arsipFisikSuratKeluar();
        $kependudukanQuery = DokumenHidup::arsipFisikKependudukan();
        $layananSuratQuery = LogSurat::arsipFisikLayananSurat();

        $unionQuery = $dokumenDesaQuery
            ->union($suratMasukQuery)
            ->union($suratKeluarQuery)
            ->union($kependudukanQuery)
            ->union($layananSuratQuery);

        return DB::table($unionQuery, 'x');
    }

    /**
     * @throws \Exception
     */
    public function totalData($kategori): int|null
    {
        return $this->dataJenis($kategori)?->count();
    }

    public function semuaFilter(): array
    {
        $jenis = [
            '1-1' => 'Informasi Desa Lain',
            '1-2' => 'Surat Keputusan Kepala Desa',
            '1-3' => 'Peraturan Desa',
            '2-1' => 'Surat Masuk',
            '3-1' => 'Surat Keluar'
        ];

        $syaratItems = SyaratSurat::all()->mapWithKeys(static fn ($item) => ["4-{$item->ref_syarat_id}" => $item->ref_syarat_nama]);
        $formatItems = FormatSurat::all()->mapWithKeys(static fn ($item) => ["5-{$item->id}" => $item->nama]);
        
        $jenis = [
            ...$jenis,
            ...$syaratItems->toArray(),
            ...$formatItems->toArray(),
        ];

        $tahun = range(2015, date('Y'));
        rsort($tahun);

        return ['jenis' => $jenis, 'tahun' => $tahun];
    }

    public function updateLokasi($table, $id, $value)
    {
        $model = $this->getModel($table);

        return $model::where('id', $id)->update(['lokasi_arsip' => $value]);
    }

    public function getNamaBerkas($table, $id, $lampiran = false)
    {
        $model = $this->getModel($table);
        $column = $this->getBerkasColumn($table, $lampiran);

        return $model::where('id', $id)->value($column);
    }

    public function getLokasiArsip($table, $id)
    {
        $model = $this->getModel($table);

        return $model::where('id', $id)->value('lokasi_arsip');
    }

    private function getModel($table)
    {
        return match ($table) {
            'surat_masuk'                                   => SuratMasuk::class,
            'surat_keluar'                                  => SuratKeluar::class,
            'dokumen_hidup', 'dokumen_desa', 'kependudukan' => DokumenHidup::class,
            'log_surat', 'layanan_surat'                    => LogSurat::class,
            default                                         => throw new \Exception('Unknown table'),
        };
    }

    private function getBerkasColumn($table, $lampiran)
    {
        return match ($table) {
            'surat_masuk', 'surat_keluar' => 'berkas_scan',
            'dokumen_hidup' => 'satuan',
            'log_surat'     => $lampiran ? 'lampiran' : 'nama_surat',
            default         => throw new \Exception('Unknown berkas column'),
        };
    }

    /**
     * @throws \Exception
     */
    private function dataJenis($kategori): \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
    {
        return match ($kategori) {
            'dokumen_desa'  => DokumenHidup::where('id_pend', 0)->whereNotNull('satuan'),
            'surat_masuk'   => SuratMasuk::whereNotNull('berkas_scan'),
            'surat_keluar'  => SuratKeluar::whereNotNull('berkas_scan'),
            'kependudukan'  => DokumenHidup::where('id_pend', '!=', 0)->whereNotNull('satuan'),
            'layanan_surat' => LogSurat::query(),
            default         => throw new \Exception('Unknown category'),
        };
    }
}