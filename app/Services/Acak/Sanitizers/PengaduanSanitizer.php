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
 * Acak data pelapor pengaduan: NIK, nama, email, telepon, dan IP.
 */
final class PengaduanSanitizer implements Sanitizer
{
    public function label(): string
    {
        return 'Mengacak data pengaduan';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        if (! Schema::hasTable('pengaduan')) {
            return;
        }

        $gen   = $context->gen;
        $count = 0;

        DB::table('pengaduan')
            ->select(['id', 'nik'])
            ->orderBy('id')
            ->chunkById(1000, function ($rows) use ($context, $gen, &$count): void {
                foreach ($rows as $r) {
                    $origNik = (string) ($r->nik ?? '');
                    $newNik  = $origNik !== ''
                        ? ($context->nikMap[$origNik] ?? $gen->orphanNik($origNik))
                        : null;
                    $newNama = ($origNik !== '' && isset($context->nikNama[$origNik]))
                        ? $context->nikNama[$origNik]
                        : $gen->name('pengaduan-' . $r->id, 1);

                    DB::table('pengaduan')->where('id', $r->id)->update([
                        'nik'        => $newNik,
                        'nama'       => $newNama,
                        'email'      => $gen->email('pengaduan-' . $r->id),
                        'telepon'    => $gen->phone('pengaduan-' . $r->id),
                        'ip_address' => '127.0.0.1',
                    ]);
                    $count++;
                }
            });

        if ($count > 0) {
            $result->markTable('pengaduan');
            $result->addRows($count);
        }
    }
}
