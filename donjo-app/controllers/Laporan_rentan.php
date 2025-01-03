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

use App\Enums\CacatEnum;
use App\Enums\SakitMenahunEnum;
use App\Models\PendudukSaja;
use App\Models\Wilayah;

class Laporan_rentan extends Admin_Controller
{
    public $modul_ini     = 'statistik';
    public $sub_modul_ini = 'laporan-kelompok-rentan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function clear(): void
    {
        $session = ['dusun'];
        $this->session->unset_userdata($session);
        session_error_clear();

        redirect('laporan_rentan');
    }

    public function index(): void
    {
        $wilayah               = Wilayah::treeAccess();
        $data['dusunTerpilih'] = $this->session->dusun ?? '';

        $data['wilayah'] = $wilayah;
        $data['main']    = $this->listData($wilayah, $data['dusunTerpilih']);
        view('admin.laporan.rentan.index', $data);
    }

    public function cetak($aksi = 'cetak'): void
    {
        $wilayah               = Wilayah::treeAccess();
        $data['dusunTerpilih'] = $this->session->dusun ?? '';

        $data['wilayah'] = $wilayah;
        $data['main']    = $this->listData($wilayah, $data['dusunTerpilih']);
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=kelompok_rentan_' . date('Y-m-d') . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.laporan.rentan.cetak', $data);
    }

    public function dusun(): void
    {
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }
        redirect('laporan_rentan');
    }

    private function listData($wilayah, $dusunTerpilih = ''): array
    {
        $sekarang = date('d-m-Y');
        $result   = [];
        $data     = [
            'jenisKelamin' => PendudukSaja::dusun($dusunTerpilih)->status()->selectRaw('count(*) as total, id_cluster, sex')->groupBy('sex')->groupBy('id_cluster')->get()->groupBy('id_cluster')->map(static fn ($q) => $q->keyBy('sex')),
            'bayi'         => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 0, 'max' => 0])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'balita'       => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 1, 'max' => 5])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'sd'           => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 6, 'max' => 12])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'smp'          => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 13, 'max' => 15])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'sma'          => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 16, 'max' => 18])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'lansia'       => PendudukSaja::dusun($dusunTerpilih)->status()->batasiUmur($sekarang, ['satuan' => 'tahun', 'min' => 61, 'max' => 999])->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
            'cacat'        => PendudukSaja::dusun($dusunTerpilih)->status()->whereNotNull('cacat_id')->whereNotIn('cacat_id', [CacatEnum::TIDAK_CACAT])->selectRaw('count(*) as total, id_cluster, cacat_id')->groupBy('id_cluster')->groupBy('cacat_id')->get()->groupBy('id_cluster')->map(static fn ($q) => $q->keyBy('cacat_id')),
            'sakit'        => PendudukSaja::dusun($dusunTerpilih)->status()->whereNotNull('sakit_menahun_id')->whereNotIn('sakit_menahun_id', [0, SakitMenahunEnum::TIDAK_ADA_TIDAK_SAKIT])->selectRaw('count(*) as total, id_cluster, sex')->groupBy('id_cluster')->groupBy('sex')->get()->groupBy('id_cluster')->map(static fn ($q) => $q->keyBy('sex')),
            'hamil'        => PendudukSaja::dusun($dusunTerpilih)->status()->where('hamil', 1)->selectRaw('count(*) as total, id_cluster')->groupBy('id_cluster')->get()->keyBy('id_cluster'),
        ];

        foreach (array_keys($data) as $key) {
            $result[$key] = [];
        }
        if ($wilayah) {
            $defaultValue = [];

            foreach ($wilayah as $dusun) {
                foreach ($dusun as $rw) {
                    foreach ($rw as $rt) {
                        foreach ($data as $key => $arr) {
                            $result[$key][$rt->id] = $arr->get($rt->id) ? $arr->get($rt->id)->toArray() : $defaultValue;
                        }
                    }
                }
            }
        }

        return $result;

    }
}
