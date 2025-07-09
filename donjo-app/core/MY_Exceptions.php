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

class MY_Exceptions extends CI_Exceptions
{
    /**
     * List shared mysql/mariadb error code.
     *
     * @see https://mariadb.com/kb/en/mariadb-error-codes
     *
     * @var array
     */
    protected $db_error_codes = [1029, 1049, 1051, 1054, 1062, 1067, 1072, 1109, 1138, 1146, 1166, 1169, 1173, 1176, 1265, 1271, 1292, 1364, 1406, 1978];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Untuk menangkap exception yg khusus untuk kasus2 tidak otomatis, misalnya bukan exception database
     * Lihat contoh di list_persil_kelas() di donjo-app/models/Data_persil_model.php
     *
     * @param mixed $severity
     * @param mixed $message
     * @param mixed $filepath
     * @param mixed $line
     */
    public function log_exception($severity, $message, $filepath, $line): void
    {
        parent::log_exception($severity, $message, $filepath, $line);
        if (preg_match('/\\[PERIKSA\\]/', $message)) {
            $_SESSION['db_error'] = [
                'code'    => 99001,
                'message' => '<p>' . $message . '</p>',
            ];
            $_SESSION['heading']           = 'Error ditemukan pada isi data';
            $_SESSION['message_query']     = '<p>Error ditemukan di file' . $filepath . 'pada baris ' . $line . '</p>';
            $_SESSION['message_exception'] = strip_tags((new Exception())->getTraceAsString());

            redirect('periksa');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        if ($template !== 'error_db') {
            return parent::show_error($heading, $message, $template, $status_code);
        }

        if (preg_match('/Aplikasi tidak bisa terhubung ke database/', $message[0])) {
            $_SESSION['error_koneksi'] = true;
            $error['code']             = 1049;
            $error['message']          = 'Aplikasi tidak bisa terhubung ke database';
        }

        $error = $error ?: get_instance()?->db?->error();
        if ($error !== [] && in_array($error['code'], $this->db_error_codes)) {
            $_SESSION['db_error']          = $error;
            $_SESSION['message']           = '<p>' . (is_array($error) ? implode('</p><p>', $error) : $error) . '</p>';
            $_SESSION['heading']           = $heading;
            $_SESSION['message_query']     = '<p>' . (is_array($message) ? implode('</p><p>', $message) : $message) . '</p>';
            $_SESSION['message_exception'] = strip_tags((new Exception())->getTraceAsString());
            /*
            | 1049 adalah kode koneksi database gagal. Dalam hal ini tampilkan halaman khusus
            | menjelaskan langkah yang dapat dilakukan untuk mengatasi.
            */
            redirect('koneksi-database');

            // redirect('periksa');
        }
        log_message('error', json_encode($message));

        return parent::show_error($heading, $message, $template, $status_code);
    }
}
