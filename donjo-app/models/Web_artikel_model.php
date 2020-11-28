<?php class Web_artikel_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('agenda_model');
	}

	public function autocomplete($cat)
	{
		$this->db->where('id_kategori', $cat);

		return $this->autocomplete_str('judul', 'artikel');
	}

	private function search_sql()
	{
		$cari = $this->session->cari;

		if (isset($cari))
		{
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$sql = " AND (judul LIKE '$kw' OR isi LIKE '$kw')";

			return $sql;
		}
	}

	private function filter_sql()
	{
		$status = $this->session->status;

		if (isset($status))
		{
			$sql = " AND a.enabled = $status";

			return $sql;
		}
	}

	private function grup_sql()
	{
		// Kontributor hanya dapat melihat artikel yg dibuatnya sendiri
		if ($this->session->grup == 4)
		{
			$kf = $this->session->user;
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
		elseif ($cat == -1)
			// Semua artikel dinamis (tidak termasuk artikel statis)
			$sql = "FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE 1 AND id_kategori NOT IN ('999', '1000', '1001')";
		else
			// Artikel dinamis tidak berkategori
			$sql = "FROM artikel a
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE a.id_kategori <> 999 AND a.id_kategori <> 1000 AND a.id_kategori <> 1001 AND k.id IS NULL ";
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
		case 3: $order_sql = ' ORDER BY hit'; break;
		case 4: $order_sql = ' ORDER BY hit DESC'; break;
		case 5: $order_sql = ' ORDER BY tgl_upload'; break;
		case 6: $order_sql = ' ORDER BY tgl_upload DESC'; break;
		default:$order_sql = ' ORDER BY id DESC';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT a.*, k.kategori AS kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri " . $this->list_data_sql($cat);
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql, $cat);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$data[$i]['boleh_ubah'] = $this->boleh_ubah($data[$i]['id'], $this->session->user);
			$j++;
		}
		return $data;
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	private function kategori($id)
	{
		$data	= $this->db
			->where('parrent', $id)
			->order_by('urut')
			->get('kategori')
			->result_array();

		return $data;
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	public function list_kategori()
	{
		$data = $this->kategori(0);

		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['submenu'] = $this->kategori($data[$i]['id']);
		}

		$data[] = [
			'id' => '0',
			'kategori' => '[Tidak Berkategori]'
		];

		return $data;
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	public function get_kategori_artikel($id)
	{
		return $this->db->select('id_kategori')->where('id', $id)->get('artikel')->row_array();
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	public function get_kategori($cat=0)
	{
		$sql = "SELECT kategori FROM kategori WHERE id = ?";
		$query = $this->db->query($sql, $cat);
		return $query->row_array();
	}

	public function insert($cat=1)
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
		// Batasi judul menggunakan teks polos
		$data['judul'] = strip_tags($data['judul']);

		// Gunakan judul untuk url artikel
		$slug = $this->str_slug($data['judul']);

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
		$nama_file = str_replace(' ', '-', $nama_file); // normalkan nama file

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

		$data['slug'] = $slug; // insert slug
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

	//Buat slug unik
	private function str_slug($str)
	{
		$slug = url_title($str, 'dash', $lowercase = true);
		$cek_slug = true;
		$n = 1;
		$slug_unik = $slug;
		while ($cek_slug)
		{
			$cek_slug = $this->db->where('slug', $slug_unik)->get('artikel')->num_rows();
			if ($cek_slug)
			{
				$slug_unik = $slug . '-' . $n++;
			}
		}
		return $slug_unik;
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
		// Batasi judul menggunakan teks polos
		$data['judul'] = strip_tags($data['judul']);

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
		$nama_file = str_replace(' ', '-', $nama_file); // normalkan nama file

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
		$this->db->where('id', $id)->update('artikel', ['id_kategori' => $id_kategori]);
	}

	public function delete($id = 0, $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$list_gambar = $this->db
			->select('gambar, gambar1, gambar2, gambar3')
			->where('id', $id)
			->get('artikel')
			->row_array();

		foreach ($list_gambar as $key => $gambar)
		{
			HapusArtikel($gambar);
		}

		$outp = $this->db->where('id', $id)->delete('artikel');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	public function delete_all()
	{
		$this->session->success = 1;

		$id_cb = $this->input->post('id_cb');
		foreach ($id_cb as $id)
		{
			if ($this->boleh_ubah($id, $this->session->user)) $this->delete($id, TRUE);
		}
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	public function hapus($id = 0, $semua = FALSE)
	{
		if ( ! $semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('kategori');

		status_sukses($outp, $gagal_saja = TRUE); //Tampilkan Pesan
	}

	public function artikel_lock($id = 0, $val = 1)
	{
		$outp = $this->db->where('id', $id)->update('artikel', ['enabled' => $val]);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function komentar_lock($id = 0, $val = 1)
	{
		$outp = $this->db->where('id', $id)->update('artikel', array('boleh_komentar' => $val));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_artikel($id = 0)
	{
		$data = $this->db
			->select('a.*, g.*, g.id as id_agenda, u.nama AS owner')
			->select('YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
			->from('artikel a')
			->join('user u', 'a.id_user = u.id', 'LEFT')
			->join('agenda g', 'g.id_artikel = a.id', 'LEFT')
			->where('a.id', $id)
			->get()
			->row_array();

		// Jika artikel tdk ditemukan
		if ( ! $data) return FALSE;

		$data['judul'] = $this->security->xss_clean($data['judul']);
		if (empty($this->setting->user_admin) OR $data['id_user'] != $this->setting->user_admin)
			$data['isi'] = $this->security->xss_clean($data['isi']);

		// Digunakan untuk timepicker
		$tempTgl = date_create_from_format('Y-m-d H:i:s', $data['tgl_upload']);
		$data['tgl_upload'] = $tempTgl->format('d-m-Y H:i:s');
		// Data artikel terkait agenda
		if ( ! empty($data['tgl_agenda']))
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
			$data['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("artikel/$id")."'>Baca Selengkapnya</a>";
		}
		return $data;
	}

	// TODO: pindahkan dan gunakan web_kategori_model
	public function insert_kategori()
	{
		$data['kategori'] = $_POST['kategori'];
		$data['tipe'] = '2';
		$outp = $this->db->insert('kategori', $data);

		status_sukses($outp); //Tampilkan Pesan
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

		status_sukses($outp); //Tampilkan Pesan
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

		status_sukses($outp); //Tampilkan Pesan
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
		return ($user == $id_user OR $this->session->grup != 4);
	}

	public function reset($cat)
	{
		// Normalkan kembali hit artikel kategori 999 (yg ditampilkan di menu) akibat robot (crawler)
		$persen = $this->input->post('hit');
		$list_menu = $this->db
			->distinct()
			->select('link')
			->like('link', 'artikel/')
			->where('enabled', 1)
			->get('menu')
			->result_array();

		foreach ($list_menu as $list)
		{
			$id = str_replace('artikel/', '', $list['link']);
			$artikel = $this->db->where('id', $id)->get('artikel')->row_array();
			$hit = $artikel['hit'] * ($persen / 100);
			if ($artikel)
				$this->db->where('id', $id)->update('artikel', array('hit' => $hit));
		}
	}

	public function list_artikel_statis()
	{
		// '999' adalah id_kategori untuk artikel statis
		$data = $this->db
			->select('id, judul')
			->where('id_kategori', '999')
			->get('artikel')
			->result_array();

		return $data;
	}

}
