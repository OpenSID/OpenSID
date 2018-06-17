<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

define('MAX_ANGGOTA_F116', 10);
define('MAX_ANGGOTA_F101', 10);

$this->load->model('keluarga_model');
$this->load->model('pamong_model');
$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
$anggota_ikut = $this->keluarga_model->list_anggota($individu['id_kk'],array('dengan_kk'=>false));

$desa = $this->keluarga_model->get_desa();
// Gunakan data identitas desa, jika ada
if ($desa['nip_kepala_desa']){
	$kepala_desa['pamong_nama'] = $desa['nama_kepala_desa'];
	$kepala_desa['pamong_nip'] = $desa['nip_kepala_desa'];
} else
	$kepala_desa = $this->pamong_model->get_pamong_by_nama($desa['nama_kepala_desa']);

?>