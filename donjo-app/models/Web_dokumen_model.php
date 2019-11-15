<?php
class Web_dokumen_model extends CI_Model {

	// Untuk datatables informasi publik
	var $table = 'dokumen_hidup';
	var $column_order = array(null, 'nama','tahun', 'kategori_info_publik', 'tgl_upload'); //set column field database for datatable orderable
	var $column_search = array('nama'); //set column field database for datatable searchable
	var $order = array('id' => 'asc'); // default order


	public function __construct()
	{
		parent::__construct();
		$this->load->model('referensi_model');
	}

	// Ambil semua peraturan
	public function all_peraturan($kategori='', $tahun='', $isi='')
	{
		$kategori_peraturan = array('2', '3');
		$this->db->select('dokumen.id, satuan, dokumen.nama, tahun, ref_dokumen.nama as kategori');
		$this->db->join('ref_dokumen', 'ref_dokumen.id = dokumen.kategori', 'left');
		$this->db->where('dokumen.enabled', 1);
		$this->db->where_in('ref_dokumen.id', $kategori_peraturan);

		if ($kategori) $this->db->where('dokumen.kategori', $kategori);
		if ($tahun) $this->db->where('tahun', $tahun);
		if ($isi) $this->db->like('dokumen.nama', $isi);
		$this->db->order_by('dokumen.tahun DESC', 'dokumen.kategori ASC', 'dokumen.nama ASC');

		$res = $this->db->from('dokumen_hidup as dokumen')->get()->result_array();
		return $res;
	}

	// ================= informasi publik ===================
	// https://mbahcoding.com/tutorial/php/codeigniter/codeigniter-simple-server-side-datatable-example.html


	private function get_all_informasi_publik_query()
	{
		$this->db->from($this->table)
			->where('id_pend', '0')
			->where('enabled', '1')
			->where('id_pend', '0');
	}

	private function get_informasi_publik_query()
	{
		$this->get_all_informasi_publik_query();

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{
				if ($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if (isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function get_informasi_publik()
	{
		$this->get_informasi_publik_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function count_informasi_publik_filtered()
	{
		$this->get_informasi_publik_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_informasi_publik_all()
	{
		$this->get_all_informasi_publik_query();
		return $this->db->count_all_results();
	}

	// ============== akhir informasi publik ===================


	// Lists Tahun Dokumen untuk web first
	public function tahun_dokumen()
	{
		$this->db->select('tahun');
		$this->db->group_by('tahun');
		$res = $this->db->from($this->table)->get()->result_array();
		return $res;
	}

	public function autocomplete()
	{
		$str = autocomplete_str('nama', 'dokumen_hidup');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (satuan LIKE '$kw' OR nama LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND enabled = $kf";
			return $filter_sql;
		}
	}

	private function list_data_sql($kat)
	{
		$sql = " FROM dokumen_hidup WHERE id_pend = 0";
		// $kat == 1 adalah informasi publik dan mencakup juga jenis dokumen lain termasuk SK Kades dan Perdes
		if ($kat != '1')
			$sql .= " AND kategori = ".$kat;
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function paging($kat, $p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) AS jml".$this->list_data_sql($kat);
		$sql .= $this->search_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($kat, $o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			case 5: $order_sql = ' ORDER BY tgl_upload'; break;
			case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT * ".$this->list_data_sql($kat);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$data[$i]['attr'] = json_decode($data[$i]['attr'], true);
			// Ambil keterangan kategori publik
			if ($data[$i]['kategori_info_publik'])
				$data[$i]['kategori_info_publik'] = $this->referensi_model->list_kode_array(KATEGORI_PUBLIK)[$data[$i]['kategori_info_publik']];

			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";

			$j++;
		}
		return $data;
	}

	private function semua_mime_type()
	{
		$semua_mime_type = array_merge(unserialize(MIME_TYPE_DOKUMEN), unserialize(MIME_TYPE_GAMBAR), unserialize(MIME_TYPE_ARSIP));
		$semua_mime_type = array_diff($semua_mime_type, array('application/octet-stream'));
		return $semua_mime_type;
	}

	private function semua_ext()
	{
		$semua_ext = array_merge(unserialize(EXT_DOKUMEN), unserialize(EXT_GAMBAR), unserialize(EXT_ARSIP));
		return $semua_ext;
	}

	private function upload_dokumen(&$data, $file_lama="")
	{
		$_SESSION['error_msg'] = "";
		$_SESSION['success'] = 1;
		unset($data['old_file']);
		if (empty($_FILES['satuan']['tmp_name']))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] .= ' -> Error upload file. Periksa apakah melebihi ukuran maksimum';
			return false;
		}

		$lokasi_file = $_FILES['satuan']['tmp_name'];
		if (empty($lokasi_file))
		{
			$_SESSION['success'] = -1;
			return false;
		}
		if (function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$tipe_file = finfo_file($finfo, $lokasi_file);
		}
		else
			$tipe_file = $_FILES['satuan']['type'];
		$nama_file = $_FILES['satuan']['name'];
		$nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$ext = get_extension($nama_file);

		if (!in_array($tipe_file, $this->semua_mime_type()) OR !in_array($ext, $this->semua_ext()))
		{
			$_SESSION['error_msg'] .= " -> Jenis file salah: " . $tipe_file . " " . $ext;
			$_SESSION['success'] = -1;
			return false;
		}
		elseif (isPHP($lokasi_file, $nama_file))
		{
			$_SESSION['error_msg'].= " -> File berisi script ";
			$_SESSION['success']=-1;
			return false;
		}

		$nama = $data['nama'];
		if (!empty($data['id_pend']))
			$nama_file = $data['id_pend']."_".$nama."_".generator(6)."_".$nama_file;
		else
			$nama_file = $nama."_".generator(6)."_".$nama_file;
		$nama_file = bersihkan_namafile($nama_file);
		UploadDocument($nama_file, $file_lama);
		$data['satuan'] = $nama_file;
		return true;
	}

	public function insert()
	{
		$data = $_POST;
		if ($this->upload_dokumen($data))
		{
			$data['attr'] = json_encode($data['attr']);
			$tgl = json_decode($data['attr'], TRUE);
			switch ($data['kategori'])
			{
				case 1:
					$data['tahun'] = $this->input->post('tahun');
					break;
				case 2:
					$data['tahun'] = date('Y', strtotime($tgl['tgl_kep_kades']));
					$data['kategori_info_publik'] = '3';
					break;
				case 3:
					$data['tahun'] = date('Y', strtotime($tgl['tgl_ditetapkan']));
					$data['kategori_info_publik'] = '3';
					break;

				default:
					$data['tahun'] = date('Y');
					break;
			}
			return $this->db->insert('dokumen', $data);
		}
		else return false;
	}

	public function update($id=0)
	{
		$data = $_POST;
		if (empty($data['satuan']) or !$this->upload_dokumen($data, $data['old_file']))
		{
			unset($data['satuan']);
			unset($data['old_file']);
		}
		$data['attr'] = json_encode($data['attr']);
		$tgl = json_decode($data['attr'], TRUE);
		switch ($data['kategori'])
		{
			case 1:
				$data['tahun'] = $this->input->post('tahun');
				break;
			case 2:
				$data['tahun'] = date('Y', strtotime($tgl['tgl_kep_kades']));
				$data['kategori_info_publik'] = '3';
				break;
			case 3:
				$data['tahun'] = date('Y', strtotime($tgl['tgl_ditetapkan']));
				$data['kategori_info_publik'] = '3';
				break;

			default:
				$data['tahun'] = date('Y');
				break;
		}
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->where('id',$id)->update('dokumen', $data);
	}

	// Soft delete, tapi hapus berkas dokumen
	public function delete($id='')
	{
		$old_dokumen = $this->db->select('satuan')->
			where('id',$id)->
			get('dokumen')->row()->satuan;
		$data = array(
			'updated_at' => date('Y-m-d H:i:s'),
			'deleted' => 1
		);
		$outp = $this->db->where('id', $id)->update('dokumen', $data);
		if ($outp)
			unlink(LOKASI_DOKUMEN . $old_dokumen);
		else $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->delete($id);
			}
		}
		else $_SESSION['success']=-1;
	}

	public function dokumen_lock($id='', $val=0)
	{
		$sql = "UPDATE dokumen SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function get_dokumen($id=0)
	{
		$data = $this->db->from($this->table)
			->where('id', $id)
			->get()->row_array();
		$data['attr'] = json_decode($data['attr'], true);
		return $data;
	}

	public function kat_nama($kat=1)
	{
		$kategori = $this->list_kategori();
		$kat_nama = $kategori[$kat];
		if (empty($kat_nama)) $kat_nama = $kategori[0];
		return $kat_nama;
	}

	public function list_kategori()
	{
		return $this->referensi_model->list_nama('ref_dokumen');
	}

	public function list_tahun($kat=1)
	{
		$list_tahun = array();
		// Data tanggal berbeda menurut kategori dokumen
		// Informasi masing2 kategori dokumen tersimpan dalam format json di kolom attr
		// MySQL baru memiliki fitur query json mulai dari 5.7; jadi di sini dilakukan secara manual

		switch ($kat)
		{
			case '1':
				# Informasi publik, termasuk kategori lainnya
				$this->db->select('tahun');
				break;
			case '2':
				# SK KADES
				$attr_str = '"tgl_kep_kades":';
				$this->db->select("SUBSTR(attr FROM LOCATE('$attr_str', attr)+LENGTH('$attr_str')+7 FOR 4) AS tahun")
					->where('kategori', $kat);
				break;
			case '3':
				# PERDES
				$attr_str = '"tgl_ditetapkan":';
				$this->db->select("SUBSTR(attr FROM LOCATE('$attr_str', attr)+LENGTH('$attr_str')+7 FOR 4) AS tahun")
					->where('kategori', $kat);
				break;
		}

		$list_tahun = $this->db->distinct()
			->from($this->table)
			->order_by('tahun DESC')
			->get()->result_array();
		return $list_tahun;
	}

	public function data_cetak($kat=1, $tahun='')
	{
		if (!empty($tahun))
		{
			switch ($kat)
			{
				case '1':
					# Informasi publik
					$this->db->where('tahun', $tahun);
					break;
				// Data tanggal berbeda menurut kategori dokumen
				// Informasi masing2 kategori dokumen tersimpan dalam format json di kolom attr
				// MySQL baru memiliki fitur query json mulai dari 5.7; jadi di sini dilakukan secara manual
				case '2':
					# SK KADES
					$regex = '"tgl_kep_kades":"[[:digit:]]{2}-[[:digit:]]{2}-' . $tahun;
					$this->db->where("attr REGEXP '" . $regex . "'");
					break;
				case '3':
					# PERDES
					$regex = '"tgl_ditetapkan":"[[:digit:]]{2}-[[:digit:]]{2}-'. $tahun;
					$this->db->where("attr REGEXP '" . $regex . "'");
					break;
			}
		}
		# Informasi publik termasuk kategori lainnya
		if ($kat != '1') $this->db->where('kategori', $kat);
		$data = $this->db->select('*')
			->from($this->table)
			->where('id_pend', '0')
			->where('enabled', '1')
			->get()->result_array();
		foreach ($data as $i => $dok)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['attr'] = json_decode($dok['attr'], true);
		}
		return $data;
	}

	public function ekspor_informasi_publik($data_ekspor, $tgl_dari=NULL)
	{
		$kode_desa = $this->db->select('kode_desa')
			->limit(1)->get('config')
			->row()->kode_desa;
		$this->db->select("id, '{$kode_desa}' as kode_desa, satuan, nama, tgl_upload, updated_at, kategori_info_publik as kategori, tahun");
		if ($data_ekspor == 1)
			$data = $this->ekspor_semua_data();
		else
			$data = $this->ekspor_perubahan_data($tgl_dari);
		return $data;
	}

	// Semua informasi publik diekspor termasuk yg tidak aktif dan yg telah dihapus
	private function ekspor_semua_data()
	{
		// Hanya data yg 'hidup'
		$data = $this->db->select("'0' as aksi")
			->from($this->table)
			->where('id_pend', '0')
			->order_by('id')
			->get()->result_array();
		return $data;
	}

	/*
		aksi:
		1 - tambah baru
		2 - berubah
		3 - dihapus
	*/
	private function ekspor_perubahan_data($tgl_dari)
	{
		$this->db->select("
			(CASE when deleted = 1
				then '3'
				else
					case when DATE(tgl_upload) > STR_TO_DATE('{$tgl_dari}', '%d-%m-%Y')
						then '1'
						else '2'
					end
				end) as aksi
		");
		// Termasuk data yg sudah dihapus
		$data = $this->db->from('dokumen')
			->where('id_pend', '0')
			->where("DATE(updated_at) > STR_TO_DATE('{$tgl_dari}', '%d-%m-%Y')")
			->order_by('id')
			->get()->result_array();
		return $data;
	}
}
?>
