<?php

namespace Tests\Unit\Services;

use App\Services\Kapabilitas\GerbangFitur;
use App\Services\Module\ModuleManager;
use Tests\BaseTestCase;

/**
 * Mirror dari premium#6877 (OpenSID/premium teknis/anjungan-kiosk-seam):
 * ModuleManager::installedFingerprint() jadi bagian kunci cache nav sidebar
 * (admin_menu(), general_helper.php) supaya perubahan folder Modules/ dari
 * luar siklus request tenant manapun otomatis membuat cache lama basi.
 */
class ModuleManagerFingerprintTest extends BaseTestCase
{
    private string $base;

    private ModuleManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->base = sys_get_temp_dir() . '/mm-fingerprint-umum-' . uniqid('', true);
        mkdir($this->base . '/Demo', 0777, true);
        file_put_contents($this->base . '/Demo/module.json', json_encode([
            'name'      => 'Demo',
            'version'   => '1.0.0',
            'menu_slug' => 'demo',
        ]));

        $this->manager = new ModuleManager(new GerbangFitur(), $this->base);
    }

    protected function tearDown(): void
    {
        $this->hapusRekursif($this->base);
        parent::tearDown();
    }

    public function test_fingerprint_berubah_saat_folder_modul_berubah(): void
    {
        $awal = $this->manager->installedFingerprint();

        // Stabil tanpa perubahan apa pun.
        $this->assertSame($awal, $this->manager->installedFingerprint());

        mkdir($this->base . '/Lain', 0777, true);
        file_put_contents($this->base . '/Lain/module.json', json_encode([
            'name' => 'Lain', 'version' => '1.0.0', 'menu_slug' => 'lain',
        ]));

        $setelahTambah = $this->manager->installedFingerprint();
        $this->assertNotSame($awal, $setelahTambah, 'Menambah folder modul harus mengubah fingerprint');

        $this->hapusRekursif($this->base . '/Lain');

        $setelahHapus = $this->manager->installedFingerprint();
        $this->assertSame($awal, $setelahHapus, 'Kembali ke state semula harus kembali ke fingerprint semula');
    }

    private function hapusRekursif(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->hapusRekursif($path) : @unlink($path);
        }

        @rmdir($dir);
    }
}
