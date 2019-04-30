<?php class Web_artikel_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('agenda_model');
	}

	public function autocomplete()
	{
		$str = autocomplete_str('judul', 'artikel');
		return $str;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (judul LIKE '$kw' OR isi LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql= " AND a.enabled = $kf";
			return $filter_sql;
		}
	}

	private function grup_sql()
	{
		// Kontributor hanya dapat melihat artikel yg dibuatnya sendiri
		if ($_SESSION['grup'] == 4)
		{
			$kf = $_SESSION['user'];
			$filter_sql= " AND a.id_user = $kf";
			return $filter_sql;
		}
	}

	public function paging($cat=0, $p=1, $o=0)
	{
		$sql = "SELECT COUNT(a.id) AS id " . $this->list_data_sql($cat);
		$query = $this->db->query($sql, $cat);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql($cat)
	{
		if ($cat > 0)
			$sql = "FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE id_kategori = ? ";
		else
			// Artikel dinamis tidak berkategori
			$sql = "FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE a.id_kategori <> 999 AND a.id_kategori <> 1000 AND k.id IS NULL ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->grup_sql();
		return $sql;
	}

	public function list_data($cat=0, $o=0, $offset=0, $limit=500)
	{
		switch ($o)
		{
		case 1: $order_sql = ' ORDER BY judul'; break;
		case 2: $order_sql = ' ORDER BY judul DESC'; break;
		case 3: $order_sql = ' ORDER BY enabled'; break;
		case 4: $order_sql = ' ORDER BY enabled DESC'; break;
		case 5: $order_sql = ' ORDER BY tgl_upload'; break;
		case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
		default:$order_sql = ' ORDER BY id DESC';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT a.*, k.kategori AS kategori " . $this->list_data_sql($cat);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql, $cat);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;

			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
				$data[$i]['aktif'] = "Tidak";
			$data[$i]['boleh_ubah'] = $this->boleh_ubah($data[$i]['id'], $this->session->user);

			$j++;
		}
		return $data;
	}

	public function list_kategori()
	{
		$sql = "SELECT * FROM kategori WHERE 1 order by urut";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		$data[] = array(
			'id' => '0',
			'kategori' => '[Tidak Berkategori]');
		return  $data;
	}

	public function get_kategori_artikel($id)
	{
		return $this->db->select('id_kategori')->where('id', $id)->get('artikel')->row_array();
	}

	public function get_kategori($cat=0)
	{
		$sql = "SELECT kategori FROM kategori WHERE id = ?";
		$query = $this->db->query($sql, $cat);
		return  $query->row_array();
	}

	public function insert($cat=1)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = "";
		$data = $_POST;

		if (empty($data['judul'])  || empty($data['isi']))
		{
			$_SESSION['error_msg'].= " -> Data harus diisi";
		  $_SESSION['success'] = -1;
		  return;
		}

		$fp = time();
		$list_gambar = array('gambar','gambar1','gambar2','gambar3');
		foreach ($list_gambar as $gambar)
		{
		  $lokasi_file = $_FILES[$gambar]['tmp_name'];
		  $nama_file   = $fp."_".$_FILES[$gambar]['name'];
		  if (!empty($lokasi_file))
		  {
			  $tipe_file = TipeFile($_FILES[$gambar]);
				$hasil = UploadArtikel($nama_file, $gambar, $fp, $tipe_file);
				if ($hasil) $data[$gambar] = $nama_file;
		  }
		}
		$data['id_kategori'] = $cat;
		$data['id_user'] = $_SESSION['user'];

		// Kontributor tidak dapat mengaktifkan artikel
		if ($_SESSION['grup'] == 4)
		{
			$data['enabled'] = 2;
		}

		// Upload dokumen lampiran

		$lokasi_file = $_FILES['dokumen']['tmp_name'];
		$tipe_file = TipeFile($_FILES['dokumen']);
		$nama_file = $_FILES['dokumen']['name'];
	  $ext = get_extension($nama_file);
		$nama_file = str_replace(' ', '-', $nama_file);    // normalkan nama file

		if ($nama_file AND !empty($lokasi_file))
		{
			if (!in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN)) or !in_array($ext, unserialize(EXT_DOKUMEN)))
			{
				unset($data['link_dokumen']);
				$_SESSION['error_msg'] .= " -> Jenis file salah: " . $tipe_file;
				$_SESSION['success'] = -1;
			}
			else
			{
				$data['dokumen'] = $nama_file;
				if($data['link_dokumen'] == '')
				$data['link_dokumen'] = $data['judul'];
				UploadDocument2($nama_file);
			}
		}

		foreach ($list_gambar as $gambar)
		{
			unset($data['old_'.$gambar]);
		}
		if ($data['tgl_upload'] == '')
			unset($data['tgl_upload']);
		else
		{
			$tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
			$data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
		}
		if ($data['tgl_agenda'] == '')
			unset($data['tgl_agenda']);
		else
		{
			$tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
			$data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
		}

		if ($cat == AGENDA)
		{
			$outp = $this->insert_agenda($data);
		}
		else
		{
			$outp = $this->db->insert('artikel', $data);
		}
		if (!$outp) $_SESSION['success'] = -1;
	}

	private function ambil_data_agenda(&$data)
	{
		$agenda = array();
		$agenda['tgl_agenda'] = $data['tgl_agenda'];
		unset($data['tgl_agenda']);
		$agenda['koordinator_kegiatan'] = $data['koordinator_kegiatan'];
		unset($data['koordinator_kegiatan']);
		$agenda['lokasi_kegiatan'] = $data['lokasi_kegiatan'];
		unset($data['lokasi_kegiatan']);
		return $agenda;
	}

	private function insert_agenda($data)
	{
		$agenda = $this->ambil_data_agenda($data);
		unset($data['id_agenda']);
		$outp = $this->db->insert('artikel', $data);
		if ($outp)
		{
			$insert_id = $this->db->insert_id();
			$agenda['id_artikel'] = $insert_id;
			$this->agenda_model->insert($agenda);
		}
		return $outp;
	}

	public function update($cat, $id=0)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = "";

	  $data = $_POST;
		if (empty($data['judul']) || empty($data['isi']))
		{
			$_SESSION['error_msg'].= " -> Data harus diisi";
		  $_SESSION['success'] = -1;
		  return;
		}

	  $fp = time();
		$list_gambar = array('gambar', 'gambar1', 'gambar2', 'gambar3');
		foreach ($list_gambar as $gambar)
		{
		  $lokasi_file = $_FILES[$gambar]['tmp_name'];
		  $nama_file   = $fp."_".$_FILES[$gambar]['name'];

		  if (!empty($lokasi_file))
		  {
			  $tipe_file = TipeFile($_FILES[$gambar]);
				$hasil = UploadArtikel($nama_file, $gambar, $fp, $tipe_file);
				if ($hasil)
				{
					$data[$gambar] = $nama_file;
					HapusArtikel($data['old_'.$gambar]);
				}
				else
				{
					unset($data[$gambar]);
				}
		  }
		  else
		  {
				unset($data[$gambar]);
		  }
		}

		foreach ($list_gambar as $gambar)
		{
			if (isset($data[$gambar.'_hapus']))
			{
				HapusArtikel($data[$gambar.'_hapus']);
				$data[$gambar] = "";
				unset($data[$gambar.'_hapus']);
			}
		}

		// Upload dokumen lampiran

		$lokasi_file = $_FILES['dokumen']['tmp_name'];
		$tipe_file = TipeFile($_FILES['dokumen']);
		$nama_file = $_FILES['dokumen']['name'];
	  $ext = get_extension($nama_file);
		$nama_file = str_replace(' ', '-', $nama_file);    // normalkan nama file

		if ($nama_file AND !empty($lokasi_file))
		{
			if (!in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN)) or !in_array($ext, unserialize(EXT_DOKUMEN)))
			{
				unset($data['link_dokumen']);
				$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
				$_SESSION['success']=-1;
			}
			else
			{
				$data['dokumen'] = $nama_file;
				if ($data['link_dokumen'] == '')
				$data['link_dokumen'] = $data['judul'];
				UploadDocument2($nama_file);
			}
		}

		foreach ($list_gambar as $gambar)
		{
			unset($data['old_'.$gambar]);
		}
		if ($data['tgl_upload'] == '')
			unset($data['tgl_upload']);
		else
		{
			$tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
			$data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
		}
		if ($data['tgl_agenda'] == '')
			unset($data['tgl_agenda']);
		else
		{
			$tempTgl = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
			$data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
		}

		if ($cat == AGENDA)
		{
			$outp = $this->update_agenda($id, $data);
		}
		else
		{
			$this->db->where('id', $id);
			$outp = $this->db->update('artikel', $data);
		}
		if (!$outp) $_SESSION['success'] = -1;
	}

	private function update_agenda($id_artikel, $data)
	{
		$agenda = $this->ambil_data_agenda($data);
		$id = $data['id_agenda'];
		unset($data['id_agenda']);
		$outp = $this->db->where('id', $id_artikel)->update('artikel', $data);
		if ($outp)
		{
			if (empty($id))
			{
				$agenda['id_artikel'] = $id_artikel;
				$this->agenda_model->insert($agenda);
			}
			else
			{
				$this->agenda_model->update($id, $agenda);
			}
		}
		return $outp;
	}

	public function update_kategori($id, $id_kategori)
	{
		$this->db->where('id', $id)->update('artikel', array('id_kategori' => $id_kategori));
	}

	public function delete($id='')
	{
		$list_gambar = $this->db->
			select('gambar, gambar1, gambar2, gambar3')->
			where('id', $id)->
			get('artikel')->row_array();
		foreach ($list_gambar as $key => $gambar)
		{
			HapusArtikel($gambar);
		}
		$outp = $this->db->where('id', $id)->delete('artikel');
		return $outp;
	}

	public function delete_all()
	{
		$_SESSION['success'] = 1;
		$id_cb = $_POST['id_cb'];
		foreach ($id_cb as $id)
		{
			if ($this->boleh_ubah($id, $_SESSION['user']))
			{
				$outp = $this->delete($id);
				if (!$outp) $_SESSION['success'] = -1;
			}
		}
	}

	public function hapus($id='')
	{
		$sql = "DELETE FROM kategori WHERE id = ?";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function artikel_lock($id='', $val=0)
	{
		$sql = "UPDATE artikel SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function komentar_lock($id='', $val=0)
	{
		$_SESSION['success'] = 1;
		$outp = $this->db->where('id', $id)->update('artikel', array('boleh_komentar'=>$val));
		if (!$outp) $_SESSION['success'] = -1;
	}

	public function get_artikel($id=0)
	{
		$sql = "SELECT a.*, g.*, g.id as id_agenda, u.nama AS owner
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			LEFT JOIN agenda g ON g.id_artikel = a.id
			WHERE a.id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		$data['judul'] = $this->security->xss_clean($data['judul']);
		if (empty($this->setting->user_admin) or $data['id_user'] != $this->setting->user_admin)
			$data['isi'] = $this->security->xss_clean($data['isi']);

		//digunakan untuk timepicker
		$tempTgl = date_create_from_format('Y-m-d H:i:s', $data['tgl_upload']);
		$data['tgl_upload'] = $tempTgl->format('d-m-Y H:i:s');
		// Data artikel terkait agenda
		if (!empty($data['tgl_agenda']))
		{
			$tempTgl = date_create_from_format('Y-m-d H:i:s', $data['tgl_agenda']);
			$data['tgl_agenda'] = $tempTgl->format('d-m-Y H:i:s');
		}
		else
			$data['tgl_agenda'] = date('d-m-Y H:i:s');

		return $data;
	}

	public function get_headline()
	{
		$sql = "SELECT a.*, u.nama AS owner
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			WHERE headline = 1
			ORDER BY tgl_upload DESC LIMIT 1 ";
		$query = $this->db->query($sql);
		$data  = $query->row_array();

		if (empty($data))
			$data = null;
		else
		{
			$id = $data['id'];
			$panjang = str_split($data['isi'], 300);
			$data['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
		}
		return $data;
	}

	public function artikel_show()
	{
		$sql = "SELECT a.*, u.nama AS owner, k.kategori AS kategori
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE a.enabled=? AND k.tipe = 1
			ORDER BY a.tgl_upload DESC LIMIT 4";
		$query = $this->db->query($sql,1);
		$data  = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$id = $data[$i]['id'];
			$pendek = str_split($data[$i]['isi'], 100);
			$data[$i]['isi_short'] = $pendek[0];
			$panjang = str_split($data[$i]['isi'], 150);
			$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
		}
		return $data;
	}

	public function insert_kategori()
	{
		$data['kategori'] = $_POST['kategori'];
		$data['tipe'] = '2';
		$outp = $this->db->insert('kategori', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function insert_comment($id=0)
	{
		$data = $_POST;
		$data['enabled'] = 2;
		$data['id_artikel'] = $id;
		$outp = $this->db->insert('komentar', $data);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function list_komentar($id=0)
	{
		$sql = "SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC";
		$query = $this->db->query($sql, $id);
		$data  = $query->result_array();
		return $data;
	}

	public function headline($id=0)
	{
		$sql1 = "UPDATE artikel SET headline = 0 WHERE headline = 1";
		$this->db->query($sql1);

		$sql = "UPDATE artikel SET headline = 1 WHERE id = ?";
		$outp = $this->db->query($sql, $id);

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function slide($id=0)
	{
		$sql = "SELECT * FROM artikel WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();

		if ($data['headline'] == '3')
		{
			$sql = "UPDATE artikel SET headline = 0 WHERE id = ?";
			$outp = $this->db->query($sql, $id);
		}
		else
		{
			$sql = "UPDATE artikel SET headline = 3 WHERE id = ?";
			$outp = $this->db->query($sql, $id);
		}

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function jml_artikel()
	{
		$jml = $this->db->select('count(*) as jml')->get('artikel')->row()->jml;
		return $jml;
	}

	public function boleh_ubah($id, $user)
	{
		// Kontributor hanya boleh mengubah artikel yg ditulisnya sendiri
		$id_user = $this->db->select('id_user')->where('id', $id)->get('artikel')->row()->id_user;
		return ($user == $id_user or $_SESSION['grup'] != 4);
	}
}