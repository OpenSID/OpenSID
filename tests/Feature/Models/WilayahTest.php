<?php

namespace Tests\Feature\Models;

use App\Models\Wilayah;
use Database\Factories\WilayahFactory;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Wilayah (tabel tweb_wil_clusterdesa).
 */
class WilayahTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_dusun_rw_rt_memfilter_level_wilayah(): void
    {
        $dusun = WilayahFactory::new()->byWilayah('DUSUN_W_UJI', '0', '0')->create();
        $rw    = WilayahFactory::new()->byWilayah('DUSUN_W_UJI', '3', '0')->create();
        $rt    = WilayahFactory::new()->byWilayah('DUSUN_W_UJI', '3', '7')->create();

        $dusunIds = Wilayah::dusun()->where('dusun', 'DUSUN_W_UJI')->pluck('id');
        $this->assertTrue($dusunIds->contains($dusun->id));
        $this->assertFalse($dusunIds->contains($rw->id));
        $this->assertFalse($dusunIds->contains($rt->id));

        $rwIds = Wilayah::rw('DUSUN_W_UJI')->pluck('id');
        $this->assertTrue($rwIds->contains($rw->id));
        $this->assertFalse($rwIds->contains($dusun->id));

        $rtIds = Wilayah::rt('DUSUN_W_UJI', '3')->pluck('id');
        $this->assertTrue($rtIds->contains($rt->id));
        $this->assertFalse($rtIds->contains($dusun->id));
    }

    #[Test]
    public function tree_mengembalikan_collection(): void
    {
        $this->assertInstanceOf(Collection::class, Wilayah::tree());
    }

    #[Test]
    public function factory_menyimpan_wilayah_ke_database(): void
    {
        $w = WilayahFactory::new()->byWilayah('DUSUN_FACTORY_UJI', '1', '1')->create();

        $this->assertDatabaseHas('tweb_wil_clusterdesa', [
            'id'    => $w->id,
            'dusun' => 'DUSUN_FACTORY_UJI',
        ]);
    }
}
