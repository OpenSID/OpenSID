<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	$buffer = str_replace("[jumlah_pengikut]", count($input['id_cb']), $buffer);
	for ($i = 0; $i < MAX_PINDAH; $i++)
	{
		$nomor = $i+1;
		if ($i < count($input['id_cb']))
		{
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
		}
		else
		{
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
	$kode["alasan_pindah"] = array(
		1 => "Pekerjaan",
		2 => "Pendidikan",
		3 => "Keamanan",
		4 => "Kesehatan",
		5 => "Perumahan",
		6 => "Keluarga",
		7 => "Lainnya"
	);
	$alasan_pindah_id = trim($input['alasan_pindah_id'], "'");
	if ($alasan_pindah_id == "7")
	{
		$str = $kode['alasan_pindah'][$alasan_pindah_id]." (".$input['sebut_alasan'].")";
		$buffer = str_replace("[alasan_pindah]", $str, $buffer);
	}
	else
	{
		$buffer = str_replace("[alasan_pindah]", $kode['alasan_pindah'][$alasan_pindah_id], $buffer);
	}

?>