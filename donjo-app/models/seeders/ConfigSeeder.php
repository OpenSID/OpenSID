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

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Config::create([
            'app_key'           => get_app_key(),
            'nama_desa'         => '',
            'kode_desa'         => '',
            'nama_kecamatan'    => '',
            'kode_kecamatan'    => '',
            'nama_kabupaten'    => '',
            'kode_kabupaten'    => '',
            'nama_propinsi'     => '',
            'kode_propinsi'     => '',
            'nama_kepala_camat' => '',
            'nip_kepala_camat'  => '',
        ]);

        $this->isiConfig();
    }

    private function isiConfig()
    {
        if (! identitas() || empty($kode_desa = config_item('kode_desa')) || ! cek_koneksi_internet()) {
            return false;
        }

        // Ambil data desa dari tracksid
        $data_desa = get_data_desa($kode_desa);

        if (null === $data_desa) {
            log_message('error', "Kode desa {$kode_desa} di desa/config/config.php tidak ditemukan di " . config_item('server_pantau'));
        } else {
            $desa = $data_desa;
            $data = [
                'nama_desa'         => nama_desa($desa->nama_desa),
                'kode_desa'         => bilangan($kode_desa),
                'nama_kecamatan'    => nama_terbatas($desa->nama_kec),
                'kode_kecamatan'    => bilangan($desa->kode_kec),
                'nama_kabupaten'    => ucwords(hapus_kab_kota(nama_terbatas($desa->nama_kab))),
                'kode_kabupaten'    => bilangan($desa->kode_kab),
                'nama_propinsi'     => ucwords(nama_terbatas($desa->nama_prov)),
                'kode_propinsi'     => bilangan($desa->kode_prov),
                'nama_kepala_camat' => '',
                'nip_kepala_camat'  => '',
            ];

            if (Config::appKey()->update($data)) {
                (new Config())->flushQueryCache();
                log_message('notice', 'Berhasil menggunakan kode desa dari file config');
            } else {
                log_message('error', 'Gagal menggunakan kode desa dari file config');
            }

            cache()->forget('identitas_desa');
        }
    }
}
