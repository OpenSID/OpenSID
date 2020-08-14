<?php
class Web_dokumen_model extends MY_Model {

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

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'dokumen_hidup');
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

	private function search_sql()
	{
		if ($cari = $this->session->cari)
		{
			$cari = $this->db->escape_like_str($cari);
			$this->db
				->group_start()
					->like('satuan', $cari)
					->or_like('nama', $cari)
				->group_end();
		}
	}

	private function filter_sql()
	{
		if ($filter = $this->session->filter)
		{
			$this->db->where('enabled', $filter);
		}
	}

	private function jenis_peraturan_sql($kat)
	{
		// Jenis peraturan ada di kolom attr dalam bentuk json
		if ($kat == 3 and ($jenis = $this->session->jenis_peraturan))
		{
			$attr = '"jenis_peraturan":"'.$jenis.'"';
			$this->db->like('attr', $attr, 'both', false);
		}
	}

	private function list_data_sql($kat)
	{
		$this->db->from('dokumen_hidup')
			->where('id_pend', 0);
		// $kat == 1 adalah informasi publik dan mencakup juga jenis dokumen lain termasuk SK Kades dan Perdes
		if ($kat != '1') $this->db->where('kategori', $kat);
		$this->search_sql();
		$this->filter_sql();
		$this->jenis_peraturan_sql($kat);
	}

	public function paging($kat, $p=1, $o=0)
	{
		$this->list_data_sql($kat);
		$jml_data = $this->db
			->select('COUNT(*) as jml')
			->get()->row()->jml;

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	function list_data($kat, $o=0, $offset=0, $limit=500)
	{
		$this->list_data_sql($kat);
		switch ($o)
		{
			case 1: $order = ' nama'; break;
			case 2: $order = ' nama DESC'; break;
			case 3: $order = ' enabled'; break;
			case 4: $order = ' enabled DESC'; break;
			case 5: $order = ' tgl_upload'; break;
			case 6: $order = ' tgl_upload DESC'; break;
			default:$order = ' id';
		}
		$data = $this->db
			->select('*')
			->order_by($order)
			->limit($limit, $offset)
			->get()
			->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$data[$i]['attr'] = json_decode($data[$i]['attr'], true);
			// Ambil keterangan kategori publik
			if ($data[$i]['kategori_info_publik'])
				$data[$i]['kategori_info_publik'] = $this->referensi_model->list_ref_flip(KATEGORI_PUBLIK)[$data[$i]['kategori_info_publik']];

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

	private function upload_dokumen($data, $file_lama="")
	{
		$_SESSION['error_msg'] = "";
		$_SESSION['success'] = 1;
		unset($data['old_file']);
		if (empty($_FILES['satuan']['tmp_name']) or (int)$_FILES['satuan']['size'] > convertToBytes(max_upload().'MB'))
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] .= ' -> Error upload file. Periksa apakah melebihi ukuran maksimum';
			return null;
		}

		$lokasi_file = $_FILES['satuan']['tmp_name'];
		if (empty($lokasi_file))
		{
			$_SESSION['success'] = -1;
			return null;
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
			return null;
		}
		elseif (isPHP($lokasi_file, $nama_file))
		{
			$_SESSION['error_msg'].= " -> File berisi script ";
			$_SESSION['success']=-1;
			return null;
		}

		$nama = $data['nama'];
		if (!empty($data['id_pend']))
			$nama_file = $data['id_pend']."_".$nama."_".generator(6)."_".$nama_file;
		else
			$nama_file = $nama."_".generator(6)."_".$nama_file;
		$nama_file = bersihkan_namafile($nama_file);
		UploadDocument($nama_file, $file_lama);
		return $nama_file;
	}

	public function insert($mandiri=false)
	{
		$retval = true;
		$post = $this->input->post();
		$data = $this->validasi($post);
		if (!empty($post['satuan'])) $data['satuan'] = $this->upload_dokumen($post);
		$data['attr'] = json_encode($data['attr']);
		$data['dok_warga'] = isset($post['dok_warga']);
		// Dari layanan mandiri gunakan NIK penduduk
		$data['created_by'] = $mandiri ? $this->session->nik : $this->session->user;

		unset($data['anggota_kk']);
		$retval &= $this->db->insert('dokumen', $data);
		$insert_id = $this->db->insert_id();

		if ($retval)
		{
			$data['id_parent'] = $insert_id;
			foreach ($post['anggota_kk'] as $key => $value)
			{
				$data['id_pend'] = $value;
				$retval &= $this->db->insert('dokumen', $data);
			}
		}
		return $retval;
	}

	private function validasi($post)
	{
		$data = array();
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['kategori'] = $post['kategori'] ?: 1;
		$data['kategori_info_publik'] = $post['kategori_info_publik'] ?: null;
		$data['id_syarat'] = $post['id_syarat'] ?: null;
		$data['id_pend'] = $post['id_pend'] ?: 0;
		switch ($data['kategori'])
		{
			case 1: //Informsi Publik
				$data['tahun'] = $post['tahun'];
				break;
			case 2: //SK Kades
				$data['tahun'] = date('Y', strtotime($post['attr']['tgl_kep_kades']));
				$data['kategori_info_publik'] = '3';
				$data['attr']['tgl_kep_kades'] = $post['attr']['tgl_kep_kades'];
				$data['attr']['uraian'] = htmlentities($post['attr']['uraian']);
				$data['attr']['no_kep_kades'] = nomor_surat_keputusan($post['attr']['no_kep_kades']);
				$data['attr']['no_lapor'] = nomor_surat_keputusan($post['attr']['no_lapor']);
				$data['attr']['tgl_lapor'] = $post['attr']['tgl_lapor'];
				$data['attr']['keterangan'] = htmlentities($post['attr']['keterangan']);
				break;
			case 3: //Perdes
				$data['tahun'] = date('Y', strtotime($post['attr']['tgl_ditetapkan']));
				$data['kategori_info_publik'] = '3';
				$data['attr']['tgl_ditetapkan'] = $post['attr']['tgl_ditetapkan'];
				$data['attr']['tgl_lapor'] = $post['attr']['tgl_lapor'];
				$data['attr']['tgl_kesepakatan'] = $post['attr']['tgl_kesepakatan'];
				$data['attr']['uraian'] = htmlentities($post['attr']['uraian']);
				$data['attr']['jenis_peraturan'] = htmlentities($post['attr']['jenis_peraturan']);
				$data['attr']['no_ditetapkan'] = nomor_surat_keputusan($post['attr']['no_ditetapkan']);
				$data['attr']['no_lapor'] = nomor_surat_keputusan($post['attr']['no_lapor']);
				$data['attr']['no_lembaran_desa'] = nomor_surat_keputusan($post['attr']['no_lembaran_desa']);
				$data['attr']['no_berita_desa'] = nomor_surat_keputusan($post['attr']['no_berita_desa']);
				$data['attr']['tgl_lembaran_desa'] = $post['attr']['tgl_lembaran_desa'];
				$data['attr']['tgl_berita_desa'] = $post['attr']['tgl_berita_desa'];
				$data['attr']['keterangan'] = htmlentities($post['attr']['keterangan']);
				break;

			default:
				$data['tahun'] = date('Y');
				break;
		}
		return $data;
	}

	// $mandiri = true kalau dipanggil dari layanan mandiri
	public function update($id=0, $id_pend=null, $mandiri=false)
	{
		$retval = true;

		$post = $this->input->post();
		$data = $this->validasi($post);
		// Jangan simpan dok_warga kalau dari Layanan Mandiri
		if (!$mandiri) !$data['dok_warga'] = isset($post['dok_warga']);
		$old_file = $this->db->select('satuan')
				->where('id', $id)
				->get('dokumen')->row()->satuan;
		$data['satuan'] = $old_file;
		if (!empty($post['satuan']))
		{
			$data['satuan'] = $this->upload_dokumen($post, $old_file);
			$retval &= !(empty($data['satuan']));
			if (!$retval) return $retval;
		}
		$data['attr'] = json_encode($data['attr']);
		$data['updated_at'] = date('Y-m-d H:i:s');
		// Dari layanan mandiri gunakan NIK penduduk
		$data['updated_by'] = $mandiri ? $this->session->nik : $this->session->user;

		unset($data['anggota_kk']);

		if ($id_pend) $this->db->where('id_pend', $id_pend);
		$retval &= $this->db->where('id',$id)->update('dokumen', $data);

		$retval &= $this->update_dok_anggota($id, $post, $data);

		status_sukses($retval);
		return $retval;
	}

	private function update_dok_anggota($id, $post, $data)
	{
		$retval = true;

		// cek jika dokumen ini juga ada di anggota yang lain
		$anggota_kk = $post['anggota_kk'];
		$anggota_lain = array_column($this->get_dokumen_di_anggota_lain($id), 'id_pend');

		// cari intersect anggota
		unset($data['id_pend']);
		$intersect_id_pend = array_intersect($anggota_kk, $anggota_lain);
		foreach ($intersect_id_pend as $key => $value)
		{
			$this->db->where('id_pend',$value);
			$this->db->where('id_parent',$id);
			$retval &= $this->db->update('dokumen', $data);
		}

		// cari diff anggota (jika ada anggota yang diuncheck - delete)
		if (isset($anggota_kk))
		{
			$diff_id_pend = array_diff($anggota_lain, $anggota_kk);
			foreach ($diff_id_pend as $key => $value)
				$retval &= $this->db->delete('dokumen', array('id_pend' => $value, 'id_parent' => $id));  // hard delete
		}
		else
		{
			foreach ($anggota_lain as $key => $value)
				$retval &= $this->db->delete('dokumen', array('id_pend' => $value, 'id_parent' => $id));  // hard delete
		}

		// cari diff anggota (jika ada anggota tambahan yang dicheck -> insert)
		$diff_id_pend = array_diff($anggota_kk, $anggota_lain);
		if (isset($diff_id_pend))
		{
			unset($data['updated_at']);

			foreach ($diff_id_pend as $key => $value)
			{
				$data["id_pend"] = $value;
				$data["id_parent"] = $id;
				$retval &= $this->db->insert('dokumen', $data);	// insert new data
			}
		}
		return $retval;
	}

	// Soft delete, tapi hapus berkas dokumen
	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

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

		// cek jika dokumen ini juga ada di anggota yang lain
		$anggota_lain = $this->get_dokumen_di_anggota_lain($id);
		// soft delete dokumen anggota lain jika ada
		foreach ($anggota_lain as $item)
			$this->db->where('id', $item['id'])->update('dokumen', $data);
	}

	public function hard_delete_dokumen_bersama($id_pend)
	{
		$this->db->delete('dokumen', array('id_pend' => $id_pend, 'id_parent >' => '0'));
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			$this->delete($id, $semua=true);
		}
	}

	public function dokumen_lock($id='', $val=0)
	{
		$sql = "UPDATE dokumen SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_dokumen($id=0, $id_pend=null)
	{
		if ($id_pend) $this->db->where('id_pend', $id_pend);
		$data = $this->db->from($this->table)
			->where('id', $id)
			->get()->row_array();
		$data['attr'] = json_decode($data['attr'], true);
		$data = array_filter($data);

		return $data;
	}

	public function get_dokumen_di_anggota_lain($id_dokumen=0)
	{
		$data = $this->db->from($this->table)
			->where('id_parent', $id_dokumen)
			->get()->result_array();

		foreach ($data as $key => $value) {
			$data[$key]['attr'] = json_decode($data[$key]['attr'], true);
			$data[$key] = array_filter($data[$key]);
		}

		return $data;
	}


	/**
	 * Ambil nama berkas dari database berdasarkan id dokumen
	 * @param  string       $id  			Id pada tabel dokumen
	 * @return  string|NULL
	 */
	public function get_nama_berkas($id, $id_pend=0)
	{
		// Ambil nama berkas dari database untuk dokumen yg aktif
		if ($id_pend) $this->db->where('id_pend', $id_pend);
		$nama_berkas = $this->db->select('satuan')
			->where('id', $id)
			->where('enabled', 1)
			->get('dokumen')->row()->satuan;
		return $nama_berkas;
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

	public function data_cetak($kat=1, $tahun='', $jenis_peraturan='')
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
		// Filter jenis peraturan hanya untuk peraturan desa
		if ($kat == 3 and $jenis_peraturan)
		{
			$like = '"jenis_peraturan":"'.$jenis_peraturan.'"';
			// $this->db->where("attr REGEXP '" . $regex . "'");
			$this->db->like('attr', $like, 'both', false);
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

	public function data_ppid($tgl_dari=NULL)
	{
		$kode_desa = $this->db->select('kode_desa')
			->limit(1)->get('config')
			->row()->kode_desa;
		$lokasi_dokumen = base_url('dokumen_web/unduh_berkas/');
		$this->db->select("id, '{$kode_desa}' as kode_desa, CONCAT('{$lokasi_dokumen}', id) as dokumen, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun");
		if (empty($tgl_dari))
			$data = $this->ekspor_semua_data();
		else
			$data = $this->ekspor_perubahan_data($tgl_dari);
		return $data;
	}

	public function ekspor_informasi_publik($data_ekspor, $tgl_dari=NULL)
	{
		$kode_desa = $this->db->select('kode_desa')
			->limit(1)->get('config')
			->row()->kode_desa;
		$this->db->select("id, '{$kode_desa}' as kode_desa, satuan, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun");
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
