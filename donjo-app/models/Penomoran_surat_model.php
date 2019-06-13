<?php

class Penomoran_surat_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Cari surat dengan nomor terakhir sesuai setting aplikasi
	 *
	 * @access	public
	 * @param		string 	nama tabel surat
	 * @return	array 	surat terakhir
	 */
	public function get_surat_terakhir($type, $url=null)
	{
		$thn = date('Y');
		$setting OR $setting = $this->setting->penomoran_surat;

		if ($setting == 3)
		{
			$last_sl= $this->get_surat_terakhir_type('log_surat', null, 1);
			$last_sm = $this->get_surat_terakhir_type('surat_masuk', null, 1);
			$last_sk = $this->get_surat_terakhir_type('surat_keluar', null, 1);

			$surat[$last_sl['no_surat']] = $last_sl;
			$surat[$last_sm['nomor_urut']] = $last_sm;
			$surat[$last_sk['nomor_urut']] = $last_sk;
			krsort($surat);

			return current($surat);
		}
		return $this->get_surat_terakhir_type($type, $url);
	}

	private function get_surat_terakhir_type($type, $url=null, $setting=null)
	{
		$thn = date('Y');
		$setting OR $setting = $this->setting->penomoran_surat;
		switch ($type)
		{
			default: show_error("Function $self(): Unknown type `$type`");

			case 'log_surat':
				if ($setting == 1)
				{
					$this->db->from("$type")
								->where('YEAR(tanggal)', $thn)
					      ->order_by('CAST(no_surat as unsigned) DESC')
					      ->limit(1);
				}
				else
				{
					$this->db->from("$type l")
					       ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
					       ->select('*, f.nama, l.id id_surat')
					       ->where('url_surat', $url)
					       ->where('YEAR(l.tanggal)', $thn)
					       ->order_by('CAST(l.no_surat as unsigned) DESC');
				}
				break;

			case 'surat_masuk':
				$this->db->from("$type")
							->where('YEAR(tanggal_surat)', $thn)
			        ->order_by('CAST(nomor_urut as unsigned) DESC')
				      ->limit(1);
				break;

			case 'surat_keluar':
				$this->db->from("$type")
							->where('YEAR(tanggal_surat)', $thn)
				      ->order_by('CAST(nomor_urut as unsigned) DESC')
				      ->limit(1);
		}
		$surat = $this->db->get()->result_array();
		$surat = $surat[0];
		$surat['nomor_urut'] OR $surat['nomor_urut'] = $surat['no_surat'];
		$surat['no_surat'] OR $surat['no_surat'] = $surat['nomor_urut'];
		$surat['tanggal_surat'] OR $surat['tanggal_surat'] = $surat['tanggal'];
		$surat['tanggal'] OR $surat['tanggal'] = $surat['tanggal_surat'];
		$surat['tanggal'] = tgl_indo2($surat['tanggal']);

		return $surat;
	}

	/**
	 * Periksa apakah nomor surat sudah digunakan sesuai setting aplikasi
	 *
	 * @access	public
	 * @param		string 		nama tabel surat
	 * @param		integer 	nomor urut atau nomor surat
	 * @param		string 		url surat untuk layanan surat
	 * @return	boolean 	apakah nomor surat sudah ada atau belum
	 */
	public function nomor_surat_duplikat($type, $nomor_surat, $url=null)
	{
		$thn = date('Y');
		$setting = $this->setting->penomoran_surat;
		if ($setting == 3)
		{
			// Nomor urut gabungan surat layanan, surat masuk dan surat keluar
			$sql = array();
			$sql[] = '('.$this->db->from("log_surat")
								->select('no_surat as nomor_urut')
								->where('YEAR(tanggal)', $thn)
								->where('no_surat', $nomor_surat)
								->get_compiled_select()
								.')';
			$sql[] = '('.$this->db->from("surat_masuk")
								->select('nomor_urut')
								->where('YEAR(tanggal_surat)', $thn)
								->where('nomor_urut', $nomor_surat)
								->get_compiled_select()
								.')';
			$sql[] = '('.$this->db->from("surat_keluar")
								->select('nomor_urut')
								->where('YEAR(tanggal_surat)', $thn)
								->where('nomor_urut', $nomor_surat)
								->get_compiled_select()
								.')';
			$sql = implode('
			UNION
			', $sql);
			$jml_surat = $this->db->query($sql)->num_rows();
			return $jml_surat > 0;
		}

		switch ($type)
		{
			default: show_error("Function $self(): Unknown type `$type`");

			case 'log_surat':
				if ($setting == 1)
				{
					$this->db->from("$type")
								->where('YEAR(tanggal)', $thn)
								->where('no_surat', $nomor_surat);
				}
				else
				{
					$this->db->from("$type l")
					      ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
					      ->select('*, f.nama, l.id id_surat')
					      ->where('url_surat', $url)
					      ->where('YEAR(l.tanggal)', $thn)
								->where('no_surat', $nomor_surat);
				}
				break;

			case 'surat_masuk':
				$this->db->from("$type")
								->where('YEAR(tanggal_surat)', $thn)
								->where('nomor_urut', $nomor_surat);
				break;

			case 'surat_keluar':
				$this->db->from("$type")
								->where('YEAR(tanggal_surat)', $thn)
								->where('nomor_urut', $nomor_surat);
		}
		$surat = $this->db->get()->row_array();
		return !empty($surat);
	}

	public function format_penomoran_surat($data)
	{
		$this->load->model('surat_model');
		$thn = date('Y');
		$setting = $this->setting->format_nomor_surat;
		$this->surat_model->substitusi_nomor_surat($data['input']['nomor'], $setting);
		$array_replace = array(
        "[kode_surat]" => $data['surat']['kode_surat'],
        "[tahun]" => $thn,
			  "[bulan_romawi]" => bulan_romawi((int)date("m")),
		);
		$setting = str_replace(array_keys($array_replace), array_values($array_replace), $setting);
		return $setting;
	}

}

?>
