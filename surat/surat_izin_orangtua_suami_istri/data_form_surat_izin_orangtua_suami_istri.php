<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

		$data['selaku'] = array("Orang Tua","Suami","Istri","Keluarga");
		$data['yang_diberi_izin'] = array("Suami","Istri","Anak","Keluarga");
		$data['nomor'] = $this->input->post('nomor_main');
		$_SESSION['post'] = $_POST;

		if($_POST['id_diberi_izin'] != '' AND $_POST['id_diberi_izin'] !='*'){
			$data['diberi_izin']=$this->surat_model->get_penduduk($_POST['id_diberi_izin']);
			$_SESSION['id_diberi_izin'] = $_POST['id_diberi_izin'];
		}elseif ($_POST['id_diberi_izin'] !='*' AND isset($_SESSION['id_diberi_izin'])){
			$data['diberi_izin']=$this->surat_model->get_penduduk($_SESSION['id_diberi_izin']);
		}else{
			unset($data['diberi_izin']);
			unset($_SESSION['id_diberi_izin']);
		}

?>