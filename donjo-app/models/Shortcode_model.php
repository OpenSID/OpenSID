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

	private function extract_shortcode($type, $thn)
	{
		if ($type == 'grafik-RP-APBD')
		{
			return $this->grafik_rp_apbd($type, $thn);
		}
		elseif($type == 'lap-RP-APBD')
		{
			return $this->tabel_rp_apbd($type, $thn);
		}
	}

	private function grafik_rp_apbd($type, $thn)
	{
    $data = $this->grafik_keuangan_tema();
		$data_widget = $data['data_widget'];
		ob_start();
			include("donjo-app/views/keuangan/grafik_rp_apbd_chart.php");
		$res = ob_get_clean();
		return $res;
	}

	private function tabel_rp_apbd($type, $thn)
	{
		$data = $this->lap_rp_apbd($thn);
		$pendapatan = $data['pendapatan'];
		$belanja = $data['belanja'];
		$pembiayaan = $data['pembiayaan'];
    $pembiayaan_keluar = $data['pembiayaan_keluar'];
		ob_start();
			include("donjo-app/views/keuangan/tabel_laporan_rp_apbd_artikel.php");
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

	private function converted_sc_list($type, $thn)
	{
		if ($type == "lap-RP-APBD")
		{
			$output = "<i class='fa fa-table'></i> Tabel Laporan Realisasi Pelaksanaan APBDes TA. " . $thn . ", ";
			return $output;
		}
		elseif ($type == "grafik-RP-APBD")
		{
			$output = "<i class='fa fa-bar-chart'></i> Grafik Realisasi Pelaksanaan APBDes TA. " . $thn . ", ";
			return $output;
		}
	}

}
