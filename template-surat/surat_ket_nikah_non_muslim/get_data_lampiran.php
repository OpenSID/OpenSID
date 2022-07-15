<?php

	defined('BASEPATH') or exit('No direct script access allowed');

	$this->load->model('pamong_model');
	$id = $this->input->post('pamong_id');
	$kepala_desa = $this->pamong_model->get_data($id);
	

	$pria = $this->get_data_surat($_POST['id_pria']);
	if ($pria)
	{
		$input['nik_pria'] = $pria['nik'];
		$input['kk_pria'] = $pria['no_kk'];
		$input['nama_pria'] = $pria['nama'];
		$input['tanggal_lahir_pria']	= $pria['tanggallahir'];
		$input['tempat_lahir_pria']	= $pria['tempatlahir'];
		$input['umur_pria'] = str_pad($pria['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_pria'] = str_pad($pria['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanpria'] = $pria['pek'];
		$input['alamat_pria'] = trim($pria['alamat'].' '.$pria['dusun']);
		$input['rt_pria'] = $pria['rt'];
		$input['rw_pria'] = $pria['rw'];
		$input['status_kawin_pria'] = $pria['status_kawin'];
		$input['wn_pria'] = $pria['warganegara'];
		$input['desapria'] = $config['nama_desa'];
		$input['kecpria'] = $config['nama_kecamatan'];
		$input['kabpria'] = $config['nama_kabupaten'];
		$input['provinsipria'] = $config['nama_propinsi'];
		$input['pendidikan_pria'] = $pria['pendidikan'];
		$input['agama_pria'] = $pria['agama'];
		$input['anak_ke_pria'] = $_POST['anak_ke_pria'];
		$input['dokumen_pasport_pria'] = $_POST['paspor_pria'];
		$input['telepon_pria'] = $_POST['telepon_pria'];
		$input['penghayat_pria'] = $_POST['penghayat_pria'];
		$input['kawin_ke_pria'] = $_POST['kawin_ke_pria'];
		$input['istri_ke_bagi_pria'] = $_POST['istri_ke'];
		$input['bangsa_pria'] = $_POST['bangsa_pria'];
	}

	$wanita = $this->get_data_surat($_POST['id_wanita']);
	if ($wanita)
	{
		$input['nik_wanita'] = $wanita['nik'];
		$input['kk_wanita'] = $wanita['no_kk'];
		$input['nama_wanita'] = $wanita['nama'];
		$input['tanggal_lahir_wanita']	= $wanita['tanggallahir'];
		$input['tempat_lahir_wanita']	= $wanita['tempatlahir'];
		$input['umur_wanita'] = str_pad($wanita['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_wanita'] = str_pad($wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanwanita'] = $wanita['pek'];
		$input['alamat_wanita'] = trim($wanita['alamat'].' '.$wanita['dusun']);
		$input['rt_wanita'] = $wanita['rt'];
		$input['rw_wanita'] = $wanita['rw'];
		$input['anak_ke_wanita'] = $wanita['kelahiran_anak_ke'];
		$input['status_kawin_wanita'] = $wanita['status_kawin'];
		$input['wn_wanita'] = $wanita['warganegara'];
		$input['desawanita'] = $config['nama_desa'];
		$input['kecwanita'] = $config['nama_kecamatan'];
		$input['kabwanita'] = $config['nama_kabupaten'];
		$input['provinsiwanita'] = $config['nama_propinsi'];
		$input['pendidikan_wanita'] = $wanita['pendidikan'];
		$input['agama_wanita'] = $wanita['agama'];

		$input['anak_ke_wanita'] = $_POST['anak_ke_wanita'];
		$input['dokumen_pasport_wanita'] = $_POST['paspor_wanita'];
		$input['telepon_wanita'] = $_POST['telepon_wanita'];
		$input['penghayat_wanita'] = $_POST['penghayat_wanita'];
		$input['kawin_ke_wanita'] = $_POST['kawin_ke_wanita'];
		$input['bangsa_wanita'] = $_POST['bangsa_wanita'];
	}

	$ibu_pria = $this->get_data_ibu($_POST['id_pria']);
	if ($ibu_pria)
	{
		$input['nik_ibu_pria'] = $ibu_pria['nik'];
		if (empty($ibu_pria['nik'])) {
			$input['nik_ibu_pria'] = $_POST['noktp_ibu_pria'];
		}
		$input['nama_ibu_pria'] = $ibu_pria['nama'];
		$input['tanggal_lahir_ibu_pria']	= $_POST['tanggallahir_ibu_pria'];
		$input['tempat_lahir_ibu_pria']	= $_POST['tempatlahir_ibu_pria'];
		$input['pekerjaanid_ibu_pria'] = str_pad($ibu_pria['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanibu_pria'] = $ibu_pria['pek'];
		$input['alamat_ibu_pria'] = $_POST['alamat_ibu_pria'];
		$input['rt_ibu_pria'] = $pria['rt'];
		$input['rw_ibu_pria'] = $pria['rw'];
		$input['desaibu_pria'] = $config['nama_desa'];
		$input['kecibu_pria'] = $config['nama_kecamatan'];
		$input['kabibu_pria'] = $config['nama_kabupaten'];
		$input['provinsiibu_pria'] = $config['nama_propinsi'];
		$input['agama_ibu_pria'] = $_POST['agama_ibu_pria'];
		$input['telepon_ibu_pria'] = $_POST['telepon_ibu_pria'];
		$input['penghayat_ibu_pria'] = $_POST['penghayat_ibu_pria'];
	}

	$ayah_pria = $this->get_data_ayah($_POST['id_pria']);
	if ($ayah_pria)
	{
		$input['nik_ayah_pria'] = $ayah_pria['nik'];
		if (empty($ayah_pria['nik'])) {
			$input['nik_ayah_pria'] = $_POST['noktp_ayah_pria'];
		}
		$input['nama_ayah_pria'] = $ayah_pria['nama'];
		$input['tanggal_lahir_ayah_pria']	= $_POST['tanggallahir_ayah_pria'];
		$input['tempat_lahir_ayah_pria']	= $_POST['tempatlahir_ayah_pria'];
		$input['pekerjaanid_ayah_pria'] = str_pad($ayah_pria['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanayah_pria'] = $ayah_pria['pek'];
		$input['alamat_ayah_pria'] = $_POST['alamat_ayah_pria'];
		$input['rt_ayah_pria'] = $pria['rt'];
		$input['rw_ayah_pria'] = $pria['rw'];
		$input['desaayah_pria'] = $config['nama_desa'];
		$input['kecayah_pria'] = $config['nama_kecamatan'];
		$input['kabayah_pria'] = $config['nama_kabupaten'];
		$input['provinsiayah_pria'] = $config['nama_propinsi'];
		$input['agama_ayah_pria'] = $_POST['agama_ayah_pria'];
		$input['telepon_ayah_pria'] = $_POST['telepon_ayah_pria'];
		$input['penghayat_ayah_pria'] = $_POST['penghayat_ayah_pria'];
	}

	$ibu_wanita = $this->get_data_ibu($_POST['id_wanita']);
	if ($ibu_wanita)
	{
		$input['nik_ibu_wanita'] = $ibu_wanita['nik'];
		if (empty($ibu_wanita['nik'])) {
			$input['nik_ibu_wanita'] = $_POST['noktp_ibu_wanita'];
		}
		$input['nama_ibu_wanita'] = $ibu_wanita['nama'];
		$input['tanggal_lahir_ibu_wanita']	= $_POST['tanggallahir_ibu_wanita'];
		$input['tempat_lahir_ibu_wanita']	= $_POST['tempatlahir_ibu_wanita'];
		$input['pekerjaanid_ibu_wanita'] = str_pad($ibu_wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanibu_wanita'] = $ibu_wanita['pek'];
		$input['alamat_ibu_wanita'] = $_POST['alamat_ibu_wanita'];
		$input['rt_ibu_wanita'] = $wanita['rt'];
		$input['rw_ibu_wanita'] = $wanita['rw'];
		$input['desaibu_wanita'] = $config['nama_desa'];
		$input['kecibu_wanita'] = $config['nama_kecamatan'];
		$input['kabibu_wanita'] = $config['nama_kabupaten'];
		$input['provinsiibu_wanita'] = $config['nama_propinsi'];
		$input['agama_ibu_wanita'] = $_POST['agama_ibu_wanita'];
		$input['telepon_ibu_wanita'] = $_POST['telepon_ibu_wanita'];
		$input['penghayat_ibu_wanita'] = $_POST['penghayat_ibu_wanita'];
	}

	$ayah_wanita = $this->get_data_ayah($_POST['id_wanita']);
	if ($ayah_wanita)
	{
		$input['nik_ayah_wanita'] = $ayah_wanita['nik'];
		if (empty($ayah_wanita['nik'])) {
			$input['nik_ayah_wanita'] = $_POST['noktp_ayah_wanita'];
		}
		$input['nama_ayah_wanita'] = $ayah_wanita['nama'];
		$input['tanggal_lahir_ayah_wanita']	= $_POST['tanggallahir_ayah_wanita'];
		$input['tempat_lahir_ayah_wanita']	= $_POST['tempatlahir_ayah_wanita'];
		$input['pekerjaanid_ayah_wanita'] = str_pad($ayah_wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanayah_wanita'] = $ayah_wanita['pek'];
		$input['alamat_ayah_wanita'] = $_POST['alamat_ayah_wanita'];
		$input['rt_ayah_wanita'] = $wanita['rt'];
		$input['rw_ayah_wanita'] = $wanita['rw'];
		$input['desaayah_wanita'] = $config['nama_desa'];
		$input['kecayah_wanita'] = $config['nama_kecamatan'];
		$input['kabayah_wanita'] = $config['nama_kabupaten'];
		$input['provinsiayah_wanita'] = $config['nama_propinsi'];
		$input['agama_ayah_wanita'] = $_POST['agama_ayah_wanita'];
		$input['telepon_ayah_wanita'] = $_POST['telepon_ayah_wanita'];
		$input['penghayat_ayah_wanita'] = $_POST['penghayat_ayah_wanita'];
	}

	$saksi1 = $this->get_data_surat($_POST['id_saksi1']);
	if ($saksi1)
	{
		$input['nik_saksi1'] = $saksi1['nik'];
		$input['nama_saksi1'] = $saksi1['nama'];
		$input['tanggal_lahir_saksi1']	= $saksi1['tanggallahir'];
		$input['tempat_lahir_saksi1']	= $saksi1['tempatlahir'];
		$input['pekerjaanid_saksi1'] = $saksi1['pekerjaan_id'];
		$input['pekerjaansaksi1'] = $saksi1['pek'];
		$input['alamat_saksi1'] = trim($saksi1['alamat'].' '.$saksi1['dusun']);
		$input['rt_saksi1'] = $saksi1['rt'];
		$input['rw_saksi1'] = $saksi1['rw'];
		$input['status_kawin_saksi1'] = $saksi1['status_kawin'];
		$input['wn_saksi1'] = $saksi1['warganegara'];
		$input['desasaksi1'] = $config['nama_desa'];
		$input['kecsaksi1'] = $config['nama_kecamatan'];
		$input['kabsaksi1'] = $config['nama_kabupaten'];
		$input['provinsisaksi1'] = $config['nama_propinsi'];
		$input['pendidikan_saksi1'] = $saksi1['pendidikan'];
		$input['agama_saksi1'] = $saksi1['agama'];
		$input['anak_ke_saksi1'] = $_POST['anak_ke_saksi1'];
		$input['dokumen_pasport_saksi1'] = $_POST['paspor_saksi1'];
		$input['telepon_saksi1'] = $_POST['telepon_saksi1'];
		$input['penghayat_saksi1'] = $_POST['penghayat_saksi1'];
		$input['kawin_ke_saksi1'] = $_POST['kawin_ke_saksi1'];
		$input['istri_ke_bagi_saksi1'] = $_POST['istri_ke'];
		$input['bangsa_saksi1'] = $_POST['bangsa_saksi1'];
	}else{
		$input['nik_saksi1'] = $_POST['noktp_saksi1'];
		$input['nama_saksi1'] = $_POST['nama_saksi1'];
		$input['tanggal_lahir_saksi1']	= $_POST['tanggallahir_saksi1'];
		$input['tempat_lahir_saksi1']	= $_POST['tempatlahir_saksi1'];
		$input['pekerjaanid_saksi1'] = $_POST['pek_saksi1'];
		$input['pekerjaansaksi1'] = $_POST['pek_saksi1'];
		$input['alamat_saksi1'] = $_POST['alamat_saksi1'];
		$input['agama_saksi1'] = $_POST['agama_saksi1'];
		$input['telepon_saksi1'] = $_POST['telepon_saksi1'];
		$input['penghayat_saksi1'] = $_POST['penghayat_saksi1'];
		$input['rt_saksi1'] = $config['rt'];
		$input['rw_saksi1'] = $config['rw'];
		$input['desasaksi1'] = $config['nama_desa'];
		$input['kecsaksi1'] = $config['nama_kecamatan'];
		$input['kabsaksi1'] = $config['nama_kabupaten'];
		$input['provinsisaksi1'] = $config['nama_propinsi'];
	}

	$saksi2 = $this->get_data_surat($_POST['id_saksi2']);
	if ($saksi2)
	{
		$input['nik_saksi2'] = $saksi2['nik'];
		$input['nama_saksi2'] = $saksi2['nama'];
		$input['tanggal_lahir_saksi2']	= $saksi2['tanggallahir'];
		$input['tempat_lahir_saksi2']	= $saksi2['tempatlahir'];
		$input['pekerjaanid_saksi2'] = $saksi2['pekerjaan_id'];
		$input['pekerjaansaksi2'] = $saksi2['pek'];
		$input['alamat_saksi2'] = trim($saksi2['alamat'].' '.$saksi2['dusun']);
		$input['rt_saksi2'] = $saksi2['rt'];
		$input['rw_saksi2'] = $saksi2['rw'];
		$input['status_kawin_saksi2'] = $saksi2['status_kawin'];
		$input['wn_saksi2'] = $saksi2['warganegara'];
		$input['desasaksi2'] = $config['nama_desa'];
		$input['kecsaksi2'] = $config['nama_kecamatan'];
		$input['kabsaksi2'] = $config['nama_kabupaten'];
		$input['provinsisaksi2'] = $config['nama_propinsi'];
		$input['pendidikan_saksi2'] = $saksi2['pendidikan'];
		$input['agama_saksi2'] = $saksi2['agama'];
		$input['anak_ke_saksi2'] = $_POST['anak_ke_saksi2'];
		$input['dokumen_pasport_saksi2'] = $_POST['paspor_saksi2'];
		$input['telepon_saksi2'] = $_POST['telepon_saksi2'];
		$input['penghayat_saksi2'] = $_POST['penghayat_saksi2'];
		$input['kawin_ke_saksi2'] = $_POST['kawin_ke_saksi2'];
		$input['istri_ke_bagi_saksi2'] = $_POST['istri_ke'];
		$input['bangsa_saksi2'] = $_POST['bangsa_saksi2'];
	}else{
		$input['nik_saksi2'] = $_POST['noktp_saksi2'];
		$input['nama_saksi2'] = $_POST['nama_saksi2'];
		$input['tanggal_lahir_saksi2']	= $_POST['tanggallahir_saksi2'];
		$input['tempat_lahir_saksi2']	= $_POST['tempatlahir_saksi2'];
		$input['pekerjaanid_saksi2'] = $_POST['pek_saksi2'];
		$input['pekerjaansaksi2'] = $_POST['pek_saksi2'];
		$input['alamat_saksi2'] = $_POST['alamat_saksi2'];
		$input['agama_saksi2'] = $_POST['agama_saksi2'];
		$input['telepon_saksi2'] = $_POST['telepon_saksi2'];
		$input['penghayat_saksi2'] = $_POST['penghayat_saksi2'];
		$input['rt_saksi2'] = $config['rt'];
		$input['rw_saksi2'] = $config['rw'];
		$input['desasaksi2'] = $config['nama_desa'];
		$input['kecsaksi2'] = $config['nama_kecamatan'];
		$input['kabsaksi2'] = $config['nama_kabupaten'];
		$input['provinsisaksi2'] = $config['nama_propinsi'];
	}

	// $saksi1 = $this->get_data_pribadi($_POST['id_saksi1']);
	// if ($saksi1)
	// {
	// 	$input['nik_saksi1'] = $_POST['noktp_saksi1'];
	// 	$input['nama_saksi1'] = $_POST['nama_saksi1'];
	// 	$input['tanggal_lahir_saksi1']	= $_POST['tanggallahir_saksi1'];
	// 	$input['tempat_lahir_saksi1']	= $_POST['tempatlahir_saksi1'];
	// 	$input['pekerjaanid_saksi1'] = $_POST['pek_saksi1'];
	// 	$input['pekerjaansaksi1'] = $_POST['pek_saksi1'];
	// 	$input['alamat_saksi1'] = $_POST['alamat_saksi1'];
	// 	$input['agama_saksi1'] = $_POST['agama_saksi1'];
	// 	$input['telepon_saksi1'] = $_POST['telepon_saksi1'];
	// 	$input['penghayat_saksi1'] = $_POST['penghayat_saksi1'];
	// 	$input['rt_saksi1'] = $config['rt'];
	// 	$input['rw_saksi1'] = $config['rw'];
	// 	$input['desasaksi1'] = $config['nama_desa'];
	// 	$input['kecsaksi1'] = $config['nama_kecamatan'];
	// 	$input['kabsaksi1'] = $config['nama_kabupaten'];
	// 	$input['provinsisaksi1'] = $config['nama_propinsi'];
	// }

	// $saksi2 = $this->get_data_pribadi($_POST['id_saksi2']);
	// if ($saksi2)
	// {
	// 	$input['nik_saksi2'] = $_POST['noktp_saksi2'];
	// 	$input['nama_saksi2'] = $_POST['nama_saksi2'];
	// 	$input['tanggal_lahir_saksi2']	= $_POST['tanggallahir_saksi2'];
	// 	$input['tempat_lahir_saksi2']	= $_POST['tempatlahir_saksi2'];
	// 	$input['pekerjaanid_saksi2'] = $_POST['pek_saksi2'];
	// 	$input['pekerjaansaksi2'] = $_POST['pek_saksi2'];
	// 	$input['alamat_saksi2'] = $_POST['alamat_saksi2'];
	// 	$input['agama_saksi2'] = $_POST['agama_saksi2'];
	// 	$input['telepon_saksi2'] = $_POST['telepon_saksi2'];
	// 	$input['penghayat_saksi2'] = $_POST['penghayat_saksi2'];
	// 	$input['rt_saksi2'] = $config['rt'];
	// 	$input['rw_saksi2'] = $config['rw'];
	// 	$input['desasaksi2'] = $config['nama_desa'];
	// 	$input['kecsaksi2'] = $config['nama_kecamatan'];
	// 	$input['kabsaksi2'] = $config['nama_kabupaten'];
	// 	$input['provinsisaksi2'] = $config['nama_propinsi'];
	// }

		$input['tanggal_pemberkatan'] = $_POST['tanggal_pemberkatan'];
		$input['tanggal_lapor'] = $_POST['tanggal_nikah'];
		$input['hari_lapor'] = $_POST['hari_nikah'];
		$input['jam_lapor'] = $_POST['jam_nikah'];
		$input['agama_kawin'] = $_POST['agama_kawin'];
		$input['penghayat_kawin'] = $_POST['penghayat_kawin'];
		$input['badan_peradilan'] = $_POST['badan_peradilan'];
		$input['nomor_putusan'] = $_POST['nomor_putusan'];
		$input['tanggal_putusan'] = $_POST['tanggal_putusan'];
		$input['nama_pemuka'] = $_POST['nama_pemuka'];
		$input['ijin_putusan'] = $_POST['ijin_putusan'];
		$input['jumlah_anak'] = $_POST['jumlah_anak'];
		$input['nama_anak1'] = $_POST['nama_anak1'];
		$input['nama_anak2'] = $_POST['nama_anak2'];
		$input['nama_anak3'] = $_POST['nama_anak3'];
		$input['nama_anak4'] = $_POST['nama_anak4'];
		$input['nama_anak5'] = $_POST['nama_anak5'];
		$input['nama_anak6'] = $_POST['nama_anak6'];
		$input['no_akta_anak1'] = $_POST['no_akta_anak1'];
		$input['no_akta_anak2'] = $_POST['no_akta_anak2'];
		$input['no_akta_anak3'] = $_POST['no_akta_anak3'];
		$input['no_akta_anak4'] = $_POST['no_akta_anak4'];
		$input['no_akta_anak5'] = $_POST['no_akta_anak5'];
		$input['no_akta_anak6'] = $_POST['no_akta_anak6'];
		$input['tgl_akta_anak1'] = $_POST['tgl_akta_anak1'];
		$input['tgl_akta_anak2'] = $_POST['tgl_akta_anak2'];
		$input['tgl_akta_anak3'] = $_POST['tgl_akta_anak3'];
		$input['tgl_akta_anak4'] = $_POST['tgl_akta_anak4'];
		$input['tgl_akta_anak5'] = $_POST['tgl_akta_anak5'];
		$input['tgl_akta_anak6'] = $_POST['tgl_akta_anak6'];

?>
