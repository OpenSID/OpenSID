<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

	$data['kepala_keluarga'] = $this->surat_model->list_kepala_keluarga();
	$data['kode']['alasan_permohonan'] = array(
	  1 => "Karena Membentuk Rumah Tangga Baru",
	  2 => "Karena Kartu Keluarga Hilang/Rusak",
	  3 => "Lainnya"
	);

?>