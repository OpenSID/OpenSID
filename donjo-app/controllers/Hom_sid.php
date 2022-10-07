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

use App\Libraries\Parsedown;
use App\Libraries\Release;
use App\Models\Bantuan;
use App\Models\Kelompok;
use App\Models\Keluarga;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\Rtm;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Hom_sid extends Admin_Controller
{
    private $viewPath = 'admin.home';

    public function index()
    {
        $this->modul_ini = 1;

        $data = [
            'rilis'         => $this->getUpdate(),
            'bantuan'       => $this->bantuan(),
            'penduduk'      => Penduduk::status()->count(),
            'keluarga'      => Keluarga::status()->count(),
            'rtm'           => Rtm::status()->count(),
            'kelompok'      => Kelompok::status()->tipe()->count(),
            'dusun'         => Wilayah::dusun()->count(),
            'pendaftaran'   => PendudukMandiri::status()->count(),
            'surat'         => LogSurat::count(),
            'catatan_rilis' => $this->catatan(),
        ];

        return view("{$this->viewPath}.index", $data);
    }

    private function getUpdate()
    {
        if (cek_koneksi_internet() && ! config_item('demo_mode')) {
            $url_rilis = config_item('rilis_umum');

            $release = new Release();
            $release->set_api_url($url_rilis)
                ->set_interval(7)
                ->set_current_version(null)
                ->set_cache_folder(config_item('cache_path'));

            $info['update_available'] = $release->is_available();
            $info['current_version']  = 'v' . VERSION;
            $info['latest_version']   = $release->get_latest_version();
            $info['release_name']     = $release->get_release_name();
            $info['release_body']     = $release->get_release_body();
            $info['url_download']     = $release->get_release_download();
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

    private function catatan()
    {
        $catatan = new Parsedown();

        return $catatan->text(file_get_contents('catatan_rilis.md'));
    }
}
