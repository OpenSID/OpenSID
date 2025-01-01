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

namespace App\Http\Transformers;

use App\Models\Wilayah;
use League\Fractal\TransformerAbstract;

class WilayahTransformer extends TransformerAbstract
{
    public function transform(Wilayah $wilayah)
    {
        $wilayah->sebutan_dusun = ucwords(setting('sebutan_dusun'));
        $wilayah->kepala_nama   = $wilayah->kepala->nama ? ', ketua ' . $wilayah->kepala->nama : '';
        $wilayah->rws->transform(static function ($rw) {
            $rw->rts->transform(static function ($rt) {
                $rt->sebutan_rt                 = 'RT';
                $rt->kepala_nama                = $rt->kepala->nama ? ', ketua ' . $rt->kepala->nama : '';
                $rt->penduduk_pria_wanita_count = $rt->penduduk_pria_count + $rt->penduduk_wanita_count;

                return $rt;
            });
            $rw->sebutan_rw                 = 'RW';
            $rw->penduduk_pria_wanita_count = $rw->penduduk_pria_count + $rw->penduduk_wanita_count;

            if ($rw->rw != '-') {
                $rw->kepala_nama = $rw->kepala->nama ? ', ketua ' . $rw->kepala->nama : '';
            }

            return $rw;
        });

        $wilayah->penduduk_pria_wanita_count = $wilayah->penduduk_pria_count + $wilayah->penduduk_wanita_count;

        return $wilayah->toArray();
    }
}
