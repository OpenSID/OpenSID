<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Layanan Mandiri > Beranda
 *
 * donjo-app/controllers/layanan_mandiri/Beranda.php
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

class Beranda extends Mandiri_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['mandiri_model', 'penduduk_model', 'kelompok_model', 'web_dokumen_model', 'pendapat_model']);
		$this->load->helper('download');
	}

	public function index()
	{
		$this->profil();
	}

	public function profil()
	{
		$data = [
			'penduduk' => $this->penduduk_model->get_penduduk($this->is_login->id_pend),
			'kelompok' => $this->penduduk_model->list_kelompok($this->is_login->id_pend),
			'dokumen' => $this->penduduk_model->list_dokumen($this->is_login->id_pend),
		];

		$this->render('profil', $data);
	}

	public function cetak_biodata()
	{
		$data = [
			'desa' => $this->header,
			'penduduk' => $this->penduduk_model->get_penduduk($this->is_login->id_pend),
		];

		$this->load->view('sid/kependudukan/cetak_biodata', $data);
	}

	public function cetak_kk() 
	{
		if ($this->is_login->id_kk == 0)
		{
			// Jika diakses melalui URL
			$respon = [
				'status' => 1,
				'pesan' => 'Anda tidak terdaftar dalam sebuah keluarga'
			];
			$this->session->set_flashdata('notif', $respon);

			redirect('layanan-mandiri');
		}

		$data = $this->keluarga_model->get_data_cetak_kk($this->is_login->id_kk);

		$this->load->view('sid/kependudukan/cetak_kk_all', $data);
	}

	public function ganti_pin()
	{
		$data = [
			'cek_anjungan' => $this->cek_anjungan,
			'form_action' => site_url('layanan-mandiri/proses-ganti-pin'),
		];

		$this->render('ganti_pin', $data);
	}

	public function proses_ganti_pin()
	{
		$data = $this->mandiri_model->ganti_pin();
		redirect('layanan-mandiri/ganti-pin');
	}

	public function keluar()
	{
		$this->mandiri_model->logout();
		redirect('layanan-mandiri');
	}

	/**
	 * Unduh berkas berdasarkan kolom dokumen.id
	 * @param   integer  $id_dokumen  Id berkas pada koloam dokumen.id
	 * @return  void
	 */
	public function unduh_berkas($id_dokumen = '')
	{
		// Ambil nama berkas dari database
		$berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen, $this->is_login->id_pend);
		if ($berkas)
			ambilBerkas($berkas, NULL, NULL, LOKASI_DOKUMEN);
		else
			$this->output->set_status_header('404');
	}

	public function pendapat(int $pilihan = 1)
	{
		$data = [
			'pengguna' => $this->is_login->id_pend,
			'pilihan' => $pilihan
		];

		$this->pendapat_model->insert($data);
		redirect('layanan-mandiri/keluar');
	}

}
