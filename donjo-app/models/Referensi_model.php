<?php

define("KATEGORI_PUBLIK", serialize(array(
	"Informasi Berkala" => "1",
	"Informasi Serta-merta" => "2",
	"Informasi Setiap Saat" => "3",
	"Informasi Dikecualikan" => "4"
)));
define("STATUS_PERMOHONAN", serialize(array(
	"Sedang diperiksa" => "0",
	"Belum lengkap" => "1",
	"Menunggu tandatangan" => "2",
	"Siap diambil" => "3",
	"Sudah diambil" => "4",
	"Dibatalkan" => "9"
)));


class Referensi_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_nama($tabel)
	{
		$data = $this->list_data($tabel);
		$list = array();
		foreach ($data as $key => $value)
		{
			$list[$value['id']] = $value['nama'];
		}
		return $list;
	}

	public function list_data($tabel, $kecuali='')
	{
		if (!empty($kecuali))
			$this->db->where("id NOT IN ($kecuali)");
		$data = $this->db->select('*')->order_by('id')->get($tabel)->result_array();
		return $data;
	}

	public function list_kode_array($s_array)
	{
		$list = array_flip(unserialize($s_array));
		return $list;
	}

	public function list_wajib_ktp()
	{
		$wajib_ktp = array_flip(unserialize(WAJIB_KTP));
		return $wajib_ktp;
	}

	public function list_ktp_el()
	{
		$ktp_el = array_flip(unserialize(KTP_EL));
		return $ktp_el;
	}

	public function list_status_rekam()
	{
		$status_rekam = array_flip(unserialize(STATUS_REKAM));
		return $status_rekam;
	}
}

?>
