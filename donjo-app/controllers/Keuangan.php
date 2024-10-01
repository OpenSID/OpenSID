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

class Keuangan extends Admin_Controller
{
    public $modul_ini = 'keuangan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('keuangan_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('keuangan_grafik_dd_model');
    }

    public function setdata_laporan($tahun, $semester): void
    {
        $sess = [
            'set_tahun'    => $tahun,
            'set_semester' => $semester,
        ];
        $this->session->set_userdata($sess);
        echo json_encode(true, JSON_THROW_ON_ERROR);
    }

    public function laporan(): void
    {
        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();

        if (! empty($data['tahun_anggaran'])) {
            redirect('keuangan/grafik/rincian_realisasi');
        } else {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = 'Data Laporan Keuangan Belum Tersedia';
            redirect('keuangan/impor_data');
        }
    }

    public function grafik($jenis): void
    {
        $this->sub_modul_ini = 'laporan';

        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
        $tahun                  = $this->session->userdata('set_tahun') ?: $data['tahun_anggaran'][0];
        $semester               = $this->session->userdata('set_semester') ?: 0;
        $sess                   = [
            'set_tahun'    => $tahun,
            'set_semester' => $semester,
        ];
        $this->session->set_userdata($sess);
        $this->load->model('keuangan_grafik_model');

        $this->session->userdata('set_semester');
        $thn = $this->session->userdata('set_tahun');

        switch ($jenis) {
            case 'grafik-RP-APBD':
            default:
                $this->grafik_rp_apbd($thn);
                break;

            case 'rincian_realisasi':
                $this->rincian_realisasi($thn, 'Akhir');
                break;

            case 'rincian_realisasi_smt1':
                $this->rincian_realisasi($thn, 'Semester1', $smt1 = 1);
                break;

            case 'rincian_realisasi_bidang':
                $this->rincian_realisasi($thn, 'Akhir Bidang');
                break;

            case 'rincian_realisasi_smt1_bidang':
                $this->rincian_realisasi($thn, 'Semester1 Bidang', $smt1 - 1);
                break;

            case 'grafik-RP-APBD-DD':
                $this->grafik_rp_apbd_dd($thn);
                break;

            case 'rincian_realisasi_dd':
                $this->rincian_realisasi_dd($thn, 'Akhir DD');
                break;

            case 'rincian_realisasi_smt1_dd':
                $this->rincian_realisasi_dd($thn, 'Semester1 DD', $smt1 = 1);
                break;

            case 'rincian_realisasi_bidang_dd':
                $this->rincian_realisasi_dd($thn, 'Akhir Bidang DD');
                break;

            case 'rincian_realisasi_smt1_bidang_dd':
                $this->rincian_realisasi_dd($thn, 'Semester1 Bidang DD', $smt1 - 1);
                break;
        }
    }

    private function rincian_realisasi($thn, string $judul, $smt1 = false): void
    {
        $data                   = $this->keuangan_grafik_model->lap_rp_apbd($thn, $smt1);
        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
        $data['ta']             = $this->session->userdata('set_tahun');
        $data['sm']             = $smt1 ? '1' : '2';
        $_SESSION['submenu']    = 'Laporan Keuangan ' . $judul;
        $this->render('keuangan/rincian_realisasi', $data);
    }

    private function rincian_realisasi_dd($thn, string $judul, $smt1 = false): void
    {
        $data                   = $this->keuangan_grafik_dd_model->lap_rp_apbd($thn, $smt1);
        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
        $data['ta']             = $this->session->userdata('set_tahun');
        $data['sm']             = $smt1 ? '1' : '2';
        $_SESSION['submenu']    = 'Laporan Keuangan ' . $judul;
        $this->render('keuangan/rincian_realisasi_dd', $data);
    }

    private function grafik_rp_apbd($thn): void
    {
        $data                   = $this->keuangan_grafik_model->grafik_keuangan_tema($thn);
        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
        $_SESSION['submenu']    = 'Grafik Keuangan';
        $this->render('keuangan/grafik_rp_apbd', $data);
    }

    private function grafik_rp_apbd_dd($thn): void
    {
        $data                   = $this->keuangan_grafik_dd_model->grafik_keuangan_tema($thn);
        $data['tahun_anggaran'] = $this->keuangan_model->list_tahun_anggaran();
        $_SESSION['submenu']    = 'Grafik Keuangan DD';
        $this->render('keuangan/grafik_rp_apbd_dd', $data);
    }

    public function impor_data(): void
    {
        $this->sub_modul_ini = 'impor-data';

        $data['main']        = $this->keuangan_model->list_data();
        $data['form_action'] = site_url('keuangan/proses_impor');

        $this->render('keuangan/impor_data', $data);
    }

    public function proses_impor(): void
    {
        isCan('u');
        if (empty($_FILES['keuangan']['name'])) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak ada file untuk diimpor';
            redirect('keuangan/impor_data');
        }
        if ($_POST['jenis_impor'] == 'update') {
            $this->keuangan_model->extract_update();
        } else {
            $this->keuangan_model->extract();
        }
        redirect('keuangan/impor_data');
    }

    public function cek_versi_database(): void
    {
        $nama       = $_FILES['keuangan'];
        $file_parts = pathinfo($nama['name']);
        if ($file_parts['extension'] === 'zip') {
            $cek = $this->keuangan_model->cek_keuangan_master($nama);
            if ($cek == -1) {
                echo json_encode(2, JSON_THROW_ON_ERROR);
            } elseif ($cek) {
                $output = ['id' => $cek->id, 'tahun_anggaran' => $cek->tahun_anggaran];
                echo json_encode($output, JSON_THROW_ON_ERROR);
            } else {
                echo json_encode(0, JSON_THROW_ON_ERROR);
            }
        } else {
            echo json_encode(1, JSON_THROW_ON_ERROR);
        }
    }

    // data tahun anggaran untuk keperluan dropdown pada plugin keuangan di text editor
    public function cek_tahun(): void
    {
        $data       = $this->keuangan_model->list_tahun_anggaran();
        $list_tahun = [];

        foreach ($data as $tahun) {
            $list_tahun[] = [
                'text'  => $tahun,
                'value' => $tahun,
            ];
        }
        echo json_encode($list_tahun, JSON_THROW_ON_ERROR);
    }

    public function delete($id = ''): void
    {
        isCan('h');
        $this->keuangan_model->delete($id);
        redirect('keuangan/impor_data');
    }

    public function pilih_desa($id_master): void
    {
        $data['desa_ganda'] = $this->keuangan_model->cek_desa($id_master);
        $data['id_master']  = $id_master;
        $this->load->view('keuangan/pilih_desa', $data);
    }

    public function bersihkan_desa($id_master): void
    {
        $this->keuangan_model->bersihkan_desa($id_master, $this->input->post('kode_desa'));
        redirect('keuangan/impor_data');
    }
}
