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

use App\Enums\JawabanKepuasanEnum;
use App\Models\Pendapat as ModelsPendapat;

defined('BASEPATH') || exit('No direct script access allowed');

class Pendapat extends Admin_Controller
{
    public $modul_ini     = 'layanan-mandiri';
    public $sub_modul_ini = 'pendapat';
    protected ModelsPendapat $pendapat;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->pendapat = new ModelsPendapat();
    }

    public function index()
    {
        $tipe                  = session('tipe');
        $data['list_pendapat'] = JawabanKepuasanEnum::all();

        foreach (array_keys($data['list_pendapat']) as $key) {
            $data["pilihan_{$key}"] = $this->pendapat->pendapat($tipe, $key)['total'];
        }
        $data['main']   = $this->pendapat->pendapat($tipe);
        $data['detail'] = $this->pendapat->with('penduduk')->whereRaw($this->pendapat->kondisi($tipe)['where'])->get()->toArray();

        return view('admin.pendapat.index', $data);
    }

    public function detail(int $tipe = 1): void
    {
        set_session('tipe', $tipe);

        redirect('pendapat');
    }
}
