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
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Issue11042;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Helpers.php';

class GetNokkHelperTest extends TestCase
{
    /**
     * Test get_nokk() adalah alias untuk get_nik()
     * Mengembalikan input jika tidak diawali dengan '0'
     *
     * @test
     * @dataProvider validNokkProvider
     */
    public function test_get_nokk_returns_input_when_not_start_with_zero($nokk, $expected)
    {
        $result = get_nokk($nokk);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test get_nokk() mengembalikan '0' jika input diawali dengan '0'
     *
     * @test
     */
    public function test_get_nokk_returns_zero_when_starts_with_zero()
    {
        $result = get_nokk('0123456789012345');

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nokk() dengan nilai default parameter
     *
     * @test
     */
    public function test_get_nokk_with_default_parameter()
    {
        $result = get_nokk();

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nokk() dengan empty string
     *
     * @test
     */
    public function test_get_nokk_with_empty_string()
    {
        $result = get_nokk('');

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nokk() memiliki behavior yang sama dengan get_nik()
     * Keduanya harus mengembalikan hasil yang sama untuk input yang sama
     *
     * @test
     * @dataProvider dataProviderForComparisonWithNik
     */
    public function test_get_nokk_same_as_get_nik($testValue)
    {
        $result_nokk = get_nokk($testValue);
        $result_nik = get_nik($testValue);

        $this->assertEquals($result_nik, $result_nokk);
    }

    /**
     * Test get_nokk() dengan KK 16 digit standard Indonesia
     *
     * @test
     */
    public function test_get_nokk_with_16_digit_number()
    {
        // KK diawali dengan 0
        $result = get_nokk('0123456789012345');
        $this->assertEquals('0', $result);

        // KK diawali dengan selain 0
        $result = get_nokk('1234567890123456');
        $this->assertEquals('1234567890123456', $result);
    }

    /**
     * Test get_nokk() dengan KK yang berisi karakter khusus
     *
     * @test
     */
    public function test_get_nokk_with_special_characters()
    {
        $result = get_nokk('0-123-456-789-012');

        $this->assertEquals('0', $result);
    }

    /**
     * Provider data untuk test get_nokk
     */
    public static function validNokkProvider(): array
    {
        return [
            'KK diawali dengan 1' => ['1234567890123456', '1234567890123456'],
            'KK diawali dengan 2' => ['2345678901234567', '2345678901234567'],
            'KK diawali dengan 3' => ['3456789012345678', '3456789012345678'],
            'KK diawali dengan 4' => ['4567890123456789', '4567890123456789'],
            'KK diawali dengan 5' => ['5678901234567890', '5678901234567890'],
            'KK diawali dengan 6' => ['6789012345678901', '6789012345678901'],
            'KK diawali dengan 7' => ['7890123456789012', '7890123456789012'],
            'KK diawali dengan 8' => ['8901234567890123', '8901234567890123'],
            'KK diawali dengan 9' => ['9012345678901234', '9012345678901234'],
        ];
    }

    /**
     * Provider data untuk perbandingan get_nokk dengan get_nik
     */
    public static function dataProviderForComparisonWithNik(): array
    {
        return [
            'Default parameter' => ['0'],
            'KK diawali 0' => ['0123456789012345'],
            'KK diawali 1' => ['1234567890123456'],
            'KK diawali 2' => ['2345678901234567'],
            'Empty string' => [''],
        ];
    }
}
