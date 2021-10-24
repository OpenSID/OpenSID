<?php 
//api_set_hari
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  File ini:
 *
 * Controller untuk api Kehadiran > Rekap
 *
 * donjo-app/controllers/kehadiran/Api_rekap.php
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
class Api_rekap extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('hadir_model');
		$this->load->model('pamong_model','pamong');
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

	public function datatables()
	{
		$startTime = microtime(true);
		$times	= [$startTime];
		$return         = [
			 'draw'			   => 0,
			 'recordsTotal'    => 0,
			 'recordsFiltered' => 0,
			 'data'            => [],
			 'raw'			   => NULL,
		];
		$column_order = [
			1 => 'tanggal',
			2 => 'pamong_nama' //harus memakai view
		];
		
		$raw[]          = [$this->input->post(), $this->input->get()];
		$return['draw'] = $this->input->post('draw',0);
		$tipe			= $this->input->post('type');
		$start			= $this->input->post('start');
		$limit			= $this->input->post('length');
		$params			= [];
		$search 		= $this->input->post('search');
		$order 			= $this->input->post('order');
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
			
			$times['cek date'] = microtime(true) - $startTime;
		}
		
		$params['active'] = 1;
		$params['waktu_masuk'] = 1;
		$params['select'] = 'id, pamong_id, tanggal, pamong_info, waktu_masuk, waktu_keluar';
		if (strlen($search['value']) >= 3)
		{
			$params['datatable_search'] = $search['value'];
			
		}
		else
		{
			$raw[] = [strlen($search), $search];
			
		}
		//-------------order
		$order_column = $order[0]['column'];//urut
		$order_dir 	  = $order[0]['dir'];//urut
		$params['orders'] = [ $column_order[$order_column] , $order_dir];

		$params0 					= ['active'=>1, 'waktu_masuk'=>1];
		$return['recordsTotal']		= $this->hadir_model->_count($params0);
		$times['recordsTotal'] = microtime(true) - $startTime;
		$raw['count']				= [$params0, $search, $this->db->last_query()];
		
		$return['recordsFiltered']	= $this->hadir_model->_count($params);
		$times['recordsFiltered']   = microtime(true) - $startTime;
		$raw[]	  					= [$params, $search, $this->db->last_query()];
//-----data
		$dataHari					= $this->hadir_model->_get($params, $limit,$start);
		$times['getData']   		= microtime(true) - $startTime;
		$raw[]    					= $this->db->last_query();
		$raw['data']                = $dataHari;
		$data     					= [];
		$no       					= $start+1;
		
		$pamongs = [];
		foreach($dataHari as $row)
		{
			if( !isset($pamongs[ $row['pamong_id'] ]) )
			{
				$pamongs[ $row['pamong_id'] ] = $this->pamong->get_data($row['pamong_id']);
				$times['getPamong-'.$row['pamong_id']] = microtime(true) - $startTime;
			}
			
			$pamong = $pamongs[ $row['pamong_id'] ];
			//$pamong = json_decode( $row['pamong_info'], 1);
			$data[] = [
				$no++, //no
				date( "d/m/Y", strtotime($row['tanggal']) ), //tanggal
				$pamong['pamong_nama'], //pamong
				date( "H:i:s", strtotime($row['waktu_masuk']) ), //jam masuk 
				date( "H:i:s", strtotime($row['waktu_keluar']) ), //jam keluar
				'pamong'=>$pamongs[ $row['pamong_id'] ]
			];
		}
		
		$raw['pamongs'] = $pamongs;
		$return['data'] = $data;
		$return['raw'] = $raw;
		if (ENVIRONMENT != 'development')
		{
			unset($return['raw']);
			
		}
		else
		{
			$return['times'] = $times;
		}
		
		return $return;
	}
	
}