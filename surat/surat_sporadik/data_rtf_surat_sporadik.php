<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/*
	Jika pemohon warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if ($input['nik'])
	{
		$array_replace = array(
		  "[nama_non_warga]"        => $individu['nama'],
	    "[tempatlahir_pemohon]"		=> $individu['tempatlahir'],
	    "[tanggallahir_pemohon]"	=> $individu['tanggallahir'],
	    "[umur_pemohon]"  				=> $individu['umur'],
	    "[pekerjaan_pemohon]" 		=> $individu['pekerjaan'],
	    "[nik_non_warga]"					=> $individu['nik'],
		  "[alamat_pemohon]"   			=> $individu['alamat_wilayah']." ".ucwords($this->setting->sebutan_desa)." ".$config['nama_desa']." ".ucwords($this->setting->sebutan_kecamatan)." ".$config['nama_kecamatan']." ".ucwords($this->setting->sebutan_kabupaten)." ".$config['nama_kabupaten']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	else
	{
		$array_replace = array(
      "[umur_pemohon]"  				=> umur($input['tanggallahir_pemohon'])
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	/*
		Jika saksi1 warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if ($input['id_saksi1'])
	{
		$saksi1 = $this->get_data_surat($input['id_saksi1']);
		$array_replace = array(
	    "[namasaksii]"        		=> $saksi1['nama'],
	    "[umursaksii]"  					=> $saksi1['umur'],
	    "[pekerjaansaksii]" 			=> $saksi1['pekerjaan'],
	    "[alamatsaksii]"   				=> $saksi1['alamat_wilayah']." ".ucwords($this->setting->sebutan_desa)." ".$config['nama_desa']." ".ucwords($this->setting->sebutan_kecamatan)." ".$config['nama_kecamatan']." ".ucwords($this->setting->sebutan_kabupaten)." ".$config['nama_kabupaten']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	/*
		Jika saksi2 warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if ($input['id_saksi2'])
	{
		$saksi2 = $this->get_data_surat($input['id_saksi2']);
		$array_replace = array(
	    "[namasaksiii]"        		=> $saksi2['nama'],
	    "[umursaksiii]"  					=> $saksi2['umur'],
	    "[pekerjaansaksiii]" 			=> $saksi2['pekerjaan'],
	    "[alamatsaksiii]"  				=> $saksi2['alamat_wilayah']." ".ucwords($this->setting->sebutan_desa)." ".$config['nama_desa']." ".ucwords($this->setting->sebutan_kecamatan)." ".$config['nama_kecamatan']." ".ucwords($this->setting->sebutan_kabupaten)." ".$config['nama_kabupaten']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

?>
