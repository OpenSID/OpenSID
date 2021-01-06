<?php

class Inventaris_peralatan_model extends CI_Model
{

	protected $table = 'inventaris_peralatan';
	protected $table_mutasi = 'mutasi_inventaris_peralatan';
	protected $table_pamong = 'tweb_desa_pamong';

	public function __construct()
	{
		parent::__construct();
	}

	public function list_aset()
	{
		$this->db
				->select('*')
				->from('tweb_aset u')
				->where('golongan',2);
		$data = $this->db->get()->result_array();
		return $data;
	}

	function count_reg()
	{
		$this->db->select('count(id) AS count');
		$this->db->from($this->table);
		$data = $this->db->get()->row();
		return $data;
	}

	function list_inventaris_kd_register()
	{
		$this->db->select($this->table.'.register');
		$this->db->from($this->table);
		$data = $this->db->get()->result();
		return $data;
	}

	public function list_inventaris()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.visible', 1);
		$data = $this->db->get()->result();
		return $data;
	}

	public function sum_inventaris()
	{
		$this->db->select_sum('harga');
		$this->db->where($this->table.'.visible', 1);
		$this->db->where($this->table.'.status', 0);
		$result = $this->db->get($this->table)->row();
		return $result->harga;
	}

	public function sum_print($tahun)
	{
		$this->db->select_sum('harga');
		$this->db->where($this->table.'.visible', 1);
		$this->db->where($this->table.'.status', 0);
		if ($tahun != 1)
		{
			$this->db->where($this->table.'.tahun_pengadaan', $tahun);
		}
		$result = $this->db->get($this->table)->row();
		return $result->harga;
	}

	public function list_mutasi_inventaris()
	{
		$this->db->select('mutasi_inventaris_peralatan.id as id,mutasi_inventaris_peralatan.*,  inventaris_peralatan.nama_barang, inventaris_peralatan.kode_barang, inventaris_peralatan.tahun_pengadaan, inventaris_peralatan.register');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.visible', 1);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_peralatan', 'left');
		$data = $this->db->get()->result();
		return $data;
	}

	public function add($data)
	{
		$this->db->insert($this->table, array_filter($data));
		$id = $this->db->insert_id();
		$inserted = $this->db->get_where($this->table, array('id' => $id))->row();
		return $inserted;
	}

	public function add_mutasi($data)
	{
		$this->db->insert($this->table_mutasi, array_filter($data));
		$id = $this->db->insert_id();
		$this->db->update($this->table, array('status' => 1), array('id' => $data['id_inventaris_peralatan']));
		$inserted = $this->db->get_where($this->table_mutasi, array('id' => $id))->row();
		return $inserted;
	}

	public function view($id)
	{
		$this->db->select('*');
		$this->db->from($this->table);
        $this->db->where($this->table.'.id', $id);
		$data = $this->db->get()->row();
		return $data;
	}

	public function view_mutasi($id)
	{
		$this->db->select('mutasi_inventaris_peralatan.id as id,mutasi_inventaris_peralatan.*,  inventaris_peralatan.nama_barang, inventaris_peralatan.kode_barang, inventaris_peralatan.tahun_pengadaan, inventaris_peralatan.register');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.id', $id);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_peralatan', 'left');
		$data = $this->db->get()->row();
		return $data;
	}

	public function edit_mutasi($id)
	{
		$this->db->select('mutasi_inventaris_peralatan.id as id,mutasi_inventaris_peralatan.*,  inventaris_peralatan.nama_barang, inventaris_peralatan.kode_barang, inventaris_peralatan.tahun_pengadaan, inventaris_peralatan.register');
		$this->db->from($this->table_mutasi);
		$this->db->where($this->table_mutasi.'.id', $id);
		$this->db->join($this->table, $this->table.'.id = '.$this->table_mutasi.'.id_inventaris_peralatan', 'left');
		$data = $this->db->get()->row();
		return $data;
	}

	public function delete($id)
	{
		$hasil = $this->db->update($this->table, array('visible' => 0), array('id' => $id));
		return $hasil;
	}

	public function delete_mutasi($id)
	{
		$hasil = $this->db->update($this->table_mutasi, array('visible' => 0), array('id' => $id));
		return $hasil;
	}

	public function update($id, $data)
	{
		$id = $this->input->post('id');
		$hasil = $this->db->update($this->table, $data, array('id' => $id));
		return $hasil;
	}

	public function update_mutasi($id, $data)
	{
		$id = $this->input->post('id');
		$hasil = $this->db->update($this->table_mutasi, $data, array('id' => $id));
		return $hasil;
	}

	public function cetak($tahun)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where($this->table.'.status', 0);
		$this->db->where($this->table.'.visible', 1);
		if ($tahun != 1)
		{
			$this->db->where($this->table.'.tahun_pengadaan', $tahun);
		}
		$this->db->order_by($this->table.'.tahun_pengadaan', "asc");
		$data = $this->db->get()->result();
		return $data;
	}

	public function pamong($pamong)
	{
		$this->db->select('*');
		$this->db->from($this->table_pamong);
		$this->db->where($this->table_pamong.'.pamong_id', $pamong);
		$data = $this->db->get()->row();
		return $data;
	}

}