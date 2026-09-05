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

namespace App\Http\Transformers;

use App\Models\BantuanPeserta;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class BantuanPesertaTransformer extends TransformerAbstract
{
    public function transform(BantuanPeserta $peserta)
    {
        $sembunyikanNama   = setting('sembunyikan_nama_penerima_bantuan');
        $sembunyikanAlamat = setting('sembunyikan_alamat_penerima_bantuan');

        return [
            'id'           => $peserta->id,
            'program_id'   => $peserta->program_id,
            'nama'         => $peserta?->bantuan?->nama,
            'kartu_nama'   => $sembunyikanNama ? Str::mask($peserta->kartu_nama, '*', 3) : $peserta->kartu_nama,
            'kartu_alamat' => $sembunyikanAlamat ? Str::mask($peserta->kartu_alamat, '*', 3) : $peserta->kartu_alamat,
            'sdate'        => $peserta?->bantuan?->sdate,
            'edate'        => $peserta?->bantuan?->edate,
        ];
    }
}
