<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

	$buffer=str_replace("[jumlah_pengikut]",count($input['id_cb']),$buffer);
	for ($i = 0; $i < MAX_PINDAH; $i++) {
		$nomor = $i+1;
		if ($i < count($input['id_cb'])) {
			$id = trim($input['id_cb'][$i],"'");
			$penduduk = $this->penduduk_model->get_penduduk($id);
			$array_replace = array(
                      "[pindah_no_$nomor]"   => $nomor,
                      "[pindah_nik_$nomor]"  => $penduduk['nik'],
                      "[pindah_nama_$nomor]" => ucwords(strtolower($penduduk['nama'])),
                      "[ktp_berlaku$nomor]"  => $input['ktp_berlaku'][$i],
                      "[pindah_shdk_$nomor]" => ucwords(strtolower($penduduk['hubungan'])),
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		} else {
			$array_replace = array(
                      "[pindah_no_$nomor]"   => "",
                      "[pindah_nik_$nomor]"  => "",
                      "[pindah_nama_$nomor]" => "",
                      "[ktp_berlaku$nomor]"  => "",
                      "[pindah_shdk_$nomor]" => "",
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		}
	}
	$kode = $this->get_daftar_kode_surat($url);
	$alasan_pindah_id = trim($input['alasan_pindah_id'],"'");
	if ($alasan_pindah_id == "7") {
		$str = $kode['alasan_pindah'][$alasan_pindah_id]." (".$input['sebut_alasan'].")";
		$buffer=str_replace("[alasan_pindah]",$str,$buffer);
	} else {
		$buffer=str_replace("[alasan_pindah]",$kode['alasan_pindah'][$alasan_pindah_id],$buffer);
	}
	$buffer=str_replace("[jenis_kepindahan]",$kode['jenis_kepindahan'][$input['jenis_kepindahan_id']],$buffer);
	if ($kode['status_kk_tidak_pindah'][$input['status_kk_tidak_pindah_id']]) {
		$buffer=str_replace("[status_kk_tidak_pindah]",$kode['status_kk_tidak_pindah'][$input['status_kk_tidak_pindah_id']],$buffer);
	}
	else {
		$buffer=str_replace("[status_kk_tidak_pindah]","-",$buffer);
	}
	$buffer=str_replace("[status_kk_pindah]",$kode['status_kk_pindah'][$input['status_kk_pindah_id']],$buffer);

?>