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

class Keuangan_manual extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['keuangan_manual_model', 'keuangan_grafik_manual_model']);
        $this->modul_ini = 201;
    }

    public function index()
    {
        redirect('keuangan_manual/manual_apbdes');
    }

    // Manual Input Anggaran dan Realisasi APBDes
    public function setdata_laporan($tahun, $semester)
    {
        $sess_manual = [
            'set_tahun'    => $tahun,
            'set_semester' => $semester,
        ];
        $this->session->set_userdata($sess_manual);
        echo json_encode(true);
    }

    public function laporan_manual()
    {
        $data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();

        if (! empty($data['tahun_anggaran'])) {
            redirect('keuangan_manual/grafik_manual/rincian_realisasi_bidang_manual');
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = 'Data Laporan Keuangan Belum Tersedia';
            redirect('keuangan_manual/manual_apbdes');
        }
    }

    public function grafik_manual($jenis)
    {
        $this->sub_modul_ini = 210;

        $data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        $tahun                  = $this->session->set_tahun ?: $data['tahun_anggaran'][0];
        $sess_manual            = [
            'set_tahun' => $tahun,
        ];
        $this->session->set_userdata($sess_manual);
        $this->load->model('keuangan_grafik_manual_model');
        $thn = $this->session->set_tahun;

        switch ($jenis) {
            case 'rincian_realisasi_bidang_manual':
                $this->rincian_realisasi_manual($thn, 'Akhir Bidang Manual');
                break;

            case 'grafik-RP-APBD-manual':
                $this->grafik_rp_apbd_manual($thn);
                break;

            default:
                $this->grafik_rp_apbd_manual($thn);
                break;
        }
    }

    private function rincian_realisasi_manual($thn, $judul)
    {
        $data                   = $this->keuangan_grafik_manual_model->lap_rp_apbd($thn);
        $data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        $data['ta']             = $this->session->set_tahun;
        $this->session->submenu = 'Laporan Keuangan ' . $judul;
        $this->render('keuangan/rincian_realisasi_manual', $data);
    }

    private function grafik_rp_apbd_manual($thn)
    {
        $data                   = $this->keuangan_grafik_manual_model->grafik_keuangan_tema($thn);
        $data['tahun_anggaran'] = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        $this->session->submenu = 'Grafik Keuangan';
        $this->render('keuangan/grafik_rp_apbd_manual', $data);
    }

    public function manual_apbdes()
    {
        $this->sub_modul_ini      = 209;
        $data['tahun_anggaran']   = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        $default_tahun            = ! empty($data['tahun_anggaran']) ? $data['tahun_anggaran'][0] : null;
        $this->session->set_tahun = $this->session->set_tahun ?? $default_tahun;
        $this->session->set_jenis = $this->session->set_jenis ?? '4.PENDAPATAN';
        $data['tahun']            = $this->session->set_tahun;
        $data['jenis']            = $this->session->set_jenis;
        $data['lpendapatan']      = $this->keuangan_manual_model->list_rek_pendapatan();
        $data['lbelanja']         = $this->keuangan_manual_model->list_rek_belanja();
        $data['lbiaya']           = $this->keuangan_manual_model->list_rek_biaya();
        $data['lakun']            = $this->keuangan_manual_model->list_akun();
        $data['main']             = $this->keuangan_manual_model->list_apbdes($data['tahun']);

        $this->render('keuangan/manual_apbdes', $data);
    }

    public function data_anggaran()
    {
        $data = $this->keuangan_manual_model->list_apbdes();
        echo json_encode($data);
    }

    public function load_data()
    {
        $data = $this->keuangan_manual_model->list_data_keuangan();
        echo json_encode($data);
    }

    public function get_anggaran()
    {
        $id   = $this->input->get('id');
        $data = $this->keuangan_manual_model->get_anggaran($id);
        echo json_encode($data);
    }

    public function simpan_anggaran()
    {
        $this->redirect_hak_akses('u');
        $insert = $this->validation($this->input->post());
        $data   = $this->keuangan_manual_model->simpan_anggaran($insert);

        status_sukses($data);
        echo json_encode($data);
    }

    public function update_anggaran()
    {
        $this->redirect_hak_akses('u');
        $id     = $this->input->post('id');
        $update = $this->validation($this->input->post());
        $data   = $this->keuangan_manual_model->update_anggaran($id, $update);

        status_sukses($data);
        echo json_encode($data);
    }

    public function delete_input($id = '')
    {
        $this->redirect_hak_akses('h');
        $this->keuangan_manual_model->delete_input($id);
        redirect('keuangan_manual/manual_apbdes');
    }

    public function delete_all()
    {
        $this->keuangan_manual_model->delete_all();
        redirect('keuangan_manual/manual_apbdes');
    }

    public function salin_anggaran_tpl()
    {
        $thn_apbdes               = bilangan($this->input->post('kode'));
        $this->session->set_tahun = $thn_apbdes;
        $data                     = $this->keuangan_manual_model->salin_anggaran_tpl($thn_apbdes);
        echo json_encode($data);
    }

    // data tahun anggaran untuk keperluan dropdown pada plugin keuangan di text editor
    public function cek_tahun_manual()
    {
        $data       = $this->keuangan_manual_model->list_tahun_anggaran_manual();
        $list_tahun = [];

        foreach ($data as $tahun) {
            $list_tahun[] = [
                'text'  => $tahun,
                'value' => $tahun,
            ];
        }
        echo json_encode($list_tahun);
    }

    /**
     * untuk menghindari double post browser
     * https://en.wikipedia.org/wiki/Post/Redirect/Get
     */
    public function set_terpilih()
    {
        $post_tahun               = $this->input->post('tahun_anggaran');
        $post_jenis               = $this->input->post('jenis_anggaran');
        $this->session->set_tahun = $post_tahun;
        $this->session->set_jenis = $post_jenis;
        redirect('keuangan_manual/manual_apbdes');
    }

    private function validation($post = [])
    {
        return [
            'Tahun'           => bilangan($post['Tahun']),
            'Kd_Akun'         => $post['Kd_Akun'],
            'Kd_Keg'          => $post['Kd_Keg'],
            'Kd_Rincian'      => $post['Kd_Rincian'],
            'Nilai_Anggaran'  => ltrim(bilangan($post['Nilai_Anggaran']), '0'),
            'Nilai_Realisasi' => ltrim(bilangan($post['Nilai_Realisasi']), '0'),
        ];
    }
}
