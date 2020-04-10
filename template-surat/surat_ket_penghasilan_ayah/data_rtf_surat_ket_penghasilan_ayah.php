<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	// -- penghasilan ayah
	if (isset($input['hasil_ayah']))
		$hasil_ayah = preg_replace("/[^0-9,]/", "", $input['hasil_ayah']);
	$buffer = str_replace("[hasil_ayah]", Rupiah($hasil_ayah), $buffer);
	$buffer = str_replace("[tbl_hasil_ayah]", ucwords(number_to_words($hasil_ayah)), $buffer);
?>