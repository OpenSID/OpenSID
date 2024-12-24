<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

define('FOTO_DEFAULT_PRIA', base_url('assets/images/pengguna/kuser.png'));
define('FOTO_DEFAULT_WANITA', base_url('assets/images/pengguna/wuser.png'));

define('MIME_TYPE_SIMBOL', serialize([
    'image/png',  'image/x-png',
]));

define('EXT_SIMBOL', serialize([
    '.png',
]));

define('MIME_TYPE_DOKUMEN', serialize([
    'application/x-download',
    'application/pdf',
    'application/ppt',
    'application/pptx',
    'application/excel',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/rtf',
    'application/powerpoint',
    'application/vnd.ms-powerpoint',
    'application/vnd.ms-excel',
    'application/msexcel',
]));

define('EXT_DOKUMEN', serialize([
    '.pdf', '.ppt', '.pptx', '.pps', '.ppsx',
    '.doc', '.docx', '.rtf', '.xls', '.xlsx',
]));

define('MIME_TYPE_GAMBAR', serialize([
    'image/jpeg', 'image/pjpeg',
    'image/png',  'image/x-png',
]));

define('EXT_GAMBAR', serialize([
    '.jpg', '.jpeg', '.png',
]));

define('MIME_TYPE_ARSIP', serialize([
    'application/rar', 'application/x-rar', 'application/x-rar-compressed', 'application/octet-stream',
    'application/zip', 'application/x-zip', 'application/x-zip-compressed',
]));

define('EXT_ARSIP', serialize([
    '.zip', '.rar',
]));

/**
 * Tambahkan suffix unik ke nama file
 *
 * @param string      $namaFile  Nama file asli (beserta ekstensinya)
 * @param bool        $urlEncode Saring nama file dengan urlencode() ?
 * @param string|null $delimiter String pemisah nama asli dengan unique id
 */
function tambahSuffixUniqueKeNamaFile($namaFile, $urlEncode = true, $delimiter = null): string
{
    $namaFile = preg_replace('/[^A-Za-z0-9\- .]/', '', $namaFile);

    // Delimiter untuk tambahSuffixUniqueKeNamaFile()
    $delimiterUniqueKey = null;

    // Type check
    $namaFile           = is_string($namaFile) ? $namaFile : (string) $namaFile;
    $delimiterUniqueKey = (! is_string($delimiter) || $delimiter === '')
        ? '__sid__' : $delimiter;

    // Pastikan nama file tidak mengandung string milik $this->delimiterUniqueKey
    $namaFile = str_replace($delimiterUniqueKey, '__', $namaFile);
    // Tambahkan suffix nama unik menggunakan uniqid()
    $namaFileUnik = explode('.', $namaFile);
    $ekstensiFile = end($namaFileUnik);
    unset($namaFileUnik[count($namaFileUnik) - 1]);
    $namaFileUnik = implode('.', $namaFileUnik);

    return urlencode($namaFileUnik) . $delimiterUniqueKey . generator() . '.' . $ekstensiFile;
    // Contoh return:
    // - nama asli = 'kitten.jpg'
    // - nama unik = 'kitten__sid__xUCc8KO.jpg'
}

/**
 * Ambil foto profil berdasarkan parameter
 */
function AmbilFoto(?string $foto, string $ukuran = 'kecil_', ?string $sex = '1'): string
{
    $sex       = $sex ?: '1';
    $file_foto = Foto_Default($foto, $sex);

    if ($foto == $file_foto) {
        $ukuran    = ($ukuran == 'kecil_') ? 'kecil_' : '';
        $file_foto = base_url(LOKASI_USER_PICT . $ukuran . $foto);

        if (! file_exists(FCPATH . LOKASI_USER_PICT . $ukuran . $foto)) {
            $file_foto = Foto_Default(null, $sex);
        }
    }

    return $file_foto;
}

/**
 * Ambil foto default berdasarkan parameter
 */
function Foto_Default(?string $foto, ?string $sex = '1'): string
{
    if (! in_array($foto, ['kuser.png', 'wuser.png']) && ($foto !== null && $foto !== '' && $foto !== '0')) {
        return $foto;
    }
    if (($foto == 'kuser.png') || $sex == 1) {
        return FOTO_DEFAULT_PRIA;
    }
    if (($foto == 'wuser.png') || $sex == 2) {
        return FOTO_DEFAULT_WANITA;
    }
}

/**
 * Unggah gambar widget
 */
function UploadGambarWidget(string $nama_file, string $lokasi_file, ?string $old_gambar): void
{
    $dir_upload = LOKASI_GAMBAR_WIDGET;
    if ($old_gambar) {
        unlink($dir_upload . $old_gambar);
    }
    $file_upload = $dir_upload . $nama_file;
    move_uploaded_file($lokasi_file, $file_upload);
}

/**
 * Unggah foto
 */
function UploadFoto(?string $fupload_name, ?string $old_foto, string $dimensi = '200x200'): bool
{
    $ci                      = &get_instance();
    $config['upload_path']   = LOKASI_USER_PICT;
    $config['allowed_types'] = 'jpg|png|jpeg';
    $ci->load->library('MY_Upload', null, 'upload');
    $ci->upload->initialize($config);

    if (! $ci->upload->do_upload('foto')) {
        session_error($ci->upload->display_errors());

        redirect($_SERVER['HTTP_REFERER']);
    }
    $uploadedImage = $ci->upload->data();
    if ($old_foto != '') {
        // Hapus old_foto
        unlink(LOKASI_USER_PICT . $old_foto);
    }
    $dimensi = generateDimensi($dimensi);
    ResizeGambar($uploadedImage['full_path'], LOKASI_USER_PICT . $fupload_name, ['width' => $dimensi['width'], 'height' => $dimensi['height']]);

    unlink($uploadedImage['full_path']);

    return true;
}

function UploadGambar(string $fupload_name, string $old_gambar): bool
{
    $vdir_upload = 'assets/front/slide/';
    if ($old_gambar != '') {
        unlink($vdir_upload . 'kecil_' . $old_gambar);
    }

    $vfile_upload = $vdir_upload . $fupload_name;

    move_uploaded_file($_FILES['gambar']['tmp_name'], $vfile_upload);

    $im_src     = imagecreatefromjpeg($vfile_upload);
    $src_width  = imagesx($im_src);
    $src_height = imagesy($im_src);
    if (($src_width * 25) < ($src_height * 44)) {
        $dst_width  = 440;
        $dst_height = ($dst_width / $src_width) * $src_height;
        $cut_height = $dst_height - 250;

        $im = imagecreatetruecolor(440, 250);
        imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);
    } else {
        $dst_height = 250;
        $dst_width  = ($dst_height / $src_height) * $src_width;
        $cut_width  = $dst_width - 440;

        $im = imagecreatetruecolor(440, 250);
        imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
    }
    imagejpeg($im, $vdir_upload . 'kecil_' . $fupload_name);

    imagedestroy($im_src);
    imagedestroy($im);

    unlink($vfile_upload);

    return true;
}

function AmbilGaleri(string $foto, string $ukuran)
{
    return base_url(LOKASI_GALERI . $ukuran . '_' . $foto);
}

// $file_upload = $_FILES['<lokasi>']
function TipeFile(array $file_upload)
{
    $lokasi_file = $file_upload['tmp_name'];
    if (empty($lokasi_file)) {
        return '';
    }
    if (isPHP($file_upload['tmp_name'], $file_upload['name'])) {
        return 'application/x-php';
    }
    if (function_exists('finfo_open')) {
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        $tipe_file = finfo_file($finfo, $lokasi_file);
        finfo_close($finfo);
    } else {
        $tipe_file = $file_upload['type'];
    }

    return $tipe_file;
}

// $file_upload = $_FILES['<lokasi>']
function UploadError(array $file_upload): bool
{
    // error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
    // TODO: pakai cara upload yg disediakan Codeigniter
    if ($file_upload['error'] == 1) {
        $upload_mb = max_upload();
        $_SESSION['error_msg'] .= ' -> Ukuran file melebihi batas ' . $upload_mb . ' MB';

        return true;
    }

    return false;
}

// $file_upload = $_FILES['<lokasi>']
function CekGambar(array $file_upload, string $tipe_file): bool
{
    $lokasi_file = $file_upload['tmp_name'];
    if (empty($lokasi_file)) {
        return false;
    }
    $nama_file = $file_upload['name'];
    $ext       = get_extension($nama_file);

    if (! in_array($tipe_file, unserialize(MIME_TYPE_GAMBAR)) || ! in_array($ext, unserialize(EXT_GAMBAR))) {
        $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file . ' ' . $ext;

        return false;
    }

    return true;
}

function UploadGallery(string $fupload_name, $old_foto = '', $tipe_file = ''): bool
{
    $ci                      = &get_instance();
    $config['upload_path']   = LOKASI_GALERI;
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $ci->load->library('upload');
    $ci->upload->initialize($config);

    if (! $ci->upload->do_upload('gambar')) {
        session_error($ci->upload->display_errors());
    } else {
        $uploadedImage = $ci->upload->data();
        ResizeGambar($uploadedImage['full_path'], LOKASI_GALERI . 'kecil_' . $fupload_name, ['width' => 440, 'height' => 440]);
        ResizeGambar($uploadedImage['full_path'], LOKASI_GALERI . 'sedang_' . $fupload_name, ['width' => 880, 'height' => 880]);
    }
    unlink($uploadedImage['full_path']);

    return true;
}

function UploadSimbolx(string $fupload_name, string $old_gambar): bool
{
    $vdir_upload = 'assets/gis/simbol';
    if ($old_gambar != '') {
        unlink($vdir_upload . 'kecil_' . $old_gambar);
        unlink($vdir_upload . $old_gambar);
    }
    $vfile_upload = $vdir_upload . $fupload_name;

    move_uploaded_file($_FILES['gambar']['tmp_name'], $vfile_upload);

    $im_src     = imagecreatefromjpeg($vfile_upload);
    $src_width  = imagesx($im_src);
    $src_height = imagesy($im_src);
    if (($src_width * 20) < ($src_height * 44)) {
        $dst_width  = 440;
        $dst_height = ($dst_width / $src_width) * $src_height;
        $cut_height = $dst_height - 300;

        $im = imagecreatetruecolor(440, 300);
        imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);
    } else {
        $dst_height = 300;
        $dst_width  = ($dst_height / $src_height) * $src_width;
        $cut_width  = $dst_width - 440;

        $im = imagecreatetruecolor(440, 300);
        imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
    }
    imagejpeg($im, $vdir_upload . 'kecil_' . $fupload_name);

    imagedestroy($im_src);
    imagedestroy($im);

    //unlink($vfile_upload);
    return true;
}

function AmbilFotoArtikel(string $foto, string $ukuran)
{
    return base_url(LOKASI_FOTO_ARTIKEL . $ukuran . '_' . $foto);
}

function UploadArtikel(string $fupload_name, $gambar): bool
{
    $ci                      = &get_instance();
    $config['upload_path']   = LOKASI_FOTO_ARTIKEL;
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $ci->load->library('upload');
    $ci->upload->initialize($config);

    if (! $ci->upload->do_upload($gambar)) {
        session_error($ci->upload->display_errors());

        return false;
    }
    $uploadedImage = $ci->upload->data();
    ResizeGambar($uploadedImage['full_path'], LOKASI_FOTO_ARTIKEL . 'kecil_' . $fupload_name, ['width' => 440, 'height' => 440]);
    ResizeGambar($uploadedImage['full_path'], LOKASI_FOTO_ARTIKEL . 'sedang_' . $fupload_name, ['width' => 880, 'height' => 880]);

    unlink($uploadedImage['full_path']);

    return true;
}

function HapusArtikel(?string $gambar): bool
{
    $vdir_upload  = LOKASI_FOTO_ARTIKEL;
    $vfile_upload = $vdir_upload . 'sedang_' . $gambar;
    unlink($vfile_upload);
    $vfile_upload = $vdir_upload . 'kecil_' . $gambar;
    unlink($vfile_upload);

    return true;
}

function UploadPeta(string $fupload_name, string $lokasi, $old_foto = null)
{
    $ci = &get_instance();
    $ci->load->library('MY_Upload', null, 'upload');
    $ci->upload->initialize([
        'upload_path'   => $lokasi,
        'allowed_types' => 'gif|jpg|png|jpeg',
    ]);

    if (! $ci->upload->do_upload('foto')) {
        session_error($ci->upload->display_errors(null, null));

        redirect($_SERVER['HTTP_REFERER']);
    } else {
        $uploadedImage = $ci->upload->data();
        ResizeGambar($uploadedImage['full_path'], $lokasi . 'kecil_' . $fupload_name, ['width' => 120, 'height' => 100]);
        ResizeGambar($uploadedImage['full_path'], $lokasi . 'sedang_' . $fupload_name, ['width' => 880, 'height' => 660]);

        unlink($uploadedImage['full_path']);

        // Hapus gambar lama
        if ($old_foto) {
            unlink($lokasi . 'kecil_' . $old_foto);
            unlink($lokasi . 'sedang_' . $old_foto);
        }

        return $fupload_name;
    }
}

function ResizeGambar($filename, $path, array $dimensi)
{
    $source_path = $filename;
    $target_path = $path;
    $imgdata     = exif_read_data($target_path, 'IFD0');

    $config_manip = [
        'image_library'  => 'gd2',
        'source_image'   => $source_path,
        'new_image'      => $target_path,
        'maintain_ratio' => true,
        'create_thumb'   => false,
        'thumb_marker'   => '_thumb',
        'width'          => $dimensi['width'],
        'height'         => $dimensi['height'],
    ];
    $ci = &get_instance();

    $ci->load->library('image_lib');
    $ci->image_lib->initialize($config_manip);
    if (! $ci->image_lib->resize()) {
        session_error($ci->image_lib->display_errors());

        return false;
    }
    $ci->image_lib->clear();

    //putar gambar
    $config['image_library'] = 'gd2';
    $config['source_image']  = $path;

    switch ($imgdata['Orientation']) {
        case 3:
            $config['rotation_angle'] = '180';
            break;

        case 6:
            $config['rotation_angle'] = '270';
            break;

        case 8:
            $config['rotation_angle'] = '90';
            break;
    }
    $ci->image_lib->initialize($config);
    $ci->image_lib->rotate();
}

// $dimensi = array("width"=>lebar, "height"=>tinggi)
function resizeImage($filepath_in, string $tipe_file, array $dimensi, $filepath_out = ''): bool
{
    // Hanya bisa resize jpeg atau png
    $mime_type_image = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];
    if (! in_array($tipe_file, $mime_type_image)) {
        $_SESSION['error_msg'] .= ' -> Jenis file tidak bisa di-resize: ' . $tipe_file;
        $_SESSION['success'] = -1;

        return false;
    }

    if (empty($filepath_out)) {
        $filepath_out = $filepath_in;
    }

    $is_png = ($tipe_file == 'image/png' || $tipe_file == 'image/x-png');

    $image      = ($is_png) ? imagecreatefrompng($filepath_in) : imagecreatefromjpeg($filepath_in);
    $width      = imagesx($image);
    $height     = imagesy($image);
    $new_width  = $dimensi['width'];
    $new_height = $dimensi['height'];
    if ($width > $new_width && $height > $new_height) {
        if ($width < $height) {
            $dst_width  = $new_width;
            $dst_height = ($dst_width / $width) * $height;
            $cut_height = $dst_height - $new_height;
            $cut_width  = 0;
        } else {
            $dst_height = $new_height;
            $dst_width  = ($dst_height / $height) * $width;
            $cut_width  = $dst_width - $new_width;
            $cut_height = 0;
        }

        $image_p = imagecreatetruecolor($new_width, $new_height);
        if ($is_png) {
            // http://stackoverflow.com/questions/279236/how-do-i-resize-pngs-with-transparency-in-php
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);
        }
        imagecopyresampled($image_p, $image, 0, 0, $cut_width, $cut_height, $dst_width, $dst_height, $width, $height);
        if ($is_png) {
            imagepng($image_p, $filepath_out, 5);
        } else {
            imagejpeg($image_p, $filepath_out);
        }
        imagedestroy($image_p);
        imagedestroy($image);
    } else {
        // Ukuran file tidak perlu di-resize
        copy($filepath_in, $filepath_out);
        imagedestroy($image);
    }

    return true;
}

/**   TODO: tulis ulang semua penggunaan supaya menggunakan resizeImage()
 * $jenis_upload contoh "logo", "foto"
 * $dimensi = array("width"=>lebar, "height"=>tinggi)
 * $lokasi contoh LOKASI_LOGO_DESA
 * $nama_simpan contoh "kecil_".$fupload_name
 *
 * @param mixed $lokasi
 * @param mixed $dimensi
 * @param mixed $jenis_upload
 * @param mixed $fupload_name
 * @param mixed $nama_simpan
 * @param mixed $old_foto
 * @param mixed $tipe_file
 */
function UploadResizeImage($lokasi, array $dimensi, $jenis_upload, string $fupload_name, string $nama_simpan, ?string $old_foto, string $tipe_file): bool
{
    // Hanya bisa upload jpeg atau png
    $mime_type_image = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];
    $ext_type_image  = ['.jpg', '.jpeg', '.png'];
    $ext             = get_extension($fupload_name);
    if (! in_array($tipe_file, $mime_type_image) || ! in_array($ext, $ext_type_image)) {
        $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file;
        $_SESSION['success'] = -1;

        return false;
    }

    $vdir_upload = $lokasi;
    if ($old_foto !== null && $old_foto !== '') {
        unlink($vdir_upload . $old_foto);
    }
    $filepath_in  = $vdir_upload . $fupload_name;
    $filepath_out = $vdir_upload . $nama_simpan;
    move_uploaded_file($_FILES[$jenis_upload]['tmp_name'], $filepath_in);

    $is_png = ($tipe_file == 'image/png' || $tipe_file == 'image/x-png');

    $image      = ($is_png) ? imagecreatefrompng($filepath_in) : imagecreatefromjpeg($filepath_in);
    $width      = imagesx($image);
    $height     = imagesy($image);
    $new_width  = $dimensi['width'];
    $new_height = $dimensi['height'];
    if ($width > $new_width && $height > $new_height) {
        $ratio_orig = $width / $height;
        $dst_width  = $new_width;
        $dst_height = $new_height;
        if ($new_width / $new_height > $ratio_orig) {
            $dst_width = $new_height * $ratio_orig;
        } else {
            $dst_height = $new_width / $ratio_orig;
        }

        $image_p = imagecreatetruecolor($new_width, $new_height);
        if ($is_png) {
            // http://stackoverflow.com/questions/279236/how-do-i-resize-pngs-with-transparency-in-php
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);
        }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $dst_width, $dst_height, $width, $height);
        if ($is_png) {
            imagepng($image_p, $filepath_out, 5);
        } else {
            imagejpeg($image_p, $filepath_out);
        }
        imagedestroy($image_p);
        imagedestroy($image);
    } else {
        // Ukuran file tidak perlu di-resize
        copy($filepath_in, $filepath_out);
        imagedestroy($image);
    }

    return true;
}

function UploadSimbol(string $fupload_name): void
{
    $vdir_upload  = 'assets/images/gis/point/';
    $vfile_upload = $vdir_upload . $fupload_name;

    move_uploaded_file($_FILES['simbol']['tmp_name'], $vfile_upload);
}

// Upload umum. Parameter lokasi dan file di $_FILES
function UploadKeLokasi(string $lokasi, $file, string $fupload_name, string $old_dokumen = ''): void
{
    $vfile_upload = $lokasi . $fupload_name;
    move_uploaded_file($file, $vfile_upload);
    unlink($lokasi . $old_dokumen);
}

function UploadDocument(string $fupload_name, string $old_dokumen = ''): void
{
    $vfile_upload = LOKASI_DOKUMEN . $fupload_name;
    move_uploaded_file($_FILES['satuan']['tmp_name'], $vfile_upload);
    unlink(LOKASI_DOKUMEN . $old_dokumen);
}

function UploadDocument2(string $fupload_name): bool
{
    $vdir_upload  = LOKASI_DOKUMEN;
    $vfile_upload = $vdir_upload . $fupload_name;
    move_uploaded_file($_FILES['dokumen']['tmp_name'], $vfile_upload);

    //unlink($vfile_upload);
    return true;
}

function UploadPengesahan(string $fupload_name): void
{
    $vdir_upload  = LOKASI_PENGESAHAN;
    $vfile_upload = $vdir_upload . $fupload_name;
    move_uploaded_file($_FILES['pengesahan']['tmp_name'], $vfile_upload);

    $im_src     = imagecreatefromjpeg($vfile_upload);
    $src_width  = imagesx($im_src);
    $src_height = imagesy($im_src);
    if (($src_width / $src_height) < (12 / 10)) {
        $dst_width  = 120;
        $dst_height = ($dst_width / $src_width) * $src_height;
        $cut_height = $dst_height - 100;

        $im = imagecreatetruecolor(120, 100);
        imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);
    } else {
        $dst_height = 100;
        $dst_width  = ($dst_height / $src_height) * $src_width;
        $cut_width  = $dst_width - 120;

        $im = imagecreatetruecolor(120, 100);
        imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
    }
}

/*
    Hasilkan nama file yg aman untuk digunakan di url
    source = https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
*/
function bersihkan_namafile($nama): string
{
    // Simpan extension
    $ext = get_extension($nama);
    // replace non letter or digits by -
    $nama = preg_replace('~[^\pL\d]+~u', '-', $nama);
    // transliterate
    $nama = iconv('utf-8', 'us-ascii//TRANSLIT', $nama);
    // remove unwanted characters
    $nama = preg_replace('~[^\-\w]+~', '', $nama);
    // trim
    $nama = trim($nama, '-');
    // remove duplicate -
    $nama = preg_replace('~-+~', '-', $nama);

    // lowercase
    return strtolower($nama . $ext);
}

function periksa_file($upload, $mime_types, $exts): string
{
    if (empty($_FILES[$upload]['tmp_name']) || (int) $_FILES[$upload]['size'] > convertToBytes(max_upload() . 'MB')) {
        return ' -> Error upload file. Periksa apakah melebihi ukuran maksimum';
    }

    $lokasi_file = $_FILES[$upload]['tmp_name'];
    if (empty($lokasi_file)) {
        return ' -> File tidak berhasil diunggah';
    }
    if (function_exists('finfo_open')) {
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        $tipe_file = finfo_file($finfo, $lokasi_file);
    } else {
        $tipe_file = $_FILES[$upload]['type'];
    }
    $nama_file = $_FILES[$upload]['name'];
    $nama_file = str_replace(' ', '-', $nama_file);    // normalkan nama file
    $ext       = get_extension($nama_file);

    if (! in_array($tipe_file, $mime_types) || ! in_array($ext, $exts)) {
        return ' -> Jenis file salah: ' . $tipe_file . ' ' . $ext;
    }
    if (isPHP($lokasi_file, $nama_file)) {
        return ' -> File berisi script ';
    }

    return '';
}

function qrcode_generate(array $qrcode = [], $base64 = false): string
{
    $sizeqr = $qrcode['sizeqr'];
    $foreqr = $qrcode['foreqr'];

    $barcodeobj = new TCPDF2DBarcode($qrcode['isiqr'], 'QRCODE,H');

    if (! empty($foreqr)) {
        if ($foreqr[0] == '#') {
            $foreqr = substr($foreqr, 1);
        }
        $split = str_split($foreqr, 2);
        $r     = hexdec($split[0]);
        $g     = hexdec($split[1]);
        $b     = hexdec($split[2]);
    }

    //Hasilkan QRCode
    $imgData  = $barcodeobj->getBarcodePngData($sizeqr, $sizeqr, [$r, $g, $b]);
    $filename = sys_get_temp_dir() . '/qrcode_' . date('Y_m_d_H_i_s') . '_temp.png';
    file_put_contents($filename, $imgData);

    //Ubah backround transparan ke warna putih supaya terbaca qrcode scanner
    $src_qr    = imagecreatefrompng($filename);
    $sizeqrx   = imagesx($src_qr);
    $sizeqry   = imagesy($src_qr);
    $backcol   = imagecreatetruecolor($sizeqrx, $sizeqry);
    $newwidth  = $sizeqrx;
    $newheight = ($sizeqry / $sizeqrx) * $newwidth;
    $color     = imagecolorallocatealpha($backcol, 255, 255, 255, 1);
    imagefill($backcol, 0, 0, $color);
    imagecopyresampled($backcol, $src_qr, 0, 0, 0, 0, $newwidth, $newheight, $sizeqrx, $sizeqry);
    imagepng($backcol, $filename);
    imagedestroy($src_qr);
    imagedestroy($backcol);

    //Tambah Logo
    $logopath = $qrcode['logoqr']; // Logo yg tampil di tengah QRCode
    $QR       = imagecreatefrompng($filename);
    $logo     = imagecreatefromstring(file_get_contents($logopath));
    imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
    imagealphablending($logo, false);
    imagesavealpha($logo, true);
    $QR_width       = imagesx($QR);
    $logo_width     = imagesx($logo);
    $logo_height    = imagesy($logo);
    $logo_qr_width  = $QR_width / 4;
    $scale          = $logo_width / $logo_qr_width;
    $logo_qr_height = $logo_height / $scale;
    $from_width     = ($QR_width - $logo_qr_width) / 2;
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
    imagepng($QR, $filename);
    imagedestroy($QR);

    if ($base64) {
        return 'data:image/png;base64,' . base64_encode(file_get_contents($filename));
    }

    return $filename;
}

function upload_foto_penduduk(?string $nama_file = '', ?string $dimensi = '')
{
    $foto     = $_POST['foto'];
    $old_foto = $_POST['old_foto'];

    if ($nama_file) {
        $nama_file = time() . random_int(10000, 999999);
    }

    if ($_FILES['foto']['tmp_name']) {
        $nama_file .= get_extension($_FILES['foto']['name']);
        UploadFoto($nama_file, $old_foto, $dimensi);
    } elseif ($foto) {
        $nama_file .= '.png';
        $foto = str_replace('data:image/png;base64,', '', $foto);
        $foto = base64_decode($foto, true);

        if (isset($old_foto)) {
            // Hapus old_foto
            unlink(LOKASI_USER_PICT . $old_foto);
            unlink(LOKASI_USER_PICT . 'kecil_' . $old_foto);
        }

        file_put_contents(LOKASI_USER_PICT . $nama_file, $foto);
        file_put_contents(LOKASI_USER_PICT . 'kecil_' . $nama_file, $foto);
    } else {
        $nama_file = null;
    }

    return $nama_file;
}

function to_base64($file): string
{
    $type = pathinfo($file, PATHINFO_EXTENSION);
    $data = file_get_contents($file);

    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function home_noimage(): string
{
    return to_base64(LOKASI_FILES_LOGO . 'home.png');
}

function unggah_file(array $config = [], $old_file = null)
{
    $ci = &get_instance();
    $ci->load->library('MY_Upload', null, 'upload');
    $ci->upload->initialize($config);

    if (! $ci->upload->do_upload('file')) {
        session_error($ci->upload->display_errors(null, null));

        redirect($_SERVER['HTTP_REFERER']);
    }

    $data = $ci->upload->data();

    if ($old_file) {
        unlink($config['upload_path'] . $old_file);
    }

    return $data['file_name'];
}

function generateDimensi($dimensi)
{
    [$width, $height] = explode('x', $dimensi);
    $width            = bilangan($width) ? (int) (bilangan($width)) : 200;
    $height           = bilangan($height) ? (int) (bilangan($height)) : 200;

    return ['width' => $width, 'height' => $height];
}
