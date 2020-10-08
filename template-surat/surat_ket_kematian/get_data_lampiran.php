<?php if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

	$this->load->model('keluarga_model');
	$this->load->model('pamong_model');

	$individu['umur'] = str_pad($individu['umur'], 3, "0", STR_PAD_LEFT);

	$ibu = $this->get_data_ibu($individu['id']);
	if ($ibu)
	{
		$input['nik_ibu'] = $ibu['nik'];
		$input['nama_ibu'] = $ibu['nama'];
		$input['tanggal_lahir_ibu']	= $ibu['tanggallahir'];
		$input['umur_ibu'] = str_pad($ibu['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_ibu'] = str_pad($ibu['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanibu'] = $ibu['pek'];
		$input['alamat_ibu'] = trim($ibu['alamat'].' '.$ibu['dusun']);
		$input['rt_ibu'] = $ibu['rt'];
		$input['rw_ibu'] = $ibu['rw'];
		$input['desaibu'] = $config['nama_desa'];
		$input['kecibu'] = $config['nama_kecamatan'];
		$input['kabibu'] = $config['nama_kabupaten'];
		$input['provinsiibu'] = $config['nama_propinsi'];
	}
	else
	{
		$input['pekerjaanid_ibu'] = str_pad($input['pekerjaanid_ibu'], 2, "0", STR_PAD_LEFT);
		$input['umur_ibu'] = str_pad($input['umur_ibu'], 3, "0", STR_PAD_LEFT);
	}

	$ayah = $this->get_data_ayah($individu['id']);
	if ($ayah)
	{
		$input['nik_ayah'] = $ayah['nik'];
		$input['nama_ayah'] = $ayah['nama'];
		$input['tanggal_lahir_ayah'] = $ayah['tanggallahir'];
		$input['umur_ayah']	= str_pad($ayah['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_ayah'] = str_pad($ayah['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanayah'] = $ayah['pek'];
		$input['alamat_ayah'] = trim($ayah['alamat'].' '.$ayah['dusun']);
		$input['rt_ayah'] = $ayah['rt'];
		$input['rw_ayah'] = $ayah['rw'];
		$input['desaayah'] = $config['nama_desa'];
		$input['kecayah'] = $config['nama_kecamatan'];
		$input['kabayah'] = $config['nama_kabupaten'];
		$input['provinsiayah'] = $config['nama_propinsi'];
	}
	else
	{
		$input['pekerjaanid_ayah'] = str_pad($input['pekerjaanid_ayah'], 2, "0", STR_PAD_LEFT);
		$input['umur_ayah']	= str_pad($input['umur_ayah'], 3, "0", STR_PAD_LEFT);
	}

	if ($input['id_pelapor'])
	{
		$pelapor = $this->get_data_surat($input['id_pelapor']);
		$input['nik_pelapor'] = $pelapor['nik'];
		$input['nama_pelapor'] = $pelapor['nama'];
		$input['tanggal_lahir_pelapor']	= $pelapor['tanggallahir'];
		$input['umur_pelapor'] = str_pad($pelapor['umur'], 3, "0", STR_PAD_LEFT);
		$input['jkpelapor']	= $pelapor['sex_id'];
		$input['pekerjaanid_pelapor'] = str_pad($pelapor['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanpelapor'] = $pelapor['pekerjaan'];
		$input['alamat_pelapor'] = trim($pelapor['alamat'].' '.$pelapor['dusun']);
		$input['rt_pelapor'] = $pelapor['rt'];
		$input['rw_pelapor'] = $pelapor['rw'];
		$input['desapelapor']	= $config['nama_desa'];
		$input['kecpelapor'] = $config['nama_kecamatan'];
		$input['kabpelapor'] = $config['nama_kabupaten'];
		$input['provinsipelapor']	= $config['nama_propinsi'];
	}
	else
	{
		$input['pekerjaanid_pelapor'] = str_pad($input['pekerjaanid_pelapor'], 2, "0", STR_PAD_LEFT);
		$input['umur_pelapor'] = str_pad($input['umur_pelapor'], 3, "0", STR_PAD_LEFT);
	}

	if ($input['id_saksi1'])
	{
		$saksi1 = $this->get_data_surat($input['id_saksi1']);
		$input['nik_saksi1'] = $saksi1['nik'];
		$input['nama_saksi1'] = $saksi1['nama'];
		$input['tanggal_lahir_saksi1'] = $saksi1['tanggallahir'];
		$input['umur_saksi1']	= str_pad($saksi1['umur'], 3, "0", STR_PAD_LEFT);
		$input['jksaksi1'] = $saksi1['sex_id'];
		$input['pekerjaanid_saksi1'] = str_pad($saksi1['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaansaksi1']	= $saksi1['pekerjaan'];
		$input['alamat_saksi1'] = trim($saksi1['alamat'].' '.$saksi1['dusun']);
		$input['rt_saksi1'] = $saksi1['rt'];
		$input['rw_saksi1'] = $saksi1['rw'];
		$input['desasaksi1'] = $config['nama_desa'];
		$input['kecsaksi1']	= $config['nama_kecamatan'];
		$input['kabsaksi1']	= $config['nama_kabupaten'];
		$input['provinsisaksi1'] = $config['nama_propinsi'];
	}
	else
	{
		$input['pekerjaanid_saksi1'] = str_pad($input['pekerjaanid_saksi1'], 2, "0", STR_PAD_LEFT);
		$input['umur_saksi1']	= str_pad($input['umur_saksi1'], 3, "0", STR_PAD_LEFT);
	}

	if ($input['id_saksi2'])
	{
		$saksi2 = $this->get_data_surat($input['id_saksi2']);
		$input['nik_saksi2'] = $saksi2['nik'];
		$input['nama_saksi2'] = $saksi2['nama'];
		$input['tanggal_lahir_saksi2'] = $saksi2['tanggallahir'];
		$input['umur_saksi2']	= str_pad($saksi2['umur'], 3, "0", STR_PAD_LEFT);
		$input['jksaksi2'] = $saksi2['sex_id'];
		$input['pekerjaanid_saksi2'] = str_pad($saksi2['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaansaksi2']	= $saksi2['pekerjaan'];
		$input['alamat_saksi2'] = trim($saksi2['alamat'].' '.$saksi2['dusun']);
		$input['rt_saksi2'] = $saksi2['rt'];
		$input['rw_saksi2'] = $saksi2['rw'];
		$input['desasaksi2'] = $config['nama_desa'];
		$input['kecsaksi2']	= $config['nama_kecamatan'];
		$input['kabsaksi2']	= $config['nama_kabupaten'];
		$input['provinsisaksi2'] = $config['nama_propinsi'];
	}
	else
	{
		$input['pekerjaanid_saksi2'] = str_pad($input['pekerjaanid_saksi2'], 2, "0", STR_PAD_LEFT);
		$input['umur_saksi2']	= str_pad($input['umur_saksi2'], 3, "0", STR_PAD_LEFT);
	}

	$id = $this->input->post('pamong_id');
	$kepala_desa = $this->pamong_model->get_pamong($id);
?>
