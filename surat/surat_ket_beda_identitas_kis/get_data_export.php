<?php
	$anggota = $this->keluarga_model->list_anggota($individu['id_kk']);
	for ($i = 0; $i < MAX_ANGGOTA; $i++) {
		$nomor = $i+1;
		if ($i < count($anggota)) {
			$nik = trim($anggota[$i]['nik'],"'");
			$buffer=str_replace("[anggota_nik_$nomor]",$anggota[$i]['nik'],$buffer);
			$buffer=str_replace("[anggota_nama_$nomor]",strtoupper($anggota[$i]['nama']),$buffer);
			$buffer=str_replace("[anggota_sex_$nomor]",$anggota[$i]['sex'],$buffer);
			$buffer=str_replace("[anggota_tempatlahir_$nomor]",strtoupper($anggota[$i]['tempatlahir']),$buffer);
			$buffer=str_replace("[anggota_tanggallahir_$nomor]",tgl_indo_out($anggota[$i]['tanggallahir']),$buffer);
			$buffer=str_replace("[anggota_pekerjaan_$nomor]",strtoupper($anggota[$i]['pekerjaan']),$buffer);
			$buffer=str_replace("[anggota_alamat_$nomor]",strtoupper($anggota[$i]['alamat']),$buffer);
		} else {
			$buffer=str_replace("[anggota_no_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_nik_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_nama_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_sex_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_tempatlahir_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_tanggallahir_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_pekerjaan_$nomor]","",$buffer);
			$buffer=str_replace("[anggota_alamat_$nomor]","",$buffer);
		}
	}
	for ($i = 0; $i < MAX_ANGGOTA; $i++) {
		$nomor = $i+1;
		$buffer=str_replace("[kartu$nomor]",$input["kartu$nomor"],$buffer);
		$buffer=str_replace("[nama$nomor]",strtoupper($input["nama$nomor"]),$buffer);
		$buffer=str_replace("[nik$nomor]",$input["nik$nomor"],$buffer);
		$buffer=str_replace("[alamat$nomor]",strtoupper($input["alamat$nomor"]),$buffer);
		$buffer=str_replace("[tanggallahir$nomor]",$input["tanggallahir$nomor"],$buffer);
		$buffer=str_replace("[faskes$nomor]",$input["faskes$nomor"],$buffer);
	}
?>