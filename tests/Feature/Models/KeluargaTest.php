<?php

namespace Tests\Feature\Models;

use App\Models\Keluarga;
use App\Models\User;
use Database\Factories\KeluargaFactory;
use Database\Factories\PendudukFactory;
use Database\Factories\WilayahFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Keluarga.
 *
 * Menguji scope aktif (whereHas kepala keluarga HIDUP) dan relasi yang
 * dieksekusi (kepalaKeluarga, anggota, wilayah) memakai schema.sql via
 * RefreshDatabase. Assertion berbasis keanggotaan id.
 */
class KeluargaTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function scope_aktif_hanya_keluarga_dengan_kepala_hidup(): void
    {
        $kkAktif = KeluargaFactory::new()->create();
        PendudukFactory::new()->forKeluarga($kkAktif)->kepalaKeluarga()->hidup()->create();

        $kkMati = KeluargaFactory::new()->create();
        PendudukFactory::new()->forKeluarga($kkMati)->kepalaKeluarga()->mati()->create();

        $ids = Keluarga::aktif()->pluck('tweb_keluarga.id');

        $this->assertTrue($ids->contains($kkAktif->id));
        $this->assertFalse($ids->contains($kkMati->id));
    }

    #[Test]
    public function relasi_kepala_keluarga_mengembalikan_penduduk_kk_level_satu(): void
    {
        $kk     = KeluargaFactory::new()->create();
        $kepala = PendudukFactory::new()->forKeluarga($kk)->kepalaKeluarga()->hidup()->create();
        PendudukFactory::new()->forKeluarga($kk)->anak()->hidup()->create();

        $fresh = Keluarga::find($kk->id);

        $this->assertNotNull($fresh->kepalaKeluarga);
        $this->assertSame($kepala->id, $fresh->kepalaKeluarga->id);
    }

    #[Test]
    public function relasi_anggota_mengembalikan_semua_anggota_aktif(): void
    {
        $kk     = KeluargaFactory::new()->create();
        $kepala = PendudukFactory::new()->forKeluarga($kk)->kepalaKeluarga()->hidup()->create();
        $anak1  = PendudukFactory::new()->forKeluarga($kk)->anak()->hidup()->create();
        $anak2  = PendudukFactory::new()->forKeluarga($kk)->anak()->hidup()->create();

        $fresh    = Keluarga::find($kk->id);
        $anggotaIds = $fresh->anggota->pluck('id');

        $this->assertTrue($anggotaIds->contains($kepala->id));
        $this->assertTrue($anggotaIds->contains($anak1->id));
        $this->assertTrue($anggotaIds->contains($anak2->id));
    }

    #[Test]
    public function scope_status_dan_status_aktif_hanya_keluarga_dengan_kepala_aktif(): void
    {
        $kkAktif = KeluargaFactory::new()->create();
        PendudukFactory::new()->forKeluarga($kkAktif)->kepalaKeluarga()->hidup()->create();

        $kkMati = KeluargaFactory::new()->create();
        PendudukFactory::new()->forKeluarga($kkMati)->kepalaKeluarga()->mati()->create();

        $idsStatus = Keluarga::status()->pluck('tweb_keluarga.id');
        $this->assertTrue($idsStatus->contains($kkAktif->id));
        $this->assertFalse($idsStatus->contains($kkMati->id));

        $idsStatusAktif = Keluarga::statusAktif()->pluck('tweb_keluarga.id');
        $this->assertTrue($idsStatusAktif->contains($kkAktif->id));
        $this->assertFalse($idsStatusAktif->contains($kkMati->id));
    }

    #[Test]
    public function relasi_wilayah_mengembalikan_cluster_yang_benar(): void
    {
        $wilayah = WilayahFactory::new()->create();
        $kk      = KeluargaFactory::new()->forWilayah($wilayah)->create();

        $fresh = Keluarga::find($kk->id);

        $this->assertNotNull($fresh->wilayah);
        $this->assertSame($wilayah->id, $fresh->wilayah->id);
    }

    #[Test]
    public function factory_menyimpan_keluarga_ke_database(): void
    {
        $kk = KeluargaFactory::new()->create(['no_kk' => '3399000000009999']);

        $this->assertDatabaseHas('tweb_keluarga', [
            'id'    => $kk->id,
            'no_kk' => '3399000000009999',
        ]);
    }

    #[Test]
    public function validasi_data_keluarga_menolak_format_no_kk_salah(): void
    {
        // Cabang validasi format (ctype_digit / panjang) tidak memanggil set_session()/base_url()
        // selama no_kk tidak duplikat -> bisa dijalankan penuh.
        $data = ['alamat' => 'Jl. Uji', 'no_kk' => 'BUKANANGKA'];

        $hasil = Keluarga::validasi_data_keluarga($data);

        $this->assertFalse($hasil['status']);
        $this->assertStringContainsString('Nomor KK hanya berisi angka', $hasil['messages']);
    }

    #[Test]
    public function validasi_data_keluarga_no_kk_duplikat_jika_helper_tersedia(): void
    {
        // Cabang duplikat memanggil set_session() + base_url() (CI3) -> di-skip bila helper
        // belum dimuat, dan dieksekusi penuh bila tersedia.
        foreach (['set_session', 'base_url'] as $f) {
            if (! function_exists($f)) {
                $this->markTestSkipped("Helper CI3 {$f}() belum dimuat pada context ini (lihat HELPER_AUDIT.md).");
            }
        }

        $kk   = KeluargaFactory::new()->create(['no_kk' => '3312000000004567']);
        $data = ['alamat' => 'Jl. Uji', 'no_kk' => $kk->no_kk];

        $hasil = Keluarga::validasi_data_keluarga($data);

        $this->assertFalse($hasil['status']);
        $this->assertStringContainsString('sudah digunakan', $hasil['messages']);
    }
}
