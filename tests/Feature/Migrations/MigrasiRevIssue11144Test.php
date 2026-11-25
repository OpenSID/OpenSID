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

namespace Tests\Feature\Migrations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrasiRevIssue11144Test extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Test bahwa migration Migrasi_rev membuat table security_baselines
     *
     * Issue #11144: Kolom atau tabel tidak ditemukan padahal sudah jalankan migrasi
     */
    public function test_security_baselines_table_is_created(): void
    {
        // Setup: hapus table security_baselines dan column pekerja_migran
        // untuk simulate kondisi legacy database yang belum difix
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }

        // Pastikan table tidak ada sebelum migrasi
        $this->assertFalse(Schema::hasTable('security_baselines'));

        // Act: Jalankan migration untuk fix issue
        $this->artisan('migrate');

        // Assert: Table security_baselines harus ada setelah migration
        $this->assertTrue(
            Schema::hasTable('security_baselines'),
            'Table security_baselines seharusnya ada setelah migration'
        );
    }

    /**
     * Test bahwa security_baselines table memiliki semua column yang diperlukan
     */
    public function test_security_baselines_table_has_correct_structure(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Assert: Column-column penting harus ada
        $this->assertTrue(Schema::hasColumn('security_baselines', 'id'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'config_id'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'generated_at'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'version'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'target_directory'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'excluded_dirs'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'statistics'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'files'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'created_at'));
        $this->assertTrue(Schema::hasColumn('security_baselines', 'updated_at'));
    }

    /**
     * Test bahwa foreign key ke config table ditambahkan pada security_baselines
     */
    public function test_security_baselines_has_foreign_key_to_config(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Assert: Foreign key harus ada
        $fkExists = DB::selectOne('
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_SCHEMA = DATABASE()
        ', ['security_baselines', 'security_baselines_config_id_foreign']);

        $this->assertNotNull(
            $fkExists,
            'Foreign key security_baselines_config_id_foreign seharusnya ada'
        );
    }

    /**
     * Test bahwa pekerja_migran column ditambahkan ke tweb_penduduk
     */
    public function test_pekerja_migran_column_is_added_to_tweb_penduduk(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Pastikan column tidak ada sebelum migrasi
        $this->assertFalse(Schema::hasColumn('tweb_penduduk', 'pekerja_migran'));
        
        // Jalankan migration
        $this->artisan('migrate');

        // Assert: Column pekerja_migran harus ada
        $this->assertTrue(
            Schema::hasColumn('tweb_penduduk', 'pekerja_migran'),
            'Column pekerja_migran seharusnya ada di table tweb_penduduk setelah migration'
        );
    }

    /**
     * Test bahwa pekerja_migran column nullable
     */
    public function test_pekerja_migran_column_is_nullable(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Get column info
        $columnInfo = DB::selectOne('
            SELECT IS_NULLABLE, COLUMN_TYPE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND TABLE_SCHEMA = DATABASE()
        ', ['tweb_penduduk', 'pekerja_migran']);

        $this->assertNotNull($columnInfo);
        $this->assertEquals(
            'YES',
            $columnInfo->IS_NULLABLE,
            'Column pekerja_migran seharusnya nullable'
        );
        $this->assertStringContainsString(
            'varchar',
            strtolower($columnInfo->COLUMN_TYPE),
            'Column pekerja_migran seharusnya varchar'
        );
    }

    /**
     * Test bahwa pekerja_migran column ditempatkan setelah column adat
     */
    public function test_pekerja_migran_column_placement(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Get all columns in order
        $columns = DB::selectOne('
            SELECT GROUP_CONCAT(COLUMN_NAME ORDER BY ORDINAL_POSITION SEPARATOR ",") as column_order
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = ? AND TABLE_SCHEMA = DATABASE()
        ', ['tweb_penduduk']);

        $columnOrder = explode(',', $columns->column_order);
        $adatIndex = array_search('adat', $columnOrder);
        $pekerjaMigranIndex = array_search('pekerja_migran', $columnOrder);

        $this->assertNotFalse(
            $adatIndex,
            'Column adat harus ada di tweb_penduduk'
        );
        $this->assertNotFalse(
            $pekerjaMigranIndex,
            'Column pekerja_migran harus ada di tweb_penduduk'
        );
        $this->assertEquals(
            $adatIndex + 1,
            $pekerjaMigranIndex,
            'Column pekerja_migran seharusnya langsung setelah column adat'
        );
    }

    /**
     * Test idempotency - migration aman dijalankan berkali-kali
     */
    public function test_migration_is_idempotent(): void
    {
        // Setup: Jalankan migrate:fresh
        $this->artisan('migrate:fresh --no-interaction');

        // Assert: State pertama
        $this->assertTrue(Schema::hasTable('security_baselines'));
        $this->assertTrue(Schema::hasColumn('tweb_penduduk', 'pekerja_migran'));

        // Act: Jalankan migration lagi (harus aman)
        $this->artisan('migrate --no-interaction');

        // Assert: State harus sama (tidak error, tidak duplicate)
        $this->assertTrue(Schema::hasTable('security_baselines'));
        $this->assertTrue(Schema::hasColumn('tweb_penduduk', 'pekerja_migran'));

        // Verify: Hanya satu foreign key dengan nama tersebut
        $foreignKeysCount = DB::selectOne('
            SELECT COUNT(*) as count
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_SCHEMA = DATABASE()
        ', ['security_baselines', 'security_baselines_config_id_foreign']);

        $this->assertEquals(
            1,
            $foreignKeysCount->count,
            'Hanya satu foreign key dengan nama security_baselines_config_id_foreign yang boleh ada'
        );
    }

    /**
     * Test bahwa Penduduk model bisa mass assign pekerja_migran
     */
    public function test_penduduk_model_can_mass_assign_pekerja_migran(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Get model fillable array
        $model = app(\App\Models\Penduduk::class);
        $fillable = $model->getFillable();

        $this->assertContains(
            'pekerja_migran',
            $fillable,
            'pekerja_migran harus ada di $fillable array Penduduk model'
        );
    }

    /**
     * Test bahwa index pada security_baselines dibuat dengan benar
     */
    public function test_security_baselines_has_correct_indexes(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Jalankan migration
        $this->artisan('migrate');

        // Assert: Index pada config_id dan generated_at harus ada
        $indexes = DB::selectOne('
            SELECT COUNT(*) as count
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_NAME = ? AND TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME IN (?, ?) AND INDEX_NAME != ?
        ', ['security_baselines', 'config_id', 'generated_at', 'PRIMARY']);

        $this->assertGreaterThan(
            0,
            $indexes->count,
            'Index pada config_id dan generated_at seharusnya ada di security_baselines'
        );
    }

    /**
     * VERIFICATION TEST: Comprehensive check untuk issue #11144 fix
     * Verifikasi bahwa kedua masalah sudah teratasi
     */
    public function test_issue_11144_is_fully_fixed(): void
    {
        // Setup: Jalankan migrate:fresh dan hapus table/column
        $this->artisan('migrate:fresh --no-interaction');
        
        if (Schema::hasTable('security_baselines')) {
            Schema::drop('security_baselines');
        }

        if (Schema::hasTable('tweb_penduduk') && Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', function ($table) {
                $table->dropColumn('pekerja_migran');
            });
        }
        
        // Ensure migration is run
        $this->artisan('migrate');

        // ===== VERIFICATION 1: security_baselines table exists =====
        $this->assertTrue(
            Schema::hasTable('security_baselines'),
            'VERIFY FAILED: Table security_baselines tidak ditemukan'
        );

        // Verify all required columns exist
        $requiredColumns = [
            'id',
            'config_id',
            'generated_at',
            'version',
            'target_directory',
            'excluded_dirs',
            'statistics',
            'files',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('security_baselines', $column),
                "VERIFY FAILED: Column {$column} missing dari security_baselines"
            );
        }

        // ===== VERIFICATION 2: pekerja_migran column exists =====
        $this->assertTrue(
            Schema::hasTable('tweb_penduduk'),
            'VERIFY FAILED: Table tweb_penduduk tidak ditemukan'
        );

        $this->assertTrue(
            Schema::hasColumn('tweb_penduduk', 'pekerja_migran'),
            'VERIFY FAILED: Column pekerja_migran tidak ada di tweb_penduduk'
        );

        // ===== VERIFICATION 3: Foreign key exists =====
        $fkExists = DB::selectOne('
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_SCHEMA = DATABASE()
        ', ['security_baselines', 'security_baselines_config_id_foreign']);

        $this->assertNotNull(
            $fkExists,
            'VERIFY FAILED: Foreign key security_baselines_config_id_foreign tidak ditemukan'
        );

        // ===== VERIFICATION 4: pekerja_migran column properties =====
        $columnInfo = DB::selectOne('
            SELECT IS_NULLABLE, COLUMN_TYPE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND TABLE_SCHEMA = DATABASE()
        ', ['tweb_penduduk', 'pekerja_migran']);

        $this->assertNotNull($columnInfo, 'VERIFY FAILED: Column info untuk pekerja_migran tidak ditemukan');
        $this->assertEquals('YES', $columnInfo->IS_NULLABLE, 'VERIFY FAILED: Column pekerja_migran seharusnya nullable');
        $this->assertStringContainsString('varchar', strtolower($columnInfo->COLUMN_TYPE), 'VERIFY FAILED: Column pekerja_migran seharusnya varchar');

        // ===== VERIFICATION 5: Model can mass assign =====
        $model = app(\App\Models\Penduduk::class);
        $fillable = $model->getFillable();
        $this->assertContains('pekerja_migran', $fillable, 'VERIFY FAILED: pekerja_migran tidak di $fillable array Penduduk model');

        // ===== VERIFICATION 6: No duplicate constraints =====
        $duplicates = DB::select('
            SELECT CONSTRAINT_NAME, COUNT(*) as count
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND TABLE_SCHEMA = DATABASE()
            GROUP BY CONSTRAINT_NAME
            HAVING count > 1
        ', ['security_baselines']);

        $this->assertEmpty($duplicates, 'VERIFY FAILED: Ada duplicate constraint di security_baselines');

        // ===== ALL VERIFICATIONS PASSED =====
        $this->assertTrue(true, 'Issue #11144 is FULLY FIXED ✓');
    }

    /**
     * QUICK VERIFY: Simple check untuk perlu-perlu saja
     * Gunakan ini untuk quick validation di production
     */
    public function test_issue_11144_quick_verify(): void
    {
        // Ensure migration is run
        $this->artisan('migrate');

        // Quick check results
        $results = [
            'security_baselines_exists' => Schema::hasTable('security_baselines'),
            'pekerja_migran_exists' => Schema::hasColumn('tweb_penduduk', 'pekerja_migran'),
            'fk_exists' => ! is_null(DB::selectOne('
                SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_SCHEMA = DATABASE()
            ', ['security_baselines', 'security_baselines_config_id_foreign'])),
            'model_fillable_ok' => in_array('pekerja_migran', app(\App\Models\Penduduk::class)->getFillable()),
        ];

        // All results must be true
        foreach ($results as $check => $result) {
            $this->assertTrue($result, "QUICK VERIFY: {$check} FAILED");
        }

        // Summary
        $allPassed = array_reduce($results, fn ($carry, $item) => $carry && $item, true);
        $this->assertTrue($allPassed, 'Issue #11144 QUICK VERIFY status: PASSED ✓');
    }
}
