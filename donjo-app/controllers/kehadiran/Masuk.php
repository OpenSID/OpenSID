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
		$this->load->model(['config_model', 'anjungan_model', 'mandiri_model', 'theme_model', 'hari_model', 'hadir_model']);
		$this->load->helper('other_helper');
		//disarankan diletakkan di config
		$this->defaultJamTerakhir="18:00:00";//23:59:59
		
	}

	public function index()
	{
		$type = [1,2,3 ];//2.4 masih ditanya
		if ($this->session->kehadiran == 1)
		{
			$this->session->unset_userdata('kehadiran');
		}			


		//Initialize Session ------------
		$this->session->unset_userdata('balik_ke');
		if ( !isset($this->session->mandiri))
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
		
		$data['login_type'] = in_array($this->input->get('form'),$type)?$this->input->get('form'):1;
		$add_sess = ['login_type'=>$data['login_type']];
		$this->session->set_userdata($add_sess);
		$params = ['now'=>1,'first'=>1];
		$hari = $this->hari_model->_get($params);
		if (count($hari))
		{
			//pre_print_r($hari);die;
			$data['locked'] = $hari;
		}
		
		$this->load->view('kehadiran/masuk_view', $data);
	}

	public function cek()
	{
		$masuk = $this->input->post();
		$nik = bilangan(bilangan($masuk['nik']));
		$pin = hash_pin(bilangan($masuk['pin']));
		
		$penduduk = $this->infoPenduduk($nik);
		$pamong   = $this->infoPamong($nik);

		
		if (!isset($penduduk['result']->nama)&&!isset($pamong['result']->nama))
		{
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali Inputan Anda. Karena tidak terdaftar dalam Sistem",
			];
			$this->session->set_userdata($add_sess); 
			//pre_print_r($penduduk);pre_print_r($pamong);die("?");
			redirect($_SERVER['HTTP_REFERER'],1);
			exit;
		}
		
		if (isset($penduduk['result']->nama)){
			$login = $penduduk['result'];
		}
		
		if (isset($pamong['result']->nama)){
			$login = $pamong['result'];
		}
		
		if ($login->pamong_status!=1)
		{ 
			$add_sess = [
				'error_msg' => "Maaf, {$login->nama} tidak aktif",
			];
			$this->session->set_userdata($add_sess); 
			
			redirect($_SERVER['HTTP_REFERER'],1);
			exit;
		}
		
		if ($pin != $login->pin)
		{
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali NIK / PIN ",
				//($pin|{$login->pin}) ".json_encode($login),
			];
			$this->session->set_userdata($add_sess); 
			redirect($_SERVER['HTTP_REFERER'],1);
		}
		
		$login->nik=base64_encode($login->nik);
		$session = [
			'kehadiran' => 1,
			'login_data' => $login
		];
		$this->session->set_userdata($session);
		redirect('kehadiran/depan',1);
	}
	
	private function infoPenduduk($nik)
	{
		$selects='pm.*, p.nama, p.nik, pamong.foto, p.kk_level, p.tag_id_card,pamong.pamong_nip nip,
		pamong.jabatan, pamong.pamong_id,pamong.pamong_status,p.id id_penduduk';
		$penduduk = $this->db
			->select($selects)
			->from('tweb_desa_pamong pamong')
			->join('tweb_penduduk p', 'pamong.id_pend=p.id' )
			->join('tweb_penduduk_mandiri pm', 'pm.id_pend = p.id' )
			->where("p.nik",$nik)
			->or_where("p.tag_id_card",$nik)
			->get();
		return ['nik'=>$nik, 'result'=>$penduduk->row()];
	}
	
	private function infoPamong($nik)
	{
		$pamong = $this->db
			->select("pamong_nama nama, pamong_nik nik, pamong.pamong_nip nip, pamong_pin pin,foto, tag_id_card, pamong.jabatan, pamong.pamong_id,pamong.pamong_status")
			->from('tweb_desa_pamong pamong')
			->where("pamong_nik",$nik)
			->or_where("tag_id_card",$nik)
			->get();
		//pamong.pamong_nip
		//'query'=>$this->db->last_query()
		return ['nik'=>$nik, 'result'=>$pamong->row()];
	}
	
	public function cekv0()
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
		if (!isset($data->nama))
		{
			log_message("info","cek penduduk sql:".$this->db->last_query());
			$selects='pamong_nama nama,pamong_nik nik, pamong.foto, "0" kk_level, pamong.jabatan, pamong.pamong_id,pamong.pamong_status';
			$pamong = $this->db
				->select($selects)
				->from('tweb_desa_pamong pamong') 	
				->where('pamong_nik', $nik)
				->get()
				->row();
			if (isset($pamong->nama))
			{
				if ($pamong->pamong_status!=1)
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
		elseif ($pin != $data->pin)
		{
			log_message("info","error:pin|data:".json_encode($data));
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali NIK / PIN",
			];
			$this->session->set_userdata($add_sess); 
			
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
		}
		elseif (!$data->pamong_id)
		{
			log_message("info","error:pamong|data:".json_encode($data));
			$add_sess = [
				'error_msg' => "Silahkan memeriksa kembali NIK . Karena tidak terdaftar dalam Sistem",
			];
			$this->session->set_userdata($add_sess); 
			 
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
		}
		elseif ($data->pamong_status != 1)
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
		
		if ( isset($this->session->kehadiran) && $this->session->kehadiran == 0 )
		{
			redirect('kehadiran/masuk',1);
		}
		
		$this->hadir_clean();
		$session =$this->session->userdata();
		
		if ($this->input->post('hadir'))
		{
			$post  = $this->input->post();
			$hadir = $this->input->post('hadir');
			if ($hadir == 1)
			{
				$params           = ['now'=>1, 'pamong_id'=>$session['login_data']->pamong_id, 'first'=>1];
				$params['select'] = "id,hadir_logs";
				$data             = $this->hadir_model->_get($params);
				$hadir_logs       = json_decode($data['hadir_logs']);
				$hadir_logs[]     = [
					'date'=>date("Ymd H:i:s"),
					'status'=>1,
					'ip'=>get_client_ip()
				];
				$update           = [
					'hadir_logs'=>json_encode($hadir_logs),
					'waktu_masuk'=>date("Y-m-d H:i:s")					
				];
				$where            = 'id';
				$cond             = $data['id'];
				$this->hadir_model->_update($update,$where,$cond);
				$add_sess         = [
					'success_msg' => "<b>Terima kasih.</b><br/>Anda telah mengisi kehadiran hari ini (".tgl_indo_dari_str('now')."). <br/>Selamat bekerja dan jangan lupa login kembali saat pulang.",
				];
				$this->session->set_userdata($add_sess); 
				$url_login        = site_url("kehadiran/masuk").'?form='. $this->session->userdata('login_type');
				redirect($url_login,1);
				exit;

			}
			
			if ($hadir==2)
			{
				$params           = ['now'=>1,'pamong_id'=>$session['login_data']->pamong_id,'first'=>1];
				$params['select'] = "*";
				$data=$this->hadir_model->_get($params);
				$hadir_logs       = json_decode($data['hadir_logs']);
				$hadir_logs[]     = [
					'date'=>date("Ymd H:i:s"),
					'status'=>2,
					'ip'=>get_client_ip()
				];
				$update           = [
					'hadir_logs'=>json_encode($hadir_logs),
					'waktu_keluar'=>date("Y-m-d H:i:s")					
				];
				$where            = 'id';
				$cond             = $data['id'];
				$this->hadir_model->_update($update,$where,$cond);
				$add_sess         = [
					'success_msg' => "Terima kasih. Anda telah mengisi kehadiran hari ini (".tgl_indo_dari_str('now')."). <br/>Terima kasih atas kerja keras hari ini, semoga bertemu kembali dengan keadaan sehat.",
				];
				$this->session->set_userdata($add_sess); 
				 
				redirect("kehadiran/masuk",1);
				exit;
				//pre_print_r($data);pre_print_r($params);die;
			}
			
		}
		
		$data            = [];
		$data['status']  = 1;
		$data['session'] = $session; 
		$login_info      = $login_info_clean=$this->session->userdata('login_data');
		//$login_info_clean->nik = base64_encode($login_info->nik);
		unset($login_info_clean->foto);
		$logs   = [];
		$logs[] = [
			'date'=>date("Ymd H:i:s"),
			'status'=>0,
			'ip'=>get_client_ip()
		];
		//insert--------------------
		$params_add 		= [
			'pamong_id'=>@$login_info->pamong_id,
			'pamong_info'=>json_encode(@$login_info_clean),
			'hadir_logs'=>json_encode($logs)
		];
		$this->hadir_model->_add($params_add,1);//insert ignore 
		$data['session'][]	= $this->db->last_query();
		$params				= [
			'now'=>1,
			'pamong'=>@$session['login_data']->pamong_id,
			'first'=>1
		];
		$data['hadir']		= $hadir=$this->hadir_model->_get($params);
		
		if ($hadir['waktu_keluar']!=NULL)
		{
			$add_sess = [
				'error_msg' => "Silahkan periksa kembali kehadiran anda pada (".tgl_indo_dari_str('now').").",
			];
			$this->session->set_userdata($add_sess);
		}
		
		
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
		$result=$this->hadir_model->tidakKeluar();
		foreach($result['data'] as $row)
		{
			$params = [
				'waktu_keluar' => date("Y-m-d {$this->defaultJamTerakhir}",strtotime(row['waktu_masuk']))
			];
			
			//bug apabila waktunya tidak sesuai?!?
			$this->hadir_model->_update($params,'id',$row['id']);
		}
		 
		return ;
	}
	
	function pengurus()
	{
		if (ENVIRONMENT != 'development')
		{
			redirect($_SERVER['HTTP_REFERER'],1);
		}
		
		$selects = 'pm.*, p.nama, p.nik, pamong.foto, p.kk_level, p.tag_id_card,
		pamong.jabatan, pamong.pamong_id,pamong.pamong_status,p.id id_penduduk';
		$penduduk = $this->db
			->select($selects)
			->from('tweb_desa_pamong pamong')
			->join('tweb_penduduk p', 'pamong.id_pend=p.id' )
			->join('tweb_penduduk_mandiri pm', 'pm.id_pend = p.id' )
			->where("p.nama is not NULL	")
			->get();
		echo "daftar<table border=1>";
		foreach($penduduk->result() as $row)
		{
			
			echo "<tr><td>{$row->nama}</td><td>{$row->nik}</td><td>{$row->jabatan}</td><td>{$row->tag_id_card}</td></tr>";
		}
		
		echo "<tr><td colspan=3>Pamong</td></tr>";
		$pamong = $this->db
			->select("pamong_nama nama, pamong_nik nik,tag_id_card,jabatan")
			->from('tweb_desa_pamong pamong')
			->where("id_pend is NULL")
			->get();
		foreach($pamong->result() as $row)
		{
			//echo"<pre>".print_r($row,1)."</pre>";
			echo "<tr><td>{$row->nama}</td><td>{$row->nik}</td><td>{$row->jabatan}</td><td>{$row->tag_id_card}</td></tr>";
		}
		echo "</table><hr/>";
	}

}
