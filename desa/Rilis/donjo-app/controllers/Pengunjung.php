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

use App\Models\StatistikPengunjung;
use Illuminate\Support\Facades\DB;

class Pengunjung extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'pengunjung';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('statistik_pengunjung_model');
    }

    public function index()
    {
        $data['hari_ini']   = StatistikPengunjung::filter(StatistikPengunjung::HARI_INI)->sum('jumlah');
        $data['kemarin']    = StatistikPengunjung::filter(StatistikPengunjung::KEMARIN)->sum('jumlah');
        $data['minggu_ini'] = StatistikPengunjung::filter(StatistikPengunjung::MINGGU_INI)->sum('jumlah');
        $data['bulan_ini']  = StatistikPengunjung::filter(StatistikPengunjung::BULAN_INI)->sum('jumlah');
        $data['tahun_ini']  = StatistikPengunjung::filter(StatistikPengunjung::TAHUN_INI)->sum('jumlah');
        $data['jumlah']     = StatistikPengunjung::sum('jumlah');
        $data['main']       = $this->getPengunjung();

        return view('admin.pengunjung.index', $data);
    }

    private function getPengunjung($type = null)
    {
        $tgl = date('Y-m-d');
        $bln = date('m');
        $thn = date('Y');

        switch ($type) {
            // Hari Ini
            case StatistikPengunjung::HARI_INI:
                $data['lblx']       = 'Tanggal';
                $data['judul']      = 'Hari Ini ( ' . tgl_indo2($tgl) . ')';
                $data['pengunjung'] = StatistikPengunjung::filter(StatistikPengunjung::HARI_INI)
                    ->groupBy(DB::raw('tanggal'))
                    ->select(DB::raw('SUM(jumlah) as Jumlah'), DB::raw('tanggal as Tanggal'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;

                // Kemarin
            case StatistikPengunjung::KEMARIN:
                $data['lblx']       = 'Tanggal';
                $data['judul']      = 'Kemarin ( ' . tgl_indo2($this->op_tgl('-1 days', $tgl)) . ')';
                $data['pengunjung'] = StatistikPengunjung::filter(StatistikPengunjung::KEMARIN)
                    ->groupBy(DB::raw('tanggal'))
                    ->select(DB::raw('SUM(jumlah) as Jumlah'), DB::raw('tanggal as Tanggal'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;

                // 7 Hari (Minggu Ini)
            case StatistikPengunjung::MINGGU_INI:
                $data['lblx']       = 'Tanggal';
                $data['judul']      = 'Dari Tanggal ' . tgl_indo2($this->op_tgl('-6 days', $tgl)) . ' - ' . tgl_indo2($tgl);
                $data['pengunjung'] = StatistikPengunjung::filter(StatistikPengunjung::MINGGU_INI)
                    ->groupBy(DB::raw('tanggal'))
                    ->select(DB::raw('SUM(jumlah) as Jumlah'), DB::raw('tanggal as Tanggal'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;

                // 1 bulan(tgl 1 sampai akhir bulan)
            case StatistikPengunjung::BULAN_INI:
                $data['lblx']       = 'Tanggal';
                $data['judul']      = 'Bulan ' . ucwords(getBulan($bln)) . ' ' . $thn;
                $data['pengunjung'] = StatistikPengunjung::filter(StatistikPengunjung::BULAN_INI)
                    ->groupBy(DB::raw('Tanggal'))
                    ->select(DB::raw('SUM(jumlah) as Jumlah'), DB::raw('tanggal as Tanggal'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;

                // 1 tahun / 12 Bulan
            case StatistikPengunjung::TAHUN_INI:
                $data['lblx']       = 'Bulan';
                $data['judul']      = 'Tahun ' . $thn;
                $data['pengunjung'] = StatistikPengunjung::filter(StatistikPengunjung::TAHUN_INI)
                    ->groupBy(DB::raw('MONTH(tanggal)'))
                    ->select(DB::raw('SUM(jumlah) as Jumlah'), DB::raw('MONTH(tanggal) as Tanggal'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;

                // Semua Data
            default:
                $data['lblx']       = 'Tahun';
                $data['judul']      = 'Setiap Tahun';
                $data['pengunjung'] = StatistikPengunjung::groupBy(DB::raw('YEAR(tanggal)'))
                    ->select(DB::raw('YEAR(tanggal) as Tanggal'), DB::raw('SUM(jumlah) as Jumlah'))
                    ->orderBy('Tanggal', 'asc')->get();
                break;
        }

        $data['Total']      = $data['pengunjung']->sum('Jumlah');
        $data['pengunjung'] = $data['pengunjung']->toArray();

        return $data;
    }

    public function detail($id = null)
    {
        $data['hari_ini']   = StatistikPengunjung::filter(StatistikPengunjung::HARI_INI)->sum('jumlah');
        $data['kemarin']    = StatistikPengunjung::filter(StatistikPengunjung::KEMARIN)->sum('jumlah');
        $data['minggu_ini'] = StatistikPengunjung::filter(StatistikPengunjung::MINGGU_INI)->sum('jumlah');
        $data['bulan_ini']  = StatistikPengunjung::filter(StatistikPengunjung::BULAN_INI)->sum('jumlah');
        $data['tahun_ini']  = StatistikPengunjung::filter(StatistikPengunjung::TAHUN_INI)->sum('jumlah');
        $data['jumlah']     = StatistikPengunjung::sum('jumlah');
        $data['main']       = $this->getPengunjung($id);

        return view('admin.pengunjung.index', $data);
    }

    public function cetak($aksi = 'cetak')
    {
        $data = [
            'aksi'   => $aksi,
            'config' => $this->header['desa'],
            'main'   => $this->getPengunjung(),
            'file'   => 'LAPORAN DATA STATISTIK PENGUNJUNG WEBSITE SETIAP TAHUN',
            'isi'    => 'admin.pengunjung.cetak',
        ];

        return view('admin.layouts.components.format_cetak', $data);
    }

    /**
     * Rentang tanggal.
     *
     * @return string
     */
    protected function op_tgl(string $op, string $tgl)
    {
        return date('Y-m-d', strtotime($op, strtotime($tgl)));
    }
}
