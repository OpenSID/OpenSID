<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	$data['warganegara'] = $this->penduduk_model->list_warganegara();
	$data['agama'] = $this->referensi_model->list_data('tweb_penduduk_agama');
	$data['pekerjaan'] = $this->penduduk_model->list_pekerjaan('ucwords');
	$data['sex'] = $this->penduduk_model->list_sex();
?>