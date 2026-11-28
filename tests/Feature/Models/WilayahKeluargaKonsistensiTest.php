<?php

namespace Tests\Feature\Models;

use App\Models\Keluarga;
use App\Models\User;
use App\Models\Wilayah;
use Database\Factories\KeluargaFactory;
use Database\Factories\PendudukFactory;
use Database\Factories\WilayahFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi untuk bug: total KK di Wilayah Administratif tidak sama dengan
 * total KK di menu Keluarga untuk filter wilayah yang sama.
 *
 * Penyebab: Wilayah::keluargaAktif() berbasis view keluarga_aktif (join via
 * nik_kepala yang bisa basi/tidak sinkron), sedangkan menu Keluarga memakai
 * relasi kepalaKeluarga (via id_kk + kk_level kepala + status_dasar hidup).
 * Keduanya kini disamakan pada definisi yang sama dan filter wilayah menu
 * Keluarga langsung memakai tweb_keluarga.id_cluster.
 */
class WilayahKeluargaKonsistensiTest extends BaseTestCase
{
    use RefreshDatabase;

    private const DUSUN = 'DUSUN_KONSISTENSI_KK';

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
        $this->resetRelationHash();
    }

    /**
     * Alias laravel_reserved_N pada withCount dihitung dari static counter
     * Laravel yang tidak di-reset antar kueri dalam satu proses, sehingga
     * raw query pengendali (Wilayah::datatables dsb.) mengasumsikan hitungan
     * dimulai dari 0. Reset di sini agar alias pada test deterministik.
     */
    private function resetRelationHash(): void
    {
        $prop = new \ReflectionProperty(Relation::class, 'selfJoinCount');
        $prop->setAccessible(true);
        $prop->setValue(null, 0);
    }

    /** @return array{dusun: Wilayah, rw: Wilayah, rt1: Wilayah, rt2: Wilayah} */
    private function buatWilayah(): array
    {
        return [
            'dusun' => WilayahFactory::new()->byWilayah(self::DUSUN, '0', '0')->create(),
            'rw'    => WilayahFactory::new()->byWilayah(self::DUSUN, '1', '0')->create(),
            'rt1'   => WilayahFactory::new()->byWilayah(self::DUSUN, '1', '1')->create(),
            'rt2'   => WilayahFactory::new()->byWilayah(self::DUSUN, '1', '2')->create(),
        ];
    }

    /**
     * A: aktif normal (nik_kepala sinkron). B: aktif dengan nik_kepala basi
     * (null) tetapi punya kepala hidup via id_kk — sebelum perbaikan, baris
     * ini dihitung menu Keluarga tetapi tidak dihitung Wilayah. C: aktif
     * normal di RT lain. D: KK kosong. E: kepala tidak hidup.
     */
    private function buatKeluargaCampuran(array $w): void
    {
        $kkA     = KeluargaFactory::new()->forWilayah($w['rt1'])->create();
        $kepalaA = PendudukFactory::new()->forKeluarga($kkA)->kepalaKeluarga()->hidup()->create();
        $kkA->update(['nik_kepala' => $kepalaA->id]);

        $kkB = KeluargaFactory::new()->forWilayah($w['rt1'])->create(['nik_kepala' => null]);
        PendudukFactory::new()->forKeluarga($kkB)->kepalaKeluarga()->hidup()->create();

        $kkC     = KeluargaFactory::new()->forWilayah($w['rt2'])->create();
        $kepalaC = PendudukFactory::new()->forKeluarga($kkC)->kepalaKeluarga()->hidup()->create();
        $kkC->update(['nik_kepala' => $kepalaC->id]);

        KeluargaFactory::new()->forWilayah($w['rt1'])->create(['nik_kepala' => null]);

        $kkE     = KeluargaFactory::new()->forWilayah($w['rt1'])->create();
        $kepalaE = PendudukFactory::new()->forKeluarga($kkE)->kepalaKeluarga()->mati()->create();
        $kkE->update(['nik_kepala' => $kepalaE->id]);
    }

    #[Test]
    public function relasi_keluarga_aktif_berbasis_tabel_keluarga(): void
    {
        $relasi = (new Wilayah())->keluargaAktif();

        $this->assertInstanceOf(HasManyThrough::class, $relasi);
        $this->assertInstanceOf(Keluarga::class, $relasi->getRelated());
    }

    #[Test]
    public function hitungan_kk_aktif_dusun_sama_dengan_menu_keluarga(): void
    {
        $w = $this->buatWilayah();
        $this->buatKeluargaCampuran($w);

        $idClusterDusun = Wilayah::whereDusun(self::DUSUN)->pluck('id')->toArray();

        // Kueri setara menu Keluarga (status aktif + filter dusun).
        $menuCount = Keluarga::aktif()->whereIn('tweb_keluarga.id_cluster', $idClusterDusun)->count();

        // Hitungan kolom JUMLAH KK di tabel dusun Wilayah Administratif.
        $wilayahCount = (int) Wilayah::dusun()->where('dusun', self::DUSUN)->withCount('keluargaAktif')->first()->keluarga_aktif_count;

        $this->assertSame(3, $menuCount);
        $this->assertSame($menuCount, $wilayahCount);
    }

    #[Test]
    public function hitungan_kk_aktif_per_rw_sama_dengan_menu_keluarga(): void
    {
        $w = $this->buatWilayah();
        $this->buatKeluargaCampuran($w);

        // Bentuk withCount persis seperti Wilayah::datatables() level rw
        // (rts memakai alias 0, keluargaAktif memakai alias 1).
        $rows = Wilayah::rw()->whereDusun(self::DUSUN)
            ->withCount([
                'rts'           => static fn ($q) => $q->whereRaw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw'),
                'keluargaAktif' => static fn ($q) => $q->whereRaw('laravel_reserved_1.rw = tweb_wil_clusterdesa.rw'),
            ])
            ->get();

        foreach ($rows as $row) {
            $idClusterRw = Wilayah::whereDusun($row->dusun)->whereRw($row->rw)->pluck('id')->toArray();
            $menuCount   = Keluarga::aktif()->whereIn('tweb_keluarga.id_cluster', $idClusterRw)->count();

            $this->assertSame($menuCount, (int) $row->keluarga_aktif_count, "RW {$row->rw} tidak konsisten");
        }

        $this->assertSame(3, (int) $rows->firstWhere('rw', '1')->keluarga_aktif_count);
    }

    #[Test]
    public function hitungan_kk_aktif_per_rt_sama_dengan_menu_keluarga(): void
    {
        $w = $this->buatWilayah();
        $this->buatKeluargaCampuran($w);

        // Bentuk withCount persis seperti Wilayah::datatables() level rt.
        $rows = Wilayah::rt()->whereDusun(self::DUSUN)->whereRw('1')->where('rt', '!=', '-')
            ->withCount([
                'keluargaAktif' => static fn ($q) => $q->whereRaw('laravel_reserved_0.rw = tweb_wil_clusterdesa.rw and laravel_reserved_0.rt = tweb_wil_clusterdesa.rt'),
            ])
            ->get();

        foreach ($rows as $row) {
            $rtId      = Wilayah::whereDusun($row->dusun)->whereRw($row->rw)->whereRt($row->rt)->first()->id;
            $menuCount = Keluarga::aktif()->whereIn('tweb_keluarga.id_cluster', [$rtId])->count();

            $this->assertSame($menuCount, (int) $row->keluarga_aktif_count, "RT {$row->rt} tidak konsisten");
        }

        $this->assertSame(2, (int) $rows->firstWhere('rt', '1')->keluarga_aktif_count);
        $this->assertSame(1, (int) $rows->firstWhere('rt', '2')->keluarga_aktif_count);
    }

    #[Test]
    public function kk_kosong_tetap_tercakup_filter_wilayah_langsung(): void
    {
        $w = $this->buatWilayah();

        $kkKosong = KeluargaFactory::new()->forWilayah($w['rt1'])->create(['nik_kepala' => null]);

        // Filter whereHas('kepalaKeluarga.keluarga') yang lama mengecualikan KK
        // kosong dari hasil berfilter wilayah; whereIn langsung tidak.
        $ids = Keluarga::whereIn('tweb_keluarga.id_cluster', [$w['rt1']->id])->pluck('tweb_keluarga.id');

        $this->assertTrue($ids->contains($kkKosong->id));
    }
}
