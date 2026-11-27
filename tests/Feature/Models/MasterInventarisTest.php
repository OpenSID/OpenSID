<?php

namespace Tests\Feature\Models;

use App\Models\MasterInventaris;
use Tests\Traits\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

/**
 * Feature test untuk App\Models\MasterInventaris.
 *
 * `master_inventaris` adalah VIEW (union dari inventaris_asset/tanah/dsb) sehingga
 * TIDAK bisa di-insert via factory (rule 3: kasus genuinely tanpa insert Eloquent).
 * Model ini hanya berisi scope DB (minTahun, permen47) tanpa accessor/relasi, jadi
 * test berupa smoke-eksekusi scope terhadap data seed. Bersih dari helper CI3.
 */
class MasterInventarisTest extends BaseTestCase
{
    use RefreshDatabase;

    #[Test]
    public function scope_min_tahun_dapat_dieksekusi(): void
    {
        // Mengembalikan nilai agregat MIN(tahun_pengadaan): numeric bila ada data, null bila kosong.
        $min = MasterInventaris::minTahun();

        $this->assertTrue($min === null || is_numeric($min));
    }

    #[Test]
    public function scope_permen47_mengembalikan_array(): void
    {
        $this->assertIsArray(MasterInventaris::permen47(2024, null));
        // dengan filter jenis asset
        $this->assertIsArray(MasterInventaris::permen47(2024, 'inventaris_asset'));
    }
}
