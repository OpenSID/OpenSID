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

use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\Statistik\StatistikPendudukEnum;
use App\Models\Area;
use App\Models\Bantuan;
use App\Models\Garis;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\PendudukSaja;
use App\Models\Persil;
use App\Models\Wilayah;
use App\Services\LaporanPenduduk;

class PetaRepository
{
    public function list()
    {
        $desa          = identitas();
        $cdesaWebsite  = setting('tampilkan_cdesa_petaweb');
        $websitePersil = true;

        return [
            'wilayah'            => Wilayah::where('zoom', '>', 0)->get()->toArray(),
            'desa'               => $desa,
            'lokasi'             => Lokasi::activeLocationMap(),
            'garis'              => Garis::activeGarisMap(),
            'area'               => Area::activeAreaMap(),
            'lokasi_pembangunan' => Pembangunan::activePembangunanMap(),
            'penduduk'           => PendudukSaja::activeMap(),
            'dusun_gis'          => Wilayah::dusun()->get()->toArray(),
            'rw_gis'             => Wilayah::rw()->get()->toArray(),
            'rt_gis'             => Wilayah::rt()->get()->toArray(),
            'list_ref'           => StatistikPendudukEnum::allKeyLabel(),
            'list_bantuan'       => StatistikJenisBantuanEnum::allKeyLabel() + Bantuan::selectRaw('nama, CONCAT(50,id) as lap')->pluck('nama', 'lap')->toArray(),
            'persil'             => $cdesaWebsite ? Persil::activeMap($websitePersil) : [],
            'list_dusun'         => Wilayah::select(['dusun'])->distinct('dusun')->get()->toArray(),
            'title'              => 'Peta ' . ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']),
            'covid'              => (new LaporanPenduduk())->listData('covid'),
            'pengaturan'         => setting('tampilkan_tombol_peta'),
            'tampilkan_cdesa'    => $cdesaWebsite,
        ];
    }
}
