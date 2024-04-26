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

use App\Models\LogPenduduk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_penduduk_rekapitulasi extends Admin_Controller
{
    public $modul_ini           = 'buku-administrasi-desa';
    public $sub_modul_ini       = 'administrasi-penduduk';
    public $kategori_pengaturan = 'data_lengkap';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['pamong_model', 'penduduk_model', 'laporan_bulanan_model', 'laporan_sinkronisasi_model', 'wilayah_model']);
        $this->logpenduduk = new LogPenduduk();
    }

    public function index()
    {
        $data['selectedNav'] = 'rekapitulasi';
        $data['subtitle']    = 'Buku Rekapitulasi Jumlah Penduduk';
        $data['tahun']       = $this->logpenduduk->min(DB::raw('YEAR(tgl_lapor)'));
        $data['mainContent'] = 'admin.bumindes.penduduk.rekapitulasi.index';

        return view('admin.bumindes.penduduk.index', $data);
    }

    public function datatables()
    {
        $filters = [
            'tahun' => empty($this->input->get('tahun')) ? null : $this->input->get('tahun'),
            'bulan' => empty($this->input->get('bulan')) ? null : $this->input->get('bulan'),
        ];

        $rekapitulasi = LogPenduduk::RekapitulasiList($filters)->get()->toArray();

        $collected = $this->dataProcess($rekapitulasi);

        if ($this->input->is_ajax_request()) {
            return datatables()->of($collected)
                ->addIndexColumn()
                ->make();
        }

        return show_404();
    }

    public function dataProcess($rekap)
    {
        return collect($rekap)->map(static function (array $item): array {
            $item['WNI_L_AKHIR']      = $item['WNI_L_AWAL'] + $item['WNI_L_TAMBAH_LAHIR'] + $item['WNI_L_TAMBAH_MASUK'] - $item['WNI_L_KURANG_MATI'] - $item['WNI_L_KURANG_KELUAR'];
            $item['WNI_P_AKHIR']      = $item['WNI_P_AWAL'] + $item['WNI_P_TAMBAH_LAHIR'] + $item['WNI_P_TAMBAH_MASUK'] - $item['WNI_P_KURANG_MATI'] - $item['WNI_P_KURANG_KELUAR'];
            $item['WNA_L_AKHIR']      = $item['WNA_L_AWAL'] + $item['WNA_L_TAMBAH_LAHIR'] + $item['WNA_L_TAMBAH_MASUK'] - $item['WNA_L_KURANG_MATI'] - $item['WNA_L_KURANG_KELUAR'];
            $item['WNA_P_AKHIR']      = $item['WNA_P_AWAL'] + $item['WNA_P_TAMBAH_LAHIR'] + $item['WNA_P_TAMBAH_MASUK'] - $item['WNA_P_KURANG_MATI'] - $item['WNA_P_KURANG_KELUAR'];
            $item['KK_AKHIR_JML']     = $item['KK_JLH'] + $item['KK_MASUK_JLH'];
            $item['KK_AKHIR_ANG_KEL'] = $item['KK_ANG_KEL'] + $item['KK_MASUK_ANG_KEL'];

            $item['JLH_JIWA_1'] = $item['KK_JLH'] + $item['KK_ANG_KEL'];
            $item['JLH_JIWA_2'] = $item['KK_AKHIR_JML'] + $item['KK_AKHIR_ANG_KEL'];
            $item['jumlah']     = 0;

            return $item;
        });
    }

    public function dialog_cetak($aksi = '')
    {
        $data = [
            'aksi'       => $aksi,
            'rekap'      => true,
            'list_tahun' => LogPenduduk::tahun()->pluck('tahun'),
            'formAction' => route('bumindes_penduduk_rekapitulasi.cetak', $aksi),
        ];

        return view('admin.bumindes.penduduk.induk.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $rekap                 = LogPenduduk::RekapitulasiList()->get()->toArray();
        $data                  = $this->modal_penandatangan();
        $data['aksi']          = $aksi;
        $data['main']          = $this->dataProcess($rekap);
        $data['config']        = $this->header['desa'];
        $data['tgl_cetak']     = $this->input->post('tgl_cetak');
        $data['tampil_jumlah'] = $this->input->post('tampil_jumlah');
        $data['file']          = 'Buku Rekapitulasi Jumlah Penduduk';
        $data['isi']           = 'admin.bumindes.penduduk.rekapitulasi.cetak';
        $data['letak_ttd']     = ['1', '2', '28'];

        if ($aksi == 'pdf') {
            $this->laporan_pdf($data);
        }

        return view('admin.layouts.components.format_cetak', $data);
    }

    private function laporan_pdf($data): void
    {
        $nama_file = 'rekap_jumlah_penduduk_' . date('Y_m_d');
        $file      = FCPATH . LOKASI_DOKUMEN . $nama_file;
        // $data['width']      = 400; // lebar dalam mm
        $data['ispdf'] = true;
        $laporan       = View::make('admin.layouts.components.format_cetak', $data)->render();
        buat_pdf($laporan, $file, null, 'L', [200, 400]); // perlu berikan dimensi eksplisit dalam mm

        $bulan = $this->session->filter_bulan ?? date('m');
        $tahun = $this->session->filter_tahun ?? date('Y');

        $where = [
            'semester' => $bulan,
            'tahun'    => $tahun,
        ];

        log_message('notice', 'Laporan Rekap Jumlah Penduduk ' . $bulan . ' ' . $tahun . ' telah dibuat.');

        $lap_sinkron = [
            'judul'     => 'Rekap Jumlah Penduduk',
            'semester'  => $bulan,
            'tahun'     => $tahun,
            'nama_file' => $nama_file . '.pdf',
            'tipe'      => 'laporan_penduduk',
        ];
        $this->laporan_sinkronisasi_model->insert_or_update($where, $lap_sinkron);
    }
}
