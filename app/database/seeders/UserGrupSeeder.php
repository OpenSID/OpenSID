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

use App\Enums\StatusEnum;
use App\Models\UserGrup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserGrupSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['Administrator', 'administrator', 1, 1],
            ['Operator', 'operator', 1, 1],
            ['Redaksi', 'redaksi', 1, 1],
            ['Kontributor', 'kontributor', 1, 1],
            ['Satgas Covid-19', 'satgas-covid-19', 2, 1],

            // Grup tambahan berdasarkan tupoksi perangkat
            ['Sekretaris Desa', 'sekretaris-desa', 1, StatusEnum::TIDAK],
            ['Kaur Perencanaan', 'kaur-perencanaan', 1, StatusEnum::TIDAK],
            ['Kasi Pemerintahan', 'kasi-pemerintahan', 1, StatusEnum::TIDAK],
            ['Kasi Pelayanan', 'kasi-pelayanan', 1, StatusEnum::TIDAK],
            ['Kasi Kesejahteraan', 'kasi-kesejahteraan', 1, StatusEnum::TIDAK],
            ['Kaur Umum dan Perencanaan', 'kaur-umum-dan-perencanaan', 1, StatusEnum::TIDAK],
            ['Kaur Keuangan', 'kaur-keuangan', 1, StatusEnum::TIDAK],
            ['Kepala Dusun', 'kepala-dusun', 1, StatusEnum::TIDAK],
        ];

        foreach ($data as [$nama, $slug, $jenis, $status]) {
            UserGrup::updateOrCreate(
                ['slug' => $slug],
                [
                    'nama'       => $nama,
                    'jenis'      => $jenis,
                    'status'     => $status,
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
