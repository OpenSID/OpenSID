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

use App\Models\LampiranSurat;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaturan_lampiran extends Admin_Controller
{
    public $modul_ini           = 'layanan-surat';
    public $sub_modul_ini       = 'lampiran';
    public $kategori_pengaturan = 'pengaturan-surat';
    public $aliasController     = 'lampiran';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $margin           = setting('lampiran_margin');
        $kotak            = setting('lampiran_kotak');
        $data['margins']  = json_decode($margin, null) ?? LampiranSurat::MARGINS;
        $data['formAksi'] = ci_route('pengaturan_lampiran.edit');
        $data['kotak']    = json_decode($kotak, 1) ?? LampiranSurat::KOTAK;
        // log_message('error', json_encode($data['kotak'], JSON_THROW_ON_ERROR));

        return view('admin.pengaturan_surat.lampiran.pengaturan.index', $data);
    }

    public function edit(): void
    {
        isCan('u');
        $this->load->model('setting_model');
        $data = $this->validate($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
        }
        (new SettingAplikasi())->flushQueryCache();
        redirect_with('success', 'Berhasil Ubah Data');
    }

    private function validate($request): array
    {
        return [
            'lampiran_margin' => json_encode($request['lampiran_margin'], JSON_THROW_ON_ERROR),
            'lampiran_kotak'  => json_encode($request['lampiran_kotak'], JSON_THROW_ON_ERROR),
        ];
    }
}
