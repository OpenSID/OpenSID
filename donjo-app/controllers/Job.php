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

use App\Libraries\FlxZipArchive;
use App\Models\LogBackup;
use App\Models\LogRestoreDesa;

defined('BASEPATH') || exit('No direct script access allowed');

class Job extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['number', 'file']);
        $this->load->model(['ekspor_model', 'database_model']);
    }

    public function restore($database = null): void
    {
        if (ENVIRONMENT !== 'production' || (! config_item('demo_mode') && ENVIRONMENT === 'production')) {
            show_404();
        }

        kosongkanFolder(config_item('log_path'));
        log_message('notice', '>_ Mulai');

        // Kecuali folder
        $exclude = [
            'desa/config',
            'desa/themes',
        ];

        // Kosongkan folder desa
        foreach (glob('desa/*', GLOB_ONLYDIR) as $folder) {
            if (! in_array($folder, $exclude)) {
                delete_files(FCPATH . $folder, true);
            }
        }

        // Buat folder desa
        folder_desa();

        // Proses Restore Database
        if ($this->ekspor_model->proses_restore($this->cekDB($database ?? 'contoh_data_awal'))) {
            $this->database_model->migrasi_db_cri();
        } else {
            log_message('error', 'Proses Restore Database Gagal');
        }

        cache()->flush();
        kosongkanFolder('storage/framework/views/');

        log_message('notice', '>_ Selesai');
    }

    private function cekDB($filename): string|false
    {
        $filename = DESAPATH . "/config/{$filename}.sql";

        if (file_exists($filename)) {
            return $filename;
        }

        log_message('error', 'File ' . $filename . ' tidak ditemukan');

        return false;
    }

    public function backup_inkremental($lokasi): void
    {
        /*
        variable status
        0 = sedang dalam prosess
        1 = selesai diproses
        2 = selesai di download
        3 = dibatalkan
        */

        $lokasi      = ($lokasi == 'null') ? null : 'backup_inkremental';
        $last_backup = LogBackup::latest()->first()->created_at;
        $last_backup = ($last_backup != null) ? $last_backup->format('Y-m-d') : '1990-01-01';
        $backup      = LogBackup::create(['permanen' => ($lokasi) ? 1 : 0, 'pid_process' => getmypid()]); // tandai backup sedang berlangsung

        try {
            $za = new FlxZipArchive();

            $path        = $za->read_dir(DESAPATH, $last_backup, $lokasi);
            $file_backup = get_file_info($path);
            $backup->update(['status' => 1, 'ukuran' => byte_format($file_backup['size']), 'path' => $path]); // update backup sudah selesai
        } catch (Exception $e) {
            $backup->update(['status' => -1]); // update backup gagal
            printf($e);
        }
    }

    public function restore_desa($id): void
    {
        /*
        variable status
        0 = sedang dalam prosess
        1 = selesai diproses
        2 = selesai di download
        3 = dibatalkan
        -1 = gagal restore
        */

        $restore = LogRestoreDesa::where('id', '=', $id)->first();
        $restore->update(['pid_process' => getmypid()]);

        try {
            $zip = new ZipArchive();
            $res = $zip->open($restore->path);
            if ($res === true) {
                // Unzip path
                $extractpath = DESAPATH . '..';

                // Extract file
                $zip->extractTo($extractpath);
                $zip->close();
                $restore->update(['status' => 1]);
            }
        } catch (Exception $e) {
            $restore->update(['status' => -1]); // update backup gagal
            printf($e);
        }
    }
}
