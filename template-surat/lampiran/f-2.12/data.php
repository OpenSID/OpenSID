<?php

	defined('BASEPATH') or exit('No direct script access allowed');

	if ($individu)
	{
		$input['nik_pria'] = $individu['nik'];
		$input['kk_pria'] = $individu['no_kk'];
		$input['nama_pria'] = $individu['nama'];
		$input['tanggal_lahir_pria']	= $individu['tanggallahir'];
		$input['tempat_lahir_pria']	= $individu['tempatlahir'];
		$input['umur_pria'] = str_pad($individu['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_pria'] = str_pad($individu['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanpria'] = $individu['pekerjaan'];
		$input['alamat_pria'] = trim($individu['alamat'].' '.$individu['dusun']);
		$input['rt_pria'] = $individu['rt'];
		$input['rw_pria'] = $individu['rw'];
		$input['status_kawin_pria'] = $individu['status_kawin'];
		$input['wn_pria'] = $individu['warganegara'];
		$input['desapria'] = $config['nama_desa'];
		$input['kecpria'] = $config['nama_kecamatan'];
		$input['kabpria'] = $config['nama_kabupaten'];
		$input['provinsipria'] = $config['nama_propinsi'];
		$input['pendidikan_pria'] = $individu['pendidikan'];
		$input['agama_pria'] = $individu['agama'];
		$input['anak_ke_pria'] = $input['anak_ke'];
		$input['dokumen_pasport_pria'] = $input['paspor'];
		$input['telepon_pria'] = $input['telepon'];
		$input['penghayat_pria'] = $input['nama_organisasi_penghayat_kepercayaan'];
		$input['kawin_ke_pria'] = $input['perkawinan_ke'];
		$input['istri_ke_bagi_pria'] = $input['jika_beristri,_istri_ke'];
		$input['bangsa_pria'] = $input['kebangsaan_(bagi_wna)'];
	}
	
	$wanita = $this->surat_model->get_data_surat($input['id_pend_Calon_Pasangan_Wanita']);
	if ($wanita)
	{
		$input['nik_wanita'] = $wanita['nik'];
		$input['kk_wanita'] = $wanita['no_kk'];
		$input['nama_wanita'] = $wanita['nama'];
		$input['tanggal_lahir_wanita']	= $wanita['tanggallahir'];
		$input['tempat_lahir_wanita']	= $wanita['tempatlahir'];
		$input['umur_wanita'] = str_pad($wanita['umur'], 3, "0", STR_PAD_LEFT);
		$input['pekerjaanid_wanita'] = str_pad($wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanwanita'] = $wanita['pekerjaan'];
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
		$input['anak_ke_wanita'] = $input['anak_ke_Calon_Pasangan_Wanita'];
		$input['dokumen_pasport_wanita'] = $input['passport_Calon_Pasangan_Wanita'];
		$input['telepon_wanita'] = $input['telepon_Calon_Pasangan_Wanita'];
		$input['penghayat_wanita'] = $input['nama_organisasi_penghayat_kepercayaan_Calon_Pasangan_Wanita'];
		$input['kawin_ke_wanita'] = $input['perkawinan_ke_Calon_Pasangan_Wanita'];
		$input['bangsa_wanita'] = $input['kebangsaan_(bagi_wna)_Calon_Pasangan_Wanita'];
	}

	$ibu_pria = $this->surat_model->get_data_ibu($data->id_pend);
	$ibu_pria = $this->surat_model->get_data_surat($ibu_pria['id']);
	if ($ibu_pria)
	{
		$input['nik_ibu_pria'] = $ibu_pria['nik'];
		if (empty($ibu_pria['nik'])) {
			$input['nik_ibu_pria'] = $input['noktp_ibu_pria'];
		}
		$input['nama_ibu_pria'] = $ibu_pria['nama'];
		$input['tanggal_lahir_ibu_pria']	= $ibu_pria['tanggallahi'];
		$input['tempat_lahir_ibu_pria']	= $ibu_pria['tempatlahir'];
		$input['pekerjaanid_ibu_pria'] = str_pad($ibu_pria['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanibu_pria'] = $ibu_pria['pekerjaan'];
		$input['alamat_ibu_pria'] = trim($ibu_pria['alamat'].' '.$ibu_pria['dusun']);
		$input['rt_ibu_pria'] = $individu['rt'];
		$input['rw_ibu_pria'] = $individu['rw'];
		$input['desaibu_pria'] = $config['nama_desa'];
		$input['kecibu_pria'] = $config['nama_kecamatan'];
		$input['kabibu_pria'] = $config['nama_kabupaten'];
		$input['provinsiibu_pria'] = $config['nama_propinsi'];
		$input['agama_ibu_pria'] = $ibu_pria['agama'];
		$input['telepon_ibu_pria'] = $input['telepon_ibu'];
		$input['penghayat_ibu_pria'] = $input['nama_organisasi_penghayat_kepercayaan_ibu'];
	}

	$ayah_pria = $this->surat_model->get_data_ayah($data['id_pend']);
	$ayah_pria = $this->surat_model->get_data_surat($ayah_pria['id']);

	if ($ayah_pria)
	{
		$input['nik_ayah_pria'] = $ayah_pria['nik'];
		if (empty($ayah_pria['nik'])) {
			$input['nik_ayah_pria'] = $input['noktp_ayah_pria'];
		}
		$input['nama_ayah_pria'] = $ayah_pria['nama'];
		$input['tanggal_lahir_ayah_pria']	= $ayah_pria['tanggallahir'];
		$input['tempat_lahir_ayah_pria']	= $ayah_pria['tempatlahir'];
		$input['pekerjaanid_ayah_pria'] = str_pad($ayah_pria['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanayah_pria'] = $ayah_pria['pekerjaan'];
		$input['alamat_ayah_pria'] = trim($ayah_pria['alamat'].' '.$ayah_pria['dusun']);
		$input['rt_ayah_pria'] = $individu['rt'];
		$input['rw_ayah_pria'] = $individu['rw'];
		$input['desaayah_pria'] = $config['nama_desa'];
		$input['kecayah_pria'] = $config['nama_kecamatan'];
		$input['kabayah_pria'] = $config['nama_kabupaten'];
		$input['provinsiayah_pria'] = $config['nama_propinsi'];
		$input['agama_ayah_pria'] = $ayah_pria['agama'];
		$input['telepon_ayah_pria'] = $input['telepon_ayah'];
		$input['penghayat_ayah_pria'] = $input['nama_organisasi_penghayat_kepercayaan_ayah'];
	}

	$ibu_wanita = $this->surat_model->get_data_ibu($input['id_pend_Calon_Pasangan_Wanita']);
	$ibu_wanita = $this->surat_model->get_data_surat($ibu_wanita['id']);
	if ($ibu_wanita)
	{
		$input['nik_ibu_wanita'] = $ibu_wanita['nik'];
		if (empty($ibu_wanita['nik'])) {
			$input['nik_ibu_wanita'] = $input['noktp_ibu_wanita'];
		}
		$input['nama_ibu_wanita'] = $ibu_wanita['nama'];
		$input['tanggal_lahir_ibu_wanita']	= $ibu_wanita['tanggallahir'];
		$input['tempat_lahir_ibu_wanita']	= $ibu_wanita['tempatlahir'];
		$input['pekerjaanid_ibu_wanita'] = str_pad($ibu_wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanibu_wanita'] = $ibu_wanita['pekerjaan'];
		$input['alamat_ibu_wanita'] = trim($ibu_wanita['alamat'].' '.$ibu_wanita['dusun']);
		$input['rt_ibu_wanita'] = $wanita['rt'];
		$input['rw_ibu_wanita'] = $wanita['rw'];
		$input['desaibu_wanita'] = $config['nama_desa'];
		$input['kecibu_wanita'] = $config['nama_kecamatan'];
		$input['kabibu_wanita'] = $config['nama_kabupaten'];
		$input['provinsiibu_wanita'] = $config['nama_propinsi'];
		$input['agama_ibu_wanita'] = $ibu_wanita['agama_ibu'];
		$input['telepon_ibu_wanita'] = $input['telepon_ibu_Calon_Pasangan_Wanita'];
		$input['penghayat_ibu_wanita'] = $input['nama_organisasi_penghayat_kepercayaan_ibu_Calon_Pasangan_Wanita'];
	}

	$ayah_wanita = $this->surat_model->get_data_ayah($input['id_pend_Calon_Pasangan_Wanita']);
	$ayah_wanita = $this->surat_model->get_data_surat($ayah_wanita['id']);
	if ($ayah_wanita)
	{
		$input['nik_ayah_wanita'] = $ayah_wanita['nik'];
		if (empty($ayah_wanita['nik'])) {
			$input['nik_ayah_wanita'] = $input['noktp_ayah_wanita'];
		}
		$input['nama_ayah_wanita'] = $ayah_wanita['nama'];
		$input['tanggal_lahir_ayah_wanita']	= $ayah_wanita['tanggallahir'];
		$input['tempat_lahir_ayah_wanita']	= $ayah_wanita['tempatlahir'];
		$input['pekerjaanid_ayah_wanita'] = str_pad($ayah_wanita['pekerjaan_id'], 2, "0", STR_PAD_LEFT);
		$input['pekerjaanayah_wanita'] = $ayah_wanita['pekerjaan'];
		$input['alamat_ayah_wanita'] = trim($ayah_wanita['alamat'].' '.$ayah_wanita['dusun']);
		$input['rt_ayah_wanita'] = $wanita['rt'];
		$input['rw_ayah_wanita'] = $wanita['rw'];
		$input['desaayah_wanita'] = $config['nama_desa'];
		$input['kecayah_wanita'] = $config['nama_kecamatan'];
		$input['kabayah_wanita'] = $config['nama_kabupaten'];
		$input['provinsiayah_wanita'] = $config['nama_propinsi'];
		$input['agama_ayah_wanita'] = $ayah_wanita['agama'];
		$input['telepon_ayah_wanita'] = $input['telepon_ayah_Calon_Pasangan_Wanita'];
		$input['penghayat_ayah_wanita'] = $input['nama_organisasi_penghayat_kepercayaan_ayah_Calon_Pasangan_Wanita'];
	}

	$saksi1 = $this->surat_model->get_data_surat($input['id_pend_Saksi_I']);
	if ($saksi1)
	{
		$input['nik_saksi1'] = $saksi1['nik'];
		$input['nama_saksi1'] = $saksi1['nama'];
		$input['tanggal_lahir_saksi1']	= $saksi1['tanggallahir'];
		$input['tempat_lahir_saksi1']	= $saksi1['tempatlahir'];
		$input['pekerjaanid_saksi1'] = $saksi1['pekerjaan_id'];
		$input['pekerjaansaksi1'] = $saksi1['pekerjaan'];
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
		$input['telepon_saksi1'] = $input['telepon_Saksi_I'];
		$input['penghayat_saksi1'] = $input['nama_organisasi_penghayat_kepercayaan_Saksi_I'];
	}else{
		$input['nik_saksi1'] = $input['noktp_saksi1'];
		$input['nama_saksi1'] = $input['nama_saksi1'];
		$input['tanggal_lahir_saksi1']	= $input['tanggallahir_saksi1'];
		$input['tempat_lahir_saksi1']	= $input['tempatlahir_saksi1'];
		$input['pekerjaanid_saksi1'] = $input['pek_saksi1'];
		$input['pekerjaansaksi1'] = $input['pek_saksi1'];
		$input['alamat_saksi1'] = $input['alamat_saksi1'];
		$input['agama_saksi1'] = $input['agama_saksi1'];
		$input['telepon_saksi1'] = $input['telepon_saksi1'];
		$input['penghayat_saksi1'] = $input['penghayat_saksi1'];
		$input['rt_saksi1'] = $config['rt'];
		$input['rw_saksi1'] = $config['rw'];
		$input['desasaksi1'] = $config['nama_desa'];
		$input['kecsaksi1'] = $config['nama_kecamatan'];
		$input['kabsaksi1'] = $config['nama_kabupaten'];
		$input['provinsisaksi1'] = $config['nama_propinsi'];
	}

	$saksi2 = $this->surat_model->get_data_surat($input['id_pend_Saksi_II']);
	if ($saksi2)
	{
		$input['nik_saksi2'] = $saksi2['nik'];
		$input['nama_saksi2'] = $saksi2['nama'];
		$input['tanggal_lahir_saksi2']	= $saksi2['tanggallahir'];
		$input['tempat_lahir_saksi2']	= $saksi2['tempatlahir'];
		$input['pekerjaanid_saksi2'] = $saksi2['pekerjaan_id'];
		$input['pekerjaansaksi2'] = $saksi2['pekerjaan'];
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
		$input['telepon_saksi2'] = $input['telepon_Saksi_II'];
		$input['penghayat_saksi2'] = $input['nama_organisasi_penghayat_kepercayaan_Saksi_II'];
	}else{
		$input['nik_saksi2'] = $input['noktp_saksi2'];
		$input['nama_saksi2'] = $input['nama_saksi2'];
		$input['tanggal_lahir_saksi2']	= $input['tanggallahir_saksi2'];
		$input['tempat_lahir_saksi2']	= $input['tempatlahir_saksi2'];
		$input['pekerjaanid_saksi2'] = $input['pek_saksi2'];
		$input['pekerjaansaksi2'] = $input['pek_saksi2'];
		$input['alamat_saksi2'] = $input['alamat_saksi2'];
		$input['agama_saksi2'] = $input['agama_saksi2'];
		$input['telepon_saksi2'] = $input['telepon_saksi2'];
		$input['penghayat_saksi2'] = $input['penghayat_saksi2'];
		$input['rt_saksi2'] = $config['rt'];
		$input['rw_saksi2'] = $config['rw'];
		$input['desasaksi2'] = $config['nama_desa'];
		$input['kecsaksi2'] = $config['nama_kecamatan'];
		$input['kabsaksi2'] = $config['nama_kabupaten'];
		$input['provinsisaksi2'] = $config['nama_propinsi'];
	}

		$input['tanggal_pemberkatan'] = $input['tanggal_pemberkatan_perkawinan'];
		$input['tanggal_lapor'] = $input['hari,_tanggal_menikah'];
		$input['hari_lapor'] = hari($input['hari,_tanggal_menikah']);
		$input['jam_lapor'] = $input['jam_menikah'];
		$input['agama_kawin'] = $input['agama/penghayat_kepercayaan'];
		$input['penghayat_kawin'] = $input['nama_organisasi_penghayat_kepercayaan_kawin'];
		$input['badan_peradilan'] = $input['nama_badan_peradilan'];
		$input['nomor_putusan'] = $input['nomor_putusan_penetapan_pengadilan'];
		$input['tanggal_putusan'] = $input['tanggal_putusan_penetapan_pengadilan'];
		$input['nama_pemuka'] = $input['nama_pemuka_agama/pghyt_kepercayaan'];
		$input['ijin_putusan'] = $input['ijin_perwakilan_bagi_wna_/_nomor'];
		$input['jumlah_anak'] = $input['jumlah_anak_yang_telah_diakui_dan_disahkan'];
		$input['nama_anak1'] = $input['nama_anak_pertama'];
		$input['nama_anak2'] = $input['nama_anak_kedua'];
		$input['nama_anak3'] = $input['nama_anak_ketiga'];
		$input['nama_anak4'] = $input['nama_anak_ke_empat'];
		$input['nama_anak5'] = $input['nama_anak_ke_lima'];
		$input['nama_anak6'] = $input['nama_anak_ke_enam'];
		$input['no_akta_anak1'] = $input['no_akta_lahir_anak_pertama'];
		$input['no_akta_anak2'] = $input['no_akta_lahir_anak_kedua'];
		$input['no_akta_anak3'] = $input['no_akta_lahir_anak_ketiga'];
		$input['no_akta_anak4'] = $input['no_akta_lahir_anak_ke_empat'];
		$input['no_akta_anak5'] = $input['no_akta_lahir_anak_ke_lima'];
		$input['no_akta_anak6'] = $input['no_akta_lahir_anak_ke_enam'];
		$input['tgl_akta_anak1'] = $input['tanggal_lahir_anak_pertama'];
		$input['tgl_akta_anak2'] = $input['tanggal_lahir_anak_kedua'];
		$input['tgl_akta_anak3'] = $input['tanggal_lahir_anak_ketiga'];
		$input['tgl_akta_anak4'] = $input['tanggal_lahir_anak_ke_empat'];
		$input['tgl_akta_anak5'] = $input['tanggal_lahir_anak_ke_lima'];
		$input['tgl_akta_anak6'] = $input['tanggal_lahir_anak_ke_enam'];

?>
