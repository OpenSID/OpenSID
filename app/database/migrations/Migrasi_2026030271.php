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
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambahSoftDeleteUser();
        $this->perbaikiUniqueConstraintUser();
        $this->ubahForeignKeyCascadeKeSetNull();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    /**
     * Tambah kolom deleted_at ke tabel user untuk mendukung soft delete.
     */
    public function tambahSoftDeleteUser(): void
    {
        if (! Schema::hasColumn('user', 'deleted_at')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Perbaiki unique constraint tabel user agar mendukung soft delete.
     *
     * Constraint lama (config_id, username) mencegah pembuatan user baru
     * dengan username yang sama ketika user lama sudah di-soft delete.
     * Solusi: tambahkan deleted_at ke constraint sehingga username yang sama
     * bisa dipakai lagi setelah user lama dihapus.
     */
    public function perbaikiUniqueConstraintUser(): void
    {
        // Drop semua foreign key di tabel user dulu karena MySQL tidak mengizinkan
        // drop index yang masih dipakai sebagai backing index foreign key.
        $this->dropAllForeignKeysOnTable('user');

        // Drop unique constraints lama yang tidak include deleted_at
        if ($this->cek_indeks('user', 'username_config')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->dropUnique('username_config');
            });
        }

        if ($this->cek_indeks('user', 'email_config')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->dropUnique('email_config');
            });
        }

        if ($this->cek_indeks('user', 'pamong_id_config')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->dropUnique('pamong_id_config');
            });
        }

        // Buat unique constraint baru yang include deleted_at
        if (! $this->cek_indeks('user', 'username_config_deleted')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->unique(['config_id', 'username', 'deleted_at'], 'username_config_deleted');
            });
        }

        if (! $this->cek_indeks('user', 'email_config_deleted')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->unique(['config_id', 'email', 'deleted_at'], 'email_config_deleted');
            });
        }

        if (! $this->cek_indeks('user', 'pamong_id_config_deleted')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->unique(['config_id', 'pamong_id', 'deleted_at'], 'pamong_id_config_deleted');
            });
        }

        // Recreate semua foreign key yang tadi di-drop
        if (! $this->foreignKeyExists('user', 'user_config_fk')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->foreign(['config_id'], 'user_config_fk')
                    ->references(['id'])->on('config')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (! $this->foreignKeyExists('user', 'user_grup_fk')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->foreign(['id_grup'], 'user_grup_fk')
                    ->references(['id'])->on('user_grup')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (! $this->foreignKeyExists('user', 'user_pamong_fk')) {
            Schema::table('user', static function (Blueprint $table) {
                $table->foreign(['pamong_id'], 'user_pamong_fk')
                    ->references(['pamong_id'])->on('tweb_desa_pamong')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Ubah foreign key terkait user dari CASCADE ke SET NULL agar riwayat
     * surat, notifikasi, dan artikel tidak ikut terhapus ketika user dihapus.
     */
    public function ubahForeignKeyCascadeKeSetNull(): void
    {
        // log_surat.id_user: CASCADE → SET NULL (sudah nullable)
        $this->hapusForeignKey('log_surat_user_fk', 'log_surat', 'user');

        if (! $this->foreignKeyExists('log_surat', 'log_surat_user_2026_fk')) {
            Schema::table('log_surat', static function (Blueprint $table) {
                $table->foreign(['id_user'], 'log_surat_user_2026_fk')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }

        // log_surat_dinas.id_user: CASCADE → SET NULL (perlu nullable dulu)
        $this->hapusForeignKey('log_surat_dinas_user_fk', 'log_surat_dinas', 'user');

        if (! $this->foreignKeyExists('log_surat_dinas', 'log_surat_dinas_user_2026_fk')) {
            Schema::table('log_surat_dinas', static function (Blueprint $table) {
                $table->integer('id_user')->nullable()->change();
                $table->foreign(['id_user'], 'log_surat_dinas_user_2026_fk')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }

        // log_surat_dinas.created_by: CASCADE → SET NULL
        $this->hapusForeignKey('log_surat_dinas_created_by_fk', 'log_surat_dinas', 'user');

        if (! $this->foreignKeyExists('log_surat_dinas', 'log_surat_dinas_created_by_2026_fk')) {
            Schema::table('log_surat_dinas', static function (Blueprint $table) {
                $table->foreign(['created_by'], 'log_surat_dinas_created_by_2026_fk')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }

        // log_surat_dinas.updated_by: CASCADE → SET NULL
        $this->hapusForeignKey('log_surat_dinas_updated_by_fk', 'log_surat_dinas', 'user');

        if (! $this->foreignKeyExists('log_surat_dinas', 'log_surat_dinas_updated_by_2026_fk')) {
            Schema::table('log_surat_dinas', static function (Blueprint $table) {
                $table->foreign(['updated_by'], 'log_surat_dinas_updated_by_2026_fk')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }

        // log_notifikasi_admin.id_user: CASCADE → SET NULL (sudah nullable)
        $this->hapusForeignKey('log_notifikasi_admin_user_fk', 'log_notifikasi_admin', 'user');

        if (! $this->foreignKeyExists('log_notifikasi_admin', 'log_notifikasi_admin_user_2026_fk')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
                $table->foreign(['id_user'], 'log_notifikasi_admin_user_2026_fk')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }
    }
};
