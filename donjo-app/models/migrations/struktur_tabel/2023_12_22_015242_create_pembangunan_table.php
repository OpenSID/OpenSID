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
        Schema::create('pembangunan', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->integer('id_lokasi')->nullable()->index('id_lokasi');
            $table->string('sumber_dana')->nullable();
            $table->string('judul')->nullable();
            $table->string('slug')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('lokasi')->nullable();
            $table->string('lat', 225)->nullable();
            $table->string('lng')->nullable();
            $table->string('volume', 100)->nullable();
            $table->year('tahun_anggaran')->nullable();
            $table->string('pelaksana_kegiatan')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('foto')->nullable();
            $table->bigInteger('anggaran')->nullable()->default(0);
            $table->integer('perubahan_anggaran')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_pemerintah')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_provinsi')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_kab_kota')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_swadaya')->nullable()->default(0);
            $table->bigInteger('sumber_biaya_jumlah')->nullable()->default(0);
            $table->text('manfaat')->nullable();
            $table->integer('waktu')->nullable()->default(0);
            $table->boolean('satuan_waktu')->default(false)->comment('1 = Hari, 2 = Minggu, 3 = Bulan, 4 = Tahun');
            $table->string('sifat_proyek', 100)->nullable()->default('BARU');

            $table->unique(['config_id', 'slug'], 'slug_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembangunan');
    }
};
