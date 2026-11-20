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

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Redaksi SMS terkirim (sentitems): samarkan nomor tujuan dan isi pesan.
 */
final class SentitemsSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Menyamarkan SMS terkirim';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! Schema::hasTable('sentitems')) {
            return;
        }

        $gen   = $context->gen;
        $count = 0;

        DB::table('sentitems')
            ->select(['ID'])
            ->orderBy('ID')
            ->chunkById(1000, function ($rows) use ($gen, &$count): void {
                foreach ($rows as $r) {
                    DB::table('sentitems')->where('ID', $r->ID)->update([
                        'DestinationNumber' => $gen->phone('sms-' . $r->ID),
                        'TextDecoded'       => '[REDACTED]',
                        'Text'              => '[REDACTED]',
                    ]);
                    $count++;
                }
            }, 'ID', 'ID');

        if ($count > 0) {
            $result->markTable('sentitems');
            $result->addRows($count);
            $result->phonesReplaced += $count;
        }
    }
}
