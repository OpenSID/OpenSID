<?php

class First_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function siteman()
	{
		$_SESSION['mandiri'] = -1;
		$nik = $this->input->post('nik');
		$pin = $this->input->post('pin');
		$hash_pin = hash_pin($pin);

		$row = $this->db->select('pin, last_login')
			->where('p.nik', $nik)
			->from('tweb_penduduk_mandiri m')
			->join('tweb_penduduk p', 'm.id_pend = p.id', 'left')
			->get()->row();

		$lg = $row->last_login;

		if ($hash_pin == $row->pin)
		{
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
				if ($lg == NULL OR $lg == "0000-00-00 00:00:00")
				$_SESSION['lg'] = 1;
				$_SESSION['nama'] = $row->nama;
				$_SESSION['nik'] = $row->nik;
				$_SESSION['id'] = $row->id;
				$_SESSION['no_kk'] = $row->no_kk;
				$_SESSION['mandiri'] = 1;
			}
		}

		if ($_SESSION['mandiri_try'] > 2)
		{
			$_SESSION['mandiri_try'] = $_SESSION['mandiri_try'] - 1;
		}
		else
		{
			$_SESSION['mandiri_wait'] = 1;
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