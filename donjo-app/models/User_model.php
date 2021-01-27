<?php
/*
 * File ini:
 *
 * Model di Modul Pengguna
 *
 * donjo-app/models/User_model.php
 *
 */

/**
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

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
		// Helper Tulis file
		$this->load->helper('file');
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
			$this->session->siteman= -1;
			if ($this->session->siteman_try > 2)
			{
				$this->session->siteman_try = $this->session->siteman_try-1;
			}
			else
			{
				$this->session->siteman_wait = 1;
				$this->session->unset_userdata('siteman_timeout');
				siteman_timer();
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
				$this->session->siteman= -2;
			}
			else
			{
				$this->session->siteman= 1;
				$this->session->sesi= $row->session;
				$this->session->user = $row->id;
				$this->session->grup = $row->id_grup;
				$this->session->per_page = 10;
				$this->session->siteman_wait = 0;
				$this->session->siteman_try = 4;
				$this->last_login($this->session->user);
			}
		}
	}

	//mengupdate waktu login
	private function last_login($id='')
	{
		$sql = "UPDATE user SET last_login = NOW() WHERE id = ?";
		$this->db->query($sql, $id);
	}

	//Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus
	public function syarat_sandi()
	{
		if (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $this->_password))
			return TRUE;
		else
			return FALSE;
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
			$this->session->siteman= 1;
			$this->session->sesi= $row->session;
			$this->session->user = $row->id;
			$this->session->grup = $row->id_grup;
			$this->session->per_page = 10;
		}
		// Verifikasi password gagal
		else
		{
			$this->session->siteman= -1;
		}
	}

	public function logout()
	{
		// Hapus file rfm ketika logout
		unlink(sys_get_temp_dir(). '/config_rfm_' . $this->session->fm_key_file);
		// Catat jumlah penduduk saat ini
		$this->laporan_bulanan_model->tulis_log_bulanan();
		// Hapus session -- semua session variable akan terhapus
		$this->session->sess_destroy();
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
		$cfg['per_page'] = $this->session->per_page;
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
		$this->session->error_msg = NULL;
		$this->session->success = 1;

		$data = $this->sterilkan_input($this->input->post());

		$sql = "SELECT username FROM user WHERE username = ?";
		$dbQuery = $this->db->query($sql, array($data['username']));
		$userSudahTerdaftar = $dbQuery->row();
		$userSudahTerdaftar = is_object($userSudahTerdaftar) ? $userSudahTerdaftar->username : FALSE;

		if ($userSudahTerdaftar !== FALSE)
		{
			$this->session->success = -1;
			$this->session->error_msg = ' -> Username ini sudah ada. silahkan pilih username lain';
			redirect('man_user');
		}

		$pwHash = $this->generatePasswordHash($data['password']);
		$data['password'] = $pwHash;
		$data['session'] = md5(now());

		$data['foto'] = $this->urusFoto();
		$data['nama'] = strip_tags($data['nama']);

		if (!$this->db->insert('user', $data))
		{
			$this->session->success = -1;
			$this->session->error_msg = ' -> Gagal memperbarui data di database';
		}
	}

	private function sterilkan_input($post)
	{
		$data = [];
		$data['password'] = $post['password'];
		if (isset($post['username'])) $data['username'] = alfanumerik($post['username']);
		if (isset($post['nama'])) $data['nama'] = alfanumerik_spasi($post['nama']);
		if (isset($post['email'])) $data['phone'] = htmlentities($post['phone']);
		if (isset($post['username'])) $data['email'] = htmlentities($post['email']);
		if (isset($post['id_grup'])) $data['id_grup'] = $post['id_grup'];
		if (isset($post['foto'])) $data['foto'] = $post['foto'];
		return $data;
	}

	/**
	 * Update data user
	 * @param   integer  $idUser  Id user di database
	 * @return  void
	 */
	public function update($idUser)
	{
		$this->session->error_msg = NULL;
		$this->session->success = 1;

		$data = $this->sterilkan_input($this->input->post());

		if (empty($idUser))
		{
			$this->session->error_msg = ' -> Pengguna tidak ditemukan datanya.';
			$this->session->success = -1;
			redirect('man_user');
		}

		if (empty($data['username']) || empty($data['password'])
		|| empty($data['nama']) || !in_array(intval($data['id_grup']), range(1, 4)))
		{
			$this->session->error_msg = ' -> Nama, Username dan Kata Sandi harus diisi';
			$this->session->success = -1;
			redirect('man_user');
		}

		// radiisi menandakan password tidak diubah
		if ($data['password'] == 'radiisi') unset($data['password']);
		// Untuk demo jangan ubah username atau password
		if ($idUser == 1 && $this->setting->demo_mode)
		{
			unset($data['username'], $data['password']);
		}
		if ($data['password'])
		{
			$pwHash = $this->generatePasswordHash($data['password']);
			$data['password'] = $pwHash;
		}

		$data['foto'] = $this->urusFoto($idUser);
		if (!$this->db->where('id', $idUser)->update('user', $data))
		{
			$this->session->success = -1;
			$this->session->error_msg = ' -> Gagal memperbarui data di database';
		}
	}

	public function delete($idUser = '', $semua=false)
	{
		// Jangan hapus admin
		if ($idUser == 1) return;

		if (!$semua)
		{
			$this->session->success = 1;
			$this->session->error_msg = '';
		}

    $foto = $this->db->get_where('user',array('id' => $idUser))->row()->foto;
		$hasil = $this->db->where('id', $idUser)->delete('user');
    // Cek apakah pengguna berhasil dihapus
		if ($hasil)
		{
	    // Cek apakah pengguna memiliki foto atau tidak
	    if($foto != 'kuser.png')
	    {
        // Ambil nama foto
        $foto = basename(AmbilFoto($foto));
        // Cek penghapusan foto pengguna
        if (!unlink(LOKASI_USER_PICT.$foto))
        {
          $this->session->error_msg = 'Gagal menghapus foto pengguna';
          $this->session->success = -1;
        }
	    }
		}
		else
		{
      $this->session->error_msg = 'Gagal menghapus pengguna';
			$this->session->success = -1;
		}
	}

	public function delete_all()
	{
		$this->session->success = 1;
		$this->session->error_msg = '';

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function user_lock($id = '', $val = 0)
	{
		$sql = "UPDATE user SET active = ? WHERE id = ?";
		$hasil = $this->db->query($sql, array($val, $id));
		$this->session->success = ($hasil === TRUE ? 1 : -1);
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
	 * Update password
	 * @param  integer $id Id user di database
	 * @return void
	 */
	public function update_password($id = 0)
	{
		$data = $this->periksa_input_password($id);
		if (!empty($data))
		{
			$hasil = $this->db->where('id', $id)
				->update('user', $data);
			status_sukses($hasil, $gagal_saja=true);
		}
	}

	private function periksa_input_password($id)
	{
		$this->session->success = 1;
		$this->session->error_msg = '';
		$password = $this->input->post('pass_lama');
		$pass_baru = $this->input->post('pass_baru');
		$pass_baru1 = $this->input->post('pass_baru1');
		$data = [];

		// Jangan edit password admin apabila di situs demo
		if ($id == 1 && $this->setting->demo_mode)
		{
		  unset($data['password']);
		  return $data;
		}

		// Ganti password
		if ($this->input->post('pass_lama') != ''
		|| $pass_baru != '' || $pass_baru1 != '')
		{
			$sql = "SELECT password,username,id_grup,session FROM user WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			$row = $query->row();
			// Cek input password
			if (password_verify($password, $row->password) === FALSE)
			{
				$this->session->error_msg .= ' -> Kata sandi lama salah<br />';
			}

			if (empty($pass_baru1))
			{
				$this->session->error_msg .= ' -> Kata sandi baru tidak boleh kosong<br />';
			}

			if ($pass_baru != $pass_baru1)
			{
				$this->session->error_msg .= ' -> Kata sandi baru tidak cocok<br />';
			}

			if (!empty($this->session->error_msg))
			{
				$this->session->success = -1;
			}
			// Cek input password lolos
			else
			{
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
	 * @param  integer $id Id user di database
	 * @return void
	 */
	public function update_setting($id = 0)
	{
		$data = $this->periksa_input_password($id);

		$data['nama'] = alfanumerik_spasi($this->input->post('nama'));
		// Update foto
		$data['foto'] = $this->urusFoto($id);
		$hasil = $this->db->where('id', $id)
			->update('user', $data);
		status_sukses($hasil, $gagal_saja=true);
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
				if (file_exists($lokasiBerkasLama)) $this->session->success = -1;
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
			$this->session->error_msg .= " -> Jenis file ini tidak diperbolehkan ";
			$this->session->success = -1;
			redirect($redirect);
		}

		if ((strlen($_FILES[$lokasi]['name']) + 20 ) >= 100)
		{
			$this->session->success = -1;
			$this->session->error_msg = ' -> Nama berkas foto terlalu panjang, maksimal 80 karakter';
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
			$this->session->success = -1;
			$this->session->error_msg = $this->upload->display_errors(NULL, NULL);
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
		if ($this->setting->demo_mode)
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

		// Daftar controller berikut disusun sesuai urutan dan struktur menu navigasi modul
		// pada komponen Admin.
		$hak_akses = array(
			// Operator
			2 => array(
				// covid-19
				'covid19' => array('b','u','h'),

				// home
				'hom_sid' => array('b','u'),

				// info desa
				'identitas_desa' => array('b','u'),
				'sid_core' => array('b','u'),
				'pengurus' => array('b','u'),

				// kependudukan
				'penduduk' => array('b','u'),

				// Penduduk
				'penduduk_log' => array('b','u'),
				'keluarga' => array('b','u'),
				'rtm' => array('b','u'),
				'kelompok' => array('b','u'),

				// kelompok
				'kelompok_master' => array('b','u'),
				'suplemen' => array('b','u'),
				'dpt' => array('b','u'),

				// statistik
				'statistik' => array('b','u'),
				'laporan' => array('b','u'),
				'laporan_rentan' => array('b','u'),

				// layanan surat
				'surat_master' => array('b','u'),
				'surat' => array('b','u'),
				'keluar' => array('b','u'),
				'surat_mohon' => array('b','u'),

				// sekretariat
				'sekretariat' => array('b','u'),
				'surat_masuk' => array('b','u'),
				'surat_keluar' => array('b','u'),
				'dokumen_sekretariat' => array('b','u'),
				'dokumen' => array('b','u'),

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
				'klasifikasi' => array('b','u'),

				// buku administrasi
				'buku_umum' => ['b', 'u'],
				'bumindes_umum' => ['b', 'u'],
				'ekspedisi' => ['b', 'u'],
				'lembaran_desa' => ['b', 'u'],

				// keuangan
				'keuangan' => array('b','u'),
				'keuangan_manual' => array('b','u'),

				// keuangan
				'bumindes_umum' => array('b','u'),
				'bumindes_penduduk' => array('b','u'),
				'bumindes_keuangan' => array('b','u'),
				'bumindes_pembangunan' => array('b','u'),
				'bumindes_lain' => array('b','u'),
				'ekspedisi' => array('b','u'),

				// analisis
				'analisis_master' => array('b','u'),

				// pengaturan analisis
				'analisis_kategori' => array('b','u'),
				'analisis_indikator' => array('b','u'),
				'analisis_klasifikasi' => array('b','u'),
				'analisis_periode' => array('b','u'),

				// input data analisis
				'analisis_respon' => array('b','u'),

				// laporan analisis
				'analisis_laporan' => array('b','u'),
				'analisis_statistik_jawaban' => array('b','u'),

				// bantuan
				'program_bantuan' => array('b','u'),

				// pertanahan
				'data_persil' => array('b','u'),

				// pemetaan
				'gis' => array('b','u'),

				//pengaturan peta
				'plan' => array('b','u'),
				'point' => array('b','u'),
				'garis' => array('b','u'),
				'line' => array('b','u'),
				'area' => array('b','u'),
				'polygon' => array('b','u'),

				// sms
				'sms' => array('b','u'),

				// pengaturan
				'modul' => array('b','u'),

				// admin web
				'web' => array('b','u'),
				'web_widget' => array('b','u'),
				'menu' => array('b','u'),

				// menu
				'kategori' => array('b','u'),
				'komentar' => array('b','u'),
				'gallery' => array('b','u'),
				'sosmed' => array('b','u'),
				'teks_berjalan' => array('b','u'),
				'pengunjung' => array('b','u'),

				// layanan mandiri
				'permohonan_surat_admin' => array('b', 'u'),
				'mailbox' => array('b','u'),
				'mandiri' => array('b','u'),

				// --- Controller berikut diakses di luar menu navigasi modul

				// notifikasi
				'notif' => array('b','u'),

				// wilayah
				'wilayah' => array('b')
			),
			// Redaktur
			3 => array(
				// admin web
				'web' => array('b','u'),
				'komentar' => array('b','u'),

				// notifikasi
				'notif' => array('b','u')
			),
			// Kontributor
			4 => array(
				// admin web
				'web' => array('b','u'),
				'komentar' => array('b','u'),

				// notifikasi
				'notif' => array('b','u')
			),
			// Satgas Covid-19
			5 => array(
				// sementara khusus masa pandemi satgas covid-19
				// covid-19
				'covid19' => array('b','u','h'),
				// statistik
				'statistik' => array('b','u'),
				// notifikasi
				'notif' => array('b','u'),
				// wilayah
				'wilayah' => array('b')
			)
		);
		return in_array($akses, $hak_akses[$group][$controller[0]]);
	}

	// RFM Key - disimpan dalam file di folder sementara sys_get_temp_dir() yg kemudian
	// dibaca di setting File Manager di assets/filemanager/config/config.php.
	// Menggunakan tempnam untuk nama config file supaya unik untuk sesi pengguna.
  // Key disimpan di $this->session->fm_key yg dipasang di setting tinymce
	// dan di tempat memanggil filemanager, seperti di donjo-app/views/setting/setting_qr.php.
  // Nama file disimpan di $this->session->fm_key_file untuk dihapus pada waktu logout.
	public function get_fm_key()
	{
		$grup	= $this->sesi_grup($this->session->sesi);
		if ($this->hak_akses($grup, 'web', 'b') == true)
		{
			$fmHash = $grup.date('Ymdhis');
			$salt = rand(100000, 999999);
			$salt = strrev($salt);
			$fm_key = MD5($fmHash.'OpenSID'.$salt);
			$this->session->fm_key = $fm_key;
			// Gunakan cara penambahan prefix ini karena Windows hanya menggunakan 3 karakter dari prefix
			$fname = tempnam(sys_get_temp_dir(), '');
			$sesi = basename($fname);
			$tmpfname = sys_get_temp_dir()."/config_rfm_".$sesi;
			rename($fname, $tmpfname);
			$this->session->fm_key_file = $sesi;
			$rfm = '<?php $config["fm_key_'.$sesi.'"] ="' . $fm_key . '";';
			$handle = fopen($tmpfname, "w");
			fwrite($handle, $rfm);
			fclose($handle);
		}
	}

}

?>
