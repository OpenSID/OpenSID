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

use App\Models\FormatSurat;
use App\Models\SuratDinas;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->create_table_webhook_calls();
        $this->defaultkanSuratBawaanSistemValid();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function create_table_webhook_calls(): void
    {
        if (Schema::hasTable('webhook_calls')) {
            return;
        }

        Schema::create('webhook_calls', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('config_id')->nullable()->index('webhook_calls_config_id_foreign');
            $table->string('name');
            $table->string('url', 512);
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->json('attachments')->nullable();
            $table->text('exception')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Surat bawaan sistem selalu boleh dipakai mencetak terlepas dari status_validasi-nya
     * (lihat FormatSurat::siapDicetak()/scopeSiapCetak()), tapi kolomnya sendiri masih bisa
     * "Belum Divalidasi" karena tombol "Validasi Template" memang disembunyikan untuk jenis
     * ini. Set default datanya jadi Valid supaya tidak menyesatkan kalau kolom itu dibaca
     * langsung di luar jalur yang sudah membypass-nya.
     */
    private function defaultkanSuratBawaanSistemValid(): void
    {
        if (Schema::hasTable('tweb_surat_format')) {
            DB::table('tweb_surat_format')
                ->whereIn('jenis', FormatSurat::SISTEM)
                ->where('status_validasi', '!=', FormatSurat::VALIDASI_VALID)
                ->update(['status_validasi' => FormatSurat::VALIDASI_VALID]);
        }

        if (Schema::hasTable('surat_dinas')) {
            DB::table('surat_dinas')
                ->whereIn('jenis', SuratDinas::SISTEM)
                ->where('status_validasi', '!=', SuratDinas::VALIDASI_VALID)
                ->update(['status_validasi' => SuratDinas::VALIDASI_VALID]);
        }
    }
};
