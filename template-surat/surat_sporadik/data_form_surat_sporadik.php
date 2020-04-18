<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
		$data['nomor'] = $this->input->post('nomor_main');
		$_SESSION['post'] = $_POST;
		if ($this->input->post('id_pemohon') == '*')
		{
			// Hapus $individu sebagai pemohon karena bukan warga desa
			unset($data['nik']);
			unset($_SESSION['post']['nik']);
			unset($data['individu']);
		}
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

?>