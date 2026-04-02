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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        if (Schema::hasTable('dtsen_ref_lampiran')) {
            return;
        }

        Schema::create('dtsen_ref_lampiran', static function (Blueprint $table) {
            $table->unsignedBigInteger('id_lampiran');
            $table->integer('id_dtsen');
            $table->integer('config_id');

            $table->index('id_dtsen', 'FK_ref_lampiran_dtsen');
            $table->index('id_lampiran', 'FK_lampiran_dtsen');
            $table->index('config_id', 'dtsen_ref_lampiran_config_id_foreign');
        });

        // Foreign key
        Schema::table('dtsen_ref_lampiran', static function (Blueprint $table) {

            $table->foreign('id_lampiran', 'FK_lampiran_dtsen')
                ->references('id')->on('dtsen_lampiran')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_dtsen', 'FK_ref_lampiran_dtsen')
                ->references('id')->on('dtsen')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_ref_lampiran_config_id_foreign')
                ->references('id')->on('config')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('dtsen_ref_lampiran', static function (Blueprint $table) {
            $table->dropForeign('FK_lampiran_dtsen');
            $table->dropForeign('FK_ref_lampiran_dtsen');
            $table->dropForeign('dtsen_ref_lampiran_config_id_foreign');
        });

        Schema::dropIfExists('dtsen_ref_lampiran');
    }
};
