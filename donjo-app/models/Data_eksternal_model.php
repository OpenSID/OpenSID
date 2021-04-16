<?php
/**
 * File ini:
 *
 * Model untuk modul data eksternal (Garis)
 *
 * /donjo-app/models/Data_eksternal_model.php
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
?>