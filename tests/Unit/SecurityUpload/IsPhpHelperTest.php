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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\SecurityUpload;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestHelpers.php';

/**
 * Unit test untuk fungsi isPHP() helper
 *
 * Test ini memastikan fungsi isPHP() dapat dengan akurat mendeteksi file PHP
 * sambil menghindari false positive pada file image dengan binary data random.
 * Test mencakup deteksi magic bytes untuk berbagai format image dan deteksi
 * pola kode PHP berbahaya.
 *
 * @see donjo-app/helpers/opensid_helper.php isPHP()
 */
class IsPhpHelperTest extends TestCase
{
    /**
     * Direktori temporary untuk file test
     */
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/opensid_php_test_' . uniqid();
        mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Cleanup temp files
        $files = glob("{$this->tempDir}/*");
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($this->tempDir);
    }

    /**
     * Helper untuk membuat file dengan content tertentu
     */
    private function createTestFile(string $filename, string $content): string
    {
        $filepath = "{$this->tempDir}/{$filename}";
        file_put_contents($filepath, $content);
        return $filepath;
    }

    /**
     * Test: File dengan extension .php harus deteksi sebagai PHP
     *
     * @test
     */
    public function test_php_file_detected_by_extension()
    {
        $phpFile = $this->createTestFile('test.php', 'random binary data');
        
        $result = isPHP($phpFile, 'test.php');
        
        $this->assertTrue($result, 'File .php harus dideteksi sebagai PHP');
    }

    /**
     * Test: PNG magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_png_magic_bytes_not_detected_as_php()
    {
        $pngMagic = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
        $content = $pngMagic . str_repeat('A', 1000); // PNG header + random data
        
        $pngFile = $this->createTestFile('test.png', $content);
        
        $result = isPHP($pngFile, 'test.png');
        
        $this->assertFalse($result, 'PNG file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: JPEG magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_jpeg_magic_bytes_not_detected_as_php()
    {
        $jpegMagic = "\xFF\xD8\xFF\xE0";
        $content = $jpegMagic . str_repeat('B', 1000);
        
        $jpegFile = $this->createTestFile('test.jpg', $content);
        
        $result = isPHP($jpegFile, 'test.jpg');
        
        $this->assertFalse($result, 'JPEG file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: GIF87a magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_gif87a_magic_bytes_not_detected_as_php()
    {
        $gifMagic = 'GIF87a';
        $content = $gifMagic . str_repeat('C', 1000);
        
        $gifFile = $this->createTestFile('test.gif', $content);
        
        $result = isPHP($gifFile, 'test.gif');
        
        $this->assertFalse($result, 'GIF87a file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: GIF89a magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_gif89a_magic_bytes_not_detected_as_php()
    {
        $gifMagic = 'GIF89a';
        $content = $gifMagic . str_repeat('D', 1000);
        
        $gifFile = $this->createTestFile('test.gif', $content);
        
        $result = isPHP($gifFile, 'test.gif');
        
        $this->assertFalse($result, 'GIF89a file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: WebP magic bytes (RIFF...WEBP) tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_webp_magic_bytes_not_detected_as_php()
    {
        $webpMagic = 'RIFF' . "\x00\x00\x00\x00" . 'WEBP';
        $content = $webpMagic . str_repeat('E', 1000);
        
        $webpFile = $this->createTestFile('test.webp', $content);
        
        $result = isPHP($webpFile, 'test.webp');
        
        $this->assertFalse($result, 'WebP file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: BMP magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_bmp_magic_bytes_not_detected_as_php()
    {
        $bmpMagic = 'BM';
        $content = $bmpMagic . str_repeat('F', 1000);
        
        $bmpFile = $this->createTestFile('test.bmp', $content);
        
        $result = isPHP($bmpFile, 'test.bmp');
        
        $this->assertFalse($result, 'BMP file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: TIFF (little-endian) magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_tiff_little_endian_not_detected_as_php()
    {
        $tiffMagic = "\x49\x49\x2A\x00";
        $content = $tiffMagic . str_repeat('G', 1000);
        
        $tiffFile = $this->createTestFile('test.tiff', $content);
        
        $result = isPHP($tiffFile, 'test.tiff');
        
        $this->assertFalse($result, 'TIFF (little-endian) file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: TIFF (big-endian) magic bytes tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_tiff_big_endian_not_detected_as_php()
    {
        $tiffMagic = "\x4D\x4D\x00\x2A";
        $content = $tiffMagic . str_repeat('H', 1000);
        
        $tiffFile = $this->createTestFile('test.tiff', $content);
        
        $result = isPHP($tiffFile, 'test.tiff');
        
        $this->assertFalse($result, 'TIFF (big-endian) file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: File dengan <?php tag dideteksi sebagai PHP
     *
     * @test
     */
    public function test_php_opening_tag_detected()
    {
        $content = "<?php\n\$var = 'test';\necho \$var;";
        $phpFile = $this->createTestFile('malicious.txt', $content);
        
        $result = isPHP($phpFile, 'malicious.txt');
        
        $this->assertTrue($result, 'File dengan <?php tag harus dideteksi sebagai PHP');
    }

    /**
     * Test: File dengan <?= tag dideteksi sebagai PHP
     *
     * @test
     */
    public function test_php_short_echo_tag_detected()
    {
        $content = "<?= \$var ?>";
        $phpFile = $this->createTestFile('malicious.txt', $content);
        
        $result = isPHP($phpFile, 'malicious.txt');
        
        $this->assertTrue($result, 'File dengan <?= tag harus dideteksi sebagai PHP');
    }

    /**
     * Test: File dengan <script language="php"> dideteksi sebagai PHP
     *
     * @test
     */
    public function test_php_script_language_tag_detected()
    {
        $content = '<script language="php">echo "test";</script>';
        $phpFile = $this->createTestFile('malicious.html', $content);
        
        $result = isPHP($phpFile, 'malicious.html');
        
        $this->assertTrue($result, 'File dengan <script language="php"> harus dideteksi sebagai PHP');
    }

    /**
     * Test: File dengan __halt_compiler() dideteksi sebagai PHP
     *
     * @test
     */
    public function test_halt_compiler_detected()
    {
        $content = '<?php __halt_compiler(); ?>';
        $phpFile = $this->createTestFile('malicious.php', $content);
        
        $result = isPHP($phpFile, 'malicious.php');
        
        $this->assertTrue($result, 'File dengan __halt_compiler() harus dideteksi sebagai PHP');
    }

    /**
     * Test: PNG polyglot (gambar dengan PHP disisipkan) WAJIB dideteksi sebagai PHP
     *
     * Polyglot file = satu file yang valid dibaca sebagai dua format berbeda
     * (di sini: PNG yang valid + kode PHP yang dapat dieksekusi).
     * Pattern scan harus tetap berjalan walaupun magic bytes mengindikasikan image.
     *
     * @test
     */
    public function test_png_polyglot_with_embedded_php_detected_as_php()
    {
        // Polyglot: PNG header + random bytes + PHP code
        $pngMagic = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
        $phpCode = '<?php system("whoami"); ?>';
        $content = $pngMagic . str_repeat('X', 500) . $phpCode;

        $pngFile = $this->createTestFile('polyglot.png', $content);

        $result = isPHP($pngFile, 'polyglot.png');

        $this->assertTrue($result, 'PNG polyglot dengan embedded PHP WAJIB dideteksi sebagai PHP');
    }

    /**
     * Test: File kosong tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_empty_file_not_detected_as_php()
    {
        $emptyFile = $this->createTestFile('empty.txt', '');
        
        $result = isPHP($emptyFile, 'empty.txt');
        
        $this->assertFalse($result, 'File kosong tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: File yang tidak dapat dibuka return false
     *
     * @test
     */
    public function test_nonexistent_file_returns_false()
    {
        $nonExistentFile = "{$this->tempDir}/nonexistent.txt";
        
        $result = isPHP($nonExistentFile, 'nonexistent.txt');
        
        $this->assertFalse($result, 'File yang tidak ada harus return false');
    }

    /**
     * Test: HTML file dengan tag <html> tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_html_file_not_detected_as_php()
    {
        $content = '<!DOCTYPE html><html><body><h1>Test</h1></body></html>';
        $htmlFile = $this->createTestFile('test.html', $content);
        
        $result = isPHP($htmlFile, 'test.html');
        
        $this->assertFalse($result, 'File HTML tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: File dengan text biasa (bukan PHP, bukan image) tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_plain_text_file_not_detected_as_php()
    {
        $content = "Ini adalah file teks biasa\nTanpa kode PHP apapun\n";
        $textFile = $this->createTestFile('test.txt', $content);
        
        $result = isPHP($textFile, 'test.txt');
        
        $this->assertFalse($result, 'File teks biasa tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: Binary data random yang kebetulan mirip byte pattern tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_random_binary_data_not_detected_as_php()
    {
        // Generate random binary yang mungkin mengandung byte sequence mirip '<html'
        $randomBytes = '';
        for ($i = 0; $i < 2000; $i++) {
            $randomBytes .= chr(rand(32, 255));
        }
        
        $binFile = $this->createTestFile('random.bin', $randomBytes);
        
        $result = isPHP($binFile, 'random.bin');
        
        // Karena bukan image magic bytes dan bukan PHP pattern, harusnya false
        // (dalam praktik, random bytes kemungkinan tidak match PHP pattern yang spesifik)
        $this->assertFalse($result, 'Random binary data harusnya tidak match PHP pattern yang spesifik');
    }

    /**
     * Test: SVG file dengan XML declaration tidak dideteksi sebagai PHP
     *
     * @test
     */
    public function test_svg_file_not_detected_as_php()
    {
        $content = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg"></svg>';
        $svgFile = $this->createTestFile('test.svg', $content);
        
        $result = isPHP($svgFile, 'test.svg');
        
        $this->assertFalse($result, 'SVG file tidak boleh dideteksi sebagai PHP');
    }

    /**
     * Test: Filename extension tidak mempengaruhi detection (hanya untuk .php)
     *
     * @test
     * @dataProvider wrongExtensionProvider
     */
    public function test_php_code_detected_regardless_of_extension($content, $filename, $expected)
    {
        $file = $this->createTestFile($filename, $content);
        
        $result = isPHP($file, $filename);
        
        $this->assertSame($expected, $result, "File '$filename' detection mismatch");
    }

    /**
     * Data provider untuk test PHP code dengan berbagai extension
     */
    public function wrongExtensionProvider()
    {
        return [
            'PHP code in TXT' => [
                "<?php system('id'); ?>",
                'payload.txt',
                true, // Harus dideteksi karena ada <?php
            ],
            'PHP code in HTML' => [
                "<?= phpinfo() ?>",
                'shell.html',
                true, // Harus dideteksi karena ada <?=
            ],
            'PHP in JPG extension (polyglot)' => [
                "\xFF\xD8\xFF" . '<?php echo "test"; ?>',
                'fake.jpg',
                true, // Polyglot: walaupun magic bytes JPEG valid, embedded <?php WAJIB terdeteksi
            ],
        ];
    }
}
