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

namespace Tests\Feature\Issue10753;

use App\Libraries\TinyMCE\KodeIsianGambar;
use Tests\BaseTestCase;

/**
 * Test untuk memastikan QR Code muncul pada surat dinas
 *
 * Issue: #10753
 * Bug: QR Code pada surat dinas tidak muncul saat tinjau maupun hasil cetak
 * Fix: Memastikan KodeIsianGambar::set() menangani QR code dengan benar untuk surat dinas
 */
class QRCodeSuratDinasTest extends BaseTestCase
{

    /**
     * Test: QR Code muncul di konten surat dinas ketika setting aktif
     */
    public function test_qr_code_muncul_di_konten_surat_dinas(): void
    {
        // Arrange: Setup request dengan qr_code aktif
        $request = [
            'qr_code'     => true,
            'logo_garuda' => false,
        ];

        $isiSurat = '<p>SURAT PERNYATAAN</p><p>Yang bertanda tangan di bawah ini:</p><p>[qr_code]</p>';

        // Act: Proses kode isian gambar
        $output = KodeIsianGambar::set($request, $isiSurat);

        // Assert: QR code harus muncul sebagai img tag dengan base64
        $this->assertStringNotContainsString('[qr_code]', $output['result'], 'Placeholder [qr_code] harus diganti');
        $this->assertStringContainsString('<img', $output['result'], 'Harus mengandung tag img untuk QR code');
        $this->assertStringContainsString('data:', $output['result'], 'Harus mengandung data URL base64');
        $this->assertStringContainsString('base64', $output['result'], 'Harus mengandung base64 encoding');
        $this->assertStringContainsString('width="90"', $output['result'], 'QR code harus memiliki width 90');
        $this->assertStringContainsString('height="90"', $output['result'], 'QR code harus memiliki height 90');
    }

    /**
     * Test: QR Code tidak muncul ketika setting dinonaktifkan
     */
    public function test_qr_code_tidak_muncul_ketika_setting_nonaktif(): void
    {
        // Arrange: Setup request dengan qr_code tidak aktif
        $request = [
            'qr_code'     => false,
            'logo_garuda' => false,
        ];

        $isiSurat = '<p>SURAT PERNYATAAN</p><p>[qr_code]</p>';

        // Act: Proses kode isian gambar
        $output = KodeIsianGambar::set($request, $isiSurat);

        // Assert: Placeholder harus dihapus dan tidak ada img tag
        $this->assertStringNotContainsString('[qr_code]', $output['result'], 'Placeholder [qr_code] harus dihapus');
        $this->assertStringNotContainsString('width="90"', $output['result'], 'Tidak boleh ada QR code img tag');
    }

    /**
     * Test: QR Code muncul dengan format base64 yang valid
     */
    public function test_format_qr_code_base64_valid(): void
    {
        // Arrange
        $request = [
            'qr_code'     => true,
            'logo_garuda' => false,
        ];

        $isiSurat = '<p>SURAT PERNYATAAN</p><p>[qr_code]</p>';

        // Act
        $output = KodeIsianGambar::set($request, $isiSurat);

        // Assert: Periksa format base64 valid
        $this->assertMatchesRegularExpression(
            '/<img\s+src="data:image\/[^;]+;base64,[A-Za-z0-9+\/=]+"[^>]*>/',
            $output['result'],
            'QR code harus memiliki format data URL base64 yang valid'
        );

        // Extract src attribute
        preg_match('/src="([^"]+)"/', $output['result'], $matches);
        if (isset($matches[1])) {
            $this->assertStringStartsWith('data:image/', $matches[1], 'Harus data URL image');
            $this->assertStringContainsString('base64,', $matches[1], 'Harus mengandung base64');
        }
    }

    /**
     * Test: Multiple placeholder QR Code diganti semua
     */
    public function test_multiple_qr_code_placeholder_diganti(): void
    {
        // Arrange
        $request = [
            'qr_code'     => true,
            'logo_garuda' => false,
        ];

        $isiSurat = '<p>Header</p><p>[qr_code]</p><p>Content</p><p>[qr_code]</p><p>Footer</p>';

        // Act
        $output = KodeIsianGambar::set($request, $isiSurat);

        // Assert: Semua placeholder harus diganti
        $this->assertStringNotContainsString('[qr_code]', $output['result'], 'Semua placeholder [qr_code] harus diganti');

        // Hitung jumlah img tag QR code
        $imgCount = substr_count($output['result'], 'width="90"');
        $this->assertGreaterThanOrEqual(2, $imgCount, 'Harus ada minimal 2 QR code untuk 2 placeholder');
    }
}
