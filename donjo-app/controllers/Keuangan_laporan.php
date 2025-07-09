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

use App\Libraries\Keuangan as LibrariesKeuangan;
use App\Models\Keuangan;

defined('BASEPATH') || exit('No direct script access allowed');

class Keuangan_laporan extends Admin_Controller
{
    public $modul_ini     = 'keuangan';
    public $sub_modul_ini = 'laporan';
    private $listTahun;

    public function __construct()
    {
        parent::__construct();
        $this->listTahun = Keuangan::tahunAnggaran()->get();
        if ($this->listTahun->isEmpty()) {
            redirect_with('error', 'Data Laporan Keuangan Belum Tersedia', ci_route('keuangan_manual'));
        }
    }

    public function index()
    {
        isCan('b');
        $tahun = $this->input->get('tahun') ?? $this->listTahun->first()->tahun;
        $jenis = $this->input->get('jenis') ?? 'grafik-RP-APBD-manual';

        switch ($jenis) {
            case 'rincian_realisasi_bidang_manual':
                $this->rincian_realisasi_manual($tahun, 'Akhir Bidang Manual');
                break;

            case 'grafik-RP-APBD-manual':

            default:
                $this->grafik_rp_apbd_manual($tahun);
                break;
        }
    }

    private function rincian_realisasi_manual($tahun, string $judul): void
    {
        $data                   = (new LibrariesKeuangan())->lap_rp_apbd($tahun);
        $data['tahun_anggaran'] = $this->listTahun;
        $data['submenu']        = 'Laporan Keuangan ' . $judul;
        $data['tahun']          = $tahun;
        $data['jenis']          = 'bidang';
        view('admin.keuangan.laporan.realisasi', $data);
    }

    private function grafik_rp_apbd_manual($tahun)
    {
        $data = (new LibrariesKeuangan())->grafik_keuangan_tema($tahun);

        $data['tahun_anggaran'] = $this->listTahun;
        $data['submenu']        = 'Grafik Keuangan';
        $data['tahun']          = $tahun;
        $data['jenis']          = 'bidang';

        view('admin.keuangan.laporan.grafik_rp_apbd_manual', $data);
    }
}
