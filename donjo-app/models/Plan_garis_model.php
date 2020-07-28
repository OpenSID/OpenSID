<?php
/**
 * File ini:
 *
 * Model untuk modul Pemetaan (Garis)
 *
 * /donjo-app/models/Plan_garis_model.php
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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

class Plan_garis_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function autocomplete()
	{
		return $this->autocomplete_str('nama', 'garis');
	}

	private function search_sql()
	{
		if (isset($_SESSION['cari']))
		{
			$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql = " AND l.nama LIKE '$kw'";
			return $search_sql;
		}
	}

	private function filter_sql()
	{
		if (isset($_SESSION['filter']))
		{
			$kf = $_SESSION['filter'];
			$filter_sql = " AND l.enabled = $kf";
			return $filter_sql;
		}
	}

	private function line_sql()
	{
		if ($kf = $this->session->line)
		{
			$line_sql = " AND m.id = $kf";
			return $line_sql;
		}
	}

	private function subline_sql()
	{
		if ($kf = $this->session->subline)
		{
			$subline_sql = " AND p.id = $kf";
			return $subline_sql;
		}
	}

	public function paging($p=1, $o=0)
	{
		$sql = "SELECT COUNT(l.id) AS jml " . $this->list_data_sql();
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

	// Pastikan paging dan pencarian data berdasarkan filter yg sama
	private function list_data_sql()
	{
		$sql = "FROM garis l
			LEFT JOIN line p ON l.ref_line = p.id
			LEFT JOIN line m ON p.parrent = m.id
			WHERE 1 ";
		$sql .= $this->search_sql();
		$sql .= $this->filter_sql();
		$sql .= $this->line_sql();
		$sql .= $this->subline_sql();
		return $sql;
	}

	public function list_data($o=0,$offset=0, $limit=1000)
	{
		switch ($o)
		{
			case 1: $order_sql = ' ORDER BY nama'; break;
			case 2: $order_sql = ' ORDER BY nama DESC'; break;
			case 3: $order_sql = ' ORDER BY enabled'; break;
			case 4: $order_sql = ' ORDER BY enabled DESC'; break;
			default:$order_sql = ' ORDER BY id';
		}

		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$select_sql = "SELECT l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol, p.color AS color ";
		$sql = $select_sql . $this->list_data_sql();

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
				$data[$i]['aktif'] = "Tidak";

			$j++;
		}
		return $data;
	}

	private function validasi($post)
	{
		$data['nama'] = nomor_surat_keputusan($post['nama']);
		$data['ref_line'] = $post['ref_line'];
		$data['desk'] = htmlentities($post['desk']);
		$data['enabled'] = $post['enabled'];
		return $data;
	}

	public function insert()
	{
		$data = $this->validasi($this->input->post());
		$garis_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		if (!empty($garis_file))
		{
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg")
			{
				Uploadgaris($nama_file);
				$data['foto'] = $nama_file;
				$outp = $this->db->insert('garis', $data);
			}
		}
		else
		{
			unset($data['foto']);
			$outp = $this->db->insert('garis', $data);
		}
		status_sukses($outp); //Tampilkan Pesan
	}

	public function update($id=0)
	{
		$data = $this->validasi($this->input->post());
		$garis_file = $_FILES['foto']['tmp_name'];
		$tipe_file = $_FILES['foto']['type'];
		$nama_file = $_FILES['foto']['name'];
		$nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
		if (!empty($garis_file))
		{
			if ($tipe_file == "image/jpg" OR $tipe_file == "image/jpeg")
			{
				Uploadgaris($nama_file);
				$data['foto'] = $nama_file;
				$this->db->where('id', $id);
				$outp = $this->db->update('garis', $data);
			}
		}
		else
		{
			unset($data['foto']);
			$this->db->where('id', $id);
			$outp = $this->db->update('garis', $data);
		}
		status_sukses($outp); //Tampilkan Pesan
	}

	public function delete($id='', $semua=false)
	{
		if (!$semua) $this->session->success = 1;

		$outp = $this->db->where('id', $id)->delete('garis');

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

	public function list_line()
	{
		$sql = "SELECT * FROM line WHERE tipe = 0 ";

		if (isset($_SESSION['subline']))
		{
			$kf = $_SESSION['subline'];
			$sql .= " AND parrent = $kf";
		}

		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_subline()
	{
		$sql = "SELECT * FROM line WHERE tipe = 2 ";

		if (isset($_SESSION['line']))
		{
			$sqlx = "SELECT * FROM line WHERE id = ?";
			$query = $this->db->query($sqlx, $_SESSION['line']);
			$temp = $query->row_array();
			$kf = $temp['parrent'];
		}

		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function garis_lock($id='', $val=0)
	{
		$sql = "UPDATE garis SET enabled = ? WHERE id = ?";
		$outp = $this->db->query($sql, array($val, $id));

		status_sukses($outp); //Tampilkan Pesan
	}

	public function get_garis($id=0)
	{
		$sql = "SELECT * FROM garis WHERE id = ?";
		$query = $this->db->query($sql, $id);
		$data = $query->row_array();
		return $data;
	}

	public function update_position($id=0)
	{
		$data = $_POST;
		$this->db->where('id', $id);
		$outp = $this->db->update('garis', $data);

		status_sukses($outp); //Tampilkan Pesan
	}

	public function list_dusun()
	{
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function list_garis()
	{
		$data = $this->db
			->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol, p.color AS color')
			->from('garis l')
			->join('line p', 'l.ref_line = p.id', 'left')
			->join('line m', ' p.parrent = m.id')
			->where('l.enabled', 1)
			->where('p.enabled', 1)
			->where('m.enabled', 1)
			->get()->result_array();
		return $data;
	}

}
?>
