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

use App\Models\SyaratSurat;
use Illuminate\Database\Seeder;

class RefSyaratSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['ref_syarat_nama' => 'Surat Pengantar RT/RW'],
            ['ref_syarat_nama' => 'Fotokopi KK'],
            ['ref_syarat_nama' => 'Fotokopi KTP'],
            ['ref_syarat_nama' => 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan'],
            ['ref_syarat_nama' => 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak'],
            ['ref_syarat_nama' => 'Surat Pindah Datang dari tempat asal'],
            ['ref_syarat_nama' => 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter'],
            ['ref_syarat_nama' => 'Surat Keterangan Cerai'],
            ['ref_syarat_nama' => 'Fotokopi Ijasah Terakhir'],
            ['ref_syarat_nama' => 'SK. PNS/KARIP/SK. TNI – POLRI'],
            ['ref_syarat_nama' => 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan'],
            ['ref_syarat_nama' => 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)'],
        ];

        foreach ($data as $item) {
            SyaratSurat::updateOrCreate(
                ['ref_syarat_nama' => $item['ref_syarat_nama']],
                $item
            );
        }
    }
}
