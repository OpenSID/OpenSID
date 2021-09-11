<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk modul Kehadiran > Hari Merah
 *
 * donjo-app/controllers/kehadiran/Set_hari.php
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

class Set_hari extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('hari_model');
		$this->modul_ini = 320;
		$this->sub_modul_ini = 321;
	}
	
	public function index()
	{
		$data=[];
		if(!$this->input->get('reload'))
		{
			$this->sabtu_minggu();
			redirect('/set_hari'."?reload=1");
			exit;
		}			
		$this->render('kehadiran/hari_view', $data);
	}
	
	public function edit_tgl()
	{
		$tgl=$this->input->get('tgl'); 
		$paramsEdit=['tanggal'=>$tgl,'first'=>1];
		$hari=$this->hari_model->_get($paramsEdit);
		if(!$hari&&$tgl!=0){
			$param=['tgl_merah'=>$tgl, 'status'=>0];
			$this->hari_model->insert_ignore($param);
			$hari=$this->hari_model->_get($paramsEdit);
		}
		$data=[
			'hari'=>$hari
		];
		$this->load->view('kehadiran/hari_edit_view',$data);
	}

	
	public function sabtu_minggu()
	{
		return;
		$tahun0=strtotime(date("Y")."-01-01");
		$tahun1=strtotime("+2 year");
		$hari=3600*24;
		for($i=$tahun0;$i<=$tahun1;$i+=$hari)
		{
			if(date('N',$i)==6||date('N',$i)==7)
			{
				$param=['tgl_merah'=>date("Y-m-d",$i), 'status'=>1];
				$this->hari_model->insert_ignore($param);
			}
		}
		
	}
}