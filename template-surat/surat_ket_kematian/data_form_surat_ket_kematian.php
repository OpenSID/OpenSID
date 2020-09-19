<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	$data['jenazah'] = $data['individu'];
	$data['mati'] = $this->penduduk_model->list_penduduk_status_dasar(2); // status mati
	$data['warganegara'] = $this->penduduk_model->list_warganegara();
	$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
	$data['sex'] = $this->penduduk_model->list_sex();
	$data['sebab'] = array(
		"1" => "Sakit biasa / tua",
		"2" => "Wabah Penyakit",
		"3" => "Kecelakaan",
		"4" => "Kriminalitas",
		"5" => "Bunuh Diri",
		"6" => "Lainnya"
	);
	$data['penolong_mati'] = array(
		"1" => "Dokter",
		"2" => "Tenaga Kesehatan",
		"3" => "Kepolisian",
		"4" => "Lainnya"
	);

	$_SESSION['post'] = $_POST;
	$_SESSION['post']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];

	$data['ibu'] = $this->surat_model->get_data_ibu($data['individu']['id']);
	$data['ayah'] = $this->surat_model->get_data_ayah($data['individu']['id']);

	if ($this->input->post('saksi1')==2) unset($_SESSION['id_saksi1']);
	if ($_POST['id_saksi1'] != '' AND $_POST['id_saksi1'] !='*')
	{
		$data['saksi1']=$this->surat_model->get_penduduk($_POST['id_saksi1']);
		$_SESSION['id_saksi1'] = $_POST['id_saksi1'];
	}
	elseif ($_POST['id_saksi1'] !='*' AND isset($_SESSION['id_saksi1']))
	{
		$data['saksi1']=$this->surat_model->get_penduduk($_SESSION['id_saksi1']);
	}
	else
	{
		unset($data['saksi1']);
		unset($_SESSION['id_saksi1']);
	}

	if ($this->input->post('saksi2')==2) unset($_SESSION['id_saksi2']);
	if ($_POST['id_saksi2'] != '' AND $_POST['id_saksi2'] !='*')
	{
		$data['saksi2']=$this->surat_model->get_penduduk($_POST['id_saksi2']);
		$_SESSION['id_saksi2'] = $_POST['id_saksi2'];
	}
	elseif ($_POST['id_saksi2'] !='*' AND isset($_SESSION['id_saksi2']))
	{
		$data['saksi2']=$this->surat_model->get_penduduk($_SESSION['id_saksi2']);
	}
	else
	{
		unset($data['saksi2']);
		unset($_SESSION['id_saksi2']);
	}

	if ($this->input->post('pelapor')==2) unset($_SESSION['id_pelapor']);
	if ($_POST['id_pelapor'] != '' AND $_POST['id_pelapor'] !='*')
	{
		$data['pelapor']=$this->surat_model->get_penduduk($_POST['id_pelapor']);
		$_SESSION['id_pelapor'] = $_POST['id_pelapor'];
	}
	elseif ($_POST['id_pelapor'] !='*' AND isset($_SESSION['id_pelapor']))
	{
		$data['pelapor']=$this->surat_model->get_penduduk($_SESSION['id_pelapor']);
	}
	else
	{
		unset($data['pelapor']);
		unset($_SESSION['id_pelapor']);
	}
?>