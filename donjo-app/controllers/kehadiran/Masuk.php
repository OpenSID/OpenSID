<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Masuk Layanan Mandiri
 *
 * donjo-app/controllers/layanan_mandiri/Masuk.php
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

class Masuk extends Web_Controller
{

	private $cek_anjungan,$defaultJamTerakhir;

	public function __construct()
	{
		parent::__construct();
		mandiri_timeout();
		$this->load->model(['config_model', 'anjungan_model', 'mandiri_model', 'theme_model','hari_model', 'hadir_model']);
		$this->load->helper('other_helper');
		//disarankan diletakkan di config
		$this->defaultJamTerakhir="18:00:00";//23:59:59
		
	}

	public function warning()
	{
		$session =$this->session->userdata();
			print_r($session);die;
			
	}

	public function index()
	{
		$type=[1, 3 ];//2.4 masih ditanya
		if ($this->session->kehadiran == 1)
		{
			$this->session->unset_userdata('kehadiran');
		}			
			
			//redirect('kehadiran/daftar');

		if ($error_msg = $this->session->error_msg)
		{
			//die($error_msg);
		}
		else{
			
		}
		//Initialize Session ------------
		$this->session->unset_userdata('balik_ke');
		if ( ! isset($this->session->mandiri))
		{
			// Belum ada session variable
			$this->session->kehadiran = 0;
		}

		$data = [
			'header' => $this->config_model->get_data(),
			//'latar_login' => $this->theme_model->latar_login(),
			'cek_anjungan' => $this->cek_anjungan,
			'form_action' => site_url('kehadiran/cek')
		];
		
		$data['login_type']=in_array($this->input->get('form'),$type)?$this->input->get('form'):1;
		
		$this->load->view('kehadiran/masuk_view', $data);
	}

	public function cek()
	{
		//$this->mandiri_model->siteman();
		$masuk = $this->input->post();
		$nik = bilangan(bilangan($masuk['nik']));
		$pin = hash_pin(bilangan($masuk['pin']));
		$selects='pm.*, p.nama, p.nik, pamong.foto, p.kk_level, 
		pamong.jabatan, pamong.pamong_id,pamong.pamong_status,p.id id_penduduk';
		$penduduk = $this->db
			->select($selects)
			->from('tweb_penduduk p')
			->join('tweb_penduduk_mandiri pm', 'pm.id_pend = p.id', 'left')
			->join('tweb_desa_pamong pamong', 'pamong.id_pend=p.id','left')	
			->where('p.nik', $nik)
			->get();
		 
		$data=$penduduk->row();
		if(!isset($data->nama))
		{
			log_message("info","cek penduduk sql:".$this->db->last_query());
			$selects='pamong_nama nama,pamong_nik nik, pamong.foto, "0" kk_level, pamong.jabatan, pamong.pamong_id,pamong.pamong_status';
			$pamong = $this->db
				->select($selects)
				->from('tweb_desa_pamong pamong') 	
				->where('pamong_nik', $nik)
				->get()
				->row();
			if(isset($pamong->nama))
			{
				if($pamong->pamong_status!=1)
				{
					log_message("info","error:not active|data:".json_encode($data));
					$add_sess = [
						'error_msg' => "Maaf, {$pamong->nama} tidak aktif",
					];
					$this->session->set_userdata($add_sess); 
					
					redirect($_SERVER['HTTP_REFERER'],1);
					exit;
					//redirect('kehadiran/masuk',1);
				}
				
				$session = [
					'kehadiran' => 1,
					'is_login' => $pamong
				];
				$this->session->set_userdata($session);
				
			}
			else
			{
				$session = [
					'error_msg' => "Silahkan memeriksa kembali NIK"
				];
				$this->session->set_userdata($session); 
				redirect($_SERVER['HTTP_REFERER'],1);
			}
 
		}
		elseif($pin != $data->pin)
		{
			log_message("info","error:pin|data:".json_encode($data));
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali NIK / PIN",
			];
			$this->session->set_userdata($add_sess); 
			
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
		}
		elseif(!$data->pamong_id)
		{
			log_message("info","error:pamong|data:".json_encode($data));
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali NIK . Karena tidak terdaftar dalam Sistem",
			];
			$this->session->set_userdata($add_sess); 
			 
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
		}
		elseif($data->pamong_status!=1)
		{
			log_message("info","error:not active|data:".json_encode($data));
			$add_sess = [
				'error_msg' => "Maaf, {$data->nama} tidak aktif",
			];
			$this->session->set_userdata($add_sess); 
			
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
		}
		else
		{
			$session = [
					'kehadiran' => 1,
					'is_login' => $data
				];
				$this->session->set_userdata($session);
		}
			
		redirect('kehadiran/depan',1);
	}

	function depan()
	{
		
		if ( isset($this->session->kehadiran)&&$this->session->kehadiran==0)
		{
			//$this->session->mandiri_try = $this->session->mandiri_try - 1;
			redirect('kehadiran/masuk',1);
		}
		$this->hadir_clean();
		$session =$this->session->userdata();
		//echo '<pre>';print_r($session);die;
		$data = [];
		$data['status']=1;
		$data['session']=$session;
		
		$login_info=$login_info_clean=$this->session->userdata('is_login');
		$login_info_clean->nik = base64_encode($login_info->nik);
		unset($login_info_clean->foto);
		$logs=[];
		$logs[]=[
			'date'=>date("Ymd H:i:s"),
			'status'=>0,
			'ip'=>get_client_ip()
		];
		//insert
		$params_add=[
			'pamong_id'=>@$login_info->pamong_id,
			'pamong_info'=>json_encode(@$login_info_clean),
			'hadir_logs'=>json_encode($logs)
		];
		$this->hadir_model->_add($params_add,1);//insert ignore 
		$data['session'][]=$this->db->last_query();
		$params=[
			'now'=>1,
			'pamong'=>@$session['is_login']->pamong_id,
			'first'=>1
		];
		$data['hadir']=$this->hadir_model->_get($params);
		//kehadiran/depan_view
		$this->load->view('kehadiran/masuk_view', $data);
	}
	
	function keluar()
	{
		$session = [
			'kehadiran' => 0,
			'is_login' => NULL,
			'info_msg' => "Logout telah dilakukan.",
		];
		$this->session->set_userdata($session);
		redirect('kehadiran/masuk',1);
	}
	
	private function hadir_clean()
	{
		//$data=$this->hadir_model->_blank();
		$result=$this->hadir_model->tidakKeluar();
		foreach($result['data'] as $row)
		{
			$params=[
				'waktu_keluar'=>date("Y-m-d {$this->defaultJamTerakhir}",strtotime(row['waktu_masuk']))
			];
			
			//bug apabila waktunya tidak sesuai?!?
			$this->hadir_model->_update($params,'id',$row['id']);
		}
		 
		return ;
	}
}
