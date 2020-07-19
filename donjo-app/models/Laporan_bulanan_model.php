<?php class Laporan_bulanan_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->tulis_log_bulanan();
	}

	public function tulis_log_bulanan()
	{
		// Jangan tulis kalau database belum dimigrasi
		if (!$this->db->field_exists('wni_lk', 'log_bulanan')) return;

		// Jangan hitung keluarga yang tidak ada Kepala Keluarga
		// Anggap warganegara_id = 0, 1 atau 3 adalah WNI
		$sql = "SELECT
			(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1) AS pend,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =1 AND warganegara_id <> 2) AS wni_lk,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =2 AND warganegara_id <> 2) AS wni_pr,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =1 AND warganegara_id = 2) AS wna_lk,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE status_dasar =1 AND sex =2 AND warganegara_id = 2) AS wna_pr,
			(SELECT COUNT(p.id) FROM tweb_keluarga k
				LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id
				WHERE p.status_dasar = 1
					AND k.nik_kepala IS NOT NULL AND k.nik_kepala <> 0) AS kk,
			(SELECT COUNT(k.id) FROM tweb_keluarga k LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id
				WHERE p.sex = 1 AND p.status_dasar = 1) AS kk_lk,
			(SELECT COUNT(k.id) FROM tweb_keluarga k LEFT JOIN tweb_penduduk p ON k.nik_kepala = p.id
				WHERE p.sex = 2  AND p.status_dasar = 1) AS kk_pr";
		$query = $this->db->query($sql);
		$data = $query->row_array();

		$bln = date("m");
		$thn = date("Y");

		$sql = "SELECT * FROM log_bulanan WHERE month(tgl) = $bln AND year(tgl) = $thn";
		$query = $this->db->query($sql);
		$ada = $query->result_array();

		if (!$ada)
		{
			$this->db->insert('log_bulanan', $data);
		}
		else
		{
			$this->db
					->where("month(tgl) = $bln AND year(tgl) = $thn")
					->update('log_bulanan', $data);
		}
	}

	private function bulan_sql()
	{
		$bulan = $_SESSION['bulanku'];
		if ( ! empty($bulan))
		{
			return " AND cast(bulan as signed) = '".$bulan."'";
		}
	}

	private function tahun_sql()
	{
		$tahun = $_SESSION['tahunku'];
		if ( ! empty($tahun))
		{
			return " AND tahun = '".$tahun."'";
		}
	}

	public function bulan($bulan)
	{
		switch ($bulan)
		{
	    case 1 : $bulan = "Januari"; break;
	    case 2 : $bulan = "Februari"; break;
	    case 3 : $bulan = "Maret"; break;
	    case 4 : $bulan = "April"; break;
	    case 5 : $bulan = "Mei"; break;
	    case 6 : $bulan = "Juni"; break;
	    case 7 : $bulan = "Juli"; break;
	    case 8 : $bulan = "Agustus"; break;
	    case 9 : $bulan = "September"; break;
	    case 10 : $bulan = "Oktober"; break;
	    case 11 : $bulan = "November"; break;
	    case 12 : $bulan = "Desember"; break;
    }
		return $bulan;
	}

	private function dusun_sql()
	{
		$dusun = $_SESSION['dusun'];
		if ( ! empty($dusun))
		{
			return " AND dusun = '" .$dusun. "'";
		}
	}

	public function list_data()
	{
		$sql = "select c.id as id_cluster,c.rt,c.rw,c.dusun as dusunnya,
			(select count(id) from penduduk_hidup where sex='1' and id_cluster=c.id) as L,
			(select count(id) from penduduk_hidup where sex='2' and id_cluster=c.id) as P,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<1 and id_cluster=c.id ) as bayi,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=1 and (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=5  and id_cluster=c.id ) as balita,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=6 and (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=12  and id_cluster=c.id ) as sd,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=13 and (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=15  and id_cluster=c.id ) as smp,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>=16 and (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)<=18  and id_cluster=c.id ) as sma,
			(select count(id) from penduduk_hidup where (DATE_FORMAT( FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS( tanggallahir ) ) , '%Y' ) +0)>60 and id_cluster=c.id ) as lansia,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id in (1,2,3,4,5,6)) as cacat,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='1') as cacat_fisik,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='2') as cacat_netra,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='3') as cacat_rungu,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='4') as cacat_mental,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='5') as cacat_fisik_mental,
			(select count(id) from penduduk_hidup where id_cluster=c.id and cacat_id='6') as cacat_lainnya,
			(select count(id) from penduduk_hidup where id_cluster=c.id and (cacat_id IS NULL OR cacat_id='7')) as tidak_cacat,
			(select count(id) from penduduk_hidup where sakit_menahun_id is not null and sakit_menahun_id <>'0' and sakit_menahun_id <>'14' and id_cluster=c.id and sex='1') as sakit_L,
			(select count(id) from penduduk_hidup where sakit_menahun_id is not null and sakit_menahun_id <>'0' and sakit_menahun_id <>'14' and id_cluster=c.id and sex='2') as sakit_P,
			(select count(id) from penduduk_hidup where hamil='1' and id_cluster=c.id) as hamil
			from  tweb_wil_clusterdesa c WHERE rw<>'0' AND rt<>'0' AND (select count(id) from tweb_penduduk where id_cluster=c.id)>0 ";

		$sql .= $this->dusun_sql();
		$sql .= " ORDER BY c.dusun,c.rw,c.rt ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		//	$data = null;
		//Formating Output
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['no'] = $i + 1;
			$data[$i]['tabel'] = $data[$i]['rt'];
		}
		return $data;
	}

  public function list_dusun()
  {
		$sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt = '0' AND rw = '0' ";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}

	public function penduduk_awal()
	{
		$bln = $this->db->escape($_SESSION['bulanku']-1);
		$thn = $this->db->escape($_SESSION['tahunku']);

		$sql = "SELECT wni_lk as WNI_L, wni_pr AS WNI_P, wna_lk as WNA_L, wna_pr AS WNA_P, kk_lk AS KK_L, kk_pr AS KK_P, kk AS KK
			FROM log_bulanan
			WHERE month(tgl) = $bln AND year(tgl) = $thn;";
		$query = $this->db->query($sql);
		if ($query)
		{
			if ($query->num_rows() > 0)
			{
				$hasil=$query->row();
				$data= array(
				"WNI_L" => $hasil->WNI_L,
				"WNI_P" => $hasil->WNI_P,
				"WNA_L" => $hasil->WNA_L,
				"WNA_P" => $hasil->WNA_P,
				"KK_L"  => $hasil->KK_L,
				"KK_P"  => $hasil->KK_P,
				"KK"	  => $hasil->KK,
				"bulan" => $bln,
				"tahun" => $thn);
			}
			else
			{
				$data = array(
				"WNI_L" => 0,
				"WNI_P" => 0,
				"WNA_L" => 0,
				"WNA_P" => 0,
				"KK_L"  => 0,
				"KK_P"  => 0,
				"KK"    => 0,
				"bulan" => $bln,
				"tahun" => $thn);
			}
		}
		else
		{
			$data = $this->db->error_reporting();
		}
		return $data;
	}

	function penduduk_akhir()
	{
		$bln = $_SESSION['bulanku'];
		$thn = $_SESSION['tahunku'];

		$sql = "SELECT wni_lk as WNI_L, wni_pr AS WNI_P, wna_lk as WNA_L, wna_pr AS WNA_P, kk_lk AS KK_L, kk_pr AS KK_P, kk AS KK
			FROM log_bulanan
			WHERE month(tgl) = $bln AND year(tgl) = $thn;";
		$query = $this->db->query($sql);
		$hasil = $query->row_array();
		$data = array(
		"WNI_L" => $hasil["WNI_L"],
		"WNI_P" => $hasil["WNI_P"],
		"WNA_L" => $hasil["WNA_L"],
		"WNA_P" => $hasil["WNA_P"],
		"KK_L"  => $hasil["KK_L"],
		"KK_P"  => $hasil["KK_P"],
		"KK"	  => $hasil["KK"],
		"bulan" => $bln,
		"tahun" => $thn);
		return $data;
	}

	/**
		Kelahiran penduduk berdasarkan tanggal lahir penduduk.
		Keluarga baru berdasarkan tgl_peristiwa di log_keluarga. Log keluarga mencatat keluarga baru pada:
		(1) tambah keluarga dari penduduk lepas
		(2) tambah keluarga baru
	*/
	public function kelahiran()
	{
		$bln = $_SESSION['bulanku'];
		$thn = $_SESSION['tahunku'];

		$sql = "SELECT
			(SELECT COUNT(id) FROM tweb_penduduk WHERE month(tanggallahir) = $bln AND year(tanggallahir) = $thn AND sex = 1 AND warganegara_id <> 2) AS WNI_L,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE month(tanggallahir) = $bln AND year(tanggallahir) = $thn AND sex = 2 AND warganegara_id <> 2) AS WNI_P,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE month(tanggallahir) = $bln AND year(tanggallahir) = $thn AND sex = 1 AND warganegara_id = 2) AS WNA_L,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE month(tanggallahir) = $bln AND year(tanggallahir) = $thn AND sex = 2 AND warganegara_id = 2) AS WNA_P,
			(SELECT COUNT(id) FROM log_keluarga WHERE id_peristiwa = 1 AND month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn) AS KK,
			(SELECT COUNT(id) FROM log_keluarga WHERE id_peristiwa = 1 AND month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND kk_sex = 1) AS KK_L,
			(SELECT COUNT(id) FROM log_keluarga k WHERE id_peristiwa = 1 AND month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND kk_sex = 2) AS KK_P
			";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	/* KETERANGAN id_detail
	   1 = status hidup, insert penduduk baru lahir
	   2 = status menjadi mati
		 3 = status menjadi pindah
		 4 = status menjadi hilang
		 5 = insert penduduk baru dengan status tetap/tidak tetap
		 6 = pindah dalam desa
		 7 = hapus anggota keluarga
		 8 = insert penduduk baru dengan status pendatang
		 9 = tambah keluarga baru dari penduduk yang sudah ada
	*/

	public function kematian()
	{
		$sql = "SELECT
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND sex = 1 AND id_detail = 2 AND warganegara_id <> 2) AS WNI_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND sex = 2 AND id_detail = 2 AND warganegara_id <> 2) AS WNI_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND sex = 1 AND id_detail = 2 AND warganegara_id = 2) AS WNA_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND sex = 2 AND id_detail = 2 AND warganegara_id = 2) AS WNA_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND p.kk_level = 1 AND id_detail = 2) AS KK,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND p.kk_level = 1 AND sex = 1 AND id_detail = 2) AS KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = ? AND year(tgl_peristiwa) =? AND p.kk_level = 1 AND sex = 2 AND id_detail = 2) AS KK_P";
		$query = $this->db->query($sql, array(
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku'],
			$_SESSION['bulanku'], $_SESSION['tahunku']));
		$data=$query->row_array();
		return $data;
	}

	public function pindah()
	{
		$bln = $this->db->escape($_SESSION['bulanku']);
		$thn = $this->db->escape($_SESSION['tahunku']);
		$sql = "SELECT
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND warganegara_id <> 2) AS WNI_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND warganegara_id <> 2) AS WNI_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND warganegara_id = 2) AS WNA_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND warganegara_id = 2) AS WNA_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND id_detail = 3) AS KK,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 3) AS KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 3) AS KK_P
			";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}

	public function rincian_pindah()
	{
		$bln = $this->db->escape($_SESSION['bulanku']);
		$thn = $this->db->escape($_SESSION['tahunku']);
		$sql = "SELECT
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND ref_pindah = 1) AS DESA_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND ref_pindah = 1) AS DESA_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 3 AND ref_pindah = 1) AS DESA_KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 3 AND ref_pindah = 1) AS DESA_KK_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND ref_pindah = 2) AS KEC_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND ref_pindah = 2) AS KEC_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 3 AND ref_pindah = 2) AS KEC_KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 3 AND ref_pindah = 2) AS KEC_KK_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND ref_pindah = 3) AS KAB_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND ref_pindah = 3) AS KAB_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 3 AND ref_pindah = 3) AS KAB_KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 3 AND ref_pindah = 3) AS KAB_KK_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 3 AND ref_pindah = 4) AS PROV_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 3 AND ref_pindah = 4) AS PROV_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 3 AND ref_pindah = 4) AS PROV_KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 3 AND ref_pindah = 4) AS PROV_KK_P
			";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		$data['TOTAL_L'] = $data['DESA_L'] + $data['KEC_L'] + $data['KAB_L'] + $data['PROV_L'];
		$data['TOTAL_P'] = $data['DESA_P'] + $data['KEC_P'] + $data['KAB_P'] + $data['PROV_P'];
		$data['TOTAL_KK_L'] = $data['DESA_KK_L'] + $data['KEC_KK_L'] + $data['KAB_KK_L'] + $data['PROV_KK_L'];
		$data['TOTAL_KK_P'] = $data['DESA_KK_P'] + $data['KEC_KK_P'] + $data['KAB_KK_P'] + $data['PROV_KK_P'];
		return $data;
	}

	public function pendatang()
	{
		$bln = $_SESSION['bulanku'];
		$thn = $_SESSION['tahunku'];

		$paging_sql = ' LIMIT 1';
		$sql = "SELECT
		(select count(s.id) from log_penduduk s INNER join tweb_penduduk p on s.id_pend=p.id and warganegara_id<>'2' and sex='1' and id_detail in ('8','5') and month(tanggal)=month(curdate()) and year(tanggal)=year(curdate()) ) as WNI_L,
		(select count(s.id) from log_penduduk s INNER join tweb_penduduk p on s.id_pend=p.id and warganegara_id<>'2' and sex='2' and id_detail in ('8','5') and month(tanggal)=month(curdate()) and year(tanggal)=year(curdate()) ) as WNI_P,
		(select count(s.id) from log_penduduk s INNER join tweb_penduduk p on s.id_pend=p.id and warganegara_id='2' and sex='1' and id_detail in ('8','5') and month(tanggal)=month(curdate()) and year(tanggal)=year(curdate()) ) as WNA_L,
		(select count(s.id) from log_penduduk s INNER join tweb_penduduk p on s.id_pend=p.id and warganegara_id='2' and sex='2' and id_detail in ('8','5') and month(tanggal)=month(curdate()) and year(tanggal)=year(curdate()) ) as WNA_P,
		bulan, tahun
		FROM log_penduduk
		WHERE 1 ";
		$sql .= $this->bulan_sql();
		$sql .= $this->tahun_sql();
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			$data=$query->row_array();
		}
		else
		{
			$data = array(
			"WNI_L" => 0,
			"WNI_P" => 0,
			"WNA_L" => 0,
			"WNA_P" => 0,
			"bulan" => $bln,
			"tahun" => $thn);
		}
		return $data;
	}

	public function hilang()
	{
		$bln = $this->db->escape($_SESSION['bulanku']);
		$thn = $this->db->escape($_SESSION['tahunku']);
		$sql = "SELECT
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 4 AND warganegara_id <> 2) AS WNI_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 4 AND warganegara_id <> 2) AS WNI_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex =1 AND id_detail = 4 AND warganegara_id = 2) AS WNA_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND sex = 2 AND id_detail = 4 AND warganegara_id = 2) AS WNA_P,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND id_detail = 4) AS KK,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 1 AND id_detail = 4) AS KK_L,
			(SELECT COUNT(u.id) FROM log_penduduk u LEFT JOIN tweb_penduduk p ON u.id_pend = p.id WHERE month(tgl_peristiwa) = $bln AND year(tgl_peristiwa) = $thn AND p.kk_level = 1 AND sex = 2 AND id_detail = 4) AS KK_P
			";
		$query = $this->db->query($sql);
		$data =$query->row_array();
		return $data;
	}

}

?>
