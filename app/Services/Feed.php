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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services;

use App\Models\Artikel;

class Feed
{
    public const STATIS = 999;
    public const AGENDA = 1000;
    public const ENABLE = 1;

    public static function list_feeds()
    {
        $feeds = Artikel::select([
            'artikel.*',
            'user.nama as owner',
            'k.kategori as kategori_nama',
            'k.slug as kat_slug',
        ])
            ->selectRaw('YEAR(tgl_upload) as thn')
            ->selectRaw('MONTH(tgl_upload) as bln')
            ->selectRaw('DAY(tgl_upload) as hri')
            ->leftJoin('user', 'artikel.id_user', '=', 'user.id')
            ->leftJoin('kategori as k', 'artikel.id_kategori', '=', 'k.id')
            ->where('artikel.enabled', static::ENABLE)
            ->where('tgl_upload', '<', Artikel::raw('NOW()'))
            ->where(static function ($query) {
                $query->whereNotIn('artikel.id_kategori', [static::STATIS, static::AGENDA])
                    ->orWhereNull('artikel.id_kategori');
            })
            ->orderBy('artikel.tgl_upload', 'desc')
            ->limit(50)
            ->get();

        // Ubah Eloquent collection menjadi array of stdClass objects
        return $feeds->map(function ($feed) {
            return (object) array_merge($feed->getAttributes(), [
                'owner'         => $feed->owner ?? null,
                'kategori_nama' => $feed->kategori_nama ?? null,
                'kat_slug'      => $feed->kat_slug ?? null,
                'thn'           => $feed->thn ?? null,
                'bln'           => $feed->bln ?? null,
                'hri'           => $feed->hri ?? null,
            ]);
        })->toArray();
    }
}
