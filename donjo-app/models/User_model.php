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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\LoginAttempts;
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
    private $_username;
    private $_password;

    // Konfigurasi untuk library 'upload'
    protected $uploadConfig  = [];
    protected $larangan_demo = [
        'database' => ['h'],
    ];

    public function __construct()
    {
        parent::__construct();
        // Untuk dapat menggunakan library upload
        $this->load->library('upload');
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
            $user = User::first();

            return $this->setLogin($user);
        }

        if ($this->is_max_login_attempts_exceeded($this->_username, $ip_address)) {
            $this->session->siteman = -1;

            $this->session->set_flashdata('time_block', $this->get_last_attempt_time($this->_username, $ip_address));

            return false;
        }

        $user = User::where('username', $username)->status()->first();

        // Cek hasil query ke db, ada atau tidak data user ybs.
        $pwMasihMD5 = $user ?
            (
                (strlen($user->password) == 32) && (stripos($user->password, '$') === false)
            ) : false;

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

    private function setLogin($user)
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

        if (! empty($this->setting->telegram_token) && cek_koneksi_internet()) {
            $this->load->library('Telegram/telegram');

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
        $salt   = mt_rand(100000, 999999);
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

    public function logout()
    {
        // Hapus session -- semua session variable akan terhapus
        $this->session->sess_destroy();
    }

    public function autocomplete()
    {
        $sql   = "SELECT username FROM user WHERE config_id = {$this->config_id} UNION SELECT nama FROM user WHERE config_id = {$this->config_id}";
        $query = $this->db->query($sql);
        $data  = $query->result_array();

        $out = '';

        for ($i = 0; $i < count($data); $i++) {
            $out .= ",'" . $data[$i]['username'] . "'";
        }

        return '[' . strtolower(substr($out, 1)) . ']';
    }

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('u.username', $cari)
                ->or_like('u.nama', $cari)
                ->group_end();
        }

        return $this->db;
    }

    private function filter_sql()
    {
        if ($filter = $this->session->filter) {
            switch ($filter) {
                case 'active':
                    $this->db->where('u.active', 1);
                    break;

                case 'inactive':
                    $this->db->where('u.active', 0);
                    break;

                default:
                    break;
            }
        }

        if ($group = $this->session->group) {
            $this->db->where('u.id_grup', $group);
        }

        return $this->db;
    }

    public function paging($page = 1, $o = 0)
    {
        $this->load->library('paging');
        $cfg['page']     = $page;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $this->list_data_sql()->select('count(u.id) as jml')->get()->row()->jml;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $this->config_id('u')
            ->from('user u')
            ->join('tweb_desa_pamong p', 'u.pamong_id = p.pamong_id', 'left')
            ->join('user_grup g', 'u.id_grup = g.id');

        $this->search_sql();
        $this->filter_sql();

        return $this->db;
    }

    public function list_data($order = 0, $offset = 0, $limit = 500)
    {
        // Ordering sql
        switch ($order) {
            case 1:
                $this->db->order_by('u.username');
                break;

            case 2:
                $this->db->order_by('u.username', 'desc');
                break;

            case 3:
                $this->db->order_by('u.nama');
                break;

            case 4:
                $this->db->order_by('u.nama', 'desc');
                break;

            case 5:
                $this->db->order_by('g.nama');
                break;

            case 6:
                $this->db->order_by('g.nama', 'desc');
                break;

            default:
                $this->db->order_by('u.username');
        }

        $this->list_data_sql();

        $data = $this->db
            ->select('u.*, p.pamong_status, g.nama as grup')
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        // Formating output
        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    /**
     * Insert user baru ke database
     *
     * @return void
     */
    public function insert()
    {
        $this->session->error_msg = null;
        $this->session->success   = 1;

        $data = $this->sterilkan_input($this->input->post());

        if (empty($data['pamong_id'])) {
            $data['pamong_id'] = null;
        }

        $pwHash           = generatePasswordHash($data['password']);
        $data['password'] = $pwHash;
        $data['session']  = md5(now());

        $data['foto']           = $this->urusFoto();
        $data['nama']           = strip_tags($data['nama']);
        $data['notif_telegram'] = (int) $data['notif_telegram'];
        $data['id_telegram']    = (int) $data['id_telegram'];
        $data['config_id']      = $this->config_id;

        if (! $this->db->insert('user', $data)) {
            session_error(' -> Gagal menambahkan data ke database');
        }
    }

    private function sterilkan_input($post)
    {
        $data             = [];
        $data['password'] = $post['password'];
        $data['active']   = (int) $post['aktif'];
        if (isset($post['username']) && ! empty($post['username'])) {
            $data['username'] = alfanumerik($post['username']);
        }
        if (isset($post['nama'])) {
            $data['nama'] = nama($post['nama']);
        }
        if (isset($post['email'])) {
            $data['phone'] = htmlentities($post['phone']);
        }
        if (isset($post['email']) && ! empty($post['email'])) {
            $data['email'] = htmlentities($post['email']);
        }
        if (isset($post['id_grup'])) {
            $data['id_grup'] = $post['id_grup'];
        }
        if (isset($post['pamong_id'])) {
            $data['pamong_id'] = $post['pamong_id'];
        }
        if (isset($post['foto'])) {
            $data['foto'] = $post['foto'];
        }

        if (isset($post['notif_telegram'])) {
            $data['notif_telegram'] = (int) $post['notif_telegram'];
        }

        if (isset($post['id_telegram'])) {
            $data['id_telegram'] = (int) $post['id_telegram'];
        }

        return $data;
    }

    /**
     * Update data user
     *
     * @param int $idUser Id user di database
     *
     * @return void
     */
    public function update($idUser)
    {
        $this->session->error_msg = null;
        $this->session->success   = 1;

        $data = $this->sterilkan_input($this->input->post());

        if (empty($data['pamong_id'])) {
            $data['pamong_id'] = null;
        }

        if (empty($idUser)) {
            session_error(' -> Pengguna tidak ditemukan datanya.');
            redirect('man_user');
        }

        if (empty($data['username']) || empty($data['nama']) || ! in_array((int) ($data['id_grup']), $this->grup_model->list_id_grup())) {
            session_error(' -> Nama, Username dan Kata Sandi harus diisi');
            redirect('man_user');
        }

        // radiisi menandakan password tidak diubah
        if ($data['password'] == '') {
            unset($data['password']);
        }
        // Untuk demo jangan ubah username atau password
        if ($idUser == $this->user_model->id_grup(UserGrup::ADMINISTRATOR) && (config_item('demo_mode') || ENVIRONMENT === 'development')) {
            unset($data['username'], $data['password']);
        }
        if ($data['password']) {
            $pwHash           = generatePasswordHash($data['password']);
            $data['password'] = $pwHash;
        }

        // cek pamong apakah sudah mempunyai user atau belum
        if ($data['pamong_id'] != null && $data['pamong_id'] != '') {
            $pamong = $this->config_id()->where('pamong_id', (int) $data['pamong_id'])->where('id != ', $idUser)->get('user')->num_rows();
            if ($pamong > 0) {
                session_error(' -> Pamong sudah dipilih oleh user lainnya. Silahkan pilih Pamong Lainnya');
                redirect('man_user');
            }
        }

        $data['foto'] = $this->urusFoto($idUser);
        if (! $this->config_id()->where('id', $idUser)->update('user', $data)) {
            session_error(' -> Gagal memperbarui data di database');
        }

        // perbaharui session login
        if ((string) $idUser === (string) $this->session->isAdmin->id) {
            $this->session->isAdmin = User::find($idUser);
        }

        $this->cache->file->delete("{$idUser}_cache_modul");
    }

    public function delete($idUser = '', $semua = false)
    {
        // Jangan hapus admin
        if ($idUser == $this->user_model->id_grup(UserGrup::ADMINISTRATOR)) {
            return;
        }

        if (! $semua) {
            $this->session->success   = 1;
            $this->session->error_msg = '';
        }

        $foto  = $this->config_id()->get_where('user', ['id' => $idUser])->row()->foto;
        $hasil = $this->config_id()->where('id', $idUser)->delete('user');
        // Cek apakah pengguna berhasil dihapus
        if ($hasil) {
            // Cek apakah pengguna memiliki foto atau tidak
            if ($foto != 'kuser.png') {
                // Ambil nama foto
                $foto = basename(AmbilFoto($foto));
                // Cek penghapusan foto pengguna
                if (! unlink(LOKASI_USER_PICT . $foto)) {
                    $this->session->error_msg = 'Gagal menghapus foto pengguna';
                    $this->session->success   = -1;
                }
            }
        } else {
            $this->session->error_msg = 'Gagal menghapus pengguna';
            $this->session->success   = -1;
        }
    }

    public function delete_all()
    {
        $this->session->success   = 1;
        $this->session->error_msg = '';

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function user_lock($id = '', $val = 0)
    {
        $hasil                  = $this->config_id()->where('id', $id)->update('user', ['active' => $val]);
        $this->session->success = ($hasil === true ? 1 : -1);
    }

    public function get_user($id = 0)
    {
        $sql   = 'SELECT * FROM user WHERE id = ?';
        $query = $this->db->query($sql, $id);
        $data  = $query->row_array();
        // Formating output
        $data['password'] = 'radiisi';

        return $data;
    }

    /**
     * Update password
     *
     * @param int $id Id user di database
     *
     * @return void
     */
    public function update_password($id = 0)
    {
        $data = $this->periksa_input_password($id);
        if (! empty($data)) {
            $hasil = $this->config_id()->where('id', $id)->update('user', $data);
            status_sukses($hasil, $gagal_saja = true);
        }
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

            if (! empty($this->session->error_msg)) {
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
     *
     * @return void
     */
    public function update_setting($id = 0)
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

    public function list_grup()
    {
        return $this->config_id()
            ->get('user_grup')
            ->result_array();
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

        $nama_foto = $this->uploadFoto('gif|jpg|jpeg|png', LOKASI_USER_PICT, 'foto', 'man_user');

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
     * @param mixed $allowed_types
     * @param mixed $upload_path
     * @param mixed $lokasi
     * @param mixed $redirect
     */
    private function uploadFoto($allowed_types, $upload_path, $lokasi, $redirect)
    {
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$lokasi]['name']);
        if ($adaBerkas !== true) {
            return null;
        }
        // Tes tidak berisi script PHP
        if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name'])) {
            $this->session->error_msg .= ' -> Jenis file ini tidak diperbolehkan ';
            $this->session->success = -1;
            redirect($redirect);
        }

        if ((strlen($_FILES[$lokasi]['name']) + 20) >= 100) {
            $this->session->success   = -1;
            $this->session->error_msg = ' -> Nama berkas foto terlalu panjang, maksimal 80 karakter';
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

        return (! empty($uploadData)) ? $uploadData['file_name'] : null;
    }

    // Hak akses setiap controller.

    public function hak_akses_url($group, $url_modul, $akses)
    {
        return $this->hak_akses($group, $url_modul, $akses, $url_modul);
    }

    public function hak_akses($group, $url_modul, $akses, $pakai_url = false)
    {
        $controller = explode('/', $url_modul);
        // Demo tidak boleh mengakses menu tertentu
        if (config_item('demo_mode')) {
            if (in_array($akses, $this->larangan_demo[$controller[0]])) {
                log_message('error', '==Akses Demo Terlarang: ' . print_r($_SERVER, true));

                return false;
            }
        }

        // Group admin punya akses global
        // b = baca; u = ubah; h= hapus
        if ($group == $this->user_model->id_grup(UserGrup::ADMINISTRATOR)) {
            return true;
        }
        // Controller yang boleh diakses oleh semua pengguna yg telah login
        if ($group && in_array($controller[0], ['user_setting', 'wilayah', 'notif', 'pengguna', 'tte', 'sign', 'surat_kecamatan'])) {
            return true;
        }

        if ($pakai_url) {
            $ada_akses = $this->grup_model->ada_akses_url($group, $url_modul, $akses);
        } else {
            $ada_akses = $this->grup_model->ada_akses($group, $controller[0], $akses);
        }

        return $ada_akses;
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
     *
     * @return bool
     */
    public function increase_login_attempts($identity, $ip_address)
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
