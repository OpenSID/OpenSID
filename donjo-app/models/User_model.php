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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\LoginAttempts;
use App\Models\LogLogin;
use App\Models\User;
use App\Models\UserGrup;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge         $dbforge
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $load
 * @property CI_Loader           $loader
 * @property CI_log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class User_model extends MY_Model
{
    private ?string $_username = null;
    private ?string $_password = null;

    // Konfigurasi untuk library 'upload'
    protected array $uploadConfig;
    protected $larangan_demo = [
        'database' => ['h'],
    ];

    public function __construct()
    {
        parent::__construct();
        // Untuk dapat menggunakan library upload
        $this->load->library('MY_Upload', null, 'upload');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_USER_PICT,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        $this->load->model('grup_model');
        // Untuk password hashing
        $this->load->helper('password');
        // Helper Tulis file
        $this->load->helper('file');
    }

    public function siteman()
    {
        $this->_username = $username = trim($this->input->post('username'));
        $this->_password = $password = trim($this->input->post('password'));
        $ip_address      = $this->input->ip_address();

        if (config_item('demo_mode') && ($username == config_item('demo_user')['username'] && $password == config_item('demo_user')['password'])) {
            // Ambil data user pertama yang merupakan admin
            $user = User::superAdmin()->first();

            return $this->setLogin($user);
        }

        if ($this->is_max_login_attempts_exceeded($this->_username, $ip_address)) {
            $this->session->siteman = -1;

            $this->session->set_flashdata('time_block', $this->get_last_attempt_time($this->_username, $ip_address));

            return false;
        }

        $user = User::where('username', $username)->status()->first();

        // Cek hasil query ke db, ada atau tidak data user ybs.
        $pwMasihMD5 = $user && ((strlen($user->password) == 32) && (stripos($user->password, '$') === false));

        $authLolos = $pwMasihMD5
            ? (md5($password) == $user->password)
            : password_verify($password, $user->password);

        // Login gagal: user tidak ada atau tidak lolos verifikasi
        if ($user === false || $authLolos === false) {
            $this->session->siteman = -1;
            $this->increase_login_attempts($this->_username, $ip_address);

            return false;
        }
        // Login sukses: ubah pass di db ke bcrypt jika masih md5 dan set session

        if ($pwMasihMD5) {
            // Ganti pass md5 jadi bcrypt
            $pwBcrypt = generatePasswordHash($password);

            // Modifikasi panjang karakter di kolom user.password menjadi 100 untuk -
            // backward compatibility dengan kolom di database lama yang hanya 40 karakter.
            // Hal ini menyebabkan string bcrypt (yang default lengthnya 60 karakter) jadi -
            // terpotong sehingga $authLolos selalu mereturn FALSE.
            $this->db->query('ALTER TABLE user MODIFY COLUMN password VARCHAR(100) NOT NULL');
            // Lanjut ke update password di database
            $this->config_id()->where('id', $user->id)->update('user', ['password' => $pwBcrypt]);
        }
        // Lanjut set session
        if ($this->db->table_exists('login_attempts')) {
            $this->clear_login_attempts($this->_username, $ip_address);
        }

        if (($user->id_grup == $this->user_model->id_grup(UserGrup::REDAKSI)) && ($this->setting->offline_mode >= 2)) {
            $this->session->siteman = -2;
        } else {
            return $this->setLogin($user);
        }
    }

    private function setLogin($user): void
    {
        $this->session->siteman      = 1;
        $this->session->sesi         = $user->session;
        $this->session->user         = $user->id;
        $this->session->nama         = $user->nama;
        $this->session->grup         = $user->id_grup;
        $this->session->per_page     = 10;
        $this->session->siteman_wait = 0;
        $this->session->siteman_try  = 4;
        $this->session->fm_key       = $this->set_fm_key($user->id . $user->id_grup . $user->sesi);
        $this->session->isAdmin      = $user;
        $this->last_login($user->id);

        $log_login = LogLogin::create([
            'username'   => $user->nama,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'referer'    => $_SERVER['HTTP_REFERER'],
            'lainnya'    => geoip_info($this->input->ip_address()),
        ]);

        if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
            $this->load->library('Telegram/telegram');
            $country = $log_login->lainnya['country'] ?? ' tidak diketahui';

            if ($country != 'Indonesia') {
                try {
                    $this->telegram->sendMessage([
                        'text' => <<<EOD
                                Terindefikasi login mencurigakan dari {$user->nama} dengan lokasi {$country}.
                            EOD,
                        'parse_mode' => 'Markdown',
                        'chat_id'    => $this->setting->telegram_user_id,
                    ]);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }

            try {
                $this->telegram->sendMessage([
                    'text'       => sprintf('%s login Halaman Admin %s pada tanggal %s', $user->nama, APP_URL, tgl_indo2(date('Y-m-d H:i:s'))),
                    'parse_mode' => 'Markdown',
                    'chat_id'    => $this->setting->telegram_user_id,
                ]);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }

    private function set_fm_key($key = null)
    {
        $fmHash = $key . date('Ymdhis');
        $salt   = random_int(100000, 999999);
        $salt   = strrev($salt);

        return md5($fmHash . 'OpenSID' . $salt);
    }

    //mengupdate waktu login
    private function last_login($id = '')
    {
        return User::where('id', $id)->update(['last_login' => Carbon::now()]);
    }

    //Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus
    public function syarat_sandi()
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $this->_password));
    }

    public function sesi_grup($sesi = '')
    {
        return $this->config_id()
            ->select('id_grup')
            ->where('session', $sesi)
            ->get('user')
            ->row()
            ->id_grup;
    }

    public function id_grup($nama)
    {
        return $this->config_id()
            ->select('id')
            ->where('slug', $nama)
            ->get('user_grup')
            ->row()
            ->id;
    }

    public function logout(): void
    {
        // Hapus session -- semua session variable akan terhapus
        $this->session->sess_destroy();
    }

    public function get_user($id = 0)
    {
        $sql   = 'SELECT * FROM user WHERE id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    private function periksa_input_password($id)
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';
        $password                 = $this->input->post('pass_lama');
        $pass_baru                = $this->input->post('pass_baru');
        $pass_baru1               = $this->input->post('pass_baru1');
        $data                     = [];

        // Jangan edit password admin apabila di situs demo
        if ($id == $this->user_model->id_grup(UserGrup::ADMINISTRATOR) && config_item('demo_mode')) {
            unset($data['password']);

            return $data;
        }

        // Ganti password
        if ($this->input->post('pass_lama') != '' || $pass_baru != '' || $pass_baru1 != '') {
            $row = $this->config_id()
                ->select('password, username, id_grup, session')
                ->where('id', $id)
                ->get('user')
                ->row();

            // Cek input password
            if (password_verify($password, $row->password) === false) {
                $this->session->error_msg .= ' -> Kata sandi lama salah<br />';
            }

            if (empty($pass_baru1)) {
                $this->session->error_msg .= ' -> Kata sandi baru tidak boleh kosong<br />';
            }

            if ($pass_baru != $pass_baru1) {
                $this->session->error_msg .= ' -> Kata sandi baru tidak cocok<br />';
            }

            if ($this->session->error_msg !== '') {
                $this->session->success = -1;
            }
            // Cek input password lolos
            else {
                $this->session->success = 1;
                // Buat hash password
                $pwHash = generatePasswordHash($pass_baru);
                // Cek kekuatan hash lolos, simpan ke array data
                $data['password'] = $pwHash;
            }
        }

        return $data;
    }

    /**
     * Update user's settings
     *
     * @param int $id Id user di database
     */
    public function update_setting($id = 0): void
    {
        $data = $this->periksa_input_password($id);

        $data['nama']           = alfanumerik_spasi($this->input->post('nama'));
        $data['notif_telegram'] = (int) $this->input->post('notif_telegram');
        $data['id_telegram']    = alfanumerik(empty($this->input->post('id_telegram')) ? 0 : $this->input->post('id_telegram'));

        // Update foto
        $data['foto'] = $this->urusFoto($id);
        $hasil        = $this->config_id()->where('id', $id)->update('user', $data);

        // Untuk Blade
        $this->session->isAdmin = User::findOrFail($id);

        status_sukses($hasil, true);
    }

    //!===========================================================
    //! Helper Methods
    //!===========================================================

    /**
     * - success: nama berkas yang diunggah
     * - fail: nama berkas lama, kalau ada
     *
     * @param mixed $idUser
     */
    public function urusFoto($idUser = '')
    {
        if ($idUser) {
            $berkasLama       = $this->config_id()->select('foto')->where('id', $idUser)->get('user')->row();
            $berkasLama       = is_object($berkasLama) ? $berkasLama->foto : 'kuser.png';
            $lokasiBerkasLama = $this->uploadConfig['upload_path'] . 'kecil_' . $berkasLama;
            $lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);
        } else {
            $berkasLama = 'kuser.png';
        }

        $nama_foto = $this->uploadFoto('foto', 'man_user');

        if (! empty($nama_foto)) {
            // Ada foto yang berhasil diunggah --> simpan ukuran 100 x 100
            $tipe_file = TipeFile($_FILES['foto']);
            $dimensi   = ['width' => 100, 'height' => 100];
            resizeImage(LOKASI_USER_PICT . $nama_foto, $tipe_file, $dimensi);
            // Nama berkas diberi prefix 'kecil'
            $nama_kecil  = 'kecil_' . $nama_foto;
            $fileRenamed = rename(
                LOKASI_USER_PICT . $nama_foto,
                LOKASI_USER_PICT . $nama_kecil
            );
            if ($fileRenamed) {
                $nama_foto = $nama_kecil;
            }
            // Hapus berkas lama
            if ($berkasLama && $berkasLama !== 'kecil_kuser.png') {
                unlink($lokasiBerkasLama);
                if (file_exists($lokasiBerkasLama)) {
                    $this->session->success = -1;
                }
            }
        }

        return null === $nama_foto ? $berkasLama : str_replace('kecil_', '', $nama_foto);
    }

    /**
     * - success: nama berkas yang diunggah
     * - fail: NULL
     *
     * @param mixed $lokasi
     * @param mixed $redirect
     */
    private function uploadFoto(string $lokasi, string $redirect)
    {
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$lokasi]['name']);
        if (! $adaBerkas) {
            return null;
        }

        if ((strlen($_FILES[$lokasi]['name']) + 20) >= 100) {
            set_session('error', 'Nama berkas foto terlalu panjang, maksimal 80 karakter. ' . session('flash_error_msg'));
            redirect($redirect);
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        $this->upload->initialize($this->uploadConfig);
        // Upload sukses
        if ($this->upload->do_upload($lokasi)) {
            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaClean    = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $this->uploadConfig['upload_path'] . $uploadData['file_name'],
                $this->uploadConfig['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
        }
        // Upload gagal
        else {
            $this->session->success   = -1;
            $this->session->error_msg = $this->upload->display_errors(null, null);
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    /**
     * is_max_login_attempts_exceeded
     * Based on code from CodeIgniter-Ion-Auth, by benedmunds (https://github.com/benedmunds/CodeIgniter-Ion-Auth/blob/3/models/Ion_auth_model.php)
     *
     * @param string $identity   user's identity
     * @param mixed  $ip_address
     *
     * @return bool
     */
    public function is_max_login_attempts_exceeded($identity, $ip_address)
    {
        if ($this->db->table_exists('login_attempts')) {
            $max_attempts = config_item('maximum_login_attempts');
            if ($max_attempts > 0) {
                $attempts = $this->get_attempts_num($identity, $ip_address);

                return $attempts >= $max_attempts;
            }
        }

        return false;
    }

    /**
     * Get number of login attempts for the given IP-address or identity
     * Based on code from CodeIgniter-Ion-Auth, by benedmunds (https://github.com/benedmunds/CodeIgniter-Ion-Auth/blob/3/models/Ion_auth_model.php)
     *
     * @param string $identity   User's identity
     * @param mixed  $ip_address
     *
     * @return int
     */
    public function get_attempts_num($identity, $ip_address)
    {
        if ($this->get_last_attempt_time($identity, $ip_address) <= (time() - config_item('lockout_time'))) {
            $this->clear_login_attempts($identity, $ip_address);
        }

        return LoginAttempts::select('1')
            ->where('username', $identity)
            ->where('ip_address', $ip_address)
            ->count();
    }

    /**
     * Based on code from CodeIgniter-Ion-Auth, by benedmunds (https://github.com/benedmunds/CodeIgniter-Ion-Auth/blob/3/models/Ion_auth_model.php)
     *
     * @param string $identity   User's identity
     * @param mixed  $ip_address
     */
    public function increase_login_attempts($identity, $ip_address): void
    {
        if ($this->db->table_exists('login_attempts')) {
            LoginAttempts::create([
                'config_id'  => $this->config_id,
                'username'   => $identity,
                'time'       => time(),
                'ip_address' => $ip_address,
            ]);
            $count   = $this->get_attempts_num($identity, $ip_address);
            $message = 'LOGIN GAGAL.<br>NAMA PENGGUNA ATAU KATA SANDI YANG ANDA MASUKKAN SALAH!<br>KESEMPATAN MENCOBA ' . (config_item('maximum_login_attempts') - $count) . ' KALI LAGI';

            if ($this->is_max_login_attempts_exceeded($identity, $ip_address)) {
                $this->session->set_flashdata('time_block', $this->get_last_attempt_time($this->_username, $ip_address));
                $message = 'LOGIN GAGAL.<br>NAMA PENGGUNA ATAU KATA SANDI YANG ANDA MASUKKAN SALAH!';
                if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                    $this->load->library('Telegram/telegram');

                    try {
                        $this->telegram->sendMessage([
                            'text' => <<<EOD
                                    Percobaan login gagal sebanyak 3 kali dengan input nama pengguna {$identity} dan IP Address {$ip_address}.
                                EOD,
                            'parse_mode' => 'Markdown',
                            'chat_id'    => $this->setting->telegram_user_id,
                        ]);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
            }
            $this->session->set_flashdata('attempts_error', $message);
        }
    }

    public function get_last_attempt_time($identity, $ip_address)
    {
        if ($this->db->table_exists('login_attempts')) {
            $last_try = LoginAttempts::where('username', $identity)
                ->where('ip_address', $ip_address)
                ->orderBy('id', 'DESC')
                ->first();

            return $last_try->time;
        }

        return time() - config_item('lockout_time');
    }

    public function clear_login_attempts($identity, $ip_address)
    {
        if ($this->db->table_exists('login_attempts')) {
            return LoginAttempts::where('username', $identity)->where('ip_address', $ip_address)->delete();
        }
    }

    // get super admin
    public function get_super_admin()
    {
        return $this->config_id()
            ->select('id')
            ->where('id_grup', $this->user_model->id_grup(UserGrup::ADMINISTRATOR))
            ->get('user')
            ->row()
            ->id;
    }
}
