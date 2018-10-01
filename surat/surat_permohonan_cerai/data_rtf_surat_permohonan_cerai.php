<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

	# Data istri
	$istri = $this->get_data_istri($individu['id']);
	$array_replace = array(
              "[nama_istri]"         => $istri['nama'],
              "[nik_istri]"          => $istri['nik'],
              "[tempatlahir_istri]"  => "$istri[tempatlahir]",
              "[tanggallahir_istri]" => tgl_indo_dari_str($istri['tanggallahir']),
              "[pekerjaan_istri]"    => $istri['pek'],
              "[agama_istri]"        => $istri['agama'],
              "[alamat_istri]"       => "RT $istri[rt] / RW $istri[rw] $istri[dusun]",
	);
	$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);

?>