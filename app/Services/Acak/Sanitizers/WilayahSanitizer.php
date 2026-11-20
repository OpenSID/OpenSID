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

use App\Models\Wilayah;
use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;

/**
 * Ganti nama dusun dengan nama sintetis yang unik.
 */
final class WilayahSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak nama wilayah';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $dusuns = Wilayah::withoutGlobalScopes()->dusun()->orderBy('id')->get(['id', 'dusun']);
        $count  = 0;
        $used   = [];
        $index  = 0;

        foreach ($dusuns as $dusun) {
            $lama = (string) $dusun->dusun;
            if ($lama === '') {
                continue;
            }

            $baru = $this->namaUnik($context, $index, $used);
            $used[$baru] = true;
            $index++;

            Wilayah::withoutGlobalScopes()->where('dusun', $lama)->update(['dusun' => $baru]);
            $count++;
        }

        if ($count > 0) {
            $result->markTable('tweb_wil_clusterdesa');
            $result->addRows($count);
        }
    }

    /**
     * @param array<string, true> $used
     */
    private function namaUnik(AcakContext $context, int $index, array $used): string
    {
        $total = $context->gen->dusunCount();
        $try   = 0;

        do {
            $slot = $index + $try;
            $baru = $context->gen->dusun($slot % $total);
            if ($slot >= $total) {
                $baru .= ' ' . (string) (intdiv($slot, $total) + 1);
            }
            $try++;
        } while (isset($used[$baru]));

        return $baru;
    }
}
