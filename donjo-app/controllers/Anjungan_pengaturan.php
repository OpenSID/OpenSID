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

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Anjungan_pengaturan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 312;
        $this->sub_modul_ini = 349;

        if (! cek_anjungan()) {
            redirect('anjungan');
        }
    }

    public function index()
    {
        $data['form_action']      = route('anjungan_pengaturan.update');
        $data['daftar_kategori']  = Kategori::get();
        $data['pengaturan']       = SettingAplikasi::whereKategori('anjungan')->pluck('value', 'key')->toArray();
        $data['anjungan_artikel'] = json_decode($data['pengaturan']['anjungan_artikel']);
        $data['slides']           = Galery::where('parrent', 0)->where('enabled', 1)->get();

        return view('admin.anjungan_pengaturan.index', $data);
    }

    public function update()
    {
        $this->redirect_hak_akses('u');

        $data = $this->validated($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::whereKey($key)->update(['value' => $value]);
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected static function validated($request = [])
    {
        return [
            'anjungan_artikel'         => json_encode($request['artikel']),
            'anjungan_teks_berjalan'   => strip_tags($request['teks_berjalan']),
            'anjungan_profil'          => bilangan($request['tampilan_profil']),
            'anjungan_video'           => strip_tags($request['video']),
            'anjungan_youtube'         => strip_tags($request['youtube']),
            'anjungan_slide'           => bilangan($request['slide']),
            'tampilan_anjungan'        => bilangan($request['screensaver']),
            'tampilan_anjungan_waktu'  => bilangan($request['screensaver_waktu']),
            'tampilan_anjungan_slider' => bilangan($request['screensaver_slide']),
            'tampilan_anjungan_video'  => strip_tags($request['screensaver_video']),
            'anjungan_layar'           => bilangan($request['layar']),
        ];
    }
}
