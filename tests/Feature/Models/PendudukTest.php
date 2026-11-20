<?php

namespace Tests\Feature\Models;

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusDasarEnum;
use App\Models\Penduduk;
use App\Models\User;
use Database\Factories\KeluargaFactory;
use Database\Factories\PendudukFactory;
use Database\Factories\WilayahFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Penduduk.
 *
 * Menguji local scope (status/status_dasar/kk_level) dan relasi yang dieksekusi.
 * Schema + seed dimuat via RefreshDatabase (schema.sql), sehingga assertion
 * dibuat berbasis keanggotaan (contains id yang dibuat) bukan hitungan absolut.
 */
class PendudukTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // AuthorObserver mengisi created_by dari auth('admin') saat creating.
        // Pakai user yang TIDAK dibatasi wilayah (batasi_wilayah = 0) agar
        // AccessWilayahScope tidak memfilter data yang baru dibuat di test.
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function scope_status_memfilter_berdasarkan_status_dasar(): void
    {
        $hidup = PendudukFactory::new()->hidup()->create();
        $mati  = PendudukFactory::new()->mati()->create();

        $idsHidup = Penduduk::status(StatusDasarEnum::HIDUP)->pluck('id');

        $this->assertTrue($idsHidup->contains($hidup->id));
        $this->assertFalse($idsHidup->contains($mati->id));
    }

    #[Test]
    public function scope_status_dasar_dengan_whereIn(): void
    {
        $hidup = PendudukFactory::new()->hidup()->create();
        $mati  = PendudukFactory::new()->mati()->create();

        $hanyaMati = Penduduk::statusDasar([StatusDasarEnum::MATI])->pluck('id');
        $this->assertTrue($hanyaMati->contains($mati->id));
        $this->assertFalse($hanyaMati->contains($hidup->id));

        $keduanya = Penduduk::statusDasar([StatusDasarEnum::HIDUP, StatusDasarEnum::MATI])->pluck('id');
        $this->assertTrue($keduanya->contains($hidup->id));
        $this->assertTrue($keduanya->contains($mati->id));
    }

    #[Test]
    public function scope_hidup_memfilter_status_dasar(): void
    {
        $hidup = PendudukFactory::new()->hidup()->create();
        $mati  = PendudukFactory::new()->mati()->create();

        $ids = Penduduk::hidup(StatusDasarEnum::HIDUP)->pluck('id');
        $this->assertTrue($ids->contains($hidup->id));
        $this->assertFalse($ids->contains($mati->id));
    }

    #[Test]
    public function scope_status_penduduk_memfilter_kolom_status(): void
    {
        $aktif = PendudukFactory::new()->aktif()->create();
        $nonAktif = PendudukFactory::new()->tidakAktif()->create();

        $ids = Penduduk::statusPenduduk(1)->pluck('id');
        $this->assertTrue($ids->contains($aktif->id));
        $this->assertFalse($ids->contains($nonAktif->id));
    }

    #[Test]
    public function scope_kepala_keluarga_hanya_kk_level_satu(): void
    {
        $keluarga = KeluargaFactory::new()->create();
        $kepala   = PendudukFactory::new()->forKeluarga($keluarga)->kepalaKeluarga()->create();
        $anak     = PendudukFactory::new()->forKeluarga($keluarga)->anak()->create();

        $ids = Penduduk::kepalaKeluarga()->where('id_kk', $keluarga->id)->pluck('id');

        $this->assertTrue($ids->contains($kepala->id));
        $this->assertFalse($ids->contains($anak->id));
    }

    #[Test]
    public function scope_ayah_dan_ibu_mengembalikan_orang_tua_dalam_keluarga(): void
    {
        $keluarga = KeluargaFactory::new()->create();
        $ayah = PendudukFactory::new()->forKeluarga($keluarga)->kepalaKeluarga()->lakiLaki()->create();
        $ibu  = PendudukFactory::new()->forKeluarga($keluarga)->istri()->create();
        PendudukFactory::new()->forKeluarga($keluarga)->anak()->create();

        $this->assertSame($ayah->id, Penduduk::ayah($keluarga->id)->first()->id);
        $this->assertSame($ibu->id, Penduduk::ibu($keluarga->id)->first()->id);
    }

    #[Test]
    public function relasi_keluarga_dieksekusi_mengembalikan_keluarga_yang_benar(): void
    {
        $keluarga = KeluargaFactory::new()->create();
        $penduduk = PendudukFactory::new()->forKeluarga($keluarga)->create();

        $fresh = Penduduk::find($penduduk->id);

        $this->assertInstanceOf(BelongsTo::class, $fresh->keluarga());
        $this->assertSame($keluarga->id, $fresh->keluarga->id);
    }

    #[Test]
    public function relasi_wilayah_dieksekusi_mengembalikan_cluster_yang_benar(): void
    {
        $wilayah  = WilayahFactory::new()->create();
        $penduduk = PendudukFactory::new()->forWilayah($wilayah)->create();

        $fresh = Penduduk::find($penduduk->id);

        $this->assertNotNull($fresh->wilayah);
        $this->assertSame($wilayah->id, $fresh->wilayah->id);
    }

    #[Test]
    public function factory_membuat_baris_tersimpan_di_database(): void
    {
        $penduduk = PendudukFactory::new()->create(['nama' => 'Budi Pengujian Unik']);

        $this->assertDatabaseHas('tweb_penduduk', [
            'id'   => $penduduk->id,
            'nama' => 'Budi Pengujian Unik',
        ]);
        // created_by terisi dari AuthorObserver (auth admin), bukan null.
        $this->assertNotNull($penduduk->fresh()->created_by);
    }

    // ===================== SCOPE & METHOD DB (tanpa helper CI3) =====================

    #[Test]
    public function scope_urut_dan_order_keluarga_dapat_dieksekusi(): void
    {
        PendudukFactory::new()->create();

        $this->assertInstanceOf(Collection::class, Penduduk::urut()->limit(3)->get());
        $this->assertInstanceOf(Collection::class, Penduduk::orderKeluarga()->limit(3)->get());
    }

    #[Test]
    public function scope_hubung_warga_mengembalikan_penduduk_dengan_kontak(): void
    {
        $dgnTelepon = PendudukFactory::new()->create(['telepon' => '08123456789']);

        $ids = Penduduk::hubungWarga()->get()->pluck('id');

        $this->assertTrue($ids->contains($dgnTelepon->id));
    }

    #[Test]
    public function scope_filters_memfilter_berdasarkan_sex(): void
    {
        $laki      = PendudukFactory::new()->lakiLaki()->create();
        $perempuan = PendudukFactory::new()->perempuan()->create();

        $ids = Penduduk::filters(['sex' => JenisKelaminEnum::LAKI_LAKI])->pluck('id');

        $this->assertTrue($ids->contains($laki->id));
        $this->assertFalse($ids->contains($perempuan->id));
    }

    #[Test]
    public function scope_filter_log_default_dan_filter_tahun_dapat_dieksekusi(): void
    {
        // cabang default (tanpa filter) dan cabang whereHas (tahun) keduanya dieksekusi.
        $this->assertInstanceOf(Collection::class, Penduduk::filterLog(['tahun' => null, 'bulan' => null])->limit(1)->get());
        $this->assertInstanceOf(Collection::class, Penduduk::filterLog(['tahun' => 2024, 'bulan' => null])->limit(1)->get());
    }

    #[Test]
    public function scope_dpt_dan_wajib_ktp_dan_batasi_umur_dapat_dieksekusi(): void
    {
        $this->assertInstanceOf(Collection::class, Penduduk::dpt('01-01-2024')->limit(1)->get());
        $this->assertInstanceOf(Collection::class, Penduduk::wajibKtp()->limit(1)->get());
        $this->assertInstanceOf(
            Collection::class,
            Penduduk::batasiUmur('01-01-2024', ['min' => 17, 'max' => 99, 'satuan' => 'tahun'])->limit(1)->get()
        );
    }

    #[Test]
    public function scope_dusun_memfilter_penduduk_berdasarkan_dusun(): void
    {
        $wilayah  = WilayahFactory::new()->byWilayah('DUSUN UJI COVERAGE', '1', '1')->create();
        $penduduk = PendudukFactory::new()->forWilayah($wilayah)->create();

        $ids = Penduduk::dusun('DUSUN UJI COVERAGE')->pluck('id');
        $this->assertTrue($ids->contains($penduduk->id));

        // cabang $dusun kosong -> query tidak difilter (smoke).
        $this->assertInstanceOf(Collection::class, Penduduk::dusun(null)->limit(1)->get());
    }

    #[Test]
    public function accessor_jml_anak_menghitung_jumlah_anak_dalam_kk(): void
    {
        $keluarga = KeluargaFactory::new()->create();
        $kepala   = PendudukFactory::new()->forKeluarga($keluarga)->kepalaKeluarga()->create();
        PendudukFactory::new()->forKeluarga($keluarga)->anak()->create();
        PendudukFactory::new()->forKeluarga($keluarga)->anak()->create();

        $this->assertEquals(2, $kepala->jml_anak);
    }

    #[Test]
    public function accessor_lokasi_dapat_diakses_tanpa_error(): void
    {
        $penduduk = PendudukFactory::new()->create();
        $fresh    = Penduduk::find($penduduk->id);

        $lokasi = $fresh->lokasi;

        $this->assertTrue($lokasi === null || $lokasi instanceof \App\Models\PendudukMap);
    }

    #[Test]
    public function nik_sementara_menghasilkan_nik_diawali_nol(): void
    {
        $nik = Penduduk::nikSementara();

        $this->assertIsString($nik);
        $this->assertStringStartsWith('0', $nik);
    }

    #[Test]
    public function orang_tua_mengembalikan_ayah_dan_ibu(): void
    {
        $keluarga = KeluargaFactory::new()->create();
        $ayah     = PendudukFactory::new()->forKeluarga($keluarga)->kepalaKeluarga()->lakiLaki()->create(['nik' => '3300000000000011']);
        $ibu      = PendudukFactory::new()->forKeluarga($keluarga)->istri()->create(['nik' => '3300000000000012']);

        $hasil = Penduduk::orangTua($keluarga->id);

        $this->assertArrayHasKey('ayah', $hasil);
        $this->assertArrayHasKey('ibu', $hasil);
        $this->assertSame($ayah->nik, $hasil['ayah']->nik);
        $this->assertSame($ibu->nik, $hasil['ibu']->nik);
    }

    #[Test]
    public function awal_bulan_membangun_query_yang_dapat_dieksekusi(): void
    {
        $this->assertInstanceOf(Collection::class, Penduduk::awalBulan(2024, 6)->limit(1)->get());
    }

    // ===================== METHOD CI3-DEPENDENT (jalan bila helper dimuat) =====================
    // Test ditulis lengkap; otomatis di-skip jika helper CI3 belum dimuat pada context ini,
    // dan benar-benar dieksekusi bila dijalankan dalam konteks yang memuat helper CI3.

    private function skipKecualiHelper(array $fungsi = [], array $konstanta = []): void
    {
        foreach ($fungsi as $f) {
            if (! function_exists($f)) {
                $this->markTestSkipped("Helper CI3 {$f}() belum dimuat pada context ini (lihat HELPER_AUDIT.md). Test berjalan saat helper tersedia.");
            }
        }
        foreach ($konstanta as $c) {
            if (! defined($c)) {
                $this->markTestSkipped("Konstanta CI3 {$c} belum terdefinisi pada context ini (lihat HELPER_AUDIT.md).");
            }
        }
    }

    #[Test]
    public function validasi_membersihkan_data_dan_mengonversi_tanggal_jika_helper_tersedia(): void
    {
        $this->skipKecualiHelper(['tgl_indo_in', 'nama', 'email', 'bilangan', 'nama_terbatas', 'nomor_surat_keputusan']);

        $data = $this->dataValidasi(['nik' => '0', 'nama' => 'Siti Pengujian', 'tanggallahir' => '01-01-2000']);

        $hasil = Penduduk::validasi($data);

        $this->assertIsArray($hasil);
        $this->assertArrayHasKey('status', $hasil);
        $this->assertTrue($hasil['status']);
        // tgl_indo_in mengubah dd-mm-yyyy menjadi yyyy-mm-dd
        $this->assertSame('2000-01-01', $data['tanggallahir']);
    }

    #[Test]
    public function delete_menghapus_penduduk_jika_helper_ci3_tersedia(): void
    {
        $this->skipKecualiHelper(['ci_auth'], ['LOKASI_USER_PICT']);

        // delete() menulis deleted_by = ci_auth()->id. Tanpa sesi login CI3 (mis. saat
        // dijalankan lewat `php artisan test`), ci_auth() mengembalikan null sehingga
        // method tidak bisa dieksekusi -> skip. Akan berjalan bila ada sesi login CI3.
        if (ci_auth()?->id === null) {
            $this->markTestSkipped('delete() butuh sesi login CI3 (ci_auth()->id); tidak tersedia di harness test - lihat HELPER_AUDIT.md');
        }

        $penduduk = PendudukFactory::new()->create(['foto' => null]);
        $id       = $penduduk->id;

        $penduduk->delete();

        $this->assertNull(Penduduk::find($id));
    }

    /**
     * Bangun array data lengkap untuk Penduduk::validasi() dengan default aman.
     */
    private function dataValidasi(array $override = []): array
    {
        return array_merge([
            'nik'                  => '3201234567890123',
            'nama'                 => 'Warga Uji',
            'tanggallahir'         => '01-01-1990',
            'tempatlahir'          => 'Bandung',
            'sex'                  => 1,
            'tanggal_akhir_paspor' => '',
            'tanggalperkawinan'    => '',
            'tanggalperceraian'    => '',
            'tanggal_cetak_ktp'    => '',
            'pendidikan_kk_id'     => null,
            'pendidikan_sedang_id' => null,
            'pekerjaan_id'         => null,
            'status_kawin'         => 1,
            'id_asuransi'          => null,
            'hamil'                => null,
            'ktp_el'               => null,
            'tag_id_card'          => null,
            'status_rekam'         => null,
            'berat_lahir'          => null,
            'tempat_dilahirkan'    => null,
            'jenis_kelahiran'      => null,
            'penolong_kelahiran'   => null,
            'panjang_lahir'        => null,
            'cacat_id'             => null,
            'sakit_menahun_id'     => null,
            'ket'                  => '',
            'no_asuransi'          => null,
            'warganegara_id'       => 1,
            'cara_kb_id'           => null,
            'jenis_peristiwa'      => 2,
            'kelahiran_anak_ke'    => null,
            'dokumen_kitas'        => null,
            'kk_level'             => 1,
            'no_kk_sebelumnya'     => '',
            'akta_lahir'           => '',
            'dokumen_pasport'      => '',
            'nama_ayah'            => 'Ayah Uji',
            'nama_ibu'             => 'Ibu Uji',
            'alamat_sebelumnya'    => '',
            'alamat_sekarang'      => 'Jl. Uji',
            'akta_perkawinan'      => '',
            'akta_perceraian'      => '',
            'bpjs_ketenagakerjaan' => '',
            'suku'                 => '',
            'marga'                => '',
            'adat'                 => '',
            'pekerja_migran'       => '',
            'telepon'              => '',
            'email'                => '',
            'telegram'             => '',
            'status_asuransi'      => '',
            'ayah_nik'             => '0',
            'ibu_nik'              => '0',
        ], $override);
    }
}
