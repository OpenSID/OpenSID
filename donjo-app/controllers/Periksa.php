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

use App\Models\Config;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Periksa extends CI_Controller
{
    public $header;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->db_error['code'] === 1049) {
            redirect('koneksi-database');
        }

        $this->load->model(['periksa_model', 'user_model']);
        $this->header = Config::first();
    }

    public function index()
    {
        if ($this->session->periksa_data != 1) {
            redirect('periksa/login');
        }

        if ($this->session->message_query || $this->session->message_exception) {
            log_message('error', $this->session->message_query);
            log_message('error', $this->session->message_exception);
        }

        // return json($this->periksa_model->periksa['tabel_invalid_date']['log_penduduk']);

        return view('periksa.index', array_merge($this->periksa_model->periksa, ['header' => $this->header]));
    }

    public function perbaiki()
    {
        if ($this->session->periksa_data != 1) {
            redirect('periksa/login');
        }
        $this->periksa_model->perbaiki();
        $this->session->unset_userdata(['db_error', 'message', 'message_query', 'heading', 'message_exception']);

        redirect('/');
    }

    // Login khusus untuk periksa
    public function login()
    {
        $this->session->siteman_wait = 0;
        $this->user_model->login();
        $data = [
            'header'      => $this->header,
            'form_action' => site_url('periksa/auth'),
        ];
        $this->setting->sebutan_desa      = $this->getSetting('sebutan_desa');
        $this->setting->sebutan_kabupaten = $this->getSetting('sebutan_kabupaten');
        $this->load->view('siteman', $data);
    }

    private function getSetting($key)
    {
        return SettingAplikasi::where('key', $key)->pluck('value');
    }

    // Login khusus untuk periksa
    public function auth()
    {
        $method       = $this->input->method(true);
        $allow_method = ['POST'];
        if (! in_array($method, $allow_method)) {
            redirect('periksa/login');
        }
        $this->user_model->siteman();

        if ($this->session->siteman != 1) {
            // Gagal otentifikasi atau bukan admin
            redirect('periksa');
        }
        if ($this->session->grup != 1) {
            // Bukan admin
            $this->user_model->logout();
            redirect('periksa');
        }

        // Bedakan dengan status login biasa supaya dipaksa login lagi setelah selesai perbaiki data
        $this->session->periksa_data = 1;
        redirect('periksa');
    }
}
