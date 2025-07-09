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

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Feed
{
    public const STATIS = 999;
    public const AGENDA = 1000;
    public const ENABLE = 1;

    public static function list_feeds()
    {
        $data = DB::table('artikel as a')
            ->select([
                'a.*',
                'u.nama as owner',
                'k.kategori',
                'k.slug as kat_slug',
                DB::raw('YEAR(tgl_upload) as thn'),
                DB::raw('MONTH(tgl_upload) as bln'),
                DB::raw('DAY(tgl_upload) as hri'),
            ])
            ->leftJoin('user as u', 'a.id_user', '=', 'u.id')
            ->leftJoin('kategori as k', 'a.id_kategori', '=', 'k.id')
            ->where('a.enabled', static::ENABLE)
            ->where('tgl_upload', '<', DB::raw('NOW()'))
            ->whereNotIn('a.id_kategori', [static::STATIS, static::AGENDA])
            ->orderBy('a.tgl_upload', 'DESC')
            ->limit(50)
            ->get()
            ->toArray();

        return $data;
    }
}
