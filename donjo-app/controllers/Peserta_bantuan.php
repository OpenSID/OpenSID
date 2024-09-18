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

use App\Enums\SasaranEnum;
use App\Models\BantuanPeserta;
use Illuminate\Support\Str;

class Peserta_bantuan extends Admin_Controller
{
    public $modul_ini        = 'bantuan';
    public $akses_modul      = 'peserta-bantuan';
    private array $_set_page = ['20', '50', '100'];

    public function __construct()
    {
        parent::__construct();
        isCan('b', 'peserta-bantuan');
        $this->load->model(['program_bantuan_model']);
    }

    public function detail($program_id = 0, $p = 1): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['cari']         = $this->session->cari ?: '';
        $data['program']      = $this->program_bantuan_model->get_program($p, $program_id);
        $data['keyword']      = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
        $data['paging']       = $data['program'][0]['paging'];
        $data['list_sasaran'] = SasaranEnum::all();
        $data['p']            = $p;
        $data['func']         = "detail/{$program_id}";
        $data['per_page']     = $this->session->per_page;
        $data['set_page']     = $this->_set_page;
        $data['nama_excerpt'] = Str::limit($data['program'][0]['nama'], 25);

        $this->render('program_bantuan/detail', $data);
    }

    public function form($program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $this->session->unset_userdata('cari');
        $data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
        $sasaran         = $data['program'][0]['sasaran'];
        $nik             = $this->input->post('nik');

        if (isset($nik)) {
            $data['individu']            = $this->program_bantuan_model->get_peserta($nik, $sasaran);
            $data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($sasaran, $data['individu']['id_peserta']);
        } else {
            $data['individu'] = null;
        }

        $data['form_action']  = site_url('peserta_bantuan/add_peserta/' . $program_id);
        $data['list_sasaran'] = SasaranEnum::all();

        $this->render('program_bantuan/form', $data);
    }

    // $id = program_peserta.id
    public function peserta($cat = 0, $id = 0): void
    {
        $data = $this->program_bantuan_model->get_peserta_program($cat, $id);

        $this->render('program_bantuan/peserta', $data);
    }

    // $id = program_peserta.id
    public function data_peserta($id = 0): void
    {
        $data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);

        switch ($data['peserta']['sasaran']) {
            case '1':
            case '2':
                $peserta_id = $data['peserta']['kartu_id_pend'];
                break;

            case '3':
            case '4':
                $peserta_id = $data['peserta']['peserta'];
                break;
        }
        $data['individu']            = $this->program_bantuan_model->get_peserta($peserta_id, $data['peserta']['sasaran']);
        $data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($data['peserta']['sasaran'], $data['peserta']['peserta']);
        $data['detail']              = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);

        $this->render('program_bantuan/data_peserta', $data);
    }

    public function add_peserta($program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');

        $cek = BantuanPeserta::where('program_id', $program_id)->where('kartu_id_pend', $this->input->post('kartu_id_pend'))->first();

        if ($cek) {
            $this->session->success = -2;
        } else {
            $this->program_bantuan_model->add_peserta($program_id);
        }

        $redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "peserta_bantuan/detail/{$program_id}";

        $this->session->unset_userdata('aksi');

        redirect($redirect);
    }

    // $id = program_peserta.id
    public function edit_peserta($id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $this->program_bantuan_model->edit_peserta($id);
        $program_id = $this->input->post('program_id');

        redirect("peserta_bantuan/detail/{$program_id}");
    }

    // $id = program_peserta.id
    public function edit_peserta_form($id = 0): void
    {
        isCan('u', 'peserta-bantuan');

        $data                = $this->program_bantuan_model->get_program_peserta_by_id($id) ?? show_404();
        $data['form_action'] = site_url("peserta_bantuan/edit_peserta/{$id}");
        $this->load->view('program_bantuan/edit_peserta', $data);
    }

    public function hapus_peserta($program_id = 0, $peserta_id = ''): void
    {
        isCan('h', 'peserta-bantuan');
        $this->program_bantuan_model->hapus_peserta($peserta_id);

        redirect("peserta_bantuan/detail/{$program_id}");
    }

    public function aksi($aksi = '', $program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $this->session->set_userdata('aksi', $aksi);

        redirect("peserta_bantuan/form/{$program_id}");
    }

    public function delete_all($program_id = 0): void
    {
        isCan('h', 'peserta-bantuan');
        $this->program_bantuan_model->delete_all();

        redirect("peserta_bantuan/detail/{$program_id}");
    }

    // $aksi = cetak/unduh
    public function daftar($program_id = 0, $aksi = ''): void
    {
        if ($program_id > 0) {
            $temp                    = $this->session->per_page;
            $this->session->per_page = 1_000_000_000; // Angka besar supaya semua data terunduh
            $data['sasaran']         = unserialize(SASARAN);

            $data['config']          = $this->header['desa'];
            $data['peserta']         = $this->program_bantuan_model->get_program(1, $program_id);
            $data['aksi']            = $aksi;
            $this->session->per_page = $temp;

            $this->load->view("program_bantuan/{$aksi}", $data);
        }
    }

    public function detail_clear($program_id): void
    {
        $this->session->per_page = $this->_set_page[0];
        $this->session->unset_userdata('cari');

        redirect("peserta_bantuan/detail/{$program_id}");
    }
}
