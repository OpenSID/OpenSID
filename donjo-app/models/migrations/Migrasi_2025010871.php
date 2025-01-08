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

use App\Models\Theme;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2025010871
{
    public function up()
    {
        $this->tambahKolomDataFormIsian();
        $this->ubahKolomUserAgent();
        $this->tambahKolomDiArtikel();
        $this->hapusTabelRefPendudukSuku();
        $this->tambahKolomBorderDiWilayah();
        $this->dropColumnStatusProgramBantuan();
        $this->scanUlangTema();
    }

    public function tambahKolomDataFormIsian()
    {
        if (! Schema::hasColumn('suplemen_terdata', 'data_form_isian')) {
            Schema::table('suplemen_terdata', static function (Blueprint $table) {
                $table->longText('data_form_isian')->nullable()->comment('Menyimpan data dinamis sebagai JSON atau teks');
            });
        }
    }

    public function ubahKolomUserAgent()
    {
        Schema::table('log_login', static function (Blueprint $table) {
            $table->text('user_agent')->change();
        });
    }

    public function tambahKolomDiArtikel()
    {
        if (! Schema::hasColumn('artikel', 'urut')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->integer('urut')->nullable();
            });
        }

        if (! Schema::hasColumn('artikel', 'jenis_widget')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->tinyInteger('jenis_widget')->default(3);
            });
        }
    }

    protected function hapusTabelRefPendudukSuku()
    {
        Schema::dropIfExists('ref_penduduk_suku');
    }

    public function tambahKolomBorderDiWilayah()
    {
        if (! Schema::hasColumn('tweb_wil_clusterdesa', 'border')) {
            Schema::table('tweb_wil_clusterdesa', static function (Blueprint $table) {
                $table->string('border', 25)->nullable();
            });
        }
    }

    public function dropColumnStatusProgramBantuan()
    {
        if (Schema::hasColumn('program', 'status')) {
            Schema::table('program', static function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function scanUlangTema()
    {
        ci()->load->helper('theme');

        Theme::withoutConfigId(identitas('id'))->delete();
        theme_scan();
    }
}
