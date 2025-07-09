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

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisParameter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AnalisisRepository
{
    public function analisisMaster()
    {
        return QueryBuilder::for(AnalisisIndikator::class)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('tahun'),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $subQuery->where('m.nama', 'like', "%{$value}%")
                            ->orWhere('p.tahun_pelaksanaan', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['master', 'tahun'])
            ->select([
                'm.id',
                'm.nama AS master',
                'm.subjek_tipe',
                's.subjek',
                'p.nama AS periode',
                'p.tahun_pelaksanaan AS tahun',
                'p.id AS id_periode',
            ])
            ->distinct()
            ->leftJoin('analisis_master as m', 'analisis_indikator.id_master', '=', 'm.id')
            ->leftJoin('analisis_ref_subjek as s', 'm.subjek_tipe', '=', 's.id')
            ->leftJoin('analisis_periode as p', 'p.id_master', '=', 'm.id')
            ->where('analisis_indikator.is_publik', 1)
            ->where('p.aktif', 1)
            ->where('analisis_indikator.config_id', identitas('id'))
            ->get();
    }

    public function analisisIndikator()
    {
        return QueryBuilder::for(AnalisisIndikator::class)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('id_master'),
                AllowedFilter::exact('tahun'),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $subQuery->where('master', 'like', "%{$value}%")
                            ->orWhere('tahun', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['master', 'tahun'])
            ->select([
                'analisis_indikator.id',
                'analisis_indikator.nomor',
                'analisis_indikator.id_master',
                'analisis_indikator.pertanyaan AS indikator',
                's.subjek',
                'p.nama AS periode',
                'p.tahun_pelaksanaan AS tahun',
                'm.nama AS master',
                'm.subjek_tipe',
                'p.id AS id_periode',
            ])
            ->leftJoin('analisis_master as m', 'analisis_indikator.id_master', '=', 'm.id')
            ->leftJoin('analisis_ref_subjek as s', 'm.subjek_tipe', '=', 's.id')
            ->leftJoin('analisis_periode as p', 'p.id_master', '=', 'm.id')
            ->where('analisis_indikator.is_publik', 1)
            ->where('p.aktif', 1)
            ->orderByRaw('LPAD(analisis_indikator.nomor, 10, " ")')
            ->where('analisis_indikator.config_id', identitas('id'))
            ->jsonPaginate();
    }

    public function jumlahAnalisisJawaban()
    {
        return QueryBuilder::for(AnalisisParameter::class)
            ->select('analisis_parameter.*')
            ->leftJoin('analisis_respon', 'analisis_respon.id_parameter', '=', 'analisis_parameter.id')
            ->orderBy('kode_jawaban', 'ASC')
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id_indikator'),
                AllowedFilter::callback('id_periode', static function ($query, $value) {
                    $query->addSelect([
                        // Subquery for the count with dynamic periode filter
                        DB::raw("(select count(analisis_respon.id_subjek)
                                  from analisis_respon
                                  where analisis_respon.id_parameter = analisis_parameter.id
                                  and analisis_respon.id_periode = '{$value}') as jml"),
                    ]);
                }),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where('jawaban', 'like', "%{$value}%");
                }),
                AllowedFilter::callback('subjek_tipe', static function ($query, $value) {
                    $query->when($value == 1, static function ($query) {
                        $query->leftJoin('tweb_penduduk as p', 'analisis_respon.id_subjek', '=', 'p.id')
                            ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id');
                    })
                        ->when($value == 2, static function ($query) {
                            $query->leftJoin('tweb_keluarga as v', 'analisis_respon.id_subjek', '=', 'v.id')
                                ->leftJoin('tweb_penduduk as p', 'v.nik_kepala', '=', 'p.id')
                                ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id');
                        })
                        ->when($value == 3, static function ($query) {
                            $query->leftJoin('tweb_rtm as v', 'analisis_respon.id_subjek', '=', 'v.id')
                                ->leftJoin('tweb_penduduk as p', 'v.nik_kepala', '=', 'p.id')
                                ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id');
                        })
                        ->when($value == 4, static function ($query) {
                            $query->leftJoin('kelompok as v', 'analisis_respon.id_subjek', '=', 'v.id')
                                ->leftJoin('tweb_penduduk as p', 'v.id_ketua', '=', 'p.id')
                                ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id');
                        });
                }),
            ])
            ->allowedSorts(['jawaban'])
            ->jsonPaginate();
    }
}
