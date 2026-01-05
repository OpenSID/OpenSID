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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Spatie\Image\Image;

class OgImage extends Web_Controller
{
    public function show($filename = null)
    {
        if (! $filename) {
            show_404();
        }

        // Sanitasi filename untuk mencegah path traversal
        $filename = basename($filename);

        // Validasi format filename (hanya alphanumeric, dash, underscore, dan titik)
        if (! preg_match('/^[\w\-\.]+$/', $filename)) {
            show_404();
        }

        $uploadDir = FCPATH . 'desa/upload/artikel/';
        $cacheDir  = FCPATH . 'desa/upload/cache/';

        // Pastikan folder cache ada
        if (! file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        // Cek apakah file asli ada (tanpa prefix 'kecil_')
        $srcPath = $uploadDir . 'kecil_' . $filename;

        // Validasi bahwa path tidak keluar dari direktori upload (path traversal protection)
        $realSrcPath   = realpath($srcPath);
        $realUploadDir = realpath($uploadDir);

        if ($realSrcPath === false || strpos($realSrcPath, $realUploadDir) !== 0) {
            show_404();
        }

        if (! file_exists($srcPath)) {
            show_404();
        }

        // Validasi tipe file yang diizinkan
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $ext               = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (! in_array($ext, $allowedExtensions)) {
            show_error('Unsupported image type', 415);
        }

        // Nama file cache versi PNG
        $cachedFilename = pathinfo($filename, PATHINFO_FILENAME) . '_og.png';
        $cachedPath     = $cacheDir . $cachedFilename;

        // Jika belum ada file cache → buat PNG baru menggunakan Spatie Image
        if (! file_exists($cachedPath)) {
            try {
                Image::load($srcPath)
                    ->format('png')
                    ->save($cachedPath);

            } catch (Exception $e) {
                log_message('error', 'OgImage conversion failed: ' . $e->getMessage());
                show_error('Failed to process image', 500);
            }
        }

        // Validasi file cache hasil konversi
        if (! file_exists($cachedPath)) {
            show_error('Cache file not found', 500);
        }

        // Tampilkan hasil cache
        header('Content-Type: image/png');
        header('Content-Length: ' . filesize($cachedPath));
        header('Cache-Control: public, max-age=31536000'); // Cache 1 tahun
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

        readfile($cachedPath);

        exit;
    }
}
