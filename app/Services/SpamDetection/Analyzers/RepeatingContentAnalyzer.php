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

class RepeatingContentAnalyzer extends AbstractAnalyzer
{
    /** Minimal jumlah kata agar analisis bermakna */
    private const MIN_WORD_COUNT = 10;

    /** Persentase pengulangan satu kata di atas threshold ini dianggap mencurigakan */
    private const REPEAT_THRESHOLD_PERCENT = 20;

    public function type(): string
    {
        return 'repeating_content';
    }

    public function maxScore(): int
    {
        return 25;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        // Gunakan \p{L}+ agar mendukung Unicode (Bahasa Indonesia, Arab, dsb)
        if (! preg_match_all('/\p{L}+/u', mb_strtolower($content, 'UTF-8'), $matches)) {
            return null;
        }

        $words      = $matches[0];
        $totalWords = count($words);

        if ($totalWords < self::MIN_WORD_COUNT) {
            return null;
        }

        $wordFrequency     = array_count_values($words);
        $mostFrequentCount = max($wordFrequency);
        $repeatPercentage  = ($mostFrequentCount / $totalWords) * 100;

        if ($repeatPercentage <= self::REPEAT_THRESHOLD_PERCENT) {
            return null;
        }

        $mostFrequentWord = array_search($mostFrequentCount, $wordFrequency);
        $excessPercent    = $repeatPercentage - self::REPEAT_THRESHOLD_PERCENT;

        return $this->result(
            rawScore: (int) ($excessPercent * 2),
            metadata: [
                'most_frequent_word'  => $mostFrequentWord,
                'most_frequent_count' => $mostFrequentCount,
                'total_words'         => $totalWords,
                'repeat_percentage'   => round($repeatPercentage, 2),
            ],
        );
    }
}
