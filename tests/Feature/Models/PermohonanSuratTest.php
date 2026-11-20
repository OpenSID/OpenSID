<?php

namespace Tests\Feature\Models;

use App\Models\PermohonanSurat;
use App\Models\User;
use Database\Factories\PendudukFactory;
use Database\Factories\PermohonanSuratFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Feature test untuk App\Models\PermohonanSurat (tabel `permohonan_surat`).
 *
 * Regresi untuk issue #6672 (IDOR pada Surat::proses() di Layanan Mandiri):
 * scope `milikPenduduk` memastikan permohonan surat hanya bisa diakses/dibatalkan
 * oleh penduduk pemiliknya, bukan oleh penduduk lain yang login lalu mengiterasi ID.
 */
class PermohonanSuratTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::first(), 'admin');
    }

    #[Test]
    public function scope_milik_penduduk_mengembalikan_permohonan_milik_penduduk_tersebut(): void
    {
        $penduduk   = PendudukFactory::new()->create();
        $permohonan = PermohonanSuratFactory::new()->untukPemohon($penduduk->id)->create();

        $hasil = PermohonanSurat::where('id', $permohonan->id)
            ->milikPenduduk($penduduk->id)
            ->firstOrFail();

        $this->assertSame($permohonan->id, $hasil->id);
    }

    #[Test]
    public function scope_milik_penduduk_melempar_not_found_jika_permohonan_milik_penduduk_lain(): void
    {
        $pemilik   = PendudukFactory::new()->create();
        $penyerang = PendudukFactory::new()->create();

        $permohonan = PermohonanSuratFactory::new()->untukPemohon($pemilik->id)->create();

        $this->expectException(ModelNotFoundException::class);

        PermohonanSurat::where('id', $permohonan->id)
            ->milikPenduduk($penyerang->id)
            ->firstOrFail();
    }

    #[Test]
    public function scope_milik_penduduk_melempar_not_found_jika_id_permohonan_tidak_ada(): void
    {
        $penduduk = PendudukFactory::new()->create();

        $this->expectException(ModelNotFoundException::class);

        PermohonanSurat::where('id', 999999)
            ->milikPenduduk($penduduk->id)
            ->firstOrFail();
    }

    #[Test]
    public function iterasi_id_permohonan_milik_warga_lain_tidak_dapat_diakses_oleh_satu_penyerang(): void
    {
        $penyerang = PendudukFactory::new()->create();

        $korban       = [];
        $permohonanId = [];
        for ($i = 0; $i < 3; $i++) {
            $korban[$i]       = PendudukFactory::new()->create();
            $permohonanId[$i] = PermohonanSuratFactory::new()->untukPemohon($korban[$i]->id)->create()->id;
        }

        foreach ($permohonanId as $id) {
            try {
                PermohonanSurat::where('id', $id)
                    ->milikPenduduk($penyerang->id)
                    ->firstOrFail();

                $this->fail("Permohonan surat #{$id} milik warga lain seharusnya tidak dapat diakses oleh penyerang.");
            } catch (ModelNotFoundException $e) {
                $this->assertDatabaseHas('permohonan_surat', [
                    'id'     => $id,
                    'status' => PermohonanSurat::SEDANG_DIPERIKSA,
                ]);
            }
        }
    }
}
