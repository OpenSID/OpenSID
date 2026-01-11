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

use App\Models\Config;
use App\Models\Notifikasi;
use App\Models\Pamong;
use App\Models\Setting;
use App\Models\UserGrup;
use App\Models\Wilayah;
use App\Services\NotificationService;
use Illuminate\Support\Facades\View;
use Modules\Pelanggan\Services\CekService;
use Modules\Pelanggan\Services\PelangganService;

defined('BASEPATH') || exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{
    public $CI;
    public $grup;
    public $modul_ini;
    public $sub_modul_ini;
    public $header;
    public $aliasController;

    /**
     * @var CekService
     */
    public $premium;

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->CI = &get_instance();

        if (! auth('admin')->check()) {
            // untuk kembali ke halaman sebelumnya setelah login.
            $this->session->intended = current_url();

            redirect('siteman');
        }

        $this->cek_identitas_desa();
        PelangganService::perbaruiLangganan();

        $modules_list = $this->modules_list();

        View::share([
            'controller'   => $this->controller ?? $this->aliasController,
            'list_setting' => app('ci')->list_setting,
            'modul'        => $this->header['modul'],
            'modul_ini'    => $this->modul_ini,
            'notif'        => [
                'langganan'  => $this->header['notif_langganan'],
                'pengumuman' => $this->header['notif_pengumuman'],
            ],
            'notif_categories'     => NotificationService::getCategories(),
            'notif_counts'         => NotificationService::getNotificationCounts(auth('admin')->user()),
            'notif_list'           => NotificationService::getRecentNotifications(auth('admin')->user(), 10),
            'kategori_pengaturan'  => app('ci')->kategori_pengaturan,
            'sub_modul_ini'        => $this->sub_modul_ini,
            'akses_modul'          => $this->sub_modul_ini ?? $this->modul_ini,
            'perbaharui_langganan' => $this->header['perbaharui_langganan'] ?? null,
            'module_name'          => SebutanDesa($modules_list->firstWhere('slug', $this->sub_modul_ini ?? $this->modul_ini)->modul ?? null),
        ]);

        // logout other devices jika melakukan perubahan password
        $this->middleware->run('AuthenticateSession');

        // paksa logout setelah perubahan password
        if ($this->session->change_password && $this->controller !== 'pengguna') {
            return redirect('pengguna');
        }

        // paksa verifikasi email/telegram jika belum terverifikasi (hanya di production bukan demo mode)
        if (ENVIRONMENT === 'production' && ! config_item('demo_mode') && $this->controller !== 'pengguna') {
            $user = auth('admin')->user();
            $smtpConfigured = ! empty(setting('smtp_host')) && ! empty(setting('smtp_user'));

            // Tentukan apakah perlu verifikasi dan pesan yang sesuai
            if (! $smtpConfigured) {
                // SMTP tidak ada, wajib verifikasi Telegram
                $needsVerification = ! $user->hasVerifiedTelegram();
                $message = 'Silakan verifikasi akun Telegram Anda terlebih dahulu sebelum mengakses halaman lain. (Verifikasi email tidak tersedia karena SMTP belum dikonfigurasi)';
            } else {
                // SMTP ada, minimal salah satu harus terverifikasi
                $needsVerification = ! $user->hasVerifiedEmail() && ! $user->hasVerifiedTelegram();
                $message = 'Silakan verifikasi minimal salah satu (Email atau Telegram) terlebih dahulu sebelum mengakses halaman lain.';
            }

            if ($needsVerification) {
                return redirect_with('warning', $message, 'pengguna', true);
            }
        }
    }

    public function render($view, ?array $data = null): void
    {
        $this->load->view('header', $this->header);
        $this->load->view('nav');
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    public function modal_penandatangan()
    {
        return [
            'pamong'         => Pamong::penandaTangan()->get(),
            'pamong_ttd'     => Pamong::sekretarisDesa()->first(),
            'pamong_ketahui' => Pamong::kepalaDesa()->first(),
        ];
    }

    public function navigasi_peta()
    {
        return collect([
            'desa'      => identitas(),
            'wil_atas'  => identitas(),
            'dusun_gis' => Wilayah::dusun()->get(),
            'rw_gis'    => Wilayah::rw()->get(),
            'rt_gis'    => Wilayah::rt()->get(),
        ])->toArray();
    }

    protected function set_hak_akses_rfm()
    {
        // reset dulu session yang berkaitan hak akses ubah dan hapus
        $this->session->hapus_gambar_rfm       = false;
        $this->session->ubah_tambah_gambar_rfm = false;

        if (can('h')) {
            $this->session->hapus_gambar_rfm = true;
        }
        if (can('u')) {
            $this->session->ubah_tambah_gambar_rfm = true;
        }
    }

    private function modules_list()
    {
        return cache()->remember('settings_modules', 60 * 60, static fn () => Setting::get());
    }

    /*
     * Urutan pengecakan :
     *
     * 1. Config desa sudah diisi
     * 2. Validasi pelanggan premium
     * 3. Password standard (sid304)
     */
    private function cek_identitas_desa(): void
    {
        $kode_desa = empty(Config::appKey()->first()->kode_desa);

        if ($kode_desa && $this->controller != 'identitas_desa') {
            set_session('error', 'Identitas ' . ucwords(setting('sebutan_desa')) . ' masih kosong, silakan isi terlebih dahulu');

            redirect('identitas_desa');
        }

        $validasi = (new CekService())->validasi();
        $force    = $this->session->force_change_password;

        if ($force && $validasi && ! $kode_desa && $this->controller != 'pengguna') {
            redirect('pengguna#sandi');
        }

        // Kalau sehabis periksa data, paksa harus login lagi
        if (auth('admin_periksa')->check()) {
            auth('admin')->logout();
            auth('admin_periksa')->logout();

            redirect('siteman');
        }

        $this->header['desa']             = collect(identitas())->toArray();
        $this->header['notif_langganan']  = PelangganService::statusLangganan();
        $this->header['notif_pengumuman'] = ($kode_desa || $force) ? null : $this->cek_pengumuman();

        if (! config_item('demo_mode')) {
            // cek langganan premium
            $info_langganan = $this->cache->file->get_metadata('status_langganan');

            if (empty($info_langganan)
                || (strtotime('+30 day', $info_langganan['mtime']) < time())
                || ($info_langganan == false && setting('layanan_opendesa_token') != null)) {
                $this->header['perbaharui_langganan'] = true;
            }
        }
    }

    private function cek_pengumuman()
    {
        if (config_item('demo_mode') || ENVIRONMENT === 'development') {
            return null;
        }

        // Hanya untuk user administrator
        $this->grup = ci_auth()->id_grup;
        if ($this->grup == UserGrup::where('slug', UserGrup::ADMINISTRATOR)->first()->id) {
            $notifikasi = Notifikasi::semua();

            foreach ($notifikasi as $notif) {
                $pengumuman = Notifikasi::convert($notif);
                if ($notif['jenis'] == 'persetujuan') {
                    break;
                }
            }
        }

        return $pengumuman;
    }
}
