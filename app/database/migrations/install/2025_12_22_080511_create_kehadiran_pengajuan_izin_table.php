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
        Schema::create('kehadiran_pengajuan_izin', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable();
            $table->integer('id_pamong');
            $table->enum('jenis_izin', ['izin', 'sakit', 'dinas_luar_kota', 'cuti', 'lainnya'])->comment('Jenis izin yang diajukan');
            $table->date('tanggal_mulai')->comment('Tanggal mulai izin');
            $table->date('tanggal_selesai')->comment('Tanggal selesai izin');
            $table->text('keterangan')->comment('Keterangan alasan izin');
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status persetujuan');
            $table->integer('approved_by')->nullable()->index('pengajuan_izin_approved_by_fk');
            $table->dateTime('tanggal_approval')->nullable()->comment('Tanggal approval/reject');
            $table->text('keterangan_approval')->nullable()->comment('Keterangan dari atasan');
            $table->string('lampiran')->nullable()->comment('File lampiran (untuk sakit, dll)');
            $table->timestamps();

            $table->index(['config_id', 'status_approval'], 'pengajuan_izin_config_status_idx');
            $table->index(['config_id', 'tanggal_mulai'], 'pengajuan_izin_config_tanggal_idx');
            $table->index(['id_pamong', 'status_approval'], 'pengajuan_izin_pamong_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pengajuan_izin');
    }
};
