<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

		$data['warganegara'] = $this->penduduk_model->list_warganegara();
		$data['agama'] = $this->penduduk_model->list_agama();
		$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
		$data['sex'] = $this->penduduk_model->list_sex();

		$_SESSION['post'] = $_POST;

		if($this->input->post('saksi1')==2) unset($_SESSION['id_saksi1']);
		if($_POST['id_saksi1'] != '' AND $_POST['id_saksi1'] !='*'){
			$data['saksi1']=$this->surat_model->get_penduduk($_POST['id_saksi1']);
			$_SESSION['id_saksi1'] = $_POST['id_saksi1'];
		}elseif ($_POST['id_saksi1'] !='*' AND isset($_SESSION['id_saksi1'])){
			$data['saksi1']=$this->surat_model->get_penduduk($_SESSION['id_saksi1']);
		}else{
			unset($data['saksi1']);
			unset($_SESSION['id_saksi1']);
		}

		if($this->input->post('saksi2')==2) unset($_SESSION['id_saksi2']);
		if($_POST['id_saksi2'] != '' AND $_POST['id_saksi2'] !='*'){
			$data['saksi2']=$this->surat_model->get_penduduk($_POST['id_saksi2']);
			$_SESSION['id_saksi2'] = $_POST['id_saksi2'];
		}elseif ($_POST['id_saksi2'] !='*' AND isset($_SESSION['id_saksi2'])){
			$data['saksi2']=$this->surat_model->get_penduduk($_SESSION['id_saksi2']);
		}else{
			unset($data['saksi2']);
			unset($_SESSION['id_saksi2']);
		}

		if($this->input->post('pelapor')==2) unset($_SESSION['id_pelapor']);
		if($_POST['id_pelapor'] != '' AND $_POST['id_pelapor'] !='*'){
			$data['pelapor']=$this->surat_model->get_penduduk($_POST['id_pelapor']);
			$_SESSION['id_pelapor'] = $_POST['id_pelapor'];
		}elseif ($_POST['id_pelapor'] !='*' AND isset($_SESSION['id_pelapor'])){
			$data['pelapor']=$this->surat_model->get_penduduk($_SESSION['id_pelapor']);
		}else{
			unset($data['pelapor']);
			unset($_SESSION['id_pelapor']);
		}

		if($this->input->post('bayi')==2) unset($_SESSION['id_bayi']);
		if($_POST['id_bayi'] != '' AND $_POST['id_bayi'] !='*'){
			$data['bayi']=$this->surat_model->get_penduduk($_POST['id_bayi']);
			$_SESSION['id_bayi'] = $_POST['id_bayi'];
		}elseif ($_POST['id_bayi'] !='*' AND isset($_SESSION['id_bayi'])){
			$data['bayi']=$this->surat_model->get_penduduk($_SESSION['id_bayi']);
		}else{
			unset($data['bayi']);
			unset($_SESSION['id_bayi']);
		}
?>