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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Modules\Anjungan\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\Anjungan\Models\AnjunganMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $from = public_path('modules/anjungan/views/assets/images/');
        $to   = public_path('desa/anjungan/menu/');

        $data = [
            [
                'nama'      => 'Peta Desa',
                'icon'      => 'peta.svg',
                'link'      => 'peta',
                'link_tipe' => 5,
                'urut'      => 1,
                'status'    => 1,
            ],
            [
                'nama'      => 'Informasi Pubik',
                'icon'      => 'protected.svg',
                'link'      => 'informasi_publik',
                'link_tipe' => 5,
                'urut'      => 2,
                'status'    => 1,
            ],
            [
                'nama'      => 'Data Pekerjaan',
                'icon'      => 'statistik.svg',
                'link'      => 'statistik/1',
                'link_tipe' => 2,
                'urut'      => 3,
                'status'    => 1,
            ],
            [
                'nama'      => 'Layanan Mandiri',
                'icon'      => 'mandiri.svg',
                'link'      => 'layanan-mandiri/beranda',
                'link_tipe' => 5,
                'urut'      => 4,
                'status'    => 1,
            ],
            [
                'nama'      => 'Lapak',
                'icon'      => 'lapak.svg',
                'link'      => 'lapak',
                'link_tipe' => 5,
                'urut'      => 5,
                'status'    => 1,
            ],
            [
                'nama'      => 'Keuangan',
                'icon'      => 'keuangan.svg',
                'link'      => 'artikel/100',
                'link_tipe' => 6,
                'urut'      => 6,
                'status'    => 1,
            ],
            [
                'nama'      => 'IDM 2021',
                'icon'      => 'idm.svg',
                'link'      => 'status-idm/2021',
                'link_tipe' => 10,
                'urut'      => 7,
                'status'    => 1,
            ],
        ];

        $anjunganMenu = new AnjunganMenu();
        if ($anjunganMenu->count() == 0) {
            foreach ($data as $item) {
                $result = AnjunganMenu::create($item);

                if ($result && ! File::exists($to . $item['icon'])) {
                    File::copy($from . $item['icon'], $to . $item['icon']);
                }
            }
        }

        $defaultIcons = array_column($data, 'icon');
        $menus        = $anjunganMenu->whereIn('icon', $defaultIcons)->get();

        foreach ($menus as $menu) {
            if (! File::exists($to . $menu->icon)) {
                File::copy($from . $menu->icon, $to . $menu->icon);
            }
        }
    }
}
