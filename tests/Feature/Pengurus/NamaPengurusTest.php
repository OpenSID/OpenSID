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

final class NamaPengurusTest extends TestCase
{
    private function getDummyData()
    {
        // include helper opensid_helper.php
        return (object) [
            'pamong_nama'    => 'Wijaya kusuma',
            'gelar_depan'    => 'Dr. Drs. Ir.',
            'gelar_belakang' => 'Bc.I.P., S.H., M.Si.',
        ];
    }

    public function testValidNameUpperWithTitle()
    {
        $pengurus = $this->getDummyData();

        $formattedName = gelar($pengurus->gelar_depan, strtoupper($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUpperWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = gelar($pengurus->gelar_depan, strtoupper($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testValidNameUcwordsWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = gelar($pengurus->gelar_depan, ucwords($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUcwordsWithTitle()
    {
        $pengurus = $this->getDummyData();
        $formattedName = gelar($pengurus->gelar_depan, ucwords($pengurus->pamong_nama), $pengurus->gelar_belakang);

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }
}