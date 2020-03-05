<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	$data['selaku'] = array("Orang Tua","Suami","Istri","Keluarga");
	$data['yang_diberi_izin'] = array("Suami","Istri","Anak","Keluarga");
	$data['status_pekerjaan'] = array("Tenaga Kerja Indonesia (TKI)","Tenaga Kerja Wanita (TKW)");
	$str_desa = ucwords($this->setting->sebutan_desa.' '.$data['lokasi']['nama_desa']);
	$data['nomor'] = $this->input->post('nomor_main');
	$_SESSION['post'] = $_POST;
	$_SESSION['post']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];

	if ($_POST['id_diberi_izin'] != '' AND $_POST['id_diberi_izin'] !='*')
	{
		$data['diberi_izin']=$this->surat_model->get_penduduk($_POST['id_diberi_izin']);
		$_SESSION['id_diberi_izin'] = $_POST['id_diberi_izin'];
	}
	elseif ($_POST['id_diberi_izin'] !='*' AND isset($_SESSION['id_diberi_izin']))
	{
		$data['diberi_izin']=$this->surat_model->get_penduduk($_SESSION['id_diberi_izin']);
	}
	else
	{
		unset($data['diberi_izin']);
		unset($_SESSION['id_diberi_izin']);
	}
	if (isset($data['diberi_izin']))
	{
		if ($data['diberi_izin']['sex_id'] == '1')
			$data['status_diberi_izin'] = "Tenaga Kerja Indonesia (TKI)";
		else
			$data['status_diberi_izin'] = "Tenaga Kerja Wanita (TKW)";
	}

	if ($data['individu'])
	{
		// Tentukan penduduk yang diberi izin dari pilihan 'selaku'
		if ($_POST['selaku'] == 'Orang Tua')
		{
			$data['penduduk_diberi_izin'] = $this->surat_model->list_anak($_POST['nik']);
		}
		elseif ($_POST['selaku'] == 'Suami')
		{
			$istri = $this->surat_model->get_data_istri($_POST['nik']);
			$data['penduduk_diberi_izin'][] = array('id'=>$istri['id'],'nik'=>$istri['nik'],'nama'=>$istri['nama'],'info_pilihan_penduduk'=>$istri['info_pilihan_penduduk']);
		}
		elseif ($_POST['selaku'] == 'Istri')
		{
			$suami = $this->surat_model->get_data_suami($_POST['nik']);
			$data['penduduk_diberi_izin'][] = array('id'=>$suami['id'],'nik'=>$suami['nik'],'nama'=>$suami['nama'],'info_pilihan_penduduk'=>$suami['info_pilihan_penduduk']);

		}
		else
		{ //Keluarga
			$data['penduduk_diberi_izin'] = $data['penduduk'];
		}
	}
	else
	{
		$data['penduduk_diberi_izin'] = $data['penduduk'];
	}
?>