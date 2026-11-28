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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\FirebaseEnum;
use App\Enums\StatusEnum;
use App\Libraries\Database;
use App\Libraries\Tracker;
use App\Models\Config;
use App\Models\FcmToken;
use App\Models\FcmTokenMandiri;
use App\Models\LogNotifikasiAdmin;
use App\Models\LogNotifikasiMandiri;
use App\Models\PendudukMandiri;
use App\Models\SettingAplikasi;
use App\Models\User;
use App\Repositories\SettingAplikasiRepository;
use App\Traits\ProvidesConvenienceMethods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $loader
 * @property CI_Log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class MY_Controller extends CI_Controller
{
    use ProvidesConvenienceMethods;

    public $includes;
    public $theme;
    public $template;
    public \OpenSID\Middleware|null $middleware = null;

    /**
     * Ambil item dari array POST.
     *
     * @var array
     */
    public $request;

    /**
     * Daftar anjungan sesuai cookie atau mac addres.
     *
     * @var array
     */
    public $cek_anjungan;

    /**
     * Daftar setting aplikasi yang diambil dari database.
     *
     * @var App\Models\SettingAplikasi|Illuminate\Database\Eloquent\Collection
     */
    public $list_setting;

    /**
     * Daftar setting aplikasi.
     *
     * @var App\Models\SettingAplikasi|object
     */
    public $setting;

    /**
     * Nama controller yang sedang diakses.
     *
     * @var string
     */
    public $controller;

    public function __construct()
    {
        parent::__construct();

        // OpenSID/OpenSID#11931 Fase 3.5: daftarkan instance controller yang
        // BARU SELESAI dikonstruksi ke container Laravel SEBELUM baris apa pun
        // di bawah ini yang bisa memicu add-on (mis. checkMigration() ->
        // PenjagaPermintaan -> Modules\Pelanggan\Services\CekService) meminta
        // app()->make(\CI_Controller::class). Tanpanya, permintaan itu mencoba
        // membangun instance CI_Controller BARU lewat reflection container --
        // konstruksi kedua yang bertumpuk itu fatal di load_class('Session')
        // (Session dimuat lewat mekanisme khusus $this->load->library(), bukan
        // load_class() generik yang dipanggil ulang oleh foreach(is_loaded())
        // di CI_Controller::__construct() -- construction pertama aman karena
        // urutan normal, construction kedua/bertumpuk ini yang gagal).
        if (! app()->bound(\CI_Controller::class)) {
            app()->instance(\CI_Controller::class, $this);
        }

        if ($this->middleware === null) {
            $this->middleware = new OpenSID\Middleware();
        }

        // throttle requests
        $this->middleware->run('ThrottleRequests');

        $error = $this->session->db_error;
        if (isset($error['code']) && $error['code'] == 1049 && ! $this->db) {
            // Database sempat gagal pada request sebelumnya. Jangan langsung berhenti:
            // coba sambungkan ulang — database bisa saja sudah pulih. Jika berhasil,
            // bersihkan flag dan lanjutkan alur normal sehingga aplikasi sembuh sendiri
            // tanpa perlu logout/hapus cookie. Jika masih gagal, pertahankan perilaku
            // lama (berhenti) agar halaman koneksi_database tetap bisa diakses.
            try {
                $this->load->database();
            } catch (\Throwable) {
                // biarkan $this->db tetap null
            }

            if (! $this->db || ! $this->db->conn_id) {
                return;
            }

            $this->session->unset_userdata('db_error');
        }
        $this->controller = strtolower($this->router->fetch_class());
        $this->request    = $this->input->post();

        if (! $this->session->instalasi) {
            try {
                $desa = identitas();
            } catch (Exception) {
            }
        }

        View::share([
            'errors'      => $this->session->errors ?: new \Illuminate\Support\ViewErrorBag(),
            'ci'          => $this,
            'desa'        => $desa ?? null,
            'auth'        => auth('admin')->user(),
            'session'     => $this->session,
            'token_name'  => $this->security->get_csrf_token_name(),
            'token_value' => $this->security->get_csrf_hash(),
        ]);

        $this->cekConfig();

        SettingAplikasiRepository::applySettingCI($this);

        View::composer('*', function ($view): void {
            foreach (SettingAplikasi::$sensitiveKeys as $key) {
                unset($this->setting->{$key});
            }
        });

        if (! app()->environment('testing')) {
            (new Database())->checkMigration();
        }

        (new Tracker())->trackDesa();
    }

    public function create_log_notifikasi_admin($next, $isi): void
    {
        $users = User::whereHas('pamong', static function ($query) use ($next) {
            if ($next == 'verifikasi_sekdes') {
                return $query->where('jabatan_id', '=', sekdes()->id);
            }
            if ($next == 'verifikasi_kades') {
                return $query->where('jabatan_id', '=', kades()->id);
            }

            return $query->where('jabatan_id', '!=', kades()->id)->where('jabatan_id', '!=', sekdes()->id);
        })
            ->when($next != 'verifikasi_sekdes' && $next != 'verifikasi_kades', static fn ($query) => $query->orWhereNull('pamong_id'))
            ->get();

        if (is_array($isi) && $users->count() > 0) {
            $logs = $users->map(static function ($user) use ($isi): array {
                $data_user = ['id_user' => $user->id, 'config_id' => $user->config_id];

                return array_merge($data_user, $isi);
            });

            foreach ($logs as $log) {
                LogNotifikasiAdmin::create($log);
            }
        }
    }

    public function kirim_notifikasi_admin($next, $pesan, $judul, $payload = ''): void
    {
        $allToken = FcmToken::whereHas('user', static fn ($user) => $user->WhereHas('pamong', static function ($query) use ($next) {
            if ($next == 'verifikasi_sekdes') {
                return $query->where('jabatan_id', '=', sekdes()->id);
            }
            if ($next == 'verifikasi_kades') {
                return $query->where('jabatan_id', '=', kades()->id);
            }

            if ($next == 'all') {
                    return $query;
            }

            return $query->where('jabatan_id', '!=', kades()->id)->where('jabatan_id', '!=', sekdes()->id);
        })->when($next != 'verifikasi_sekdes' && $next != 'verifikasi_kades', static fn ($query) => $query->orWhereNull('pamong_id')))->get();

        if (cek_koneksi_internet()) {
            // kirim ke aplikasi android admin.
            try {
                $client       = new Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                $notification = new Fcm\Push\Notification();

                $notification
                    ->addRecipient($allToken->pluck('token')->all())
                    ->setTitle($judul)
                    ->setBody($pesan)
                    ->addData('payload', $payload);
                $client->send($notification);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }

        $isi = [
            'judul'      => $judul,
            'isi'        => $pesan,
            'payload'    => $payload,
            'read'       => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->create_log_notifikasi_admin($next, $isi);
    }

    public function create_log_notifikasi_penduduk($isi): void
    {
        if (is_array($isi)) {
            if (isset($isi['id_user_mandiri'])) {
                $exists = PendudukMandiri::where('id_pend', $isi['id_user_mandiri'])->exists();
                if (! $exists) {
                    return;
                }
            }
            LogNotifikasiMandiri::create($isi);
        }
    }

    public function kirim_notifikasi_penduduk($id_penduduk, $pesan, $judul, $payload = ''): void
    {
        $allToken = FcmTokenMandiri::where('id_user_mandiri', $id_penduduk)->get();

        if (cek_koneksi_internet()) {
            // kirim ke aplikasi android admin.
            try {
                $client       = new Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                $notification = new Fcm\Push\Notification();

                $notification
                    ->addRecipient($allToken->pluck('token')->all())
                    ->setTitle($judul)
                    ->setBody($pesan)
                    ->addData('payload', $payload);
                $client->send($notification);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }

        $isi = [
            'judul'           => $judul,
            'isi'             => $pesan,
            'payload'         => $payload,
            'read'            => 0,
            'id_user_mandiri' => $id_penduduk,
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        $this->create_log_notifikasi_penduduk($isi);
    }

    private function cekConfig(): void
    {
        // jika belum install
        if (! file_exists(DESAPATH)) {
            redirect('install');
        }

        try {
            $this->load->database();

            // Tambahkan model yg akan diautoload di sini. Seeder di load disini setelah
            // installer berhasil dijalankan dengan kondisi folder desa sudah ada.
            $this->load->model(['seeders/seeder']);

            $appKey   = get_app_key();
            $appKeyDb = Config::first();

            if (Config::count() === 0) {
                $this->session->cek_app_key = true;
                show_error('Silakan tambah desa baru melalui console');
            } elseif (Config::count() > 1) {
                $appKeyDb = Config::appKey()->first();
            }

            if (! empty($appKeyDb->app_key) && $appKey !== $appKeyDb->app_key) {
                $this->session->cek_app_key = true;
                redirect('koneksi_database/config');
            }

            $this->cek_anjungan = $this->cekAnjungan();
        } catch (\Throwable $e) {
            // Jika database tidak tersedia, arahkan ke halaman koneksi_database
            if (! $this->db || ! $this->db->conn_id) {
                logger()->error('Database connection error: ' . $e->getMessage());
                $_SESSION['db_error'] = ['code' => 1049, 'message' => $e->getMessage()];
                redirect('koneksi_database');
            }

            throw $e;
        }
    }

    /**
     * Daftar anjungan sesuai cookie atau mac addres.
     */
    private function cekAnjungan(): array
    {
        $macAddress   = $this->session->mac_address;
        $anjunganUuid = $this->session->anjungan_uuid;

        // Auto-restore dari Cookie jika session kosong tapi cookie ada
        $restoreFromCookie = false;
        if (! $anjunganUuid && isset($_COOKIE['anjungan_uuid'])) {
            $cookieUuid = $_COOKIE['anjungan_uuid'];
            if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $cookieUuid)) {
                $anjunganUuid      = $cookieUuid;
                $restoreFromCookie = true;
            }
        }

        // jika sesi tidak berisi pengenal apa pun, jangan ambil row generik
        if (! $macAddress && ! $anjunganUuid) {
            return [];
        }

        try {
            $data = DB::table('anjungan')
                ->where(static function ($query) use ($macAddress, $anjunganUuid) {
                    if ($macAddress) {
                        $query->orWhere('mac_address', $macAddress);
                    }
                    if ($anjunganUuid) {
                        $query->orWhere('uuid', $anjunganUuid);
                    }
                })
                ->where('status', StatusEnum::YA)
                ->where('config_id', identitas('id'))
                ->orderBy('tipe')
                ->first();

            if ($data && $restoreFromCookie) {
                $this->session->set_userdata('anjungan_uuid', $anjunganUuid);
            }

            if ($data) {
                $data->tipe = json_decode($data->tipe, true) ?? [];
            }

            return (array) ($data ?? []);
        } catch (Exception $e) {
            return [];
        }
    }
}

// Backend controller
require_once APPPATH . 'core/Admin_Controller.php';

// Frontend controller
require_once APPPATH . 'core/Web_Controller.php';

// Mandiri controller
require_once APPPATH . 'core/Mandiri_Controller.php';

// Api controller
require_once APPPATH . 'core/Api_Controller.php';

class Tte_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (! ci_auth()) {
            redirect('siteman');
        }
    }
}
