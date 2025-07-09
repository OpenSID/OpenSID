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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tanah_desa', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('tanah_desa_config_fk');
            $table->integer('id_penduduk')->index('id_penduduk');
            $table->decimal('nik', 16, 0)->nullable();
            $table->text('jenis_pemilik')->nullable();
            $table->string('nama_pemilik_asal', 200);
            $table->integer('luas');
            $table->integer('hak_milik')->nullable();
            $table->integer('hak_guna_bangunan')->nullable();
            $table->integer('hak_pakai')->nullable();
            $table->integer('hak_guna_usaha')->nullable();
            $table->integer('hak_pengelolaan')->nullable();
            $table->integer('hak_milik_adat')->nullable();
            $table->integer('hak_verponding')->nullable();
            $table->integer('tanah_negara')->nullable();
            $table->integer('perumahan')->nullable();
            $table->integer('perdagangan_jasa')->nullable();
            $table->integer('perkantoran')->nullable();
            $table->integer('industri')->nullable();
            $table->integer('fasilitas_umum')->nullable();
            $table->integer('sawah')->nullable();
            $table->integer('tegalan')->nullable();
            $table->integer('perkebunan')->nullable();
            $table->integer('peternakan_perikanan')->nullable();
            $table->integer('hutan_belukar')->nullable();
            $table->integer('hutan_lebat_lindung')->nullable();
            $table->integer('tanah_kosong')->nullable();
            $table->integer('lain')->nullable();
            $table->text('mutasi');
            $table->text('keterangan');
            $table->tinyInteger('visible')->default(1);
            $table->timesWithUserstamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tanah_desa');
    }
};
