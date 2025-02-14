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

namespace App\Models;

use App\Enums\StatusEnum;
use Carbon\Carbon;
use Modules\Kehadiran\Models\Kehadiran;

defined('BASEPATH') || exit('No direct script access allowed');

class KehadiranPamong extends BaseModel
{
    // Ambil data untuk widget aparatur desa
    public static function widget()
    {
        $data_query = Pamong::aktif()->urut()->get()->toArray();

        $result = collect($data_query)->map(static function (array $item): array {
            $kehadiran = Kehadiran::where('pamong_id', $item['pamong_id'])
                ->where('tanggal', Carbon::now()->format('Y-m-d'))
                ->orderBy('id', 'DESC')->first();

            $nama = $item['pamong_nama'];
            $sex  = $item['id_pend'] ? $item['penduduk']['sex'] : $item['pamong_sex'];

            return [
                'pamong_id'        => $item['pamong_id'],
                'jabatan'          => $item['status_pejabat'] == StatusEnum::YA ? setting('sebutan_pj_kepala_desa') . ' ' . $item['jabatan']['nama'] : $item['jabatan']['nama'],
                'pamong_niap'      => $item['pamong_niap'],
                'gelar_depan'      => $item['gelar_depan'],
                'gelar_belakang'   => $item['gelar_belakang'],
                'kehadiran'        => $item['kehadiran'],
                'media_sosial'     => json_encode($item['media_sosial']),
                'foto'             => AmbilFoto($item['foto_staff'], '', ($item['pamong_sex'] ?? $item['penduduk->sex'])),
                'id_sex'           => $sex,
                'nama'             => $nama,
                'status_kehadiran' => $kehadiran ? $kehadiran->status_kehadiran : null,
                'tanggal'          => $kehadiran ? $kehadiran->tanggal : null,
            ];
        })->toArray();

        return ['daftar_perangkat' => $result];
    }
}
