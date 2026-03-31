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

use App\Enums\StatusEnum;
use App\Models\Pamong;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;
use Modules\Kehadiran\Models\HariLibur;
use Modules\Kehadiran\Models\JamKerja;
use Modules\Kehadiran\Models\Kehadiran;

class PemerintahTransformer extends TransformerAbstract
{
    public function transform(Pamong $pemerintah)
    {
        $today = Carbon::now()->format('Y-m-d');

        $kehadiran = Kehadiran::where('pamong_id', $pemerintah->pamong_id)
            ->where('tanggal', $today)
            ->latest('id')
            ->first();

        $defaultFoto = ($pemerintah->pamong_sex_id ?? 1) == 1 ? 'kuser.png' : 'wuser.png';

        $isHariLiburNasional = HariLibur::liburNasional()->exists();
        $isJamLibur          = JamKerja::libur()->exists();

        $tampilkanStatusKehadiran = (!$isJamLibur && !$isHariLiburNasional)
            || setting('tampilkan_status_kehadiran_pada_hari_libur');

        return [
            // Data utama
            'id'           => (int) $pemerintah->pamong_id,
            'nama'         => $pemerintah->pamong_nama,
            'nama_jabatan' => $pemerintah->status_pejabat == StatusEnum::YA
                ? setting('sebutan_pj_kepala_desa') . ' ' . $pemerintah->jabatan->nama
                : $pemerintah->jabatan->nama,
            'tupoksi'      => $pemerintah->jabatan->tupoksi ?? null,

            // Bagan untuk organisasi charts
            'atasan'        => $pemerintah->atasan ? (int) $pemerintah->atasan : null,
            'bagan_tingkat' => $pemerintah->bagan_tingkat,
            'bagan_offset'  => $pemerintah->bagan_offset,
            'bagan_layout'  => $pemerintah->bagan_layout,
            'bagan_warna'   => $pemerintah->bagan_warna,

            // Data media sosial dan foto
            'foto'         => $this->urlAsset($pemerintah->foto_staff ?? $defaultFoto, $defaultFoto),
            'media_sosial' => $pemerintah->media_sosial,

            // Data kehadiran
            'status_kehadiran' => !$isHariLiburNasional
                ? ucwords($kehadiran->status_kehadiran ?? 'Belum Rekam Kehadiran')
                : 'Hari Libur',

            'kehadiran' => $tampilkanStatusKehadiran && $kehadiran ? [
                'status_kehadiran' => ucwords($kehadiran->status_kehadiran),
                'jam_masuk'        => $kehadiran->jam_masuk,
                'jam_keluar'       => $kehadiran->jam_keluar,
                'tanggal'          => $kehadiran->tanggal,
            ] : null,

            // Informasi tambahan
            'hari_libur' => $isHariLiburNasional,
        ];
    }

    private function urlAsset(?string $foto = null, ?string $defaultFoto = null)
    {
        return URL::signedRoute('storage.desa', [
            'path'        => (string) Str::of(LOKASI_USER_PICT)
                ->remove('desa/')
                ->append($foto),
            'default'     => "images/pengguna/{$defaultFoto}",
            'defaultDisk' => 'assets',
        ]);
    }
}
