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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\FirebaseEnum;
use App\Models\Config;
use App\Models\FcmToken;
use App\Models\FcmTokenMandiri;
use App\Models\LogNotifikasiAdmin;
use App\Models\LogNotifikasiMandiri;
use App\Models\User;
use App\Traits\ProvidesConvenienceMethods;
use Illuminate\Support\Facades\DB;

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
    public $request;
    public $cek_anjungan;

    /**
     * @var string
     */
    public $controller;

    public function __construct()
    {
        parent::__construct();
        $error = $this->session->db_error;
        if ($error['code'] == 1049 && ! $this->db) {
            return;
        }

        $this->controller = strtolower($this->router->fetch_class());
        $this->request    = $this->input->post();

        $this->cekConfig();
    }

    // Bersihkan session cluster wilayah
    public function clear_cluster_session(): void
    {
        $cluster_session = ['dusun', 'rw', 'rt'];

        foreach ($cluster_session as $session) {
            $this->session->unset_userdata($session);
        }
    }

    private function cekConfig(): void
    {
        // jika belum install
        if (! file_exists(DESAPATH)) {
            redirect('install');
        }

        $this->load->database();

        // Tambahkan model yg akan diautoload di sini. Seeder di load disini setelah
        // installer berhasil dijalankan dengan kondisi folder desa sudah ada.
        $this->load->model(['seeders/seeder', 'setting_model']);

        $appKey   = get_app_key();
        $appKeyDb = Config::first();

        if (Config::count() === 0) {
            $this->session->cek_app_key = true;
            show_error('Silahkan tambah desa baru melalui console');
        } elseif (Config::count() > 1) {
            $appKeyDb = Config::appKey()->first();
        }

        if (! empty($appKeyDb->app_key) && $appKey !== $appKeyDb->app_key) {
            $this->session->cek_app_key = true;
            redirect('koneksi_database/config');
        }

        $this->setting_model->init();

        $this->cek_anjungan = $this->cekAnjungan();
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
            LogNotifikasiAdmin::insert($logs->toArray());
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

    private function cekAnjungan(): array
    {
        $ip         = $this->input->ip_address();
        $macAddress = $this->session->mac_address;

        try {
            return (array) DB::table('anjungan')->where(['ip_address' => $ip, 'status' => 1])
                ->orWhere('id_pengunjung', $_COOKIE['pengunjung'])
                ->when($macAddress, static function ($query) use ($macAddress) {
                    $query->orWhere('mac_address', $macAddress);
                })->orderBy('tipe')->first();
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
