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

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * @internal
 */
final class ServeFileControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    // —— Signature —-

    #[Test]
    public function tanpaSignatureMenolakAkses(): void
    {
        // Middleware `signed` Laravel menolak akses dengan 403.
        $this->get('storage-desa?path=upload/asset-test.txt')
            ->assertStatus(403);
    }

    // —— path harus berada di dalam folder upload/ —-

    #[Test]
    public function pathAppKeyDitolakMeskiSignatureValid(): void
    {
        $this->get($this->signedUrl(['path' => 'app_key']))
            ->assertStatus(404);
    }

    #[Test]
    public function pathConfigDatabasePhpDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'config/database.php']))
            ->assertStatus(404);
    }

    #[Test]
    public function pathArsipDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'arsip/surat_rahasia.pdf']))
            ->assertStatus(404);
    }

    #[Test]
    public function pathTraversalCollapseDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'upload/../app_key']))
            ->assertStatus(404);
    }

    #[Test]
    public function pathKosongDitolak(): void
    {
        $this->get($this->signedUrl(['path' => '']))
            ->assertStatus(404);
    }

    #[Test]
    public function pathDiFolderUploadMelayaniFile(): void
    {
        // Respons berupa StreamedResponse, isi tidak terbaca via assertSee;
        // verifikasi status, Content-Type, dan Content-Length.
        $this->get($this->signedUrl(['path' => 'upload/asset-test.txt']))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/plain; charset=utf-8')
            ->assertHeader('Content-Length', '15');
    }

    // —— area upload/ sebagai pengunci non-regresi dokumen publik —-

    #[Test]
    public function dokumenPublikDiAreaUploadTerlayaniDenganHeaderKeamanan(): void
    {
        Storage::disk('desa')->put('upload/dokumen/regulasi.pdf', 'isi-regulasi');

        $response = $this->get($this->signedUrl(['path' => 'upload/dokumen/regulasi.pdf']));

        $response->assertStatus(200);
        foreach (['no-store', 'no-cache', 'must-revalidate', 'max-age=0'] as $directive) {
            $this->assertStringContainsString($directive, $response->headers->get('Cache-Control', ''));
        }
        $this->assertStringContainsString(
            "default-src 'none'",
            $response->headers->get('Content-Security-Policy', ''),
        );
    }

    #[Test]
    public function signatureTermodifikasiDitolak403(): void
    {
        $url = $this->signedUrl(['path' => 'upload/dokumen/regulasi.pdf']);

        $this->get(preg_replace('/upload%2Fdokumen%2Fregulasi\.pdf/', 'upload%2Fdokumen%2Fdibajak.pdf', $url))
            ->assertStatus(403);
    }

    #[Test]
    public function appKeyDiDalamPrefixUploadTetapDitolak(): void
    {
        // File `upload/app_key` tidak ada di disk desa → tanpa fallback valid → 404.
        $this->get($this->signedUrl(['path' => 'upload/app_key']))
            ->assertStatus(404);
    }

    // —— area impor/ (template impor admin) —-

    #[Test]
    public function templateImporTerlayaniDenganContentDisposition(): void
    {
        Storage::disk('template')->put('impor/format.xlsx', 'konten-template');

        $response = $this->get($this->signedUrl(['path' => 'impor/format.xlsx']));

        $response->assertStatus(200);
        $this->assertStringContainsString('inline', $response->headers->get('Content-Disposition', ''));
    }

    #[Test]
    public function templateImporSkripPhpDitolak(): void
    {
        Storage::disk('template')->put('impor/skrip.php', '<?php echo "evil";');

        $this->get($this->signedUrl(['path' => 'impor/skrip.php']))
            ->assertStatus(404);
    }

    #[Test]
    public function templateImporTanpaEkstensiDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'impor/app_key']))
            ->assertStatus(404);
    }

    #[Test]
    public function templateImporTraversalDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'impor/../app_key']))
            ->assertStatus(404);

        $this->get($this->signedUrl(['path' => 'impor/..']))
            ->assertStatus(404);
    }

    #[Test]
    public function templateImporTidakAdaDitolak(): void
    {
        $this->get($this->signedUrl(['path' => 'impor/format-tidak-ada.xlsx']))
            ->assertStatus(404);
    }

    // —— fallback `default` menerapkan whitelist yang sama —-

    #[Test]
    public function fallbackDefaultAppKeyDariDiskDesaDitolak(): void
    {
        $this->get($this->signedUrl([
            'path'        => 'upload/tidak-ada.jpg',
            'default'     => 'app_key',
            'defaultDisk' => 'desa',
        ]))->assertStatus(404);
    }

    #[Test]
    public function fallbackDefaultConfigDatabasePhpDitolak(): void
    {
        $this->get($this->signedUrl([
            'path'        => 'upload/tidak-ada.jpg',
            'default'     => 'config/database.php',
            'defaultDisk' => 'desa',
        ]))->assertStatus(404);
    }

    #[Test]
    public function fallbackDefaultDiskTidakDiizinkanDitolak(): void
    {
        $this->get($this->signedUrl([
            'path'        => 'upload/tidak-ada.jpg',
            'default'     => 'images/404-image-not-found.jpg',
            'defaultDisk' => 'local',
        ]))->assertStatus(404);
    }

    #[Test]
    public function fallbackKeAssetDiDiskAssetsMelayaniFile(): void
    {
        $this->get($this->signedUrl([
            'path'        => 'upload/tidak-ada.jpg',
            'default'     => 'images/404-image-not-found.jpg',
            'defaultDisk' => 'assets',
        ]))->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Disk desa difake dan diisi file sensitif plus file upload untuk pengujian.
        Storage::fake('desa');
        Storage::disk('desa')->put('app_key', 'base64:secret-app-key');
        Storage::disk('desa')->put('config/database.php', "<?php\n\$db['default']['password'] = 'secret-db-password';");
        Storage::disk('desa')->put('arsip/surat_rahasia.pdf', 'secret-arsip');
        Storage::disk('desa')->put('upload/asset-test.txt', 'isi-file-upload');

        // Disk template difake agar test tidak menyentuh storage asli.
        Storage::fake('template');
    }

    private function signedUrl(array $params): string
    {
        return URL::signedRoute('storage.desa', $params);
    }
}
