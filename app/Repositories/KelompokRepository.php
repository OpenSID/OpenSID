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

use App\Models\Kelompok;
use App\Models\KelompokAnggota;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class KelompokRepository
{
    public $tipe = 'kelompok';

    public function detail($slug)
    {
        return QueryBuilder::for(Kelompok::with('pengurus')->tipe($this->tipe)->whereSlug($slug))
            ->allowedFields('*')
            ->first();
    }

    public function anggota($slug)
    {
        return QueryBuilder::for(KelompokAnggota::query()
            ->with('anggota')
            ->anggota()
            ->slugKelompok($slug)
            ->leftJoin('tweb_penduduk as tp', 'kelompok_anggota.id_penduduk', '=', 'tp.id')
            ->leftJoin('tweb_penduduk_sex as tps', 'tp.sex', '=', 'tps.id')
            ->leftJoin('tweb_wil_clusterdesa as twc', 'tp.id_cluster', '=', 'twc.id')
            ->select('kelompok_anggota.*'))
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $searchValue = '%' . $value . '%';

                        $subQuery->where('kelompok_anggota.no_anggota', 'LIKE', $searchValue)
                            ->orWhere('kelompok_anggota.nama_luar', 'LIKE', $searchValue)
                            ->orWhere('kelompok_anggota.nik_luar', 'LIKE', $searchValue)
                            ->orWhere('kelompok_anggota.alamat_luar', 'LIKE', $searchValue)
                            ->orWhere('kelompok_anggota.tempatlahir_luar', 'LIKE', $searchValue)
                            ->orWhere('tp.nama', 'LIKE', $searchValue)
                            ->orWhere('tp.nik', 'LIKE', $searchValue)
                            ->orWhere('tps.nama', 'LIKE', $searchValue)
                            ->orWhere('twc.dusun', 'LIKE', $searchValue)
                            ->orWhere('twc.rw', 'LIKE', $searchValue)
                            ->orWhere('twc.rt', 'LIKE', $searchValue);
                    });
                }),
            ])
            ->allowedSorts([
                'id',
                'no_anggota',
                AllowedSort::custom('jenis_kelamin', new class () implements \Spatie\QueryBuilder\Sorts\Sort {
                    public function __invoke($query, $descending, string $property)
                    {
                        $direction = $descending ? 'desc' : 'asc';
                        $query->orderByRaw("COALESCE(CAST(kelompok_anggota.sex_luar AS UNSIGNED), tp.sex, 0) {$direction}");
                    }
                }),
                AllowedSort::custom('alamat', new class () implements \Spatie\QueryBuilder\Sorts\Sort {
                    public function __invoke($query, $descending, string $property)
                    {
                        $direction = $descending ? 'desc' : 'asc';
                        $query->orderByRaw("COALESCE(NULLIF(kelompok_anggota.alamat_luar, ''), CONCAT(twc.dusun, ' RW ', twc.rw, ' RT ', twc.rt), '') {$direction}");
                    }
                }),
                AllowedSort::custom('nama', new class () implements \Spatie\QueryBuilder\Sorts\Sort {
                    public function __invoke($query, $descending, string $property)
                    {
                        $direction = $descending ? 'desc' : 'asc';
                        $query->orderByRaw("COALESCE(NULLIF(kelompok_anggota.nama_luar, ''), tp.nama, '') {$direction}");
                    }
                }),
            ])
            ->jsonPaginate();
    }
}
