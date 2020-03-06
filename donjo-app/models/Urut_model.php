<?php

class Urut_model extends CI_Model {

	private $tabel;
	private $kolom_id;

	public function __construct($tabel, $kolom_id='id')
	{
		parent::__construct();
		$this->tabel = $tabel;
		$this->kolom_id = $kolom_id;
	}

	/**
	 * Cari nomor urut terbesar untuk subset data
	 *
	 * @access	public
	 * @param		array		syarat kolom data yang akan diperiksa
	 * @return	integer	nomor urut maksimum untuk subset
	 */
  public function urut_max($subset=array('1' => '1'))
  {
    $urut = $this->db->select_max('urut')
	  	->where($subset)
    	->get($this->tabel)
    	->row()->urut;
    return $urut;
  }

	private function urut_semua($subset=array('1' => '1'))
	{
		$urut_duplikat = $this->db->select('urut, COUNT(*) c')
			->where($subset)
			->group_by('urut')
			->having('c > 1')
			->get($this->tabel)->result_array();
		$belum_diurut = $this->db
			->where($subset)
			->where('urut IS NULL')
			->limit(1)
			->get($this->tabel)->row_array();
		$daftar = array();
		if ($urut_duplikat OR $belum_diurut)
		{
			$daftar = $this->db->select($this->kolom_id)
				->where($subset)
				->order_by("urut")
				->get($this->tabel)->result_array();
		}
		for ($i=0; $i<count($daftar); $i++)
		{
			$this->db->where($this->kolom_id, $daftar[$i][$this->kolom_id]);
			$data['urut'] = $i + 1;
			$this->db->update($this->tabel, $data);
		}
	}

	// $arah:
	//		1 - turun
	// 		2 - naik
	public function urut($id, $arah, $subset=array('1' => '1'))
	{
		$this->urut_semua($subset);
		$unsur1 = $this->db->where($this->kolom_id, $id)
			->get($this->tabel)
			->row_array();

		$daftar = $this->db->select("{$this->kolom_id}, urut")
			->where($subset)
			->order_by("urut")
			->get($this->tabel)
			->result_array();
		$this->urut_daftar($id, $arah, $daftar, $unsur1);
	}

	private function urut_daftar($id, $arah, $daftar, $unsur1)
	{
		for ($i=0; $i<count($daftar); $i++)
		{
			if ($daftar[$i][$this->kolom_id] == $id)
				break;
		}

		if ($arah == 1)
		{
			if ($i >= count($daftar) - 1) return;
			$unsur2 = $daftar[$i + 1];
		}
		if ($arah == 2)
		{
			if ($i <= 0) return;
			$unsur2 = $daftar[$i - 1];
		}

		// Tukar urutan
		$this->db->where($this->kolom_id, $unsur2[$this->kolom_id])->
			update($this->tabel, array('urut' => $unsur1['urut']));
		$this->db->where($this->kolom_id, $unsur1[$this->kolom_id])->
			update($this->tabel, array('urut' => $unsur2['urut']));		
	}

}

?>
