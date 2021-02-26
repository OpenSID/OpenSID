<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Layanan Mandiri
 *
 * donjo-app/controllers/Mailbox_web.php
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

// class Mailbox_web extends Mandiri_Controller
// {
// 	public function __construct()
// 	{
// 		parent::__construct();
// 		$this->load->model('mailbox_model');
// 		$this->load->model('mandiri_model');
// 		$this->load->model('config_model');
class Mailbox_web extends Web_Controller {

	private $cek_anjungan;

	public function __construct()
	{
		parent::__construct();
		if ( ! isset($_SESSION['mandiri'])) {
			redirect('first');
		}
		else
		{
			$this->load->model(['config_model', 'mailbox_model', 'mandiri_model', 'anjungan_model']);
			$this->cek_anjungan = $this->anjungan_model->cek_anjungan();
		}
	}

	public function index()
	{
		redirect('mandiri_web/mandiri/1/3');
	}

	public function form()
	{
		if ( ! empty($subjek = $this->input->post('subjek'))) {
			$data['subjek'] = $subjek;
		}
		$data['desa'] = $this->config_model->get_data();
		$data['individu'] = $this->mandiri_model->get_mandiri($this->session->nik, true);
		$data['form_action'] = site_url("mailbox_web/kirim_pesan");
		$data['views_partial_layout'] = "web/mandiri/mailbox_form";
		$data['cek_anjungan'] = $this->cek_anjungan;

		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	// TODO: pisahkan mailbox dari komentar
	public function kirim_pesan()
	{
		$post = $this->input->post();
		$individu = $this->mandiri_model->get_mandiri($this->session->nik, true);
		$post['email'] = $individu['nik']; // kolom email diisi nik untuk pesan
		$post['owner'] = $individu['nama'];
		$post['tipe'] = 1;
		$post['status'] = 2;
		$this->mailbox_model->insert($post);
		redirect('mandiri_web/mandiri/1/3/2');
	}

	public function baca_pesan($kat = 1, $id)
	{
		$nik = $this->session->userdata('nik');
		if ($kat == 1) {
			$this->mailbox_model->ubah_status_pesan($nik, $id, 1);
		}

		$data['kat'] = $kat;
		$data['owner'] = $kat == 1 ? 'Penerima' : 'Pengirim';
		$data['pesan'] = $this->mailbox_model->get_pesan($nik, $id);
		$data['tipe_mailbox'] = $this->mailbox_model->get_kat_nama($kat);
		$data['views_partial_layout'] = "web/mandiri/mailbox_detail";
		$data['cek_anjungan'] = $this->cek_anjungan;

		$this->load->view('web/mandiri/layout.mandiri.php', $data);
	}

	public function pesan_read($id = '')
	{
		$nik = $this->session->userdata('nik');
		$this->mailbox_model->ubah_status_pesan($nik, $id, 1);
		redirect("mandiri_web/mandiri/1/3");
	}

	public function pesan_unread($id = '')
	{
		$nik = $this->session->userdata('nik');
		$this->mailbox_model->ubah_status_pesan($nik, $id, 2);
		redirect("mandiri_web/mandiri/1/3");
	}
}
