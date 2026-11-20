<?php

namespace Tests\Feature\Models;

use App\Enums\StatusDasarEnum;
use App\Models\Rtm;
use App\Models\User;
use Database\Factories\PendudukFactory;
use Database\Factories\RtmFactory;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\Rtm (tabel tweb_rtm).
 */
class RtmTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');
    }

    #[Test]
    public function is_nomor_exist_mendeteksi_no_kk_yang_ada(): void
    {
        $rtm = RtmFactory::new()->create(['no_kk' => '3399000000000001']);

        $this->assertTrue(Rtm::isNomorExist('3399000000000001'));
        $this->assertFalse(Rtm::isNomorExist('0000000000000000'));
    }

    #[Test]
    public function scope_status_hanya_rtm_dengan_kepala_aktif(): void
    {
        $kepala = PendudukFactory::new()->create([
            'status_dasar' => StatusDasarEnum::HIDUP,
            'rtm_level'    => 1,
        ]);
        $rtm = RtmFactory::new()->kepala($kepala->id)->create();

        $ids = Rtm::status()->pluck('id');

        $this->assertTrue($ids->contains($rtm->id));
    }

    #[Test]
    public function relasi_kepala_keluarga_dieksekusi(): void
    {
        $kepala = PendudukFactory::new()->create(['rtm_level' => 1]);
        $rtm    = RtmFactory::new()->kepala($kepala->id)->create();

        $fresh = Rtm::find($rtm->id);

        $this->assertNotNull($fresh->kepalaKeluarga);
        $this->assertSame($kepala->id, $fresh->kepalaKeluarga->id);
    }

    #[Test]
    public function factory_menyimpan_rtm_ke_database(): void
    {
        $rtm = RtmFactory::new()->create(['no_kk' => '3312000000009999']);

        $this->assertDatabaseHas('tweb_rtm', [
            'id'    => $rtm->id,
            'no_kk' => '3312000000009999',
        ]);
    }
}
