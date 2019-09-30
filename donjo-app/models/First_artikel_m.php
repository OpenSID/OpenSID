<?php

class First_artikel_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('web_sosmed_model');
		$this->load->model('shortcode_model');
	}

	public function get_headline()
	{
		$sql = "SELECT a.*, u.nama AS owner, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			WHERE headline = 1 AND a.tgl_upload < NOW()
			ORDER BY tgl_upload DESC LIMIT 1 ";
		$query = $this->db->query($sql);
		$data  = $query->row_array();
		if (empty($data))
			$data = null;
		else
		{
			$id = $data['id'];
			//$panjang=str_split($data['isi'],800);
			//$data['isi'] = "<label>".strip_tags($panjang[0])."...</label><a href='".site_url("first/artikel/$id")."'>Baca Selengkapnya</a>";
		}
		return $data;
	}

	public function get_teks_berjalan()
	{
		$this->load->model('teks_berjalan_model');
		return $this->teks_berjalan_model->isi_teks_berjalan();
	}

	public function get_widget()
	{
		$sql = "SELECT * FROM widget LIMIT 1 ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function paging($p=1)
	{
		$sql = "SELECT COUNT(a.id) AS id FROM artikel a
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE ((a.enabled=1) AND (headline <> 1)) AND a.tgl_upload < NOW() ";
		$cari = trim($this->input->get('cari'));
		if ( ! empty($cari))
		{
			$cari = $this->db->escape_like_str($cari);
			$sql .= "AND (a.judul like '%$cari%' or a.isi like '%$cari%') ";
			$cfg['suffix'] = "?cari=$cari";
		}
		$sql .= "ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->setting->web_artikel_per_page;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	public function paging_kat($p=1, $id=0)
	{
		$sql = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE 1 ";
		if ($id!=0)
			$sql .= "AND ((id_kategori = ".$id.") OR (parrent = ".$id."))";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = 8;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	public function artikel_show($id='0', $offset, $limit)
	{
		if ($id > 0)
		{
			$sql = "SELECT a.*,u.nama AS owner,k.kategori AS kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri
				FROM artikel a
				LEFT JOIN user u ON a.id_user = u.id
				LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND headline <> 1 AND a.id = ".$id;
		}
		else
		{
			// Penampilan daftar artikel di halaman depan tidak terbatas pada artikel dinamis saja
			$sql = "SELECT a.*, u.nama AS owner, k.kategori AS kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri
				FROM artikel a
				LEFT JOIN user u ON a.id_user = u.id
				LEFT JOIN kategori k ON a.id_kategori = k.id
				WHERE a.enabled = 1 AND headline <> 1";
			$cari = trim($this->input->get('cari'));
			if ( ! empty($cari))
			{
				$cari = $this->db->escape_like_str($cari);
				$sql .= " AND (a.judul like '%$cari%' or a.isi like '%$cari%') ";
			}
			$sql .= " AND a.tgl_upload < NOW()";
			$sql .= " ORDER BY a.tgl_upload DESC LIMIT ".$offset.", ".$limit;
		}
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		for ($i=0; $i < count($data); $i++)
		{
			$data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
			if (empty($this->setting->user_admin) or $data[$i]['id_user'] != $this->setting->user_admin)
				$data[$i]['isi'] = $this->security->xss_clean($data[$i]['isi']);
				// ganti shortcode menjadi icon
				$data[$i]['isi'] = $this->shortcode_model->convert_sc_list($data[$i]['isi']);
		}
		return $data;
	}

	public function arsip_show($rand = false)
	{
		$sql = "SELECT a.*, u.nama AS owner, k.kategori AS kategori
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE a.enabled = ?
			AND a.tgl_upload < NOW() ";
		if ($rand)
			$sql .= "	ORDER BY RAND() DESC LIMIT 7 ";
		else
			$sql .= "	ORDER BY a.tgl_upload DESC LIMIT 7 ";
		$query = $this->db->query($sql, 1);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$id = $data[$i]['id'];
			$pendek = str_split($data[$i]['isi'], 100);
			$pendek2 = str_split($pendek[0], 90);
			$data[$i]['isi_short'] = $pendek2[0]."...";
			$panjang = str_split($data[$i]['isi'], 150);
			$data[$i]['isi'] = "<label>".$panjang[0]."...</label><a href='".site_url("first/artikel/$id")."'>baca selengkapnya</a>";
		}
		return $data;
	}

	public function arsip_rand()
	{
		return $this->arsip_show($rand = true);
	}

	public function paging_arsip($p=1)
	{
		$sql = "SELECT COUNT(a.id) AS id FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=1 AND a.tgl_upload < NOW()";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['id'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = 20;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	public function full_arsip($offset=0, $limit=50)
	{
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT a.*,u.nama AS owner,k.kategori AS kategori FROM artikel a LEFT JOIN user u ON a.id_user = u.id LEFT JOIN kategori k ON a.id_kategori = k.id WHERE a.enabled=?
			AND a.tgl_upload < NOW()
		ORDER BY a.tgl_upload DESC";

		$sql .= $paging_sql;

		$query = $this->db->query($sql,1);
		$data  = $query->result_array();
		if ($query->num_rows()>0)
		{
			for ($i=0; $i<count($data); $i++)
			{
				$nomer = $offset + $i+1;
				$id = $data[$i]['id'];
				$tgl = date("d/m/Y",strtotime($data[$i]['tgl_upload']));
				$data[$i]['no'] = $nomer;
				$data[$i]['tgl'] = $tgl;
				$data[$i]['isi'] = "<a href='".site_url("first/artikel/$id")."'>".$data[$i]['judul']."</a>, <i class=\"fa fa-user\"></i> ".$data[$i]['owner'];
			}
		}
		else
		{
			$data  = false;
		}
		return $data;
	}

	// Jika $gambar_utama, hanya tampilkan gambar utama masing2 artikel terbaru
	public function slide_show($gambar_utama=FALSE)
	{
		$sql = "SELECT id,judul,gambar FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())";
		if (!$gambar_utama) $sql .= "
			UNION SELECT id,judul,gambar1 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
			UNION SELECT id,judul,gambar2 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
			UNION SELECT id,judul,gambar3 FROM artikel WHERE (enabled=1 AND headline=3 AND tgl_upload < NOW())
		";
		$sql .= ($gambar_utama) ? "ORDER BY tgl_upload DESC LIMIT 10" : "ORDER BY RAND() LIMIT 10";
		$query = $this->db->query($sql);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}
		else
		{
			$data = false;
		}
		return $data;
	}

	// Ambil gambar slider besar tergantung dari settingnya.
	public function slider_gambar()
	{
		$slider_gambar = array();
		switch ($this->setting->sumber_gambar_slider)
		{
			case '1':
				# 10 gambar utama semua artikel terbaru
				$slider_gambar['gambar'] = $this->db->select('id,judul,gambar')->where('enabled',1)->where('gambar !=','')->where('tgl_upload < NOW()')->order_by('tgl_upload DESC')->limit(10)->get('artikel')->result_array();
				$slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
				break;
			case '2':
				# 10 gambar utama artikel terbaru yang masuk ke slider atas
				$slider_gambar['gambar'] = $this->slide_show(true);
				$slider_gambar['lokasi'] = LOKASI_FOTO_ARTIKEL;
				break;
			case '3':
				# 10 gambar dari galeri yang masuk ke slider besar
				$this->load->model('web_gallery_model');
				$slider_gambar['gambar'] = $this->web_gallery_model->list_slide_galeri();
				$slider_gambar['lokasi'] = LOKASI_GALERI;
				break;
			default:
				# code...
				break;
		}
		return $slider_gambar;
	}

	public function agenda_show()
	{
		$sql = "SELECT a.*, g.*, u.nama AS owner, k.kategori AS kategori
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			LEFT JOIN agenda g ON g.id_artikel = a.id
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE id_kategori='1000' AND a.enabled = 1 AND a.tgl_upload < NOW()
			ORDER BY a.tgl_upload DESC";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function komentar_show()
	{
		$sql = "SELECT a.*, b.*, YEAR(b.tgl_upload) AS thn, MONTH(b.tgl_upload) AS bln, DAY(b.tgl_upload) AS hri, b.slug as slug
			FROM komentar a
			INNER JOIN artikel b ON  a.id_artikel = b.id
			WHERE a.enabled = ? AND a.id_artikel <> 775
			ORDER BY a.tgl_upload DESC LIMIT 10 ";
		$query = $this->db->query($sql, 1);
		$data = $query->result_array();

		for ($i=0; $i<count($data); $i++)
		{
			$id = $data[$i]['id_artikel'];
			$pendek = str_split($data[$i]['komentar'], 25);
			$pendek2 = str_split($pendek[0], 90);
			$data[$i]['komentar_short'] = $pendek2[0]."...";
			$panjang = str_split($data[$i]['komentar'], 50);
			$data[$i]['komentar'] = "".$panjang[0]."...<a href='".site_url("first/artikel/".$data[$i]['thn']."/".$data[$i]['bln']."/".$data[$i]['hri']."/".$data[$i]['slug']." ")."'>baca selengkapnya</a>";
		}
		return $data;
	}

	public function get_kategori($id=0)
	{
		$sql = "SELECT a.kategori FROM kategori a WHERE a.id=?";
		$query = $this->db->query($sql,$id);
		if ($query->num_rows()>0)
		{
			$data  = $query->row_array();
		}
		else if (!empty($id))
		{
			// untuk artikel jenis statis = "AGENDA"
			$judul = array(
				999 => "Halaman Statis",
				1000 => "Agenda",
				1001 => "Artikel Keuangan",
			);
			$data = $judul[$id];
		}
		else
		{
			$data = false;
		}
		return $data;
	}

	public function get_artikel($slug, $is_id=false)
	{
		$this->db->select('a.*, u.nama AS owner, k.kategori')
			->from('artikel a')
			->join('user u', 'a.id_user = u.id', 'left')
			->join('kategori k', 'a.id_kategori = k.id', 'left')
			->where('tgl_upload < NOW()');

		if ($is_id)
		{
			// $slug adalah id
			$this->db->where('a.id', $slug);
		}
		else
		{
			$this->db->where('slug', $slug);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			$data = $query->row_array();
			$data['slug'] = $this->security->xss_clean($data['slug']);
			if (empty($this->setting->user_admin) or $data['id_user'] != $this->setting->user_admin)
				$data['isi'] = $this->security->xss_clean($data['isi']);
		}
		else
		{
			$data = false;
		}
		return $data;
	}

	public function list_artikel($offset=0, $limit=50, $id=0)
	{
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		$sql = "SELECT a.*, u.nama AS owner, k.kategori AS kategori, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri
			FROM artikel a
			LEFT JOIN user u ON a.id_user = u.id
			LEFT JOIN kategori k ON a.id_kategori = k.id
			WHERE a.enabled = 1 AND a.tgl_upload < NOW() ";
		if ($id!=0)
			$sql .= "AND id_kategori = $id OR parrent = $id";
		$sql .= " ORDER BY a.tgl_upload DESC ";
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
			for ($i=0; $i < count($data); $i++)
			{
				$data[$i]['judul'] = $this->security->xss_clean($data[$i]['judul']);
				if (empty($this->setting->user_admin) or $data[$i]['id_user'] != $this->setting->user_admin)
					$data[$i]['isi'] = $this->security->xss_clean($data[$i]['isi']);
					// ganti shortcode menjadi icon
					$data[$i]['isi'] = $this->shortcode_model->convert_sc_list($data[$i]['isi']);
			}
		}
		else
		{
			$data = false;
		}
		return $data;
	}

	/**
	 * Simpan komentar yang dikirim oleh pengunjung
	 */
	public function insert_comment($id=0)
	{
		$data['komentar'] = strip_tags($_POST["komentar"]);
		$data['owner'] = strip_tags($_POST["owner"]);
		$data['no_hp'] = strip_tags($_POST["no_hp"]);
		$data['email'] = strip_tags($_POST["email"]);

		// load library form_validation
		$this->load->library('form_validation');
		$this->form_validation->set_rules('komentar', 'Komentar', 'required');
		$this->form_validation->set_rules('owner', 'Nama', 'required');
		$this->form_validation->set_rules('no_hp', 'No HP', 'required');
		$this->form_validation->set_rules('email', 'Email', 'valid_email');

		if ($this->form_validation->run() == TRUE)
		{
			$data['enabled'] = 2;
			$data['id_artikel'] = $id;
			$outp = $this->db->insert('komentar',$data);
		}
		else
		{
			$_SESSION['validation_error'] = 'Form tidak terisi dengan benar';
		}
		if ($outp)
		{
			$_SESSION['success'] = 1;
			return true;
		}

		$_SESSION['success'] = -1;
		return false;
	}

	public function list_komentar($id=0)
	{
		$sql = "SELECT * FROM komentar WHERE id_artikel = ? ORDER BY tgl_upload DESC";
		$query = $this->db->query($sql,$id);
		if ($query->num_rows()>0)
		{
			$data = $query->result_array();
		}
		else
		{
			$data = false;
		}
		return $data;
	}

	// Tampilan di widget sosmed
	public function list_sosmed()
	{
		$sql = "SELECT * FROM media_sosial WHERE enabled=1";
		$query = $this->db->query($sql);
		if ($query->num_rows()>0)
		{
			$data  = $query->result_array();
			for ($i=0; $i<count($data); $i++)
			{
				$data[$i]['link'] = $this->web_sosmed_model->link_sosmed($data[$i]['id'], $data[$i]['link']);
			}
		}
		else
		{
			$data = false;
		}
		return $data;
	}

}
