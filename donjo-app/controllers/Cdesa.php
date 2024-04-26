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

use App\Models\RefPersilKelas;
use App\Models\RefPersilMutasi;

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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Cdesa extends Admin_Controller
{
    public $modul_ini           = 'pertanahan';
    public $sub_modul_ini       = 'c-desa';
    private array $set_page     = ['20', '50', '100'];
    private array $list_session = ['cari'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('data_persil_model');
        $this->load->model('cdesa_model');
        $this->load->model('wilayah_model');
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];
        redirect($this->controller);
    }

    // TODO: fix
    public function autocomplete()
    {
        return json($this->cdesa_model->autocomplete($this->input->post('cari')));
    }

    public function search(): void
    {
        $this->session->cari = $this->input->post('cari') ?: null;
        redirect('cdesa');
    }

    public function index($page = 1, $o = 0): void
    {
        $this->tab_ini = 12;

        $data['cari']         = $_SESSION['cari'] ?? '';
        $_SESSION['per_page'] = $_POST['per_page'] ?: null;
        $data['per_page']     = $_SESSION['per_page'];

        $data['func']     = 'index';
        $data['set_page'] = $this->set_page;
        $data['paging']   = $this->cdesa_model->paging_c_desa($page);
        $data['keyword']  = $this->data_persil_model->autocomplete();
        $data['cdesa']    = $this->cdesa_model->list_c_desa($data['paging']->offset, $data['paging']->per_page);

        $this->render('data_persil/c_desa', $data);
    }

    public function rincian($id): void
    {
        $this->tab_ini   = 13;
        $data            = [];
        $data['cdesa']   = $this->cdesa_model->get_cdesa($id);
        $data['desa']    = $this->header['desa'];
        $data['pemilik'] = $this->cdesa_model->get_pemilik($id);
        $data['persil']  = $this->cdesa_model->get_list_persil($id);
        $this->render('data_persil/rincian', $data);
    }

    public function mutasi($id_cdesa, $id_persil): void
    {
        $data            = [];
        $data['cdesa']   = $this->cdesa_model->get_cdesa($id_cdesa);
        $data['desa']    = $this->header['desa'];
        $data['pemilik'] = $this->cdesa_model->get_pemilik($id_cdesa);
        $data['mutasi']  = $this->cdesa_model->get_list_mutasi($id_cdesa, $id_persil);
        $data['persil']  = $this->data_persil_model->get_persil($id_persil);
        if (empty($data['cdesa'])) {
            show_404();
        }

        $this->render('data_persil/mutasi_persil', $data);
    }

    public function create($mode = 0, $id = 0): void
    {
        isCan('u');
        $this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');

        $this->tab_ini = empty($mode) ? 10 : 12;

        $post             = $this->input->post();
        $data             = [];
        $data['mode']     = $mode;
        $data['penduduk'] = $this->cdesa_model->list_penduduk();
        if ($mode === 'edit') {
            $data['cdesa'] = $this->cdesa_model->get_cdesa($id) ?? show_404();
            $this->ubah_pemilik($id, $data, $post);
        } else {
            switch ($post['jenis_pemilik']) {
                case '1':
                    // Pemilik desa
                    if (! empty($post['nik'])) {
                        $data['pemilik'] = $this->cdesa_model->get_penduduk($post['nik'], $nik = true);
                    }
                    break;

                case '2':
                    // Pemilik luar desa
                    $data['cdesa']['jenis_pemilik'] = 2;
                    break;
            }
        }

        $this->render('data_persil/create', $data);
    }

    private function ubah_pemilik($id, array &$data, array $post): void
    {
        isCan('u');
        $jenis_pemilik_baru = $post['jenis_pemilik'] ?: 0;

        switch ($jenis_pemilik_baru) {
            case '0':
                // Buka form ubah pertama kali
                if ($data['cdesa']['jenis_pemilik'] == 1) {
                    $data['pemilik'] = $this->cdesa_model->get_pemilik($id);
                }
                break;

            case '1':
                // Ubah atau ambil pemilik desa
                $data['pemilik'] = $this->cdesa_model->get_pemilik($id);
                if ($post['nik'] && ${$data}['pemilik']['nik'] != $post['nik']) {
                    $data['pemilik'] = $this->cdesa_model->get_penduduk($post['nik'], $nik = true);
                }
                $data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
                break;

            case '2':
                // Ubah pemilik luar
                $data['cdesa']['jenis_pemilik'] = $jenis_pemilik_baru;
                break;
        }
    }

    public function simpan_cdesa($page = 1): void
    {
        isCan('u');
        $this->form_validation->set_rules('c_desa', 'Nomor Surat C-DESA', 'required|trim|numeric');
        $this->form_validation->set_rules('c_desa', 'Username', 'callback_cek_nomor');

        if ($this->form_validation->run() != false) {
            $id_cdesa = $this->cdesa_model->simpan_cdesa();
            if ($this->input->post('id')) {
                redirect('cdesa');
            } else {
                redirect("cdesa/create_mutasi/{$id_cdesa}");
            }
        } else {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = trim(strip_tags(validation_errors()));
            $jenis_pemilik         = $this->input->post('jenis_pemilik');
            $id                    = $this->input->post('id');
            if ($jenis_pemilik == 1) {
                if ($id) {
                    redirect('cdesa/create/edit/' . $id);
                } else {
                    redirect('cdesa/create');
                }
            } elseif ($id) {
                redirect('cdesa/create/edit/' . $id);
            } else {
                redirect('cdesa/create');
            }
        }
    }

    public function create_mutasi($id_cdesa, $id_persil = '', $id_mutasi = ''): void
    {
        isCan('u');
        $this->load->model('plan_area_model');
        $this->form_validation->set_rules('nama', 'Nama Jenis Tanah', 'required');
        $this->session->unset_userdata('cari'); // Area menggunakan session cari, jadi perlu dihapus terlebih dahulu

        $this->tab_ini = 12;
        if (empty($id_persil)) {
            $id_persil = $this->input->post('id_persil');
        }

        $data['persil'] = $id_persil ? $this->data_persil_model->get_persil($id_persil) ?? show_404() : null;

        if ($id_mutasi) {
            $data['persil'] = $this->cdesa_model->get_persil($id_mutasi);
            $data['mutasi'] = $this->cdesa_model->get_mutasi($id_mutasi);
        }
        $data['cdesa'] = $this->cdesa_model->get_cdesa($id_cdesa) ?? show_404();

        $data['list_cdesa'] = $this->cdesa_model->list_c_desa(0, 0, [$id_cdesa]);
        $data['pemilik']    = $this->cdesa_model->get_pemilik($id_cdesa);

        $data['list_persil']         = $this->data_persil_model->list_persil();
        $data['persil_lokasi']       = $this->wilayah_model->list_semua_wilayah();
        $data['persil_kelas']        = RefPersilKelas::get()->toArray();
        $data['persil_sebab_mutasi'] = RefPersilMutasi::get()->toArray();
        $data['peta']                = $this->plan_area_model->list_data();

        $this->render('data_persil/create_mutasi', $data);
    }

    public function simpan_mutasi($id_cdesa, $id_mutasi = ''): void
    {
        isCan('u');
        $data = $this->cdesa_model->simpan_mutasi($id_cdesa, $id_mutasi, $this->input->post());
        if ($data['id_persil']) {
            redirect("cdesa/mutasi/{$id_cdesa}/{$data['id_persil']}");
        } else {
            redirect("cdesa/rincian/{$id_cdesa}");
        }
    }

    public function hapus_mutasi($cdesa, $id_mutasi): void
    {
        isCan('u');
        $id_persil = $this->db
            ->select('id_persil')
            ->where('id', $id_mutasi)
            ->where('config_id', identitas('id'))
            ->get('mutasi_cdesa')
            ->row()
            ->id_persil;

        $this->db
            ->where('id', $id_mutasi)
            ->where('config_id', identitas('id'))
            ->delete('mutasi_cdesa');
        redirect("cdesa/mutasi/{$cdesa}/{$id_persil}");
    }

    // TODO: gunakan pada waktu validasi C-Desa
    public function cek_nomor($nomor): bool
    {
        $id_cdesa = $this->input->post('id');
        if ($id_cdesa) {
            $this->db->where('id <>', $id_cdesa);
        }
        $ada = $this->db
            ->where('nomor', $nomor)
            ->where('config_id', identitas('id'))
            ->get('cdesa')
            ->num_rows();

        if ($ada) {
            $this->form_validation->set_message('cek_nomor', 'Nomor C-Desa sudah ada');

            return false;
        }

        return true;
    }

    // TODO: perbaiki
    public function panduan(): void
    {
        $this->tab_ini = 15;
        $nav['act']    = 7;
        $this->render('data_persil/panduan');
    }

    public function hapus($id): void
    {
        isCan('h');
        $this->cdesa_model->hapus_cdesa($id);
        redirect('cdesa');
    }

    public function import(): void
    {
        $data['form_action'] = site_url('data_persil/import_proses');
        $this->load->view('data_persil/import', $data);
    }

    public function import_proses(): void
    {
        isCan('u');
        $this->data_persil_model->impor_persil();
        redirect('data_persil');
    }

    public function cetak($o = 0): void
    {
        $data['data_cdesa'] = $this->cdesa_model->list_c_desa();
        $this->load->view('data_persil/c_desa_cetak', $data);
    }

    public function unduh($o = 0): void
    {
        $data['data_cdesa'] = $this->cdesa_model->list_c_desa();
        $this->load->view('data_persil/c_desa_unduh', $data);
    }

    public function form_c_desa($id = 0): void
    {
        $data['desa']   = $this->header['desa'];
        $data['cdesa']  = $this->cdesa_model->get_cdesa($id);
        $data['basah']  = $this->cdesa_model->get_cetak_mutasi($id, 'BASAH');
        $data['kering'] = $this->cdesa_model->get_cetak_mutasi($id, 'KERING');
        $this->load->view('data_persil/c_desa_form_print', $data);
    }

    public function awal_persil($id_cdesa, $id_persil, $hapus = false): void
    {
        isCan('u');
        $this->data_persil_model->awal_persil($id_cdesa, $id_persil, $hapus);
        redirect("cdesa/mutasi/{$id_cdesa}/{$id_persil}");
    }
}
