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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Config;
use App\Models\Komentar;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Pesan;
use App\Models\UserGrup;
use App\Models\Wilayah;
use Illuminate\Support\Facades\View;
use Modules\Pelanggan\Services\PelangganService;

defined('BASEPATH') || exit('No direct script access allowed');

class Admin_Controller extends MY_Controller
{
    public $CI;
    public $grup;
    public $modul_ini;
    public $sub_modul_ini;
    public $header;
    public $controller;
    public $aliasController;

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $this->CI         = &get_instance();
        $this->controller = strtolower($this->router->fetch_class());

        if (! auth('admin')->check()) {
            // untuk kembali ke halaman sebelumnya setelah login.
            $this->session->intended = current_url();

            redirect('siteman');
        }

        $this->cek_identitas_desa();

        View::share([
            'controller'   => $this->controller ?? $this->aliasController,
            'list_setting' => app('ci')->list_setting,
            'modul'        => $this->header['modul'],
            'modul_ini'    => $this->modul_ini,
            'notif'        => [
                'surat'           => $this->header['notif_permohonan_surat'],
                'opendkpesan'     => $this->header['notif_pesan_opendk'],
                'inbox'           => $this->header['notif_inbox'],
                'komentar'        => $this->header['notif_komentar'],
                'langganan'       => $this->header['notif_langganan'],
                'pengumuman'      => $this->header['notif_pengumuman'],
                'permohonansurat' => $this->header['notif_permohonan'],
            ],
            'kategori_pengaturan'  => app('ci')->kategori_pengaturan,
            'sub_modul_ini'        => $this->sub_modul_ini,
            'akses_modul'          => $this->sub_modul_ini ?? $this->modul_ini,
            'perbaharui_langganan' => $this->header['perbaharui_langganan'] ?? null,
        ]);

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

        $force = $this->session->force_change_password;

        if ($force && ! $kode_desa && $this->controller != 'pengguna') {
            redirect('pengguna#sandi');
        }

        $this->load->model(['user_model', 'notif_model', 'referensi_model']);

        // Kalau sehabis periksa data, paksa harus login lagi
        if (auth('admin_periksa')->check()) {
            auth('admin')->logout();
            auth('admin_periksa')->logout();

            redirect('siteman');
        }

        $cek_kotak_pesan                        = $this->db->table_exists('pesan') && $this->db->table_exists('pesan_detail');
        $this->header['desa']                   = collect(identitas())->toArray();
        $this->header['notif_permohonan_surat'] = $this->notif_model->permohonan_surat_baru();
        $this->header['notif_inbox']            = $this->notif_model->inbox_baru();
        $this->header['notif_komentar']         = Komentar::unread()->whereNull('parent_id')->count();
        $this->header['notif_langganan']        = PelangganService::statusLangganan();
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

            if (empty($info_langganan)
                || (strtotime('+30 day', $info_langganan['mtime']) < time())
                || ($info_langganan == false && setting('layanan_opendesa_token') != null)) {
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
        $this->grup = $this->user_model->sesi_grup($this->session->sesi);
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

    public function render($view, ?array $data = null): void
    {
        $this->load->view('header', $this->header);
        $this->load->view('nav');
        $this->load->view($view, $data);
        $this->load->view('footer');
    }

    public function modal_penandatangan()
    {
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
