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

use App\Enums\FormatNoRtmEnum;
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
        $this->tweb_penduduk_mandiri();
        $this->modifikasiStrukturTabel();

        $this->tambahPengaturanNoRtm();
        $this->tambahKolomIdKelompokDokumen();
        $this->updateViewDokumenHidup();
        $this->tambahUniqueSlugConfigArtikel();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function tambahTanggalPeriksa()
    {
        // tambahkan kolom jika belum ada
        if (! Schema::hasColumn('bulanan_anak', 'tanggal_periksa')) {
            Schema::table('bulanan_anak', static function (Blueprint $table) {
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
                if (! Schema::hasColumn('tweb_penduduk_mandiri', 'id')) {
                    Schema::table('tweb_penduduk_mandiri', static function (Blueprint $table) {
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

    public function tambahPengaturanNoRtm()
    {
        $this->createSetting([
            'judul'      => 'Format Nomor Rumah Tangga',
            'key'        => 'format_no_rtm',
            'value'      => FormatNoRtmEnum::ANGKA,
            'keterangan' => 'Format yang digunakan untuk penomoran nomor rumah tangga',
            'jenis'      => 'select-array',
            'option'     => json_encode(FormatNoRtmEnum::toOptionArray()),
            'kategori'   => 'sistem',
            'attribute'  => json_encode([]),
        ]);
    }

    public function tambahKolomIdKelompokDokumen(): void
    {
        try {
            if (Schema::hasTable('dokumen') && ! Schema::hasColumn('dokumen', 'id_kelompok')) {
                Schema::table('dokumen', static function (Blueprint $table): void {
                    $table->unsignedBigInteger('id_kelompok')->nullable()->after('config_id');

                    $table->foreign('id_kelompok')
                        ->references('id')
                        ->on('kelompok')
                        ->onDelete('cascade');
                });
            }
        } catch (Exception $e) {
            log_message('error', 'Gagal menambahkan kolom id_kelompok pada tabel dokumen: ' . $e->getMessage());
        }
    }

    public function updateViewDokumenHidup(): void
    {
        try {
            DB::statement('DROP VIEW IF EXISTS `dokumen_hidup`');
            DB::statement('CREATE VIEW `dokumen_hidup` AS select `dokumen`.`id` AS `id`,`dokumen`.`config_id` AS `config_id`,`dokumen`.`satuan` AS `satuan`,`dokumen`.`nama` AS `nama`,`dokumen`.`enabled` AS `enabled`,`dokumen`.`tgl_upload` AS `tgl_upload`,`dokumen`.`id_pend` AS `id_pend`,`dokumen`.`kategori` AS `kategori`,`dokumen`.`attr` AS `attr`,`dokumen`.`tipe` AS `tipe`,`dokumen`.`url` AS `url`,`dokumen`.`tahun` AS `tahun`,`dokumen`.`kategori_info_publik` AS `kategori_info_publik`,`dokumen`.`updated_at` AS `updated_at`,`dokumen`.`deleted` AS `deleted`,`dokumen`.`id_syarat` AS `id_syarat`,`dokumen`.`id_parent` AS `id_parent`,`dokumen`.`created_at` AS `created_at`,`dokumen`.`created_by` AS `created_by`,`dokumen`.`updated_by` AS `updated_by`,`dokumen`.`dok_warga` AS `dok_warga`,`dokumen`.`lokasi_arsip` AS `lokasi_arsip`,`dokumen`.`keterangan` AS `keterangan`,`dokumen`.`status` AS `status`,`dokumen`.`retensi_date` AS `retensi_date`,`dokumen`.`retensi_number` AS `retensi_number`,`dokumen`.`retensi_unit` AS `retensi_unit`,`dokumen`.`published_at` AS `published_at`,`dokumen`.`id_kelompok` AS `id_kelompok` from `dokumen` where `dokumen`.`deleted` <> 1');
        } catch (Exception $e) {
            log_message('error', 'Gagal update view dokumen_hidup: ' . $e->getMessage());
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
                if (! $this->foreignKeyExists('tweb_penduduk_mandiri', 'tweb_penduduk_mandiri_config_2026_fk')) {
                    Schema::table('tweb_penduduk_mandiri', static function (Blueprint $table) {
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
                if (! $this->foreignKeyExists('tweb_penduduk_mandiri', 'tweb_penduduk_mandiri_penduduk_2026_fk')) {
                    Schema::table('tweb_penduduk_mandiri', static function (Blueprint $table) {
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
                if (! $this->foreignKeyExists('log_notifikasi_mandiri', 'log_notifikasi_mandiri_user_mandiri_2026_fk')) {
                    Schema::table('log_notifikasi_mandiri', static function (Blueprint $table) {
                        $table->foreign(['id_user_mandiri'], 'log_notifikasi_mandiri_user_mandiri_2026_fk')
                            ->references(['id_pend'])
                            ->on('tweb_penduduk_mandiri')
                            ->onUpdate('cascade')
                            ->onDelete('cascade');
                    });
                }
            }
        } catch (Exception $e) {
            logger()->error('Gagal merecreate foreign keys: ' . $e->getMessage());
        }
    }

    /**
     * Tambahkan unique constraint pada artikel (slug + config_id)
     * dengan pengecekan duplikat dan index existing
     */
    public function tambahUniqueSlugConfigArtikel()
    {
        // Cek duplikat data dulu
        $duplikat = DB::table('artikel')
            ->select('slug', 'config_id', DB::raw('COUNT(*) as total'))
            ->groupBy('slug', 'config_id')
            ->having('total', '>', 1)
            ->exists();

        if ($duplikat) {
            set_session(
                'warning',
                'Terdapat data duplikat slug artikel pada konfigurasi yang sama. 
                Silakan cek dan perbaiki data di halaman <a href="/periksa">periksa</a> 
                sebelum menjalankan migrasi kembali.'
            );
            return;
        }

        // hapus unique judul index jika ada
        $judulIndexAda = collect(DB::select("SHOW INDEX FROM artikel WHERE Key_name = 'artikel_unique_judul_config'"))
            ->isNotEmpty();

        if ($judulIndexAda) {
            DB::statement("ALTER TABLE artikel DROP INDEX artikel_unique_judul_config");
        }

        // Cek apakah index sudah ada
        $indexSudahAda = collect(DB::select("SHOW INDEX FROM artikel WHERE Key_name = 'artikel_unique_slug_config'"))
            ->isNotEmpty();

        if ($indexSudahAda) {
            // Sudah ada, tidak perlu buat lagi
            return;
        }

        // Tambahkan unique index
        Schema::table('artikel', function (Blueprint $table) {
            $table->unique(['slug', 'config_id'], 'artikel_unique_slug_config');
        });
    }
};
