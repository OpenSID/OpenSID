<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	$this->load->model('pamong_model');

	/**
	* Kembalikan nama file lampiran yang akan digunakan, di mana
	* Surat Keterangan Pindah Penduduk ada beberapa pilihan format dan
	* pengguna bisa memilih format mana yang akan digunakan.
	*/
	if ($input['kode_format'] == 'F-1.23')
	{
		$input['judul_format'] = "Dalam Satu Desa/Kelurahan";
	}
	elseif ($input['kode_format'] == 'F-1.25')
	{
		$input['judul_format'] = "Antar Desa/Kelurahan Dalam Satu Kecamatan";
	}
	elseif ($input['kode_format'] =='F-1.29')
	{
		$input['judul_format'] = "Antar Kecamatan Dalam Satu Kabupaten/Kota";
	}
	elseif ($input['kode_format'] == 'F-1.34')
	{
		$input['judul_format'] = "Antar Kabupaten/Kota atau Antar Provinsi";
	}

	if ($input['kode_format'] == "f108")
		$daftar_lampiran = array($daftar_lampiran[0]);
	else
		$daftar_lampiran = array($daftar_lampiran[1]);

	$id = $this->input->post('pamong_id');
	$kepala_desa = $this->pamong_model->get_pamong($id);

?>
