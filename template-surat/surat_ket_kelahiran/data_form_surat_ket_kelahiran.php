<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	$data['warganegara'] = $this->penduduk_model->list_warganegara();
	$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
	$data['sex'] = $this->penduduk_model->list_sex();
	$data['tempat_dilahirkan'] = $this->referensi_model->list_ref_flip(TEMPAT_DILAHIRKAN);
	$data['jenis_kelahiran'] = $this->referensi_model->list_ref_flip(JENIS_KELAHIRAN);
	$data['penolong_kelahiran'] = $this->referensi_model->list_ref_flip(PENOLONG_KELAHIRAN);
	$data['nomor'] = $this->input->post('nomor_main');
	$_SESSION['post'] = $_POST;
	$_SESSION['post']['nomor'] = $data['surat_terakhir']['no_surat_berikutnya'];

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

	if ($this->input->post('ibu')==2) unset($_SESSION['id_ibu']);
	if ($_POST['id_ibu'] != '' AND $_POST['id_ibu'] !='*')
	{
		$data['ibu']=$this->surat_model->get_penduduk($_POST['id_ibu']);
		$data['ayah'] = $this->surat_model->get_data_suami($_POST['id_ibu']);
		if ($data['ayah']) $data['ayah']['warganegara'] = $data['ayah']['wn']; // Karena diambil dari get_data_pribadi
		$_SESSION['id_ibu'] = $_POST['id_ibu'];
	}
	elseif ($_POST['id_ibu'] !='*' AND isset($_SESSION['id_ibu']))
	{
		$data['ibu'] = $this->surat_model->get_penduduk($_SESSION['id_ibu']);
		$data['ayah'] = $this->surat_model->get_data_suami($data['ibu']['id']);
	}
	else
	{
		unset($data['ibu']);
		unset($_SESSION['id_ibu']);
	}

	// Kalau ibu dari database, hanya tampilkan anak sebagai pilihan yang lahir
	if ($data['ibu'])
	{
		$data['anak'] = $this->surat_model->list_anak($data['ibu']['id']);
	}
	else
	{
		$data['anak'] = $data['penduduk'];
	}
	// Buat penduduk baru
	if ($this->input->post('penduduk_baru'))
	{
		/* Tulis data penduduk baru */
		$bayi = array();
		$kolom = array('sex','waktu_lahir','tempat_dilahirkan','tempatlahir','jenis_kelahiran','kelahiran_anak_ke','penolong_kelahiran','berat_lahir','panjang_lahir');
		foreach ($kolom as $item)
		{
			$bayi[$item] = $_POST[$item];
		}
		$bayi['nama'] = $_POST['nama_bayi'];
		$bayi['nik'] = $_POST['nik_bayi'];
		$bayi['id_kk'] = $data['ibu']['id_kk']; // Kalau bayi belum terdata, ibu harus dari database
		$bayi['kk_level'] = 4; // anak
		$bayi['id_cluster'] = $data['ibu']['id_cluster']; // Samakan dengan Dusun/RW/RT ibu
		$bayi['tanggallahir'] = tgl_indo_in($_POST['tanggallahir']);
		$bayi['status'] = 1;
		$bayi['created_by'] = $this->session->user;
		$this->db->insert('tweb_penduduk', $bayi);
		$id_bayi = $this->db->insert_id();
		$data['bayi'] = $this->surat_model->get_penduduk($id_bayi);
		$_SESSION['id_bayi'] = $id_bayi;
	}
	else
	{
		if ($this->input->post('bayi')==2) unset($_SESSION['id_bayi']);
		if ($_POST['id_bayi'] != '' AND $_POST['id_bayi'] !='*')
		{
			$data['bayi']=$this->surat_model->get_penduduk($_POST['id_bayi']);
			// Data kelahiran ditampilkan dan bisa diedit di form.
			// Reset kalau id berubah
			if ($_POST['id_bayi'] != $_SESSION['id_bayi'])
			{
				$_SESSION['post']['hari']	= hari(strtotime($bayi['tanggallahir']));
				$_SESSION['post']['tanggallahir'] = date('d-m-Y',strtotime($data['bayi']['tanggallahir']));
				$_SESSION['post']['waktu_lahir'] = $data['bayi']['waktu_lahir'];
				$_SESSION['post']['tempat_dilahirkan'] = $data['bayi']['tempat_dilahirkan'];
				$_SESSION['post']['tempatlahir'] = $data['bayi']['tempatlahir'];
				$_SESSION['post']['jenis_kelahiran'] = $data['bayi']['jenis_kelahiran'];
				$_SESSION['post']['kelahiran_anak_ke'] = $data['bayi']['kelahiran_anak_ke'];
				$_SESSION['post']['penolong_kelahiran'] = $data['bayi']['penolong_kelahiran'];
				$_SESSION['post']['berat_lahir'] = $data['bayi']['berat_lahir'];
				$_SESSION['post']['panjang_lahir'] = $data['bayi']['panjang_lahir'];
			}
			$_SESSION['id_bayi'] = $_POST['id_bayi'];
		}
		elseif ($_POST['id_bayi'] !='*' AND isset($_SESSION['id_bayi']))
		{
			$data['bayi']=$this->surat_model->get_penduduk($_SESSION['id_bayi']);
		}
		else
		{
			unset($data['bayi']);
			unset($_SESSION['id_bayi']);
		}
	}
?>