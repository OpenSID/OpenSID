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
        return QueryBuilder::for(KelompokAnggota::with('anggota')->anggota()->slugKelompok($slug))
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $subQuery->where('no_anggota', 'LIKE', '%' . $value . '%')
                            ->orWhereHas('anggota', static function ($anggotaQuery) use ($value) {
                                $anggotaQuery->where('nama', 'LIKE', '%' . $value . '%')
                                    ->orWhereHas('jenisKelamin', static function ($jenisKelaminQuery) use ($value) {
                                        $jenisKelaminQuery->where('nama', 'LIKE', '%' . $value . '%');
                                    })
                                    ->orWhereHas('wilayah', static function ($wilayahQuery) use ($value) {
                                        $wilayahQuery->where('dusun', 'LIKE', '%' . $value . '%')
                                            ->orWhere('rw', 'LIKE', '%' . $value . '%')
                                            ->orWhere('rt', 'LIKE', '%' . $value . '%');
                                    });
                            });
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
                        $query->join('tweb_penduduk', 'kelompok_anggota.id_penduduk', '=', 'tweb_penduduk.id')
                            ->orderBy('tweb_penduduk.sex', $direction);
                    }
                }),
                AllowedSort::custom('alamat', new class () implements \Spatie\QueryBuilder\Sorts\Sort {
                    public function __invoke($query, $descending, string $property)
                    {
                        $direction = $descending ? 'desc' : 'asc';
                        $query->join('tweb_penduduk', 'kelompok_anggota.id_penduduk', '=', 'tweb_penduduk.id')
                            ->join('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                            ->orderBy('tweb_wil_clusterdesa.dusun', $direction);
                    }
                }),
                AllowedSort::custom('nama', new class () implements \Spatie\QueryBuilder\Sorts\Sort {
                    public function __invoke($query, $descending, string $property)
                    {
                        $direction = $descending ? 'desc' : 'asc';
                        $query->join('tweb_penduduk', 'kelompok_anggota.id_penduduk', '=', 'tweb_penduduk.id')
                            ->orderBy('tweb_penduduk.nama', $direction);
                    }
                }),
            ])
            ->jsonPaginate();
    }
}
