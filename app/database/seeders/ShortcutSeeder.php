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

namespace Database\Seeders;

use App\Models\Shortcut;
use Illuminate\Database\Seeder;

class ShortcutSeeder extends Seeder
{
    public function run(): void
    {
        if (Shortcut::count() === 0) {
            $shortcut = [
                [
                    'judul'     => 'Wilayah [desa]',
                    'raw_query' => 'Dusun',
                    'icon'      => 'fa-map-marker',
                    'urut'      => 1,
                    'warna'     => '#605ca8',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Penduduk',
                    'raw_query' => 'Penduduk',
                    'icon'      => 'fa-user',
                    'urut'      => 2,
                    'warna'     => '#00c0ef',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Keluarga',
                    'raw_query' => 'Keluarga',
                    'icon'      => 'fa-users',
                    'urut'      => 3,
                    'warna'     => '#00a65a',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Surat Tercetak',
                    'raw_query' => 'Surat Tercetak',
                    'icon'      => 'fa-file-text-o',
                    'urut'      => 4,
                    'warna'     => '#0073b7',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Kelompok',
                    'raw_query' => 'Kelompok',
                    'icon'      => 'fa-user-plus',
                    'urut'      => 5,
                    'warna'     => '#dd4b39',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Rumah Tangga',
                    'raw_query' => 'RTM',
                    'icon'      => 'fa-home',
                    'urut'      => 6,
                    'warna'     => '#d2d6de',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Bantuan',
                    'raw_query' => 'Bantuan',
                    'icon'      => 'fa-handshake-o',
                    'urut'      => 7,
                    'warna'     => '#f39c12',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Verifikasi Layanan Mandiri',
                    'raw_query' => 'Verifikasi Layanan Mandiri',
                    'icon'      => 'fa-drivers-license',
                    'urut'      => 8,
                    'warna'     => '#39cccc',
                    'status'    => 1,
                ],
            ];

            foreach ($shortcut as $item) {
                Shortcut::create($item);
            }
        }
    }
}
