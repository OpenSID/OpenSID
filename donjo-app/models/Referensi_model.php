<?php

define("KATEGORI_PUBLIK", serialize(array(
	"Informasi Berkala" => "4",
	"Informasi Serta-merta" => "5",
	"Informasi Setiap Saat" => "6",
	"Informasi Dikecualikan" => "7"
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

	public function list_nama_peraturan($tabel)
	{
		$data = $this->list_data_peraturan($tabel);
		$list = array();
		foreach ($data as $key => $value)
		{
			$list[$value['id']] = $value['nama'];
		}
		return $list;
	}

	public function list_nama_public($tabel)
	{
		$data = $this->list_data_public($tabel);
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

	public function list_data_peraturan($tabel)
	{
		$this->db->where("id NOT IN (1,4,5,6,7)");
		$data = $this->db->select('*')->order_by('id')->get($tabel)->result_array();
		return $data;
	}

	public function list_data_public($tabel)
	{
		$this->db->where("id NOT IN (1,2,3)");
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
