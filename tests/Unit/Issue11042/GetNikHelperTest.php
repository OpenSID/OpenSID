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

class GetNikHelperTest extends TestCase
{
    /**
     * Test get_nik() mengembalikan input jika tidak diawali dengan '0'
     *
     * @test
     * @dataProvider validNikProvider
     */
    public function test_get_nik_returns_input_when_not_start_with_zero($nik, $expected)
    {
        $result = get_nik($nik);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test get_nik() mengembalikan '0' jika input diawali dengan '0'
     *
     * @test
     */
    public function test_get_nik_returns_zero_when_starts_with_zero()
    {
        $result = get_nik('0123456789012345');

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nik() dengan nilai default parameter
     *
     * @test
     */
    public function test_get_nik_with_default_parameter()
    {
        $result = get_nik();

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nik() dengan empty string
     *
     * @test
     */
    public function test_get_nik_with_empty_string()
    {
        $result = get_nik('');

        $this->assertEquals('0', $result);
    }

    /**
     * Test get_nik() dengan NIK 16 digit standard Indonesia
     *
     * @test
     */
    public function test_get_nik_with_16_digit_number()
    {
        // NIK diawali dengan 0
        $result = get_nik('0123456789012345');
        $this->assertEquals('0', $result);

        // NIK diawali dengan selain 0
        $result = get_nik('1234567890123456');
        $this->assertEquals('1234567890123456', $result);
    }

    /**
     * Test get_nik() dengan NIK yang berisi karakter khusus
     *
     * @test
     */
    public function test_get_nik_with_special_characters()
    {
        $result = get_nik('0-123-456-789-012');

        $this->assertEquals('0', $result);
    }

    /**
     * Provider data untuk test get_nik
     */
    public static function validNikProvider(): array
    {
        return [
            'NIK diawali dengan 1' => ['1234567890123456', '1234567890123456'],
            'NIK diawali dengan 2' => ['2345678901234567', '2345678901234567'],
            'NIK diawali dengan 3' => ['3456789012345678', '3456789012345678'],
            'NIK diawali dengan 4' => ['4567890123456789', '4567890123456789'],
            'NIK diawali dengan 5' => ['5678901234567890', '5678901234567890'],
            'NIK diawali dengan 6' => ['6789012345678901', '6789012345678901'],
            'NIK diawali dengan 7' => ['7890123456789012', '7890123456789012'],
            'NIK diawali dengan 8' => ['8901234567890123', '8901234567890123'],
            'NIK diawali dengan 9' => ['9012345678901234', '9012345678901234'],
        ];
    }
}
