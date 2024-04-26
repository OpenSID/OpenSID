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

use App\Models\Pamong;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_tanah extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['inventaris_tanah_model', 'pamong_model', 'aset_model']);
    }

    public function index(): void
    {
        $data['main']   = $this->inventaris_tanah_model->list_inventaris();
        $data['total']  = $this->inventaris_tanah_model->sum_inventaris();
        $data['pamong'] = Pamong::penandaTangan()->get();
        $data['tip']    = 1;

        $this->render('inventaris/tanah/table', $data);
    }

    public function view($id): void
    {
        $data['main'] = $this->inventaris_tanah_model->view($id);
        $data['tip']  = 1;

        $this->render('inventaris/tanah/view_inventaris', $data);
    }

    public function view_mutasi($id): void
    {
        $data['main'] = $this->inventaris_tanah_model->view_mutasi($id);
        $data['tip']  = 2;

        $this->render('inventaris/tanah/view_mutasi', $data);
    }

    public function edit($id): void
    {
        isCan('u');
        $data['main']      = $this->inventaris_tanah_model->view($id);
        $data['aset']      = $this->aset_model->list_aset(2);
        $data['count_reg'] = $this->inventaris_tanah_model->count_reg();
        $data['get_kode']  = $this->header['desa'];
        $data['kd_reg']    = $this->inventaris_tanah_model->list_inventaris_kd_register();
        $data['tip']       = 1;

        $this->render('inventaris/tanah/edit_inventaris', $data);
    }

    public function edit_mutasi($id): void
    {
        isCan('u');
        $data['main'] = $this->inventaris_tanah_model->edit_mutasi($id);
        $data['tip']  = 2;

        $this->render('inventaris/tanah/edit_mutasi', $data);
    }

    public function form(): void
    {
        isCan('u');
        $data['tip']       = 1;
        $data['get_kode']  = $this->header['desa'];
        $data['aset']      = $this->aset_model->list_aset(2);
        $data['count_reg'] = $this->inventaris_tanah_model->count_reg();

        $this->render('inventaris/tanah/form_tambah', $data);
    }

    public function form_mutasi($id): void
    {
        isCan('u');
        $data['main'] = $this->inventaris_tanah_model->view($id);
        $data['tip']  = 2;

        $this->render('inventaris/tanah/form_mutasi', $data);
    }

    public function mutasi(): void
    {
        $data['main'] = $this->inventaris_tanah_model->list_mutasi_inventaris();
        $data['tip']  = 2;

        $this->render('inventaris/tanah/table_mutasi', $data);
    }

    public function cetak($tahun, $penandatangan): void
    {
        $data['header'] = $this->header['desa'];
        $data['total']  = $this->inventaris_tanah_model->sum_print($tahun);
        $data['print']  = $this->inventaris_tanah_model->cetak($tahun);
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);

        $this->load->view('inventaris/tanah/inventaris_print', $data);
    }

    public function download($tahun, $penandatangan): void
    {
        $data['header'] = $this->header['desa'];
        $data['total']  = $this->inventaris_tanah_model->sum_print($tahun);
        $data['print']  = $this->inventaris_tanah_model->cetak($tahun);
        $data['pamong'] = $this->pamong_model->get_data($penandatangan);

        $this->load->view('inventaris/tanah/inventaris_excel', $data);
    }
}
