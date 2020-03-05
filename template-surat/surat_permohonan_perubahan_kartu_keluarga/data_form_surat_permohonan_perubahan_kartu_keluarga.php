<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	$data['kepala_keluarga'] = $this->surat_model->list_kepala_keluarga();
	$data['sebab'] = array(
	"1" => "Karena Penambahan Anggota Keluarga (Kelahiran, Kedatangan)",
  "2" => "Karena Pengurangan Anggota Keluarga (Kematian, Kepindahan)",
  	"3" => "Lainnya"
	);
?>