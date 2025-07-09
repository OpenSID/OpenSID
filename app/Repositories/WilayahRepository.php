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

use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WilayahRepository
{
    public function list()
    {
        $query = Wilayah::dusun()->with([
            'kepala', 'rws' => static fn ($q) => $q->orderBy('urut')->with([
                'kepala', 'rts' => static fn ($q) => $q->orderBy('urut')->with('kepala')->withCount([
                    'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_9.rw = tweb_wil_clusterdesa.rw and laravel_reserved_9.rt = tweb_wil_clusterdesa.rt')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_10.rw = tweb_wil_clusterdesa.rw and laravel_reserved_10.rt = tweb_wil_clusterdesa.rt')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_11.rw = tweb_wil_clusterdesa.rw and laravel_reserved_11.rt = tweb_wil_clusterdesa.rt')),
                ]),
            ])->withCount([
                'rts' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_5.rw = tweb_wil_clusterdesa.rw')), 'keluargaAktif' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_6.rw = tweb_wil_clusterdesa.rw')), 'pendudukPria' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_7.rw = tweb_wil_clusterdesa.rw')), 'pendudukWanita' => static fn ($q) => $q->whereRaw(DB::raw('laravel_reserved_8.rw = tweb_wil_clusterdesa.rw')),
            ]),
        ])
            ->orderBy('urut')
            ->withCount(['rts', 'rws' => static fn ($q) => $q->where('rw', '!=', '-'), 'keluargaAktif', 'pendudukPria', 'pendudukWanita']);

        return QueryBuilder::for($query)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where('dusun', 'LIKE', '%' . $value . '%')
                        ->orWhere('rt', 'LIKE', '%' . $value . '%')
                        ->orWhere('rw', 'LIKE', '%' . $value . '%');
                }),
            ])
            ->allowedSorts(['urut', 'id'])
            ->jsonPaginate();
    }
}
