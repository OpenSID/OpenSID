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
        $this->restructure();
        $this->tweb_penduduk_mandiri();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }

    public function restructure(): void
    {
        // Hapus foreign key yang duplikat
        $this->hapusForeignKey('id_pend_fk', 'dokumen', 'tweb_penduduk');
        $this->hapusForeignKey('log_tolak_surat_fk', 'log_tolak', 'log_surat');

        // Tambah relasi foreign key yang hilang pada kolom config_id tabel sinergi_program
        $this->tambahForeignKey('sinergi_program_config_fk', 'sinergi_program', 'config_id', 'tweb_config', 'id', 'CASCADE', 'CASCADE');

    }

    public function tweb_penduduk_mandiri(): void
    {
        if (Schema::hasTable('tweb_penduduk_mandiri')) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                $PK = $this->cek_primary_key('tweb_penduduk_mandiri', ['id_pend']);
                if ($PK) {
                    logger()->info('Migrasi_rev: Menghapus primary key id_pend di tabel tweb_penduduk_mandiri');
                    Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
                        $table->dropPrimary();
                    });
                }

                DB::statement('ALTER TABLE tweb_penduduk_mandiri MODIFY id_pend INT NOT NULL');

                if (!Schema::hasColumn('tweb_penduduk_mandiri', 'id')) {
                    Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
                        $table->bigIncrements('id')->first();
                    });
                }
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
    }
};
