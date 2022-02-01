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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Job extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model(['export_model', 'database_model']);
    }

    public function restore($database = null)
    {
        $ip = 'IP : ' . $this->input->ip_address();

        if (! $this->input->is_cli_request()) {
            // echo 'Skrip ini hanya dapat diakses melalui baris perintah' . PHP_EOL;
            log_message('error', $ip . ' | Skrip ini hanya dapat diakses melalui baris perintah');

            show_404();
        }

        if (! config_item('demo_mode')) {
            // echo 'Skrip ini hanya dapat diakses melalui web demo' . PHP_EOL;
            log_message('error', $ip . ' | Skrip ini hanya dapat diakses melalui web demo');
        }

        delete_files(config_item('log_path'), true);
        log_message('error', 'Mulai normalkan website demo');
        log_message('error', 'Hapus folder logs');

        // Kosongkan folder desa dan copy isi folder desa-contoh
        foreach (glob('desa/*', GLOB_ONLYDIR) as $folder) {
            if ($folder != 'desa/config') {
                delete_files(FCPATH . $folder, true);
            }
        }
        xcopy('desa-contoh', 'desa', ['config']);
        log_message('error', 'Normalkan folder desa');

        // Proses Restore Database
        if ($this->export_model->proses_restore($this->cek_db($database))) {
            log_message('error', 'Proses Restore Database Berhasil');

            // Proses migrasi database
            log_message('error', 'Proses Migrasi Database');
            $this->database_model->migrasi_db_cri();
        } else {
            log_message('error', 'Proses Restore Database Gagal');
        }

        log_message('error', 'Selesai normalkan website demo');
    }

    private function cek_db($filename = null)
    {
        if (! $filename) {
            $versi    = AmbilVersi();
            $versi    = preg_replace('/-premium.*|pasca-|-pasca/', '', $versi);
            $filename = FCPATH . 'contoh_data_awal_' . str_replace('.', '', '20' . $versi) . '01.sql';
        }

        return $this->cek_file($filename);
    }

    private function cek_file($filename = null)
    {
        if (file_exists($filename)) {
            return $filename;
        }

        log_message('error', 'File ' . $filename . ' tidak ditemukan');

        return false;
    }
}
