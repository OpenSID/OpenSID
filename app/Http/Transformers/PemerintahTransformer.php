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

use App\Enums\StatusEnum;
use App\Models\Pamong;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Kehadiran\Models\Kehadiran;

class PemerintahTransformer extends TransformerAbstract
{
    public function transform(Pamong $pemerintah)
    {
        $kehadiran = Kehadiran::where('pamong_id', $pemerintah->pamong_id)
            ->where('tanggal', Carbon::now()->format('Y-m-d'))
            ->orderBy('id', 'DESC')->first();

        $pemerintah->id             = (int) $pemerintah->pamong_id;
        $pemerintah->nama_jabatan   = $pemerintah->status_pejabat == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $pemerintah->jabatan->nama : $pemerintah->jabatan->nama;
        $pemerintah->pamong_niap    = $pemerintah->pamong_niap;
        $pemerintah->gelar_depan    = $pemerintah->gelar_depan;
        $pemerintah->gelar_belakang = $pemerintah->gelar_belakang;
        $pemerintah->kehadiran      = $pemerintah->kehadiran;
        $fotoStaff                  = AmbilFoto($pemerintah->foto_staff, '', ($pemerintah->pamong_sex ?? $pemerintah->penduduk->sex));
        $pemerintah->foto           = to_base64($fotoStaff);
        // $pemerintah->id_sex = $sex;
        $pemerintah->nama             = $pemerintah->pamong_nama;
        $pemerintah->status_kehadiran = ucwords($kehadiran ? $kehadiran->status_kehadiran : 'Belum Rekam Kehadiran');
        $pemerintah->tanggal          = $kehadiran ? $kehadiran->tanggal : null;

        return $pemerintah->toArray();
    }
}
