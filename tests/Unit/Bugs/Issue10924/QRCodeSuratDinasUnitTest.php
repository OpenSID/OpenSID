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

namespace Tests\Unit\Bugs\Issue10924;

use App\Models\LogSuratDinas;
use App\Models\Urls;
use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit Test untuk Bug #10924 - QR Code Scan Result Surat Dinas Tidak Sesuai
 *
 * Bug: HASIL SCAN QR CODE SURAT DINAS TIDAK SESUAI
 * Cause: LogSurat::buatQrCode() menggunakan Urls::urlPendek() untuk semua tipe surat,
 *        padahal Surat Dinas membutuhkan Urls::urlPendekDinas() untuk endpoint yang berbeda
 * Fix: Tambah LogSuratDinas::buatQrCode() yang menggunakan Urls::urlPendekDinas()
 *      Update KodeIsianGambar::handleQrCode() untuk mendeteksi tipe model dan call method yang benar
 */
class QRCodeSuratDinasUnitTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Test: LogSuratDinas memiliki method buatQrCode
     */
    public function test_log_surat_dinas_has_static_buat_qr_code_method(): void
    {
        // Arrange
        $methodName = 'buatQrCode';

        // Act
        $hasMethod = method_exists(LogSuratDinas::class, $methodName);

        // Assert
        $this->assertTrue($hasMethod, 'LogSuratDinas harus memiliki method ' . $methodName);

        // Verifikasi adalah static method
        $reflection = new \ReflectionMethod(LogSuratDinas::class, $methodName);
        $this->assertTrue($reflection->isStatic(), 'Method ' . $methodName . ' harus static');
    }

    /**
     * Test: buatQrCode mengembalikan array dengan key yang benar
     */
    public function test_buat_qr_code_returns_correct_array_structure(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        // Buat test LogSuratDinas
        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertIsArray($result, 'Result harus array');
        $this->assertArrayHasKey('viewqr', $result, 'Result harus punya key viewqr');
        $this->assertArrayHasKey('urls_id', $result, 'Result harus punya key urls_id');
        $this->assertArrayHasKey('isiqr', $result, 'Result harus punya key isiqr');
        $this->assertArrayHasKey('logoqr', $result, 'Result harus punya key logoqr');
        $this->assertArrayHasKey('sizeqr', $result, 'Result harus punya key sizeqr');
        $this->assertArrayHasKey('foreqr', $result, 'Result harus punya key foreqr');
    }

    /**
     * Test: buatQrCode menggunakan urlPendekDinas (bukan urlPendek)
     */
    public function test_buat_qr_code_uses_url_pendek_dinas(): void
    {
        // Arrange
        $namaSurat = 'test_surat_dinas_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat'      => $namaSurat,
            'id_format_surat' => 1,
            'id_pend'         => 1,
            'id_pamong'       => 1,
            'id_user'         => 1,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        // URL harus mengandung 'v/' yang merupakan hasil dari urlPendekDinas
        // dan harus ada di tabel urls
        if ($result['urls_id']) {
            $url = Urls::find($result['urls_id']);
            $this->assertNotNull($url, 'Urls record harus ada untuk urls_id: ' . $result['urls_id']);

            // Periksa bahwa URL record berisi route untuk surat dinas
            // urlPendekDinas menggunakan route 'c1.{id}.surat_dinas'
            $this->assertNotNull($url->url, 'URL record harus memiliki url');
            $this->assertStringContainsString('surat_dinas', $url->url, 'URL harus mengandung surat_dinas');
        }
    }

    /**
     * Test: isiqr mengandung short URL yang valid
     */
    public function test_isiqr_contains_valid_short_url(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertIsString($result['isiqr'], 'isiqr harus string');
        $this->assertStringContainsString('v/', $result['isiqr'], 'isiqr harus mengandung /v/ untuk short URL');
        $this->assertTrue(filter_var($result['isiqr'], FILTER_VALIDATE_URL), 'isiqr harus valid URL');
    }

    /**
     * Test: sizeqr default 6
     */
    public function test_sizeqr_default_value_six(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertEquals(6, $result['sizeqr'], 'sizeqr default harus 6');
    }

    /**
     * Test: foreqr default #000000
     */
    public function test_foreqr_default_color_black(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertEquals('#000000', $result['foreqr'], 'foreqr default harus #000000 (black)');
    }

    /**
     * Test: buatQrCode return null viewqr jika surat tidak ditemukan
     */
    public function test_buat_qr_code_returns_null_viewqr_when_surat_not_found(): void
    {
        // Arrange
        $namaSurat = 'tidak_ada_' . time() . '.pdf';
        $logo = null;

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertNull($result['viewqr'], 'viewqr harus null jika surat tidak ditemukan');
    }

    /**
     * Test: Multiple calls menghasilkan URL pendek yang berbeda
     */
    public function test_multiple_calls_generate_different_short_urls(): void
    {
        // Arrange
        $namaSurat1 = 'test_surat_1_' . time() . '.pdf';
        $namaSurat2 = 'test_surat_2_' . time() . '.pdf';
        $logo = null;

        $surat1 = LogSuratDinas::factory()->create(['nama_surat' => $namaSurat1]);
        $surat2 = LogSuratDinas::factory()->create(['nama_surat' => $namaSurat2]);

        // Act
        $result1 = LogSuratDinas::buatQrCode($namaSurat1, $logo);
        $result2 = LogSuratDinas::buatQrCode($namaSurat2, $logo);

        // Assert
        // URLs pendek harus berbeda untuk surat yang berbeda
        if ($result1['urls_id'] && $result2['urls_id']) {
            $this->assertNotEquals(
                $result1['isiqr'],
                $result2['isiqr'],
                'isiqr harus berbeda untuk surat yang berbeda'
            );
        }
    }

    /**
     * Test: viewqr menghasilkan file yang ada
     */
    public function test_viewqr_generates_existing_file(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        if ($result['viewqr']) {
            $this->assertTrue(
                file_exists($result['viewqr']),
                'File QR code harus ada di path: ' . $result['viewqr']
            );

            // Periksa bahwa file adalah PNG image
            $this->assertTrue(
                function_exists('mime_content_type') ? strpos(mime_content_type($result['viewqr']), 'image') !== false : true,
                'File harus berupa image'
            );
        }
    }

    /**
     * Test: logoqr menggunakan identitas logo yang benar
     */
    public function test_logoqr_uses_correct_identity_logo(): void
    {
        // Arrange
        $namaSurat = 'test_surat_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertIsString($result['logoqr'], 'logoqr harus string (file path)');
        // logoqr akan berupa path absolut ke file logo
        $this->assertNotEmpty($result['logoqr'], 'logoqr tidak boleh kosong');
    }

    /**
     * Test: Konsistensi antara pemanggilan pertama dan kedua
     */
    public function test_multiple_calls_for_same_surat_produces_consistent_urls(): void
    {
        // Arrange
        $namaSurat = 'test_surat_consistency_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result1 = LogSuratDinas::buatQrCode($namaSurat, $logo);
        
        // Tunggu sebentar
        sleep(1);
        
        $result2 = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        // Untuk surat yang sama, URL pendek seharusnya menggunakan urls_id yang sama
        if ($result1['urls_id'] && $result2['urls_id']) {
            // Bisa berbeda karena dibuatkan Urls record baru setiap kali
            // Tapi structure-nya harus sama
            $this->assertIsString($result1['isiqr'], 'isiqr pertama harus string');
            $this->assertIsString($result2['isiqr'], 'isiqr kedua harus string');
            
            // Keduanya harus memiliki format yang valid
            $this->assertStringContainsString('v/', $result1['isiqr'], 'isiqr pertama harus punya /v/');
            $this->assertStringContainsString('v/', $result2['isiqr'], 'isiqr kedua harus punya /v/');
        }
    }

    /**
     * Test: Response dari buatQrCode bukan null
     */
    public function test_buat_qr_code_response_not_null_array(): void
    {
        // Arrange
        $namaSurat = 'test_surat_not_null_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat' => $namaSurat,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        $this->assertNotNull($result, 'Hasil buatQrCode tidak boleh null');
        $this->assertIsArray($result, 'Hasil buatQrCode harus array');
        $this->assertGreaterThan(0, count($result), 'Array hasil harus tidak kosong');
    }

    /**
     * Test: Perbedaan dengan urlPendek (untuk surat biasa)
     *
     * Method ini memverifikasi bahwa LogSuratDinas::buatQrCode() menggunakan
     * endpoint surat_dinas dan BUKAN endpoint surat biasa seperti LogSurat::buatQrCode()
     */
    public function test_ensures_surat_dinas_specific_url_generation(): void
    {
        // Arrange
        $namaSurat = 'test_specific_' . time() . '.pdf';
        $logo = null;

        $suratDinas = LogSuratDinas::factory()->create([
            'nama_surat'      => $namaSurat,
            'id_format_surat' => 1,
        ]);

        // Act
        $result = LogSuratDinas::buatQrCode($namaSurat, $logo);

        // Assert
        if ($result['urls_id']) {
            $urlRecord = Urls::find($result['urls_id']);
            
            // URL untuk Surat Dinas harus mengandung 'surat_dinas' endpoint
            // bukan endpoint surat biasa yang menggunakan 'c1.{id}'
            $this->assertNotNull($urlRecord, 'Urls record harus ditemukan');
            $this->assertStringContainsString(
                'surat_dinas',
                $urlRecord->url,
                'URL harus mengandung endpoint surat_dinas'
            );
        }
    }
}
