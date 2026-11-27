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

namespace Tests\Feature\Auth;

use App\Enums\AktifEnum;
use App\Models\User;
use App\Models\UserGrup;
use App\Repositories\SettingAplikasiRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi: pesan penonaktifan akun karena masa aktif tidak konsisten.
 *
 * Bug: percobaan login pertama menampilkan pesan masa aktif, sedangkan
 * percobaan berikutnya menampilkan pesan kredensial salah karena akun yang
 * sudah dinonaktifkan tidak lagi ditemukan oleh query berfilter status aktif.
 *
 * Fix: cek masa aktif di dalam proses authenticate (closure) tanpa filter
 * status aktif, sehingga pesan yang sama tampil di setiap percobaan login.
 */
class MasaAktifAkunTest extends BaseTestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Rahasia123!';

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->setSetting('masa_akun_pengguna', 1);
        $this->setSetting('masa_akun_tidak_aktif', 30);
    }

    protected function tearDown(): void
    {
        app(SettingAplikasiRepository::class)->forgetResolvedSetting();
        parent::tearDown();
    }

    private function setSetting(string $key, mixed $value): void
    {
        $tersimpan = app(SettingAplikasiRepository::class)->updateWithKey($key, $value);
        $this->assertTrue($tersimpan, "Setting {$key} tidak ditemukan");
    }

    private function buatPengguna(array $atribut = []): User
    {
        return User::create(array_merge([
            'username'   => 'operator_dormant',
            'password'   => bcrypt(self::PASSWORD),
            'id_grup'    => UserGrup::getGrupId(UserGrup::OPERATOR),
            'active'     => AktifEnum::AKTIF,
            'nama'       => 'Operator Dormant',
            'last_login' => Carbon::now()->subDays(40),
        ], $atribut));
    }

    private function kredensial(User $user, string $password = self::PASSWORD): array
    {
        return ['username' => $user->username, 'password' => $password];
    }

    private function pesanMasaAktif(): string
    {
        return 'Login gagal. Akun Anda telah dinonaktifkan karena tidak digunakan selama lebih dari 30 hari. Silakan hubungi administrator untuk mengaktifkan kembali.';
    }

    #[Test]
    public function login_akun_dormant_menonaktifkan_akun_dengan_pesan_masa_aktif(): void
    {
        $pengguna = $this->buatPengguna();

        $response = $this->from('siteman')->post('siteman/auth', $this->kredensial($pengguna));

        $response->assertRedirect('siteman');
        $response->assertSessionHas('notif', $this->pesanMasaAktif());
        $this->assertGuest('admin');
        $this->assertSame(AktifEnum::TIDAK_AKTIF, (int) $pengguna->fresh()->active);
    }

    #[Test]
    public function login_ulang_akun_dormant_menampilkan_pesan_yang_sama(): void
    {
        $pengguna = $this->buatPengguna();

        $this->from('siteman')->post('siteman/auth', $this->kredensial($pengguna));
        $response = $this->from('siteman')->post('siteman/auth', $this->kredensial($pengguna));

        $response->assertRedirect('siteman');
        $response->assertSessionHas('notif', $this->pesanMasaAktif());
        $this->assertGuest('admin');
        $this->assertSame(AktifEnum::TIDAK_AKTIF, (int) $pengguna->fresh()->active);
    }

    #[Test]
    public function login_akun_aktif_terbaru_berhasil(): void
    {
        $pengguna = $this->buatPengguna(['last_login' => Carbon::now()]);

        $response = $this->from('siteman')->post('siteman/auth', $this->kredensial($pengguna));

        $response->assertRedirect('main');
        $this->assertAuthenticated('admin');
        $this->assertSame(AktifEnum::AKTIF, (int) $pengguna->fresh()->active);
    }

    #[Test]
    public function login_akun_dormant_dengan_password_salah_tidak_menonaktifkan_akun(): void
    {
        $pengguna = $this->buatPengguna();

        $response = $this->from('siteman')->post('siteman/auth', $this->kredensial($pengguna, 'Salah123!'));

        $response->assertSessionHasErrors('email');
        $response->assertSessionMissing('notif');
        $this->assertGuest('admin');
        $this->assertSame(AktifEnum::AKTIF, (int) $pengguna->fresh()->active);
    }
}
