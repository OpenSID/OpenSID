<?php

/**
 * SuratExportDesa
 *
 * Mengembalikan path surat ubahan desa apabila ada.
 * Cek folder semua komponen surat dulu, baru cek folder export
 *
 * @access  public
 * @return  string
 */
function SuratExportDesa($nama_surat)
{
	$surat_export_desa = LOKASI_SURAT_DESA . $nama_surat . "/" . $nama_surat . ".rtf";
	if (is_file($surat_export_desa))
		return $surat_export_desa;
	else
		$surat_export_desa = LOKASI_SURAT_EXPORT_DESA . $nama_surat . ".rtf";
	if (is_file($surat_export_desa))
	{
		return $surat_export_desa;
	}
	else
	{
		return "";
	}

}

/**
 * SuratCetakDesa
 *
 * Mengembalikan path surat ubahan desa apabila ada
 *
 * @access  public
 * @return  string
 */
function SuratCetakDesa($nama_surat)
{
	$surat_cetak_desa = LOKASI_SURAT_DESA . $nama_surat . "/print_" . $nama_surat . ".php";
	if (is_file($surat_cetak_desa))
		return $surat_cetak_desa;
	else
		$surat_cetak_desa = LOKASI_SURAT_PRINT_DESA . "print_" . $nama_surat . ".php";
	if (is_file($surat_cetak_desa)) {
		return $surat_cetak_desa;
	} else {
		return "";
	}

}

/**
 * SuratExport
 *
 * Mengembalikan path surat export apabila ada, dengan prioritas:
 *    1. surat export ubahan desa
 *    2. surat export asli SID
 *
 * @access  public
 * @return  string
 */
function SuratExport($nama_surat)
{
	if (SuratExportDesa($nama_surat) != "") {
		return SuratExportDesa($nama_surat);
	} elseif (is_file("surat/$nama_surat/$nama_surat.rtf")) {
		return "surat/$nama_surat/$nama_surat.rtf";
	} else {
		return "";
	}
}

/**
 * SuratCetak
 *
 * Mengembalikan path surat cetak apabila ada, dengan prioritas:
 *    1. surat cetak ubahan desa
 *    2. surat cetak asli SID
 * Path surat hanya dikembalikan apabila setting tombol_cetak_surat bernilai TRUE
 *
 * @access  public
 * @return  string
 */
function SuratCetak($nama_surat)
{
	$CI =& get_instance();
	if (!$CI->setting->tombol_cetak_surat) return "";

	if (SuratCetakDesa($nama_surat) != "")
	{
		return SuratCetakDesa($nama_surat);
	}
	elseif (is_file("surat/$nama_surat/print_" . $nama_surat . ".php"))
	{
		return "surat/$nama_surat/print_" . $nama_surat . ".php";
	}
	else
	{
		return "";
	}
}

function ikut_case($format, $str)
{
	$str = strtolower($str);
	if (ctype_upper($format[0]) AND ctype_upper($format[1]))
		return strtoupper($str);
	elseif (ctype_upper($format[0]))
		return ucwords($str);
	else
		return $str;
}
/**
 * Membuat string yang diisi &nbsp; di awal dan di akhir, dengan panjang yang ditentukan.
 *
 * @param            str      Text yang akan ditambahi awal dan akhiran
 * @param            awal     Jumlah karakter &nbsp; pada awal text
 * @param            panjang  Panjang string yang dihasilkan,
 *                            di mana setiap &nbsp; dihitung sebagai satu karakter
 * @return           string berisi text yang telah diberi awalan dan akhiran &nbsp;
 */
function padded_string_fixed_length($str, $awal, $panjang)
{
	$padding = "&nbsp;";
	$panjang_padding = strlen($padding);
	$panjang_text = strlen($str);
	$str = str_pad($str, ($awal * $panjang_padding) + $panjang_text, $padding, STR_PAD_LEFT);
	$str = str_pad($str, (($panjang - $panjang_text) * $panjang_padding) + $panjang_text, $padding, STR_PAD_RIGHT);
	return $str;
}

function padded_string_center($str, $panjang)
{
	$padding = "&nbsp;";
	$panjang_padding = strlen($padding);
	$panjang_text = strlen($str);
	$to_pad = ($panjang - $panjang_text) / 2;
	for ($i = 0; $i < $to_pad; $i++)
	{
		$str = $padding . $str . $padding;
	}
	return $str;
}
