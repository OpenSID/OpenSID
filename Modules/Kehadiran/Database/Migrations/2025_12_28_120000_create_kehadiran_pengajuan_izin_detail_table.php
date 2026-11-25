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
use Modules\Kehadiran\Models\PengajuanIzinDetail;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            if (! Schema::hasTable('kehadiran_pengajuan_izin_detail')) {
                Schema::create('kehadiran_pengajuan_izin_detail', static function (Blueprint $table) {
                    $table->integer('id', true);
                    $table->configId();
                    $table->unsignedBigInteger('pengajuan_izin_id')->comment('FK ke tabel pengajuan izin');
                    $table->date('tanggal')->comment('Tanggal izin spesifik');
                    $table->enum('jenis_izin', [
                        'izin',
                        'sakit',
                        'dinas_luar_kota',
                        'cuti',
                        'lainnya',
                    ])->comment('Jenis izin (copy dari header)');
                    $table->integer('id_pamong')->comment('ID pamong (copy dari header)');
                    $table->enum('status', [
                        'pending',
                        'approved',
                        'rejected',
                    ])->default('pending')->comment('Status approval (copy dari header)');
                    $table->timestamps();

                    // Add indexes for better reporting performance
                    $table->index(['config_id', 'tanggal'], 'pengajuan_detail_config_tanggal_idx');
                    $table->index(['config_id', 'tanggal', 'status'], 'pengajuan_detail_config_tanggal_status_idx');
                    $table->index(['config_id', 'id_pamong', 'tanggal'], 'pengajuan_detail_pamong_tanggal_idx');
                    $table->index(['tanggal', 'jenis_izin'], 'pengajuan_detail_tanggal_jenis_idx');

                    $table->foreign('pengajuan_izin_id', 'pengajuan_detail_header_fk')
                        ->references('id')->on('kehadiran_pengajuan_izin')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

                    $table->foreign('id_pamong', 'pengajuan_detail_pamong_fk')
                        ->references('pamong_id')->on('tweb_desa_pamong')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                });
            }
        } catch (Throwable $th) {
            log_message('error', 'Migrasi Kehadiran Pengajuan Izin Detail Gagal: ' . $th->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('kehadiran_pengajuan_izin_detail', static function () {
            PengajuanIzinDetail::withoutConfigId(identitas('id'))->delete();
        });
    }
};
