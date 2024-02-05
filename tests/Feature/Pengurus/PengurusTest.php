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

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

final class PengurusTest extends TestCase
{
    private function getDummyData()
    {
        // include helper opensid_helper.php
        return (object) [
            'pamong_nama'    => 'Wijaya Kusuma',
            'gelar_depan'    => 'Drs.',
            'gelar_belakang' => 'Bc.I.P., S.H., M.Si.',
        ];
    }

    public function testValidNameUpperWithTitle()
    {
        $pengurus = $this->getDummyData();

        $formattedName = $this->gelar($pengurus->gelar_depan, strtoupper($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertEquals('Drs. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUpperWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = $this->gelar($pengurus->gelar_depan, strtoupper($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Drs. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testValidNameUcwordsWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = $this->gelar($pengurus->gelar_depan, ucwords($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertEquals('Drs. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUcwordsWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = $this->gelar($pengurus->gelar_depan, ucwords($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Drs. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    // TODO:: Gunakan opensid_helper.php -> gelar()
    private function gelar($gelar_depan = null, $nama = null, $gelar_belakang = null)
    {
        // Gelar depan
        if ($gelar_depan) {
            $nama = $gelar_depan . ' ' . $nama;
        }

        // Gelar belakang
        if ($gelar_belakang) {
            return $nama . ', ' . $gelar_belakang;
        }

        return $nama;
    }
}