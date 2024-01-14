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

use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan_inventaris extends Admin_Controller
{
    private $list_session = ['tahun'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['inventaris_laporan_model', 'pamong_model', 'surat_model']);
        $this->modul_ini     = 'sekretariat';
        $this->sub_modul_ini = 61;
    }

    public function index()
    {
        $data['pamong'] = Pamong::penandaTangan()->get();
        $data           = array_merge($data, $this->inventaris_laporan_model->laporan_inventaris());
        $data['tip']    = 1;

        $this->render('inventaris/laporan/table', $data);
    }

    public function cetak($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_print', $data);
    }

    public function download($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_excel', $data);
    }

    public function mutasi()
    {
        $this->load->model('surat_model');
        $data['pamong'] = Pamong::penandaTangan()->get();
        $data['tip']    = 2;
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_laporan_inventaris());

        $this->render('inventaris/laporan/table_mutasi', $data);
    }

    public function cetak_mutasi($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_print_mutasi', $data);
    }

    public function download_mutasi($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['tahun']  = $tahun;
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);
        $data           = array_merge($data, $this->inventaris_laporan_model->mutasi_cetak_inventaris($tahun));

        $this->load->view('inventaris/laporan/inventaris_excel_mutasi', $data);
    }

    // TODO: Ini masih digunakan ? Jika tidak, hapus
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

    public function permendagri_47_dialog($aksi = 'cetak', $asset = null)
    {
        // TODO :: gunakan view global penandatangan
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $tahun           = (isset($this->session->tahun)) ? $this->session->tahun : date('Y');
        $data['header']  = $this->header['desa'];
        $data['data']    = $this->inventaris_laporan_model->permen_47($tahun, $asset);
        $data['tahun']   = $this->session->tahun;
        $data['tanggal'] = date('d / M / y');

        if ($aksi == 'unduh') {
            $this->load->view('inventaris/laporan/permen47_excel', $data);
        } else {
            $this->load->view('inventaris/laporan/permen47_print', $data);
        }
    }

    // TODO: Ini digunakan dimana pada view
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
