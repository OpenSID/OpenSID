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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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

if (! function_exists('get_csv')) {
    /**
     * Get data from a CSV file inside a ZIP archive.
     *
     * This function extracts and reads a CSV file from a ZIP archive, and returns the
     * data as an associative array where the keys are the CSV column headers.
     *
     * @param string $zipFile Path to the ZIP file.
     * @param string $csvFile The CSV file inside the ZIP archive.
     *
     * @throws Exception if the file cannot be read or parsed.
     *
     * @return array
     *
     * @see https://stackoverflow.com/questions/7391969/in-memory-download-and-extract-zip-archive
     * @see https://www.php.net/manual/en/function.str-getcsv.php
     * @see https://bugs.php.net/bug.php?id=55763
     */
    function get_csv($zipFile, $csvFile)
    {
        // Normalize file paths for Windows and Linux compatibility
        $zipFile = str_replace('\\', '/', $zipFile);
        $csvFile = str_replace('\\', '/', $csvFile);

        // Check if ZIP file exists
        if (! file_exists($zipFile)) {
            throw new Exception("ZIP file does not exist: {$zipFile}");
        }

        // Attempt to use ZipArchive if available
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipFile) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    if (preg_match('/\.(php|exe|js|sh|bat|cmd|msi|sys|dll|lnk|so)$/i', $stat['name'])) {
                        redirect_with('error', 'File tidak valid atau berbahaya ditemukan dalam arsip ZIP.', ci_route('keuangan_manual.impor_data'));
                    }
                }

                $index = $zip->locateName($csvFile);
                if ($index !== false) {
                    $fileData = $zip->getFromIndex($index);
                    $zip->close();

                    // Parse CSV content directly
                    $csv    = array_map('str_getcsv', preg_split('/\r\n|\n|\r/', trim($fileData)));
                    $header = $csv[0] ?? [];
                    $result = [];

                    foreach (array_slice($csv, 1) as $row) {
                        if (count($header) === count($row)) {
                            $result[] = array_combine($header, $row);
                        }
                    }

                    return $result;
                }

                throw new Exception("CSV file {$csvFile} not found in ZIP archive.");

            } else {
                throw new Exception("Unable to open ZIP file: {$zipFile}");
            }
        }

        // Fallback using zip:// stream (for Linux systems, or when ZipArchive is unavailable)
        $path     = sprintf('zip://%s#%s', $zipFile, $csvFile);
        $fileData = @file_get_contents($path);

        if ($fileData === false) {
            throw new Exception("Unable to read file from ZIP: {$path}");
        }

        // Parse CSV content directly
        $csv    = array_map('str_getcsv', preg_split('/\r\n|\n|\r/', trim($fileData)));
        $header = $csv[0] ?? [];
        $result = [];

        foreach (array_slice($csv, 1) as $row) {
            if (count($header) === count($row)) {
                $result[] = array_combine($header, $row);
            }
        }

        return $result;
    }
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
