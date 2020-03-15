<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	// ----- surat keterangan penghasilan orang tua
	// -- penghasilan ayah
	if (isset($input['hasil_ayah']))
		$hasil_ayah = preg_replace("/[^0-9,]/", "", $input['hasil_ayah']);
	$buffer = str_replace("[hasil_ayah]", Rupiah($hasil_ayah), $buffer);
	$buffer = str_replace("[tbl_hasil_ayah]", ucwords(number_to_words($hasil_ayah)), $buffer);
	// -- penghasilan ibu
	if (isset($input['hasil_ibu']))
		$hasil_ibu = preg_replace("/[^0-9]/", "", $input['hasil_ibu']);
	$buffer = str_replace("[hasil_ibu]", Rupiah($hasil_ibu), $buffer);
	$buffer = str_replace("[tbl_hasil_ibu]", ucwords(number_to_words($hasil_ibu)), $buffer);
	// -------------------- total penghasilan ayah + ibu
	$buffer = str_replace("[total_hasil]", rupiah24($hasil_ayah+$hasil_ibu), $buffer);
	// -------------------- total penghasilan ayah + ibu (terbilang)
	$buffer = str_replace("[tbl_total_hasil]", ucwords(number_to_words($hasil_ayah+$hasil_ibu)), $buffer);
	// ---------------------
?>