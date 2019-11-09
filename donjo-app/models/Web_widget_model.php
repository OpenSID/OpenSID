<?php class Web_widget_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('first_gallery_m');
		$this->load->model('laporan_penduduk_model');
		$this->load->model('pamong_model');
		$this->load->model('keuangan_grafik_model');
	}

	public function autocomplete()
	{
		$str = autocomplete_str('judul', 'widget');
		return $str;
	}

	public function get_widget($id='')
	{
		$data = $this->db->where('id', $id)->get('widget')->row_array();
		$data['judul'] = htmlentities($data['judul']);
		$data['isi'] = $this->security->xss_clean($data['isi']);
		return $data;
	}

	public function get_widget_aktif()
	{
		$data = $this->db->where('enabled', 1)->
			order_by('urut')->
			get('widget')->result_array();
		return $data;
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (judul LIKE '$kw' OR isi LIKE '$kw')";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND enabled = $kf";
			return $filter_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(*) as jml " . $this->list_data_sql();
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

	private function list_data_sql()
	{
		$sql = " FROM widget WHERE 1";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		$order_sql = ' ORDER BY urut';
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql = "SELECT * " . $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data = $query->result_array();

		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;

			if ($data[$i]['enabled'] == 1)
				$data[$i]['aktif'] = "Ya";
			else
			{
				$data[$i]['aktif'] = "Tidak";
				$data[$i]['enabled'] = 2;
			}
			$teks = htmlentities($data[$i]['isi']);
			if (strlen($teks) > 150)
			{
				$abstrak = substr($teks,0,150)."...";
			}
			else
			{
				$abstrak = $teks;
			}
			$data[$i]['isi'] = $abstrak;

			$j++;
		}
		$data = $this->security->xss_clean($data);
		return $data;
	}

  private function urut_max()
  {
    $this->db->select_max('urut');
    $query = $this->db->get('widget');
    $widget = $query->row_array();
    return $widget['urut'];
  }

	private function urut_semua()
	{
		$sql = "SELECT urut, COUNT(*) c FROM widget GROUP BY urut HAVING c > 1";
		$query = $this->db->query($sql);
		$urut_duplikat = $query->result_array();
		if ($urut_duplikat)
		{
			$this->db->select("id");
			$this->db->order_by("urut");
			$q = $this->db->get('widget');
			$widgets = $q->result_array();
			for ($i=0; $i<count($widgets); $i++)
			{
				$this->db->where('id', $widgets[$i]['id']);
				$data['urut'] = $i + 1;
				$this->db->update('widget', $data);
			}
		}
	}

	/**
	 * @param $id Id widget
	 * @param $arah Arah untuk menukar dengan widget: 1) bawah, 2) atas
	 * @return int Nomer urut widget lain yang ditukar
	 */
	public function urut($id, $arah)
	{
		$this->urut_semua();
		$this->db->where('id', $id);
		$q = $this->db->get('widget');
		$widget1 = $q->row_array();

		$this->db->select("id, urut");
		$this->db->order_by("urut");
		$q = $this->db->get('widget');
		$widgets = $q->result_array();
		for ($i=0; $i<count($widgets); $i++)
		{
			if ($widgets[$i]['id'] == $id)
				break;
		}

		if ($arah == 1)
		{
			if ($i >= count($widgets) - 1) return;
			$widget2 = $widgets[$i + 1];
		}
		if ($arah == 2)
		{
			if ($i <= 0) return;
			$widget2 = $widgets[$i - 1];
		}

		// Tukar urutan
		$this->db->where('id', $widget2['id'])->
			update('widget', array('urut' => $widget1['urut']));
		$this->db->where('id', $widget1['id'])->
			update('widget', array('urut' => $widget2['urut']));

		return (int)$widget2['urut'];
	}

	public function lock($id='', $val=0)
	{
		$sql  = "UPDATE widget SET enabled = ? WHERE id = ?";
		$this->db->query($sql, array($val, $id));
	}

	public function insert()
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = "";

		$data = $_POST;
		$data['enabled'] = 2;

		// Widget diberi urutan terakhir
		$data['urut'] = $this->urut_max() + 1;
		if ($data['jenis_widget'] == 2)
		{
			$data['isi'] = $data['isi-statis'];
		}
		elseif ($data['jenis_widget'] == 3)
		{
			$data['isi'] = $data['isi-dinamis'];
		}
		unset($data['isi-dinamis']);
		unset($data['isi-statis']);

		$outp = $this->db->insert('widget', $data);
		if (!$outp) $_SESSION['success'] = -1;
	}

	public function update($id=0)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = "";

	  $data = $_POST;
	  unset($data['isi']);

		// Widget isinya tergantung jenis widget
		if ($data['jenis_widget'] == 2)
		{
			$this->db->set('isi', $data['isi-statis']);
		}
		elseif ($data['jenis_widget'] == 3)
		{
			$this->db->set('isi', $data['isi-dinamis']);
		}
		unset($data['isi-dinamis']);
		unset($data['isi-statis']);

		$this->db->where('id', $id);
		$outp = $this->db->update('widget', $data);
		if (!$outp) $_SESSION['success'] = -1;
	}

	public function get_setting($widget, $opsi='')
	{
	  // Data di kolom setting dalam format json
		$setting = $this->db->select('setting')->
			where('isi',$widget.'.php')->
			get('widget')->row_array();
		$setting = json_decode($setting['setting'], true);
		return empty($opsi) ? $setting : $setting[$opsi];
	}

	protected function filter_setting($k)
	{
  	$berisi = false;
  	foreach ($k as $kolom)
  	{
  		if ($kolom)
  		{
  			$berisi = true;
	  		break;
	  	}
  	}
  	return $berisi;
	}

  private function sort_sinergi_program($a, $b)
  {
      $keya = str_pad($a['baris'], 2, '0', STR_PAD_LEFT).$a['kolom'];
      $keyb = str_pad($b['baris'], 2, '0', STR_PAD_LEFT).$b['kolom'];
      return $keya > $keyb;
  }

  private function upload_gambar_sinergi_program(&$setting)
  {
  	foreach ($setting as $key => $value)
  	{
		  $lokasi_file = $_FILES['setting']['tmp_name'][$key]['gambar'];
		  $tipe_file = $_FILES['setting']['type'][$key]['gambar'];
		  $nama_file = $_FILES['setting']['name'][$key]['gambar'];
		  $fp = time();
		  $nama_file   = $fp . "_". str_replace(' ', '-', $nama_file); 	 // normalkan nama file
			$old_gambar    = $value['old_gambar'];
			$setting[$key]['gambar'] = $old_gambar;
			if (!empty($lokasi_file))
			{
				if (in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)))
				{
					UploadGambarWidget($nama_file, $lokasi_file, $old_gambar);
					$setting[$key]['gambar'] = $nama_file;
				}
				else
				{
					$_SESSION['success'] = -1;
					$_SESSION['error_msg'] = " -> Jenis file " . $nama_file ." salah: " . $tipe_file;
				}
			}
	  }
  }

	public function update_setting($widget, $setting)
	{
		$_SESSION['success'] = 1;
	  switch ($widget)
	  {
	  	case 'sinergi_program':
			  // Upload semua gambar setting
			  $this->upload_gambar_sinergi_program($setting);
			  // Hapus setting kosong menggunakan callback
			  $setting = array_filter($setting, array($this,'filter_setting'));
			  // Sort setting berdasarkan [baris][kolom]
			  usort($setting, array($this,"sort_sinergi_program"));
	  		break;
	  	default:
	  		break;
	  }
 	  // Simpan semua setting di kolom setting sebagai json
	  $setting = json_encode($setting);
	  $data = array('setting' => $setting);
		$outp = $this->db->where('isi', $widget.'.php')->update('widget', $data);
		if (!$outp) $_SESSION['success'] = -1;
	}

	public function delete($id='')
	{
		$sql = "DELETE FROM widget WHERE id = ? AND jenis_widget <> 1";
		$outp = $this->db->query($sql, array($id));

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];

		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$sql = "DELETE FROM widget WHERE id = ? AND jenis_widget <> 1";
				$outp = $this->db->query($sql, array($id));
			}
		}
		else $outp = false;

		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}

	// pengambilan data yang akan ditampilkan di widget
	public function get_widget_data(&$data)
	{
		$data['w_gal']  = $this->first_gallery_m->gallery_widget();
		$data['agenda'] = $this->first_artikel_m->agenda_show();
		$data['komen'] = $this->first_artikel_m->komentar_show();
		$data['sosmed'] = $this->first_artikel_m->list_sosmed();
		$data['arsip'] = $this->first_artikel_m->arsip_show();
		$data['arsip_rand'] = $this->first_artikel_m->arsip_rand();
		$data['aparatur_desa'] = $this->pamong_model->list_data(true);
		$data['stat_widget'] = $this->laporan_penduduk_model->list_data(4);
		$data['sinergi_program'] = $this->get_setting('sinergi_program');
	 	$data['widget_keuangan'] = $this->keuangan_grafik_model->widget_keuangan();
	}

}
?>
