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

defined('BASEPATH') || exit('No direct script access allowed');

class Masuk extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        mandiri_timeout();
        $this->session->login_ektp        = false;
        $this->session->daftar            = false;
        $this->session->daftar_verifikasi = false;
        $this->load->model(['mandiri_model', 'theme_model']);
        if ($this->setting->layanan_mandiri == 0) {
            show_404();
        }
    }

    public function index(): void
    {
        $mac_address = $this->input->get('mac_address', true);
        $token       = $this->input->get('token_layanan', true);
        if (($mac_address && $token == $this->setting->layanan_opendesa_token) || $this->session->mandiri == 1) {
            $this->session->mac_address = $mac_address;
            redirect('layanan-mandiri/beranda');
        }

        //Initialize Session ------------
        $this->session->unset_userdata('balik_ke');
        if (! isset($this->session->mandiri)) {
            // Belum ada session variable
            $this->session->mandiri           = 0;
            $this->session->mandiri_try       = 4;
            $this->session->mandiri_wait      = 0;
            $this->session->login_ektp        = false;
            $this->session->daftar            = false;
            $this->session->daftar_verifikasi = false;
        }

        $data = [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'cek_anjungan'        => $this->cek_anjungan,
            'form_action'         => site_url('layanan-mandiri/cek'),
        ];

        $this->load->view(MANDIRI . '/masuk', $data);
    }

    public function cek(): void
    {
        $this->mandiri_model->siteman();
        redirect('layanan-mandiri/beranda');
    }

    public function lupa_pin(): void
    {
        $data = [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'cek_anjungan'        => $this->anjungan_model->cek_anjungan(),
            'form_action'         => site_url('layanan-mandiri/cek-pin'),
        ];

        $this->load->view(MANDIRI . '/lupa_pin', $data);
    }

    public function cek_pin(): void
    {
        $nik = bilangan($this->input->post('nik'));
        $this->mandiri_model->cek_verifikasi($nik);

        redirect('layanan-mandiri/lupa-pin');
    }
}
