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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\KaderMasyarakat;
use App\Models\RefPendudukBidang;
use App\Models\RefPendudukKursus;
use Illuminate\Support\Facades\DB;

class Migrasi_2024031351 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        return $hasil;
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024030151($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2024030751($hasil);
        $hasil = $hasil && $this->migrasi_2024031051($hasil);

        return $hasil && $this->migrasi_2024031251($hasil);
    }

    protected function migrasi_2024030151($hasil, $id)
    {
        return $hasil && $this->tambah_setting([
            'judul'      => 'Sinkronisasi OpenDK Server',
            'key'        => 'sinkronisasi_opendk',
            'value'      => setting('api_opendk_key') ? 1 : 0,
            'keterangan' => 'Aktifkan Sinkronisasi Server OpenDK',
            'kategori'   => 'opendk',
            'jenis'      => 'boolean',
            'option'     => null,
        ], $id);
    }

    protected function migrasi_2024030751($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'buku-tanah-di-desa', 'url' => 'bumindes_tanah_desa/clear'],
            ['url' => 'bumindes_tanah_desa']
        );
    }

    protected function migrasi_2024031051($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'rumah-tangga', 'url' => 'rtm/clear'],
            ['url' => 'rtm']
        );
    }

    protected function migrasi_2024031251($hasil)
    {
        $kader  = KaderMasyarakat::get();
        $bidang = RefPendudukBidang::get();
        $kursus = RefPendudukKursus::get();

        foreach ($kader as $item) {
            $resultBidang = [];
            $resultKursus = [];

            foreach ($bidang as $valueBidang) {
                if (strpos($item->bidang, $valueBidang['nama']) !== false) {
                    $resultBidang[] = $valueBidang['nama'];
                }
            }

            foreach ($kursus as $valueKursus) {
                if (strpos($item->kursus, $valueKursus['nama']) !== false) {
                    $resultKursus[] = $valueKursus['nama'];
                }
            }
            KaderMasyarakat::find($item->id)->update([
                'bidang' => json_encode($resultBidang),
                'kursus' => json_encode($resultKursus),
            ]);
        }

        return $hasil;
    }
}
