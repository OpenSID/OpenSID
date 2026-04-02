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
        if (Schema::hasTable('dtsen_pengaturan_program')) {
            return;
        }

        Schema::create('dtsen_pengaturan_program', static function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->integer('versi_kuisioner');
            $table->string('kode', 25);
            $table->integer('id_bantuan')->nullable();
            $table->string('nilai_default', 50)->nullable();
            $table->string('target_table', 100);
            $table->text('target_field');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            // Unique key
            $table->unique(['config_id', 'versi_kuisioner', 'kode'], 'config_idversi_kuisionerkode');

            // Index
            $table->index('id_bantuan', 'FK_dtsen_p_program');
        });

        // Foreign keys
        Schema::table('dtsen_pengaturan_program', static function (Blueprint $table) {

            $table->foreign('id_bantuan', 'FK_dtsen_p_program')
                ->references('id')->on('program')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_pengaturan_program_config_fk')
                ->references('id')->on('config')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('dtsen_pengaturan_program', static function (Blueprint $table) {
            $table->dropForeign('FK_dtsen_p_program');
            $table->dropForeign('dtsen_pengaturan_program_config_fk');
        });

        Schema::dropIfExists('dtsen_pengaturan_program');
    }
};
