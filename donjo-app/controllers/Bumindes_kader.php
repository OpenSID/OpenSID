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

class Bumindes_kader extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['kader_model', 'pamong_model', 'penduduk_model']);
        $this->modul_ini     = 301;
        $this->sub_modul_ini = 305;
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $start  = $this->input->post('start');
            $length = $this->input->post('length');
            $search = $this->input->post('search[value]');
            $order  = $this->kader_model::ORDER_ABLE[$this->input->post('order[0][column]')];
            $dir    = $this->input->post('order[0][dir]');

            return json([
                'draw'            => $this->input->post('draw'),
                'recordsTotal'    => $this->kader_model->get_data()->count_all_results(),
                'recordsFiltered' => $this->kader_model->get_data($search)->count_all_results(),
                'data'            => $this->kader_model->get_data($search)->order_by($order, $dir)->limit($length, $start)->get()->result(),
            ]);
        }

        $this->render('bumindes/pembangunan/main', [
            'selected_nav' => 'kader',
            'subtitle'     => 'Buku Kader Pemberdayaan',
            'main_content' => 'bumindes/pembangunan/kader/index',
        ]);
    }

    public function form($id = 0)
    {
        if ($id) {
            $data['main']        = $this->kader_model->find($id) ?? redirect($this->controller . '/form');
            $data['form_action'] = site_url("{$this->controller}/ubah/{$id}");
        } else {
            $data['main']        = null;
            $data['form_action'] = site_url("{$this->controller}/tambah");
        }
        $data['daftar_penduduk'] = $this->kader_model->list_penduduk($data['main']['penduduk_id'] ?? 0);
        $data['daftar_bidang']   = $this->referensi_model->list_data('ref_penduduk_bidang');
        $data['daftar_kursus']   = $this->referensi_model->list_data('ref_penduduk_kursus');

        $this->render('bumindes/pembangunan/kader/form', $data);
    }

    public function get_kursus()
    {
        $data = $this->kader_model->get_kursus($this->input->get('nama', true));

        echo json_encode($data);
    }

    public function get_bidang()
    {
        $data = $this->kader_model->get_bidang($this->input->get('nama', true));

        echo json_encode($data);
    }

    public function tambah()
    {
        $this->redirect_hak_akses('u');
        $this->kader_model->tambah();

        redirect($this->controller);
    }

    public function ubah($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->kader_model->ubah($id);

        redirect($this->controller);
    }

    public function hapus($id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->kader_model->hapus($id);

        redirect($this->controller);
    }

    public function hapus_semua($id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->kader_model->hapus_semua();

        redirect($this->controller);
    }

    public function dialog($aksi = '')
    {
        $data = [
            'aksi'        => $aksi,
            'form_action' => site_url($this->controller . '/cetak/' . $aksi),
            'isi'         => 'bumindes/pembangunan/ajax_dialog',
        ];

        $this->load->view('global/dialog_cetak', $data);
    }

    public function cetak($aksi = '')
    {
        $data = [
            'aksi'           => $aksi,
            'config'         => $this->header['desa'],
            'pamong_ketahui' => $this->pamong_model->get_ttd(),
            'pamong_ttd'     => $this->pamong_model->get_ub(),
            'main'           => $this->kader_model->get_data()->get()->result(),
            'tgl_cetak'      => $this->input->post('tgl_cetak'),
            'file'           => 'Buku ' . ucwords($this->tipe) . ' Kerja Pembangunan',
            'isi'            => 'bumindes/pembangunan/kader/cetak',
            'letak_ttd'      => ['2', '2', '5'],
        ];

        $this->load->view('global/format_cetak', $data);
    }
}
