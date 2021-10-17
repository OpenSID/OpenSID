<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Controller untuk modul Layanan Mandiri > Pesan
 *
 * donjo-app/controllers/kehadiran/layanan_mandiri.php
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

class Layanan_mandiri extends Mandiri_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mailbox_model');
		$this->load->model('hadir_model');
	}

	public function index($kat = 1)
	{

		$data = [
			'desa' => $this->header,
			'aparat_aktif' => $this->cek_aparat(),
			'kat' => $kat,
			'judul' => ($kat == 1) ? 'Keluar' : 'Masuk',
			'pesan' => $pesan,
			'konten' => 'laporan_view'
		];
		
		$this->load->view('layanan_mandiri/template', $data);
	}
	
	public function aparat_lapor( )
	{
		$selects = " h.pamong_info, h.pamong_id pamong_id, h.lapor_logs, h.id";
		$id = $this->input->get('aparatid');
		if(!$id) $id = $this->input->post('aparatid');
		$tables = $this->db
			->select($selects)
			->from('hadir_pamong_hari h')
			->join('tweb_desa_pamong p','p.pamong_id=h.pamong_id','left')
			->where('tanggal = date(now())')
			->where('h.pamong_id',$id)
			->get()->row();
			
		
		$tables->pamong_info =  json_decode( $tables->pamong_info );
		$data = [ 
			'aparat' => $tables,  
			'id'	 => $id
		];
		
		if($this->input->post('lapor_txt'))
		{
			$login = $this->session->userdata('is_login');
			$lapor_logs = json_decode( $tables->lapor_logs);
			$lapor_logs[]=[
				'id_penduduk' => @$login->id_pend,
				'nama' => @$login->nama,
				'nik' => @$login->nik,
				'laporan' => $this->input->post('lapor_txt')
			];
			
			$params=[
				'lapor_logs'=>json_encode($lapor_logs)
			];
			//pre_print_r($login);
		//	pre_print_r([$this->input->post(), $params,$lapor_logs, $tables]);
			//die('update laporan');
			$where = 'id';
			$cond = $tables->id;
			$this->hadir_model->_update($params,$where,$cond);
			$add_sess         = [
				'success_msg' => "<b>Terima kasih.</b><br/>Anda telah membuat laporan (".tgl_indo_dari_str('now')."). <br/>Kami akan proses laporan anda.",
			];
			$this->session->set_userdata($add_sess); 
			$url      = site_url("layanan-mandiri/kehadiran");
			redirect($url, 1);exit;
		}
		
		$this->load->view('kehadiran/form/lapor_form', $data);
	}

	private function cek_aparat()
	{
		$sql="SELECT * FROM `hadir_pamong_hari` tanggal = date(now()) ORDER BY `id` ASC";
		$selects = "h.tanggal, h.waktu_masuk, h.waktu_keluar, h.pamong_info, h.hadir_logs, h.lapor_logs, p.foto,h.pamong_id id";
 
		$tables = $this->db
			->select($selects)
			->from('hadir_pamong_hari h')
			->join('tweb_desa_pamong p','p.pamong_id=h.pamong_id','left')
			->where('tanggal = date(now())')
			->get()->result();
		
		//print_r($tables->result());
		$aparats = array();
		foreach( $tables as $row)
		{
			$row->pamong_info = json_decode( $row->pamong_info );
			$row->hadir_logs  = json_decode( $row->hadir_logs  );
			$row->lapor_logs  = json_decode( $row->lapor_logs  );
			$status_end = $row->hadir_logs[count($row->hadir_logs)-1]->status;
			$row->status = $status_end;
			
			$aparats[] = $row;
		}

		// 'sql'=> $this->db->last_query()
		$result=[ 'result'=>$aparats, ];
		return $result;
	}
}