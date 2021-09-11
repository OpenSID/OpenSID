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

	private $cek_anjungan;

	public function __construct()
	{
		parent::__construct();
		mandiri_timeout();
		$this->load->model(['config_model', 'anjungan_model', 'mandiri_model', 'theme_model']);
		$this->cek_anjungan = $this->anjungan_model->cek_anjungan();

		if ($this->setting->layanan_mandiri == 0 && ! $this->cek_anjungan) show_404();
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
		$selects='pm.*, p.nama, p.nik, p.foto, p.kk_level, 
		pamong.jabatan, pamong.pamong_id,pamong.pamong_status';
		$data = $this->db
			->select($selects)
			->from('tweb_penduduk_mandiri pm')
			->join('tweb_penduduk p', 'pm.id_pend = p.id', 'left')
			->join('tweb_desa_pamong pamong', 'pamong.pamong_nip=p.nik','left')	
			->where('p.nik', $nik)
			->get()
			->row();
		if(!isset($data->nama))
		{
			log_message("info","cek user sql:".$this->db->last_query());
			//$this->session->error_msg = "Silahkan memeriksa kembali NIK";
			$session = [
				'error_msg' => "Silahkan memeriksa kembali NIK"
			];
			$this->session->set_userdata($session); 
			redirect($_SERVER['HTTP_REFERER'],1);
			//redirect('kehadiran/masuk',1);
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
			//echo "OK";die;
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
		$session =$this->session->userdata();
		print_r($session);die;
		$data = $this->db
			->select('pm.*, p.nama, p.nik, p.foto, p.kk_level')
			->from('tweb_penduduk_mandiri pm')
			->join('tweb_penduduk p', 'pm.id_pend = p.id', 'left')
			->where('p.nik', $nik)
			->get()
			->row();
		
	}
}
