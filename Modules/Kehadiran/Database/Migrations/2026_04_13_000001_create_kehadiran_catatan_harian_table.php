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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            if (! Schema::hasTable('kehadiran_catatan_harian')) {
                Schema::create('kehadiran_catatan_harian', static function (Blueprint $table) {
                    $table->uuid('uuid')->primary();
                    $table->configId();
                    $table->integer('pamong_id')->comment('FK ke tweb_desa_pamong.pamong_id');
                    $table->date('tanggal')->comment('Tanggal kegiatan');
                    $table->text('uraian_kegiatan')->comment('Deskripsi kegiatan yang dilakukan');
                    $table->string('lokasi_kegiatan', 255)->comment('Tempat kegiatan berlangsung');
                    $table->text('hasil_diharapkan')->nullable()->comment('Hasil yang diharapkan (opsional)');
                    $table->enum('status', [
                        'draft',
                        'menunggu',
                        'disetujui',
                        'ditolak',
                    ])->default('draft')->comment('Status catatan: draft, menunggu, disetujui, ditolak');
                    $table->integer('approved_by')->nullable()->comment('FK ke tweb_desa_pamong.pamong_id (atasan yang approve)');
                    $table->dateTime('approved_at')->nullable()->comment('Waktu approval');
                    $table->text('alasan_penolakan')->nullable()->comment('Alasan jika ditolak');
                    $table->timestamps();

                    // Add indexes and unique constraint for better performance
                    $table->index('pamong_id', 'catatan_harian_pamong_fk');
                    $table->index('approved_by', 'catatan_harian_approved_by_fk');
                    $table->index(['config_id', 'status'], 'catatan_harian_config_status_idx');
                    $table->index(['config_id', 'tanggal'], 'catatan_harian_config_tanggal_idx');
                    $table->index(['pamong_id', 'status'], 'catatan_harian_pamong_status_idx');

                    // Foreign keys
                    $table->foreign('pamong_id', 'catatan_harian_pamong_fk')
                        ->references('pamong_id')->on('tweb_desa_pamong')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                    $table->foreign('approved_by', 'catatan_harian_approved_by_fk')
                        ->references('pamong_id')->on('tweb_desa_pamong')
                        ->onUpdate('cascade')
                        ->onDelete('set null');
                });
            }
        } catch (Throwable $th) {
            log_message('error', 'Migrasi Kehadiran Catatan Harian Gagal: ' . $th->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_catatan_harian', static function () {
            // Cleanup data jika diperlukan
        });
    }
};
