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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

require_once FCPATH . 'Modules/Anjungan/Http/Controllers/BackEnd/AnjunganBaseController.php';

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\SettingAplikasi;
use Spatie\Activitylog\Facades\LogBatch;

class AnjunganPengaturanController extends AnjunganBaseController
{
    public $moduleName      = 'Anjungan';
    public $modul_ini       = 'anjungan';
    public $sub_modul_ini   = 'pengaturan-anjungan';
    public $aliasController = 'anjungan_pengaturan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    protected static function validate(array $request = []): array
    {
        return [
            'sebutan_anjungan_mandiri' => strip_tags($request['sebutan_anjungan_mandiri']),
            'anjungan_artikel'         => json_encode($request['artikel'], JSON_THROW_ON_ERROR),
            'anjungan_teks_berjalan'   => strip_tags($request['teks_berjalan']),
            'anjungan_profil'          => bilangan($request['tampilan_profil']),
            'anjungan_video'           => strip_tags($request['video']),
            'anjungan_youtube'         => strip_tags($request['youtube']),
            'anjungan_slide'           => bilangan($request['slide']),
            'tampilan_anjungan'        => bilangan($request['screensaver']),
            'tampilan_anjungan_waktu'  => bilangan($request['screensaver_waktu']),
            'tampilan_anjungan_slider' => bilangan($request['screensaver_slide']),
            'tampilan_anjungan_video'  => strip_tags($request['screensaver_video']),
            'warna_anjungan'           => strip_tags($request['warna_anjungan']),
            'pencahayaan_anjungan'     => strip_tags($request['pencahayaan_anjungan']),
        ];
    }

    public function index()
    {
        $data['form_action']      = ci_route('anjungan_pengaturan.update');
        $data['daftar_kategori']  = Kategori::get();
        $data['pengaturan']       = SettingAplikasi::whereKategori('Anjungan')->pluck('value', 'key')->toArray();
        $data['anjungan_artikel'] = json_decode($data['pengaturan']['anjungan_artikel'], null);
        $data['slides']           = Galery::where('parrent', 0)->where('enabled', 1)->get();

        return view('anjungan::backend.pengaturan.index', $data);
    }

    public function update(): void
    {
        isCan('u');

        $data = static::validate($this->request);

        LogBatch::startBatch();

        foreach ($data as $key => $value) {

            if ($key === 'anjungan_youtube') {
                // kalau yang dimasukkan berupa URL
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    $value = basename(parse_url($value, PHP_URL_PATH));
                }

                // validasi hanya ID alfanumerik 11 karakter (pola YouTube ID)
                if (! preg_match('/^[a-zA-Z0-9_-]{11}$/', $value)) {
                    redirect_with('error', 'ID YouTube tidak valid');
                }
            }

            $setting = SettingAplikasi::where('key', '=', $key)->first();

            $setting->value = $value;
            $setting->save();
        }

        LogBatch::endBatch();

        (new SettingAplikasi())->flushQueryCache();
        redirect_with('success', 'Berhasil Ubah Data');
    }
}
