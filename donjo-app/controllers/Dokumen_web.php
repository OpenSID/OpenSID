<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  File ini:
 *
 * Controller untuk modul Dokumen
 *
 * donjo-app/controllers/Dokumen_web.php
 *
 */

/*
 *  File ini bagian dari:
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

class Dokumen_web extends Web_Controller
{
	
  /**
	 * Unduh berkas berdasarkan kolom dokumen.id
	 * @param   integer  $id_dokumen  Id berkas pada koloam dokumen.id
	 * @return  void
	 */
	public function unduh_berkas($id_dokumen)
	{
		$this->load->model('web_dokumen_model');

		// Ambil nama berkas dari database
		$berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen);
		ambilBerkas($berkas, null, null, LOKASI_DOKUMEN);
	}

	public function check_surat1($id_dokumen)
	{
		$this->load->model('url_shortener_model');

		$seed = time();
		$id_encoded = $this->url_shortener_model->encode_id($id_dokumen, $seed);
		$id_decoded = $this->url_shortener_model->decode_id($id_encoded, $seed);

		redirect('c2/'. $id_decoded);
	}

	public function check_surat2($id_decoded)
	{
		$this->load->model(['config_model', 'keluar_model']);

		$data['config'] = $this->config_model->get_data();
		$data['surat'] = $this->keluar_model->verifikasi_data_surat($id_decoded, $data['config']['kode_desa']);

		if (! $data['surat']) show_404();
		
		$this->load->view('surat/verifikasi_surat', $data);
	}
}
