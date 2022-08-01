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

class Laporan_inventaris extends Admin_Controller
{
    private $list_session = ['tahun'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['inventaris_laporan_model', 'pamong_model', 'surat_model']);
        $this->modul_ini     = 15;
        $this->sub_modul_ini = 61;
    }

    public function index()
    {
        $data['pamong'] = $this->pamong_model->list_data();
        $data           = array_merge($data, $this->inventaris_laporan_model->laporan_inventaris());
        $data['tip']    = 1;

        $this->render('inventaris/laporan/table', $data);
    }

    public function cetak($tahun, $penandatangan)
    {
        $data['header'] = $this->config_model->get_data();
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_print', $data);
    }

    public function download($tahun, $penandatangan)
    {
        $data['header'] = $this->config_model->get_data();
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_excel', $data);
    }

    public function mutasi()
    {
        $this->load->model('surat_model');
        $data['pamong'] = $this->surat_model->list_pamong();
        $data['tip']    = 2;
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_laporan_inventaris());

        $this->render('inventaris/laporan/table_mutasi', $data);
    }

    public function cetak_mutasi($tahun, $penandatangan)
    {
        $data['header'] = $this->config_model->get_data();
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_print_mutasi', $data);
    }

    public function download_mutasi($tahun, $penandatangan)
    {
        $data['header'] = $this->config_model->get_data();
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_excel_mutasi', $data);
    }

    public function permendagri_47($asset = null)
    {
        $tahun = (isset($this->session->tahun)) ? $this->session->tahun : date('Y');

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $pamong        = $this->pamong_model->list_data();
        $data['kades'] = array_filter($pamong, static function ($x) {
            if ($x['jabatan'] == 'Kepala Desa') {
                return $x;
            }
        });

        $data['sekdes'] = array_filter($pamong, static function ($x) {
            if ($x['jabatan'] == 'Sekretaris Desa') {
                return $x;
            }
        });

        $data['tip']   = 3;
        $data['data']  = $this->inventaris_laporan_model->permen_47($tahun, $asset);
        $data['tahun'] = $tahun;

        $this->render('inventaris/laporan/table_permen47', $data);
    }

    public function permendagri_47_cetak($kades, $sekdes, $asset = null)
    {
        $tahun           = (isset($this->session->tahun)) ? $this->session->tahun : date('Y');
        $data['header']  = $this->config_model->get_data();
        $pamong          = $this->pamong_model->list_data();
        $data['kades']   = $this->pamong_model->get_data($kades);
        $data['sekdes']  = $this->pamong_model->get_data($sekdes);
        $data['data']    = $this->inventaris_laporan_model->permen_47($tahun, $asset);
        $data['tahun']   = $tahun;
        $data['tanggal'] = date('d / M / y');

        $this->load->view('inventaris/laporan/permen47_print', $data);
    }

    public function permendagri_47_excel($kades, $sekdes, $asset = null)
    {
        $tahun           = (isset($this->session->tahun)) ? $this->session->tahun : date('Y');
        $data['header']  = $this->config_model->get_data();
        $pamong          = $this->pamong_model->list_data();
        $data['kades']   = $this->pamong_model->get_data($kades);
        $data['sekdes']  = $this->pamong_model->get_data($sekdes);
        $data['data']    = $this->inventaris_laporan_model->permen_47($tahun, $asset);
        $data['tahun']   = $tahun;
        $data['tanggal'] = date('d / M / y');

        $this->load->view('inventaris/laporan/permen47_excel', $data);
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('laporan_inventaris/permendagri_47');
    }
}
