<?php 
//api_set_hari
if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk api Setting > Hari Merah
 *
 * donjo-app/controllers/settings/Api_set_hari.php
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
error_reporting(E_ALL);
class Api_set_hari extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('hari_model');
	}

	public function index()
	{
		$post = $this->input->post();
		$json_send=[ ];
		$raw=[$post,$_SESSION];
		$action=$this->input->post('action','none');
		//die($action."|".json_encode($post));
		if(method_exists($this, $action))
		{
			$json_send=$this->{$action}();
		}else{
			$json_send['error']=TRUE;
			$json_send['message']='Metode tidak diketahui';
		}
		
		header('Content-Type: application/json');
		echo json_encode($json_send);
	}
	
	private function show()
	{
		$post = $this->input->post();
		$tgl1=strtotime($post['tahun'].'-'.$post['bln'].'-01');
		$hari1=date("N",$tgl1);
		$tglAkhir=date("t",$tgl1);
		$raw[]=[$tgl1,$hari1,$tglAkhir];
		$tgl_merah=$this->hari_model->tgl_by_range(date("Y-m-d",$tgl1), date("Y-m-t",$tgl1));
		$data=[
			'tgl1'=>$tgl1,
			'start'=>$hari1,
			'last'=>$tglAkhir,
			'merah'=>$tgl_merah
		];
		$raw[]=$data;
		$json_send['raw']=$raw;
		$view=$this->load->view('hari/hari_api',$data,true);
		$json_send['html']=$view;
		
		return $json_send;
	}
	
		
	function update_tgl()
	{
		$post = $this->input->post();
		$json_send=[ ];
		$raw=[$post,$_SESSION];
		$param=['tgl_merah'=>$this->input->post('tgl_merah'), 'status'=>0];
		$this->hari_model->insert_ignore($param);
		$param['status']=$this->input->post('status');
		$param['detail']=$this->input->post('detail');
		$this->hari_model->_update($param);
		return $raw;
	}
}