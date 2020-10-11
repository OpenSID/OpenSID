<?php class Mandiri_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		$data = $this->db->select('p.nik, p.nama')
			->join('tweb_penduduk p','p.id = m.id_pend', 'left')
			->from('tweb_penduduk_mandiri m')
			->get()
			->result_array();

		return autocomplete_data_ke_str($data);
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND (n.nik LIKE '$kw' OR n.nama LIKE '$kw')";
			return $search_sql;
			}
		}

	public function paging($p=1, $o=0)
	{
		$list_data_sql = $this->list_data_sql($log);
		$sql = "SELECT COUNT(*) AS jml ".$list_data_sql;
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
		$sql = "FROM tweb_penduduk_mandiri u
			LEFT JOIN tweb_penduduk n ON u.id_pend = n.id
			WHERE 1";
		$sql .= $this->search_sql();
		return $sql;
	}

	public function list_data($o=0, $offset=0, $limit=500)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.last_login'; break;
			case 2: $order_sql = ' ORDER BY u.last_login DESC'; break;
			default:$order_sql = ' ORDER BY u.tanggal_buat DESC';
		}

		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		//Main Query
		$select_sql = "SELECT u.*, n.nama AS nama, n.nik AS nik ";
		$list_data_sql = $this->list_data_sql();
		$sql = $select_sql." ".$list_data_sql;

		$sql .= $order_sql;
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data = $query->result_array();
		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	private function generate_pin($pin="")
	{
		if ($pin == "")
		{
			$pin = rand(100000, 999999);
			$pin = strrev($pin);
		}
		return $pin;
	}

	public function insert()
  {
    if ($_POST['nik'] == "")
    {
        redirect("mandiri");
    }
    if (empty($_POST['pin']))
    {
    	$rpin = $this->generate_pin($_POST['pin']);
    }
    else
    {
	    // load library form_validation
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('pin', 'Pin', 'trim|numeric|required|min_length[6]|max_length[6]');
	    if ($this->form_validation->run() !== true)
	    {
	    	$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = 'PIN harus 6 (enam) digit angka.';
				redirect('mandiri');
	    }
	    $rpin = $_POST['pin'];
    }

    $sql = "DELETE FROM tweb_penduduk_mandiri
				WHERE id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, ?) = 0)";
    $outp = $this->db->query($sql, array($_POST['nik']));
    $hash_pin = hash_pin($rpin);
    $data['pin'] = $hash_pin;
    $data['id_pend'] = $this->db->select('id')->where('nik', $_POST['nik'])
        ->get('tweb_penduduk')->row()->id;
    $data['tanggal_buat'] = date("Y-m-d H:i:s");
    $outp = $this->db->insert('tweb_penduduk_mandiri', $data);
    if ($_POST['pin'] != "")
    {
      return $_POST['pin'];
    }
    else
    {
      return $rpin;
    }
  }

	public function delete($id_pend='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id_pend', $id_pend)->delete('tweb_penduduk_mandiri');

		status_sukses($outp, $gagal_saja=true); //Tampilkan Pesan
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

	public function list_penduduk()
	{
		$data = $this->db->select('nik AS id, nik, nama')
			->where('nik <>', '')
			->where('nik <>', 0)
			->where('id NOT IN (SELECT id_pend FROM tweb_penduduk_mandiri)')
			->get('penduduk_hidup')
			->result_array();

		//Formating Output AND nik NOT IN(SELECT nik FROM tweb_penduduk_mandiri)
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['alamat'] = "Alamat :".$data[$i]['nama'];
		}
		return $data;
	}

	public function get_penduduk($id)
	{
		$data = $this->db->select('nik AS id, nik, nama')
			->where('id', $id)
			->get('penduduk_hidup')
			->row_array();

		return $data;
	}

	private function list_data_ajax_sql($cari = '')
	{
		$this->db
			->select('u.*, n.nama AS nama, n.nik AS nik')
			->from('tweb_penduduk_mandiri u')
			->join('tweb_penduduk n', 'u.id_pend = n.id', 'left')
			->join('tweb_wil_clusterdesa w', 'n.id_cluster = w.id', 'left');
		if ($cari)
		{
			$this->db
				->where("(nik like '%{$cari}%' or nama like '%{$cari}%')");
		}
	}

	public function list_data_ajax($cari, $page)
	{
		$this->list_data_ajax_sql($cari);
		$jml = $this->db->select('count(u.id_pend) as jml')
				->get()->row()->jml;
		$result_count = 25;
		$offset = ($page - 1) * $result_count;

		$this->list_data_ajax_sql($cari);
		$this->db
			->distinct()
			->select('u.id_pend, nik, nama, w.dusun, w.rw, w.rt')
			->limit($result_count, $offset);
		$data = $this->db->get()->result_array();

		foreach ($data as $row ) {
			$nama = addslashes($row['nama']);
			$alamat = addslashes("Alamat: RT-{$row['rt']}, RW-{$row['rw']} {$row['dusun']}");
			$outp = "{$row['nik']} - {$nama} \n {$alamat}";
			$pendaftar_mandiri[] = array(
				'id' => $row['nik'],
				'text' => $outp
			);
		}

		$end_count = $offset + $result_count;
		$more_pages = $end_count < $jml;

		$result = array(
			'results' => $pendaftar_mandiri,
			"pagination" => array(
        "more" => $more_pages
      )
		);
		return $result;
	}

	public function get_pendaftar_mandiri($nik)
	{
		return $this->db
			->select('id, nik, nama')
			->from('tweb_penduduk')
			->where('status', 1)
			->where('nik', $nik)
			->get()
			->row_array();
	}

	public function update($id_pend)
	{
		$pin = $this->input->post('pin');

		if (empty($pin))
		{
			$rpin = $this->generate_pin($pin);
		}
		else
		{
			// load library form_validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('pin', 'Pin', 'trim|numeric|required|min_length[6]|max_length[6]');
			if ($this->form_validation->run() !== true)
			{
				$_SESSION['success'] = -1;
				$_SESSION['error_msg'] = 'PIN harus 6 (enam) digit angka.';
				redirect('mandiri');
			}
			$rpin = $pin;
		}

		$hash_pin = hash_pin($rpin);
  	$data['pin'] = $hash_pin;
		$data['tanggal_buat'] = date("Y-m-d H:i:s");
		$this->db->where('id_pend', $id_pend);
		$this->db->update('tweb_penduduk_mandiri', $data);

    return $rpin;
	}

	#Login Layanan Mandiri

	public function siteman()
	{
		$_SESSION['mandiri'] = -1;
		$nik = $this->input->post('nik');
		$pin = $this->input->post('pin');
		$hash_pin = hash_pin($pin);

		$row = $this->db->select('pin, last_login')
			->where('p.nik', $nik)
			->from('tweb_penduduk_mandiri m')
			->join('tweb_penduduk p', 'm.id_pend = p.id', 'left')
			->get()->row();

		$lg = $row->last_login;

		if ($hash_pin == $row->pin)
		{
			$sql = "SELECT nama,nik,p.id,k.no_kk
				FROM tweb_penduduk p
				LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
				WHERE nik = ?";
			$query = $this->db->query($sql, array($nik));
			$row = $query->row();
			// Kosong jika NIK penduduk ybs telah berubah
			if (!empty($row))
			{
				// Kalau pertama kali login, pengguna perlu mengganti PIN ($_SESSION['lg'] == 1)
				if ($lg == NULL OR $lg == "0000-00-00 00:00:00")
				$_SESSION['lg'] = 1;
				$_SESSION['nama'] = $row->nama;
				$_SESSION['nik'] = $row->nik;
				$_SESSION['id'] = $row->id;
				$_SESSION['no_kk'] = $row->no_kk;

				if ($this->session->lg == 2)
				{
					$_SESSION['mandiri'] = 1;
				}
			}
		}

		if ($_SESSION['mandiri_try'] > 2)
		{
			$_SESSION['mandiri_try'] = $_SESSION['mandiri_try'] - 1;
		}
		else
		{
			$_SESSION['mandiri_wait'] = 1;
		}
	}

	public function logout()
	{
		if (isset($_SESSION['nik']))
		{
			$nik = $_SESSION['nik'];
			$sql = "UPDATE tweb_penduduk_mandiri SET last_login = NOW()
				WHERE id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, ?) = 0)";
			$this->db->query($sql, $nik);
		}
		unset($_SESSION['mandiri']);
		unset($_SESSION['id']);
		unset($_SESSION['nik']);
		unset($_SESSION['nama']);
	}

	public function update_pin($nik = 0)
	{
		$_SESSION['success'] = 1;
		$_SESSION['error_msg'] = '';

		$nik = $_SESSION['nik'];
		$pin_lama = hash_pin($_POST['pin_lama']);
		$pin1 = hash_pin($_POST['pin1']);
		$pin2 = hash_pin($_POST['pin2']);

		// Ganti password
		if ($pin_lama != ''	|| $pin1 != '' || $pin2 != '')
		{
			$row = $this->db->select('pin, last_login')
			->where('p.nik', $nik)
			->from('tweb_penduduk_mandiri m')
			->join('tweb_penduduk p', 'm.id_pend = p.id', 'left')
			->get()->row();

			if ($pin_lama != $row->pin)
			{
				$_SESSION['error_msg'] .= 'PIN lama salah<br />';
			}
			if (empty($pin1))
			{
				$_SESSION['error_msg'] .= 'PIN baru tidak boleh kosong<br />';
			}
			if ($pin1 != $pin2)
			{
				$_SESSION['error_msg'] .= 'Ulang PIN baru tidak cocok<br />';
			}
			if (!empty($_SESSION['error_msg']))
			{
				$_SESSION['success'] = -1;
			}
			else
			{
				$hash_pin = $pin1;
				$data['pin'] = $hash_pin;
				$this->db->where("id_pend = (SELECT id FROM tweb_penduduk WHERE strcmp(nik, {$_SESSION['nik']}) = 0)");
				$outp = $this->db->update('tweb_penduduk_mandiri', $data);
				$_SESSION['lg'] = 2;
			}
		}
	}

}
