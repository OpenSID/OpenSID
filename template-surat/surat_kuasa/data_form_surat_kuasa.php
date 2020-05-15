<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

	$data['warganegara'] = $this->penduduk_model->list_warganegara();
	$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
	$data['sex'] = $this->penduduk_model->list_sex();

	$_SESSION['post'] = $_POST;
	$_SESSION['post']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];

	// -- Penerima Kuasa
	if ($this->input->post('penerima_kuasa') == 2) unset($_SESSION['id_penerima_kuasa']);
	if ($_POST['id_penerima_kuasa'] != '' AND $_POST['id_penerima_kuasa'] !='*')
	{
		$data['penerima_kuasa'] = $this->surat_model->get_penduduk($_POST['id_penerima_kuasa']);
		$_SESSION['id_penerima_kuasa'] = $_POST['id_penerima_kuasa'];
	}
	elseif ($_POST['id_penerima_kuasa'] !='*' AND isset($_SESSION['id_penerima_kuasa']))
	{
		$data['penerima_kuasa'] = $this->surat_model->get_penduduk($_SESSION['id_penerima_kuasa']);
	}
	else
	{
		unset($data['penerima_kuasa']);
		unset($_SESSION['id_penerima_kuasa']);
	}
	// -- Akhir Penerima Kuasa

	// -- Pemberi Kuasa
	if ($this->input->post('pemberi_kuasa') == 2) unset($_SESSION['id_pemberi_kuasa']);
	if ($_POST['id_pemberi_kuasa'] != '' AND $_POST['id_pemberi_kuasa'] !='*')
	{
		$data['pemberi_kuasa'] = $this->surat_model->get_penduduk($_POST['id_pemberi_kuasa']);
		$_SESSION['id_pemberi_kuasa'] = $_POST['id_pemberi_kuasa'];
	}
	elseif ($_POST['id_pemberi_kuasa'] !='*' AND isset($_SESSION['id_pemberi_kuasa']))
	{
		$data['pemberi_kuasa'] = $this->surat_model->get_penduduk($_SESSION['id_pemberi_kuasa']);
	}
	else
	{
		unset($data['pemberi_kuasa']);
		unset($_SESSION['id_pemberi_kuasa']);
	}
	// -- Akhir Pemberi Kuasa

?>