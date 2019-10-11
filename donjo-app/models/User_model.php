<?php

class User_model extends CI_Model {

	const GROUP_REDAKSI = 3;

	private $_username;
	private $_password;
	// Konfigurasi untuk library 'upload'
	protected $uploadConfig = array();

	protected $larangan_demo = array(
		'database' => array('h')
	);

	public function __construct()
	{
		parent::__construct();
		// Untuk dapat menggunakan library upload
		$this->load->library('upload');
		// Untuk dapat menggunakan fungsi generator()
		$this->load->helper('donjolib');
		$this->uploadConfig = array(
			'upload_path' => LOKASI_USER_PICT,
			'allowed_types' => 'gif|jpg|jpeg|png',
			'max_size' => max_upload()*1024,
		);
		$this->load->model('laporan_bulanan_model');
		// Untuk password hashing
		$this->load->helper('password');
        // Helper upload file
		$this->load->helper('pict_helper');
	}

	public function siteman()
	{
		$this->_username = $username = trim($this->input->post('username'));
		$this->_password = $password = trim($this->input->post('password'));
		$sql = "SELECT id, password, id_grup, session FROM user WHERE username = ?";

		// User 'admin' tidak bisa di-non-aktifkan
		if ($username !== 'admin')
		{
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
		if ($userAda === FALSE || $authLolos === FALSE)
		{
			$_SESSION['siteman'] = -1;
			if ($_SESSION['siteman_try'] > 2)
			{
				$_SESSION['siteman_try'] = $_SESSION['siteman_try']-1;
			}
			else
			{
				$_SESSION['siteman_wait'] = 1;
			}
		}
		// Login sukses: ubah pass di db ke bcrypt jika masih md5 dan set session
		else
		{
			if ($pwMasihMD5)
			{
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
			if (($row->id_grup == self::GROUP_REDAKSI) && ($this->setting->offline_mode >= 2))
			{
				$_SESSION['siteman'] = -2;
			}
			else
			{
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
  public function validate_admin_has_changed_password()
  {
		$_SESSION['admin_warning'] = '';
    $auth = $this->config->item('defaultAdminAuthInfo');

    if ($this->_username == $auth['username'] && $this->_password == $auth['password'])
    {
      $_SESSION['admin_warning'] = array(
        'Pemberitahuan Keamanan Akun',
        'Penting! Password anda harus diganti demi keamanan.',
      );
    }
  }

	public function sesi_grup($sesi = '')
	{
		$sql = "SELECT id_grup FROM user WHERE session = ?";
		$query = $this->db->query($sql, array($sesi));
		$row = $query->row_array();
		return $row['id_grup'];
	}

	public function login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$sql = "SELECT id, password, id_grup, session FROM user WHERE id_grup = 1 LIMIT 1";
		$query = $this->db->query($sql);
		$row = $query->row();

		// Verifikasi password lolos
		if (password_verify($password, $row->password))
		{
			// Simpan sesi - sesi
			$_SESSION['siteman'] = 1;
			$_SESSION['sesi'] = $row->session;
			$_SESSION['user'] = $row->id;
			$_SESSION['grup'] = $row->id_grup;
			$_SESSION['per_page'] = 10;
		}
		// Verifikasi password gagal
		else
		{
			$_SESSION['siteman'] = -1;
		}
	}

	public function logout()
	{
		if (isset($_SESSION['user']))
		{
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
	}

	public function autocomplete()
	{
		$sql = "SELECT username FROM user UNION SELECT nama FROM user";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		$out = '';
		for ($i=0; $i < count($data); $i++)
		{
			$out .= ",'".$data[$i]['username']."'";
		}
		return '['.strtolower(substr($out, 1)).']';
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$keyword = $_SESSION['cari'];
			$keyword = '%'.$this->db->escape_like_str($keyword).'%';
			$search_sql = " AND (u.username LIKE '$keyword' OR u.nama LIKE '$keyword')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$filter = $_SESSION['filter'];
			$filter_sql = " AND u.id_grup = $filter";
			return $filter_sql;
		}
	}

	public function paging($page = 1, $o = 0)
	{
		$sql = "SELECT COUNT(*) AS jml " . $this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $page;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = " FROM user u, user_grup g WHERE u.id_grup = g.id ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($order = 0, $offset = 0, $limit = 500)
	{
		// Ordering sql
		switch($order)
		{
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
		$sql = "SELECT u.*, g.nama as grup " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		// Formating output
		$j = $offset;
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
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
		$_SESSION['error_msg'] = NULL;
		$_SESSION['success'] = 1;

		$data = $this->input->post(NULL);

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

		$pwHash = $this->generatePasswordHash($data['password']);
		$data['password'] = $pwHash;
		$data['session'] = md5(now());

		$data['foto'] = $this->urusFoto();
		$data['nama'] = strip_tags($data['nama']);

		if (!$this->db->insert('user', $data))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Gagal memperbarui data di database';
		}
	}

	/**
	 * Update data user
	 * @param   integer  $idUser  Id user di database
	 * @return  void
	 */
	public function update($idUser)
	{
		$_SESSION['error_msg'] = NULL;
		$_SESSION['success'] = 1;

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

		if ($idUser == 1 && config_item('demo'))
		{
			unset($data['username'], $data['password']);
		}
		else
		{
			$pwHash = $this->generatePasswordHash($data['password']);
			$data['password'] = $pwHash;
		}

		$data['foto'] = $this->urusFoto($idUser);
		$data['nama'] = strip_tags($data['nama']);

		if (!$this->db->where('id', $idUser)->update('user', $data))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Gagal memperbarui data di database';
		}
	}

	public function delete($idUser = '')
	{
		// Jangan hapus admin
		if ($idUser == 1)
		{
			return;
		}
    $foto = $this->db->get_where('user',array('id' => $idUser))->row()->foto;
		$sql = "DELETE FROM user WHERE id = ?";
		$hasil = $this->db->query($sql, array($idUser));

    // Cek apakah pengguna berhasil dihapus
		if ($hasil)
		{
	    // Cek apakah pengguna memiliki foto atau tidak
	    if($foto != 'kuser.png')
	    {
        // Ambil nama foto
        $foto = basename(AmbilFoto($foto));
        // Cek penghapusan foto pengguna
        if(unlink(LOKASI_USER_PICT.$foto))
        {
            $_SESSION['success'] = 1;
        }
        else
        {
          $_SESSION['error_msg'] = 'Gagal menghapus foto pengguna';
          $_SESSION['success'] = -1;
        }
	    }
	    else
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

	public function delete_all()
	{
    $id_cb = $_POST['id_cb'];
    // Cek apakah ada data yang dicentang atau dipilih
    if (!is_null($id_cb))
    {
      foreach ($id_cb as $id)
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

	public function user_lock($id = '', $val = 0)
	{
		$sql = "UPDATE user SET active = ? WHERE id = ?";
		$hasil = $this->db->query($sql, array($val, $id));
		$_SESSION['success'] = ($hasil === TRUE ? 1 : -1);
	}

	public function get_user($id = 0)
	{
		$sql = "SELECT * FROM user WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		// Formating output
		$data['password'] = 'radiisi';
		return $data;
	}

	/**
	 * Update user's settings
	 * @param  integer $id Id user di database
	 * @return void
	 */
	public function update_setting($id = 0)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';
		$data['nama'] = strip_tags($this->input->post('nama'));
		$password = $this->input->post('pass_lama');
		$pass_baru = $this->input->post('pass_baru');
		$pass_baru1 = $this->input->post('pass_baru1');

		// Jangan edit password admin apabila di situs demo
		if ($id == 1 && config_item('demo'))
		{
		  unset($data['password']);
		}
		// Ganti password
		else
		{
			if ($this->input->post('pass_lama') != ''
			|| $pass_baru != '' || $pass_baru1 != '')
			{
				$sql = "SELECT password,username,id_grup,session FROM user WHERE id = ?";
				$query = $this->db->query($sql, array($id));
				$row = $query->row();
				// Cek input password
				if (password_verify($password, $row->password) === FALSE)
				{
					$_SESSION['error_msg'] .= ' -> Password lama salah<br />';
				}

				if (empty($pass_baru1))
				{
					$_SESSION['error_msg'] .= ' -> Password baru tidak boleh kosong<br />';
				}

				if ($pass_baru != $pass_baru1)
				{
					$_SESSION['error_msg'] .= ' -> Password baru tidak cocok<br />';
				}
				$this->_username = $row->username;
				$this->_password = $pass_baru;
				$this->validate_admin_has_changed_password();
				$_SESSION['dari_login'] = '1';

				if (!empty($_SESSION['admin_warning']))
				{
					$_SESSION['error_msg'] .= $_SESSION['admin_warning'][1];
				}

				if (!empty($_SESSION['error_msg']))
				{
					$_SESSION['success'] = -1;
				}
				// Cek input password lolos
				else
				{
					$_SESSION['success'] = 1;
					// Buat hash password
					$pwHash = $this->generatePasswordHash($pass_baru);
					// Cek kekuatan hash lolos, simpan ke array data
					$data['password'] = $pwHash;
					unset($_SESSION['admin_warning']);
				}

			}
		}

		// Update foto
		$data['foto'] = $this->urusFoto($id);

		$this->db->where('id', $id);
		$hasil = $this->db->update('user', $data);

		if (!$hasil)
		{
			$_SESSION['success'] = -1;
		}
		elseif ($_SESSION['success'] === 1)
		{
			unset($_SESSION['admin_warning']);
		}
	}

	public function list_grup()
	{
		$sql = "SELECT * FROM user_grup";
		$query = $this->db->query($sql);
		return $query->result_array();
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

	/***
		* @return
			- success: nama berkas yang diunggah
			- fail: nama berkas lama, kalau ada
	*/
	private function urusFoto($idUser='')
	{
		if ($idUser)
		{
			$berkasLama = $this->db->select('foto')->where('id', $idUser)->get('user')->row();
			$berkasLama = is_object($berkasLama) ? $berkasLama->foto : 'kuser.png';
			$lokasiBerkasLama = $this->uploadConfig['upload_path'].'kecil_'.$berkasLama;
			$lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH.$lokasiBerkasLama);
		}
		else
		{
			$berkasLama = 'kuser.png';
		}

		$nama_foto = $this->uploadFoto('gif|jpg|jpeg|png', LOKASI_USER_PICT, 'foto', 'man_user');

		if (!empty($nama_foto))
		{
			// Ada foto yang berhasil diunggah --> simpan ukuran 100 x 100
			$tipe_file = TipeFile($_FILES['foto']);
			$dimensi = array("width"=>100, "height"=>100);
			resizeImage(LOKASI_USER_PICT.$nama_foto, $tipe_file, $dimensi);
			// Nama berkas diberi prefix 'kecil'
			$nama_kecil = 'kecil_'.$nama_foto;
			$fileRenamed = rename(
				LOKASI_USER_PICT.$nama_foto,
				LOKASI_USER_PICT.$nama_kecil
			);
			if ($fileRenamed) $nama_foto = $nama_kecil;
			// Hapus berkas lama
			if ($berkasLama and $berkasLama !== 'kecil_kuser.png')
			{
				unlink($lokasiBerkasLama);
				if (file_exists($lokasiBerkasLama)) $_SESSION['success'] = -1;
			}
		}

		return is_null($nama_foto) ? $berkasLama : str_replace('kecil_', '', $nama_foto);
	}

	/***
		* @return
			- success: nama berkas yang diunggah
			- fail: NULL
	*/
	private function uploadFoto($allowed_types, $upload_path, $lokasi, $redirect)
	{
		// Adakah berkas yang disertakan?
		$adaBerkas = !empty($_FILES[$lokasi]['name']);
		if ($adaBerkas !== TRUE)
		{
			return NULL;
		}
		// Tes tidak berisi script PHP
		if (isPHP($_FILES[$lokasi]['tmp_name'], $_FILES[$lokasi]['name']))
		{
			$_SESSION['error_msg'] .= " -> Jenis file ini tidak diperbolehkan ";
			$_SESSION['success'] = -1;
			redirect($redirect);
		}

		if ((strlen($_FILES[$lokasi]['name']) + 20 ) >= 100)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = ' -> Nama berkas foto terlalu panjang, maksimal 80 karakter';
			redirect($redirect);
		}

		$uploadData = NULL;
		// Inisialisasi library 'upload'
		$this->upload->initialize($this->uploadConfig);
		// Upload sukses
		if ($this->upload->do_upload($lokasi))
		{
			$uploadData = $this->upload->data();
			// Buat nama file unik agar url file susah ditebak dari browser
			$namaClean = preg_replace('/[^A-Za-z0-9.]/', '_', $uploadData['file_name']);
      $namaFileUnik = tambahSuffixUniqueKeNamaFile($namaClean); // suffix unik ke nama file
			// Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
			$fileRenamed = rename(
				$this->uploadConfig['upload_path'].$uploadData['file_name'],
				$this->uploadConfig['upload_path'].$namaFileUnik
			);
			// Ganti nama di array upload jika file berhasil di-rename --
			// jika rename gagal, fallback ke nama asli
			$uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
		}
		// Upload gagal
		else
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = $this->upload->display_errors(NULL, NULL);
		}
		return (!empty($uploadData)) ? $uploadData['file_name'] : NULL;
	}

	/*
	 * Hak akses setiap controller.
	 * TODO: pindahkan menggunakan authentication/authorisation library
	*/
	public function hak_akses($group, $controller, $akses)
	{
		$controller = explode('/', $controller);
		// Demo tidak boleh mengakses menu tertentu
		if (config_item('demo'))
		{
			if (in_array($akses, $this->larangan_demo[$controller[0]]))
			{
				log_message('error', '==Akses Demo Terlarang: '.print_r($_SERVER, true));
				return false;
			}
		}
		// Group admin punya akses global
		// b = baca; u = ubah; h= hapus
		if ($group == 1) return true;
		// Controller yang boleh diakses oleh semua pengguna yg telah login
		if ($group and in_array($controller[0], array('user_setting'))) return true;

		$hak_akses = array(
			// Operator
			2 => array(
				// home
				'hom_sid' => array('b','u'),
				// info desa
				'hom_desa' => array('b','u'),
				'pengurus' => array('b','u'),
				'sid_core' => array('b','u'),
				// kependudukan
				'dpt' => array('b','u'),
				'keluarga' => array('b','u'),
				'kelompok' => array('b','u'),
				'kelompok_master' => array('b','u'),
				'penduduk' => array('b','u'),
				'penduduk_log' => array('b','u'),
				'rtm' => array('b','u'),
				'suplemen' => array('b','u'),
				// statistik
				'laporan' => array('b','u'),
				'laporan_rentan' => array('b','u'),
				'statistik' => array('b','u'),
				// analisis
				'analisis_indikator' => array('b','u'),
				'analisis_kategori' => array('b','u'),
				'analisis_klasifikasi' => array('b','u'),
				'analisis_laporan' => array('b','u'),
				'analisis_master' => array('b','u'),
				'analisis_periode' => array('b','u'),
				'analisis_respon' => array('b','u'),
				'analisis_statistik_jawaban' => array('b','u'),
				// keuangan
				'keuangan' => array('b','u'),
				// bantuan
				'program_bantuan' => array('b','u'),
				// inventaris
				'api_inventaris_asset' => array('b','u'),
				'api_inventaris_gedung' => array('b','u'),
				'api_inventaris_jalan' => array('b','u'),
				'api_inventaris_kontruksi' => array('b','u'),
				'api_inventaris_peralatan' => array('b','u'),
				'api_inventaris_tanah' => array('b','u'),
				'inventaris_asset' => array('b','u'),
				'inventaris_gedung' => array('b','u'),
				'inventaris_jalan' => array('b','u'),
				'inventaris_kontruksi' => array('b','u'),
				'inventaris_peralatan' => array('b','u'),
				'inventaris_tanah' => array('b','u'),
				'laporan_inventaris' => array('b','u'),
				// pemetaan
				'area' => array('b','u'),
				'garis' => array('b','u'),
				'gis' => array('b','u'),
				'line' => array('b','u'),
				'plan' => array('b','u'),
				'point' => array('b','u'),
				'polygon' => array('b','u'),
				// sms
				'sms' => array('b','u'),
				// pertanahan
				'data_persil' => array('b','u'),
				// admin web
				'dokumen' => array('b','u'),
				'gallery' => array('b','u'),
				'kategori' => array('b','u'),
				'komentar' => array('b','u','h'),
				'menu' => array('b','u'),
				'sosmed' => array('b','u'),
				'teks_berjalan' => array('b','u'),
				'web' => array('b','u'),
				'web_widget' => array('b','u'),
				// pengaturan
				'modul' => array('b','u'),
				// sekretariat
				'sekretariat' => array('b','u'),
				'surat_masuk' => array('b','u'),
				'surat_keluar' => array('b','u'),
				'dokumen_sekretariat' => array('b','u'),
				// layanan surat
				'keluar' => array('b','u'),
				'surat' => array('b','u'),
				'surat_master' => array('b','u'),
				// layanan mandiri
				'lapor' => array('b','u'),
				'mandiri' => array('b','u'),
				// notifikasi
				'notif' => array('b','u'),
				// wilayah
				'wilayah' => array('b')
			),
			// Redaktur
			3 => array(
				// admin web
				'komentar' => array('b','u'),
				'web' => array('b','u'),
				// notifikasi
				'notif' => array('b','u')
			),
			// Kontributor
			4 => array(
				// admin web
				'komentar' => array('b','u'),
				'web' => array('b','u'),
				// notifikasi
				'notif' => array('b','u')
			)
		);
		return in_array($akses, $hak_akses[$group][$controller[0]]);
	}

}

?>
