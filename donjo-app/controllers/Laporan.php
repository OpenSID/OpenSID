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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['laporan_bulanan_model', 'pamong_model']);
        $this->modul_ini          = 3;
        $this->sub_modul_ini      = 28;
        $this->header['kategori'] = 'data_lengkap';
    }

    public function clear()
    {
        session_error_clear();
        $this->session->unset_userdata(['cari']);
        $this->session->bulanku  = date('n');
        $this->session->tahunku  = date('Y');
        $this->session->per_page = 200;

        redirect('laporan');
    }

    public function index()
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
        if (! $this->setting->tgl_data_lengkap_aktif || empty($this->setting->tgl_data_lengkap)) {
            $data['data_lengkap'] = false;
            $this->render('laporan/bulanan', $data);

            return;
        }
        $tahun_bulan = (new DateTime($this->setting->tgl_data_lengkap))->format('Y-m');
        if ($tahun_bulan > $data['tahunku'] . '-' . $data['bulanku']) {
            $data['sesudah_data_lengkap'] = false;
            $this->render('laporan/bulanan', $data);

            return;
        }
        $this->session->tgl_lengkap = rev_tgl($this->setting->tgl_data_lengkap);
        $data['tahun_lengkap']      = (new DateTime($this->setting->tgl_data_lengkap))->format('Y');
        $data['config']             = $this->config_model->get_data();
        $data['pamong']             = $this->pamong_model->list_data();
        $data['kelahiran']          = $this->laporan_bulanan_model->kelahiran();
        $data['kematian']           = $this->laporan_bulanan_model->kematian();
        $data['pendatang']          = $this->laporan_bulanan_model->pendatang();
        $data['pindah']             = $this->laporan_bulanan_model->pindah();
        $data['hilang']             = $this->laporan_bulanan_model->hilang();
        $data['penduduk_awal']      = $this->laporan_bulanan_model->penduduk_awal();
        $data['penduduk_akhir']     = $this->laporan_bulanan_model->penduduk_akhir();

        $this->render('laporan/bulanan', $data);
    }

    public function dialog_cetak()
    {
        $data['aksi']        = 'Cetak';
        $data['pamong']      = $this->pamong_model->list_data();
        $data['form_action'] = site_url('laporan/cetak');
        $this->load->view('laporan/ajax_cetak', $data);
    }

    public function dialog_unduh()
    {
        $data['aksi']        = 'Unduh';
        $data['pamong']      = $this->pamong_model->list_data();
        $data['form_action'] = site_url('laporan/unduh');
        $this->load->view('laporan/ajax_cetak', $data);
    }

    public function cetak()
    {
        $data = $this->data_cetak();
        $this->load->view('laporan/bulanan_print', $data);
    }

    public function unduh()
    {
        $data = $this->data_cetak();
        $this->load->view('laporan/bulanan_excel', $data);
    }

    private function data_cetak()
    {
        $data                   = [];
        $data['config']         = $this->config_model->get_data();
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

    public function bulan()
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

    public function detail_penduduk($rincian, $tipe)
    {
        $data     = [];
        $keluarga = ['kk', 'kk_l', 'kk_p'];

        switch (strtolower($rincian)) {
            case 'awal':
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AWAL BULAN INI',
                    'main'  => $this->laporan_bulanan_model->penduduk_awal($rincian, $tipe),
                ];
                break;

            case 'lahir':
                $data = [
                    'title' => in_array($tipe, $keluarga) ? 'KELUARGA BARU BULAN INI' : 'KELAHIRAN BULAN INI',
                    'main'  => $this->laporan_bulanan_model->kelahiran($rincian, $tipe),
                ];
                break;

            case 'mati':
                $data = [
                    'title' => 'KEMATIAN BULAN INI',
                    'main'  => $this->laporan_bulanan_model->kematian($rincian, $tipe),
                ];
                break;

            case 'datang':
                $data = [
                    'title' => 'PENDATANG BULAN INI',
                    'main'  => $this->laporan_bulanan_model->pendatang($rincian, $tipe),
                ];
                break;

            case 'pindah':
                $data = [
                    'title' => 'PINDAH/KELUAR PERGI BULAN INI',
                    'main'  => $this->laporan_bulanan_model->pindah($rincian, $tipe),
                ];
                break;

            case 'hilang':
                $data = [
                    'title' => 'PENDUDUK HILANG BULAN INI',
                    'main'  => $this->laporan_bulanan_model->hilang($rincian, $tipe),
                ];
                break;

            case 'akhir':
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AKHIR BULAN INI',
                    'main'  => $this->laporan_bulanan_model->penduduk_akhir($rincian, $tipe),
                ];
                break;
        }

        $this->render('laporan/tabel_bulanan_detil', $data);
    }
}
