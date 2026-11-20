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

namespace App\Services\SpamDetection\Analyzers;

use App\Services\SpamDetection\DTOs\AnalyzerResult;

class CapsLockAnalyzer extends AbstractAnalyzer
{
    /** Minimal jumlah huruf agar analisis bermakna */
    private const MIN_LETTER_COUNT = 20;

    /** Persentase huruf kapital di atas ini dianggap mencurigakan */
    private const CAPS_THRESHOLD_PERCENT = 60;

    public function type(): string
    {
        return 'caps_lock_abuse';
    }

    public function maxScore(): int
    {
        return 15;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        // Gunakan mb_ functions untuk Unicode safety
        preg_match_all('/\p{Lu}/u', $content, $upperMatches);
        preg_match_all('/\p{L}/u', $content, $allLetterMatches);

        $upperCount     = count($upperMatches[0]);
        $allLetterCount = count($allLetterMatches[0]);

        if ($allLetterCount < self::MIN_LETTER_COUNT) {
            return null;
        }

        $capsPercentage = ($upperCount / $allLetterCount) * 100;

        if ($capsPercentage <= self::CAPS_THRESHOLD_PERCENT) {
            return null;
        }

        $excessPercent = $capsPercentage - self::CAPS_THRESHOLD_PERCENT;

        return $this->result(
            rawScore: (int) ($excessPercent * 0.375),
            metadata: [
                'caps_percentage'  => round($capsPercentage, 2),
                'upper_count'      => $upperCount,
                'total_letters'    => $allLetterCount,
            ],
        );
    }
}
