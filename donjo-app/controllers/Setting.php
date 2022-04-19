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

class Setting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['theme_model']);
        $this->modul_ini     = 11;
        $this->sub_modul_ini = 43;
    }

    public function index()
    {
        $data = [
            'judul'         => 'Pengaturan Aplikasi',
            'kategori'      => [null, '', 'sistem', 'web_theme', 'readonly', 'web', 'mobile'],
            'atur_latar'    => true,
            'latar_website' => $this->theme_model->latar_website(),
            'latar_login'   => $this->theme_model->latar_login(),
            'list_tema'     => $this->theme_model->list_all(),
        ];
        $this->setting_model->load_options();

        $this->render('setting/setting_form', $data);
    }

    public function update()
    {
        $this->redirect_hak_akses_url('u');
        $this->setting_model->update_setting($this->input->post());
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function aktifkan_tracking()
    {
        if ($this->input->post('notifikasi') != 1) {
            return;
        } // Hanya bila dipanggil dari form pengumuman
        $this->setting_model->aktifkan_tracking();
        $this->db->where('kode', 'tracking_off')->update('notifikasi', ['aktif' => 0]);
    }

    // Pengaturan web
    public function web()
    {
        $this->modul_ini     = 13;
        $this->sub_modul_ini = 211;

        $data = [
            'judul'           => 'Pengaturan Halaman Web',
            'kategori'        => ['conf_web'],
            'aksi_controller' => 'setting/web',
        ];

        $this->render('setting/setting_form', $data);
    }

    // Pengaturan mandiri
    public function mandiri()
    {
        $this->modul_ini     = 14;
        $this->sub_modul_ini = 314;
        $this->load->model('first_gallery_m');

        $data = [
            'judul'               => 'Pengaturan Layanan Mandiri',
            'kategori'            => ['setting_mandiri'],
            'atur_latar'          => true,
            'latar_login_mandiri' => $this->theme_model->latar_login_mandiri(),
            'daftar_album'        => $this->first_gallery_m->gallery_show(),
            'aksi_controller'     => 'setting/mandiri',
        ];
        $this->setting_model->load_options();

        $this->render('setting/setting_form', $data);
    }

    // Pengaturan analisis
    public function analisis()
    {
        $this->modul_ini     = 5;
        $this->sub_modul_ini = 111;

        $data = [
            'judul'           => 'Pengaturan Analisis',
            'kategori'        => ['setting_analisis'],
            'aksi_controller' => 'setting/analisis',
        ];

        $this->render('setting/setting_form', $data);
    }

    public function qrcode($aksi = '', $file = '')
    {
        switch ($aksi) {
            case 'clear':
                $this->session->unset_userdata('qrcode');
                redirect('setting/qrcode');

                // no break
            case 'hapus':
                $this->redirect_hak_akses_url('u');
                unlink(LOKASI_MEDIA . '' . $file . '.png');
                redirect('setting/qrcode/clear');

                // no break
            case 'unduh':
                $this->load->helper('download');
                force_download(LOKASI_MEDIA . $file . '.png', null);
                redirect('setting/qrcode');

                // no break
            default:
                $this->modul_ini     = 11;
                $this->sub_modul_ini = 212;

                $data['qrcode']        = $this->session->qrcode ?: $qrcode        = ['changeqr' => '1', 'sizeqr' => '6', 'foreqr' => '#000000'];
                $data['list_changeqr'] = ['Otomatis (Logo Desa)', 'Manual'];
                $data['list_sizeqr']   = ['25', '50', '75', '100', '125', '150', '175', '200', '225', '250'];

                $this->render('setting/setting_qr', $data);

                break;
        }
    }

    public function qrcode_generate()
    {
        $this->redirect_hak_akses_url('u');
        $pathqr   = LOKASI_MEDIA; // Lokasi default simpan file qrcode
        $post     = $this->input->post();
        $namaqr   = $post['namaqr']; // Nama file gambar asli
        $namaqr1  = str_replace(' ', '_', nama_terbatas($namaqr)); // Nama file gambar yg akan disimpan
        $changeqr = $post['changeqr'];

        // $logoqr = yg akan ditampilkan, url
        // $logoqr1 = yg akan disimpan, directory
        if ($changeqr == '1') {
            $desa = $this->header['desa'];
            // Ambil absolute path, bukan url
            $logoqr1 = gambar_desa($desa['logo'], false, $file = true);
        } else {
            $logoqr = $post['logoqr'];
            // Ubah url (http) menjadi absolute path ke file di lokasi media
            $lokasi_media = preg_quote(LOKASI_MEDIA, '/');
            $file_logoqr  = preg_split('/' . $lokasi_media . '/', $logoqr)[1];
            $logoqr1      = APPPATH . '../' . LOKASI_MEDIA . $file_logoqr;
        }

        $qrcode = [
            'namaqr'   => $namaqr, // Nama file
            'namaqr1'  => $namaqr1, // Nama file untuk download
            'isiqr'    => $post['isiqr'], // Isi / arti dr qrcode
            'changeqr' => $changeqr, // Pilihan jenis sisipkan logo
            'logoqr'   => $logoqr,
            'sizeqr'   => bilangan($post['sizeqr']), // Ukuran qrcode
            'foreqr'   => $post['foreqr'],
            'pathqr'   => base_url(LOKASI_MEDIA . '' . $namaqr1 . '.png'), // Tampilkan gambar qrcode
        ];

        $this->session->qrcode = $qrcode;

        if ($post) {
            $this->session->success = 1;
            $data                   = qrcode_generate($pathqr, $namaqr1, $qrcode['isiqr'], $logoqr1, $qrcode['sizeqr'], $qrcode['foreqr']);
            echo json_encode($data);
        } else {
            $this->session->success = -1;
        }
    }
}
