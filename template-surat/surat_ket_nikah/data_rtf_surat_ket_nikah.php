<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	// Data pasangan pria =====================
	if ($input['id_pria'])
	{
		$pria = $this->get_data_surat($input['id_pria']);
		$ibu_pria = $this->get_data_ibu($input['id_pria']);
		$ayah_pria = $this->get_data_ayah($input['id_pria']);
		$array_replace = array(
                  "[agama_pria]"        => "$pria[agama]",
                  "[alamat_pria]"       => "" . $pria[alamat] .  " RT " . $pria[rt] .  " RW " . $pria[rw] . " $alamat_desa",
                  "[nama_pria]"         => "$pria[nama]",
                  "[no_ktp_pria]"       => "$pria[nik]",
                  "[no_kk_pria]"        => "$pria[no_kk]",
                  "[pekerjaan_pria]"    => "$pria[pekerjaan]",
                  "[sex_pria]"          => "$pria[sex]",
                  "[status_pria]"       => "$pria[status_kawin]",
                  "[tempatlahir_pria]"  => $pria[tempatlahir],
                  "[tanggallahir_pria]" => tgl_indo_dari_str($pria[tanggallahir]),
                  "[usia_pria]"         => "$pria[umur] Tahun",
                  "[wn_pria]"           => "$pria[warganegara]",
		);
		if (!isset($input['jumlah_istri'])) $array_replace["[form_jumlah_istri]"] = "";
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}

	# Data orang tua apabila warga desa
	if ($ayah_pria)
	{
		$array_replace = array(
                  "[form_nama_ayah_pria]"         => $ayah_pria['nama'],
								  "[form_noktp_ayah_pria]"        => $ayah_pria['nik'],
								  "[form_bin_ayah_pria]"          => $ayah_pria['nama_ayah'],
                  "[form_tempatlahir_ayah_pria]"  => ucwords(strtolower($ayah_pria['tempatlahir'])),
                  "[form_tanggallahir_ayah_pria]" => tgl_indo_dari_str($ayah_pria['tanggallahir']),
                  "[form_wn_ayah_pria]"           => $ayah_pria['wn'],
                  "[form_agama_ayah_pria]"        => ucwords(strtolower($ayah_pria['agama'])),
                  "[form_pekerjaan_ayah_pria]"    => ucwords(strtolower($ayah_pria['pek'])),
                  "[form_alamat_ayah_pria]"       => "" . $ayah_pria[alamat] .  " RT " . $ayah_pria[rt] .  " RW " . $ayah_pria[rw] . " $alamat_desa",
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	if ($ibu_pria)
	{
		$array_replace = array(
                  "[form_nama_ibu_pria]"         => $ibu_pria['nama'],
								  "[form_noktp_ibu_pria]"        => $ibu_pria['nik'],
								  "[form_binti_ibu_pria]"        => $ibu_pria['nama_ayah'],
                  "[form_tempatlahir_ibu_pria]"  => ucwords(strtolower($ibu_pria['tempatlahir'])),
                  "[form_tanggallahir_ibu_pria]" => tgl_indo_dari_str($ibu_pria['tanggallahir']),
                  "[form_wn_ibu_pria]"           => $ibu_pria['wn'],
                  "[form_agama_ibu_pria]"        => ucwords(strtolower($ibu_pria['agama'])),
                  "[form_pekerjaan_ibu_pria]"    => ucwords(strtolower($ibu_pria['pek'])),
                  "[form_alamat_ibu_pria]"       => "$ibu_pria[alamat] RT $ibu_pria[rt] RW $ibu_pria[rw] " . " $alamat_desa",
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	// Kode isian yang mungkin tidak terisi
	$buffer = str_replace("[form_istri_dulu]",$input['istri_dulu'], $buffer);

	// Data pasangan wanita =====================
	if ($input['id_wanita'])
	{
		$wanita = $this->get_data_surat($input['id_wanita']);
		$ibu_wanita = $this->get_data_ibu($input['id_wanita']);
		$ayah_wanita = $this->get_data_ayah($input['id_wanita']);
		$array_replace = array(
                  "[form_agama_wanita]"        => $wanita[agama],
                  "[form_alamat_wanita]"       => "$wanita[alamat] RT $wanita[rt]  RW $wanita[rw] " . " $alamat_desa",
                  "[form_nama_wanita]"         => $wanita[nama],
								  "[form_no_ktp_wanita]"       => $wanita[nik],
								  "[form_sex_wanita]"          => $wanita[sex],
								  "[status_wanita]"       		 => $wanita[status_kawin],
                  "[form_pekerjaan_wanita]"    => $wanita[pekerjaan],
                  "[form_tempatlahir_wanita]"  => $wanita[tempatlahir],
                  "[form_tanggallahir_wanita]" => tgl_indo_dari_str($wanita[tanggallahir]),
                  "[form_wn_wanita]"           => $wanita[warganegara],
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	# Data orang tua apabila warga desa
	if ($ayah_wanita)
	{
		$array_replace = array(
                  "[form_nama_ayah_wanita]"         => $ayah_wanita['nama'],
								  "[form_noktp_ayah_wanita]"        => $ayah_wanita['nik'],
								  "[form_bin_ayah_wanita]"          => $ayah_wanita['nama_ayah'],
                  "[form_tempatlahir_ayah_wanita]"  => ucwords(strtolower($ayah_wanita['tempatlahir'])),
                  "[form_tanggallahir_ayah_wanita]" => tgl_indo_dari_str($ayah_wanita['tanggallahir']),
                  "[form_wn_ayah_wanita]"           => $ayah_wanita['wn'],
                  "[form_agama_ayah_wanita]"        => ucwords(strtolower($ayah_wanita['agama'])),
                  "[form_pekerjaan_ayah_wanita]"    => ucwords(strtolower($ayah_wanita['pek'])),
                  "[form_alamat_ayah_wanita]"       => "" . $ayah_wanita[alamat] .  " RT " . $ayah_wanita[rt] .  " RW " . $ayah_wanita[rw] . " $alamat_desa",
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	if ($ibu_wanita)
	{
		$array_replace = array(
                  "[form_nama_ibu_wanita]"         => $ibu_wanita['nama'],
								  "[form_noktp_ibu_wanita]"        => $ibu_wanita['nik'],
								  "[form_binti_ibu_wanita]"        => $ibu_wanita['nama_ayah'],
                  "[form_tempatlahir_ibu_wanita]"  => ucwords(strtolower($ibu_wanita['tempatlahir'])),
                  "[form_tanggallahir_ibu_wanita]" => tgl_indo_dari_str($ibu_wanita['tanggallahir']),
                  "[form_wn_ibu_wanita]"           => $ibu_wanita['wn'],
                  "[form_agama_ibu_wanita]"        => ucwords(strtolower($ibu_wanita['agama'])),
                  "[form_pekerjaan_ibu_wanita]"    => ucwords(strtolower($ibu_wanita['pek'])),
                  "[form_alamat_ibu_wanita]"       => "" . $ibu_wanita[alamat] .  " RT " . $ibu_wanita[rt] .  " RW " . $ibu_wanita[rw] . " $alamat_desa",
		);
		$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
	}
	// Kode isian yang mungkin tidak terisi
	$buffer = str_replace("[form_suami_dulu]", $input['nama_suami_dulu'], $buffer);

?>