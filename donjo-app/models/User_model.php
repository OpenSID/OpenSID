<?php

class User_model extends CI_Model {

	const GROUP_REDAKSI = 3;

	private $_username;
	private $_password;
	// Konfigurasi untuk library 'upload'
	protected $uploadConfig = array();


	function __construct() {
		parent::__construct();
		// Untuk dapat menggunakan library upload
		$this->load->library('upload');
		// Untuk dapat menggunakan fungsi generator()
		$this->load->helper('donjolib');
		$this->uploadConfig = array(
			'upload_path' => LOKASI_USER_PICT,
			'allowed_types' => 'gif|jpg|jpeg|png',
			'max_size' => 2048,
		);
		$this->load->model('laporan_bulanan_model');
		// Untuk password hashing
		$this->load->helper('password');
        // Helper upload file
		$this->load->helper('pict_helper');
	}


	function siteman() {
		$this->_username = $username = trim($this->input->post('username'));
		$this->_password = $password = trim($this->input->post('password'));
		$sql = "SELECT id, password, id_grup, session FROM user WHERE username = ?";

		// User 'admin' tidak bisa di-non-aktifkan
		if ($username !== 'admin') {
			$sql .= ' AND active = 1';
		}

		$query = $this->db->query($sql, array($username));
		$row = $query->row();

		// Cek hasil query ke db, ada atau tidak data user ybs.
		$userAda = is_object($row);
		$pwMasihMD5 = $userAda ?
		(
			(strlen($row->password) == 32) && (stripos($row->password, '$') === FALSE)
		) : FALSE;

		$authLolos = $pwMasihMD5
			? (md5($password) == $row->password)
			: password_verify($password, $row->password);

		// Login gagal: user tidak ada atau tidak lolos verifikasi
		if ($userAda === FALSE || $authLolos === FALSE) {
			$_SESSION['siteman'] = -1;
			if ($_SESSION['siteman_try'] > 2) {
				$_SESSION['siteman_try'] = $_SESSION['siteman_try']-1;
			} else {
				$_SESSION['siteman_wait'] = 1;
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
				$sql = "ALTER TABLE user MODIFY COLUMN password VARCHAR(100) NOT NULL";
				$this->db->query($sql);
				// Lanjut ke update password di database
				$sql = "UPDATE user SET password = ? WHERE id = ?";
				$this->db->query($sql, array($pwBcrypt, $row->id));
			}
			// Lanjut set session
			if (($row->id_grup == self::GROUP_REDAKSI) && ($this->setting->offline_mode >= 2)) {
				$_SESSION['siteman'] = -2;
			} else {
				$_SESSION['siteman'] = 1;
				$_SESSION['sesi'] = $row->session;
				$_SESSION['user'] = $row->id;
				$_SESSION['grup'] = $row->id_grup;
				$_SESSION['per_page'] = 10;
				unset($_SESSION['siteman_timeout']);
			}
		}
	}

    /**
     * Pastikan admin sudah mengubah password yang digunakan pertama kali. Berikan warning jika belum.
     */
    function validate_admin_has_changed_password() {
        $auth = $this->config->item('defaultAdminAuthInfo');

        if ($this->_username == $auth['username'] && $this->_password == $auth['password']) {
            $_SESSION['admin_warning'] = array(
                'Pemberitahuan Keamanan Akun',
                'Penting! Password anda harus diganti demi keamanan.',
            );
        }
    }

	function sesi_grup($sesi = '') {
		$sql = "SELECT id_grup FROM user WHERE session = ?";
		$query = $this->db->query($sql, array($sesi));
		$row = $query->row_array();
		return $row['id_grup'];
	}


	function login() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$sql = "SELECT id, password, id_grup, session FROM user WHERE id_grup = 1 LIMIT 1";
		$query = $this->db->query($sql);
		$row = $query->row();

		// Verifikasi password lolos
		if (password_verify($password, $row->password)) {
			// Simpan sesi - sesi
			$_SESSION['siteman'] = 1;
			$_SESSION['sesi'] = $row->session;
			$_SESSION['user'] = $row->id;
			$_SESSION['grup'] = $row->id_grup;
			$_SESSION['per_page'] = 10;
		}
		// Verifikasi password gagal
		else {
			$_SESSION['siteman'] = -1;
		}
	}


	function logout() {
		if (isset($_SESSION['user'])) {
			$id = $_SESSION['user'];
			$sql = "UPDATE user SET last_login = NOW() WHERE id = ?";
			$this->db->query($sql, $id);
		}
		// Catat jumlah penduduk saat ini
		$this->laporan_bulanan_model->tulis_log_bulanan();
		unset(
			$_SESSION['user'],
			$_SESSION['sesi'],
			$_SESSION['cari'],
			$_SESSION['filter']
		);

		// $this->create_xml();
		// if ($this->sid_online())
		// 	$this->send_data();
	}


	function autocomplete() {
		$sql = "SELECT username FROM user UNION SELECT nama FROM user";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$i = 0;
		$out = '';
		while ($i < count($data)) {
			$out .= ",'".$data[$i]['username']."'";
			$i++;
		}
		return '['.strtolower(substr($out,1)).']';
	}


	function search_sql() {
		if (isset($_SESSION['cari'])) {
			$keyword = $_SESSION['cari'];
			$keyword = '%'.$this->db->escape_like_str($keyword).'%';
			$search_sql = " AND (u.username LIKE '$keyword' OR u.nama LIKE '$keyword')";
			return $search_sql;
		}
	}


	function filter_sql() {
		if (isset($_SESSION['filter'])) {
			$filter = $_SESSION['filter'];
			$filter_sql = " AND u.id_grup = $filter";
			return $filter_sql;
		}
	}


	function paging($page = 1, $o = 0) {
		$sql = "SELECT COUNT(id) AS id FROM user u WHERE 1";
		$sql .= $this->search_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $page;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}


	function list_data($order = 0, $offset = 0, $limit = 500) {
		// Ordering sql
		switch($order) {
			case 1 :
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
		$paging_sql = ' LIMIT '.$offset.','.$limit;
		// Query utama
		$sql = "SELECT u.*, g.nama as grup FROM user u, user_grup g WHERE u.id_grup = g.id";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		// Formating output
		$i = 0;
		$j = $offset;
		while ($i < count($data)) {
			$data[$i]['no'] = $j + 1;
			$i++;
			$j++;
		}
		return $data;
	}

	/**
	 * Insert user baru ke database
	 * @return  void
	 */
	public function insert()
	{
		$data = $this->input->post(NULL);
		$adaLampiran = !empty($_FILES['foto']['name']);

		if ((strlen($_FILES['foto']['name']) + 20 ) >= 100)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Nama berkas scan telalu panjang, maksimal 80 karakter';
			redirect('man_user');
		}

		$sql = "SELECT username FROM user WHERE username = ?";
		$dbQuery = $this->db->query($sql, array($data['username']));
		$userSudahTerdaftar = $dbQuery->row();
		$userSudahTerdaftar = is_object($userSudahTerdaftar) ? $userSudahTerdaftar->username : FALSE;

		if ($userSudahTerdaftar !== FALSE)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Username ini sudah ada. silahkan pilih username lain';
			redirect('man_user');
		}

		$uploadData = NULL;
		$uploadError = NULL;

		if ($adaLampiran === TRUE)
		{
			// Tes tidak berisi script PHP
			if(isPHP($_FILES['foto']['tmp_name'], $_FILES['foto']['name'])){
				$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
				$_SESSION['success']=-1;
				redirect('man_user');
			}

			$this->upload->initialize($this->uploadConfig);

			if ($this->upload->do_upload('foto'))
			{
				$uploadData = $this->upload->data();
				$namaClean = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
				$fileRenamed = rename(
					$this->uploadConfig['upload_path'].$uploadData['file_name'],
					$this->uploadConfig['upload_path'].'kecil_'.$namaFileUnik
				);
				$uploadData['file_name'] = $fileRenamed ? 'kecil_'.$namaFileUnik : $uploadData['file_name'];
			}
			// Upload gagal
			else
			{
				$uploadError = $this->upload->display_errors(NULL, NULL);
				$data['foto'] = NULL;
			}

			$data['foto'] = $adaLampiran && !is_null($uploadData) ? $uploadData['file_name'] : NULL;
		}
		// Tidak ada lampiran foto
		else
		{
			$data['foto'] = NULL;
			$uploadError = NULL;
		}
		$pwHash = $this->generatePasswordHash($data['password']);
		$data['password'] = $pwHash;
		$data['session'] = md5(now());
		// Helper function pict_helper::AmbilFoto() membuat nama file di database dan di disk tidak sama
		// Fungsi tersebut menambah prefix 'kecil_' pada nama file di disk,
		// sedangkan nama di database tidak ditambah. Menyulitkan programmer lain.
		$data['foto'] = is_null($data['foto']) ? 'kuser.png' : str_replace('kecil_', '', $data['foto']);

		$dbInserted = is_null($uploadError) && $this->db->insert('user', $data);

		$_SESSION['success'] = $dbInserted ? 1 : -1;
		$_SESSION['error_msg'] = $_SESSION['success'] === 1 ? NULL : ' -> '.$uploadError;
	}

	/**
	 * Update data user
	 * @param   integer  $idUser  Id user di database
	 * @return  void
	 */
	public function update($idUser)
	{
		$_SESSION['error_msg'] = NULL;
		$_SESSION['success'] = NULL;

		$data = $this->input->post(NULL);

		if (empty($idUser))
		{
			$_SESSION['error_msg'] = ' -> Pengguna yang hendak Anda ubah tidak ditemukan datanya.';
			$_SESSION['success'] = -1;
			redirect('man_user');
		}


		if (empty($data['username']) || empty($data['password'])
		|| empty($data['nama']) || !in_array(intval($data['id_grup']), range(1, 4)))
		{
			$_SESSION['error_msg'] = ' -> Nama, Username dan Password harus diisi';
			$_SESSION['success'] = -1;
			redirect('man_user');
		}

		$sql = "SELECT foto FROM user WHERE id = ?";
		$dbQuery = $this->db->query($sql, array($idUser));
		$berkasLama = $dbQuery->row();
		$berkasLama = is_object($berkasLama) ? $berkasLama->foto : 'kuser.png';
		$lokasiBerkasLama = $this->uploadConfig['upload_path'].'kecil_'.$berkasLama;
		$lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH.$lokasiBerkasLama);

		$uploadData = NULL;
		$uploadError = NULL;

		$indikatorSukses = FALSE;

		if ($id == 1 && config_item('demo'))
		{
			unset($data['username'], $data['password']);
		}
		else
		{
			$pwHash = $this->generatePasswordHash($data['password']);
			$data['password'] = $pwHash;
		}


		$adaLampiran = !empty($_FILES['foto']['name']);

		if ((strlen($_FILES['foto']['name']) + 20 ) >= 100)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Nama berkas foto terlalu panjang, maksimal 80 karakter';
			redirect('man_user');
		}

		// Ada lampiran file
		if ($adaLampiran === TRUE)
		{
			// Tes tidak berisi script PHP
			if(isPHP($_FILES['foto']['tmp_name'], $_FILES['foto']['name'])){
				$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
				$_SESSION['success']=-1;
				redirect('man_user');
			}

			// Inisialisasi library 'upload'
			$this->upload->initialize($this->uploadConfig);
			// Upload sukses
			if ($this->upload->do_upload('foto'))
			{
				$uploadData = $this->upload->data();
				$namaClean = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
				$fileRenamed = rename(
					$this->uploadConfig['upload_path'].$uploadData['file_name'],
					$this->uploadConfig['upload_path'].'kecil_'.$namaFileUnik
				);
				$data['foto'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
				if ($berkasLama !== 'kecil_kuser.png') {
					unlink($lokasiBerkasLama);
					$indikatorSukses = !file_exists($lokasiBerkasLama);
				}
				else
				{
					$indikatorSukses = TRUE;
				}
			}
			// Upload gagal
			else
			{
				$uploadError = $this->upload->display_errors(NULL, NULL);
				$indikatorSukses = FALSE;
				$data['foto'] = $berkasLama;
			}
		}
		// Tidak ada lampiran foto
		else
		{
			$data['foto'] = NULL;
			$uploadError = NULL;
			$indikatorSukses = TRUE;
		}

		$data['foto'] = is_null($data['foto']) ? $berkasLama : str_replace('kecil_', '', $data['foto']);

		$this->db->where('id', $idUser);
		$indikatorSukses = $indikatorSukses && $this->db->update('user', $data);
		$_SESSION['success'] = $indikatorSukses === TRUE ? 1 : -1;
		$_SESSION['error_msg'] = ($_SESSION['success'] === 1)
			? NULL : ' -> Gagal memperbarui data di database';
	}


	function delete($idUser = '') {
		// Jangan hapus admin
		if ($idUser == 1) {
			return;
		}
        $foto = $this->db->get_where('user',array('id' => $idUser))->row()->foto;
		$sql = "DELETE FROM user WHERE id = ?";
		$hasil = $this->db->query($sql, array($idUser));
        
        //cek apakah pengguna berhasil dihapus
		if ($hasil)
        {
            //cek apakah pengguna memiliki foto atau tidak
            if($foto != 'kuser.png')
            {
                //ambil nama foto
                $foto = basename(AmbilFoto($foto));
                //cek penghapusan foto pengguna
                if(unlink(LOKASI_USER_PICT.$foto))
                {
                    $_SESSION['success'] = 1;
                }
                else
                {
                    $_SESSION['error_msg'] = 'Gagal menghapus foto pengguna';
                    $_SESSION['success'] = -1;
                }
            }else
            {
                $_SESSION['success'] = 1;
            }
		}
        else
        {
            $_SESSION['error_msg'] = 'Gagal menghapus pengguna';
			$_SESSION['success'] = -1;
		}
	}


	function delete_all() {
        $id_cb = $_POST['id_cb'];
        //cek apakah ada data yang dicentang atau dipilih
        if(!is_null($id_cb))
        {
            foreach($id_cb as $id)
            {
                $this->delete($id);
            }
        }
        else
        {
            $_SESSION['error_msg'] = 'Tidak ada data yang dipilih';
            $_SESSION['success'] = -1;
        }
	}


	function user_lock($id = '', $val = 0) {
		$sql = "UPDATE user SET active = ? WHERE id = ?";
		$hasil = $this->db->query($sql, array($val, $id));
		$_SESSION['success'] = ($hasil === TRUE ? 1 : -1);
	}


	function get_user($id = 0) {
		$sql = "SELECT * FROM user WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		// Formating output
		$data['password'] = 'radiisi';
		return $data;
	}


	function get_user2($user = '') {
		$sql = "SELECT id, nama, username FROM user WHERE username = ?";
		$query = $this->db->query($sql, $user);
		return $query->row_array();
	}


	function update_setting($id = 0) {
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

		$data['nama'] = $this->input->post('nama');
		$password = $this->input->post('pass_lama');
		$pass_baru = $this->input->post('pass_baru');
		$pass_baru1 = $this->input->post('pass_baru1');

		// Jangan edit password admin apabila di situs demo
		if ($id == 1 && config_item('demo')) {
		  unset($data['password']);
		}
		// Ganti password
		else {
			if ($this->input->post('pass_lama') != '' || $pass_baru != '' || $pass_baru1 != '') {
				$sql = "SELECT password,id_grup,session FROM user WHERE id = ?";
				$query = $this->db->query($sql, array($id));
				$row = $query->row();
				// Cek input password
				if (password_verify($password, $row->password) === FALSE) {
					$_SESSION['error_msg'] .= ' -> Password lama salah<br />';
				}
				if (empty($pass_baru1)) {
					$_SESSION['error_msg'] .= ' -> Password baru tidak boleh kosong<br />';
				}
				if ($pass_baru != $pass_baru1) {
					$_SESSION['error_msg'] .= ' -> Password baru tidak cocok<br />';
				}

				if (!empty($_SESSION['error_msg'])) {
					$_SESSION['success'] = -1;
				}
				// Cek input password lolos
				else {
					$_SESSION['success'] = 1;
					// Buat hash password
					$pwHash = $this->generatePasswordHash($pass_baru);
					// Cek kekuatan hash lolos, simpan ke array data
					$data['password'] = $pwHash;
				}

			}
		}

		// Update foto
		// TODO : mestinya pake cara upload CI?
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		// Normalkan nama file
		$nama_file = str_replace(' ', '-', $nama_file);
		$old_foto = $this->input->post('old_foto');
		if (!empty($lokasi_file)) {

			// Tes tidak berisi script PHP
			if(isPHP($_FILES['foto']['tmp_name'], $_FILES['foto']['name'])){
				$_SESSION['error_msg'].= " -> Jenis file ini tidak diperbolehkan ";
				$_SESSION['success']=-1;
				return;
			}

			if (UploadFoto($nama_file, $old_foto, $tipe_file)) {
				$data['foto'] = $nama_file;
			}
		}
		$this->db->where('id', $id);
		$hasil = $this->db->update('user', $data);

		if (!$hasil) {
			$_SESSION['success'] = -1;
		} elseif ($_SESSION['success'] === 1) {
			unset($_SESSION['admin_warning']);
		}
	}


	function list_grup() {
		$sql = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	function sid_online() {
		// $q = $_GET["q"]; // ????
		$q = "sid.web.id";
		$input = '';
		exec("ping -n 1 -w 1 $q", $input, $result);
		return ($result == 0);
	}


	function create_xml() {
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$desa = $query->row_array();
		$newLine = "\r\n";
		$string = NULL;

		// Desa
		$string .= '<desa>'.$newLine;
		$string .= '<nama>'.$desa['nama_desa'].'</nama>'.$newLine;
		$string .= '<kode>'.
				$desa['kode_kabupaten'].
				$desa['kode_kecamatan'].
				$desa['kode_desa'].
			'</kode>'.$newLine;
		$string .= '<lat>'.$desa['lat'].'</lat>'.$newLine;
		$string .= '<lng>'.$desa['lng'].'</lng>'.$newLine;
		$string .= '</desa>'.$newLine.$newLine;

		// Wilayah
		$sql = "SELECT DISTINCT(dusun) FROM tweb_wil_clusterdesa";
		$query = $this->db->query($sql);
		$wilayah = $query->result_array();

		$string .= '<wilayah>'.$newLine;
		foreach ($wilayah as $wil) {
			$string .= '<dusun>'.$wil['dusun'].'</dusun>'.$newLine;
		}
		$string .= '</wilayah>'.$newLine.$newLine;

		// Pendeuduk
		$sql = "SELECT * FROM data_surat";
		$query = $this->db->query($sql);
		$penduduk = $query->result_array();

		$string .= '<penduduk>'.$newLine;
		foreach ($penduduk as $pend) {
			$string .= '<individu>'.$newLine;
			$string .= '<nik>'.$pend['nik'].'</nik>'.$newLine;
			$string .= '<nama>'.$pend['nama'].'</nama>'.$newLine;
			$string .= '<pekerjaan>'.$pend['pekerjaan'].'</pekerjaan>'.$newLine;
			$string .= '</individu>'.$newLine;
		}
		$string .= '</penduduk>'.$newLine.$newLine;

		// $mypath = "assets\\sync\\";
		// $path = str_replace("\\", "/", $mypath).'/';
		$path = 'assets/sync/'; // ???
		$ccyymmdd = date('Y-m-d');
		$handle = fopen($path."sycn_data_".$ccyymmdd.".xml",'w+');
		fwrite($handle, $string);
		fclose($handle);
		// echo $string;
	}


	function send_data() {
		// $ip="sid.web.id"; // ???
		$ip = '127.0.0.1';
		$connect = fsockopen($ip, '80', $errno, $errstr, 1);
		if ($connect) {
			// $p['id'] // ???
			$soap_request = '<GetAttLog>'.
				'<ArgComKey xsi:type="xsd:integer">'.$key.'</ArgComKey>'.
					'<Arg><PIN xsi:type="xsd:integer">'.$p['id'].'</PIN></Arg>'.
				'</GetAttLog>';

			$newLine = "\r\n";
			fputs($connect, 'POST /iWsService HTTP/1.0'.$newLine);
			fputs($connect, 'Content-Type: text/xml'.$newLine);
			fputs($connect, 'Content-Length: '.strlen($soap_request).$newLine.$newLine);
			fputs($connect, $soap_request.$newLine);

			$buffer = '';
			while ($response = fgets($connect, 8192)) {
				$buffer .= $response;
			}
			echo $buffer;
		}
	}

	//!===========================================================
	//! Helper Methods
	//!===========================================================

	/**
	 * Buat hash password (bcrypt) dari string sebuah password
	 * @param  [type]  $string  [description]
	 * @return  [type]  [description]
	 */
	private function generatePasswordHash($string)
	{
		// Pastikan inputnya adalah string
		$string = is_string($string) ? $string : strval($string);
		// Buat hash password
		$pwHash = password_hash($string, PASSWORD_BCRYPT);
		// Cek kekuatan hash, regenerate jika masih lemah
		if (password_needs_rehash($pwHash, PASSWORD_BCRYPT))
		{
			$pwHash = password_hash($string, PASSWORD_BCRYPT);
		}

		return $pwHash;
	}

}

?>
