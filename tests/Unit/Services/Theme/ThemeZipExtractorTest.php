<?php

namespace Tests\Unit\Services\Theme;

use App\Services\Theme\ThemeZipExtractor;
use Tests\BaseTestCase;
use ZipArchive;

/**
 * Mirror dari premium#6790 (OpenSID/premium teknis/anjungan-kiosk-seam):
 * ZipArchive::extractTo() menimpa file-per-file, bukan mengganti seluruh
 * folder — file yang dihapus pengembang tema antara dua versi tertinggal
 * (stale) di disk bila diekstrak langsung ke folder lama.
 *
 * PENTING: FCPATH selalu path proyek NYATA (bukan bisa diarahkan ke folder
 * sementara), jadi test ini genuinely menulis ke `desa/themes/<nama unik>/`
 * sungguhan. Nama tema dibuat unik per test (uniqid()) dan SELALU dibersihkan
 * di tearDown() (juga dijalankan saat assertion gagal) supaya tak pernah
 * meninggalkan folder tema uji di instalasi lokal.
 */
class ThemeZipExtractorTest extends BaseTestCase
{
    private string $namaTema;

    private string $lokasiTema;

    /** @var list<string> */
    private array $zipSementara = [];

    protected function setUp(): void
    {
        parent::setUp();

        // delete_files() adalah helper bawaan CodeIgniter 3 (system/helpers/file_helper.php),
        // dimuat via $this->load->helper('file') saat runtime CI3 nyata — tak tersedia
        // di konteks Unit test murni tanpa CI3.
        if (! function_exists('delete_files')) {
            require_once base_path('vendor/codeigniter/framework/system/helpers/file_helper.php');
        }

        $this->namaTema   = 'uji-tema-6790-' . uniqid('', true);
        $this->lokasiTema = FCPATH . 'desa/themes/' . $this->namaTema;
    }

    protected function tearDown(): void
    {
        $this->hapusRekursif($this->lokasiTema);
        foreach (glob(FCPATH . 'desa/themes/_staging_*') ?: [] as $stagingLama) {
            $this->hapusRekursif($stagingLama);
        }

        foreach ($this->zipSementara as $path) {
            @unlink($path);
        }

        parent::tearDown();
    }

    public function test_versi_baru_menghapus_file_stale_dari_versi_lama(): void
    {
        $extractor = new ThemeZipExtractor();

        $hasil1 = $extractor->extract(['full_path' => $this->buatZipTema([
            'resources/views/template.blade.php' => '<div>v1</div>',
            'file_lama.txt'                      => 'harus hilang setelah upgrade',
        ])]);

        $this->assertTrue($hasil1['status'], $hasil1['data']);
        $this->assertFileExists($this->lokasiTema . '/file_lama.txt');

        $hasil2 = $extractor->extract(['full_path' => $this->buatZipTema([
            'resources/views/template.blade.php' => '<div>v2</div>',
        ])]);

        $this->assertTrue($hasil2['status'], $hasil2['data']);
        $this->assertFileDoesNotExist($this->lokasiTema . '/file_lama.txt', 'File stale dari versi lama harus terhapus');
        $this->assertSame('<div>v2</div>', file_get_contents($this->lokasiTema . '/resources/views/template.blade.php'));
    }

    public function test_zip_baru_tidak_valid_tidak_menghapus_tema_lama_yang_masih_berjalan(): void
    {
        $extractor = new ThemeZipExtractor();

        $hasil1 = $extractor->extract(['full_path' => $this->buatZipTema([
            'resources/views/template.blade.php' => '<div>versi berjalan</div>',
        ])]);
        $this->assertTrue($hasil1['status'], $hasil1['data']);

        $hasil2 = $extractor->extract(['full_path' => $this->buatZipTema([
            'entah.txt' => 'bukan tema',
        ])]);

        $this->assertFalse($hasil2['status']);
        $this->assertFileExists(
            $this->lokasiTema . '/resources/views/template.blade.php',
            'Tema lama yang masih berjalan tidak boleh hilang hanya karena unduhan baru ternyata tidak valid'
        );
        $this->assertSame('<div>versi berjalan</div>', file_get_contents($this->lokasiTema . '/resources/views/template.blade.php'));

        $this->assertSame([], glob(FCPATH . 'desa/themes/_staging_*') ?: []);
    }

    /**
     * @param array<string, string> $files path relatif di dalam folder tema => isi
     */
    private function buatZipTema(array $files): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('', true) . '-tema.zip';
        $this->zipSementara[] = $path;

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);
        $zip->addEmptyDir($this->namaTema);

        foreach ($files as $relatif => $isi) {
            $zip->addFromString($this->namaTema . '/' . $relatif, $isi);
        }

        $zip->close();

        return $path;
    }

    private function hapusRekursif(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;
            is_dir($path) ? $this->hapusRekursif($path) : @unlink($path);
        }

        @rmdir($dir);
    }
}
