<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$id_cb = $this->input->post('id_cb');
	$pilih="";
	foreach ($id_cb as $nik)
	{
		$pilih .= $nik.',';
	}
	$pilih = rtrim($pilih,',');
	$anggota = $this->keluarga_model->list_anggota($individu['id_kk'],array('pilih'=>$pilih));
	/*
		Abaikan baris data keluarga yang tidak dipilih
	*/
	for ($i = 0; $i < MAX_ANGGOTA; $i++)
	{
		$nomor = $i+1;
		if ($i < count($anggota))
		{
			$nik = trim($anggota[$i]['nik'],"'");
			$buffer=str_replace("[anggota_nik_$nomor]",$anggota[$i]['nik'],$buffer);
			$buffer=str_replace("[anggota_nama_$nomor]",strtoupper($anggota[$i]['nama']),$buffer);
			$buffer=str_replace("[anggota_sex_$nomor]",$anggota[$i]['sex'],$buffer);
			$buffer=str_replace("[anggota_tempatlahir_$nomor]",strtoupper($anggota[$i]['tempatlahir']),$buffer);
			$buffer=str_replace("[anggota_tanggallahir_$nomor]",tgl_indo_out($anggota[$i]['tanggallahir']),$buffer);
			$buffer=str_replace("[anggota_pekerjaan_$nomor]",strtoupper($anggota[$i]['pekerjaan']),$buffer);
			$buffer=str_replace("[anggota_alamat_$nomor]",strtoupper($anggota[$i]['alamat']),$buffer);
		}
		else
		{
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
	/*
		Abaikan baris data identitas KIS yang kosong
	*/
	$j = 0;
	for ($i = 0; $i < MAX_ANGGOTA; $i++)
	{
		$nomor = $i+1;
		if (!empty($input["nomor$nomor"]))
		{
			$j++;
			$buffer=str_replace("[kartu$j]",$input["kartu$nomor"],$buffer);
			$buffer=str_replace("[nama$j]",strtoupper($input["nama$nomor"]),$buffer);
			$buffer=str_replace("[nik$j]",$input["nik$nomor"],$buffer);
			$buffer=str_replace("[alamat$j]",strtoupper($input["alamat$nomor"]),$buffer);
			$buffer=str_replace("[tanggallahir$j]",$input["tanggallahir$nomor"],$buffer);
			$buffer=str_replace("[faskes$j]",$input["faskes$nomor"],$buffer);
		}
	}
	for ($i = $j+1; $i < MAX_ANGGOTA+1; $i++)
	{
		$buffer=str_replace("[kartu$i]","",$buffer);
		$buffer=str_replace("[nama$i]","",$buffer);
		$buffer=str_replace("[nik$i]","",$buffer);
		$buffer=str_replace("[alamat$i]","",$buffer);
		$buffer=str_replace("[tanggallahir$i]","",$buffer);
		$buffer=str_replace("[faskes$i]","",$buffer);
	}
?>