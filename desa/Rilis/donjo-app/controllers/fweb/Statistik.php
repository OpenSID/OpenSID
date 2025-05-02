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

use App\Enums\Statistik\StatistikEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Libraries\Statistik as LibrariesStatistik;
use App\Models\Menu;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($slug = null): void
    {
        $key     = StatistikEnum::keyFromSlug($slug);
        $cekMenu = Menu::active()->where('link', 'statistik/' . $key)->first()?->isActive();

        $data = $this->includes;

        $label               = StatistikEnum::labelFromSlug($slug);
        $data['heading']     = $label;
        $data['stat']        = $this->sumberData($key);
        $data['tipe']        = 0;
        $data['slug_aktif']  = $slug;
        $data['last_update'] = Penduduk::latest()->first()->updated_at;
        $data['tampil']      = $cekMenu;
        $this->_get_common_data($data);
        $statistik     = getStatistikLabel($key, $label, $data['desa']['nama_desa']);
        $data['judul'] = $statistik['label'];
        $this->set_template('layouts/stat.tpl.php');
        theme_view($this->template, $data);
    }

    public function cetak($slug, $aksi = '')
    {
        $data              = $this->modal_penandatangan();
        $lap               = $this->getKeyFromSlug($slug);
        $tahun             = $this->input->get('tahun');
        $filter['tahun']   = $tahun;
        $label             = StatistikEnum::labelFromSlug($slug) ?? StatistikJenisBantuanEnum::allKeyLabel()[$lap];
        $statistik         = getStatistikLabel($lap, $label, identitas('nama_desa'));
        $query             = $this->sumberData($lap, $filter);
        $data['main']      = $query;
        $data['aksi']      = $aksi;
        $data['config']    = identitas();
        $data['file']      = 'Statistik penduduk';
        $data['isi']       = 'web.statistik.cetak';
        $data['judul']     = $statistik['label'];
        $data['letak_ttd'] = ['2', '2', '9'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    public function sumberData($lap, $filter = [], $paramCetak = [])
    {
        return $this->isBantuan($lap) ? LibrariesStatistik::bantuan($lap, $filter) : (new LaporanPenduduk())->listData($lap, $filter, $paramCetak);
    }

    public function modal_penandatangan()
    {
        return [
            'pamong'         => Pamong::penandaTangan()->get(),
            'pamong_ttd'     => Pamong::sekretarisDesa()->first(),
            'pamong_ketahui' => Pamong::kepalaDesa()->first(),
        ];
    }

    private function isBantuan($lap)
    {
        if (in_array($lap, array_keys(StatistikJenisBantuanEnum::allKeyLabel()))) {
            return true;
        }

        // Program bantuan berbentuk '50<program_id>'
        return (bool) ((int) $lap > 50 && substr($lap, 0, 2) == '50');
    }

    private function getKeyFromSlug($slug)
    {
        $key = StatistikEnum::keyFromSlug($slug) ?? StatistikJenisBantuanEnum::keyFromSlug($slug);
        if ($key) return $key;

        return $slug;
    }
}
