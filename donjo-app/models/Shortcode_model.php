<?php
// https://stackoverflow.com/questions/28001003/extends-model-in-codeigniter
require APPPATH.'/models/Keuangan_grafik_model.php';
class Shortcode_model extends Keuangan_grafik_model {

  public function __construct()
  {
    parent::__construct();
  }


	/**
	 * Menghasilkan grafik & tabel dari database
	 * konversi isi artikel yang berisi kode_tampilan
	 */

	// Shortcode untuk isi artikel
	public function shortcode($str = '')
	{
		$regex = "/\[\[(.*?)\]\]/";
		return preg_replace_callback($regex, function ($matches) {
			$result = array();

			$params_explode = explode(",", $matches[1]);
			$fnName = 'extract_shortcode';
			return $this->extract_shortcode($params_explode[0], $params_explode[1], $params_explode[2]);
		}, $str);
	}

	private function extract_shortcode($type, $smt, $thn)
	{
		if ($type == 'grafik-RP-APBD')
		{
			return $this->grafik_rp_apbd($type, $smt, $thn);
		}
		elseif ($type == 'grafik-R-PD')
		{
			return $this->grafik_r_pd($type, $smt, $thn);
		}
		elseif ($type == 'grafik-R-BD')
		{
			return $this->grafik_r_bd($type, $smt, $thn);
		}
		elseif($type == 'grafik-R-PEMDES')
		{
			return $this->grafik_r_pemdes($type, $smt, $thn);
		}
		elseif($type == 'lap-RP-APBD')
		{
			return $this->tabel_rp_apbd($type, $smt, $thn);
		}
	}

	private function grafik_rp_apbd($type, $smt, $thn)
	{
		$data = $this->rp_apbd($smt, $thn);
		$jenisbelanja = array();
		foreach ($data['jenis_belanja'] as $j)
		{
			$jenisbelanja[] = "'". $j['Nama_Akun']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $p)
		{
			$anggaran[] = $p['AnggaranStlhPAK'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $s)
		{
			if(!empty($s['Nilai']) || !is_null($s['Nilai']))
			{
				$realisasi[] =  $s['Nilai'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		ob_start();
			include("donjo-app/views/keuangan/grafik_rp_apbd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function grafik_r_pd($type, $smt, $thn)
	{
		$data = $this->r_pd($smt, $thn);
		$jp = array();
		foreach ($data['jenis_pendapatan'] as $b)
		{
			$jp[] = "'". $b['Nama_Jenis']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
			$anggaran[] = $a['Pagu'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['Nilai']) || !is_null($r['Nilai']))
			{
				$realisasi[] =  $r['Nilai'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		ob_start();
			include("donjo-app/views/keuangan/grafik_r_pd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function grafik_r_bd($type, $smt, $thn)
	{
		$data = $this->r_bd($smt, $thn);
		$bidang = array();
		foreach ($data['bidang'] as $b)
		{
			$bidang[] = "'". $b['Nama_Bidang']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
			$anggaran[] = $a['Pagu'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['Nilai']) || !is_null($r['Nilai']))
			{
				$realisasi[] =  $r['Nilai'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		ob_start();
			include("donjo-app/views/keuangan/grafik_r_bd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function grafik_r_pemdes($type, $smt, $thn)
	{
		$data = $this->r_pembiayaan($smt, $thn);
		$pembiayaan = array();
		foreach ($data['pembiayaan'] as $d)
		{
			$pembiayaan[] = "'". $d['Nama_Kelompok']. "'";
		}
		$anggaran = array();
		foreach ($data['anggaran'] as $a)
		{
			$anggaran[] = $a['Pagu'];
		}
		$realisasi = array();
		foreach ($data['realisasi'] as $r)
		{
			if(!empty($r['Nilai']) || !is_null($r['Nilai']))
			{
				$realisasi[] =  $r['Nilai'];
			}
			else
			{
				$realisasi[] =  0;
			}
		}
		ob_start();
			include("donjo-app/views/keuangan/grafik_r_pemdes_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function tabel_rp_apbd($type, $smt, $thn)
	{
		$data = $this->lap_rp_apbd($smt, $thn);
		$pendapatan = $data['pendapatan'];
		$belanja = $data['belanja'];
		$pembiayaan = $data['pembiayaan'];
		ob_start();
			include("donjo-app/views/keuangan/tabel_laporan_rp_apbd.php");
		$output = ob_get_clean();
		return $output;
	}

	// Shortcode untuk list artikel
	public function convert_sc_list($str = '')
	{
		$regex = "/\[\[(.*?)\]\]/";
		return preg_replace_callback($regex, function ($matches) {
			$result = array();

			$params_explode = explode(",", $matches[1]);
			$fnName = 'converted_sc_list';
			return $this->converted_sc_list($params_explode[0], $params_explode[1], $params_explode[2]);
		}, $str);
	}

	private function converted_sc_list($type, $smt, $thn)
	{
		if ($type == "lap-RP-APBD")
		{
			$output = "<i class='fa fa-table'></i> Tabel Laporan Realisasi Pelaksanaan APBDes Smt " . $smt . " TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-RP-APBD")
		{
			$output = "<i class='fa fa-bar-chart'></i> Realisasi Pelaksanaan APBDes Smt " . $smt . " TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-R-PD")
		{
			$output = "<i class='fa fa-bar-chart'></i> Realisasi Pendapatan Desa Smt " . $smt . " TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-R-BD")
		{
			$output = "<i class='fa fa-bar-chart'></i> Realisasi Belanja Bidang Desa Smt " . $smt . " TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-R-PEMDES")
		{
			$output = "<i class='fa fa-bar-chart'></i> Realisasi Pembiayaan Desa Smt " . $smt . " TA. " . $thn . ", ";
			return $output;
		}
	}

}