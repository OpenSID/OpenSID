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

use App\Libraries\Stunting;
use App\Models\Anak;
use App\Models\IbuHamil;

defined('BASEPATH') || exit('No direct script access allowed');

class Kesehatan extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('tglindo_helper');
    }

    public function detail($slug = null): void
    {
        $cekMenu                           = $this->menu_aktif('data-kesehatan/' . $slug);
        $idPosyandu                        = $this->input->get('id_posyandu');
        $kuartal                           = $this->input->get('kuartal');
        $tahun                             = $this->input->get('tahun');
        $stunting                          = new Stunting(['idPosyandu' => $idPosyandu, 'kuartal' => $kuartal, 'tahun' => $tahun]);
        $data                              = $this->includes;
        $data['title']                     = 'e-' . ucwords($slug);
        $data['tampil']                    = $cekMenu;
        $data['scorecard']                 = $stunting->scoreCard();
        $data['widgets']                   = $this->widget();
        $data['chartStuntingUmurData']     = $stunting->chartStuntingUmurData();
        $data['chartStuntingPosyanduData'] = $stunting->chartPosyanduData();
        $data['posyandu']                  = $data['scorecard']['posyandu'];
        $data['kuartal']                   = $data['scorecard']['kuartal'];
        $data['dataTahun']                 = $data['scorecard']['dataTahun'];
        $data['idPosyandu']                = $idPosyandu;

        $this->_get_common_data($data);
        $this->set_template('layouts/kesehatan.tpl.php');
        theme_view($this->template, $data);
    }

    private function widget(): array
    {
        return [
            [
                'title'    => 'Ibu Hamil Periksa Bulan ini',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-blue',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => IbuHamil::whereMonth('created_at', date('m'))->count(),
            ],
            [
                'title'    => 'Anak Periksa Bulan ini',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-gray',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::whereMonth('created_at', date('m'))->count(),
            ],
            [
                'title'    => 'Ibu Hamil & Anak 0-23 Bulan',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => IbuHamil::count() + Anak::count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Normal',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::normal()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Resiko Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-yellow',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::resikoStunting()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-red',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::stunting()->count(),
            ],
        ];
    }
}
