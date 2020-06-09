<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shortcode_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('keuangan_grafik_model');
    $this->load->model('keuangan_grafik_manual_model');
    $this->load->model('laporan_penduduk_model');
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

	private function extract_shortcode($type, $thn)
	{
		if ($type == 'grafik-RP-APBD')
    {
      return $this->grafik_rp_apbd($type, $thn);
    }
    elseif ($type == 'lap-RP-APBD')
    {
      return $this->tabel_rp_apbd($type, $thn);
    }
    elseif ($type == 'lap-RP-APBD-Bidang')
    {
      return $this->tabel_rp_apbd_bidang($type, $thn);
    }
    elseif ($type == 'penerima_bantuan_penduduk_grafik')
    {
      return $this->penerima_bantuan_penduduk_grafik($stat=0, $tipe=0);
    }
    elseif ($type == 'penerima_bantuan_penduduk_daftar')
    {
      return $this->penerima_bantuan_penduduk_daftar($stat=0, $tipe=0);
    }
    elseif ($type == 'penerima_bantuan_keluarga_grafik')
    {
      return $this->penerima_bantuan_keluarga_grafik($stat=0, $tipe=0);
    }
    elseif ($type == 'penerima_bantuan_keluarga_daftar')
    {
      return $this->penerima_bantuan_keluarga_daftar($stat=0, $tipe=0);
    }
    elseif ($type == 'grafik-RP-APBD-manual')
    {
      return $this->grafik_rp_apbd_manual($type, $thn);
    }
    elseif ($type == 'lap-RP-APBD-Bidang-manual')
    {
      return $this->tabel_rp_apbd_bidang_manual($type, $thn);
    }
	}

	private function grafik_rp_apbd($type, $thn)
	{
    $data = $this->keuangan_grafik_model->grafik_keuangan_tema($thn);
		$data_widget = $data['data_widget'];
		ob_start();
			include("donjo-app/views/keuangan/grafik_rp_apbd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function tabel_rp_apbd($type, $thn)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd($thn);
		$pendapatan = $data['pendapatan'];
		$belanja = $data['belanja'];
    $belanja_bidang = $data['belanja_bidang'];
		$pembiayaan = $data['pembiayaan'];
    $pembiayaan_keluar = $data['pembiayaan_keluar'];
		ob_start();
			include("donjo-app/views/keuangan/tabel_laporan_rp_apbd_artikel.php");
		$output = ob_get_clean();
		return $output;
	}

  private function tabel_rp_apbd_bidang($type, $thn)
	{
		$data = $this->keuangan_grafik_model->lap_rp_apbd($thn);
		$pendapatan = $data['pendapatan'];
		$belanja = $data['belanja'];
    $belanja_bidang = $data['belanja_bidang'];
		$pembiayaan = $data['pembiayaan'];
    $pembiayaan_keluar = $data['pembiayaan_keluar'];
    $jenis = 'bidang';
		ob_start();
			include("donjo-app/views/keuangan/tabel_laporan_rp_apbd_artikel.php");
		$output = ob_get_clean();
		return $output;
	}

  private function grafik_rp_apbd_manual($type, $thn)
	{
    $data = $this->keuangan_grafik_manual_model->grafik_keuangan_tema($thn);
		$data_widget = $data['data_widget'];
		ob_start();
			include("donjo-app/views/keuangan/grafik_rp_apbd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

  private function tabel_rp_apbd_bidang_manual($type, $thn)
	{
		$data = $this->keuangan_grafik_manual_model->lap_rp_apbd($thn);
		$pendapatan = $data['pendapatan'];
		$belanja = $data['belanja'];
    $belanja_bidang = $data['belanja_bidang'];
		$pembiayaan = $data['pembiayaan'];
    $pembiayaan_keluar = $data['pembiayaan_keluar'];
    $jenis = 'bidang';
		ob_start();
			include("donjo-app/views/keuangan/tabel_laporan_rp_apbd_artikel.php");
		$output = ob_get_clean();
		return $output;
	}

  private function penerima_bantuan_penduduk_grafik($stat=0, $tipe=0)
	{
    $heading = 'Penerima Bantuan (Penduduk)';
		$jenis_laporan = $this->laporan_penduduk_model->jenis_laporan('bantuan_penduduk');
		$stat = $this->laporan_penduduk_model->list_data('bantuan_penduduk',0);
		$tipe = $tipe;
		$st = $stat;
    $lap = 'bantuan_penduduk';

		ob_start();
			include("donjo-app/views/statistik/penduduk_grafik.php");
		$res = ob_get_clean();
		return $res;
	}

  private function penerima_bantuan_penduduk_daftar($stat=0, $tipe=0)
	{
    $heading = 'Penerima Bantuan (Penduduk)';
		$jenis_laporan = $this->laporan_penduduk_model->jenis_laporan('bantuan_penduduk');
		$stat = $this->laporan_penduduk_model->list_data('bantuan_penduduk',0);
		$tipe = $tipe;
		$st = $stat;
    $lap = 'bantuan_penduduk';

		ob_start();
			include("donjo-app/views/statistik/peserta_bantuan.php");
		$res = ob_get_clean();
		return $res;
	}

  private function penerima_bantuan_keluarga_grafik($stat=0, $tipe=0)
	{
    $heading = 'Penerima Bantuan (Keluarga)';
		$jenis_laporan = $this->laporan_penduduk_model->jenis_laporan('bantuan_keluarga');
		$stat = $this->laporan_penduduk_model->list_data('bantuan_keluarga',0);
		$tipe = $tipe;
		$st = $stat;
    $lap = 'bantuan_keluarga';

		ob_start();
			include("donjo-app/views/statistik/penduduk_grafik.php");
		$res = ob_get_clean();
		return $res;
	}

  private function penerima_bantuan_keluarga_daftar($stat=0, $tipe=0)
	{
    $heading = 'Penerima Bantuan (Keluarga)';
		$jenis_laporan = $this->laporan_penduduk_model->jenis_laporan('bantuan_keluarga');
		$stat = $this->laporan_penduduk_model->list_data('bantuan_keluarga',0);
		$tipe = $tipe;
		$st = $stat;
    $lap = 'bantuan_keluarga';

		ob_start();
			include("donjo-app/views/statistik/peserta_bantuan.php");
		$res = ob_get_clean();
		return $res;
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

	private function converted_sc_list($type, $thn)
	{
		if ($type == "lap-RP-APBD")
		{
			$output = "<i class='fa fa-table'></i> Tabel Laporan APBDes TA. " . $thn . ", ";
			return $output;
		}
    elseif ($type == "lap-RP-APBD-Bidang")
		{
			$output = "<i class='fa fa-table'></i> Tabel Laporan APBDes TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-RP-APBD")
		{
			$output = "<i class='fa fa-bar-chart'></i> Grafik APBDes TA. " . $thn . ", ";
			return $output;
		}
    elseif ($type == "lap-RP-APBD-Bidang-manual")
		{
			$output = "<i class='fa fa-table'></i> Tabel Laporan APBDes TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-RP-APBD-manual")
		{
			$output = "<i class='fa fa-bar-chart'></i> Grafik APBDes TA. " . $thn . ", ";
			return $output;
		}
    elseif ($type == "penerima_bantuan_penduduk_grafik")
		{
			$output = "<i class='fa fa-bar-chart'></i> Penerima Bantuan (Penduduk)";
			return $output;
		}
    elseif ($type == "penerima_bantuan_penduduk_daftar")
		{
			$output = "<i class='fa fa-table'></i> Penerima Bantuan (Penduduk)";
			return $output;
		}
    elseif ($type == "penerima_bantuan_keluarga_grafik")
		{
			$output = "<i class='fa fa-bar-chart'></i> Penerima Bantuan (Keluarga)";
			return $output;
		}
    elseif ($type == "penerima_bantuan_keluarga_daftar")
		{
			$output = "<i class='fa fa-table'></i> Penerima Bantuan (Keluarga)";
			return $output;
		}
	}

}
