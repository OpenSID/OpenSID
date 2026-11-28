<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\BaseTestCase;

class PeriksaKeuanganTemplateTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once base_path('tests/Support/ci_periksa_stubs.php');
    }

    public function test_route_periksa_deteksi_keuangan_template(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('keuangan_template')->insertOrIgnore([
            'uuid'        => '999999999999999',
            'parent_uuid' => '888888888888888',
            'uraian'      => 'Pendapatan Asli Desa',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $periksaLib = new \App\Libraries\Periksa();
        $invalidData = $periksaLib->deteksiKeuanganTemplateTidakSama();

        $this->assertNotEmpty($invalidData);
        $found = false;
        foreach ($invalidData as $item) {
            if ($item['current_uuid'] === '999999999999999') {
                $found = true;
                $this->assertEquals('4.1', $item['expected_uuid']);
                $this->assertEquals('4', $item['expected_parent_uuid']);
                break;
            }
        }
        $this->assertTrue($found);

        // Perbaiki via perbaikiSebagian
        $periksaLib->perbaikiSebagian('keuangan_template_tidak_sama');

        $this->assertDatabaseHas('keuangan_template', [
            'uuid'        => '4.1',
            'parent_uuid' => '4',
        ]);

        DB::table('keuangan_template')->where('uuid', '999999999999999')->delete();
    }
}
