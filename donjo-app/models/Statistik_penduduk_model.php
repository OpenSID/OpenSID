<?php class Statistik_penduduk_model extends Laporan_penduduk_model {

/* Gunakan model ini untuk mulai refactor statistik penduduk
 * Mungkin bisa gunakan anonymous classes yg disediakan di PHP 7.x
 * Usahakan supaya di Laporan_penduduk_model juga menggunakan query builder Codeigniter
*/

	public function __construct()
	{
		parent::__construct();
		$this->load->model('program_bantuan_model');
	}

	private function order_sql($o)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $this->db->order_by('u.id'); break;
			case 2: $this->db->order_by('u.id DESC'); break;
			case 3: $this->db->order_by('laki'); break;
			case 4: $this->db->order_by('laki DESC'); break;
			case 5: $this->db->order_by('jumlah'); break;
			case 6: $this->db->order_by('jumlah DESC'); break;
			case 7: $this->db->order_by('perempuan'); break;
			case 8: $this->db->order_by('perempuan DESC'); break;
		}
	}

	private function jml_per_kategori()
	{
		// Ambil data sasaean penduduk
		$this->db->select('u.id, u.nama')
			->select('COUNT(pp.id) AS jumlah')
		  ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		  ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
			->from('program u')
			->join('program_peserta pp', 'pp.program_id = u.id', 'left')
			->join('tweb_penduduk p', 'pp.peserta = p.nik', 'left')
			->where('u.sasaran', '1')
			->group_by('u.id');
		$this->$order_sql;
		$penduduk = $this->db->get_compiled_select();

		// Ambil data sasaean keluarga
		// $this->db->select('u.id, u.nama')
		// 	->select('COUNT(pp.id) AS jumlah')
		//   ->select('COUNT(CASE WHEN p.sex = 1 THEN pp.id END) AS laki')
		//   ->select('COUNT(CASE WHEN p.sex = 2 THEN pp.id END) AS perempuan')
		// 	->from('program u')
		// 	->join('program_peserta pp', 'pp.program_id = u.id', 'left')
		// 	->join('tweb_keluarga k', 'pp.peserta = k.no_kk', 'left')
		// 	->join('tweb_penduduk p', 'k.nik_kepala = p.id', 'left')
		// 	->where('u.sasaran', '2')
		// 	->group_by('u.id');
		// $this->$order_sql;
		// $kepala_keluarga = $this->db->get_compiled_select();

		// $data = $this->db->query($penduduk . ' UNION ' . $kepala_keluarga)
		// 	->result_array();

		$data = $this->db->query($penduduk)->result_array();
		return $data;
	}

	private function jml_semua()
	{
		// Siapkan jumlah
		$sql3 = "SELECT ";
		$sql3 .= $this->get_jumlah_sql(false, true);
		$sql3 .= $this->get_laki_sql(false, true);
		$sql3 .= $this->get_perempuan_sql(false);

		$query3 = $this->db->query($sql3);
		$semua = $query3->row_array();
		$semua['no'] = "";
		$semua['id'] = "";
		$semua['nama'] = "TOTAL";
		$semua['persen'] = "100%";

		$semua['persen1'] = $semua['laki']/$semua['jumlah']*100;
		$semua['persen1'] = number_format((float)$bel['persen1'], 2, '.', '');
		$semua['persen1'] = $semua['persen1']."%";

 		$semua['persen2'] = $semua['perempuan']/$semua['jumlah']*100;
		$semua['persen2'] = number_format((float)$bel['persen2'], 2, '.', '');
		$semua['persen2'] = $semua['persen2']."%";
		return $semua;
	}

	private function hitung_total(&$data)
	{
		$total['jumlah'] = 0;
		$total['laki'] = 0;
		$total['perempuan'] = 0;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$total['jumlah'] += $data[$i]['jumlah'];
			$total['laki'] += $data[$i]['laki'];
			$total['perempuan'] += $data[$i]['perempuan'];
		}
		return $total;
	}

	public function list_data($o)
	{
		$data = $this->jml_per_kategori($o);
		$semua = $this->jml_semua();
		$total = $this->hitung_total($data);

		// Isi Total
		$baris_jumlah = array(
			'no' => "",
			'id' => JUMLAH,
			'nama' => "PENERIMA",
			'jumlah' => $total['jumlah'],
			'perempuan' => $total['perempuan'],
			'laki' => $total['laki']
		);
		$data[] = $baris_jumlah;

		// Isi data jml belum mengisi
		$baris_belum = array(
			'no' => "",
			'id' => BELUM_MENGISI,
			'nama' => "BUKAN PENERIMA",
			'jumlah' => $semua['jumlah'] - $total['jumlah'],
			'perempuan' => $semua['perempuan'] - $total['perempuan'],
			'laki' => $semua['laki'] - $total['laki']
		);
		$data[] = $baris_belum;

		// Hitung semua presentase
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['persen'] = $data[$i]['jumlah']/$semua['jumlah']*100;
			$data[$i]['persen'] = number_format((float)$data[$i]['persen'], 2, '.', '');
			$data[$i]['persen'] = $data[$i]['persen']."%";

			$data[$i]['persen1'] = $data[$i]['laki']/$semua['jumlah']*100;
			$data[$i]['persen1'] = number_format((float)$data[$i]['persen1'], 2, '.', '');
			$data[$i]['persen1'] = $data[$i]['persen1']."%";

			$data[$i]['persen2'] = $data[$i]['perempuan']/$semua['jumlah']*100;
			$data[$i]['persen2'] = number_format((float)$data[$i]['persen2'], 2, '.', '');
			$data[$i]['persen2'] = $data[$i]['persen2']."%";
		}

		$data['total'] = $semua;
		return $data;
	}

}

?>
