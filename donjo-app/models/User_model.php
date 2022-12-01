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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\User;

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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class User_model extends CI_Model
{
    public const GROUP_REDAKSI = 3;

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
        // Untuk dapat menggunakan fungsi generator()
        $this->load->helper('donjolib');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_USER_PICT,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => max_upload() * 1024,
        ];
        $this->load->model('grup_model');
        // Untuk password hashing
        $this->load->helper('password');
        // Helper upload file
        $this->load->helper('pict_helper');
        // Helper Tulis file
        $this->load->helper('file');
    }

    public function siteman()
    {
        $this->load->library('Telegram/telegram');

        $this->_username = $username = trim($this->input->post('username'));
        $this->_password = $password = trim($this->input->post('password'));
        $sql             = 'SELECT * FROM user WHERE username = ?';

        // User 'admin' tidak bisa di-non-aktifkan
        if ($username !== 'admin') {
            $sql .= ' AND active = 1';
        }

        $query = $this->db->query($sql, [$username]);
        $row   = $query->row();

        // Cek hasil query ke db, ada atau tidak data user ybs.
        $userAda    = is_object($row);
        $pwMasihMD5 = $userAda ?
            (
                (strlen($row->password) == 32) && (stripos($row->password, '$') === false)
            ) : false;

        $authLolos = $pwMasihMD5
            ? (md5($password) == $row->password)
            : password_verify($password, $row->password);

        // Login gagal: user tidak ada atau tidak lolos verifikasi
        if ($userAda === false || $authLolos === false) {
            $this->session->siteman = -1;
            if ($this->session->siteman_try > 2) {
                $this->session->siteman_try = $this->session->siteman_try - 1;
            } else {
                $this->session->siteman_wait = 1;
                $this->session->unset_userdata('siteman_timeout');
                siteman_timer();
            }
        }
        // Login sukses: ubah pass di db ke bcrypt jika masih md5 dan set session
        else {
            if ($pwMasihMD5) {
                // Ganti pass md5 jadi bcrypt
                $pwBcrypt = $this->generatePasswordHash($password);

                // Modifikasi panjang karakter di kolom user.password menjadi 100 untuk -
                // backward compatibility dengan kolom di database lama yang hanya 40 karakter.
                // Hal ini menyebabkan string bcrypt (yang default lengthnya 60 karakter) jadi -
                // terpotong sehingga $authLolos selalu mereturn FALSE.
                $sql = 'ALTER TABLE user MODIFY COLUMN password VARCHAR(100) NOT NULL';
                $this->db->query($sql);
                // Lanjut ke update password di database
                $sql = 'UPDATE user SET password = ? WHERE id = ?';
                $this->db->query($sql, [$pwBcrypt, $row->id]);
            }
            // Lanjut set session
            if (($row->id_grup == self::GROUP_REDAKSI) && ($this->setting->offline_mode >= 2)) {
                $this->session->siteman = -2;
            } else {
                $this->session->siteman      = 1;
                $this->session->sesi         = $row->session;
                $this->session->user         = $row->id;
                $this->session->nama         = $row->nama;
                $this->session->grup         = $row->id_grup;
                $this->session->per_page     = 10;
                $this->session->siteman_wait = 0;
                $this->session->siteman_try  = 4;
                $this->session->fm_key       = $this->set_fm_key($row->id . $row->id_grup . $row->sesi);
                $this->session->isAdmin      = $row;
                $this->last_login($this->session->user);

                if (! empty($this->setting->telegram_token) && cek_koneksi_internet()) {
                    try {
                        $this->telegram->sendMessage([
                            'text'       => sprintf('%s login Halaman Admin %s pada tanggal %s', $row->nama, APP_URL, tgl_indo2(date('Y-m-d H:i:s'))),
                            'parse_mode' => 'Markdown',
                            'chat_id'    => $this->setting->telegram_user_id,
                        ]);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
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
        $sql = 'UPDATE user SET last_login = NOW() WHERE id = ?';
        $this->db->query($sql, $id);
    }

    //Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus
    public function syarat_sandi()
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $this->_password));
    }

    public function sesi_grup($sesi = '')
    {
        $sql   = 'SELECT id_grup FROM user WHERE session = ?';
        $query = $this->db->query($sql, [$sesi]);
        $row   = $query->row_array();

        return $row['id_grup'];
    }

    public function login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $sql      = 'SELECT id, password, id_grup, session FROM user WHERE id_grup = 1 LIMIT 1';
        $query    = $this->db->query($sql);
        $row      = $query->row();

        // Verifikasi password lolos
        if (password_verify($password, $row->password)) {
            // Simpan sesi - sesi
            $this->session->siteman  = 1;
            $this->session->sesi     = $row->session;
            $this->session->user     = $row->id;
            $this->session->grup     = $row->id_grup;
            $this->session->per_page = 10;
        }
        // Verifikasi password gagal
        else {
            $this->session->siteman = -1;
        }
    }

    public function logout()
    {
        // Hapus session -- semua session variable akan terhapus
        $this->session->sess_destroy();
    }

    public function autocomplete()
    {
        $sql   = 'SELECT username FROM user UNION SELECT nama FROM user';
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
        if (isset($_SESSION['cari'])) {
            $keyword    = $_SESSION['cari'];
            $keyword    = '%' . $this->db->escape_like_str($keyword) . '%';
            $search_sql = " AND (u.username LIKE '{$keyword}' OR u.nama LIKE '{$keyword}')";

            return $search_sql;
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $filter     = $_SESSION['filter'];
            $filter_sql = " AND u.id_grup = {$filter}";

            return $filter_sql;
        }
    }

    public function paging($page = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(*) AS jml ' . $this->list_data_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $page;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $sql = ' FROM user u, user_grup g WHERE u.id_grup = g.id ';
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();

        return $sql;
    }

    public function list_data($order = 0, $offset = 0, $limit = 500)
    {
        // Ordering sql
        switch ($order) {
            case 1:
                $order_sql = ' ORDER BY u.username';
                break;

            case 2:
                $order_sql = ' ORDER BY u.username DESC';
                break;

            case 3:
                $order_sql = ' ORDER BY u.nama';
                break;

            case 4:
                $order_sql = ' ORDER BY u.nama DESC';
                break;

            case 5:
                $order_sql = ' ORDER BY g.nama';
                break;

            case 6:
                $order_sql = ' ORDER BY g.nama DESC';
                break;

            default:
                $order_sql = ' ORDER BY u.username';
        }
        // Paging sql
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;
        // Query utama
        $sql = 'SELECT u.*, g.nama as grup ' . $this->list_data_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

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

        $pwHash           = $this->generatePasswordHash($data['password']);
        $data['password'] = $pwHash;
        $data['session']  = md5(now());

        $data['foto'] = $this->urusFoto();
        $data['nama'] = strip_tags($data['nama']);

        if (! $this->db->insert('user', $data)) {
            $this->session->success   = -1;
            $this->session->error_msg = ' -> Gagal memperbarui data di database';
        }
    }

    private function sterilkan_input($post)
    {
        $data             = [];
        $data['password'] = $post['password'];
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

        if (
            empty($data['username']) || empty($data['password'])
            || empty($data['nama']) || ! in_array((int) ($data['id_grup']), $this->grup_model->list_id_grup())
        ) {
            session_error(' -> Nama, Username dan Kata Sandi harus diisi');
            redirect('man_user');
        }

        // radiisi menandakan password tidak diubah
        if ($data['password'] == 'radiisi') {
            unset($data['password']);
        }
        // Untuk demo jangan ubah username atau password
        if ($idUser == 1 && (config_item('demo_mode') || ENVIRONMENT === 'development')) {
            unset($data['username'], $data['password']);
        }
        if ($data['password']) {
            $pwHash           = $this->generatePasswordHash($data['password']);
            $data['password'] = $pwHash;
        }

        // cek pamong apakah sudah mempunyai user atau belum
        if ($data['pamong_id'] != null && $data['pamong_id'] != '') {
            $pamong = $this->db->where('pamong_id', (int) $data['pamong_id'])->where('id != ', $idUser)->get('user')->num_rows();
            if ($pamong > 0) {
                session_error(' -> Pamong sudah dipilih oleh user lainnya. Silahkan pilih Pamong Lainnya');
                redirect('man_user');
            }
        }

        $data['foto'] = $this->urusFoto($idUser);
        if (! $this->db->where('id', $idUser)->update('user', $data)) {
            session_error(' -> Gagal memperbarui data di database');
        }
        $this->cache->file->delete("{$idUser}_cache_modul");
    }

    public function delete($idUser = '', $semua = false)
    {
        // Jangan hapus admin
        if ($idUser == 1) {
            return;
        }

        if (! $semua) {
            $this->session->success   = 1;
            $this->session->error_msg = '';
        }

        $foto  = $this->db->get_where('user', ['id' => $idUser])->row()->foto;
        $hasil = $this->db->where('id', $idUser)->delete('user');
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
        $sql                    = 'UPDATE user SET active = ? WHERE id = ?';
        $hasil                  = $this->db->query($sql, [$val, $id]);
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
            $hasil = $this->db->where('id', $id)
                ->update('user', $data);
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
        if ($id == 1 && config_item('demo_mode')) {
            unset($data['password']);

            return $data;
        }

        // Ganti password
        if (
            $this->input->post('pass_lama') != ''
            || $pass_baru != '' || $pass_baru1 != ''
        ) {
            $sql   = 'SELECT password,username,id_grup,session FROM user WHERE id = ?';
            $query = $this->db->query($sql, [$id]);
            $row   = $query->row();
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
                $pwHash = $this->generatePasswordHash($pass_baru);
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

        $data['nama'] = alfanumerik_spasi($this->input->post('nama'));
        // Update foto
        $data['foto'] = $this->urusFoto($id);
        $hasil        = $this->db->where('id', $id)
            ->update('user', $data);

        // Untuk Blade
        $this->session->isAdmin = User::whereId($id)->first();

        status_sukses($hasil, $gagal_saja = true);
    }

    public function list_grup()
    {
        $sql   = 'SELECT * FROM user_grup';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    //!===========================================================
    //! Helper Methods
    //!===========================================================

    /**
     * Buat hash password (bcrypt) dari string sebuah password
     *
     * @param  [type]  $string  [description]
     *
     * @return  [type]  [description]
     */
    private function generatePasswordHash($string)
    {
        // Pastikan inputnya adalah string
        $string = is_string($string) ? $string : (string) $string;
        // Buat hash password
        $pwHash = password_hash($string, PASSWORD_BCRYPT);
        // Cek kekuatan hash, regenerate jika masih lemah
        if (password_needs_rehash($pwHash, PASSWORD_BCRYPT)) {
            $pwHash = password_hash($string, PASSWORD_BCRYPT);
        }

        return $pwHash;
    }

    /**
     * - success: nama berkas yang diunggah
     * - fail: nama berkas lama, kalau ada
     *
     * @param mixed $idUser
     *
     * @return
     */
    private function urusFoto($idUser = '')
    {
        if ($idUser) {
            $berkasLama       = $this->db->select('foto')->where('id', $idUser)->get('user')->row();
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
     *
     * @return
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
        if ($group == 1) {
            return true;
        }
        // Controller yang boleh diakses oleh semua pengguna yg telah login

        if ($group && in_array($controller[0], ['user_setting', 'wilayah', 'notif'])) {
            return true;
        }

        if ($pakai_url) {
            $ada_akses = $this->grup_model->ada_akses_url($group, $url_modul, $akses);
        } else {
            $ada_akses = $this->grup_model->ada_akses($group, $controller[0], $akses);
        }

        return $ada_akses;
    }
}
