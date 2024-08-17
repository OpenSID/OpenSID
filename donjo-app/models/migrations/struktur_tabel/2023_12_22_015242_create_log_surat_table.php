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
        Schema::create('log_surat', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('log_surat_config_fk');
            $table->integer('id_format_surat');
            $table->integer('id_pend')->nullable();
            $table->integer('id_pamong');
            $table->string('nama_pamong', 100)->nullable()->comment('Nama pamong agar tidak berubah saat ada perubahan di master pamong');
            $table->string('nama_jabatan', 100)->nullable();
            $table->integer('id_user');
            $table->timestamp('tanggal')->useCurrent();
            $table->string('bulan', 2)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('no_surat', 20)->nullable();
            $table->string('nama_surat', 100)->nullable();
            $table->string('lampiran', 100)->nullable();
            $table->decimal('nik_non_warga', 16, 0)->nullable();
            $table->string('nama_non_warga', 100)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('lokasi_arsip', 150)->nullable()->default('');
            $table->integer('urls_id')->nullable()->unique('urls_id');
            $table->tinyInteger('status')->default(0)->comment('0. Konsep, 1. Cetak');
            $table->string('log_verifikasi', 100)->nullable();
            $table->boolean('tte')->nullable();
            $table->boolean('verifikasi_operator')->nullable();
            $table->boolean('verifikasi_kades')->nullable();
            $table->boolean('verifikasi_sekdes')->nullable();
            $table->longText('isi_surat')->nullable();
            $table->boolean('kecamatan')->default(true);
            $table->dateTime('deleted_at')->nullable();
            $table->string('pemohon', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_surat');
    }
};
