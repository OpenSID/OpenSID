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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Paud;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

require_once 'Stunting.php';
class Stunting_rekapitulasi extends Stunting
{
    public $modul_ini     = 'kesehatan';
    public $sub_modul_ini = 'stunting';

    public function __construct()
    {
        parent::__construct();
    }

    ////////////////////////////////////
    public function ibu_hamil($kuartal = null, $tahun = null, $id = null)
    {
        [$awalBulan, $akhirBulan, $tahun] = $this->setKuartaTahun($kuartal, $tahun);

        $data               = $this->widget();
        $data['navigasi']   = 'rekapitulasi-hasil-pemantauan-ibu-hamil';
        $data['id']         = $id;
        $data['posyandu']   = Posyandu::get();
        $data['awalBulan']  = $awalBulan;
        $data['akhirBulan'] = $akhirBulan;
        $data               = array_merge($data, $this->rekap->get_data_range_ibu_hamil($awalBulan, $akhirBulan, $tahun, $id));
        $tahunIni           = date('Y');
        if (! $data['dataTahun']->contains('tahun', $tahunIni)) {
            $tahunBaru        = new stdClass();
            $tahunBaru->tahun = $tahunIni;
            $data['dataTahun']->prepend($tahunBaru);
        }

        return view('admin.stunting.rekapitulasi-ibu-hamil', $data);
    }

    public function bulanan_anak($kuartal = null, $tahun = null, $id = null)
    {
        [$awalBulan, $akhirBulan, $tahun] = $this->setKuartaTahun($kuartal, $tahun);

        $data               = $this->widget();
        $data['navigasi']   = 'rekapitulasi-hasil-pemantauan-anak';
        $data['id']         = $id;
        $data['posyandu']   = Posyandu::get();
        $data['awalBulan']  = $awalBulan;
        $data['akhirBulan'] = $akhirBulan;
        $data               = array_merge($data, $this->rekap->get_data_range_bulanan_anak($awalBulan, $akhirBulan, $tahun, $id));
        $tahunIni           = date('Y');
        if (! $data['dataTahun']->contains('tahun', $tahunIni)) {
            $tahunBaru        = new stdClass();
            $tahunBaru->tahun = $tahunIni;
            $data['dataTahun']->prepend($tahunBaru);
        }

        return view('admin.stunting.rekapitulasi-bulanan-anak', $data);
    }

    public function bulanan_balita($kuartal = null, $tahun = null, $id = null)
    {
        [$awalBulan, $akhirBulan, $tahun] = $this->setKuartaTahun($kuartal, $tahun);

        $awalCreatedAt      = Carbon::createFromFormat('Y-m-d', $tahun . '-' . $awalBulan . '-01')->startOfDay();
        $akhirCreatedAt     = Carbon::createFromFormat('Y-m-d', $tahun . '-' . $akhirBulan . '-01')->endOfMonth();
        $data               = $this->widget();
        $data['navigasi']   = 'rekapitulasi-hasil-pemantauan-balita';
        $data['id']         = $id;
        $data['kuartal']    = $kuartal;
        $data['awalBulan']  = $awalBulan;
        $data['akhirBulan'] = $akhirBulan;
        $data['posyandu']   = Posyandu::get();
        $data['dataTahun']  = Paud::select(DB::raw('YEAR(created_at) tahun'))->groupBy('tahun')->get();
        $tahunIni           = date('Y');
        if (! $data['dataTahun']->contains('tahun', $tahunIni)) {
            $tahunBaru        = new stdClass();
            $tahunBaru->tahun = $tahunIni;
            $data['dataTahun']->prepend($tahunBaru);
        }
        $data['dataFilter'] = Paud::with(['kia', 'kia.anak'])
            ->when($id, static fn ($q) => $q->where('posyandu_id', $id))
            ->whereBetween('created_at', [$awalCreatedAt, $akhirCreatedAt])
            ->get();

        return view('admin.stunting.rekapitulasi-bulanan-balita', $data);
    }

    private function setKuartaTahun($kuartal, $tahun)
    {
        if ($kuartal == null) {
            $awalBulan  = date('m');
            $akhirBulan = date('m');
        } else {
            [$awalBulan, $akhirBulan] = explode('__', $kuartal);
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        return [$awalBulan, $akhirBulan, $tahun];
    }
}
