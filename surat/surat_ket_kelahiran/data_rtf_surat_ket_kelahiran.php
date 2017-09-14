<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

	/*
		Jika saksi1 warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if($input['id_saksi1']) {
		$saksi1 = $this->get_data_surat($input['id_saksi1']);
		$array_replace = array(
	                "[nama_saksi1]"        		=> $saksi1['nama'],
	                "[nik_saksi1]"       			=> $saksi1['nik'],
	                "[tempat_lahir_saksi1]"   => $saksi1['tempatlahir'],
	                "[tanggal_lahir_saksi1]"	=> tgl_indo_dari_str($saksi1['tanggallahir']),
	                "[umur_saksi1]"  					=> $saksi1['umur'],
	                "[pekerjaansaksi1]" 			=> $saksi1['pekerjaan'],
	                "[form_desasaksi1]"       => $config['nama_desa'],
	                "[form_kecsaksi1]"       	=> $config['nama_kecamatan'],
	                "[form_kabsaksi1]"       	=> $config['nama_kabupaten'],
	                "[form_provinsisaksi1]"   => $config['nama_provinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	/*
		Jika saksi2 warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if($input['id_saksi2']) {
		$saksi2 = $this->get_data_surat($input['id_saksi2']);
		$array_replace = array(
	                "[nama_saksi2]"        		=> $saksi2['nama'],
	                "[nik_saksi2]"       			=> $saksi2['nik'],
	                "[tempat_lahir_saksi2]"   => $saksi2['tempatlahir'],
	                "[tanggal_lahir_saksi2]"	=> tgl_indo_dari_str($saksi2['tanggallahir']),
	                "[umur_saksi2]"  					=> $saksi2['umur'],
	                "[pekerjaansaksi2]" 			=> $saksi2['pekerjaan'],
	                "[form_desasaksi2]"       => $config['nama_desa'],
	                "[form_kecsaksi2]"       	=> $config['nama_kecamatan'],
	                "[form_kabsaksi2]"       	=> $config['nama_kabupaten'],
	                "[form_provinsisaksi2]"   => $config['nama_provinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	/*
		Jika pelapor warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if($input['id_pelapor']) {
		$pelapor = $this->get_data_surat($input['id_pelapor']);
		$array_replace = array(
	                "[form_nama_pelapor]"      => $pelapor['nama'],
	                "[nama_pelapor]"      		 => $pelapor['nama'],
	                "[form_nik_pelapor]"       => $pelapor['nik'],
	                "[nik_pelapor]"       		 => $pelapor['nik'],
	                "[tempat_lahir_pelapor]"   => $pelapor['tempatlahir'],
	                "[tanggal_lahir_pelapor]"	 => tgl_indo_dari_str($pelapor['tanggallahir']),
	                "[form_umur_pelapor]"  		 => $pelapor['umur'],
	                "[umur_pelapor]"  		 		 => $pelapor['umur'],
	                "[form_pekerjaanpelapor]"  => $pelapor['pekerjaan'],
	                "[pekerjaanpelapor]"  		 => $pelapor['pekerjaan'],
	                "[form_desapelapor]"       => $config['nama_desa'],
	                "[form_kecpelapor]"        => $config['nama_kecamatan'],
	                "[form_kabpelapor]"        => $config['nama_kabupaten'],
	                "[form_provinsipelapor]"   => $config['nama_provinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
?>