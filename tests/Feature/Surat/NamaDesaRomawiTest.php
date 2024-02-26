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

use PHPUnit\Framework\TestCase;

final class NamaDesaRomawiTest extends TestCase
{
    public $namaDesa = 'banuaji angkasa IV';

    public function testValidDesaUpperWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'upper');

        // Assert that the formatted name is as expected
        $this->assertEquals('BANUAJI ANGKASA IV', $formattedName);
    }

    public function testInvalidDesaUpperWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'upper');

        // Assert that the formatted name is as expected
        $this->assertNotEquals('BANUAJI ANGKASA iv', $formattedName);
    }

    public function testValidDesaLowerWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'lower');

        // Assert that the formatted name is as expected
        $this->assertEquals('banuaji angkasa IV', $formattedName);
    }

    public function testInvalidDesaLowerWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'lower');

        // Assert that the formatted name is as expected
        $this->assertNotEquals('banuaji angkasa iv', $formattedName);
    }

    // public function testValidDesaUcfirstWithRomawi()
    // {
    //     $formattedName = set_words($this->namaDesa, 'ucfirst');

    //     // Assert that the formatted name is as expected
    //     $this->assertEquals('Banuaji angkasa IV', $formattedName);
    // }

    // public function testInvalidDesaUcfirstWithRomawi()
    // {
    //     $formattedName = set_words($this->namaDesa, 'ucfirst');

    //     // Assert that the formatted name is as expected
    //     $this->assertNotEquals('Banuaji angkasa iv', $formattedName);
    // }

    public function testValidDesaUcwordsWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'ucwords');

        // Assert that the formatted name is as expected
        $this->assertEquals('Banuaji Angkasa IV', $formattedName);
    }

    public function testInvalidDesaUcwordsWithRomawi()
    {
        $formattedName = set_words($this->namaDesa, 'ucwords');

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Banuaji Angkasa iv', $formattedName);
    }
}