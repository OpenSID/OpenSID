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

namespace Tests\Feature\Http\Controllers\Sso;

use Illuminate\Support\Facades\RateLimiter;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * @internal
 */
final class SsoLoginRateLimitTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('sso-login|127.0.0.1');
        RateLimiter::clear('sso-login|10.0.0.1');
        RateLimiter::clear('sso-login|10.0.0.2');
        RateLimiter::clear('sso-login|10.0.0.9');
    }

    #[Test]
    public function request_ke_11_dalam_satu_menit_dari_ip_yang_sama_dibatasi_429(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
                ->post(route('sso.login'), ['token' => "percobaan-{$i}"])
                ->assertStatus(302);
        }

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->post(route('sso.login'), ['token' => 'percobaan-11'])
            ->assertStatus(429);
    }

    #[Test]
    public function limit_diterapkan_per_ip_tidak_saling_memblokir(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
                ->post(route('sso.login'), ['token' => "percobaan-{$i}"])
                ->assertStatus(302);
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->post(route('sso.login'), ['token' => 'percobaan-11'])
            ->assertStatus(429);

        // IP berbeda belum pernah mencoba, sehingga tidak ikut terblokir.
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.2'])
            ->post(route('sso.login'), ['token' => 'percobaan-lain-ip'])
            ->assertStatus(302);
    }
}
