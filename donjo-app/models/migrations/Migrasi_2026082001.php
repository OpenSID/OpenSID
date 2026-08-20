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

use App\Models\Modul;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2026082001
{
    private const KUNCI_PETA = 'modul_menu_map';
    private const NAMA_MODUL = 'Anjungan';
    private const SLUG_MENU  = 'anjungan';

    /**
     * Backfill `modul_menu_map` untuk instalasi lama yang punya baris menu
     * Anjungan dari era sebelum diekstrak jadi add-on (baris `setting_modul`
     * dibuat di luar `ModuleManager::install()`, jadi peta tak pernah
     * mencatatnya). Tanpa entri ini, `ModuleManager::hiddenMenuSlugs()` tak
     * bisa mengenali & menyembunyikan menu Anjungan saat add-on-nya belum
     * (lagi) terpasang -- menu tampil tapi link-nya rusak. Padanan
     * Premium#Migrasi_2026082071.
     *
     * Instalasi baru (tak pernah punya baris menu ini) dilewati.
     */
    public function up()
    {
        $this->backfillPetaMenuAnjungan();
    }

    public function backfillPetaMenuAnjungan()
    {
        $adaBarisMenu = Modul::withoutGlobalScope('config_id')
            ->where('slug', self::SLUG_MENU)
            ->where('parent', 0)
            ->exists();

        if (! $adaBarisMenu) {
            return;
        }

        $row = SettingAplikasi::where('key', self::KUNCI_PETA)->first();
        $map = ($row === null || empty($row->value)) ? [] : (json_decode((string) $row->value, true) ?: []);

        if (array_key_exists(self::NAMA_MODUL, $map)) {
            return;
        }

        $map[self::NAMA_MODUL] = self::SLUG_MENU;
        $value                 = json_encode($map);

        if ($row) {
            $row->update(['value' => $value]);
        } else {
            SettingAplikasi::create(['key' => self::KUNCI_PETA, 'value' => $value]);
        }

        (new SettingAplikasi())->flushQueryCache();
        cache()->flush();
    }
}
