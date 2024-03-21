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
use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan extends Admin_Controller
{
    public $modul_ini           = 'statistik';
    public $sub_modul_ini       = 'laporan-bulanan';
    public $kategori_pengaturan = 'data_lengkap';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['laporan_bulanan_model', 'pamong_model']);
        $this->logpenduduk = new LogPenduduk();
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

        $tanggal_lengkap = $this->logpenduduk::min('tgl_lapor');

        if (! $this->setting->tgl_data_lengkap_aktif) {
            $data['data_lengkap'] = false;
            $this->render('laporan/bulanan', $data);

            return;
        }

        $tahun_bulan = (new DateTime($tanggal_lengkap))->format('Y-m');
        if ($tahun_bulan > $data['tahunku'] . '-' . $data['bulanku']) {
            $data['sesudah_data_lengkap'] = false;
            $this->render('laporan/bulanan', $data);

            return;
        }
        $this->session->tgl_lengkap = $tanggal_lengkap;
        $data['tahun_lengkap']      = (new DateTime($tanggal_lengkap))->format('Y');
        $data['config']             = $this->header['desa'];
        $data['kelahiran']          = $this->laporan_bulanan_model->kelahiran();
        $data['kematian']           = $this->laporan_bulanan_model->kematian();
        $data['pendatang']          = $this->laporan_bulanan_model->pendatang();
        $data['pindah']             = $this->laporan_bulanan_model->pindah();
        $data['hilang']             = $this->laporan_bulanan_model->hilang();
        $data['penduduk_awal']      = $this->laporan_bulanan_model->penduduk_awal();
        $data['penduduk_akhir']     = $this->laporan_bulanan_model->penduduk_akhir();

        $this->render('laporan/bulanan', $data);
    }

    // TODO: Gunakan view global ttd
    // TODO: Satukan dialog cetak dan unduh
    public function dialog_cetak(): void
    {
        $data['aksi']        = 'Cetak';
        $data['pamong']      = Pamong::penandaTangan()->get();
        $data['form_action'] = site_url('laporan/cetak');
        $this->load->view('laporan/ajax_cetak', $data);
    }

    // TODO: Satukan dialog cetak dan unduh
    public function dialog_unduh(): void
    {
        $data['aksi']        = 'Unduh';
        $data['pamong']      = Pamong::penandaTangan()->get();
        $data['form_action'] = site_url('laporan/unduh');
        $this->load->view('laporan/ajax_cetak', $data);
    }

    // TODO: Satukan aksi cetak dan unduh
    public function cetak(): void
    {
        $data = $this->data_cetak();
        $this->load->view('laporan/bulanan_print', $data);
    }

    // TODO: Satukan aksi cetak dan unduh
    public function unduh(): void
    {
        $data = $this->data_cetak();
        $this->load->view('laporan/bulanan_excel', $data);
    }

    private function data_cetak()
    {
        $data                   = [];
        $data['config']         = $this->header['desa'];
        $data['bulan']          = $this->session->bulanku;
        $data['tahun']          = $this->session->tahunku;
        $data['bln']            = getBulan($data['bulan']);
        $data['penduduk_awal']  = $this->laporan_bulanan_model->penduduk_awal();
        $data['kelahiran']      = $this->laporan_bulanan_model->kelahiran();
        $data['kematian']       = $this->laporan_bulanan_model->kematian();
        $data['pendatang']      = $this->laporan_bulanan_model->pendatang();
        $data['pindah']         = $this->laporan_bulanan_model->pindah();
        $data['rincian_pindah'] = $this->laporan_bulanan_model->rincian_pindah();
        $data['hilang']         = $this->laporan_bulanan_model->hilang();
        $data['penduduk_akhir'] = $this->laporan_bulanan_model->penduduk_akhir();
        $data['pamong_ttd']     = $this->pamong_model->get_data($_POST['pamong_ttd']);

        return $data;
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
        $data         = [];
        $keluarga     = ['kk', 'kk_l', 'kk_p'];
        $tahun        = $this->session->tahunku;
        $bulan        = $this->session->bulanku;
        $titlePeriode = strtoupper(getBulan($bulan)) . ' ' . $tahun;

        switch (strtolower($rincian)) {
            case 'awal':
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->penduduk_awal($rincian, $tipe),
                ];
                break;

            case 'lahir':
                $data = [
                    'title' => (in_array($tipe, $keluarga) ? 'KELUARGA BARU BULAN ' : 'KELAHIRAN BULAN ') . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->kelahiran($rincian, $tipe),
                ];
                break;

            case 'mati':
                $data = [
                    'title' => 'KEMATIAN BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->kematian($rincian, $tipe),
                ];
                break;

            case 'datang':
                $data = [
                    'title' => 'PENDATANG BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->pendatang($rincian, $tipe),
                ];
                break;

            case 'pindah':
                $data = [
                    'title' => 'PINDAH/KELUAR PERGI BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->pindah($rincian, $tipe),
                ];
                break;

            case 'hilang':
                $data = [
                    'title' => 'PENDUDUK HILANG BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->hilang($rincian, $tipe),
                ];
                break;

            case 'akhir':
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AKHIR BULAN ' . $titlePeriode,
                    'main'  => $this->laporan_bulanan_model->penduduk_akhir($rincian, $tipe),
                ];
                break;
        }

        $this->render('laporan/tabel_bulanan_detil', $data);
    }
}
