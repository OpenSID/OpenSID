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

class pengaduan_admin_backup extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 334;
        $this->load->model('pengaduan_model');
    }

    public function index()
    {
        $cari = $this->input->get('cari');

        $data = [
            'pengaduan'     => $this->pengaduan_model->list_data($cari),
            'form_action'   => site_url('pengaduan_admin/kirim'),
            'search_action' => site_url('pengaduan_admin'),
            'cari'          => $cari,
            'allstatus'     => $this->pengaduan_model->get_data()->count_all_results(),
            'status1'       => $this->pengaduan_model->get_data('1')->count_all_results(),
            'status2'       => $this->pengaduan_model->get_data('2')->count_all_results(),
            'status3'       => $this->pengaduan_model->get_data('3')->count_all_results(),

            'm_allstatus' => $this->pengaduan_model->get_data_month()->count_all_results(),
            'm_status1'   => $this->pengaduan_model->get_data_month('1')->count_all_results(),
            'm_status2'   => $this->pengaduan_model->get_data_month('2')->count_all_results(),
            'm_status3'   => $this->pengaduan_model->get_data_month('3')->count_all_results(),
            'main'        => null,
        ];

        $this->render("{$this->controller}/index", $data);
    }

    public function kirim()
    {
        $this->redirect_hak_akses('u');
        $this->pengaduan_model->m_insert();

        redirect($this->controller);
    }
}
