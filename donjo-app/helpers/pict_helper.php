<?php

define("FOTO_DEFAULT", base_url() . 'assets/files/user_pict/kuser.png');

define ('MIME_TYPE_SIMBOL', serialize (array(
	'image/png',  'image/x-png' )));

define ('EXT_SIMBOL', serialize(array(
	".png"
)));

define ('MIME_TYPE_DOKUMEN', serialize (array(
	"application/x-download",
	"application/pdf",
	"application/ppt",
	"application/pptx",
	"application/excel",
	"application/msword",
	"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
	"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
	"text/rtf",
	"application/powerpoint",
	"application/vnd.ms-powerpoint",
	"application/vnd.ms-excel",
	"application/msexcel")));

define ('EXT_DOKUMEN', serialize(array(
	".pdf", ".ppt", ".pptx", ".pps", ".ppsx",
	".doc", ".docx", ".rtf", ".xls", ".xlsx"
)));

define ('MIME_TYPE_GAMBAR', serialize (array(
	'image/jpeg', 'image/pjpeg',
	'image/png',  'image/x-png' )));

define ('EXT_GAMBAR', serialize(array(
	".jpg", ".jpeg", ".png"
)));

define ('MIME_TYPE_ARSIP', serialize (array(
	'application/rar','application/x-rar','application/x-rar-compressed','application/octet-stream',
	'application/zip','application/x-zip','application/x-zip-compressed')));

define ('EXT_ARSIP', serialize(array(
	".zip", ".rar"
)));

/**
* Tambahkan suffix unik ke nama file
* @param   string        $namaFile    Nama file asli (beserta ekstensinya)
* @param   boolean       $urlEncode  Saring nama file dengan urlencode() ?
* @param   string|NULL   $delimiter  String pemisah nama asli dengan unique id
* @return  string
*/
function tambahSuffixUniqueKeNamaFile($namaFile, $urlEncode = TRUE, $delimiter = NULL)
{
		// Delimiter untuk tambahSuffixUniqueKeNamaFile()
	$delimiterUniqueKey = NULL;

		// Type check
	$namaFile = is_string($namaFile) ? $namaFile : strval($namaFile);
	$urlEncode = is_bool($urlEncode) ? $urlEncode : TRUE;
	$delimiterUniqueKey = (!is_string($delimiter) || empty($delimiter))
	? '__sid__' : $delimiter;

		// Pastikan nama file tidak mengandung string milik $this->delimiterUniqueKey
	$namaFile = str_replace($delimiterUniqueKey, '__', $namaFile);
		// Tambahkan suffix nama unik menggunakan uniqid()
	$namaFileUnik = explode('.', $namaFile);
	$ekstensiFile = end($namaFileUnik);
	unset($namaFileUnik[count($namaFileUnik) - 1]);
	$namaFileUnik = implode('.', $namaFileUnik);
	$namaFileUnik = urlencode($namaFileUnik).
	$delimiterUniqueKey.generator().'.'.$ekstensiFile;
		// Contoh return:
		// - nama asli = 'kitten.jpg'
		// - nama unik = 'kitten__sid__xUCc8KO.jpg'
	return $namaFileUnik;
}

function AmbilFoto($foto, $ukuran="kecil_")
{
	if (empty($foto) OR $foto == 'kuser.png')
		$file_foto = FOTO_DEFAULT;
	else
	{
		$ukuran = ($ukuran == "kecil_") ? "kecil_" : "";
		$file_foto = base_url() . LOKASI_USER_PICT . $ukuran . $foto;
		if (!file_exists(FCPATH . LOKASI_USER_PICT . $ukuran . $foto)) $file_foto = FOTO_DEFAULT;
	}
	return $file_foto;
}

function UploadGambarWidget($nama_file, $lokasi_file, $old_gambar)
{
	$dir_upload = LOKASI_GAMBAR_WIDGET;
	if ($old_gambar) unlink($dir_upload . $old_gambar);
	$file_upload = $dir_upload . $nama_file;
	move_uploaded_file($lokasi_file, $file_upload);
}

function UploadFoto($fupload_name,$old_foto,$tipe_file="")
{
	$tipe_file = TipeFile($_FILES["foto"]);
	$dimensi = array("width"=>200, "height"=>250);
	if ($old_foto!="")
	{
		// Hapus old_foto
		unlink(LOKASI_USER_PICT.$old_foto);
		$old_foto = "kecil_".$old_foto;
	}
	$nama_simpan = "kecil_".$fupload_name;
	return UploadResizeImage(LOKASI_USER_PICT, $dimensi, "foto", $fupload_name, $nama_simpan, $old_foto, $tipe_file);
}

function UploadGambar($fupload_name,$old_gambar)
{
	$vdir_upload = "assets/front/slide/";
	if ($old_gambar!="")
		unlink($vdir_upload."kecil_".$old_gambar);

	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["gambar"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width * 25) < ($src_height * 44))
	{
		$dst_width = 440;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 250;

		$im = imagecreatetruecolor(440,250);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 250;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 440;

		$im = imagecreatetruecolor(440,250);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."kecil_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);

	unlink($vfile_upload);
	return true;
}

function AmbilGaleri($foto, $ukuran)
{
	$file_foto = base_url() . LOKASI_GALERI . $ukuran ."_" . $foto;
	return $file_foto;
}

/*
	$file_upload = $_FILES['<lokasi>']
*/
function TipeFile($file_upload)
{
	$lokasi_file = $file_upload['tmp_name'];
	if (empty($lokasi_file))
	{
		return "";
	}
	if (isPHP($file_upload['tmp_name'], $file_upload['name']))
	{
		return "application/x-php";
	}
	if (function_exists('finfo_open'))
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$tipe_file = finfo_file($finfo, $lokasi_file);
		finfo_close($finfo);
	}
	else
		$tipe_file = $file_upload['type'];
	return $tipe_file;
}

/*
	$file_upload = $_FILES['<lokasi>']
*/
function UploadError($file_upload)
{
// error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
// TODO: pakai cara upload yg disediakan Codeigniter
	if ($file_upload['error'] == 1)
	{
		$upload_mb = max_upload();
		$_SESSION['error_msg'].= " -> Ukuran file melebihi batas " . $upload_mb . " MB";
		return true;
	}
	else return false;
}

/*
$file_upload = $_FILES['<lokasi>']
*/
function CekGambar($file_upload,$tipe_file)
{
	$lokasi_file = $file_upload['tmp_name'];
	if (empty($lokasi_file))
	{
		return false;
	}
	$nama_file   = $file_upload['name'];
	$ext = get_extension($nama_file);

	if (!in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)) OR !in_array($ext, unserialize(EXT_GAMBAR)))
	{
		$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file . " " . $ext;
		return false;
	}
	return true;
}

function UploadGallery($fupload_name, $old_foto='', $tipe_file='')
{
	$dimensi = array("width"=>440, "height"=>440);
	if (!empty($old_foto)) $old_foto_hapus = "kecil_".$old_foto;
	$nama_simpan = "kecil_".$fupload_name;
	$hasil1 = UploadResizeImage(LOKASI_GALERI, $dimensi, "gambar", $fupload_name, $nama_simpan, $old_foto_hapus, $tipe_file);
	$dimensi = array("width"=>880, "height"=>880);
	if (!empty($old_foto)) $old_foto_hapus = "sedang_".$old_foto;
	$nama_simpan = "sedang_".$fupload_name;
	$hasil2 = UploadResizeImage(LOKASI_GALERI, $dimensi, "gambar", $fupload_name, $nama_simpan, $old_foto_hapus, $tipe_file);
// Hapus upload file di sini, karena $_FILES["gambar"]["tmp_name"] dihapus sistem sesudah dipindahkan
	unlink(LOKASI_GALERI.$fupload_name);

	return $hasil1 AND $hasil2;
}

function UploadSimbolx($fupload_name, $old_gambar)
{
	$vdir_upload = "assets/gis/simbol";
	if ($old_gambar!="")
	{
		unlink($vdir_upload."kecil_".$old_gambar);
		unlink($vdir_upload.$old_gambar);
	}
	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["gambar"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width * 20) < ($src_height * 44))
	{
		$dst_width = 440;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 300;

		$im = imagecreatetruecolor(440, 300);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 300;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 440;

		$im = imagecreatetruecolor(440, 300);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."kecil_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);

//unlink($vfile_upload);
	return true;
}

function AmbilFotoArtikel($foto, $ukuran)
{
	$file_foto = base_url() . LOKASI_FOTO_ARTIKEL . $ukuran ."_" . $foto;
	return $file_foto;
}

function UploadArtikel($fupload_name, $gambar, $fp, $tipe_file, $old_foto='')
{
	$dimensi = array("width"=>440, "height"=>440);
	if (!empty($old_foto)) $old_foto_hapus = "kecil_".$old_foto;
	$nama_simpan = "kecil_".$fupload_name;
	$hasil1 = UploadResizeImage(LOKASI_FOTO_ARTIKEL, $dimensi, $gambar, $fupload_name, $nama_simpan, $old_foto_hapus, $tipe_file);
// Tidak perlu buat gambar sedang, jika jenis file sudah salah
	if ($hasil1)
	{
		$dimensi = array("width"=>880, "height"=>880);
		if (!empty($old_foto)) $old_foto_hapus = "sedang_".$old_foto;
		$nama_simpan = "sedang_".$fupload_name;
		$hasil2 = UploadResizeImage(LOKASI_FOTO_ARTIKEL, $dimensi, $gambar, $fupload_name, $nama_simpan, $old_foto_hapus, $tipe_file);
	}
// Hapus upload file di sini, karena $_FILES["gambar"]["tmp_name"] dihapus sistem sesudah dipindahkan
	unlink(LOKASI_FOTO_ARTIKEL.$fupload_name);

	return $hasil1 AND $hasil2;
}

function HapusArtikel($gambar)
{
	$vdir_upload = LOKASI_FOTO_ARTIKEL;
	$vfile_upload = $vdir_upload . "sedang_" . $gambar;
	unlink($vfile_upload);
	$vfile_upload = $vdir_upload . "kecil_" . $gambar;
	unlink($vfile_upload);
	return true;
}

function UploadLokasi($fupload_name)
{
	$vdir_upload = LOKASI_FOTO_LOKASI;

	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["foto"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (12 / 10))
	{
		$dst_width = 120;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 100;

		$im = imagecreatetruecolor(120,100);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 100;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 120;

		$im = imagecreatetruecolor(120,100);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."kecil_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);


	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (44 / 30))
	{
		$dst_width = 880;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 600;

		$im = imagecreatetruecolor(880,600);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 600;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 880;

		$im = imagecreatetruecolor(880,600);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."sedang_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);
	unlink($vdir_upload.$fupload_name);

//unlink($vfile_upload);
	return true;
}

function UploadGaris($fupload_name)
{
	$vdir_upload = LOKASI_FOTO_GARIS;

	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["foto"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (12 / 10))
	{
		$dst_width = 120;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 100;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 100;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 120;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."kecil_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (44 / 30)){
		$dst_width = 880;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 600;

		$im = imagecreatetruecolor(880, 600);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 600;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 880;

		$im = imagecreatetruecolor(880, 600);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."sedang_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);
	unlink($vdir_upload.$fupload_name);

//unlink($vfile_upload);
	return true;
}

function UploadArea($fupload_name)
{
	$vdir_upload = LOKASI_FOTO_AREA;

	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["foto"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (12 / 10))
	{
		$dst_width = 120;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 100;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 100;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 120;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."kecil_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);


	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (44 / 30))
	{
		$dst_width = 880;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 600;

		$im = imagecreatetruecolor(880, 600);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);

	}
	else
	{
		$dst_height = 600;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 880;

		$im = imagecreatetruecolor(880, 600);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
	imagejpeg($im,$vdir_upload ."sedang_".$fupload_name);

	imagedestroy($im_src);
	imagedestroy($im);
	unlink($vdir_upload.$fupload_name);

//unlink($vfile_upload);
	return true;
}

/*
	$dimensi = array("width"=>lebar, "height"=>tinggi)
*/
function resizeImage($filepath_in, $tipe_file, $dimensi, $filepath_out='')
{
// Hanya bisa resize jpeg atau png
	$mime_type_image = array("image/jpeg", "image/pjpeg", "image/png", "image/x-png");
	if (!in_array($tipe_file, $mime_type_image))
	{
		$_SESSION['error_msg'].= " -> Jenis file tidak bisa di-resize: " . $tipe_file;
		$_SESSION['success']=-1;
		return FALSE;
	}

	if (empty($filepath_out)) $filepath_out = $filepath_in;

	$is_png = ($tipe_file == "image/png" OR $tipe_file == "image/x-png");

	$image = ($is_png) ? imagecreatefrompng($filepath_in) : imagecreatefromjpeg($filepath_in);
	$width = imageSX($image);
	$height = imageSY($image);
	$new_width = $dimensi["width"];
	$new_height = $dimensi["height"];
	if ($width>$new_width && $height>$new_height)
	{
		if ($width < $height)
		{
			$dst_width = $new_width;
			$dst_height = ($dst_width/$width)*$height;
			$cut_height = $dst_height - $new_height;
			$cut_width = 0;
		}
		else
		{
			$dst_height = $new_height;
			$dst_width = ($dst_height/$height)*$width;
			$cut_width = $dst_width - $new_width;
			$cut_height = 0;
		}

		$image_p = imagecreatetruecolor($new_width, $new_height);
		if ($is_png)
		{
		// http://stackoverflow.com/questions/279236/how-do-i-resize-pngs-with-transparency-in-php
			imagealphablending($image_p, false);
			imagesavealpha($image_p, true);
		}
		imagecopyresampled($image_p, $image, 0, 0, $cut_width, $cut_height, $dst_width, $dst_height, $width, $height);
		if ($is_png)
			imagepng($image_p,$filepath_out,5);
		else
			imagejpeg($image_p,$filepath_out);
		imagedestroy($image_p);
		imagedestroy($image);
	}
	else
	{
	// Ukuran file tidak perlu di-resize
		copy($filepath_in, $filepath_out);
		imagedestroy($image);
	}
	return TRUE;
}

/**   TODO: tulis ulang semua penggunaan supaya menggunakan resizeImage()
* $jenis_upload contoh "logo", "foto"
* $dimensi = array("width"=>lebar, "height"=>tinggi)
* $lokasi contoh LOKASI_LOGO_DESA
* $nama_simpan contoh "kecil_".$fupload_name
*/
function UploadResizeImage($lokasi,$dimensi,$jenis_upload,$fupload_name,$nama_simpan,$old_foto,$tipe_file)
{
	// Hanya bisa upload jpeg atau png
	$mime_type_image = array("image/jpeg", "image/pjpeg", "image/png", "image/x-png");
	$ext_type_image = array(".jpg", ".jpeg", ".png");
	$ext = get_extension($fupload_name);
	if (!in_array($tipe_file, $mime_type_image) or !in_array($ext, $ext_type_image))
	{
		$_SESSION['error_msg'].= " -> Jenis file salah: " . $tipe_file;
		$_SESSION['success']=-1;
		return FALSE;
	}

	$vdir_upload = $lokasi;
	if (!empty($old_foto)) unlink($vdir_upload.$old_foto);
	$filepath_in = $vdir_upload . $fupload_name;
	$filepath_out = $vdir_upload.$nama_simpan;
	move_uploaded_file($_FILES[$jenis_upload]["tmp_name"], $filepath_in);

	$is_png = ($tipe_file == "image/png" OR $tipe_file == "image/x-png");

	$image = ($is_png) ? imagecreatefrompng($filepath_in) : imagecreatefromjpeg($filepath_in);
	$width = imageSX($image);
	$height = imageSY($image);
	$new_width = $dimensi["width"];
	$new_height = $dimensi["height"];
	if ($width>$new_width && $height>$new_height)
	{
		if ($width < $height)
		{
			$dst_width = $new_width;
			$dst_height = ($dst_width/$width)*$height;
			$cut_height = $dst_height - $new_height;
			$cut_width = 0;
		}
		else
		{
			$dst_height = $new_height;
			$dst_width = ($dst_height/$height)*$width;
			$cut_width = $dst_width - $new_width;
			$cut_height = 0;
		}

		$image_p = imagecreatetruecolor($new_width, $new_height);
		if ($is_png)
		{
			// http://stackoverflow.com/questions/279236/how-do-i-resize-pngs-with-transparency-in-php
			imagealphablending($image_p, false);
			imagesavealpha($image_p, true);
		}
		imagecopyresampled($image_p, $image, 0, 0, $cut_width, $cut_height, $dst_width, $dst_height, $width, $height);
		if ($is_png)
			imagepng($image_p,$filepath_out, 5);
		else
			imagejpeg($image_p,$filepath_out);
		imagedestroy($image_p);
		imagedestroy($image);
	}
	else
	{
		// Ukuran file tidak perlu di-resize
		copy($filepath_in, $filepath_out);
		imagedestroy($image);
	}
	return TRUE;
}

function UploadSimbol($fupload_name)
{
	$vdir_upload = "assets/images/gis/point/";
	$vfile_upload = $vdir_upload . $fupload_name;

	move_uploaded_file($_FILES["simbol"]["tmp_name"], $vfile_upload);
}

function AmbilDokumen($dokumen)
{
	$file_dokumen = base_url() . LOKASI_DOKUMEN . $dokumen;
	return $file_dokumen;
}

// Upload umum. Parameter lokasi dan file di $_FILES
function UploadKeLokasi($lokasi, $file, $fupload_name, $old_dokumen="")
{
	$vfile_upload = $lokasi . $fupload_name;
	move_uploaded_file($file, $vfile_upload);
	unlink($lokasi . $old_dokumen);
}

function UploadDocument($fupload_name, $old_dokumen="")
{
	$vfile_upload = LOKASI_DOKUMEN . $fupload_name;
	move_uploaded_file($_FILES["satuan"]["tmp_name"], $vfile_upload);
	unlink(LOKASI_DOKUMEN . $old_dokumen);
}

function UploadDocument2($fupload_name)
{
	$vdir_upload = LOKASI_DOKUMEN;
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["dokumen"]["tmp_name"], $vfile_upload);

	//unlink($vfile_upload);
	return true;
}

function UploadPengesahan($fupload_name)
{
	$vdir_upload = LOKASI_PENGESAHAN;
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["pengesahan"]["tmp_name"], $vfile_upload);

	$im_src = imagecreatefromjpeg($vfile_upload);
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	if (($src_width / $src_height) < (12 / 10))
	{
		$dst_width = 120;
		$dst_height = ($dst_width/$src_width)*$src_height;
		$cut_height = $dst_height - 100;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);
	}
	else
	{
		$dst_height = 100;
		$dst_width = ($dst_height/$src_height)*$src_width;
		$cut_width = $dst_width - 120;

		$im = imagecreatetruecolor(120, 100);
		imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
	}
}

/*
	Hasilkan nama file yg aman untuk digunakan di url
	source = https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
*/
function bersihkan_namafile($nama)
{
// Simpan extension
	$ext = get_extension($nama);
// replace non letter or digits by -
	$nama = preg_replace('~[^\pL\d]+~u', '-', $nama);
// transliterate
	$nama = iconv('utf-8', 'us-ascii//TRANSLIT', $nama);
// remove unwanted characters
	$nama = preg_replace('~[^-\w]+~', '', $nama);
// trim
	$nama = trim($nama, '-');
// remove duplicate -
	$nama = preg_replace('~-+~', '-', $nama);
// lowercase
	return strtolower($nama.$ext);
}

function periksa_file($upload, $mime_types, $exts)
{
	if (empty($_FILES[$upload]['tmp_name']) or (int)$_FILES[$upload]['size'] > convertToBytes(max_upload().'MB'))
	{
		return ' -> Error upload file. Periksa apakah melebihi ukuran maksimum';
	}

	$lokasi_file = $_FILES[$upload]['tmp_name'];
	if (empty($lokasi_file))
	{
		return ' -> File tidak berhasil diunggah';
	}
	if (function_exists('finfo_open'))
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$tipe_file = finfo_file($finfo, $lokasi_file);
	}
	else
		$tipe_file = $_FILES[$upload]['type'];
	$nama_file = $_FILES[$upload]['name'];
	$nama_file = str_replace(' ', '-', $nama_file);    // normalkan nama file
	$ext = get_extension($nama_file);

	if (!in_array($tipe_file, $mime_types) OR !in_array($ext, $exts))
	{
		return " -> Jenis file salah: " . $tipe_file . " " . $ext;
	}
	elseif (isPHP($lokasi_file, $nama_file))
	{
		return " -> File berisi script ";
	}
	return '';
}

function qrcode_generate($pathqr, $namaqr, $isiqr, $logoqr, $sizeqr, $backqr, $foreqr)
{
	$CI =& get_instance();
	$CI->load->library('ciqrcode'); //pemanggilan library QR CODE

	$backqr1 = preg_replace('/#/', '0x', $backqr); // code warna default filter
	$foreqr1 = preg_replace('/#/', '0x', $foreqr); // code warna filter

	$config['cacheable']		=	true; //boolean, the default is true
	$config['cachedir']			=	'./cache/';
	$config['errorlog']			=	'./logs/';
	$config['imagedir']			=	$pathqr; //direktori penyimpanan qr code
	$config['quality']			=	TRUE; //boolean, the default is true
	$config['size']					=	'1024'; //interger, the default is 1024
	$CI->ciqrcode->initialize($config);

	$image_name = $namaqr.'.png';

	$params['data'] = $isiqr; //data yang akan di jadikan QR CODE
	$params['level'] = 'H'; //H=High
	$params['size'] = $sizeqr; //Ukuran QR CODE
	$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder /desa/upload/media/
	if (!empty($foreqr1))
	{
		$params['fore_color'] = hexdec($foreqr1);
	}
	else
	{
		$params['back_color'] = hexdec($backqr1); //0x000000
	}
	$CI->ciqrcode->generate($params); // fungsi untuk generate QR CODE

	//ambil logo
	$logopath = $logoqr; // Logo yg tampil di tengah QRCode

	// ambil file qrcode
	$QR = imagecreatefrompng(FCPATH.$config['imagedir'].$image_name);

	// memulai menggambar logo dalam file qrcode
	// ambil file di server menggunakan absolute path, tidak menggunakan url
	$logo = imagecreatefromstring(file_get_contents($logopath));

	imagecolortransparent($logo, imagecolorallocatealpha($logo , 0, 0, 0, 127));
	imagealphablending($logo, false);
	imagesavealpha($logo, true);

	$QR_width = imagesx($QR);
	$QR_height = imagesy($QR);

	$logo_width = imagesx($logo);
	$logo_height = imagesy($logo);

	// Scale logo to fit in the QR Code
	$logo_qr_width = $QR_width/4;
	$scale = $logo_width/$logo_qr_width;
	$logo_qr_height = $logo_height/$scale;

	imagecopyresampled($QR, $logo, $QR_width/2.5, $QR_height/2.5, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

	// Simpan kode QR lagi, dengan logo di atasnya
	imagepng($QR, FCPATH.$config['imagedir'].$image_name);
}

?>
