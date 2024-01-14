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

defined('BASEPATH') || exit('No direct script access allowed');

class Setting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['theme_model']);
        $this->modul_ini     = 'pengaturan';
        $this->sub_modul_ini = 'aplikasi';
    }

    public function index()
    {
        $data = [
            'judul'               => 'Pengaturan Aplikasi',
            'pengaturan_kategori' => ['sistem', 'peta', 'web_theme', 'readonly', 'web', 'mobile'],
            'atur_latar'          => true,
            'latar_website'       => [$this->setting->latar_website, 'latar_website'],
            'latar_siteman'       => [$this->setting->latar_login, 'latar_login'],
        ];

        return view('admin.pengaturan.index', $data);
    }

    public function ambil_foto()
    {
        $foto       = $this->input->get('foto');
        $pengaturan = $this->input->get('pengaturan');

        if ($pengaturan == 'latar_website') {
            $default     = LOKASI_ASSET_FRONT_IMAGES;
            $new_setting = $this->theme_model->lokasi_latar_website();
        }

        if ($pengaturan == 'latar_login' || $pengaturan == 'latar_login_mandiri') {
            $default     = LOKASI_ASSET_IMAGES;
            $new_setting = LATAR_LOGIN;
        }

        ambilBerkas($foto, $this->controller, null, $foto == $pengaturan . '.jpg' ? $default : $new_setting, $tampil = true);
    }

    // Untuk view lama
    public function update()
    {
        $this->redirect_hak_akses_url('u');
        $hasil = $this->setting_model->update_setting($this->input->post());
        status_sukses($hasil, false, 'Berhasil Ubah Data');

        redirect($_SERVER['HTTP_REFERER']);
    }

    // Untuk view menggunakan blade
    public function new_update()
    {
        $this->redirect_hak_akses_url('u');
        if ($this->setting_model->update_setting($this->input->post())) {
            set_session('success', 'Berhasil Ubah Data');
        } else {
            set_session('error', 'Gagal Ubah Data. ' . session('flash_error_msg'));
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function aktifkan_tracking()
    {
        if ($this->input->post('notifikasi') != 1) {
            return;
        } // Hanya bila dipanggil dari form pengumuman
        $this->setting_model->aktifkan_tracking();
        $this->db->where('config_id', identitas('id'))->where('kode', 'tracking_off')->update('notifikasi', ['aktif' => 0]);
    }

    // Pengaturan web
    public function web()
    {
        $this->modul_ini     = 'admin-web';
        $this->sub_modul_ini = 'pengaturan-web';

        $data = [
            'judul'               => 'Pengaturan Halaman Web',
            'pengaturan_kategori' => ['conf_web'],
            'aksi_controller'     => 'setting/web',
        ];

        return view('admin.pengaturan.index', $data);
    }

    // Pengaturan mandiri
    public function mandiri()
    {
        $this->modul_ini     = 'layanan-mandiri';
        $this->sub_modul_ini = 'pengaturan-layanan-mandiri';

        $data = [
            'judul'               => 'Pengaturan Layanan Mandiri',
            'pengaturan_kategori' => ['setting_mandiri'],
            'atur_latar'          => true,
            'aksi_controller'     => 'setting/mandiri',
            'latar_mandiri'       => [$this->setting->latar_login_mandiri, 'latar_login_mandiri'],
        ];

        return view('admin.pengaturan.index', $data);
    }

    // Pengaturan analisis
    public function analisis()
    {
        $this->modul_ini     = 'analisis';
        $this->sub_modul_ini = 'pengaturan-analisis';

        $data = [
            'judul'               => 'Pengaturan Analisis',
            'pengaturan_kategori' => ['setting_analisis'],
            'aksi_controller'     => 'setting/analisis',
        ];

        return view('admin.pengaturan.index', $data);
    }

    public function qrcode($aksi = '')
    {
        $this->modul_ini     = 'pengaturan';
        $this->sub_modul_ini = 'qr-code';

        $data['qrcode']        = ['changeqr' => '1', 'sizeqr' => '6', 'foreqr' => '#000000']; // Default
        $data['list_changeqr'] = ['Otomatis (Logo Desa)', 'Manual'];
        $data['list_sizeqr']   = ['25', '50', '75', '100', '125', '150', '175', '200', '225', '250'];

        $this->render('setting/setting_qr', $data);
    }

    public function qrcode_generate()
    {
        $this->redirect_hak_akses_url('u');
        $post     = $this->input->post();
        $changeqr = $post['changeqr'];

        // $logoqr = yg akan ditampilkan, url
        // $logoqr1 = yg akan disimpan, directory
        if ($changeqr == '1') {
            // Ambil absolute path, bukan url
            $logoqr1 = gambar_desa($this->header['desa']['logo'], false, true);
        } else {
            $logoqr = $post['logoqr'];
            // Ubah url (http) menjadi absolute path ke file di lokasi media
            $lokasi_media = preg_quote(LOKASI_MEDIA, '/');
            $file_logoqr  = preg_split('/' . $lokasi_media . '/', $logoqr)[1];
            $logoqr1      = FCPATH . LOKASI_MEDIA . $file_logoqr;
        }

        $qrCode = [
            'isiqr'    => $post['isiqr'], // Isi / arti dr qrcode
            'changeqr' => $changeqr, // Pilihan jenis sisipkan logo
            'logoqr'   => $logoqr1,
            'sizeqr'   => bilangan($post['sizeqr']), // Ukuran qrcode
            'foreqr'   => $post['foreqr'],
        ];

        json(qrcode_generate($qrCode, true));
    }
}
