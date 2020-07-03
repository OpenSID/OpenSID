<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hom_sid extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('program_bantuan_model');
		$this->load->model('surat_model');
		$this->load->model('notif_model');
		$this->modul_ini = 1;
	}

	public function index()
	{
		// Pengambilan data penduduk untuk ditampilkan widget Halaman Dashboard (modul Home SID)
		$data['penduduk'] = $this->header_model->penduduk_total();
		$data['keluarga'] = $this->header_model->keluarga_total();
		$data['bantuan'] = $this->header_model->bantuan_total();
		$data['kelompok'] = $this->header_model->kelompok_total();
		$data['rtm'] = $this->header_model->rtm_total();
		$data['dusun'] = $this->header_model->dusun_total();
		$data['jumlah_surat'] = $this->surat_model->surat_total();
		$header = $this->header_model->get_data();

		$user_grup = $this->session->userdata('grup');
		$data['persetujuan_pengguna'] = NULL;
		if(isset($user_grup) && $user_grup==1) // hanya utk user administrator
		{
			$data['persetujuan_pengguna'] = $this->persetujuan_pengguna();
		}

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('home/desa', $data);
		$this->load->view('footer');
	}

	private function persetujuan_pengguna()
	{

		$notif = $this->notif_model->get_notif_by_judul("persetujuan penggunaan");
		$tgl_sekarang = date("Y-m-d H:i:s");  
		$tgl_berikutnya = $notif['tgl_berikutnya'];
		$persetujuan = NULL;
		// pengumuman tampil saat sistem pertama digunakan atau ketika tgl_berikutnya tlh tercapai
		// data pengumuman di input ke database jauh hari sebelumnya 
		// nilai default tgl_berikutnya pasti lebih kecil dr tgl saat pertama sistem digunakan
		if($tgl_berikutnya <= $tgl_sekarang)
		{
			// simpan view pengumuman dalam variabel
			$var['isi_pengumuman'] = $notif['isi'];
			$persetujuan = $this->load->view('notif/pengumuman', $var, TRUE); // TRUE utk ambil content view sebagai output
			
			// update tabel notifikasi
			$frekuensi = $notif['frekuensi'];
			$string_frekuensi = "+". $frekuensi . " Days";
			$tambah_hari = strtotime($string_frekuensi);  // tgl hari ini ditambah frekuensi
			$tgl_berikutnya =  date('Y-m-d H:i:s', $tambah_hari); 
			$user = $this->session->userdata('user');
			$this->notif_model->update_by_judul("persetujuan penggunaan", $tgl_berikutnya, $tgl_sekarang, $user);
		}
		
		return $persetujuan;
	}

	public function dialog_pengaturan()
	{
		$data['list_program_bantuan'] = $this->program_bantuan_model->list_program();
		$data['sasaran'] = unserialize(SASARAN);
		$data['form_action'] = site_url("hom_sid/ubah_program_bantuan");
		$this->load->view('home/pengaturan_form', $data);
	}

	public function ubah_program_bantuan()
	{
		$this->db->where('key','dashboard_program_bantuan')->update('setting_aplikasi', array('value'=>$this->input->post('program_bantuan')));
		redirect('hom_sid');
	}
}