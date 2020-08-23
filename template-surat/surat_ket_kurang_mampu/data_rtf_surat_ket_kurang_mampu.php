<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

	$anggota = $this->keluarga_model->list_anggota($individu['id_kk'], array('dengan_kk'=>false));
	for ($i = 0; $i < max(MAX_ANGGOTA, count($anggota)); $i++)
	{
		$nomor = $i + 1;
		if ($i < count($anggota))
		{
			$nik = trim($anggota[$i],"'");
			$array_replace = array(
	                    "[anggota_no_$nomor]"           => $nomor,
	                    "[anggota_nik_$nomor]"          => $anggota[$i]['nik'],
	                    "[anggota_nama_$nomor]"         => strtoupper($anggota[$i]['nama']),
	                    "[anggota_sex_$nomor]"          => $anggota[$i]['sex'][0],
	                    "[anggota_tempatlahir_$nomor]"  => strtoupper($anggota[$i]['tempatlahir']),
	                    "[anggota_tanggallahir_$nomor]" => tgl_indo_out($anggota[$i]['tanggallahir']),
	                    "[anggota_shdk_$nomor]"         => strtoupper($anggota[$i]['hubungan']),
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		}
		else
		{
			$array_replace = array(
	                    "[anggota_no_$nomor]"           => "",
	                    "[anggota_nik_$nomor]"          => "",
	                    "[anggota_nama_$nomor]"         => "",
	                    "[anggota_sex_$nomor]"          => "",
	                    "[anggota_tempatlahir_$nomor]"  => "",
	                    "[anggota_tanggallahir_$nomor]" => "",
	                    "[anggota_shdk_$nomor]"         => "",
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		}
	}

?>
