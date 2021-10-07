<?php 
//api_set_hari
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk api Kehadiran > Hari Merah
 *
 * donjo-app/controllers/kehadiran/Api_set_hari.php
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
 * @author	Gunawan Wibisono
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
		$post      = $this->input->post();
		$json_send = [ ];
		$raw       = [ $post, $this->session->userdata() ];
		$action    = $this->input->post('action', 'none');

		if ( method_exists($this, $action))
		{
			$json_send = $this->{$action}();
		}
		else
		{
			$json_send['error']   = TRUE;
			$json_send['message'] = 'Metode tidak diketahui';
		}
		
		header('Content-Type: application/json' );
		echo json_encode($json_send);
		
	}
	
	private function show()
	{
		$return   = [];
		$post     = $this->input->post();
		$tgl1     = strtotime($post['tahun'] .'-' .$post['bln'] .'-01');
		$hari1    = date("N",$tgl1);
		$tglAkhir = date("t",$tgl1);
		$raw[]	  = [$tgl1, $hari1, $tglAkhir];
		$tgl_mrh  = $this->hari_model->tgl_by_range(date("Y-m-d",$tgl1), date("Y-m-t",$tgl1));
		$data     = [
			'tgl1'  => $tgl1,
			'start' => $hari1,
			'last'  => $tglAkhir,
			'merah' => $tgl_merah
		];
//-------------DATA 
		$raw[]          = $data;
		$return['raw']  = $raw;
		$view           = $this->load->view('kehadiran/hari_api_view', $data,true);
		$return['html'] = $view;
		
		return $return;
	}
		
	public function update_tgl()
	{
		$post      = $this->input->post();
		$json_send = [ ];
		$raw       = [$post, $this->session->userdata()];
		$param     = [
			'tgl_merah' => $this->input->post('tgl_merah'), 'status'=>0
		];
		$this->hari_model->insert_ignore($param);
		
		$param['status'] = $this->input->post('status');
		$param['detail'] = $this->input->post('detail');
		$raw['sql']      = $this->hari_model->_update($param);
		$raw['param']    = $param;
		
		return $raw;
	}

	public function datatables()
	{
		$return         = [
		 'draw'			   => 0,
		 'recordsTotal'    => 0,
		 'recordsFiltered' => 0,
		 'data'            => [],
		 'raw'			   => NULL,
		];
		$raw[]          = [$this->input->post(), $this->input->get()];
		$return['draw'] = $this->input->post('draw',0);
		$tipe			= $this->input->post('type');
		$start			= $this->input->post('start');
		$limit			= $this->input->post('length');
		$params			= [];
		$search 		= $this->input->post('search');
		$params			= ['active'=>1];
		if ($tipe=='date')
		{
			$date1 = $this->input->post('dateStart');
			$date2 = $this->input->post('dateEnd');
			if ($date1 != '' && $date2 != '')
			{
				if ($date1 > $date2)
				{
					$params['date_range'] = [$date2, $date1];
				}
				else
				{
					$params['date_range'] = [$date1, $date2];
				}
				
			}
			
		}
		
		$params['active'] = 1;
		if (strlen($search['value']) >= 3)
		{
			$params['datatable_search'] = $search['value'];
			
		}
		else
		{
			$raw[] = [strlen($search), $search];
			
		}

		$params0 					= ['active'=>1];
		$return['recordsTotal']		= $this->hari_model->_count($params0);
		$return['recordsFiltered']	= $this->hari_model->_count($params);		
		$raw[]	  					= [$params,$search,$this->db->last_query()];
//-----data
		$dataHari					= $this->hari_model->_get($params, $limit,$start);
		$raw[]    					= $this->db->last_query();
		$raw[]    					= $dataHari;
		$data     					= [];
		$no       					= 1;
		foreach($dataHari as $row)
		{
			$info = "Hari biasa";
			if ($row['status'] == 1)
			{
				$info = "Hari Libur";
				
			}
			elseif ($row['status'] == 9)
			{
				$info = $row['detail'];
				
			}

			$data[] = [
				$no++,
				date("d/m/Y",strtotime($row['tgl_merah'])),
				$info,
				'-'
			];
		}
		
		$return['data'] = $data;
		$return['raw'] = $raw;
		if (ENVIRONMENT != 'development')
		{
			unset($return['raw']);
			
		}
		
		return $return;
	}
	
}