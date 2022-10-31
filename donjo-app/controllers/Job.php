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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
        $this->load->model(['ekspor_model', 'database_model']);
    }

    public function restore($database = null)
    {
        /**
         * Job hanya bisa digunakan jika :
         * 1. Diakses lewat CLI dan Config demo true
         * 2. Diakses lewat CLI dan ENV development
         *
         * Selain itu akan menampilkan halaman tidak ditemukan.
         */
        if (! is_cli() || (! config_item('demo_mode') && ENVIRONMENT === 'production')) {
            show_404();
        }

        delete_files(config_item('log_path'), true);
        log_message('error', '>_ Mulai');

        // Kecuali folder
        $exclude = [
            'desa/config',
            'desa/themes',
        ];

        // Kosongkan folder desa dan copy isi folder desa-contoh
        foreach (glob('desa/*', GLOB_ONLYDIR) as $folder) {
            if (! in_array($folder, $exclude)) {
                delete_files(FCPATH . $folder, true);
            }
        }
        xcopy('desa-contoh', 'desa', ['config']);

        // Proses Restore Database
        if ($this->ekspor_model->proses_restore($this->cekDB($database))) {
            $this->database_model->migrasi_db_cri();
        } else {
            log_message('error', 'Proses Restore Database Gagal');
        }

        log_message('error', '>_ Selesai');
    }

    private function cekDB($filename = null)
    {
        if (! $filename) {
            $filename = FCPATH . 'contoh_data_awal_' . str_replace('.', '', '20' . currentVersion()) . '01.sql';
        }

        return $this->cekFile($filename);
    }

    private function cekFile($filename = null)
    {
        if (file_exists($filename)) {
            return $filename;
        }

        log_message('error', 'File ' . $filename . ' tidak ditemukan');

        return false;
    }
}
