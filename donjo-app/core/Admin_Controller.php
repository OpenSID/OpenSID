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

use App\Models\Config;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Pesan;
use App\Models\UserGrup;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{
    public $CI;
    public $grup;
    public $modul_ini;
    public $sub_modul_ini;
    public $akses_modul;
    public $header;
    public $controller;
    public $aliasController;

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->CI = &get_instance();
        $this->controller = strtolower($this->router->fetch_class());
        $this->cek_identitas_desa();

        // paksa untuk logout jika melakukan ubah password
        if (! $this->session->change_password) {
            return;
        }
        if ($this->controller === 'pengguna') {
            return;
        }

        redirect('pengguna');
    }

    /*
     * Urutan pengecakan :
     *
     * 1. Config desa sudah diisi
     * 2. Password standard (sid304)
     */
    private function cek_identitas_desa(): void
    {
        $kode_desa = empty(Config::appKey()->first()->kode_desa);

        if ($kode_desa && $this->controller != 'identitas_desa') {
            set_session('error', 'Identitas ' . ucwords(setting('sebutan_desa')) . ' masih kosong, silakan isi terlebih dahulu');

            redirect('identitas_desa');
        }

        $force    = $this->session->force_change_password;

        if ($force && ! $kode_desa && $this->controller != 'pengguna') {
            redirect('pengguna#sandi');
        }

        $this->load->model(['user_model', 'notif_model', 'pelanggan_model', 'referensi_model']);

        // Kalau sehabis periksa data, paksa harus login lagi
        if ($this->session->periksa_data == 1) {
            $this->user_model->logout();
        }

        $this->grup = $this->user_model->sesi_grup($this->session->sesi);
        $this->load->model('modul_model');
        $aliasController = $this->aliasController ?? $this->controller;
        if (! $this->modul_model->modul_aktif($aliasController)) {
            session_error('Fitur ini tidak aktif');
            redirect($_SERVER['HTTP_REFERER']);
        }

        if (! $this->user_model->hak_akses($this->grup, $aliasController, 'b')) {
            if (empty($this->grup)) {
                $_SESSION['request_uri'] = $_SERVER['REQUEST_URI'];
                redirect('siteman');
            } else {
                // TODO:: cek masalah ini kenapa selalu muncul error di untuk can('u', 'pelanggan)
                // session_error('Anda tidak mempunyai akses pada fitur itu');
                unset($_SESSION['request_uri']);
                redirect('main');
            }
        }
        $cek_kotak_pesan                        = $this->db->table_exists('pesan') && $this->db->table_exists('pesan_detail');
        $this->header['desa']                   = collect(identitas())->toArray();
        $this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
        $this->header['notif_inbox']            = $this->notif_model->inbox_baru();
        $this->header['notif_komentar']         = $this->notif_model->komentar_baru();
        $this->header['notif_langganan']        = $this->pelanggan_model->status_langganan();
        $this->header['notif_pesan_opendk']     = $cek_kotak_pesan ? Pesan::where('sudah_dibaca', '=', 0)->where('diarsipkan', '=', 0)->count() : 0;
        $this->header['notif_pengumuman']       = ($kode_desa || $force) ? null : $this->cek_pengumuman();
        $isAdmin                                = $this->session->isAdmin->pamong;
        $this->header['notif_permohonan']       = 0;
        $this->header['notif_permohonan']       = LogSurat::whereNull('deleted_at')->when($isAdmin->jabatan_id == kades()->id, static fn ($q) => $q->when(setting('tte') == 1, static fn ($tte) => $tte->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0))->when(setting('tte') == 0, static fn ($tte) => $tte->where('verifikasi_kades', '=', 0)))
            ->when($isAdmin->jabatan_id == sekdes()->id, static fn ($q) => $q->where('verifikasi_sekdes', '=', '0'))
            ->when($isAdmin == null || ! in_array($isAdmin->jabatan_id, [kades()->id, sekdes()->id]), static fn ($q) => $q->where('verifikasi_operator', '=', '0')->orWhere('verifikasi_operator', '=', '-1'))
            ->count();

        if (! config_item('demo_mode')) {
            // cek langganan premium
            $info_langganan = $this->cache->file->get_metadata('status_langganan');

            if ((strtotime('+30 day', $info_langganan['mtime']) < strtotime('now')) || ($this->cache->file->get_metadata('status_langganan') == false && $this->setting->layanan_opendesa_token != null)) {
                $this->header['perbaharui_langganan'] = true;
            }
        }
    }

    private function cek_pengumuman()
    {
        if (config_item('demo_mode') || ENVIRONMENT === 'development') {
            return null;
        }

        // Hanya untuk user administrator
        if ($this->grup == $this->user_model->id_grup(UserGrup::ADMINISTRATOR)) {
            $notifikasi = $this->notif_model->get_semua_notif();

            foreach ($notifikasi as $notif) {
                $pengumuman = $this->notif_model->notifikasi($notif);
                if ($notif['jenis'] == 'persetujuan') {
                    break;
                }
            }
        }

        return $pengumuman;
    }

    // TODO:: hapus method ini jika sudah tidak digunakan
    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    protected function redirect_hak_akses_url($akses, $redirect = '', $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }
        if (! $this->user_model->hak_akses_url($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');
            if (empty($this->grup)) {
                redirect('siteman');
            }
            empty($redirect) ? redirect($_SERVER['HTTP_REFERER']) : redirect($redirect);
        }
    }

    // TODO:: hapus method ini jika sudah tidak digunakan
    protected function redirect_hak_akses($akses, $redirect = '', $controller = '', $admin_only = false)
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        if (($admin_only && $this->grup != $this->user_model->id_grup(UserGrup::ADMINISTRATOR)) || ! $this->user_model->hak_akses($this->grup, $controller, $akses)) {
            session_error('Anda tidak mempunyai akses pada fitur ini');

            if (empty($this->grup)) {
                redirect('siteman');
            }
            empty($redirect) ? redirect($_SERVER['HTTP_REFERER']) : redirect($redirect);
        }
    }

    // TODO:: hapus method ini jika sudah tidak digunakan
    // Untuk kasus di mana method controller berbeda hak_akses. Misalnya 'setting_qrcode' readonly, tetapi 'setting/analisis' boleh ubah
    public function cek_hak_akses_url($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses_url($this->grup, $controller, $akses);
    }

    // TODO:: hapus method ini jika sudah tidak digunakan
    public function cek_hak_akses($akses, $controller = '')
    {
        if (empty($controller)) {
            $controller = $this->controller;
        }

        return $this->user_model->hak_akses($this->grup, $controller, $akses);
    }

    // TODO:: hapus method ini jika sudah tidak digunakan
    public function redirect_tidak_valid($valid): void
    {
        if ($valid) {
            return;
        }

        session_error('Aksi ini tidak diperbolehkan');
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function render($view, ?array $data = null): void
    {
        $this->load->view('header', $this->header);
        $this->load->view('nav');
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    public function modal_penandatangan()
    {
        $this->load->model('pamong_model');

        return [
            'pamong'         => Pamong::penandaTangan()->get(),
            'pamong_ttd'     => Pamong::sekretarisDesa()->first(),
            'pamong_ketahui' => Pamong::kepalaDesa()->first(),
        ];
    }

    public function navigasi_peta()
    {
        return collect([
            'desa'      => identitas(),
            'wil_atas'  => identitas(),
            'dusun_gis' => Wilayah::dusun()->get(),
            'rw_gis'    => Wilayah::rw()->get(),
            'rt_gis'    => Wilayah::rt()->get(),
        ])->toArray();
    }

    protected function set_hak_akses_rfm()
    {
        // reset dulu session yang berkaitan hak akses ubah dan hapus
        $this->session->hapus_gambar_rfm       = false;
        $this->session->ubah_tambah_gambar_rfm = false;

        if (can('h')) {
            $this->session->hapus_gambar_rfm = true;
        }
        if (can('u')) {
            $this->session->ubah_tambah_gambar_rfm = true;
        }
    }
}
