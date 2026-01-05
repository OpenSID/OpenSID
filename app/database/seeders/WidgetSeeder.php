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

use App\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'judul'        => 'Peta Desa',
                'isi'          => '<p><iframe src="https =>//www.google.co.id/maps?f=q&source=s_q&hl=en&geocode=&q=Logandu,+Karanggayam&aq=0&oq=logan&sll=-2.550221,118.015568&sspn=52.267573,80.332031&t=h&ie=UTF8&hq=&hnear=Logandu,+Karanggayam,+Kebumen,+Central+Java&ll=-7.55854,109.634173&spn=0.052497,0.078449&z=14&output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="100%"></iframe></p> ',
                'jenis_widget' => 3,
                'enabled'      => 0,
                'urut'         => 1,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Agenda',
                'isi'          => 'agenda',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 6,
                'form_admin'   => 'web/agenda',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Galeri',
                'isi'          => 'galeri',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 8,
                'form_admin'   => 'gallery',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Statistik',
                'isi'          => 'statistik',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 4,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Komentar',
                'isi'          => 'komentar',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 10,
                'form_admin'   => 'komentar',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Media Sosial',
                'isi'          => 'media_sosial',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 11,
                'form_admin'   => 'sosmed',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Peta Lokasi Kantor',
                'isi'          => 'peta_lokasi_kantor',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 13,
                'form_admin'   => 'identitas_desa/maps/kantor',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Statistik Pengunjung',
                'isi'          => 'statistik_pengunjung',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 14,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Arsip Artikel',
                'isi'          => 'arsip_artikel',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 5,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Aparatur Desa',
                'isi'          => 'aparatur_desa',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 9,
                'form_admin'   => 'web_widget/admin/aparatur_desa',
                'setting'      => "[\\'overlay\\' =>\"1\"]",
                'foto'         => '',
            ],
            [
                'judul'        => 'Sinergi Program',
                'isi'          => 'sinergi_program',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 7,
                'form_admin'   => 'web_widget/admin/sinergi_program',
                'setting'      => '[]',
                'foto'         => '',
            ],
            [
                'judul'        => 'Menu Kategori',
                'isi'          => 'menu_kategori',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 2,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Peta Wilayah Desa',
                'isi'          => 'peta_wilayah_desa',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 12,
                'form_admin'   => 'identitas_desa/maps/wilayah',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Keuangan',
                'isi'          => 'keuangan',
                'jenis_widget' => 1,
                'enabled'      => 1,
                'urut'         => 15,
                'form_admin'   => 'keuangan_manual',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Jam Kerja',
                'isi'          => 'jam_kerja',
                'jenis_widget' => 1,
                'enabled'      => 0,
                'urut'         => 16,
                'form_admin'   => '',
                'setting'      => '',
                'foto'         => '',
            ],
            [
                'judul'        => 'Profil Desa',
                'isi'          => 'profil_desa',
                'jenis_widget' => 1,
                'enabled'      => 0,
                'urut'         => 17,
                'form_admin'   => 'identitas_desa',
                'setting'      => '',
                'foto'         => '',
            ],
        ];

        foreach ($data as $item) {
            Widget::updateOrCreate(
                [
                    'judul'        => $item['judul'],
                    'jenis_widget' => $item['jenis_widget'],
                ],
                $item
            );
        }
    }
}
