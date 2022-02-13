<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

# Data ibu dan ayah kandung dari database penduduk
if ($_SESSION['id_ibu']) {
	$ibu = $this->get_data_surat($_SESSION['id_ibu']);
	$array_replace = array(
		"[form_nama_ibu]"     	=> $ibu['nama'],
		"[nik_ibu]"       		=> $ibu['nik'],
		"[tempat_lahir_ibu]"  	=> $ibu['tempatlahir'],
		"[tanggal_lahir_ibu]"	=> tgl_indo_dari_str($ibu['tanggallahir']),
		"[umur_ibu]"  			=> $ibu['umur'],
		"[pekerjaanibu]" 		=> $ibu['pekerjaan'],
		"[alamat_ibu]"    		=> "RT $ibu[rt] / RW $ibu[rw] $ibu[dusun]",
		"[desaibu]"       		=> $config['nama_desa'],
		"[kecibu]"       		=> $config['nama_kecamatan'],
		"[kabibu]"       		=> $config['nama_kabupaten'],
		"[provinsiibu]"   		=> $config['nama_propinsi']
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

	$ayah = $this->get_data_suami($ibu['id']);
	// Jika tidak ada ayah dari database, ambil dari form
	if ($ayah) {
		$array_replace = array(
			"[form_nama_ayah]"     	=> $ayah['nama'],
			"[nik_ayah]"       		=> $ayah['nik'],
			"[tempat_lahir_ayah]"  	=> $ayah['tempatlahir'],
			"[tanggal_lahir_ayah]"	=> tgl_indo_dari_str($ayah['tanggallahir']),
			"[umur_ayah]"  			=> $ayah['umur'],
			"[pekerjaanayah]" 		=> $ayah['pek'], // dari get_data_pribadi()
			"[alamat_ayah]"    		=> "RT $ayah[rt] / RW $ayah[rw] $ayah[dusun]",
			"[desaayah]"       		=> $config['nama_desa'],
			"[kecayah]"       		=> $config['nama_kecamatan'],
			"[kabayah]"       		=> $config['nama_kabupaten'],
			"[provinsiayah]"   		=> $config['nama_propinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
}

if ($_SESSION['id_bayi']) {
	$bayi = $this->get_data_surat($_SESSION['id_bayi']);
	$jenis_kelamin = '';
	if ($bayi['sex_id'] == 1) {
		$jenis_kelamin = "LAKI-LAKI";
	}
	if ($bayi['sex_id'] == 2) {
		$jenis_kelamin = "PEREMPUAN";
	}

	$array_replace = array(
		"[form_nama_sex]"					=> $jenis_kelamin,
		"[form_nama_bayi]" 					=> $bayi['nama'],
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
}

/*
Jika saksi1 warga desa, ganti kolom isiannya dengan data dari database penduduk
*/
if ($_SESSION['id_saksi1']) {
	$saksi1 = $this->get_data_surat($_SESSION['id_saksi1']);
	$array_replace = array(
		"[nama_saksi1]"        		=> $saksi1['nama'],
		"[nik_saksi1]"       		=> $saksi1['nik'],
		"[tempat_lahir_saksi1]"   	=> $saksi1['tempatlahir'],
		"[tanggal_lahir_saksi1]"	=> tgl_indo_dari_str($saksi1['tanggallahir']),
		"[umur_saksi1]"  			=> $saksi1['umur'],
		"[pekerjaansaksi1]" 		=> $saksi1['pekerjaan'],
		"[form_desasaksi1]"       	=> $config['nama_desa'],
		"[form_kecsaksi1]"       	=> $config['nama_kecamatan'],
		"[form_kabsaksi1]"       	=> $config['nama_kabupaten'],
		"[form_provinsisaksi1]"   	=> $config['nama_propinsi']
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
}

/*
Jika saksi2 warga desa, ganti kolom isiannya dengan data dari database penduduk
*/
if ($_SESSION['id_saksi2']) {
	$saksi2 = $this->get_data_surat($_SESSION['id_saksi2']);
	$array_replace = array(
		"[nama_saksi2]"        		=> $saksi2['nama'],
		"[nik_saksi2]"       		=> $saksi2['nik'],
		"[tempat_lahir_saksi2]"   	=> $saksi2['tempatlahir'],
		"[tanggal_lahir_saksi2]"	=> tgl_indo_dari_str($saksi2['tanggallahir']),
		"[umur_saksi2]"  			=> $saksi2['umur'],
		"[pekerjaansaksi2]" 		=> $saksi2['pekerjaan'],
		"[form_desasaksi2]"       	=> $config['nama_desa'],
		"[form_kecsaksi2]"       	=> $config['nama_kecamatan'],
		"[form_kabsaksi2]"       	=> $config['nama_kabupaten'],
		"[form_provinsisaksi2]"   	=> $config['nama_propinsi']
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
}

/*
Jika pelapor warga desa, ganti kolom isiannya dengan data dari database penduduk
*/
if ($_SESSION['id_pelapor']) {
	$pelapor = $this->get_data_surat($_SESSION['id_pelapor']);
	$array_replace = array(
		"[form_nama_pelapor]"      	=> $pelapor['nama'],
		"[nama_pelapor]"      		=> $pelapor['nama'],
		"[form_nik_pelapor]"       	=> $pelapor['nik'],
		"[nik_pelapor]"       		=> $pelapor['nik'],
		"[tempat_lahir_pelapor]"   	=> $pelapor['tempatlahir'],
		"[tanggal_lahir_pelapor]"	=> tgl_indo_dari_str($pelapor['tanggallahir']),
		"[form_umur_pelapor]"  		=> $pelapor['umur'],
		"[umur_pelapor]"  		 	=> $pelapor['umur'],
		"[form_pekerjaanpelapor]"  	=> $pelapor['pekerjaan'],
		"[pekerjaanpelapor]"  		=> $pelapor['pekerjaan'],
		"[form_desapelapor]"       	=> $config['nama_desa'],
		"[form_kecpelapor]"        	=> $config['nama_kecamatan'],
		"[form_kabpelapor]"        	=> $config['nama_kabupaten'],
		"[form_provinsipelapor]"   	=> $config['nama_propinsi']
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
}
