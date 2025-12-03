<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Issue10441;

use App\Enums\PeristiwaKeluargaEnum;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan enum PeristiwaKeluargaEnum berjalan sesuai kebutuhan
 *
 * Issue: #10441
 * Fix: Refactor LogKeluarga constants ke PHP native enum
 */
class PeristiwaKeluargaEnumTest extends BaseTestCase
{
    /**
     * Test: Enum dapat mengambil instance berdasarkan value
     */
    public function test_enum_from_value(): void
    {
        $this->assertSame(
            PeristiwaKeluargaEnum::KELUARGA_BARU,
            PeristiwaKeluargaEnum::from(1)
        );

        $this->assertNull(
            PeristiwaKeluargaEnum::tryFrom(999)
        );
    }

    /**
     * Test: Setiap enum value memiliki angka yang sudah ditentukan
     */
    public function test_enum_values_are_correct(): void
    {
        $this->assertEquals(1, PeristiwaKeluargaEnum::KELUARGA_BARU->value);
        $this->assertEquals(2, PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value);
        $this->assertEquals(3, PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value);
        $this->assertEquals(4, PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value);
        $this->assertEquals(5, PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value);
        $this->assertEquals(6, PeristiwaKeluargaEnum::KEPALA_KELUARGA_PERGI->value);
        $this->assertEquals(11, PeristiwaKeluargaEnum::KEPALA_KELUARGA_TIDAK_VALID->value);
        $this->assertEquals(12, PeristiwaKeluargaEnum::ANGGOTA_KELUARGA_PECAH->value);
        $this->assertEquals(13, PeristiwaKeluargaEnum::KELUARGA_HAPUS->value);
        $this->assertEquals(14, PeristiwaKeluargaEnum::KEPALA_KELUARGA_KEMBALI_HIDUP->value);
    }

    /**
     * Test: Label yang dikembalikan sesuai dengan mapping yang ditentukan
     */
    public function test_enum_label_output(): void
    {
        $this->assertEquals('Baru Lahir', PeristiwaKeluargaEnum::KELUARGA_BARU->label());
        $this->assertEquals('Kepala Keluarga Mati', PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->label());
        $this->assertEquals('Kepala Keluarga Pindah', PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->label());
        $this->assertEquals('Kepala Keluarga Hilang', PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->label());
        $this->assertEquals('Keluarga Baru Datang', PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->label());
        $this->assertEquals('Kepala Keluarga Pergi', PeristiwaKeluargaEnum::KEPALA_KELUARGA_PERGI->label());
        $this->assertEquals('Kepala Keluarga Tidak Valid', PeristiwaKeluargaEnum::KEPALA_KELUARGA_TIDAK_VALID->label());
        $this->assertEquals('Anggota Keluarga Pecah', PeristiwaKeluargaEnum::ANGGOTA_KELUARGA_PECAH->label());
        $this->assertEquals('Keluarga Hapus', PeristiwaKeluargaEnum::KELUARGA_HAPUS->label());
        $this->assertEquals('Kepala Keluarga Kembali Hidup', PeristiwaKeluargaEnum::KEPALA_KELUARGA_KEMBALI_HIDUP->label());
    }

    /**
     * Test: Method labels() mengembalikan array mapping yang benar
     */
    public function test_enum_labels_array(): void
    {
        $labels = PeristiwaKeluargaEnum::labels();

        $this->assertIsArray($labels);
        $this->assertCount(10, $labels);

        // Cek key sesuai dengan value enum
        $this->assertArrayHasKey(1, $labels);
        $this->assertArrayHasKey(2, $labels);
        $this->assertArrayHasKey(3, $labels);
        $this->assertArrayHasKey(4, $labels);
        $this->assertArrayHasKey(5, $labels);
        $this->assertArrayHasKey(6, $labels);
        $this->assertArrayHasKey(11, $labels);
        $this->assertArrayHasKey(12, $labels);
        $this->assertArrayHasKey(13, $labels);
        $this->assertArrayHasKey(14, $labels);

        // Cek value sesuai dengan label
        $this->assertEquals('Baru Lahir', $labels[1]);
        $this->assertEquals('Kepala Keluarga Pergi', $labels[6]);
        $this->assertEquals('Kepala Keluarga Kembali Hidup', $labels[14]);
    }

    /**
     * Test: Semua cases enum tercakup
     */
    public function test_all_enum_cases_count(): void
    {
        $cases = PeristiwaKeluargaEnum::cases();

        $this->assertCount(10, $cases);
    }
}
