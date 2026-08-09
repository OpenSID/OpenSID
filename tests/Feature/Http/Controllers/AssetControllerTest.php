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
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * @internal
 */
final class AssetControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    // —— theme_asset: LFI melalui fallback `default` harus ditolak —-

    #[Test]
    public function themeAssetMenolakDefaultAppKeyDariDiskDesa(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=app_key&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultConfigDatabasePhpDariDiskDesa(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=config/database.php&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultConfigConfigPhpDariDiskDesa(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=config/config.php&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultFileDotEnv(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=.env&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultPathTraversal(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=../../../../etc/passwd&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultDenganEkstensiPhp(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=config.php&defaultDisk=assets')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDiskYangTidakDiizinkan(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=images/404-image-not-found.jpg&defaultDisk=local')
            ->assertStatus(404);
    }

    #[Test]
    public function themeAssetMenolakDefaultKosong(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css')
            ->assertStatus(404);
    }

    // —— theme_asset: file aset yang sah tetap dilayani —-

    #[Test]
    public function themeAssetMelayaniFileAssetYangAda(): void
    {
        $this->get('theme_asset/esensi?file=css/style.css')
            ->assertStatus(200);
    }

    #[Test]
    public function themeAssetFallbackKeAssetDiDiskAssets(): void
    {
        $this->get('theme_asset/esensi?file=does-not-exist.css&default=images/404-image-not-found.jpg&defaultDisk=assets')
            ->assertStatus(200);
    }

    // —— module_asset: pola yang sama —-

    #[Test]
    public function moduleAssetMenolakDefaultAppKeyDariDiskDesa(): void
    {
        $this->get('module_asset/anjungan?file=does-not-exist.css&default=app_key&defaultDisk=desa')
            ->assertStatus(404);
    }

    #[Test]
    public function moduleAssetMelayaniFileAssetYangAda(): void
    {
        $this->get('module_asset/anjungan?file=css/style.css')
            ->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Disk desa difake dan diisi file sensitif supaya pengujian membuktikan
        // whitelist benar-benar menolaknya, bukan sekadar karena file tidak ada.
        Storage::fake('desa');
        Storage::disk('desa')->put('app_key', 'base64:secret-app-key');
        Storage::disk('desa')->put('.env', 'APP_KEY=secret-env');
        Storage::disk('desa')->put('config/database.php', "<?php\n\$db['default']['password'] = 'secret-db-password';");
        Storage::disk('desa')->put('config/config.php', "<?php\n\$config['smtp_pass'] = 'secret-smtp';");
        Storage::disk('desa')->put('arsip/surat_rahasia.pdf', 'secret-arsip');
    }
}
