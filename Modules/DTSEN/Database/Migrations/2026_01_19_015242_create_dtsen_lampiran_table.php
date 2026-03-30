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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        if (Schema::hasTable('dtsen_lampiran')) {
            return;
        }

        Schema::create('dtsen_lampiran', static function (Blueprint $table) {
            $table->id();
            $table->integer('config_id');
            $table->integer('id_rtm')->nullable();
            $table->integer('id_keluarga')->nullable();
            $table->string('judul', 30);
            $table->string('keterangan', 100);
            $table->text('foto')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('id_keluarga', 'FK_dtsen_lampiran_keluarga');
            $table->index('config_id', 'dtsen_lampiran_config_fk');
        });

        // Foreign Keys
        Schema::table('dtsen_lampiran', static function (Blueprint $table) {
            $table->foreign('id_keluarga', 'FK_dtsen_lampiran_keluarga')
                ->references('id')->on('tweb_keluarga')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('config_id', 'dtsen_lampiran_config_fk')
                ->references('id')->on('config')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::table('dtsen_lampiran')->insert([
            'config_id'  => identitas('id'),
            'judul'      => 'Dokumen',
            'keterangan' => 'Dokumen identitas',
        ]);
    }

    public function down()
    {
        Schema::table('dtsen_lampiran', static function (Blueprint $table) {
            $table->dropForeign('FK_dtsen_lampiran_keluarga');
            $table->dropForeign('dtsen_lampiran_config_fk');
        });

        Schema::dropIfExists('dtsen_lampiran');
    }
};
