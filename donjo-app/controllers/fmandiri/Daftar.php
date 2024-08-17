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

class Daftar extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        mandiri_timeout();
        $this->session->daftar = true;
        $this->load->model(['mandiri_model', 'theme_model']);
        if (! $this->setting->tampilkan_pendaftaran) {
            redirect('layanan-mandiri/masuk');
        }
    }

    public function index(): void
    {
        if ($this->session->mandiri == 1) {
            redirect('layanan-mandiri/beranda');
        }

        //Initialize Session ------------
        $this->session->unset_userdata('balik_ke');
        if (! isset($this->session->mandiri)) {
            // Belum ada session variable
            $this->session->mandiri      = 0;
            $this->session->mandiri_try  = 4;
            $this->session->mandiri_wait = 0;
            $this->session->daftar       = true;
        }

        $data = [
            'header'              => $this->header,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'form_action'         => site_url('layanan-mandiri/proses-daftar'),
        ];

        $this->load->view(MANDIRI . '/masuk', $data);
    }

    //Prosess Pendaftaran
    public function proses_daftar(): void
    {
        $post              = $this->input->post();
        $data['nama']      = $post['daftar_nama'];
        $data['nik']       = $post['daftar_nik'];
        $data['kk']        = $post['daftar_kk'];
        $data['tgl_lahir'] = date('Y-m-d', strtotime($post['daftar_tgl_lahir']));
        $data['pin1']      = $post['daftar_pin1'];
        $data['pin2']      = $post['daftar_pin2'];

        if ($data['pin1'] == $data['pin2']) {
            $this->mandiri_model->pendaftaran_mandiri($data);
        } else {
            $respon = [
                'status' => -1,
                'pesan'  => 'Mohon Maaf, PIN yang dimasukan tidak sama',
            ];
            $this->session->set_flashdata('info_pendaftaran', $respon);
        }
        redirect('layanan-mandiri/daftar');
    }
}
