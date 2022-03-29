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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Info_sistem extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 11;
        $this->sub_modul_ini = 46;
    }

    public function index()
    {
        // Logs viewer
        $this->load->library('Log_Viewer');

        $data                      = $this->log_viewer->showLogs();
        $data['ekstensi']          = $this->setting_model->cekEkstensi();
        $data['php']               = $this->setting_model->cekPhp();
        $data['mysql']             = $this->setting_model->cekDatabase();
        $data['disable_functions'] = $this->setting_model->disableFunctions();
        // $data['free_space']        = $this->convertDisk(disk_free_space('/'));
        // $data['total_space']       = $this->convertDisk(disk_total_space('/'));
        $data['disk'] = false;

        $this->render('setting/info_sistem/index', $data);
    }

    public function remove_log()
    {
        $path = config_item('log_path');
        $file = base64_decode($this->input->get('f'), true);

        if ($this->input->post()) {
            $files = $this->input->post('id_cb');

            foreach ($files as $file) {
                $file = $path . basename($file);
                unlink($file);
            }
        }

        redirect($this->controller);
    }

    private function convertDisk($disk)
    {
        $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
        $base      = 1024;
        $class     = min((int) log($disk, $base), count($si_prefix) - 1);

        return sprintf('%1.2f', $disk / $base ** $class) . ' ' . $si_prefix[$class] . '<br />';
    }
}
