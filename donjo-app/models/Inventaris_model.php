<?php class Inventaris_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function autocomplete()
	{
		$data = $this->db->select('nama')->order_by('nama')->get('jenis_barang')->result_array();
		$outp='';
		for ($i=0; $i<count($data); $i++)
		{
			$outp .= ',"'.$data[$i]['nama'].'"';
		}
		$outp = substr($outp, 1);
		$outp = '[' .$outp. ']';
		return $outp;
	}

	function get_jenis($id=0)
	{
		$hasil = $this->db->where('id',$id)->get('jenis_barang')->row_array();
		$status = $this->get_status_jenis($id,date('Y'));
		foreach($status as $key => $value)
		{
			$hasil[$key] = $value;
		}
		return $hasil;
	}

	function search_jenis_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (j.keterangan LIKE '$kw')";
			return $search_sql;
		}
	}

	function paging_jenis($p=1, $o=0)
	{
		$list_data_sql = $this->list_jenis_sql();
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_jenis_sql()
	{
		$sql = "
			FROM jenis_barang j
			WHERE 1 ";
		$sql .= $this->search_jenis_sql();
		return $sql;
	}

	function list_jenis($o=0, $offset=0, $limit=500)
	{
		$select_sql = "SELECT *
			";
		//Main Query
		$list_data_sql = $this->list_jenis_sql();
		$sql = $select_sql." ".$list_data_sql;

		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY j.nama'; break;
			case 2: $order_sql = ' ORDER BY j.nama DESC'; break;
			default:$order_sql = '';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();
		// Isi status setiap jenis barang yang ditampilkan
		for ($i=0; $i<count($data); $i++)
		{
			$status = $this->get_status_jenis($data[$i]['id'],date('Y'));
			foreach ($status as $key => $value)
			{
				$data[$i][$key] = $value;
			}
			$status = $this->status_awal_tahun($data[$i]['id']);
			foreach ($status as $key => $value)
			{
				$data[$i][$key] = $value;
			}
			$status = $this->status_akhir_tahun($data[$i]['id']);
			foreach ($status as $key => $value)
			{
					$data[$i][$key] = $value;
			}
		}
		return $data;
	}

	function get_status_jenis($id_jenis_barang, $tahun)
	{
		$select = "
				(select sum(jml_barang) from inventaris where id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as jml_barang,
				(select sum(jml_barang) from inventaris where asal_barang = 1 and id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as asal_sendiri,
				(select sum(jml_barang) from inventaris where asal_barang = 2 and id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as asal_pemerintah,
				(select sum(jml_barang) from inventaris where asal_barang = 3 and id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as asal_provinsi,
				(select sum(jml_barang) from inventaris where asal_barang = 4 and id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as asal_kab,
				(select sum(jml_barang) from inventaris where asal_barang = 5 and id_jenis_barang = $id_jenis_barang and DATE(tanggal_pengadaan) <= '$tahun-12-31') as asal_sumbangan,
				max(case when (jenis_mutasi = 1 or jenis_mutasi = 4) then tanggal_mutasi else 0 end) tgl_penghapusan,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) then jml_mutasi else 0 end) penghapusan,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and asal_barang = 1 then jml_mutasi else 0 end) hapus_asal_sendiri,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and asal_barang = 2 then jml_mutasi else 0 end) hapus_asal_pemerintah,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and asal_barang = 3 then jml_mutasi else 0 end) hapus_asal_provinsi,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and asal_barang = 4 then jml_mutasi else 0 end) hapus_asal_kab,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and asal_barang = 5 then jml_mutasi else 0 end) hapus_asal_sumbangan,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 1 then jml_mutasi else 0 end) hapus_rusak,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 2 then jml_mutasi else 0 end) hapus_dijual,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 3 then jml_mutasi else 0 end) hapus_sumbangkan,
        sum(case when jenis_mutasi = 2 then jml_mutasi else 0 end) status_rusak,
        sum(case when jenis_mutasi = 3 then jml_mutasi else 0 end) status_diperbaiki,
        sum(case when jenis_mutasi = 4 then jml_mutasi else 0 end) hapus_barang_rusak
    ";
    $status = $this->db->select($select)
			->where("DATE(tanggal_mutasi) <= '".$tahun."-12-31'")
			->where("DATE(tanggal_pengadaan) <= '".$tahun."-12-31'")
    	->where('i.id_jenis_barang',$id_jenis_barang)
			->join('mutasi_inventaris m','i.id = m.id_barang')
    	->get('inventaris i')
    	->row_array();
    $status['asal_sendiri'] = $status['asal_sendiri'] - $status['hapus_asal_sendiri'];
    $status['asal_pemerintah'] = $status['asal_pemerintah'] - $status['hapus_asal_pemerintah'];
    $status['asal_provinsi'] = $status['asal_provinsi'] - $status['hapus_asal_provinsi'];
    $status['asal_kab'] = $status['asal_kab'] - $status['hapus_asal_kab'];
    $status['asal_sumbangan'] = $status['asal_sumbangan'] - $status['hapus_asal_sumbangan'];
    $status['jml_sekarang'] = $status['jml_barang'] - $status['penghapusan'];
    $status['status_rusak'] = $status['status_rusak'] - $status['status_diperbaiki'] - $status['hapus_barang_rusak'];
    $status['status_baik'] = $status['jml_sekarang'] - $status['status_rusak'];
    return $status;
	}

	function status_awal_tahun($id_jenis_barang)
	{
		if (!isset($_SESSION['tahun'])) return;
		$tahun = $_SESSION['tahun']-1;
		$status = $this->get_status_jenis($id_jenis_barang,$tahun);
		$status_awal_tahun = array();
		$status_awal_tahun['status_rusak_awal'] = $status['status_rusak'];
		$status_awal_tahun['status_baik_awal'] = $status['status_baik'];
		return $status_awal_tahun;
	}

	function status_akhir_tahun($id_jenis_barang)
	{
		if (!isset($_SESSION['tahun'])) return;
		$tahun = $_SESSION['tahun'];
		$status = $this->get_status_jenis($id_jenis_barang,$tahun);
		$status_akhir_tahun = array();
		$status_akhir_tahun['status_rusak_akhir'] = $status['status_rusak'];
		$status_akhir_tahun['status_baik_akhir'] = $status['status_baik'];
		return $status_akhir_tahun;
	}

	function insert_jenis()
	{
		$data = $_POST;
		$outp = $this->db->insert('jenis_barang',$data);
		if (!$outp) session_error(); else session_success();
	}

	function update_jenis($id='')
	{
	  $data = $_POST;
		$outp = $this->db->where('id',$id)->update('jenis_barang',$data);
		if (!$outp) session_error(); else session_success();
	}

	function delete_jenis($id='')
	{
		$outp = $this->db->where('id',$id)->delete('jenis_barang');
		if (!$outp) session_error(); else session_success();
	}

	function get_inventaris($id=0)
	{
		$hasil = $this->db->where('id',$id)->get('inventaris')->row_array();
		$hasil['tanggal_pengadaan'] = tgl_indo_out($hasil['tanggal_pengadaan']);

    $status = $this->get_status_inventaris($id,$hasil);
    foreach ($status as $key => $value)
    {
    	$hasil[$key] = $value;
    }
		return $hasil;
	}

	function get_status_inventaris($id_barang,$inventaris)
	{
		$select = "
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) then jml_mutasi else 0 end) penghapusan,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 1 then jml_mutasi else 0 end) hapus_rusak,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 2 then jml_mutasi else 0 end) hapus_dijual,
        sum(case when (jenis_mutasi = 1 or jenis_mutasi = 4) and jenis_penghapusan = 3 then jml_mutasi else 0 end) hapus_sumbangkan,
        sum(case when jenis_mutasi = 2 then jml_mutasi else 0 end) status_rusak,
        sum(case when jenis_mutasi = 3 then jml_mutasi else 0 end) status_diperbaiki,
        sum(case when jenis_mutasi = 4 then jml_mutasi else 0 end) hapus_barang_rusak,
        max(tanggal_mutasi) as mutasi_terakhir
    ";
    $status = $this->db->select($select)->where('id_barang',$id_barang)->get('mutasi_inventaris')->row_array();
    $status['jml_sekarang'] = $inventaris['jml_barang'] - $status['penghapusan'];
    $status['status_rusak'] = $status['status_rusak'] - $status['status_diperbaiki'] - $status['hapus_barang_rusak'];
    $status['status_baik'] = $status['jml_sekarang'] - $status['status_rusak'];
    $status['mutasi_terakhir'] = tgl_indo_out($status['mutasi_terakhir']);
    return $status;
	}

	function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (i.keterangan LIKE '$kw')";
			return $search_sql;
		}
	}

	function paging($id_jenis, $p=1, $o=0)
	{
		$list_data_sql = $this->list_data_sql($id_jenis);
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		return $this->paging;
	}

	// Digunakan untuk paging dan query utama supaya jumlah data selalu sama
	private function list_data_sql($id_jenis)
	{
		$sql = "
			FROM inventaris i
			WHERE i.id_jenis_barang = $id_jenis ";
		$sql .= $this->search_sql();
		return $sql;
	}

	function list_data($id_jenis=0, $o=0, $offset=0, $limit=500)
	{
		$select_sql = "SELECT *
			";
		//Main Query
		$list_data_sql = $this->list_data_sql($id_jenis);
		$sql = $select_sql." ".$list_data_sql;

		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY i.nama_barang'; break;
			case 2: $order_sql = ' ORDER BY i.nama_barang DESC'; break;
			case 3: $order_sql = ' ORDER BY i.asal_barang'; break;
			case 4: $order_sql = ' ORDER BY i.asal_barang DESC'; break;
			case 5: $order_sql = ' ORDER BY i.tanggal_pengadaan'; break;
			case 6: $order_sql = ' ORDER BY i.tanggal_pengadaan DESC'; break;
			default:$order_sql = ' ORDER BY i.tanggal_pengadaan DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$status = $this->get_status_inventaris($data[$i]['id'],$data[$i]);
			foreach ($status as $key => $value)
			{
				$data[$i][$key] = $value;
			}
		}

		return $data;
	}

	function insert($id_jenis_barang)
	{
		$data = $_POST;
		$data['id_jenis_barang'] = $id_jenis_barang;
		$data['tanggal_pengadaan'] = tgl_indo_in($data['tanggal_pengadaan']);
		$outp = $this->db->insert('inventaris',$data);
		if (!$outp) session_error(); else session_success();
	}

	function update($id='')
	{
	  $data = $_POST;
		$data['tanggal_pengadaan'] = tgl_indo_in($data['tanggal_pengadaan']);
		$outp = $this->db->where('id',$id)->update('inventaris',$data);
		if (!$outp) session_error(); else session_success();
	}

	function delete($id='')
	{
		$outp = $this->db->where('id',$id)->delete('inventaris');
		if (!$outp) session_error(); else session_success();
	}

	function list_mutasi($id_inventaris)
	{
		$hasil = $this->db->where('id_barang',$id_inventaris)->order_by('tanggal_mutasi DESC')->get('mutasi_inventaris')->result_array();
		return $hasil;
	}

	function get_mutasi($id=0)
	{
		$hasil = $this->db->where('id',$id)->get('mutasi_inventaris')->row_array();
		$hasil['tanggal_mutasi'] = tgl_indo_out($hasil['tanggal_mutasi']);
		return $hasil;
	}

	function insert_mutasi($id_inventaris)
	{
		$data = $_POST;
		$data['id_barang'] = $id_inventaris;
		$data['tanggal_mutasi'] = tgl_indo_in($data['tanggal_mutasi']);
		$outp = $this->db->insert('mutasi_inventaris',$data);
		if (!$outp) session_error(); else session_success();
	}

	function update_mutasi($id)
	{
		$data = $_POST;
		$data['tanggal_mutasi'] = tgl_indo_in($data['tanggal_mutasi']);
		$outp = $this->db->where('id',$id)->update('mutasi_inventaris',$data);
		if (!$outp) session_error(); else session_success();
	}

	function delete_mutasi($id)
	{
		$outp = $this->db->where('id',$id)->delete('mutasi_inventaris');
		if (!$outp) session_error(); else session_success();
	}

	function list_tahun()
	{
		$tahun = $this->db->distinct()->select('YEAR(tanggal_pengadaan) AS tahun')->order_by('tanggal_pengadaan DESC')->get('inventaris')->result_array();
		return $tahun;
	}

}
