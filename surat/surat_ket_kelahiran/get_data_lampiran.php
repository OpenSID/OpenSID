<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');
	if($input['id_ibu']) {
		$ibu = $this->get_data_surat($input['id_ibu']);
		$input['nik_ibu'] 							= $ibu['nik'];
		$input['nama_ibu'] 							= $ibu['nama'];
    $input['tanggal_lahir_ibu']			= $ibu['tanggallahir'];
    $input['umur_ibu']  						= $ibu['umur'];
    $input['pekerjaanibu'] 					= $ibu['pekerjaan'];
    $input['alamat_ibu']    				= trim($ibu['alamat'].' '.$ibu['dusun']);
    $input['rt_ibu']    						= $ibu['rt'];
    $input['rw_ibu']    						= $ibu['rw'];
    $input['desaibu']       				= $config['nama_desa'];
    $input['kecibu']       					= $config['nama_kecamatan'];
    $input['kabibu']       					= $config['nama_kabupaten'];
    $input['provinsiibu']   				= $config['nama_propinsi'];
		$input['wn_ibu']								= $ibu['warganegara_id'];
		$input['tanggalperkawinan_ibu']	= $ibu['tanggalperkawinan'];

		$ayah = $this->get_data_suami($ibu['id']);
		if ($ayah) {
			$input['nik_ayah'] 								= $ayah['nik'];
			$input['nama_ayah'] 							= $ayah['nama'];
	    $input['tanggal_lahir_ayah']			= $ayah['tanggallahir'];
	    $input['umur_ayah']  							= $ayah['umur'];
	    $input['pekerjaanayah'] 					= $ayah['pek'];
	    $input['alamat_ayah']    					= trim($ayah['alamat'].' '.$ayah['dusun']);
	    $input['rt_ayah']    							= $ayah['rt'];
	    $input['rw_ayah']    							= $ayah['rw'];
	    $input['desaayah']       					= $config['nama_desa'];
	    $input['kecayah']       					= $config['nama_kecamatan'];
	    $input['kabayah']       					= $config['nama_kabupaten'];
	    $input['provinsiayah']   					= $config['nama_propinsi'];
			$input['wn_ayah']									= $ayah['warganegara_id'];
			$input['tanggalperkawinan_ayah']	= $ayah['tanggalperkawinan'];
		}
	}
	if($input['id_bayi']) {
		$bayi = $this->get_data_surat($input['id_bayi']);
		$input['nik_bayi'] 		= $bayi['nik'];
		$input['nama_bayi'] 	= $bayi['nama'];
		$input['sex']					= $bayi['sex_id'];
		$input['hari']	  		= hari($bayi['tanggallahir']);
		$input['tanggal']	  	= $bayi['tanggallahir'];
	}
	// Jika ibu dari database, gunakan data ibu untuk info kepala keluarga.
	// Kalau tidak, gunakan data yang lahir. Salah satu harus dari database.
	if($ibu){
		$input['kepala_kk'] 	= $ibu['kepala_kk'];
		$input['no_kk'] 			= $ibu['no_kk'];
	} elseif ($bayi) {
		$input['kepala_kk'] 	= $bayi['kepala_kk'];
		$input['no_kk'] 			= $bayi['no_kk'];
	}
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