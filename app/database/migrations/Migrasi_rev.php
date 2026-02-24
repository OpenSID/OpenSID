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
        $this->tambahTanggalPeriksa();

        // if (! $this->cek_indeks('artikel', 'artikel_unique_judul_config')) {
        //     Schema::table('artikel', function (Blueprint $table) {
        //         $table->unique(['judul', 'config_id'], 'artikel_unique_judul_config');
        //     });
        // }

        $this->tweb_penduduk_mandiri();
        $this->modifikasiStrukturTabel();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropUnique('artikel_unique_judul_config');
        });
    }

    public function tambahTanggalPeriksa()
    {
        // tambahkan kolom jika belum ada
        if (!Schema::hasColumn('bulanan_anak', 'tanggal_periksa')) {
            Schema::table('bulanan_anak', function (Blueprint $table) {
                $table->date('tanggal_periksa')->nullable()->after('keterangan');
            });
        }

        // selalu coba isi nilai null dengan created_at
        if (Schema::hasColumn('bulanan_anak', 'tanggal_periksa')) {
            DB::table('bulanan_anak')
                ->whereNull('tanggal_periksa')
                ->update(['tanggal_periksa' => DB::raw('created_at')]);
        }
    }

    
    public function tweb_penduduk_mandiri(): void
    {
        if (Schema::hasTable('tweb_penduduk_mandiri')) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // Step 1: Drop SEMUA foreign keys pada tweb_penduduk_mandiri (dinamis)
                $this->dropAllForeignKeysOnTable('tweb_penduduk_mandiri');

                // Step 2: Drop SEMUA foreign keys dari table lain yang mereferensi tweb_penduduk_mandiri (dinamis)
                $this->dropAllReferencingForeignKeys('tweb_penduduk_mandiri');

                // Step 3: Hilangkan AUTO_INCREMENT dulu dari id_pend
                DB::statement('ALTER TABLE tweb_penduduk_mandiri MODIFY id_pend INT NOT NULL');

                // Step 4: Drop primary key lama (jika ada)
                $PK = $this->cek_primary_key('tweb_penduduk_mandiri', ['id_pend']);
                if ($PK) {
                    DB::statement('ALTER TABLE tweb_penduduk_mandiri DROP PRIMARY KEY');
                }

                // Step 5: Tambahkan kolom id sebagai primary key baru (jika belum ada)
                if (!Schema::hasColumn('tweb_penduduk_mandiri', 'id')) {
                    Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
                        $table->bigIncrements('id')->first();
                    });
                }

                // Step 6: Re-create foreign keys yang sudah dihapus
                $this->recreateForeignKeys();

            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
    }

    /**
     * Re-create foreign keys yang sudah dihapus
     */
    private function recreateForeignKeys(): void
    {
        try {
            // Re-create foreign keys untuk tweb_penduduk_mandiri
            if (Schema::hasTable('tweb_penduduk_mandiri') && Schema::hasTable('config')) {
                if (!$this->foreignKeyExists('tweb_penduduk_mandiri', 'tweb_penduduk_mandiri_config_2026_fk')) {
                    Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
                        $table->foreign(['config_id'], 'tweb_penduduk_mandiri_config_2026_fk')
                            ->references(['id'])
                            ->on('config')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
                    });
                }
            }

            // Re-create foreign key untuk id_pend
            if (Schema::hasTable('tweb_penduduk_mandiri') && Schema::hasTable('tweb_penduduk')) {
                if (!$this->foreignKeyExists('tweb_penduduk_mandiri', 'tweb_penduduk_mandiri_penduduk_2026_fk')) {
                    Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
                        $table->foreign(['id_pend'], 'tweb_penduduk_mandiri_penduduk_2026_fk')
                            ->references(['id'])
                            ->on('tweb_penduduk')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
                    });
                }
            }

            // Re-create foreign key dari log_notifikasi_mandiri
            if (Schema::hasTable('log_notifikasi_mandiri') && Schema::hasTable('tweb_penduduk_mandiri')) {
                if (!$this->foreignKeyExists('log_notifikasi_mandiri', 'log_notifikasi_mandiri_user_mandiri_2026_fk')) {
                    Schema::table('log_notifikasi_mandiri', function (Blueprint $table) {
                        $table->foreign(['id_user_mandiri'], 'log_notifikasi_mandiri_user_mandiri_2026_fk')
                            ->references(['id_pend'])
                            ->on('tweb_penduduk_mandiri')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
                    });
                }
            }
        } catch (\Exception $e) {
            logger()->error('Gagal merecreate foreign keys: ' . $e->getMessage());
        }
    }

    public function modifikasiStrukturTabel(): void
    {
        $this->hapusForeignKey('fcm_token_user_fk', 'fcm_token', 'user');
        $this->hapusForeignKey('fcm_token_config_fk', 'fcm_token', 'config');

        if (! $this->foreignKeyExists('fcm_token', 'fcm_token_config_2026_fk')) {
            Schema::table('fcm_token', static function (Blueprint $table) {
                $table->foreign(['config_id'], 'fcm_token_config_2026_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (! $this->foreignKeyExists('artikel', 'artikel_config_2026_fk')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->foreign(['config_id'], 'artikel_config_2026_fk')->references(['id'])->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (! $this->foreignKeyExists('artikel', 'artikel_kategori_2026_fk')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->foreign(['id_kategori'], 'artikel_kategori_2026_fk')->references(['id'])->on('kategori')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // Drop FK lama sebelum mengubah tipe kolom id_user
        $this->hapusForeignKey('artikel_kategori_id_user_fk', 'artikel', 'user');

        Schema::table('artikel', static function (Blueprint $table) {
            $table->integer('id_user')->nullable()->change();
        });

        if (! $this->foreignKeyExists('artikel', 'artikel_kategori_id_user_2026_fk')) {
            Schema::table('artikel', static function (Blueprint $table) {
                $table->foreign(['id_user'], 'artikel_kategori_id_user_2026_fk')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('set null');
            });
        }
    } 
};
