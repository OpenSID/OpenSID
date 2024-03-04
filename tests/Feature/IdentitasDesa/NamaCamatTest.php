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

final class NamaCamatTest extends TestCase
{
    private function getDummyData() {
        return (object) [
            'nama_kepala_camat' => 'Dr. Drs. Ir. Wijaya kusuma, Bc.I.P., S.H., M.Si.'
        ];
    }

    public function testValidNameUpperWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . strtoupper($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUpperWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . strtoupper($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testValidNameLowerWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . strtolower($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. wijaya kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameLowerWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . strtolower($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testValidNameUcFirstWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . ucfirst($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. Wijaya kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUcFirstWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . ucfirst($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testValidNameUcWordsWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . ucwords($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertEquals('Dr. Drs. Ir. Wijaya Kusuma, Bc.I.P., S.H., M.Si.', $formattedName);
    }

    public function testInvalidNameUcWordsWithTitle()
    {
        $identitas        = $this->getDummyData();
        $pecah_nama_gelar = pecah_nama_gelar($identitas->nama_kepala_camat);
        $gelar_depan      = $pecah_nama_gelar['gelar_depan'];
        $gelar_belakang   = $pecah_nama_gelar['gelar_belakang'];
        $formattedName    = $gelar_depan . ' ' . ucwords($pecah_nama_gelar['nama']) . ', ' . $gelar_belakang;

        // Assert that the formatted name is as expected
        $this->assertNotEquals('Dr. Drs. Ir. WIJAYA KUSUMA, Bc.I.P., S.H., M.Si.', $formattedName);
    }
}