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

/**
 * Menghasilkan csv dari data tabel
 * Baris pertama berisi nama kolom
 * Saat ini pemisah menggunakan ','
 * Acuan: https://stackoverflow.com/questions/4249432/export-to-csv-via-php
 *
 * @param string	nama tabel yang akan diekspor
 * @param mixed $table
 *
 * @return string
 */
function tulis_csv($table)
{
    $data = $this->db->where('config_id', identitas('id'))->get($table)->result_array();
    if (count($data) == 0) {
        return null;
    }

    ob_start();
    $df = fopen('php://output', 'wb');
    fputcsv($df, array_keys(reset($data)));

    foreach ($data as $row) {
        fputcsv($df, $row);
    }
    fclose($df);

    return ob_get_clean();
}

/**
 * https://stackoverflow.com/questions/7391969/in-memory-download-and-extract-zip-archive
 * https://www.php.net/manual/en/function.str-getcsv.php
 * https://bugs.php.net/bug.php?id=55763
 *
 * Contoh yg dihasilkan:
 *
 * Array
 * (
 * [0] => Array
 * (
 * [Kd_Bid] => 01
 * [Nama_Bidang] => Bidang Penyelenggaraan Pemerintah Desa
 * )
 *
 * [1] => Array
 * (
 * [Kd_Bid] => 02
 * [Nama_Bidang] => Bidang Pelaksanaan Pembangunan Desa
 * )
 * )
 *
 * @param mixed $zip_file
 * @param mixed $file_in_zip
 *
 * @return mixed[]
 */
function get_csv($zip_file, $file_in_zip): array
{
    // read the file's data:
    $path      = sprintf('zip://%s#%s', $zip_file, $file_in_zip);
    $file_data = file_get_contents($path);
    //$file_data = preg_split('/[\r\n]{1,2}(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/', $file_data);
    $file_data = preg_split('/\r*\n+|\r+/', $file_data);
    $csv       = array_map('str_getcsv', $file_data);
    $result    = [];
    $header    = $csv[0];

    foreach ($csv as $key => $value) {
        if (! $key) {
            continue;
        }
        if (count($header) === count($value)) {
            $result[] = array_combine($csv[0], $value);
        }
    }

    return $result;
}

/**
 * Paksa download file
 *
 * @param string	nama file untuk didownload
 * @param mixed $filename
 */
function download_send_headers($filename): void
{
    // disable caching
    $now = gmdate('D, d M Y H:i:s');
    header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
    header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
    header("Last-Modified: {$now} GMT");

    // force download
    header('Content-Type: application/force-download');
    header('Content-Type: application/octet-stream');
    header('Content-Type: application/download');

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header('Content-Transfer-Encoding: binary');
}

function duplicate_key_update_str($data): string
{
    $update_str = '';

    foreach ($data as $key => $item) {
        $update_str .= $key . '=VALUES(' . $key . '),';
    }

    return ' ON DUPLICATE KEY UPDATE ' . rtrim($update_str, ', ');
}
