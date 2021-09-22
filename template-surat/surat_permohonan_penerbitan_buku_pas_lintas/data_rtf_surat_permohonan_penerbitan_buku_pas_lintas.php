<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	for ($i = 0; $i < MAX_PINDAH; $i++)
	{
		$nomor = $i+1;
		if ($i < count($input['id_cb']))
		{
			$id = trim($input['id_cb'][$i],"'");
			$penduduk = $this->penduduk_model->get_penduduk($id, TRUE);
			$array_replace = array(
				"[bpl_no_$nomor]"   => $nomor,
				"[bpl_nik_$nomor]"  => $penduduk['nik'],
				"[bpl_nama_$nomor]" => ucwords(strtolower($penduduk['nama'])),
				"[bpl_sex_$nomor]" => ucwords(strtolower($penduduk['sex'])),
				"[bpl_tempat_$nomor]" => ucwords(strtolower($penduduk['tempatlahir'])),
				"[bpl_tanggal_$nomor]" => ucwords(strtolower($penduduk['tanggallahir'])),
				"[bpl_shdk_$nomor]" => ucwords(strtolower($penduduk['hubungan'])),
				"[ket_$nomor]"  => $input["ket_$id"],
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		}
		else
		{
			$array_replace = array(
			"[bpl_no_$nomor]"   => "",
			"[bpl_nik_$nomor]"  => "",
			"[bpl_nama_$nomor]" => "",
			"[bpl_sex_$nomor]" => "",
			"[bpl_tempat_$nomor]" => "",
			"[bpl_tanggal_$nomor]" => "",
			"[bpl_shdk_$nomor]" => "",
			"[ket_$nomor]"  => "",
			);
			$buffer = str_replace(array_keys($array_replace), array_values($array_replace), $buffer);
		}
	}
	$buffer = str_replace("[s1]", $individu['sex_id'], $buffer);
	$buffer = str_replace("[s2]", $individu['status_kawin_id'], $buffer);
	$buffer = str_replace("[s3]", $individu['agama_id'], $buffer);

	$Pecah = explode('-', $individu['tanggallahir']);
	$kode_provinsi = str_split($config['kode_propinsi']);
	$kode_kabupaten = str_split($config['kode_kabupaten']);
	$kode_kecamatan = str_split($config['kode_kecamatan']);
	$bln = str_split($Pecah[1]);
	$tgl = str_split($Pecah[2]);
	for ($i=1; $i<=2; $i++)
	{
		$buffer = str_replace("[prov{$i}]", $kode_provinsi[$i-1], $buffer);
		$buffer = str_replace("[kab{$i}]", $kode_kabupaten[$i-1], $buffer);
		$buffer = str_replace("[kec{$i}]", $kode_kecamatan[$i-1], $buffer);
		$buffer = str_replace("[tgl{$i}]", $tgl[$i-1], $buffer);
		$buffer = str_replace("[bln{$i}]", $bln[$i-1], $buffer);
	}

	$thn = str_split($Pecah[0]);
	$kode_desa = str_split($config['kode_desa']);
	for ($i=1; $i<=4; $i++)
	{
		$buffer = str_replace("[des{$i}]", $kode_desa[$i-1], $buffer);
		$buffer = str_replace("[thn{$i}]", $thn[$i-1], $buffer);
	}

	$ktp = str_split($individu['nik']);
	$kk = str_split($individu['no_kk']);
	for ($i=1; $i<=16; $i++)
	{
		$buffer = str_replace("[ktp{$i}]", $ktp[$i-1], $buffer);
		$buffer = str_replace("[kk{$i}]", $kk[$i-1], $buffer);
	}
?>
