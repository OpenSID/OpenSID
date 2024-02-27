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

use App\Models\Rtm;
use App\Models\Modul;
use App\Models\Bantuan;
use App\Models\Wilayah;
use App\Models\Kelompok;
use App\Models\Keluarga;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\Shortcut;
use App\Libraries\Release;
use App\Models\RefJabatan;
use App\Models\PendudukMandiri;

defined('BASEPATH') || exit('No direct script access allowed');

class Hom_sid extends Admin_Controller
{
    public $isAdmin;

    public function __construct()
    {
        parent::__construct();

        $this->isAdmin = $this->session->isAdmin->pamong;
    }

    public function index()
    {
        get_pesan_opendk(); //ambil pesan baru di opendk

        $this->modul_ini = 'home';

        $this->load->library('saas');

        $data = [
            'shortcut'        => Shortcut::status(Shortcut::ACTIVE)->orderBy('urutan')->get()->toArray(),
            'rilis'           => $this->getUpdate(),
            'saas'            => $this->saas->peringatan(),
            'notif_langganan' => $this->pelanggan_model->status_langganan(),
        ];

        return view('admin.home.index', $data);
    }

    private function getUpdate()
    {
        if (cek_koneksi_internet() && ! config_item('demo_mode')) {
            $url_rilis = ($this->premium->validasi_akses() && PREMIUM) ? config_item('rilis_premium') : config_item('rilis_umum');

            $release = new Release();
            $release->setApiUrl($url_rilis)->setCurrentVersion($this->versi_setara);

            if ($release->isAvailable()) {
                $info['update_available'] = $release->isAvailable();
                $info['current_version']  = 'v' . AmbilVersi();
                $info['latest_version']   = $release->getLatestVersion() . (PREMIUM ? '-premium' : '');
                $info['release_name']     = $release->getReleaseName();
                $info['release_body']     = $release->getReleaseBody();
                $info['url_download']     = $release->getReleaseDownload();

                if ($this->versi_setara) {
                    $info['current_version'] .= '(' . $release->getCurrentVersion() . ')';
                }
            } else {
                $info['update_available'] = false;
            }
        }

        return $info;
    }

    private function bantuan()
    {
        $program                = Bantuan::with('peserta')->whereId($this->setting->dashboard_program_bantuan)->first();
        $bantuan['jumlah']      = $program ? $program->peserta->count() : Bantuan::status()->count();
        $bantuan['nama']        = $program ? $program->nama : 'Bantuan';
        $bantuan['link_detail'] = $program ? ('statistik/clear/50' . $this->setting->dashboard_program_bantuan) : 'program_bantuan';
        $bantuan['program']     = Bantuan::status()->pluck('nama', 'id');

        return $bantuan;
    }
}
