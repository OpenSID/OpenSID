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
        Schema::create('tweb_surat_format', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable();
            $table->string('nama', 100);
            $table->string('url_surat', 100);
            $table->string('kode_surat', 10)->nullable();
            $table->string('lampiran', 100)->nullable();
            $table->boolean('kunci')->default(false);
            $table->boolean('favorit')->default(false);
            $table->tinyInteger('jenis')->default(2);
            $table->boolean('mandiri')->nullable()->default(false);
            $table->integer('masa_berlaku')->nullable()->default(1);
            $table->string('satuan_masa_berlaku', 15)->nullable()->default('M');
            $table->boolean('qr_code')->default(false);
            $table->boolean('logo_garuda')->default(false);
            $table->boolean('kecamatan')->default(false);
            $table->longText('syarat_surat')->nullable();
            $table->longText('template')->nullable();
            $table->longText('template_desa')->nullable();
            $table->longText('form_isian')->nullable();
            $table->longText('kode_isian')->nullable();
            $table->string('orientasi', 10)->nullable();
            $table->string('ukuran', 10)->nullable();
            $table->text('margin')->nullable();
            $table->boolean('margin_global')->nullable()->default(false);
            $table->integer('footer')->default(1);
            $table->integer('header')->default(1);
            $table->string('format_nomor', 100)->nullable();
            $table->tinyInteger('format_nomor_global')->nullable()->default(1);
            $table->boolean('sumber_penduduk_berulang')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->integer('updated_by')->nullable();

            $table->unique(['config_id', 'url_surat'], 'url_surat_config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweb_surat_format');
    }
};
