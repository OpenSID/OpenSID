<?php

class Analisis_parameter_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function list_parameter_by_id_master($id_master)
	{
		$data = $this->db
			->select('i.nomor, p.kode_jawaban, p.jawaban, p.nilai')
			->from('analisis_indikator i')
			->join('analisis_parameter p', 'i.id = p.id_indikator')
			->where('i.id_master', $id_master)
			->order_by('LPAD(i.nomor, 10, " ") ASC', 'p.kode_jawaban ASC')
			->get()->result_array();

		return $data;
	}

	// Untuk Jawabab Dari Pertanyaan
	public function get_data_penduduk($tabel_id = null)
	{
		$tabel_referensi = [
			"kk_level" => "tweb_penduduk_hubungan",
			"rtm_level" => "tweb_rtm_hubungan",
			"sex" => "tweb_penduduk_sex",
			"agama_id" => "tweb_penduduk_agama",
			"pendidikan_kk_id" => "tweb_penduduk_pendidikan_kk",
			"pendidikan_sedang_id" => "tweb_penduduk_pendidikan",
			"pekerjaan_id" => "tweb_penduduk_pekerjaan",
			"status_kawin" => "tweb_penduduk_kawin",
			"hubungan_kk" => "tweb_penduduk_hubungan",
			"id_referensi" => "tweb_penduduk_warganegara",
			"status" => "tweb_penduduk_status",
			"golongan_darah_id" => "tweb_golongan_darah",
			"cacat_id" => "tweb_cacat",
			"sakit_menahun_id" => "tweb_sakit_menahun",
			"cara_kb_id" => "tweb_cara_kb",
			"id_asuransi" => "tweb_penduduk_asuransi",
			"ktp_el" => "tweb_status_ktp",
			"warganegara_id" => "tweb_penduduk_warganegara",
			"id_cluster" => "tweb_wil_clusterdesa",
		];
		
		$tipe = 4;
		$daftar_pilihan = null;
		if (in_array($tabel_id, $tabel_referensi))
		{
			$tipe = 1;
			$daftar_pilihan = $this->referensi_model->list_data($tabel_referensi[$tabel_id])['nama'];
		}

		$data = [
			'tipe' => $tipe,
			'daftar_pilihan' => $daftar_pilihan
		];

		return $data;
	}
}
