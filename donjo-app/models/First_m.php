<?php

class First_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_data()
	{
		$sql = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	/**
	 * Check login mandiri based on posted data (nik and pin)
	 * Return boolean true ketika berhasil, atau string berisi pesan error ketika gagal
	 * 
	 * @return boolean
	 */
	public function siteman()
	{
		$nik = $this->input->post('nik');
		$pin = $this->input->post('pin');
		$hash_pin = hash_pin($pin);

		$row = $this->db->select('pin, last_login, locked, fail_count, id_pend')
			->where('p.nik', $nik)
			->from('tweb_penduduk_mandiri m')
			->join('tweb_penduduk p', 'm.id_pend = p.id', 'left')
			->get()->row();

		// Karena pake left join, maka ada kemungkinan berisi 1 row tapi tweb_penduduk_mandiri kosong
		if( empty($row) || empty($row->id_pend) )
		{
			return 'NIK Anda belum diaktifkan, silahkan hubungi Operator/Perangkat ' . ucwords($this->setting->sebutan_desa);
		}
		
		$id_pend = $row->id_pend;

		// Berapa lama akun terkunci
		$lock_timeout	= 60 * 5;
		
		// Jumlah percobaan login sebelum akun terkunci
		$lock_count		= 4;

		// Indikator lock updated agar tidak doble action aja
		$lock_updated	= false;
		
		// Cek apakah NIK Terkunci
		if( $row->locked )
		{
			if( time() <= $row->locked )
			{
				$remaining = $row->locked - time();
				return "Akun terkunci, tunggu $remaining detik untuk mencoba lagi.";
			}else
			{
				// Unlock locked dan reset counter karena sudah timeout
				$this->db->update('tweb_penduduk_mandiri', array(
					'locked' => NULL,
					'fail_count' => 0
				), array('id_pend' => $id_pend ) );
				
				// set tr u)e
				$lock_updated = true;
			}
		}
		$lg = $row->last_login;

		if ($hash_pin == $row->pin)
		{
			if( !isset($lock_updated) ){
				// Reset LOCK dan fail_count
				$this->db->update('tweb_penduduk_mandiri', array(
					'locked' => NULL,
					'fail_count' => 0
				), array('id_pend' => $id_pend ) );
			}

			$sql = "SELECT nama,nik,p.id,k.no_kk
				FROM tweb_penduduk p
				LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
				WHERE nik = ?";
			$query = $this->db->query($sql, array($nik));
			$row = $query->row();
			// Kosong jika NIK penduduk ybs telah berubah
			if (!empty($row))
			{
				// Kalau pertama kali login, pengguna perlu mengganti PIN ($_SESSION['lg'] == 1)
				if ( empty($lg) OR $lg == "0000-00-00 00:00:00" )
				{
					$_SESSION['lg'] = 1;
				}

				$_SESSION['nama']	= $row->nama;
				$_SESSION['nik']	= $row->nik;
				$_SESSION['id']		= $row->id;
				$_SESSION['no_kk']	= $row->no_kk;
				$_SESSION['mandiri']= 1;
				return true;
			}else{
				return 'Data anda kosong, kemungkinan NIK anda berubah. Silahkan hubungi Operator/Perangkat ' . 
						ucwords($this->setting->sebutan_desa);
			}
		}else
		{
			// update jumlah gagal
			$update	= array(
				'fail_count' => $row->fail_count + 1
			);
			if( $update['fail_count'] >= $lock_count )
			{
				// Set locked time
				$update['locked'] = time() + $lock_timeout;
			}
			$this->db->update('tweb_penduduk_mandiri', $update, array('id_pend' => $id_pend ));
			if( isset($update['locked']) )
			{
				return 'Anda terlalu banyak memasukan PIN yang salah, akun anda terkunci selama ' . round($lock_timeout/60) . ' menit.';
			}else{
				return 'NIK anda terdaftar, tetapi PIN yang anda masukan SALAH. <br/> Anda memiliki ' .
				( $lock_count - $update['fail_count']) . ' kali kesempatan lagi sebelum akun terkunci sementara';
			}
		}
	}

	public function logout()
	{
		if (isset($_SESSION['nik']))
		{
			$nik = $_SESSION['nik'];
			$sql = "UPDATE tweb_penduduk_mandiri SET last_login = NOW()
				WHERE id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, ?) = 0)";
			$this->db->query($sql, $nik);
		}
		unset($_SESSION['mandiri']);
		unset($_SESSION['id']);
		unset($_SESSION['nik']);
		unset($_SESSION['nama']);
	}

	public function ganti()
	{
		if ($_POST['pin1'] != $_POST['pin2'])
		{
			$this->session->set_flashdata('flash_message', 'Pengulangan PIN anda salah. Coba masukkan lagi.');
			$_SESSION['lg'] = 1;
			return;
		}
		$hash_pin = hash_pin($_POST['pin1']);
		$data['pin'] = $hash_pin;
		$this->db->where("id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, {$_SESSION['nik']}) = 0)");
		$outp = $this->db->update('tweb_penduduk_mandiri', $data);
		$_SESSION['lg'] = 2;
	}

}

