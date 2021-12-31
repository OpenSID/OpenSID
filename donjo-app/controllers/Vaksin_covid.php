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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Vaksin_covid extends Admin_Controller
{
    protected $_list_session;
    protected $_set_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['vaksin_covid_model', 'wilayah_model', 'pamong_model']);
        $this->_list_session = ['cari', 'dusun', 'vaksin', 'jenis_vaksin', 'tanggal_vaksin'];
        $this->_set_page     = ['50', '100', '200'];
        $this->modul_ini     = 206;
        $this->sub_modul_ini = 335;
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect($this->controller);
    }

    public function search()
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect($this->controller);
    }

    public function clear()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = 50;
        redirect($this->controller);
    }

    public function index(int $p = 1)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data = [
            'set_page'     => $this->_set_page,
            'list_dusun'   => $this->wilayah_model->list_dusun(),
            'main'         => $this->vaksin_covid_model->list_penduduk($p),
            'list_vaksin'  => $this->vaksin_covid_model->jenis_vaksin(),
            'paging'       => $this->vaksin_covid_model->paging($p),
            'per_page'     => $this->session->per_page,
            'func'         => 'index',
            'p'            => $p,
            'selected_nav' => 'daftar',
        ];

        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?? null;
        }

        $this->render('covid19/vaksin/index', $data);
    }

    public function form()
    {
        $this->redirect_hak_akses('u');
        $this->session->unset_userdata($this->_list_session);
        $id_penduduk = $this->input->get('terdata');
        $data        = [
            'list_vaksin'   => $this->vaksin_covid_model->jenis_vaksin(),
            'list_penduduk' => $this->vaksin_covid_model->list_penduduk(0),
            'penduduk'      => $this->vaksin_covid_model->data_penduduk($id_penduduk ?? null),
        ];

        $this->render('covid19/vaksin/form', $data);
    }

    public function update()
    {
        $this->redirect_hak_akses('u');
        $this->vaksin_covid_model->update_vaksin();

        if ($this->session->success == -1) {
            $this->session->dari_internal = true;
            redirect("{$this->controller}/form");
        } else {
            redirect("{$this->controller}/clear");
        }
    }

    public function laporan_penduduk()
    {
        $this->session->unset_userdata($this->_list_session);
        $pamong = $this->pamong_model->list_data();

        $data = [
            'selected_nav' => 'laporan',
            'main'         => $this->vaksin_covid_model->list_penduduk(0),
            'kades'        => $data['sekdes'] = $pamong,
            'sekdes'       => $data['sekdes'] = $pamong,
            'isi'          => 'covid19/vaksin/cetak',
        ];

        $this->render('covid19/vaksin/laporan_penduduk', $data);
    }

    public function laporan_penduduk_cetak($aksi)
    {
        $sekdes = (int) ($this->input->post('sekdes'));
        $this->session->unset_userdata($this->_list_session);
        $data['pamong_ttd'] = $this->pamong_model->get_data($sekdes);
        $data['aksi']       = $aksi;
        $data['header']     = $this->header['desa'];
        $data['file']       = 'Laporan Hasil Rekap Vaksin Covid 19';
        $data['isi']        = 'covid19/vaksin/laporan_penduduk_print';
        $data['letak_ttd']  = ['2', '2', '1'];
        $data['main']       = $this->vaksin_covid_model->list_penduduk(1, 0);

        $this->load->view('global/format_cetak', $data);
    }

    public function laporan_rekap()
    {
        $this->session->unset_userdata($this->_list_session);
        $umur          = (int) ($this->input->get('umur'));
        $penduduk      = $this->vaksin_covid_model->rekap(0);
        $rekap         = $this->rekap($penduduk);
        $sasaran       = $this->vaksin_covid_model->rekap($umur);
        $rekap_sasaran = $this->rekap($sasaran);
        $pamong        = $this->pamong_model->list_data();
        $data          = [
            'selected_nav' => 'rekap',
            'main'         => $rekap,
            'sasaran'      => $rekap_sasaran,
            'kades'        => $data['sekdes'] = $pamong,
            'sekdes'       => $data['sekdes'] = $pamong,
            'umur'         => $data['sekdes'] = $umur,
            'isi'          => 'covid19/vaksin/cetak_rekap',
            'aksi'         => 'Cetak',
        ];

        $this->render('covid19/vaksin/laporan_rekap', $data);
    }

    public function laporan_rekap_cetak($aksi)
    {
        $sekdes = (int) ($this->input->post('sekdes'));
        $umur   = (int) ($this->input->post('umur'));
        $this->session->unset_userdata($this->_list_session);
        $penduduk             = $this->vaksin_covid_model->rekap(0);
        $rekap                = $this->rekap($penduduk);
        $sasaran              = $this->vaksin_covid_model->rekap($umur);
        $rekap_sasaran        = $this->rekap($sasaran);
        $data['pamong_ttd']   = $this->pamong_model->get_data($sekdes);
        $data['aksi']         = $aksi;
        $data['header']       = $this->header['desa'];
        $data['file']         = 'Laporan Hasil Rekap Vaksin Covid 19';
        $data['isi']          = 'covid19/vaksin/laporan_rekap_print';
        $data['letak_ttd']    = ['2', '2', '1'];
        $data['main']         = $rekap;
        $data['sasaran']      = $sasaran;
        $data['tanggal']      = tgl_indo(date('Y-m-d'));
        $data['umur_sasaran'] = $umur;

        $this->load->view('global/format_cetak', $data);
    }

    public function rekap($penduduk)
    {
        $rekap = [];

        foreach ($penduduk as $key => $value) {
            $value->dusun = $value->dusun ?? 'Data Dusun Tidak Ada';
            if (! isset($rekap[$value->dusun])) {
                $rekap[$value->dusun] = ['vaksin_1' => [], 'vaksin_2' => [], 'vaksin_3' => [], 'belum' => []];
            }
            if ($value->vaksin_1 == null || $value->tunda == 1) {
                $rekap[$value->dusun]['belum'][] = $value->id;
            } elseif ($value->vaksin_1 == 1 && $value->vaksin_2 == 0 && $value->vaksin_3 == 0) {
                $rekap[$value->dusun]['vaksin_1'][] = $value->id;
            } elseif ($value->vaksin_1 == 1 && $value->vaksin_2 == 1 && $value->vaksin_3 == 0) {
                $rekap[$value->dusun]['vaksin_2'][] = $value->id;
            } elseif ($value->vaksin_1 == 1 && $value->vaksin_2 == 1 && $value->vaksin_3 == 1) {
                $rekap[$value->dusun]['vaksin_3'][] = $value->id;
            }
        }

        return $rekap;
    }
}
