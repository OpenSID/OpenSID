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

defined('BASEPATH') || exit('No direct script access allowed');

class Info_sistem extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'info-sistem';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('directory');
    }

    public function index(): void
    {
        // Logs viewer
        $this->load->library('Log_Viewer');

        $data                      = $this->log_viewer->showLogs();
        $data['ekstensi']          = $this->setting_model->cekEkstensi();
        $data['kebutuhan_sistem']  = $this->setting_model->cekKebutuhanSistem();
        $data['php']               = $this->setting_model->cekPhp();
        $data['mysql']             = $this->setting_model->cekDatabase();
        $data['disable_functions'] = $this->setting_model->disableFunctions();
        $data['check_permission']  = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 0 : 1;

        // $data['free_space']        = $this->convertDisk(disk_free_space('/'));
        // $data['total_space']       = $this->convertDisk(disk_total_space('/'));
        $data['disk'] = false;

        $this->render('setting/info_sistem/index', $data);
    }

    public function remove_log(): void
    {
        $this->redirect_hak_akses('h');

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

    public function cache_desa(): void
    {
        $this->redirect_hak_akses('u');

        cache()->flush();

        status_sukses(true);

        redirect($this->controller);
    }

    public function cache_blade(): void
    {
        $this->redirect_hak_akses('u');

        kosongkanFolder(config_item('cache_blade'));

        status_sukses(true);

        redirect($this->controller);
    }

    public function set_permission_desa(): void
    {
        $this->redirect_hak_akses('u');

        $dirs   = $_POST['folders'];
        $error  = [];
        $result = ['status' => 1, $message = 'Berhasil ubah permission folder desa'];

        foreach ($dirs as $dir) {
            if (! chmod($dir, DESAPATHPERMISSION)) {
                $error[] = 'Gagal mengubah hak akses folder ' . $dir;
            }
        }

        if ($error !== []) {
            $result['status']  = 0;
            $result['message'] = implode('<br />', $error);
        }

        status_sukses(true);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result, JSON_THROW_ON_ERROR));
    }
}
