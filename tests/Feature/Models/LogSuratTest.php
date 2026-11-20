<?php

namespace Tests\Feature\Models;

use App\Models\LogSurat;
use Database\Factories\LogSuratFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\LogSurat (tabel `log_surat`).
 *
 * Menguji scope status, scope ditolak, dan accessor keterangan yang dibaca dari
 * kolom input ter-persist. Schema dimuat via RefreshDatabase. Assertion berbasis
 * keanggotaan id.
 */
class LogSuratTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_status_memfilter_kolom_status(): void
    {
        $aktif    = LogSuratFactory::new()->aktif()->create();
        $nonAktif = LogSuratFactory::new()->nonAktif()->create();

        $ids = LogSurat::status(1)->pluck('id');

        $this->assertTrue($ids->contains($aktif->id));
        $this->assertFalse($ids->contains($nonAktif->id));
    }

    #[Test]
    public function scope_ditolak_hanya_verifikasi_operator_minus_satu(): void
    {
        $ditolak = LogSuratFactory::new()->ditolak()->create();
        $normal  = LogSuratFactory::new()->create();

        $ids = LogSurat::ditolak()->pluck('id');

        $this->assertTrue($ids->contains($ditolak->id));
        $this->assertFalse($ids->contains($normal->id));
    }

    #[Test]
    public function accessor_keterangan_dibaca_dari_input_terpersist(): void
    {
        $log = LogSuratFactory::new()->withInput(['keperluan' => 'Permohonan SKTM'])->create();

        $fresh = LogSurat::find($log->id);

        $this->assertSame('Permohonan SKTM', $fresh->keterangan);
    }

    #[Test]
    public function factory_menyimpan_log_surat_ke_database(): void
    {
        $log = LogSuratFactory::new()->create(['no_surat' => 'TEST-LS-0001']);

        $this->assertDatabaseHas('log_surat', [
            'id'       => $log->id,
            'no_surat' => 'TEST-LS-0001',
        ]);
    }

    #[Test]
    public function scope_arsip_fisik_layanan_surat_dapat_dieksekusi(): void
    {
        LogSuratFactory::new()->create(['verifikasi_operator' => 1]);

        $hasil = LogSurat::arsipFisikLayananSurat()->limit(5)->get();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $hasil);
    }
}
