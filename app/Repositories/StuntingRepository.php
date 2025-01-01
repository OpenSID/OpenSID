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

namespace App\Repositories;

use App\Libraries\Stunting;
use App\Models\Anak;
use App\Models\IbuHamil;

class StuntingRepository
{
    public function list($tahun, $kuartal, $idPosyandu)
    {
        $stunting  = new Stunting(['idPosyandu' => $idPosyandu, 'kuartal' => $kuartal, 'tahun' => $tahun]);
        $scoreCard = $stunting->scoreCard();

        return [
            'scorecard'                 => $scoreCard,
            'widgets'                   => $this->widget(),
            'chartStuntingUmurData'     => $stunting->chartStuntingUmurData(),
            'chartStuntingPosyanduData' => $stunting->chartPosyanduData(),
        ];
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
