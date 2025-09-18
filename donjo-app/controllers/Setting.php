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

use App\Models\Notifikasi;
use App\Models\SettingAplikasi;
use App\Repositories\SettingAplikasiRepository;
use App\Traits\Upload;

defined('BASEPATH') || exit('No direct script access allowed');

class Setting extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'aplikasi';

    public function __construct()
    {
        parent::__construct();
        isCan('b');

    }

    public function index()
    {
        $data = [
            'judul'               => 'Pengaturan Aplikasi',
            'pengaturan_kategori' => ['sistem', 'email', 'web_theme', 'readonly', 'web', 'mobile'],
            'atur_latar'          => true,
            'latar_website'       => [setting('latar_website'), 'latar_website'],
            'latar_siteman'       => [setting('latar_login'), 'latar_login'],
        ];

        return view('admin.pengaturan.index', $data);
    }

    public function ambil_foto(): void
    {
        $foto       = $this->input->get('foto');
        $pengaturan = $this->input->get('pengaturan');

        $paths = [
            'latar_website'       => [(new App\Models\Theme())->lokasiLatarWebsite(), LOKASI_ASSET_FRONT_IMAGES],
            'latar_login'         => [LATAR_LOGIN, LOKASI_ASSET_IMAGES],
            'latar_login_mandiri' => [LATAR_LOGIN, LOKASI_ASSET_IMAGES],
        ];

        if (isset($paths[$pengaturan])) {
            [$new_setting, $default] = $paths[$pengaturan];
            if (! file_exists(FCPATH . $new_setting . $foto)) {
                $foto = $pengaturan . '.jpg';
            }
        }

        ambilBerkas($foto, $this->controller, null, $foto == $pengaturan . '.jpg' ? $default : $new_setting, $tampil = true);
    }

    // Untuk view lama
    public function update(): void
    {
        isCan('u');
        $data = $this->input->post();
        $this->uploadImgSetting($data);
        $fixData                        = $this->input->post();
        $fixData['latar_login_mandiri'] = $data['latar_login_mandiri'];
        $hasil                          = (new SettingAplikasiRepository())->updateSetting($fixData);
        if ($hasil) {
            status_sukses($hasil, false, 'Berhasil Ubah Data');
            set_session('success', 'Berhasil Ubah Data');
        } else {
            status_sukses($hasil, true, 'Gagal Ubah Data');
            set_session('error', 'Gagal Ubah Data. ' . session('flash_error_msg'));
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function aktifkan_tracking(): void
    {
        if ($this->input->post('notifikasi') != 1) {
            return;
        } // Hanya bila dipanggil dari form pengumuman
        (SettingAplikasi::where('key', 'enable_track')->first())->update(['value' => 1]);
        Notifikasi::where('kode', 'tracking_off')->update(['aktif' => 0]);
    }
}
