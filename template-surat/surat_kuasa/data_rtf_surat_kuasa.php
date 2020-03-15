<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

	/*
		Jika pemberi_kuasa warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if ($input['id_pemberi_kuasa'])
	{
		$pemberi_kuasa = $this->get_data_surat($input['id_pemberi_kuasa']);
		$array_replace = array(
	    "[nama_pemberi_kuasa]" => $pemberi_kuasa['nama'],
	    "[nik_pemberi_kuasa]" => $pemberi_kuasa['nik'],
			"[jkpemberi_kuasa]" => $pemberi_kuasa['sex'],
	    "[tempat_lahir_pemberi_kuasa]" => $pemberi_kuasa['tempatlahir'],
	  	"[tanggal_lahir_pemberi_kuasa]" => tgl_indo_dari_str($pemberi_kuasa['tanggallahir']),
	    "[umur_pemberi_kuasa]" => $pemberi_kuasa['umur'],
			"[warga_negara_pemberi_kuasa]" => $pemberi_kuasa['warganegara'],
			"[alamat_pemberi_kuasa]" => $pemberi_kuasa['alamat_wilayah'],
	    "[pekerjaanpemberi_kuasa]" => $pemberi_kuasa['pekerjaan'],
	    "[form_desapemberi_kuasa]" => $config['nama_desa'],
	    "[form_kecpemberi_kuasa]" => $config['nama_kecamatan'],
	    "[form_kabpemberi_kuasa]" => $config['nama_kabupaten'],
	    "[form_provinsipemberi_kuasa]" => $config['nama_propinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	/*
		Jika penerima_kuasa warga desa, ganti kolom isiannya dengan data dari database penduduk
	*/
	if ($input['id_penerima_kuasa'])
	{
		$penerima_kuasa = $this->get_data_surat($input['id_penerima_kuasa']);
		$array_replace = array(
	    "[nama_penerima_kuasa]" => $penerima_kuasa['nama'],
	    "[nik_penerima_kuasa]" => $penerima_kuasa['nik'],
			"[jkpenerima_kuasa]" => $penerima_kuasa['sex'],
	    "[tempat_lahir_penerima_kuasa]" => $penerima_kuasa['tempatlahir'],
	  	"[tanggal_lahir_penerima_kuasa]" => tgl_indo_dari_str($penerima_kuasa['tanggallahir']),
	    "[umur_penerima_kuasa]" => $penerima_kuasa['umur'],
			"[warga_negara_penerima_kuasa]" => $penerima_kuasa['warganegara'],
			"[alamat_penerima_kuasa]" => $penerima_kuasa['alamat_wilayah'],
	    "[pekerjaanpenerima_kuasa]" => $penerima_kuasa['pekerjaan'],
	    "[form_desapenerima_kuasa]" => $config['nama_desa'],
	    "[form_kecpenerima_kuasa]" => $config['nama_kecamatan'],
	    "[form_kabpenerima_kuasa]" => $config['nama_kabupaten'],
	    "[form_provinsipenerima_kuasa]" => $config['nama_propinsi']
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

?>