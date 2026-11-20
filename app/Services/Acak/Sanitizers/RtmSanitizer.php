<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Sanitizers;

use App\Models\Rtm;
use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use Carbon\Carbon;

/**
 * Acak nomor Rumah Tangga Miskin (RTM): no_kk, tanggal daftar, dan BDT.
 */
final class RtmSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data RTM';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $rtms  = Rtm::withoutGlobalScopes()->orderBy('id')->get(['id', 'no_kk', 'bdt', 'tgl_daftar']);
        $count = 0;
        // Keunikan dari nilai yang digenerate run ini saja (reproducible).
        $used  = [];

        foreach ($rtms->values() as $key => $rtm) {
            $tglStr    = $context->gen->syntheticDate('rtm-daftar', (int) $rtm->id, (string) $rtm->tgl_daftar) ?? '2016-01-01';
            $tglDaftar = Carbon::parse($tglStr);
            $noKk      = $this->uniqueKk((int) $rtm->id, (string) $rtm->no_kk, $tglDaftar, $context, $used);
            $bdt       = empty($rtm->bdt)
                ? null
                : $context->kodeKecamatan . str_pad((string) ($key + 1), 10, '0', STR_PAD_LEFT);

            Rtm::withoutGlobalScopes()->where('id', $rtm->id)->update([
                'no_kk'      => $noKk,
                'tgl_daftar' => $tglStr,
                'bdt'        => $bdt,
            ]);
            $count++;
        }

        if ($count > 0) {
            $result->markTable('tweb_rtm');
            $result->addRows($count);
        }
    }

    /**
     * No. KK RTM sintetis 16 digit: region(6) + tanggal daftar(6) + urut(4),
     * dengan penelusuran (probing) urutan agar unik dalam sekali proses.
     *
     * @param array<string, mixed> $used
     */
    private function uniqueKk(int $id, string $origKk, Carbon $tglDaftar, AcakContext $context, array &$used): string
    {
        $gen    = $context->gen;
        $prefix = $gen->region6($origKk, $context->kodeKecamatan);
        $mid    = $tglDaftar->format('dmy');

        for ($i = 0; $i < 9000; $i++) {
            $kk = $prefix . $mid . $gen->seq4('rtm-kk-seq', (string) $id, $i);
            if (! isset($used[$kk])) {
                $used[$kk] = true;

                return $kk;
            }
        }

        return $prefix . $mid . $gen->seq4('rtm-kk-seq', (string) $id);
    }
}
