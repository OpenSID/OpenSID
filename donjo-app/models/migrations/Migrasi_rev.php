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

use App\Models\Dokumen;
use App\Models\SettingAplikasi;
use App\Models\Widget;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_rev
{
    use Migrator;

    public function up()
    {
        $this->updateIdPendDokumen();
        $this->hapusTabelSakitMenahun();
        $this->ubahLinkWidgetKeuangan();
        $this->tambahPengaturanAPBD();
        $this->perbaikiUrlQrcodeSurat();
        $this->ubahWidgetIsi();
        $this->updateKategoriTable();
    }

    public function updateIdPendDokumen()
    {
        Dokumen::where('id_pend', 0)->update(['id_pend' => null]);
    }

    protected function hapusTabelSakitMenahun()
    {
        Schema::dropIfExists('tweb_sakit_menahun');
    }

    public function ubahLinkWidgetKeuangan()
    {
        Widget::where('isi', 'keuangan.php')->update(['form_admin' => 'keuangan_manual']);
    }

    public function tambahPengaturanAPBD()
    {
        $this->createSetting([
            'key'        => 'apbdes_tahun',
            'judul'      => 'Tahun APBDes',
            'keterangan' => 'Tahun APBDes yang akan ditampilkan dihalaman depan',
            'value'      => date('Y'),
            'jenis'      => 'text',
            'kategori'   => 'conf_web',
        ]);

        SettingAplikasi::where('key', 'apbdes_manual_input')->delete();
    }

    public function perbaikiUrlQrcodeSurat()
    {
        DB::table('urls')
            ->leftJoin('log_surat', 'log_surat.urls_id', '=', 'urls.id')
            ->whereNotNull('log_surat.urls_id')
            ->where('urls.url', 'REGEXP', '/c1/$')
            ->where('log_surat.config_id', identitas('id'))
            ->update([
                'urls.url' => DB::raw('CONCAT(urls.url, log_surat.id)'),
            ]);
    }

    public function ubahWidgetIsi()
    {
        DB::table('widget')
            ->where('config_id', identitas('id'))
            ->whereRaw('isi REGEXP "\\.blade\\.php$|\\.php$"')
            ->update([
                'isi' => DB::raw('REGEXP_REPLACE(SUBSTRING_INDEX(isi, "/", -1), "\\.blade\\.php$|\\.php$", "")'),
            ]);
    }

    public function updateKategoriTable()
    {
        Schema::table('kategori', static function (Blueprint $table) {
            $table->integer('parrent')->change();
        });
    }
}
