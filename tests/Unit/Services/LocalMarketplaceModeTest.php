<?php

namespace Tests\Unit\Services;

use Modules\Pelanggan\Services\Dev\LocalMarketplace;
use Tests\BaseTestCase;

/**
 * Mengunci kontrak mode tristate LocalMarketplace: mode(), aktif(), setel().
 * Backward-compat dengan nilai `.mode` lama ('1'/'0') juga diuji.
 */
class LocalMarketplaceModeTest extends BaseTestCase
{
    private string $modeFile;
    private string|false $isiAwal;

    protected function setUp(): void
    {
        parent::setUp();

        $dir            = storage_path('app/dev-marketplace');
        $this->modeFile = $dir . '/.mode';

        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $this->isiAwal = is_file($this->modeFile)
            ? file_get_contents($this->modeFile)
            : false;
    }

    protected function tearDown(): void
    {
        if ($this->isiAwal === false) {
            @unlink($this->modeFile);
        } else {
            file_put_contents($this->modeFile, $this->isiAwal);
        }

        parent::tearDown();
    }

    private function tulisModeFile(string $isi): void
    {
        file_put_contents($this->modeFile, $isi);
    }

    // -----------------------------------------------------------------------
    // Konstanta
    // -----------------------------------------------------------------------

    public function test_konstanta_mode_bernilai_benar(): void
    {
        $this->assertSame('lokal', LocalMarketplace::MODE_LOKAL);
        $this->assertSame('staging', LocalMarketplace::MODE_STAGING);
        $this->assertSame('produksi', LocalMarketplace::MODE_PRODUKSI);
    }

    // -----------------------------------------------------------------------
    // mode() — nilai baru
    // -----------------------------------------------------------------------

    public function test_mode_kembalikan_lokal_bila_file_berisi_lokal(): void
    {
        $this->tulisModeFile('lokal');

        $this->assertSame(LocalMarketplace::MODE_LOKAL, LocalMarketplace::mode());
    }

    public function test_mode_kembalikan_staging_bila_file_berisi_staging(): void
    {
        $this->tulisModeFile('staging');

        $this->assertSame(LocalMarketplace::MODE_STAGING, LocalMarketplace::mode());
    }

    public function test_mode_kembalikan_produksi_bila_file_berisi_produksi(): void
    {
        $this->tulisModeFile('produksi');

        $this->assertSame(LocalMarketplace::MODE_PRODUKSI, LocalMarketplace::mode());
    }

    // -----------------------------------------------------------------------
    // mode() — backward compat nilai lama ('1'/'0')
    // -----------------------------------------------------------------------

    public function test_mode_compat_satu_berarti_lokal(): void
    {
        $this->tulisModeFile('1');

        $this->assertSame(LocalMarketplace::MODE_LOKAL, LocalMarketplace::mode());
    }

    public function test_mode_compat_nol_berarti_produksi(): void
    {
        $this->tulisModeFile('0');

        $this->assertSame(LocalMarketplace::MODE_PRODUKSI, LocalMarketplace::mode());
    }

    public function test_mode_nilai_tidak_dikenal_di_file_fallback_produksi(): void
    {
        $this->tulisModeFile('entah-apa');

        $this->assertSame(LocalMarketplace::MODE_PRODUKSI, LocalMarketplace::mode());
    }

    // -----------------------------------------------------------------------
    // aktif() — hanya true untuk mode lokal
    // -----------------------------------------------------------------------

    public function test_aktif_true_saat_mode_lokal(): void
    {
        $this->tulisModeFile('lokal');

        $this->assertTrue(LocalMarketplace::aktif());
    }

    public function test_aktif_false_saat_mode_staging(): void
    {
        $this->tulisModeFile('staging');

        $this->assertFalse(LocalMarketplace::aktif());
    }

    public function test_aktif_false_saat_mode_produksi(): void
    {
        $this->tulisModeFile('produksi');

        $this->assertFalse(LocalMarketplace::aktif());
    }

    public function test_aktif_compat_satu_true(): void
    {
        $this->tulisModeFile('1');

        $this->assertTrue(LocalMarketplace::aktif());
    }

    public function test_aktif_compat_nol_false(): void
    {
        $this->tulisModeFile('0');

        $this->assertFalse(LocalMarketplace::aktif());
    }

    // -----------------------------------------------------------------------
    // setel()
    // -----------------------------------------------------------------------

    public function test_setel_lokal_tulis_lokal_ke_file(): void
    {
        LocalMarketplace::setel(LocalMarketplace::MODE_LOKAL);

        $this->assertSame('lokal', trim((string) file_get_contents($this->modeFile)));
    }

    public function test_setel_staging_tulis_staging_ke_file(): void
    {
        LocalMarketplace::setel(LocalMarketplace::MODE_STAGING);

        $this->assertSame('staging', trim((string) file_get_contents($this->modeFile)));
    }

    public function test_setel_produksi_tulis_produksi_ke_file(): void
    {
        LocalMarketplace::setel(LocalMarketplace::MODE_PRODUKSI);

        $this->assertSame('produksi', trim((string) file_get_contents($this->modeFile)));
    }

    public function test_setel_nilai_tidak_dikenal_fallback_ke_produksi(): void
    {
        LocalMarketplace::setel('tidak-valid');

        $this->assertSame('produksi', trim((string) file_get_contents($this->modeFile)));
    }

    // -----------------------------------------------------------------------
    // setel → mode roundtrip
    // -----------------------------------------------------------------------

    public function test_setel_lalu_mode_konsisten_untuk_semua_mode(): void
    {
        foreach ([LocalMarketplace::MODE_LOKAL, LocalMarketplace::MODE_STAGING, LocalMarketplace::MODE_PRODUKSI] as $m) {
            LocalMarketplace::setel($m);
            $this->assertSame($m, LocalMarketplace::mode(), "setel({$m}) → mode() harus kembalikan {$m}");
        }
    }

    public function test_setel_staging_aktif_tetap_false(): void
    {
        LocalMarketplace::setel(LocalMarketplace::MODE_STAGING);

        $this->assertFalse(LocalMarketplace::aktif(), 'aktif() harus false di mode staging — token premium tidak di-bypass');
        $this->assertSame(LocalMarketplace::MODE_STAGING, LocalMarketplace::mode());
    }
}
