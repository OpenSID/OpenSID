<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
	// -- penghasilan ibu
	if (isset($input['hasil_ibu']))
		$hasil_ibu = preg_replace("/[^0-9]/", "", $input['hasil_ibu']);
	$buffer = str_replace("[hasil_ibu]", Rupiah($hasil_ibu), $buffer);
	$buffer = str_replace("[tbl_hasil_ibu]", ucwords(number_to_words($hasil_ibu)), $buffer);
?>