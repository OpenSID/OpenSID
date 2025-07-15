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
use App\Traits\Migrator;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->updateRestrictFkNew();
        $this->checkMailBoxClear();
        $this->urutPengaturanKehadiran();
    }

    public function updateRestrictFkNew()
    {
        $table      = 'tweb_penduduk_mandiri';
        $column     = 'config_id';
        $foreignKey = 'tweb_penduduk_mandiri_config_fk';
        $refTable   = 'config';
        $this->resetForeignKey($table, $column, $foreignKey, $refTable);
    }

    public function checkMailBoxClear()
    {
        Modul::where('url', 'mailbox/clear')->update(['url' => 'mailbox']);
    }

    public function urutPengaturanKehadiran()
    {
        if (!Schema::hasColumn('setting_aplikasi', 'urut')) return;

        $urutan = [
            'tampilkan_kehadiran'     => 1,
            'ip_adress_kehadiran'     => 2,
            'mac_adress_kehadiran'    => 3,
            'id_pengunjung_kehadiran' => 4,
            'latar_kehadiran'         => 5,
            'rentang_waktu_keluar'    => 6,
            'rentang_waktu_masuk'     => 7,
        ];

        SettingAplikasi::whereIn('key', array_keys($urutan))
            ->get(['id', 'key', 'urut'])
            ->each(function ($item) use ($urutan) {
                $targetUrut = $urutan[$item->key];

                if (is_null($item->urut) || $item->urut != $targetUrut) {
                    $item->urut = $targetUrut;
                    $item->save();
                }
            });

        (new SettingAplikasi())->flushQueryCache();
    }
}
