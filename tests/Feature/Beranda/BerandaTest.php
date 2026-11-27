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

namespace Tests\Feature\Beranda;

use App\Models\User;
use Tests\Traits\RefreshDatabase;
use Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;

class BerandaTest extends BaseTestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
    }

    #[Test]
    public function beranda_tanpa_login_redirect_ke_siteman(): void
    {
        $this->get('beranda')
            ->assertRedirect('siteman');
    }

    #[Test]
    public function beranda_dengan_login_berhasil_status_200(): void
    {
        $this->actingAs($this->user)
            ->get('beranda')
            ->assertStatus(200);
    }

    #[Test]
    public function beranda_response_content_type_html(): void
    {
        $this->actingAs($this->user)
            ->get('beranda')
            ->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    #[Test]
    public function beranda_dua_request_berturut_turut_tetap_200(): void
    {
        $this->actingAs($this->user)->get('beranda')->assertStatus(200);
        $this->actingAs($this->user)->get('beranda')->assertStatus(200);
    }
}
