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
 * Hak Cipta 2009 - 2015 Combine Resource Institution
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka
 *
 */

namespace Tests\Unit\Issue10441;

use Tests\BaseTestCase;

/**
 * Test untuk memastikan nama file migrasi bersifat case-sensitive
 *
 * Issue: #10441
 * Tujuan: Mencegah error di Linux akibat perbedaan huruf besar/kecil
 */
class MigrationFileCaseSensitiveTest extends BaseTestCase
{
    /**
     * Cek file dengan perbandingan case-sensitive
     */
    protected function fileExistsCaseSensitive(string $path): bool
    {
        $dir  = dirname($path);
        $file = basename($path);

        if (! is_dir($dir)) {
            return false;
        }

        return in_array($file, scandir($dir), true);
    }

    /**
     * Test: File migrasi harus ada dengan penulisan huruf yang benar
     */
    public function test_migration_files_case_sensitive(): void
    {
        $path = FCPATH . 'app/database/migrations';

        $files = [
            'Migrasi_required.php',
            'Migrasi_rev.php',
            'Migrasi_beta.php',
            'Migrasi_module.php',
        ];

        foreach ($files as $file) {
            $this->assertTrue(
                $this->fileExistsCaseSensitive($path . DIRECTORY_SEPARATOR . $file),
                "File migrasi {$file} tidak ditemukan atau salah huruf besar/kecil"
            );
        }
    }

    /**
     * Test: Tidak boleh ada file migrasi dengan nama lowercase
     */
    public function test_no_lowercase_migration_files(): void
    {
        $path = FCPATH . 'app/database/';

        $invalidFiles = [
            'migrasi_required.php',
            'migrasi_rev.php',
            'migrasi_beta.php',
            'migrasi_module.php',
        ];

        foreach ($invalidFiles as $file) {
            $this->assertFalse(
                file_exists($path . DIRECTORY_SEPARATOR . $file),
                "File migrasi lowercase {$file} tidak boleh ada"
            );
        }
    }
}
