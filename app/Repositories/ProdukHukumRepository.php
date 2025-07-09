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

use App\Models\Dokumen;
use App\Models\RefDokumen;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProdukHukumRepository
{
    public function list()
    {
        return QueryBuilder::for(Dokumen::select('id', 'nama', 'tahun', 'satuan', 'kategori', 'attr', 'url'))
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('tahun'),
                AllowedFilter::exact('kategori'),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->where(static function ($subQuery) use ($value) {
                        $subQuery->where('nama', 'LIKE', '%' . $value . '%')
                            ->orWhere('tahun', 'LIKE', '%' . $value . '%')
                            ->orWhere('kategori', 'LIKE', '%' . $value . '%');
                    });
                }),
            ])
            ->allowedSorts(['id', 'nama', 'tahun', 'kategori'])
            ->tap(static fn ($query) => $query->produkHukum()->active())
            ->jsonPaginate();
    }

    public function tahun()
    {
        $years = Dokumen::where('kategori', '!=', 1)
            ->whereNotNull('tahun')
            ->selectRaw('MIN(tahun) as min_year, MAX(tahun) as max_year')
            ->first();

        if ($years) {
            return range($years->min_year, $years->max_year);
        }

        return [];
    }

    public function kategori()
    {
        return RefDokumen::where('id', '!=', 1)
            ->get()
            ->transform(fn ($item, $key) => $this->transformKategori($item, $key));
    }

    private function transformKategori($item, $key)
    {
        if ($key === 2) {
            return str_replace(['Desa', 'desa'], ucwords(setting('sebutan_desa')), $item);
        }

        if ($key === 3) {
            return "{$item} Di " . ucwords(setting('sebutan_desa'));
        }

        return $item;
    }
}
