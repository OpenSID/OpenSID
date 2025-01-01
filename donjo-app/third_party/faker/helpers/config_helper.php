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

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

function buatConfig(?int $configId = null)
{
    $kodeDesa = configFaker('kecamatan')['kode'] . (configFaker('kecamatan')['awalan'] + random_int(1, configFaker('kecamatan')['desa']));

    // Ambil data desa dari tracksid
    $ci = &get_instance();
    $ci->load->library('data_publik');
    $ci->data_publik->set_api_url(config_item('server_pantau') . '/index.php/api/wilayah/kodedesa?token=' . config_item('token_pantau') . '&kode=' . $kodeDesa, 'kode_desa');
    $dataDesa = $ci->data_publik->get_url_content(true);

    if ($dataDesa->header->http_code != 200 || empty($dataDesa->body)) {
        $data = [];
    } else {
        $desa = $dataDesa->body;

        $data = [
            'id'                => $configId ?? faker()->numberBetween(1, 100),
            'app_key'           => set_app_key(),
            'nama_desa'         => nama_desa($desa->nama_desa),
            'kode_desa'         => bilangan($kodeDesa),
            'kode_pos'          => faker()->postcode,
            'nama_kecamatan'    => nama_terbatas($desa->nama_kec),
            'kode_kecamatan'    => bilangan($desa->kode_kec),
            'nama_kepala_camat' => faker()->name,
            'nip_kepala_camat'  => faker()->numberBetween(1_000_000_000_000_000, 9_999_999_999_999_999),
            'nama_kabupaten'    => nama_terbatas($desa->nama_kab),
            'kode_kabupaten'    => bilangan($desa->kode_kab),
            'nama_propinsi'     => nama_terbatas($desa->nama_prov),
            'kode_propinsi'     => bilangan($desa->kode_prov),
        ];
    }

    if (count($data) == 0 || DB::table('config')->where('kode_desa', $kodeDesa)->count() > 0) {
        $data = buatConfig($configId);
    } else {
        DB::table('config')->insert($data);
    }

    return $data;
}
