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

require_once FCPATH . 'tools/vendor/fakerphp/faker/src/autoload.php';

defined('BASEPATH') || exit('No direct script access allowed');

class Faker_Controller extends CI_Controller
{
    public $faker;
    public $tabel = [
        'config',
        'tweb_wil_clusterdesa',
        'tweb_penduduk',
        'tweb_keluarga',
        'log_penduduk',
        'log_keluarga',
        'tweb_rtm',
        'program',
        'program_peserta',
    ];
    public $helpers = [
        'faker',
        'config',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers($this->helpers);
    }

    public function index($reset = 1)
    {
        $cekConfig = DB::table('config')->orderBy('id', 'desc')->first()->id;

        if ($reset != 1 && $cekConfig > 1) {
            $mulai = $cekConfig + 1;
        } else {
            truncateTable($this->tabel);
            $mulai = 1;
        }

        $jumlahDesa = configFaker('desa')['jumlah'];

        for ($i = $mulai; $i <= $jumlahDesa; $i++) {
            $config = buatConfig($i);
            buatWilayah($i);
            buatKeluarga($i, $config['kode_kecamatan']);
            buatRumahTangga($i, $config['kode_desa']);
            buatBantuan($i);
        }

        return json('OK');
    }

    public function config($jumlahDesa = 1)
    {
        for ($i = 1; $i <= $jumlahDesa; $i++) {
            buatConfig();
        }

        return json('OK');
    }

    public function wilayah($configID = 1)
    {
        buatWilayah($configID);

        return json('OK');
    }

    public function penduduk($configID = 1, $kodeKecamatan = null, $kkLevel = null, $statusKawin = null)
    {
        if (null === $kodeKecamatan) {
            $kodeKecamatan = DB::table('config')->where('id', $configID)->first()->kode_kecamatan;
        }

        buatIndividu($configID, $kodeKecamatan, $kkLevel, $statusKawin = null);

        return json('OK');
    }

    public function keluarga($configID = 1, $kodeKecamatan = null, $urut = 1)
    {
        if (null === $kodeKecamatan) {
            $kodeKecamatan = DB::table('config')->where('id', $configID)->first()->kode_kecamatan;
        }

        buatAnggota($configId, $kodeKecamatan, $urut);

        return json('OK');
    }
}
