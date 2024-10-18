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

use App\Libraries\Release;
use App\Models\Shortcut;

defined('BASEPATH') || exit('No direct script access allowed');

class Beranda extends Admin_Controller
{
    public $isAdmin;
    public $modul_ini           = 'beranda';
    public $kategori_pengaturan = 'beranda';

    public function __construct()
    {
        parent::__construct();
        $this->isAdmin = $this->session->isAdmin->pamong;
    }

    public function index()
    {
        get_pesan_opendk(); //ambil pesan baru di opendk

        $this->load->library('saas');
        $data = [
            'rilis'           => $this->getUpdate(),
            'shortcut'        => Shortcut::querys()['data'],
            'saas'            => $this->saas->peringatan(),
            'notif_langganan' => $this->pelanggan_model->status_langganan(),
        ];

        return view('admin.home.index', $data);
    }

    private function getUpdate(): array
    {
        $info = [];

        if (cek_koneksi_internet() && ! config_item('demo_mode')) {
            $url_rilis = config_item('rilis_umum');

            $release = new Release();
            $release->setApiUrl($url_rilis)->setCurrentVersion();

            if ($release->isAvailable()) {
                $info['update_available'] = $release->isAvailable();
                $info['current_version']  = 'v' . AmbilVersi();
                $info['latest_version']   = $release->getLatestVersion();
                $info['release_name']     = $release->getReleaseName();
                $info['release_body']     = $release->getReleaseBody();
                $info['url_download']     = $release->getReleaseDownload();
            } else {
                $info['update_available'] = false;
            }
        }

        return $info;
    }
}
