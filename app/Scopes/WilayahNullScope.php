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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Scopes;

use App\Models\ArtikelWilayah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class WilayahNullScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Filter artikel/kategori agar desa hanya melihat:
     * 1. Data milik desa sendiri (config_id = id desa)
     * 2. Data OpenKab/OpenProvinsi (config_id IS NULL) yang dipetakan
     *    ke kabupaten ATAU provinsi desa tersebut via tabel jembatan.
     *
     * Jika tabel pivot belum ada (migrasi belum dijalankan), fallback ke
     * behavior lama: config_id = X OR config_id IS NULL.
     *
     * CATATAN: Fallback ini bagian normal dari workflow rilis OpenSID.
     * Pada fase dev sebelum VERSI_DATABASE dinaikkan, admin perlu menjalankan
     * migrasi manual via menu Pengaturan > Database > Migrasi DB untuk
     * mengaktifkan filter per-wilayah. Setelah rilis resmi, devops menaikkan
     * VERSI_DATABASE sehingga migrasi auto-jalan dan cabang ini tidak terpakai.
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tbl      = $model->getTable();
        $pivotTbl = $model->wilayahPivotTable();
        $pivotFk  = $model->wilayahPivotForeignKey();
        $configId = identitas('id');

        // Cache hasil Schema::hasTable per-request supaya tidak hit DB
        // berulang kali pada halaman dengan banyak query Artikel/Kategori
        // (mis. Beranda, Sitemap, RSS). Static akan ter-reset otomatis tiap request.
        static $pivotExistsCache = [];
        $pivotExists = $pivotExistsCache[$pivotTbl] ??= Schema::hasTable($pivotTbl);

        // Jika tabel pivot belum ada, fallback ke filter config_id sederhana
        if (! $pivotExists) {
            $builder->where(function ($q) use ($tbl, $configId) {
                $q->where("{$tbl}.config_id", $configId)
                  ->orWhereNull("{$tbl}.config_id");
            });

            return;
        }

        $kodeKab  = identitas('kode_kabupaten');
        $kodeProv = identitas('kode_propinsi');

        $builder->where(static function ($q) use ($tbl, $pivotTbl, $pivotFk, $configId, $kodeKab, $kodeProv) {
            $q->where("{$tbl}.config_id", $configId)
                ->orWhere(static function ($q2) use ($tbl, $pivotTbl, $pivotFk, $kodeKab, $kodeProv) {
                    $q2->whereNull("{$tbl}.config_id")
                        ->whereIn("{$tbl}.id", static function ($sub) use ($pivotTbl, $pivotFk, $kodeKab, $kodeProv) {
                             $sub->select($pivotFk)->from($pivotTbl)
                                 ->where(static function ($w) use ($kodeKab, $kodeProv) {
                                     $w->where(static function ($k) use ($kodeKab) {
                                         $k->where('tingkat_wilayah', ArtikelWilayah::TINGKAT_KABUPATEN)
                                             ->where('kode_wilayah', $kodeKab);
                                     })->orWhere(static function ($p) use ($kodeProv) {
                                         $p->where('tingkat_wilayah', ArtikelWilayah::TINGKAT_PROVINSI)
                                             ->where('kode_wilayah', $kodeProv);
                                     });
                                 });
                         });
                });
        });
    }
}
