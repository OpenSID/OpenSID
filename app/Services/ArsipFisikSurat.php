<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services;

use App\Models\DokumenHidup;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\SyaratSurat;
use Exception;
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
     * @throws Exception
     */
    public function totalData(mixed $kategori): int|null
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
            '3-1' => 'Surat Keluar',
        ];

        $syaratItems = SyaratSurat::all()->mapWithKeys(static fn ($item): array => ["4-{$item->ref_syarat_id}" => $item->ref_syarat_nama]);
        $formatItems = FormatSurat::all()->mapWithKeys(static fn ($item): array => ["5-{$item->id}" => $item->nama]);

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
        $model  = $this->getModel($table);
        $column = $this->getBerkasColumn($table, $lampiran);

        return $model::where('id', $id)->value($column);
    }

    public function getLokasiArsip($table, $id)
    {
        $model = $this->getModel($table);

        return $model::where('id', $id)->value('lokasi_arsip');
    }

    private function getModel($table): string
    {
        return match ($table) {
            'surat_masuk'  => SuratMasuk::class,
            'surat_keluar' => SuratKeluar::class,
            'dokumen_hidup', 'dokumen_desa', 'kependudukan' => DokumenHidup::class,
            'log_surat', 'layanan_surat' => LogSurat::class,
            default => throw new Exception("Unknown table: {$table}"),
        };
    }

    private function getBerkasColumn($table, $lampiran): string
    {
        return match ($table) {
            'surat_masuk', 'surat_keluar' => 'berkas_scan',
            'dokumen_hidup' => 'satuan',
            'log_surat'     => $lampiran ? 'lampiran' : 'nama_surat',
            default         => throw new Exception("Unknown berkas column: {$table}"),
        };
    }

    /**
     * @throws Exception
     */
    private function dataJenis(mixed $kategori): \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
    {
        return match ($kategori) {
            'dokumen_desa'  => DokumenHidup::where('id_pend', 0)->whereNotNull('satuan'),
            'surat_masuk'   => SuratMasuk::whereNotNull('berkas_scan'),
            'surat_keluar'  => SuratKeluar::whereNotNull('berkas_scan'),
            'kependudukan'  => DokumenHidup::where('id_pend', '!=', 0)->whereNotNull('satuan'),
            'layanan_surat' => LogSurat::where(static fn ($query) => $query->where('verifikasi_operator', 1)->orWhere('verifikasi_operator', null))
                ->whereNull('deleted_at'),
            default => throw new Exception("Unknown category: {$kategori}"),
        };
    }
}
