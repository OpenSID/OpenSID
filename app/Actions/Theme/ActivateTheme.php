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

namespace App\Actions\Theme;

use App\Enums\AktifEnum;
use App\Models\Theme;
use Exception;
use Illuminate\Support\Facades\Artisan;

/**
 * Mengaktifkan satu tema (dan menonaktifkan sisanya).
 *
 * OpenSID/OpenSID#11931 Fase 3.5 (jalur tema): kelas ini lahir pasca-refaktor
 * (#7033) — TAK ADA di sumber pipeline v2601–v2604 (rilis Umum 2701–2704), dan
 * ADA dalam bentuk sederhana ini di v2605+. Overlay `pipeline/additions/`
 * menambalnya untuk rentang yang belum punya, byte-identik untuk yang sudah.
 *
 * SENGAJA versi PRA-#7033 (tanpa gerbang kompatibilitas core `min_core`/
 * `max_core` premium#7026, tanpa gerbang entitlement `pastikanBerhakAktivasi`
 * yang bersandar pada `Theme::KATEGORI_*` + `cache('siappakai')` — keduanya
 * fitur Premium pasca-v2609 yang eksplisit DILUAR cakupan rilis 2701, lihat
 * "Cakupan yang TIDAK termasuk" di issue). Rilis Umum menegakkan tema
 * premium-eksklusif secara REAKTIF lewat `PenjagaTemaEksklusif` di request
 * publik, bukan lewat Exception saat aktivasi.
 *
 * Titik picu CI3: `donjo-app/controllers/Theme.php::aktifkan()` (di-wire lewat
 * pipeline/helpers/patch-boot-tentacles-umum.php untuk v2601–v2604; v2605+
 * sudah memanggilnya sendiri) dan command test-support `tema:pasang-uji`.
 */
class ActivateTheme
{
    /**
     * Mengaktifkan tema dan menonaktifkan tema lainnya.
     *
     * @param int|string $idOrSlug ID atau slug tema
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle(int|string $idOrSlug): Theme
    {
        $theme = Theme::where(static function ($q) use ($idOrSlug): void {
                $q->where('id', $idOrSlug)
                    ->orWhere('slug', $idOrSlug);
            })
            ->first();

        if (! $theme) {
            throw new Exception("Theme tidak ditemukan: {$idOrSlug}");
        }

        $theme->update(['status' => AktifEnum::AKTIF]);

        Theme::where('id', '!=', $theme->id)
            ->update(['status' => AktifEnum::TIDAK_AKTIF]);

        Artisan::call('view:clear');
        cache()->flush();

        return $theme;
    }
}
