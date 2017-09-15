<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');
	$suami = $this->get_data_suami($individu['id']);
	if($input['id_pelapor']) {
		$pelapor = $this->get_data_surat($input['id_pelapor']);
		$input['nik_pelapor'] 			= $pelapor['nik'];
		$input['nama_pelapor'] 			= $pelapor['nama'];
		$input['umur_pelapor']			= $pelapor['umur'];
		$input['jkpelapor']					= $pelapor['sex_id'];
		$input['pekerjaanpelapor']	= $pelapor['pekerjaan'];
		$input['desapelapor']				= $config['nama_desa'];
		$input['kecpelapor']				= $config['nama_kecamatan'];
		$input['kabpelapor']				= $config['nama_kabupaten'];
		$input['provinsipelapor']		= $config['nama_propinsi'];
	}
	if($input['id_saksi1']) {
		$saksi1 = $this->get_data_surat($input['id_saksi1']);
		$input['nik_saksi1'] 				= $saksi1['nik'];
		$input['nama_saksi1'] 			= $saksi1['nama'];
		$input['umur_saksi1']				= $saksi1['umur'];
		$input['jksaksi1']					= $saksi1['sex_id'];
		$input['pekerjaansaksi1']		= $saksi1['pekerjaan'];
		$input['desasaksi1']				= $config['nama_desa'];
		$input['kecsaksi1']					= $config['nama_kecamatan'];
		$input['kabsaksi1']					= $config['nama_kabupaten'];
		$input['provinsisaksi1']		= $config['nama_propinsi'];
	}
	if($input['id_saksi2']) {
		$saksi2 = $this->get_data_surat($input['id_saksi2']);
		$input['nik_saksi2'] 				= $saksi2['nik'];
		$input['nama_saksi2'] 			= $saksi2['nama'];
		$input['umur_saksi2']				= $saksi2['umur'];
		$input['jksaksi2']					= $saksi2['sex_id'];
		$input['pekerjaansaksi2']		= $saksi2['pekerjaan'];
		$input['desasaksi2']				= $config['nama_desa'];
		$input['kecsaksi2']					= $config['nama_kecamatan'];
		$input['kabsaksi2']					= $config['nama_kabupaten'];
		$input['provinsisaksi2']		= $config['nama_propinsi'];
	}
	$desa = $this->keluarga_model->get_desa();
	// Gunakan data identitas desa, jika ada
	if ($desa['nip_kepala_desa']){
		$kepala_desa['pamong_nama'] = $desa['nama_kepala_desa'];
		$kepala_desa['pamong_nip'] = $desa['nip_kepala_desa'];
	} else
		$kepala_desa = $this->pamong_model->get_pamong_by_nama($desa['nama_kepala_desa']);

?>