<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_eksternal_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function sdgs_kemendes($kode_desa)
	{		
		include FCPATH . '/vendor/simple_html_dom.php';
		$url = "https://sid.kemendesa.go.id/home/sdgs/{$kode_desa}";
		$data 		= [];
		$selector 	= '.accordion-stn';
		$html 		= file_get_html($url);
		if ($html == false) {
			show_error('Reload Kembali Halaman', 408,'Gagal Memuat Halaman');
			return;
		}
		$kiri 		= $html->find($selector,0); //pertama
		foreach ($kiri->find('.panel') as $panel) { //ambil dari panel
			$sub = [];
			foreach ($panel->find('tr') as $tr) {
				$sub  [] = [
					'uraian'	=> $tr->children(0)->innertext,
					'value'		=> $tr->children(1)->innertext
				];
			}
			$data [] = [
				'uraian'	=> trim(preg_replace('/\t+/', '', $panel->find('a',0)->innertext)),
				'sub'		=> $sub
			];
		}

		$kanan 	= $html->find($selector,1); //kedua
		foreach ($kanan->find('.panel') as $panel) { //ambil dari panel
			$sub = [];
			foreach ($panel->find('tr') as $tr) {
				$sub  [] = [
					'uraian'	=> $tr->children(0)->innertext,
					'value'		=> $tr->children(1)->innertext
				];
			}
			$data [] = [
				'uraian'	=> trim(preg_replace('/\t+/', '', $panel->find('a',0)->innertext)),
				'sub'		=> $sub
			];
		}
		return $data;
	}

}

/* End of file Sdgs_model.php */
 