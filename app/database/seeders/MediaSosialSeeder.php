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

use App\Models\MediaSosial;
use Illuminate\Database\Seeder;

class MediaSosialSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'gambar'  => 'fb.png',
                'link'    => null,
                'nama'    => 'Facebook',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'twt.png',
                'link'    => null,
                'nama'    => 'Twitter',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'yb.png',
                'link'    => null,
                'nama'    => 'YouTube',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'ins.png',
                'link'    => null,
                'nama'    => 'Instagram',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'wa.png',
                'link'    => null,
                'nama'    => 'WhatsApp',
                'tipe'    => 1,
                'enabled' => 1,
            ],
            [
                'gambar'  => 'tg.png',
                'link'    => null,
                'nama'    => 'Telegram',
                'tipe'    => 1,
                'enabled' => 2,
            ],
        ];

        foreach ($data as $item) {
            MediaSosial::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
