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

class Inventaris_peralatan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['inventaris_peralatan_model', 'pamong_model', 'aset_model']);
        $this->modul_ini     = 15;
        $this->sub_modul_ini = 61;
    }

    public function index()
    {
        $data['main']   = $this->inventaris_peralatan_model->list_inventaris();
        $data['total']  = $this->inventaris_peralatan_model->sum_inventaris();
        $data['pamong'] = $this->pamong_model->list_data();
        $data['tip']    = 1;

        $this->render('inventaris/peralatan/table', $data);
    }

    public function view($id)
    {
        $data['main'] = $this->inventaris_peralatan_model->view($id);
        $data['tip']  = 1;

        $this->render('inventaris/peralatan/view_inventaris', $data);
    }

    public function view_mutasi($id)
    {
        $data['main'] = $this->inventaris_peralatan_model->view_mutasi($id);
        $data['tip']  = 2;

        $this->render('inventaris/peralatan/view_mutasi', $data);
    }

    public function edit($id)
    {
        $this->redirect_hak_akses('u');
        $data['main']      = $this->inventaris_peralatan_model->view($id);
        $data['get_kode']  = $this->header['desa'];
        $data['aset']      = $this->aset_model->list_aset(3);
        $data['count_reg'] = $this->inventaris_peralatan_model->count_reg();
        $data['kd_reg']    = $this->inventaris_peralatan_model->list_inventaris_kd_register();
        $data['tip']       = 1;

        $this->render('inventaris/peralatan/edit_inventaris', $data);
    }

    public function edit_mutasi($id)
    {
        $this->redirect_hak_akses('u');
        $data['main'] = $this->inventaris_peralatan_model->edit_mutasi($id);
        $data['tip']  = 2;

        $this->render('inventaris/peralatan/edit_mutasi', $data);
    }

    public function form()
    {
        $this->redirect_hak_akses('u');
        $data['tip']       = 1;
        $data['get_kode']  = $this->header['desa'];
        $data['aset']      = $this->aset_model->list_aset(3);
        $data['count_reg'] = $this->inventaris_peralatan_model->count_reg();

        $this->render('inventaris/peralatan/form_tambah', $data);
    }

    public function form_mutasi($id)
    {
        $this->redirect_hak_akses('u');
        $data['main'] = $this->inventaris_peralatan_model->view($id);
        $data['tip']  = 1;

        $this->render('inventaris/peralatan/form_mutasi', $data);
    }

    public function mutasi()
    {
        $data['main'] = $this->inventaris_peralatan_model->list_mutasi_inventaris();
        $data['tip']  = 2;

        $this->render('inventaris/peralatan/table_mutasi', $data);
    }

    public function cetak($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['total']  = $this->inventaris_peralatan_model->sum_print($tahun);
        $data['print']  = $this->inventaris_peralatan_model->cetak($tahun);
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);

        $this->load->view('inventaris/peralatan/inventaris_print', $data);
    }

    public function download($tahun, $penandatangan)
    {
        $data['header'] = $this->header['desa'];
        $data['total']  = $this->inventaris_peralatan_model->sum_print($tahun);
        $data['print']  = $this->inventaris_peralatan_model->cetak($tahun);
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);

        $this->load->view('inventaris/peralatan/inventaris_excel', $data);
    }
}
