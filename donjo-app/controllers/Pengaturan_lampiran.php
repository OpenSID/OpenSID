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

use App\Models\LampiranSurat;
use App\Models\SettingAplikasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaturan_lampiran extends Admin_Controller
{
    // digunakan untuk cek hak akses, mengikuti hak akses controller yang dialiaskan
    protected $aliasController = 'surat_master';

    public function __construct()
    {
        parent::__construct();
        $this->modul_ini          = 'layanan-surat';
        $this->sub_modul_ini      = 'pengaturan-surat';
        $this->header['kategori'] = 'pengaturan-surat';
    }

    public function index()
    {
        $margin           = setting('lampiran_margin');
        $data['margins']  = json_decode($margin) ?? LampiranSurat::MARGINS;
        $data['formAksi'] = route('pengaturan_lampiran.edit');

        return view('admin.pengaturan_surat.lampiran.pengaturan.index', $data);
    }

    public function edit()
    {
        $this->redirect_hak_akses('u');
        $this->load->model('setting_model');
        $data = $this->validate($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    private function validate($request)
    {
        return [
            'lampiran_margin' => json_encode($request['lampiran_margin']),
        ];
    }
}
