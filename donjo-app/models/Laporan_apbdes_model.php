<?php 

class Laporan_apbdes_model extends CI_Model {

	private $table = "laporan_apbdes";
	
	const ORDER_ABLE_APBDES = [
		2 => 'nama',
		3 => 'tahun',
		4 => 'semester',
		5 => 'nama_file',
		6 => 'updated_at',
		7 => 'kirim'
	];
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_apbdes(string $search = '', $tahun = NULL)
	{
		$this->db->from($this->table);

		if ($search)
		{
			$this->db
				->group_start()
					->like('nama', $search)
					->or_like('tahun', $search)
					->or_like('semester', $search)
					->or_like('nama_file', $search)
				->group_end();
		}

		if ($tahun) $this->db->where('tahun', $tahun);
		
		return $this->db;
	}

	public function find($id)
	{
		return $this->db->get_where($this->table, ['id' => $id])->row();
	}

	public function get_tahun()
	{
		$data = $this->db
			->distinct()
			->select('tahun')
			->get($this->table)
			->result();

		return $data;
	}

	public function insert()
	{
		$data = $this->validasi();
		$outp = $this->db->insert($this->table, $data);

		status_sukses($outp);
	}

	public function update($id)
	{
		$data = $this->validasi();
		$data['updated_at'] = date('Y-m-d H:i:s');
		$outp = $this->db->where('id', $id)->where('kirim', NULL)->update($this->table, $data);

		status_sukses($outp);
	}

	public function delete($id)
	{	
		if ($nama_file = $this->find($id)->nama_file)
		{
			unlink(LOKASI_DOKUMEN . $nama_file);
		}

		$outp = $this->db->where('id', $id)->delete($this->table);

		status_sukses($outp);
	}

	public function delete_all()
	{
		foreach ($this->input->post('id_cb') as $id)
		{
			$this->delete($id);
		}
	}

	private function validasi()
	{
		$post = $this->input->post();

		$data = [
			'judul' => alfanumerik($post['judul']),
			'tahun' => bilangan($post['tahun']),
			'semester' => bilangan($post['semester']),
			'nama_file' => $this->upload($post['judul'], $post['old_file']),
		];

		return $data;
	}

	private function upload($nama_file, $old_file)
	{
		$this->load->library('upload');

		$config['upload_path'] = LOKASI_DOKUMEN;
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = 2048;
		$config['file_name'] = namafile($nama_file);

		$this->upload->initialize($config);

		if ( ! $this->upload->do_upload('nama_file'))
		{
			$this->session->error_msg = $this->upload->display_errors();
			$this->session->success = -1;
			return NULL;
		}

		$upload = $this->upload->data();

		if ($old_file) unlink(LOKASI_DOKUMEN . $old_file);
		
		return $upload['file_name'];
	}

	public function opendk($id)
	{
		$list_data = $this->db
			->where_in('id', $id)
			->get($this->table)
			->result_array();

		$kirim = [];
		foreach($list_data as $key => $data)
		{
			$kirim[$key]['id'] = $data['id'];
			$kirim[$key]['judul'] = $data['judul'];
			$kirim[$key]['tahun'] = $data['tahun'];
			$kirim[$key]['semester'] = $data['semester'];
			$kirim[$key]['nama_file'] = $data['nama_file'];
			$kirim[$key]['created_at'] = $data['created_at'];
			$kirim[$key]['updated_at'] = $data['updated_at'];
			$kirim[$key]['file'] = $this->file($data['nama_file']);
		}

		return $kirim;
	}

	public function file($nama_file)
	{
		return base64_encode(file_get_contents(LOKASI_DOKUMEN . $nama_file));
	}

	public function kirim($id)
	{
		$data['kirim'] = date('Y-m-d H:i:s');
		$outp = $this->db->where_in('id', $id)->update($this->table, $data);

		status_sukses($outp);
	}
}