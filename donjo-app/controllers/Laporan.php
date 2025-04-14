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

use App\Models\LogPenduduk;
use App\Models\Pamong;
use App\Repositories\LaporanPendudukRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan extends Admin_Controller
{
    public $modul_ini           = 'statistik';
    public $sub_modul_ini       = 'laporan-bulanan';
    public $kategori_pengaturan = 'Data Lengkap';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function clear(): void
    {
        session_error_clear();
        $this->session->unset_userdata(['cari']);
        $this->session->bulanku  = date('n');
        $this->session->tahunku  = date('Y');
        $this->session->per_page = 200;

        redirect('laporan');
    }

    public function index(): void
    {

        if (isset($this->session->bulanku)) {
            $data['bulanku'] = $this->session->bulanku;
        } else {
            $data['bulanku']        = date('n');
            $this->session->bulanku = $data['bulanku'];
        }

        if (isset($this->session->tahunku)) {
            $data['tahunku'] = $this->session->tahunku;
        } else {
            $data['tahunku']        = date('Y');
            $this->session->tahunku = $data['tahunku'];
        }

        $data['bulan']                = $data['bulanku'];
        $data['tahun']                = $data['tahunku'];
        $data['data_lengkap']         = true;
        $data['sesudah_data_lengkap'] = true;
        $tanggal_lengkap              = LogPenduduk::min('tgl_lapor');
        $dataLengkap                  = data_lengkap();
        if (! $dataLengkap) {
            $data['data_lengkap'] = false;
            view('admin.laporan.bulanan', $data);

            return;
        }

        $tahun_bulan = (new DateTime($tanggal_lengkap))->format('Y-m');
        if ($tahun_bulan > $data['tahunku'] . '-' . $data['bulanku']) {
            $data['sesudah_data_lengkap'] = false;
            view('admin.laporan.bulanan', $data);

            return;
        }

        $this->session->tgl_lengkap = $tanggal_lengkap;
        $data['tgl_lengkap']        = $tanggal_lengkap;
        $data['tahun_lengkap']      = (new DateTime($tanggal_lengkap))->format('Y');
        $dataPenduduk               = LaporanPendudukRepository::dataPenduduk($data['tahun'], $data['bulan']);

        view('admin.laporan.bulanan', array_merge($data, $dataPenduduk));
    }

    public function dialog(string $aksi = 'cetak'): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = 'Cetak';
        $data['form_action'] = ci_route('laporan.cetak', $aksi);
        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function cetak(string $aksi = 'cetak'): void
    {
        $data = $this->data_cetak();
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=Laporan_bulanan_' . date('d_m_Y') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.laporan.bulanan_print', $data);
    }

    private function data_cetak()
    {
        $data               = [];
        $data['bulan']      = $this->session->bulanku;
        $data['tahun']      = $this->session->tahunku;
        $data['bln']        = getBulan($data['bulan']);
        $data['pamong_ttd'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $dataPenduduk       = LaporanPendudukRepository::dataPenduduk($data['tahun'], $data['bulan']);

        return array_merge($data, $dataPenduduk);
    }

    public function bulan(): void
    {
        $bulanku = $this->input->post('bulan');
        if ($bulanku != '') {
            $this->session->bulanku = $bulanku;
        } else {
            unset($this->session->bulanku);
        }

        $tahunku = $this->input->post('tahun');
        if ($tahunku != '') {
            $this->session->tahunku = $tahunku;
        } else {
            unset($this->session->tahunku);
        }
        redirect('laporan');
    }

    public function detail_penduduk($rincian, $tipe): void
    {
        $data            = LaporanPendudukRepository::sumberData($rincian, $tipe, $this->session->tahunku, $this->session->bulanku);
        $data['rincian'] = $rincian;
        $data['tipe']    = $tipe;
        view('admin.laporan.detail.index', $data);
    }

    public function detail_dialog($aksi = 'cetak', $rincian = 'awal', $tipe = 'wni_l')
    {
        $data                = $this->modal_penandatangan();
        $data['sensor_nik']  = true;
        $data['aksi']        = ucwords($aksi);
        $data['form_action'] = ci_route("laporan.detail_cetak.{$aksi}.{$rincian}.{$tipe}");

        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function detail_cetak($aksi = 'cetak', $rincian = 'awal', $tipe = 'wni_l')
    {
        $sumberData             = LaporanPendudukRepository::sumberData($rincian, $tipe, $this->session->tahunku, $this->session->bulanku);
        $sumberData['file']     = $sumberData['title'];
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['isi']            = 'admin.laporan.detail.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];
        $data['sensor_nik']     = $this->input->post('sensor_nik') == 'on' ? 1 : false;

        view('admin.layouts.components.format_cetak', array_merge($data, $sumberData));
    }
}
