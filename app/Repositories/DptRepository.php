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

use App\Models\PendudukSaja;
use Spatie\QueryBuilder\QueryBuilder;

class DptRepository
{
    private $tanggalPemilihan;

    public function __construct($tanggalPemilihan)
    {
        $this->tanggalPemilihan = $tanggalPemilihan;
    }

    public function list()
    {
        return QueryBuilder::for(PendudukSaja::dpt($this->tanggalPemilihan))
            ->allowedFields('*')
            ->allowedFilters('*')
            ->allowedSorts('*')->jsonPaginate();
    }

    public function summary()
    {
        return QueryBuilder::for(PendudukSaja::selectRaw('tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.dusun, sex, count(*) as total')->join('tweb_wil_clusterdesa', 'tweb_wil_clusterdesa.id', '=', 'tweb_penduduk.id_cluster')->dpt($this->tanggalPemilihan)->groupBy(['tweb_wil_clusterdesa.dusun', 'tweb_wil_clusterdesa.rw', 'sex']))
            ->allowedFields('*')
            ->allowedFilters('*')
            ->allowedSorts('*')->jsonPaginate();
    }
}
