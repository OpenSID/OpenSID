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

use App\Models\Galery;
use App\Repositories\SettingAplikasiRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Slider extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'slider';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Jika offline_mode dalam level yang menyembunyikan website,
        // tidak perlu menampilkan halaman website
        if (setting('offline_mode') >= 2) {
            redirect('beranda');

            exit;
        }
    }

    public function index(): void
    {
        view('admin.web.slider.index');
    }

    public function update(): void
    {
        isCan('u');

        if ($this->request['pilihan_sumber'] == 3) {
            if (Galery::daftar()->doesntExist()) {
                redirect_with('error', 'Tidak ada slider aktif pada album, silahkan aktifkan satu album dan album foto tidak boleh kosong di <a target="_blank" href="' . route('gallery.index') . '"><b>Galeri</b></a>');
            }
        }

        $settings = new SettingAplikasiRepository();
        $settings->updateWithKey('sumber_gambar_slider', $this->request['pilihan_sumber']);
        $settings->updateWithKey('jumlah_gambar_slider', $this->request['jumlah_gambar_slider']);

        redirect_with('success', 'Berhasil Ubah Data');
    }
}
