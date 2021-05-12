<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  File ini:
 *
 * Controller untuk modul pendapat
 *
 * donjo-app/controllers/Pendapat.php
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

class Pendapat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['pendapat_model']);
		$this->modul_ini = 14;
		$this->sub_modul_ini = 321;
	}

	public function index()
	{
		$tipe = $this->session->flashdata('tipe');
		$data['list_pendapat'] = unserialize(NILAI_PENDAPAT);
		foreach ($data['list_pendapat'] as $key => $value)
		{
			$data["pilihan_$key"] = $this->pendapat_model->get_pilihan($tipe, $key);
		}

		$data['main'] = $this->pendapat_model->get_pendapat($tipe);
		$data['detail'] = $this->pendapat_model->get_data($tipe);

		$this->render('pendapat/index', $data);
		//echo json_encode($data);
	}

	public function detail(int $tipe = 1)
	{
		$this->session->set_flashdata('tipe', $tipe);

		redirect('pendapat');
	}
}
