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

class Anjungan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('anjungan_model');

        $this->modul_ini     = 14;
        $this->sub_modul_ini = 312;
    }

    public function index()
    {
        $data['main'] = $this->anjungan_model->list_data();
        $this->render('anjungan/table', $data);
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);

        if ($id) {
            $data['anjungan'] = $this->anjungan_model->get_anjungan($id);
            if (empty($data['anjungan'])) {
                status_sukses(false, false, '--> Data itu tidak ditemukan');
                redirect('anjungan');
            }
            $data['form_action'] = site_url("anjungan/update/{$id}");
        } else {
            $data['suplemen']    = null;
            $data['form_action'] = site_url('anjungan/insert');
        }
        $this->render('anjungan/form', $data);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->anjungan_model->insert();
        redirect('anjungan');
    }

    public function update($id)
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->anjungan_model->update($id);
        redirect('anjungan');
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h', $_SERVER['HTTP_REFERER']);
        $this->anjungan_model->delete($id);
        redirect('anjungan');
    }

    public function lock($id = 0, $val = 1)
    {
        $this->redirect_hak_akses('u', $_SERVER['HTTP_REFERER']);
        $this->anjungan_model->lock($id, $val);
        redirect('anjungan');
    }
}
