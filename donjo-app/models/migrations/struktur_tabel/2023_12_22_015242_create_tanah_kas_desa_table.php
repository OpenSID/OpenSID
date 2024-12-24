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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
        Schema::create('tanah_kas_desa', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('tanah_kas_desa_config_fk');
            $table->string('nama_pemilik_asal', 200);
            $table->text('letter_c');
            $table->text('kelas');
            $table->integer('luas');
            $table->integer('asli_milik_desa')->nullable();
            $table->integer('pemerintah')->nullable();
            $table->integer('provinsi')->nullable();
            $table->integer('kabupaten_kota')->nullable();
            $table->integer('lain_lain')->nullable();
            $table->integer('sawah')->nullable();
            $table->integer('tegal')->nullable();
            $table->integer('kebun')->nullable();
            $table->integer('tambak_kolam')->nullable();
            $table->integer('tanah_kering_darat')->nullable();
            $table->integer('ada_patok')->nullable();
            $table->integer('tidak_ada_patok')->nullable();
            $table->integer('ada_papan_nama')->nullable();
            $table->integer('tidak_ada_papan_nama')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->text('lokasi');
            $table->text('peruntukan');
            $table->text('mutasi');
            $table->text('keterangan');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by');
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by');
            $table->tinyInteger('visible')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tanah_kas_desa');
    }
};
