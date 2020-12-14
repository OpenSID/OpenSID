<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Pengaturan > Aplikasi
 *
 * donjo-app/controllers/Setting.php
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

class Setting extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['config_model','theme_model', 'notif_model']);

		$this->modul_ini = 11;
		$this->sub_modul_ini = 43;
	}

	public function index()
	{
		$data['list_tema'] = $this->theme_model->list_all();
		$data['judul'] = 'Pengaturan Aplikasi';
		$data['list_setting'] = 'list_setting';
		$this->setting_model->load_options();

		$this->render('setting/setting_form', $data);
	}

	public function update()
	{
		$this->setting_model->update_setting($this->input->post());
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function aktifkan_tracking()
	{
		if ($this->input->post('notifikasi') != 1) return; // Hanya bila dipanggil dari form pengumuman
		$this->setting_model->aktifkan_tracking();
		$this->db->where('kode', 'tracking_off')->update('notifikasi', ['aktif' => 0]);
	}

	public function info_sistem()
	{
		$this->sub_modul_ini = 46;

		$data['ekstensi'] = $this->setting_model->cek_ekstensi();
		$data['php'] = $this->setting_model->cek_php();
		$data['mysql'] = $this->setting_model->cek_mysql();
		$this->render('setting/info_php', $data);
	}

	/* Pengaturan web */
	public function web()
	{
		$this->modul_ini = 13;
		$this->sub_modul_ini = 211;

		$data['judul'] = 'Pengaturan Halaman Web';
		$data['list_setting'] = 'list_setting_web';
		//$this->setting_model->load_options(); // Digunakan apabila jenis = option-value atau option-kode

		$this->render('setting/setting_form', $data);
	}

	/* Pengaturan mandiri */
	public function mandiri()
	{
		$this->modul_ini = 14;
		$this->sub_modul_ini = 314;

		$data['judul'] = 'Pengaturan Layanan Mandiri';
		$data['list_setting'] = 'list_setting_mandiri';
		//$this->setting_model->load_options(); // Digunakan apabila jenis = option-value atau option-kode

		$this->render('setting/setting_form', $data);
	}

	public function qrcode($aksi = '', $file = '')
	{
		switch ($aksi)
		{
			case 'clear':
				$this->session->unset_userdata('qrcode');
				redirect('setting/qrcode');

			case 'hapus':
				unlink(LOKASI_MEDIA.''.$file.'.png');
				redirect('setting/qrcode/clear');

			case 'unduh':
				$this->load->helper('download');
				force_download(LOKASI_MEDIA.$file.'.png', NULL);
				redirect('setting/qrcode');

			default:
				$this->modul_ini = 11;
				$this->sub_modul_ini = 212;

				$data['qrcode'] = $this->session->qrcode ?: $qrcode = ['changeqr' => '1', 'sizeqr' => '6', 'backqr' => '#ffffff'];
				$data['list_changeqr'] = ['Otomatis (Logo Desa)', 'Manual'];
				$data['list_sizeqr'] = ['25', '50', '75', '100', '125', '150', '175', '200', '225', '250'];

				$this->render('setting/setting_qr', $data);

				break;
		}
	}

	public function qrcode_generate()
	{
		$pathqr = LOKASI_MEDIA; // Lokasi default simpan file qrcode
		$post = $this->input->post();
		$namaqr = $post['namaqr']; // Nama file gambar asli
		$namaqr1 = str_replace(' ', '_', nama_terbatas($namaqr)); // Nama file gambar yg akan disimpan
		$changeqr = $post['changeqr'];

		// $logoqr = yg akan ditampilkan, url
		// $logoqr1 = yg akan disimpan, directory
		if ($changeqr == '1')
		{
			$desa = $this->config_model->get_data();
			// Ambil absolute path, bukan url
			$logoqr1 = gambar_desa($desa['logo'], false, $file = true);
		}
		else
		{
			$logoqr = $post['logoqr'];
			// Ubah url (http) menjadi absolute path ke file di lokasi media
			$lokasi_media = preg_quote(LOKASI_MEDIA, '/');
			$file_logoqr = preg_split('/'.$lokasi_media.'/', $logoqr)[1];
			$logoqr1 = APPPATH.'../'.LOKASI_MEDIA.$file_logoqr;
		}

		$qrcode = [
			'namaqr' => $namaqr, // Nama file
			'namaqr1' => $namaqr1, // Nama file untuk download
			'isiqr' => $post['isiqr'], // Isi / arti dr qrcode
			'changeqr' => $changeqr, // Pilihan jenis sisipkan logo
			'logoqr' => $logoqr,
			'sizeqr' => bilangan($post['sizeqr']), // Ukuran qrcode
			'backqr' => '#ffffff', // Code warna default (#ffffff / putih)
			'foreqr' => $post['foreqr'], // Code warna asli
			'pathqr' => base_url(LOKASI_MEDIA.''.$namaqr1.'.png') // Tampilkan gambar qrcode
		];

		$this->session->qrcode = $qrcode;

		if ($post)
		{
			$this->session->success = 1;
			$data = qrcode_generate($pathqr, $namaqr1, $qrcode['isiqr'], $logoqr1, $qrcode['sizeqr'], $qrcode['backqr'], $qrcode['foreqr']);
			echo json_encode($data);
		}
		else
		{
			$this->session->success = -1;
		}
	}
}
