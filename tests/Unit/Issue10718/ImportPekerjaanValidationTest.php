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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Issue10718;

use App\Enums\PekerjaanEnum;
use Illuminate\Support\Facades\Validator;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan validasi pekerjaan_id menerima ID 89 (LAINNYA)
 *
 * Issue: #10718
 * Fix: Validasi import penduduk harus menerima pekerjaan_id = 89
 */
class ImportPekerjaanValidationTest extends BaseTestCase
{
    /**
     * Test: Validasi pekerjaan_id menerima ID 89 (LAINNYA)
     */
    public function test_validasi_pekerjaan_id_89_berhasil(): void
    {
        $data = [
            'pekerjaan_id' => 89,
        ];

        $validator = Validator::make($data, [
            'pekerjaan_id' => ['nullable', static function ($attribute, $value, $fail): void {
                if (! in_array((int) $value, PekerjaanEnum::keys())) {
                    $fail("kode pekerjaan {$value} tidak dikenal");
                }
            }],
        ]);

        $this->assertFalse($validator->fails(), 'Validasi pekerjaan_id = 89 harus berhasil');
        $this->assertEmpty($validator->errors()->all(), 'Tidak boleh ada error untuk pekerjaan_id = 89');
    }

    /**
     * Test: Validasi pekerjaan_id menolak ID yang tidak valid
     */
    public function test_validasi_pekerjaan_id_tidak_valid_gagal(): void
    {
        $data = [
            'pekerjaan_id' => 999,
        ];

        $validator = Validator::make($data, [
            'pekerjaan_id' => ['nullable', static function ($attribute, $value, $fail): void {
                if (! in_array((int) $value, PekerjaanEnum::keys())) {
                    $fail("kode pekerjaan {$value} tidak dikenal");
                }
            }],
        ]);

        $this->assertTrue($validator->fails(), 'Validasi pekerjaan_id = 999 harus gagal');
        $this->assertStringContainsString('tidak dikenal', $validator->errors()->first('pekerjaan_id'));
    }

    /**
     * Test: Validasi pekerjaan_id menerima semua ID dari 1-89
     */
    public function test_validasi_pekerjaan_id_menerima_semua_id_valid(): void
    {
        $validIds = PekerjaanEnum::keys();

        foreach ($validIds as $id) {
            $data = [
                'pekerjaan_id' => $id,
            ];

            $validator = Validator::make($data, [
                'pekerjaan_id' => ['nullable', static function ($attribute, $value, $fail): void {
                    if (! in_array((int) $value, PekerjaanEnum::keys())) {
                        $fail("kode pekerjaan {$value} tidak dikenal");
                    }
                }],
            ]);

            $this->assertFalse(
                $validator->fails(),
                "Validasi pekerjaan_id = {$id} harus berhasil"
            );
        }
    }

    /**
     * Test: Validasi pekerjaan_id nullable (boleh kosong)
     */
    public function test_validasi_pekerjaan_id_nullable(): void
    {
        $data = [
            'pekerjaan_id' => null,
        ];

        $validator = Validator::make($data, [
            'pekerjaan_id' => ['nullable', static function ($attribute, $value, $fail): void {
                if (! in_array((int) $value, PekerjaanEnum::keys())) {
                    $fail("kode pekerjaan {$value} tidak dikenal");
                }
            }],
        ]);

        $this->assertFalse($validator->fails(), 'Validasi pekerjaan_id = null harus berhasil (nullable)');
    }

    /**this->assertIsArray($all);
        $this->assertArrayHasKey(89, $all, 'PekerjaanEnum::all() harus memiliki key 89');
        $this->assertEquals('LAINNYA', $all[89], 'ID 89 harus memiliki label "LAINNYA"');
    }

    /**
     * Test: Semua ID pekerjaan dari 1-89 tersedia
     */
    public function test_pekerjaan_enum_memiliki_89_pekerjaan(): void
    {
        $keys = PekerjaanEnum::keys();

        $this->assertCount(89, $keys, 'PekerjaanEnum harus memiliki 89 jenis pekerjaan');
        
        // Pastikan ID 1 sampai 89 semua ada
        for ($i = 1; $i <= 89; $i++) {
            $this->assertContains($i, $keys, "PekerjaanEnum harus memiliki ID {$i}");
        }
    }

    /**
     * Test: ID 89 adalah ID pekerjaan tertinggi
     */
    public function test_id_89_adalah_pekerjaan_tertinggi(): void
    {
        $keys = PekerjaanEnum::keys();
        $maxId = max($keys);

        $this->assertEquals(89, $maxId, 'ID pekerjaan tertinggi harus 89 (LAINNYA)');
    }
}