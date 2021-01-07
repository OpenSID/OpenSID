<?php class Keluarga_model extends CI_Model {

/**
 * File ini:
 *
 * Model data Keluarga untuk komponen Admin
 *
 * donjo-app/models/Keluarga_model.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['program_bantuan_model', 'penduduk_model', 'web_dokumen_model', 'config_model']);
	}

	public function autocomplete($cari='')
	{
		if ($cari)
		{
			$cari = $this->db->escape_like_str($cari);
			$this->db->like('t.nama', $cari);
		}
		$this->db->select('t.nama')
			->distinct()
			->from('tweb_keluarga u')
			->join('tweb_penduduk t', 'u.nik_kepala = t.id', 'left')
			->order_by('t.nama');
		$data = $this->db->get()->result_array();

		return autocomplete_data_ke_str($data);
	}

	/*
		1 - tampilkan keluarga di mana KK mempunyai status dasar 'hidup'
		2 - tampilkan keluarga di mana KK mempunyai status dasar 'hilang/pindah/mati'
		3 - tampilkan keluarga di mana KK tidak ada'
	*/
	private function status_dasar_sql()
	{
		$value = $this->session->status_dasar;

		if (isset($value))
		{
			if ($value == '1') $status_dasar_sql = " AND t.status_dasar = 1 AND t.kk_level = 1";
			elseif ($value == '3') $status_dasar_sql = 'AND (t.status_dasar IS NULL OR t.kk_level <> 1)';
			else $status_dasar_sql = " AND t.status_dasar <> 1";
			return $status_dasar_sql;
		}
	}

	private function search_sql()
	{
		$value = $this->session->cari;

		if (isset($value))
		{
			$kw = $this->db->escape_like_str($value);
			$kw = '%' .$kw. '%';
			$search_sql = " AND (t.nama LIKE '$kw' OR u.no_kk LIKE '$kw' OR t.tag_id_card LIKE '$kw')";
			return $search_sql;
		}
	}

	private function kumpulan_kk_sql()
	{
		if (empty($this->session->kumpulan_kk)) return;

		$kumpulan_kk = preg_replace('/[^0-9\,]/', '', $this->session->kumpulan_kk);
		$kumpulan_kk = array_filter(array_slice(explode(",", $kumpulan_kk), 0, 20)); // ambil 20 saja
		$kumpulan_kk = implode(',', $kumpulan_kk);
		$this->session->kumpulan_kk = $kumpulan_kk;
		$sql = " AND u.no_kk in ($kumpulan_kk)";
		return $sql;
	}

	public function paging($p = 1)
	{
		$sql = "SELECT COUNT(*) AS jml ".$this->list_data_sql();
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $this->session->per_page;
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	private function list_data_sql()
	{
		$sql = "FROM tweb_keluarga u
			LEFT JOIN tweb_penduduk t ON u.nik_kepala = t.id
			LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id";

		$sql .= " WHERE 1 ";
		$sql .=	$this->search_sql();
		$sql .=	$this->kumpulan_kk_sql();
		$sql .=	$this->status_dasar_sql();

		$kolom_kode = [
			array('dusun', 'c.dusun'),
			array('rw', 'c.rw'),
			array('rt', 'c.rt'),
			array('sex', 't.sex'),
			array('kelas', 'u.kelas_sosial'),
			array('id_bos', 'id_bos'),
		];

		foreach ($kolom_kode as $kolom)
		{
			$sql .= $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
		}

		return $sql;
	}

	protected function get_sql_kolom_kode($session, $kolom)
	{
		$kf = $this->session->$session;
		if ( ! empty($kf))
		{
			if ($kf == JUMLAH)
				$sql = " AND (" . $kolom . " IS NOT NULL OR " . $kolom . " != '')";
			else if ($kf == BELUM_MENGISI)
				$sql = " AND (" . $kolom . " IS NULL OR " . $kolom . " = '')";
			else
				$sql = " AND " . $kolom . " = '$kf'";

			return $sql;
		}
	}

	// $limit = 0 mengambil semua
	public function list_data($o = 0, $offset = 0, $limit = 0)
	{
		//Ordering SQL
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY u.no_kk'; break;
			case 2: $order_sql = ' ORDER BY u.no_kk DESC'; break;
			case 3: $order_sql = ' ORDER BY kepala_kk'; break;
			case 4: $order_sql = ' ORDER BY kepala_kk DESC'; break;
			case 5: $order_sql = ' ORDER BY u.tgl_daftar'; break;
			case 6: $order_sql = ' ORDER BY u.tgl_daftar DESC'; break;
			default:$order_sql = ' ORDER BY u.no_kk DESC';
		}

		//Paging SQL
		$paging_sql = $limit > 0 ? ' LIMIT ' . $offset . ',' . $limit : '';

		$sql = "SELECT u.*, t.nama AS kepala_kk, t.nik, t.tag_id_card, t.sex, t.status_dasar, t.foto, t.id as id_pend,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE id_kk = u.id AND status_dasar = 1) AS jumlah_anggota,
			c.dusun, c.rw, c.rt ";
		$sql .= $this->list_data_sql();
		$sql .= $order_sql;
		$sql .= $paging_sql;

		$query = $this->db->query($sql);
		$data=$query->result_array();

		//Formating Output
		$j = $offset;
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			if ($data[$i]['jumlah_anggota'] == 0)
				$data[$i]['jumlah_anggota'] = "-";

			if ($data[$i]['sex'] == 1)
				$data[$i]['sex'] = "LAKI-LAKI";
			else
				$data[$i]['sex'] = "PEREMPUAN";
			$j++;
		}

		return $data;
	}

	// Tambah keluarga baru dari penduduk lepas (status tetap atau pendatang)
	public function insert()
	{
		unset($_SESSION['error_msg']);
		$data = $_POST;

		if (!$this->validasi_data_keluarga($data)) return;

		$pend = $this->db->select('alamat_sekarang, id_cluster')->
			where('id', $data['nik_kepala'])->
			get('tweb_penduduk')->row_array();
		// Gunakan alamat penduduk sebagai alamat keluarga
		$data['alamat'] = $pend['alamat_sekarang'];
		$data['id_cluster'] = $pend['id_cluster'];

		$outp = $this->db->insert('tweb_keluarga', $data);
		$kk_id = $this->db->insert_id();

		$default['id_kk'] = $kk_id;
		$default['kk_level'] = 1;
		$default['status'] = 1; // statusnya menjadi tetap
		$default['updated_at'] = date('Y-m-d H:i:s');
		$default['updated_by'] = $this->session->user;
		$this->db->where('id', $data['nik_kepala']);
		$this->db->update('tweb_penduduk', $default);

		$this->penduduk_model->tulis_log_penduduk($kk_id, '9', date('m'), date('Y'));

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('log_perubahan_penduduk', $log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk_id, $data['nik_kepala'], 1);

		status_sukses($outp); //Tampilkan Pesan
	}

	private function validasi_data_keluarga(&$data)
	{
		// Sterilkan data
		$data['alamat'] = strip_tags($data['alamat']);

		if (!empty($data['id']))
		{
			$nokk_lama = $this->get_nokk($data['id']);
			if ($data['no_kk'] == $nokk_lama) return true; // Tidak berubah
		}
		$valid = array();
		if (isset($data['no_kk']))
		{
			if (!ctype_digit($data['no_kk']))
				array_push($valid, "Nomor KK hanya berisi angka");
			if (strlen($data['no_kk']) != 16 AND $data['no_kk'] != '0')
				array_push($valid, "Nomor KK panjangnya harus 16 atau 0");
			if ($this->db->select('no_kk')->from('tweb_keluarga')->where(array('no_kk'=>$data['no_kk']))->limit(1)->get()->row()->no_kk)
				array_push($valid, "Nomor KK {$data['no_kk']} sudah digunakan");
		}
		if (!empty($valid))
		{
			$_SESSION['validation_error'] = true;
			foreach ($valid as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return false;
		}

		return true;
	}

	public function insert_new()
	{
		unset($_SESSION['validation_error']);
		unset($_SESSION['success']);
		unset($_SESSION['error_msg']);
		$data = $_POST;

		if (!$this->validasi_data_keluarga($data)) return;

		$error_validasi = $this->penduduk_model->validasi_data_penduduk($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success']=-1;
			return;
		}

		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file   = $_FILES['foto']['type'];
		$nama_file   = $_FILES['foto']['name'];
		$nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		$old_foto    = '';
		if (!empty($lokasi_file))
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png")
			{
				unset($data['foto']);
			}
			else
			{
				UploadFoto($nama_file,$old_foto,$tipe_file);
				$data['foto'] = $nama_file;
			}
		}
		else
		{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);
		unset($data['kk_level_lama']);

		UNSET($data['dusun']);
		UNSET($data['rw']);
		UNSET($data['no_kk']);

		// Simpan alamat keluarga sebelum menulis penduduk
		$data2['alamat'] = $data['alamat'];
		UNSET($data['alamat']);

		// Tulis penduduk baru sebagai kepala keluarga
		$data['kk_level'] = 1;
		$data['created_by'] = $this->session->user;
		$outp = $this->db->insert('tweb_penduduk', $data);
		$id_pend = $this->db->insert_id();
		status_sukses($outp); //Tampilkan Pesan

		// Tulis keluarga baru
		$data2['nik_kepala'] = $id_pend;
		$data2['no_kk'] = $_POST['no_kk'];
		$data2['id_cluster'] = $data['id_cluster'];
		$outp = $this->db->insert('tweb_keluarga', $data2);
		$kk_id = $this->db->insert_id();

		// Update penduduk kaitkan dengan KK
		$default['updated_at'] = date('Y-m-d H:i:s');
		$default['updated_by'] = $this->session->user;
		$default['id_kk'] = $kk_id;
		$this->db->where('id', $id_pend);
		$this->db->update('tweb_penduduk', $default);

		$satuan = $_POST['tanggallahir'];
		$blnlahir = substr($satuan,3,2);
		$thnlahir = substr($satuan,6,4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if (($blnlahir == $blnskrg) and ($thnlahir == $thnskrg))
		{
			$x['id_detail'] = '1';
		}
		else
		{
			$x['id_detail'] = '5';
		}

		$x['id_pend'] = $id_pend;
		$x['bulan'] = $blnskrg;
		$x['tahun'] = $thnskrg;
		$this->penduduk_model->tulis_log_penduduk_data($x);

		$log['id_pend'] = 1;
		$log['id_cluster'] = 1;
		$log['tanggal'] = date("Y-m-d H:i:s");
		$outp = $this->db->insert('log_perubahan_penduduk', $log);

		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($kk_id, $data2['nik_kepala'], 1);

		status_sukses($outp); //Tampilkan Pesan
	}

	/* 	Hapus keluarga:
			(1) Untuk setiap anggota keluarga lakukan rem_anggota (pecah kk).
			(2) Hapus keluarga
			$id adalah id tweb_keluarga
	*/
	public function delete($id = 0, $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$nik_kepala = $this->db->select('nik_kepala')->where('id',$id)->get('tweb_keluarga')->row()->nik_kepala;
		$list_anggota = $this->db->select('id')->where('id_kk',$id)->get('tweb_penduduk')->result_array();
		foreach ($list_anggota as $anggota)
		{
			$this->rem_anggota($id,$anggota['id']);
		}
		$outp = $this->db->where('id',$id)->delete('tweb_keluarga');
		// Untuk statistik perkembangan keluarga
		$this->log_keluarga($id, $nik_kepala, 2);

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

	/* 	Untuk statistik perkembangan keluarga
			id_peristiwa:
				 1 - keluarga baru
				 2 - keluarga dihapus
				 3 - kepala keluarga status dasar kembali 'hidup' (salah mengisi di log_penduduk)
				 4 - kepala keluarga status dasar 'mati'
				 5 - kepala keluarga status dasar 'pindah'
				 6 - kepala keluarga status dasar 'hilang'
	*/
	public function log_keluarga($id, $kk, $id_peristiwa)
	{
		$this->db->select('sex');
		$this->db->where('id', $kk);
		$q = $this->db->get('tweb_penduduk');
		$penduduk = $q->row_array();
		$log_keluarga['id_kk'] = $id;
		$log_keluarga['kk_sex'] = $penduduk['sex'];
		$log_keluarga['id_peristiwa'] = $id_peristiwa;
		$log_keluarga['tgl_peristiwa'] = date('Y-m-d H:i:s');
		$outp = $this->db->insert('log_keluarga', $log_keluarga);
	}

	public function add_anggota($id = 0)
	{
		$data = $_POST;
		$this->update_kk_level($data['nik'], $id, $data['kk_level'], null);

		$temp['id_kk'] = $id;
		$temp['kk_level'] = $data['kk_level'];
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;

		$this->db->where('id', $data['nik']);
		$outp = $this->db->update('tweb_penduduk', $temp);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function update_kk_level($id, $id_kk, $kk_level, $kk_level_lama)
	{
		$outp = true;
		if ($kk_level == 1 and $kk_level_lama != 1)
		{
			// Kalau ada penduduk lain yg juga Kepala Keluarga, ubah menjadi hubungan Lainnya
			$lvl['kk_level'] = 11;
			$lvl['updated_at'] = date('Y-m-d H:i:s');
			$lvl['updated_by'] = $this->session->user;
			$this->db->where('id_kk', $id_kk);
			$this->db->where('kk_level', 1);
			$this->db->update('tweb_penduduk', $lvl);

			$nik['nik_kepala'] = $id;
			$this->db->where('id', $id_kk);
			$outp = $this->db->update('tweb_keluarga', $nik);
		}
		elseif ($kk_level_lama == 1 and $kk_level != 1)
		{
			// Ubah kepala keluarga menjadi kosong
			$nik['nik_kepala'] = NULL;
			$this->db->where('id', $id_kk);
			$outp = $this->db->update('tweb_keluarga', $nik);
		}

		return $outp;
	}

	public function update_anggota($id = 0)
	{
		$data = $_POST;

		$sql = "SELECT id_kk FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$pend = $query->row_array();

		$this->update_kk_level($id, $pend['id_kk'], $data['kk_level'], $data['kk_level_lama']);
		unset($data['kk_level_lama']);

		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user;
		$this->db->where('id', $id);
		$outp = $this->db->update('tweb_penduduk', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function rem_anggota($kk = 0, $id = 0)
	{
		$pend = $this->keluarga_model->get_anggota($id);
		$temp['no_kk_sebelumnya'] = $this->db->select('no_kk')->where('id', $kk)->get('tweb_keluarga')->row()->no_kk;
		$temp['id_kk'] = 0;
		$temp['kk_level'] = 0;
		$temp['updated_at'] = date('Y-m-d H:i:s');
		$temp['updated_by'] = $this->session->user;
		$this->db->where('id',$id);
		$outp = $this->db->update('tweb_penduduk', $temp);
		if ($pend['kk_level'] == '1')
		{
			$temp2['nik_kepala'] = 0;
			$this->db->where('id', $pend['id_kk']);
			$outp = $this->db->update('tweb_keluarga', $temp2);
		}

		// hapus dokumen bersama dengan kepala KK sebelumnya
		$this->web_dokumen_model->hard_delete_dokumen_bersama($id);

		$this->penduduk_model->tulis_log_penduduk($id, '7', date('m'), date('Y'));
	}

	public function rem_all_anggota($kk)
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$this->rem_anggota($kk, $id);
			}
		}
	}

	// TODO: Gunakan wilayah_model
	public function get_dusun($id = 0)
	{
		$sql = "SELECT * FROM tweb_keluarga WHERE dusun_id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();

		return $data;
	}

	public function get_keluarga($id = 0)
	{
		$sql = "SELECT k.*, b.dusun as dusun, b.rw as rw
			FROM tweb_keluarga k
			LEFT JOIN tweb_wil_clusterdesa b ON k.id_cluster = b.id
			WHERE k.id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		$data['alamat_plus_dusun'] = $data['alamat'];
		$data['tgl_cetak_kk'] = tgl_indo_out($data['tgl_cetak_kk']);

		return $data;
	}

	public function get_data_cetak_kk($id = 0)
	{
		$kk['id_kk'] = $id;
		$kk['main'] = $this->keluarga_model->list_anggota($id);
		$kk['kepala_kk'] = $this->keluarga_model->get_kepala_kk($id);
		$kk['desa'] = $this->config_model->get_data();
		$data['all_kk'][] = $kk;

		return $data;
	}

	public function get_data_cetak_kk_all()
	{
		$data = array();
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			foreach ($id_cb as $id)
			{
				$kk = $this->get_data_cetak_kk($id);
				$data['all_kk'][] = $kk['all_kk'][0]; //Kumpulkan semua kk
			}
		}

		return $data;
	}

	public function get_anggota($id = 0)
	{
		$sql = "SELECT * FROM tweb_penduduk WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();

		return $data;
	}

	public function list_penduduk_lepas()
	{
		$sql = "SELECT u.id, u.nik, u.nama, u.alamat_sekarang as alamat, w.rt, w.rw, w.dusun
			FROM tweb_penduduk u
			LEFT JOIN tweb_wil_clusterdesa w ON u.id_cluster = w.id
			WHERE (status = 1 ) AND id_kk = 0";
		$query = $this->db->query($sql);
		$data = $query->result_array();

		return $data;
	}

	// $options['dengan_kk'] = false jika hanya perlu tanggungan keluarga tanpa kepala keluarga
	// $options['pilih'] untuk membatasi ke nik tertentu saja
	public function list_anggota($id=0,$options=array('dengan_kk'=>true))
	{
		$sql = "SELECT u.*, u.sex as sex_id, u.status_kawin as status_kawin_id,
			(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 FROM tweb_penduduk WHERE id = u.id) AS umur,
			(CASE when u.status_kawin <> 2
				then w.nama
				else
					case when u.akta_perkawinan = ''
						then 'KAWIN BELUM TERCATAT'
						else 'KAWIN TERCATAT'
					end
				end) as status_kawin,
			b.dusun, b.rw, b.rt, x.nama as sex, u.kk_level, a.nama as agama, d.nama as pendidikan,j.nama as pekerjaan, f.nama as warganegara, g.nama as golongan_darah, h.nama AS hubungan, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_pekerjaan j ON u.pekerjaan_id = j.id
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_golongan_darah g ON u.golongan_darah_id = g.id
			LEFT JOIN tweb_penduduk_kawin w ON u.status_kawin = w.id
			LEFT JOIN tweb_penduduk_sex x ON u.sex = x.id
			LEFT JOIN tweb_penduduk_hubungan h ON u.kk_level = h.id
			LEFT JOIN tweb_wil_clusterdesa b ON u.id_cluster = b.id
			LEFT JOIN tweb_keluarga k ON u.id_kk = k.id
			WHERE status = 1 AND status_dasar = 1 AND id_kk = ?";
		if ($options['dengan_kk'] !== NULL AND !$options['dengan_kk']) $sql .= " AND kk_level <> 1";
		if (!empty($options['pilih'])) $sql .= " AND u.nik IN (".$options['pilih'].")";
		$sql .= " ORDER BY kk_level, tanggallahir";
		$query = $this->db->query($sql, array($id));
		$data = $query->result_array();

		return $data;
	}

	// $id adalah id_kk : id dari tabel tweb_keluarga, kecuali
	// apabila $is_no_kk == true maka $id adalah no_kk
	public function get_kepala_kk($id, $is_no_kk = false)
	{
		// Buat subquery umur
		$kolom_id = ($is_no_kk) ? "no_kk" : "id";
		$this->db->select("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0")
			->from('tweb_penduduk')
			->where('id = u.id');
		$umur = $this->db->get_compiled_select();
		// Buat subquery untuk setiap kolom yg diperlukan dari tweb_keluarga
		$list_kk = array_map(function ($a) use ($kolom_id, $id)
		{
			$this->db->select($a)
				->from('tweb_keluarga')
				->where($kolom_id, $id);
			return $this->db->get_compiled_select();
		}, ['no_kk', 'alamat', 'id', 'id_cluster', 'nik_kepala']);
		foreach (['no_kk', 'alamat', 'id_kk', 'id_cluster', 'nik_kepala'] as $key => $a)
		{
			$$a = $list_kk[$key]; // Hasilkan variabel dgn nama dari string
		}

		$this->db
			->select('nik, u.id, u.nama, u.tanggalperkawinan, u.status_kawin as status_kawin_id, u.sex as sex_id, tempatlahir, tanggallahir')
			->select('('.$umur.') AS umur')
			->select('a.nama as agama, d.nama as pendidikan, j.nama as pekerjaan, x.nama as sex, w.nama as status_kawin')
			->select('h.nama as hubungan, f.nama as warganegara, warganegara_id, nama_ayah, nama_ibu, g.nama as golongan_darah')
			->select('c.rt as rt, c.rw as rw, c.dusun as dusun')
			->select('('.$no_kk.') AS no_kk')
			->select('('.$alamat.') AS alamat')
			->select('('.$id_kk.') AS id_kk')
			->from('tweb_penduduk u')
			->join('tweb_penduduk_pekerjaan j', 'u.pekerjaan_id = j.id', 'left')
			->join('tweb_golongan_darah g', 'u.golongan_darah_id = g.id', 'left')
			->join('tweb_penduduk_pendidikan_kk d', 'u.pendidikan_kk_id = d.id', 'left')
			->join('tweb_penduduk_warganegara f', 'u.warganegara_id = f.id', 'left')
			->join('tweb_penduduk_agama a', 'u.agama_id = a.id', 'left')
			->join('tweb_penduduk_kawin w', 'u.status_kawin = w.id', 'left')
			->join('tweb_penduduk_sex x', 'u.sex = x.id', 'left')
			->join('tweb_penduduk_hubungan h', 'u.kk_level = h.id', 'left')
			->join('tweb_wil_clusterdesa c', '('.$id_cluster.') = c.id', 'left')
			->where('u.id = ('.$nik_kepala.')');

			$data = $this->db->get()->row_array();

		if ($data['dusun'] != '-' && $data['dusun'] != '') $data['alamat_plus_dusun'] = trim($data['alamat'].' '.ucwords($this->setting->sebutan_dusun).' '.$data['dusun']);
		elseif ($data['alamat']) $data['alamat_plus_dusun'] = $data['alamat'];
		$data['alamat_wilayah'] = $this->get_alamat_wilayah($data['id_kk']);

		return $data;
	}

	public function get_kepala_a($id)
	{
		$sql = "SELECT u.*, c.*, k.no_kk, k.alamat
			FROM tweb_penduduk u
			LEFT JOIN tweb_keluarga k ON k.id = ?
			LEFT JOIN tweb_wil_clusterdesa c ON u.id_cluster = c.id WHERE u.id = (SELECT nik_kepala FROM tweb_keluarga WHERE id = ?) ";
		$query = $this->db->query($sql,array($id,$id));

		return $query->row_array();
	}

	// TODO: Ganti fuction ini jika sudah tdk lg digunakan di modul lain, gunakan referensi_model
	public function list_hubungan()
	{
		$sql = "SELECT *,nama as hubungan FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	// Tambah anggota keluarga, penduduk baru
	public function insert_a()
	{
		unset($_SESSION['validation_error']);
		$_SESSION['success'] = 1;
		unset($_SESSION['error_msg']);

		$data = $_POST;
		$lokasi_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		if (!empty($lokasi_file))
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg" AND $tipe_file != "image/png")
			{
				unset($data['foto']);
			}
			else
			{
				UploadFoto($nama_file,"",$tipe_file);
				$data['foto'] = $nama_file;
			}
		}
		else
		{
			unset($data['foto']);
		}

		unset($data['file_foto']);
		unset($data['old_foto']);
		unset($data['nik_lama']);

		$satuan = $_POST['tanggallahir'];
		$blnlahir = substr($satuan, 3, 2);
		$thnlahir= substr($satuan, 6, 4);
		$blnskrg = (date("m"));
		$thnskrg = (date("Y"));
		if (($blnlahir == $blnskrg) and ($thnlahir == $thnskrg))
		{
			$id_detail='1';
		}
		else
		{
			$id_detail='5';
		}

		if (!$this->validasi_data_keluarga($data)) return;
		unset($data['alamat']);

		$error_validasi = $this->penduduk_model->validasi_data_penduduk($data);
		if (!empty($error_validasi))
		{
			foreach ($error_validasi as $error)
			{
				$_SESSION['error_msg'] .= ': ' . $error . '\n';
			}
			$_SESSION['post'] = $_POST;
			$_SESSION['success'] = -1;
			return;
		}

		$data['created_by'] = $this->session->user;
		$outp = $this->db->insert('tweb_penduduk', $data);
		if (!$outp) $_SESSION = -1;

		$id_pend = $this->db->insert_id();
		$this->penduduk_model->tulis_log_penduduk($id_pend, $id_detail, $blnskrg, $thnskrg);
	}

	public function get_nokk($id)
	{
		$this->db->select('no_kk');
		$this->db->where('id', $id);
		$q = $this->db->get('tweb_keluarga');
		$kk = $q->row_array();

		return $kk['no_kk'];
	}

	public function update_nokk($id=0)
	{
		unset($_SESSION['error_msg']);
		$data = $_POST;

		if (!$this->validasi_data_keluarga($data)) return;

		// Pindah dusun/rw/rt anggota keluarga kalau berubah
		if ($data['id_cluster'] != $data['id_cluster_lama']){
			$this->keluarga_model->pindah_anggota_keluarga($id, $data['id_cluster']);
		}
		unset($data['dusun']);
		unset($data['rw']);
		unset($data['id_cluster_lama']);

		$id_program = $data['id_program'];
		unset($data['id_program']);

		if (!empty($data['tgl_cetak_kk'])) $data['tgl_cetak_kk'] = date("Y-m-d H:i:s", strtotime($data['tgl_cetak_kk']));
		else $data['tgl_cetak_kk'] = NULL;
		if (empty($data['kelas_sosial'])) $data['kelas_sosial'] = NULL;
		$this->db->where("id", $id);
		$outp=$this->db->update("tweb_keluarga", $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function pindah_keluarga($id_kk, $id_cluster)
	{
		$this->db->where('id', $id_kk)->
			update('tweb_keluarga', array('id_cluster' => $id_cluster));
		$this->pindah_anggota_keluarga($id_kk, $id_cluster);
	}

	private function pindah_anggota_keluarga($id_kk, $id_cluster)
	{
		// Ubah dusun/rw/rt untuk semua anggota keluarga
		if (!empty($id_cluster))
		{
			$this->db->where('id_kk', $id_kk);
			$data['id_cluster'] = $id_cluster;
			$data['updated_at'] = date('Y-m-d H:i:s');
			$data['updated_by'] = $this->session->user;
			$outp = $this->db->update('tweb_penduduk', $data);

			// Tulis log pindah untuk setiap anggota keluarga
			$sql = "SELECT id FROM tweb_penduduk WHERE id_kk = $id_kk";
			$query = $this->db->query($sql);
			$data2 = $query->result_array();
			foreach ($data2 as $datanya)
			{
				$this->penduduk_model->tulis_log_penduduk($datanya[id], '6', date('m'), date('Y'));
			}
		}
	}

	public function get_alamat_wilayah($id_kk)
	{
		$sql = "SELECT a.dusun, a.rw, a.rt, k.alamat
				FROM tweb_keluarga k
				LEFT JOIN tweb_wil_clusterdesa a ON k.id_cluster = a.id
				WHERE k.id = ?";
		$query = $this->db->query($sql, $id_kk);
		$data  = $query->row_array();
		if (!isset($data['alamat'])) $data['alamat'] = '';
		if (!isset($data['rt'])) $data['rt'] = '';
		if (!isset($data['rw'])) $data['rw'] = '';
		$str_dusun = (empty($data['dusun']) or $data['dusun'] == '-') ? '' : ikut_case($data['dusun'], $this->setting->sebutan_dusun." ".$data['dusun']);
		$alamat_wilayah= trim("$data[alamat] RT $data[rt] / RW $data[rw] ".$str_dusun);

		return $alamat_wilayah;
	}

	public function get_judul_statistik($tipe = 0, $nomor = 1, $sex = 0)
	{
		if ($nomor == JUMLAH)
			$judul = array("nama" => "JUMLAH");
		else if ($nomor == BELUM_MENGISI)
			$judul = array("nama" => "BELUM MENGISI");
		else
		{
			switch ($tipe)
			{
				case 'kelas_sosial':
					$sql = "SELECT * FROM tweb_keluarga_sejahtera WHERE id = ? ";
					break;
				case 'bantuan_keluarga':
					$sql = "SELECT * FROM program WHERE id = ? ";
					break;
			}
			$query = $this->db->query($sql, $nomor);
			$judul = $query->row_array();
		}
		if ($sex == 1) $judul['nama'] .= " - LAKI-LAKI";
		elseif ($sex == 2) $judul['nama'] .= " - PEREMPUAN";

		return $judul;
	}

	public function get_data_unduh_kk($id)
	{
		$data = array();
		$data['desa'] = $this->config_model->get_data();
		$data['id_kk'] = $id;
		$data['main'] = $this->list_anggota($id);
		$data['kepala_kk']= $this->get_kepala_kk($id);

		return $data;
	}

	public function unduh_kk($id = 0)
	{
		$id_cb = $_POST['id_cb'];
		if (empty($id) AND count($id_cb) == 1)
		{
			// Aksi borongan dengan satu KK saja
			$id = $id_cb[0];
		}
		if (empty($id))
		{
			// Aksi borongan lebih dari satu KK
			$berkas_kk = array();
			if (count($id_cb))
			{
				foreach ($id_cb as $id)
				{
					$data = $this->get_data_unduh_kk($id);
					$berkas_kk[] = $this->buat_berkas_kk($data);
				}
			}
			# Masukkan semua berkas ke dalam zip
			$berkas_kk = masukkan_zip($berkas_kk);
			# Unduh berkas zip
			header('Content-disposition: attachment; filename=berkas_kk_'.date("d-m-Y").'.zip');
			header('Content-type: application/zip');
			readfile($berkas_kk);
		}
		else
		{
			// Satu kk
			$data = $this->get_data_unduh_kk($id);
			$berkas_kk = $this->buat_berkas_kk($data);
			header("location:".base_url($berkas_kk));
		}
	}

	private function buat_berkas_kk($data = '')
	{
		$mypath="template-surat\\kk\\";

		$path = "".str_replace("\\","/", $mypath);
		$path_arsip = LOKASI_ARSIP;

		$file = $path."kk.rtf";
		if (!is_file($file))
		{
			return;
		}

		$nama = "";

		$handle = fopen($file,'r');
		$buffer = stream_get_contents($handle);
		$i = 0;

		foreach ($data['main'] AS $ranggota)
		{
			$i++;
			$nama .= $ranggota['nama']."\line ";
			$no .= $i."\line ";
			$hubungan .= $ranggota['hubungan']."\line ";
			$nik .= $ranggota['nik']."\line ";
			$sex .= $ranggota['sex']."\line ";
			$tempatlahir .= $ranggota['tempatlahir']."\line ";
			$tanggallahir .= tgl_indo($ranggota['tanggallahir'])."\line ";
			$agama .= $ranggota['agama']."\line ";
			$pendidikan .= $ranggota['pendidikan']."\line ";
			$pekerjaan .= $ranggota['pekerjaan']."\line ";
			$status_kawin .= $ranggota['status_kawin']."\line ";
			$warganegara .= $ranggota['warganegara']."\line ";
			$dokumen_pasport .= $ranggota['dokumen_pasport']."\line ";
			$dokumen_kitas .= $ranggota['dokumen_kitas']."\line ";
			$nama_ayah .= $ranggota['nama_ayah']."\line ";
			$nama_ibu .= $ranggota['nama_ibu']."\line ";
			$golongan_darah .= $ranggota['golongan_darah']."\line ";
			$tanggalperkawinan .= isset($ranggota['tanggalperkawinan']) ? tgl_indo($ranggota['tanggalperkawinan'])."\line " : "- \line ";
			$tanggalperceraian .= isset($ranggota['tanggalperceraian']) ? tgl_indo($ranggota['tanggalperceraian'])."\line " : "- \line ";
		}

		$buffer = str_replace("[no]","$no", $buffer);
		$buffer = str_replace("[nama]","\caps $nama", $buffer);
		$buffer = str_replace("[hubungan]","$hubungan", $buffer);
		$buffer = str_replace("[nik]","$nik", $buffer);
		$buffer = str_replace("[sex]","$sex", $buffer);
		$buffer = str_replace("[agama]","$agama", $buffer);
		$buffer = str_replace("[pendidikan]","$pendidikan", $buffer);
		$buffer = str_replace("[pekerjaan]","$pekerjaan", $buffer);
		$buffer = str_replace("[tempatlahir]","\caps $tempatlahir", $buffer);
		$buffer = str_replace("[tanggallahir]","\caps $tanggallahir", $buffer);
		$buffer = str_replace("[kawin]","$status_kawin", $buffer);
		$buffer = str_replace("[warganegara]","$warganegara", $buffer);
		$buffer = str_replace("[pasport]","$dokumen_pasport", $buffer);
		$buffer = str_replace("[kitas]","$dokumen_kitas", $buffer);
		$buffer = str_replace("[ayah]","\caps $nama_ayah", $buffer);
		$buffer = str_replace("[ibu]","\caps $nama_ibu", $buffer);
		$buffer = str_replace("[darah]","\caps $golongan_darah", $buffer);
		$buffer = str_replace("[tanggalperkawinan]","\caps $tanggalperkawinan", $buffer);
		$buffer = str_replace("[tanggalperceraian]","\caps $tanggalperceraian", $buffer);

		$h = $data['desa'];
		$k = $data['kepala_kk'];
		$tertanda = tgl_indo(date("Y m d"));
		$tertanda = $h['nama_desa'].", ".$tertanda;
		$buffer = str_replace("desa","\caps $h[nama_desa]", $buffer);
		$buffer = str_replace("alamat_plus_dusun","\caps $k[alamat_plus_dusun]", $buffer);
		$buffer = str_replace("prop","\caps $h[nama_propinsi]", $buffer);
		$buffer = str_replace("kab","\caps $h[nama_kabupaten]", $buffer);
		$buffer = str_replace("kec","\caps $h[nama_kecamatan]", $buffer);
		$buffer = str_replace("*camat","\caps $h[nama_kepala_camat]", $buffer);
		$buffer = str_replace("*kades","\caps $h[nama_kepala_desa]", $buffer);
		$buffer = str_replace("*rt","$k[rt]", $buffer);
		$buffer = str_replace("*rw","$k[rw]", $buffer);
		$buffer = str_replace("*kk","\caps $k[nama]", $buffer);
		$buffer = str_replace("no_kk","$k[no_kk]", $buffer);
		$buffer = str_replace("pos","$h[kode_pos]", $buffer);
		$buffer = str_replace("*tertanda","\caps $tertanda", $buffer);
		$buffer = str_replace("*nip_camat","$h[nip_kepala_camat]", $buffer);

		$berkas_arsip = $path_arsip."kk_$k[no_kk].rtf";
		$handle = fopen($berkas_arsip, 'w+');
		fwrite($handle,$buffer);
		fclose($handle);

		return $berkas_arsip;
	}
}
