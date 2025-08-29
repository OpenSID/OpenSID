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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Helpers;

use Tests\BaseTestCase;
use Illuminate\Support\Facades\Cache;

final class OpenSIDHelperTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../../../donjo-app/helpers/opensid_helper.php';
    }

    // Mulai Email
    public function testEmailCleansAndValidatesCorrectly()
    {
        $email = 'andi_fahruddin?@gmail.com';
        $cleanedEmail = email($email);
        $expectedEmail = 'andi_fahruddin@gmail.com';
        $this->assertSame($expectedEmail, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithNoInvalidCharacters()
    {
        $email = 'andi_fahruddin@gmail.com';
        $cleanedEmail = email($email);
        $this->assertSame($email, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithMultipleInvalidCharacters()
    {
        $email = 'andi_fahruddin!!@gmail.com';
        $cleanedEmail = email($email);
        $expectedEmail = 'andi_fahruddin@gmail.com';
        $this->assertSame($expectedEmail, $cleanedEmail);
        $this->assertTrue(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }

    public function testEmailWithEmptyString()
    {
        $email = '';
        $cleanedEmail = email($email);
        $this->assertSame('', $cleanedEmail);
        $this->assertFalse(filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL) !== false);
    }
    // Akhir Email
}