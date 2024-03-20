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

use App\Libraries\Sistem;

defined('BASEPATH') || exit('No direct script access allowed');

class Info_sistem extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'info-sistem';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('directory');
    }

    public function index()
    {
        // Logs viewer
        $this->load->library('Log_Viewer');

        $data                      = $this->log_viewer->showLogs();
        $data['ekstensi']          = Sistem::cekEkstensi();
        $data['kebutuhan_sistem']  = Sistem::cekKebutuhanSistem();
        $data['php']               = Sistem::cekPhp();
        $data['mysql']             = Sistem::cekDatabase();
        $data['disable_functions'] = Sistem::disableFunctions();
        $data['check_permission']  = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 0 : 1;
        $data['controller']        = $this->controller;
        // $data['free_space']        = $this->convertDisk(disk_free_space('/'));
        // $data['total_space']       = $this->convertDisk(disk_total_space('/'));
        $data['disk'] = false;

        return view('admin.setting.info_sistem.index', $data);
    }

    public function remove_log(): void
    {
        isCan('h');
        $path = config_item('log_path');
        $file = base64_decode($this->input->get('f'), true);

        if ($this->input->post()) {
            $files = $this->input->post('id_cb');

            foreach ($files as $file) {
                $file = $path . basename($file);
                unlink($file);
            }

            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function cache_desa(): void
    {
        isCan('u');

        cache()->flush();

        redirect_with('success', 'Berhasil Hapus Cache');
    }

    public function cache_blade(): void
    {
        isCan('u');

        kosongkanFolder('storage/framework/views/');

        redirect_with('success', 'Berhasil Hapus Cache');
    }

    public function set_permission_desa(): void
    {
        isCan('u');

        $dirs   = $_POST['folders'];
        $error  = [];
        $result = ['status' => 1, 'message' => 'Berhasil ubah permission folder desa'];

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
