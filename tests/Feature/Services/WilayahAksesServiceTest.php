<?php

namespace Tests\Feature\Services;

use App\Models\Wilayah;
use App\Services\WilayahAksesService;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Feature test untuk App\Services\WilayahAksesService::gisData().
 *
 * gisData() query tabel tweb_wil_clusterdesa sungguhan (Wilayah::dusun()/rw()/rt()),
 * jadi butuh RefreshDatabase. Baris wilayah dibuat lewat Wilayah::create() langsung
 * (bukan WilayahFactory) supaya tidak ikut memakai unique generator Faker milik
 * WilayahFactory yang dipakai bersama seluruh test suite. Pohon akses di-inject lewat
 * reflection supaya tidak perlu menyentuh helper CI3 ci_auth() (lihat
 * Tests\Unit\Services\WilayahAksesServiceTest).
 */
class WilayahAksesServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    private function serviceWithTree(Collection $tree): WilayahAksesService
    {
        $service = new WilayahAksesService();
        $prop    = (new ReflectionClass(WilayahAksesService::class))->getProperty('tree');
        $prop->setAccessible(true);
        $prop->setValue($service, $tree);

        return $service;
    }

    private function buatWilayah(string $dusun, string $rw, string $rt): Wilayah
    {
        return Wilayah::create(['config_id' => 1, 'dusun' => $dusun, 'rw' => $rw, 'rt' => $rt]);
    }

    #[Test]
    public function gis_data_hanya_berisi_dusun_rw_rt_yang_ada_di_tree(): void
    {
        $this->buatWilayah('DUSUN_GIS_A', '0', '0');
        $rwA = $this->buatWilayah('DUSUN_GIS_A', '1', '0');
        $rtA = $this->buatWilayah('DUSUN_GIS_A', '1', '1');

        $this->buatWilayah('DUSUN_GIS_B', '0', '0');
        $rwB = $this->buatWilayah('DUSUN_GIS_B', '2', '0');
        $rtB = $this->buatWilayah('DUSUN_GIS_B', '2', '5');

        // Tree hanya mengizinkan DUSUN_GIS_A, RW 1, RT 1 (persis bentuk Wilayah::treeAccess()).
        $tree = collect([
            'DUSUN_GIS_A' => collect([
                '1' => collect([$rtA]),
            ]),
        ]);

        $data = $this->serviceWithTree($tree)->gisData();

        $this->assertTrue(collect($data['dusun_gis'])->contains('dusun', 'DUSUN_GIS_A'));
        $this->assertFalse(collect($data['dusun_gis'])->contains('dusun', 'DUSUN_GIS_B'));

        $this->assertTrue(collect($data['rw_gis'])->contains('id', $rwA->id));
        $this->assertFalse(collect($data['rw_gis'])->contains('id', $rwB->id));

        $this->assertTrue(collect($data['rt_gis'])->contains('id', $rtA->id));
        $this->assertFalse(collect($data['rt_gis'])->contains('id', $rtB->id));
    }

    #[Test]
    public function gis_data_mengembalikan_semua_baris_saat_tree_tidak_dibatasi(): void
    {
        $dusun = $this->buatWilayah('DUSUN_GIS_BEBAS', '0', '0');
        $rw    = $this->buatWilayah('DUSUN_GIS_BEBAS', '1', '0');
        $rt    = $this->buatWilayah('DUSUN_GIS_BEBAS', '1', '1');

        // Tree tanpa batasan = hasil Wilayah::tree() (semua dusun/rw/rt masuk).
        $tree = Wilayah::tree();

        $data = $this->serviceWithTree($tree)->gisData();

        $this->assertTrue(collect($data['dusun_gis'])->contains('id', $dusun->id));
        $this->assertTrue(collect($data['rw_gis'])->contains('id', $rw->id));
        $this->assertTrue(collect($data['rt_gis'])->contains('id', $rt->id));
    }
}
