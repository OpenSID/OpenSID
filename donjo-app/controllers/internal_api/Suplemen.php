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

use App\Http\Transformers\SuplemenTerdataTransformer;
use App\Http\Transformers\SuplemenTransformer;
use App\Models\Penduduk;
use App\Repositories\SuplemenRepository;
use App\Repositories\SuplemenTerdataRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Suplemen extends Api_Controller
{
    public function apipenduduksuplemen()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $suplemen = $this->input->get('suplemen');
            $sasaran  = $this->input->get('sasaran');

            switch ($sasaran) {
                case 1:
                    $this->get_pilihan_penduduk($cari, $suplemen);
                    break;

                case 2:
                    $this->get_pilihan_kk($cari, $suplemen);
                    break;

                default:
            }
        }

        return show_404();
    }

    private function get_pilihan_penduduk($cari, $terdata)
    {
        $id_suplemen = $terdata;
        $penduduk    = Penduduk::select(['id', 'nik', 'nama', 'id_cluster', 'kk_level'])
            ->when($cari, static function ($query) use ($cari) {
                return $query->where(static function ($q) use ($cari) {
                    $q->where('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                });
            })
            ->whereNotIn('id', static fn ($q) => $q->select(['penduduk_id'])->whereNotNull('penduduk_id')->from('suplemen_terdata')->where('id_suplemen', $id_suplemen))
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id,
                    'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kk($cari, $terdata)
    {
        $id_suplemen = $terdata;
        $penduduk    = Penduduk::with('pendudukHubungan')
            ->select(['tweb_penduduk.id', 'tweb_penduduk.id_kk', 'tweb_penduduk.nik', 'keluarga_aktif.no_kk', 'tweb_penduduk.kk_level', 'tweb_penduduk.nama', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk_hubungan', static function ($join): void {
                $join->on('tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id');
            })
            ->leftJoin('keluarga_aktif', static function ($join): void {
                $join->on('tweb_penduduk.id_kk', '=', 'keluarga_aktif.id');
            })
            ->when($cari, static function ($query) use ($cari): void {
                $query->where(static function ($q) use ($cari): void {
                    $q->where('tweb_penduduk.nik', 'like', "%{$cari}%")
                        ->orWhere('keluarga_aktif.no_kk', 'like', "%{$cari}%")
                        ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
                });
            })
            ->whereIn('tweb_penduduk.kk_level', ['1'])
            ->whereNotIn('tweb_penduduk.id_kk', static fn ($q) => $q->select(['keluarga_id'])->whereNotNull('keluarga_id')->from('suplemen_terdata')->where('id_suplemen', $id_suplemen))
            ->orderBy('tweb_penduduk.id_kk')
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static fn ($item): array => [
                    'id'   => $item->id_kk,
                    'text' => 'No KK : ' . $item->no_kk . ' - ' . $item->pendudukHubungan->nama . '- NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper((string) setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                ]),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    public function list()
    {
        $suplemen = new SuplemenRepository();
        json($this->fractal($suplemen->list(), new SuplemenTransformer(), 'suplemen'));
    }

    public function anggota($suplemen)
    {
        $suplemenTerdata = new SuplemenTerdataRepository($suplemen);
        json($this->fractal($suplemenTerdata->list(), new SuplemenTerdataTransformer(), 'suplemen_terdata'));
    }
}
