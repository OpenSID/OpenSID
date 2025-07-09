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

use App\Enums\Statistik\StatistikEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Models\Pamong;
use App\Models\PendudukSaja;
use App\Repositories\StatistikRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($slug = null)
    {
        $key = $this->getKeyFromSlug($slug);
        $this->hak_akses_menu('statistik/' . $key);

        $label                   = StatistikEnum::labelFromSlug($slug) ?? StatistikJenisBantuanEnum::allKeyLabel()[$key];
        $data['heading']         = $label;
        $data['slug_aktif']      = $slug;
        $data['key']             = $key;
        $data['last_update']     = PendudukSaja::select(['updated_at'])->latest()->first()->updated_at;
        $statistik               = getStatistikLabel($key, $label, identitas('nama_desa'));
        $data['judul']           = $statistik['label'];
        $data['statistik_aktif'] = menu_statistik_aktif();
        $data['bantuan']         = $this->isBantuan($key);
        if ($data['bantuan']) {
            $selectedTahun              = request()->get('tahun');
            $data['list_tahun']         = range(date('Y'), date('Y') - 5);
            $data['selected_tahun']     = $selectedTahun;
            $data['default_chart_type'] = 'column';
        }

        return view('theme::partials.statistik.index', $data);
    }

    public function cetak($slug, $aksi = '')
    {
        $data              = $this->modal_penandatangan();
        $lap               = $this->getKeyFromSlug($slug);
        $tahun             = $this->input->get('tahun');
        $filter['tahun']   = $tahun;
        $label             = StatistikEnum::labelFromSlug($slug) ?? StatistikJenisBantuanEnum::allKeyLabel()[$lap];
        $statistik         = getStatistikLabel($lap, $label, identitas('nama_desa'));
        $query             = (new StatistikRepository())->sumberData($lap, $filter);
        $data['main']      = $query;
        $data['aksi']      = $aksi;
        $data['config']    = identitas();
        $data['file']      = 'Statistik penduduk';
        $data['isi']       = 'web.statistik.cetak';
        $data['judul']     = $statistik['label'];
        $data['letak_ttd'] = ['2', '2', '9'];

        return view('theme::admin.layouts.components.format_cetak', $data);
    }

    public function modal_penandatangan()
    {
        return [
            'pamong'         => Pamong::penandaTangan()->get(),
            'pamong_ttd'     => Pamong::sekretarisDesa()->first(),
            'pamong_ketahui' => Pamong::kepalaDesa()->first(),
        ];
    }

    private function getKeyFromSlug($slug)
    {
        $key = StatistikEnum::keyFromSlug($slug) ?? StatistikJenisBantuanEnum::keyFromSlug($slug);
        if ($key != '') return $key;

        return $slug;
    }

    private function isBantuan($lap)
    {
        if (in_array($lap, array_keys(StatistikJenisBantuanEnum::allKeyLabel()))) {
            return true;
        }

        // Program bantuan berbentuk '50<program_id>'
        return (bool) ((int) $lap > 50 && substr($lap, 0, 2) == '50');
    }
}
