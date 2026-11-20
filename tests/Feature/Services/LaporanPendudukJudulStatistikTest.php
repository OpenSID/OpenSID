<?php

namespace Tests\Feature\Services;

use App\Services\LaporanPenduduk;
use Database\Factories\BantuanFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk LaporanPenduduk::judulStatistik().
 *
 * Menguji resolusi judul program bantuan lewat skema slug (modern) dan
 * skema numerik "50<id>" (legacy, dipakai Statistik.php & Statistik_web.php),
 * serta fallback ke enum statistik penduduk/keluarga/rtm.
 */
class LaporanPendudukJudulStatistikTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function judul_statistik_ditemukan_lewat_slug(): void
    {
        $program = BantuanFactory::new()->create(['nama' => 'Bantuan Langsung Tunai']);

        $this->assertSame('Bantuan Langsung Tunai', LaporanPenduduk::judulStatistik($program->slug));
    }

    #[Test]
    public function judul_statistik_ditemukan_lewat_skema_legacy_50_id(): void
    {
        $program = BantuanFactory::new()->create(['nama' => 'Bantuan Sembako']);

        $this->assertSame('Bantuan Sembako', LaporanPenduduk::judulStatistik('50' . $program->id));
    }

    #[Test]
    public function judul_statistik_null_jika_id_legacy_tidak_ditemukan(): void
    {
        $this->assertNull(LaporanPenduduk::judulStatistik('50999999'));
    }

    #[Test]
    public function judul_statistik_ditemukan_lewat_enum_statistik_penduduk(): void
    {
        $this->assertNotNull(LaporanPenduduk::judulStatistik('0'));
    }

    #[Test]
    public function judul_statistik_null_untuk_kunci_yang_tidak_dikenal(): void
    {
        $this->assertNull(LaporanPenduduk::judulStatistik('tidak-dikenal'));
    }
}
