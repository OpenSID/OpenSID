<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	// Perlu disimpan di SESSION karena belum ketemu cara
	// memanggil flexbox memakai ajax atau menyimpan data
	// TODO: cari pengganti flexbox yang sudah tidak di-support lagi
	$_SESSION['post'] = $_POST;
	$_SESSION['post']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];
	if ($this->input->post('calon_pria') == 2) unset($_SESSION['id_pria']);
	if ($_POST['id_pria'] != '' AND $_POST['id_pria'] != '*')
	{
		$data['pria'] = $this->surat_model->get_penduduk($_POST['id_pria']);
		$_SESSION['id_pria'] = $_POST['id_pria'];
	}
	elseif ($_POST['id_pria'] != '*' AND isset($_SESSION['id_pria']))
	{
		$data['pria'] = $this->surat_model->get_penduduk($_SESSION['id_pria']);
	}
	else
	{
		unset($data['pria']);
		unset($_SESSION['id_pria']);
	}

	$data['calon_wanita_berbeda'] = true;
	if ($this->input->post('calon_wanita') == 2) unset($_SESSION['id_wanita']);
	if ($_POST['id_wanita'] != '' AND $_POST['id_wanita'] != '*'){
		if ($_POST['id_wanita'] == $_SESSION['id_wanita'])
			$data['calon_wanita_berbeda'] = false;
		$data['wanita'] = $this->surat_model->get_penduduk($_POST['id_wanita']);
		$_SESSION['id_wanita'] = $_POST['id_wanita'];
	}
	elseif ($_POST['id_wanita'] != '*' AND isset($_SESSION['id_wanita']))
	{
		$data['wanita'] = $this->surat_model->get_penduduk($_SESSION['id_wanita']);
	}
	else
	{
		unset($data['wanita']);
		unset($_SESSION['id_wanita']);
	}

	if ($_POST['id_wanita'] == '*')
	{
		unset($_SESSION['post']['nama_wali']);
		unset($_SESSION['post']['bin_wali']);
		unset($_SESSION['post']['tempatlahir_wali']);
		unset($_SESSION['post']['tanggallahir_wali']);
		unset($_SESSION['post']['wn_wali']);
		unset($_SESSION['post']['pek_wali']);
		unset($_SESSION['post']['alamat_wali']);
		unset($_SESSION['post']['hub_wali']);
	}
	$status_kawin_pria = array(
		"BELUM KAWIN" => "Jejaka",
		"KAWIN" => "Beristri",
		"CERAI HIDUP" => "Duda",
		"CERAI MATI" => "Duda");
	$status_kawin_wanita = array(
		"BELUM KAWIN" => "Perawan",
		"KAWIN" => "Bersuami",
		"CERAI HIDUP" => "Janda",
		"CERAI MATI" => "Janda");
	$data['warganegara'] = $this->penduduk_model->list_warganegara();
	$data['agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
	$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan();
	$data['laki'] = $this->surat_model->list_penduduk_laki();
	$data['nomor'] = $this->input->post('nomor_main');
	if (isset($_SESSION['id_pria']))
	{
		$id = $_SESSION['id_pria'];
		$data['ayah_pria'] = $this->surat_model->get_data_ayah($id);
		$data['ibu_pria'] = $this->surat_model->get_data_ibu($id);
	}
	if (isset($data['pria']))
	{
		$data['pria']['status_kawin_pria'] = $status_kawin_pria[$data['pria']['status_kawin']];
	}
	if (isset($_SESSION['id_wanita']))
	{
		$id = $_SESSION['id_wanita'];
		$data['ayah_wanita'] = $this->surat_model->get_data_ayah($id);
		$data['ibu_wanita'] = $this->surat_model->get_data_ibu($id);
	}
	if (isset($data['wanita']))
	{
		$data['wanita']['status_kawin_wanita'] = $status_kawin_wanita[$data['wanita']['status_kawin']];
	}

	$data['kode']['status_kawin_pria'] = array(
	  "Jejaka",
	  "Duda",
	  "Beristri"
	);
	$data['kode']['status_kawin_wanita'] = array(
	  "Perawan",
	  "Janda"
	);
?>