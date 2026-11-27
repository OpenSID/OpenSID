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

namespace App\Services\SpamDetection;

use App\Services\SpamDetection\Analyzers\CapsLockAnalyzer;
use App\Services\SpamDetection\Analyzers\ContactPatternAnalyzer;
use App\Services\SpamDetection\Analyzers\ExcessiveSymbolsAnalyzer;
use App\Services\SpamDetection\Analyzers\KeywordAnalyzer;
use App\Services\SpamDetection\Analyzers\PhoneNumberAnalyzer;
use App\Services\SpamDetection\Analyzers\RepeatingContentAnalyzer;
use App\Services\SpamDetection\Analyzers\UrlAnalyzer;
use App\Services\SpamDetection\Contracts\ContentAnalyzerInterface;
use App\Services\SpamDetection\DTOs\AnalyzerResult;
use App\Services\SpamDetection\DTOs\SpamDetectionResult;

/**
 * Orchestrator deteksi spam
 *
 * Menjalankan semua analyzer secara berurutan, mengakumulasi skor,
 * dan mengembalikan SpamDetectionResult yang immutable.
 *
 * Desain stateless: setiap panggilan analyze() menghasilkan result baru
 * tanpa bergantung pada state sebelumnya.
 *
 * Cara penggunaan:
 *
 *   $service = new SpamDetectionService();
 *   $result  = $service->analyze($content);
 *
 *   if ($result->isSpam) {
 *       // tolak konten
 *   }
 *
 * Atau dengan threshold kustom:
 *
 *   $result = $service->analyze($content, threshold: 50);
 *
 * Menambah analyzer baru:
 *
 *   $service = new SpamDetectionService([
 *       ...SpamDetectionService::defaultAnalyzers(),
 *       new MyCustomAnalyzer(),
 *   ]);
 */
class SpamDetectionService
{
    /** Threshold default — konten dengan skor >= ini dianggap spam */
    public const DEFAULT_THRESHOLD = 70;

    /** @var ContentAnalyzerInterface[] */
    private readonly array $analyzers;

    /**
     * @param ContentAnalyzerInterface[]|null $analyzers
     *        Null = gunakan set analyzer default
     */
    public function __construct(?array $analyzers = null)
    {
        $this->analyzers = $analyzers ?? self::defaultAnalyzers();
    }

    /**
     * Analisis konten dan kembalikan hasil deteksi spam
     *
     * @param string $content   Konten yang akan diperiksa
     * @param int    $threshold Threshold skor spam (0–100)
     */
    public function analyze(string $content, int $threshold = self::DEFAULT_THRESHOLD): SpamDetectionResult
    {
        if (empty(trim($content))) {
            return new SpamDetectionResult(
                score: 0,
                isSpam: false,
                threshold: $threshold,
            );
        }

        $details          = [];
        $accumulatedScore = 0;

        foreach ($this->analyzers as $analyzer) {
            $result = $analyzer->analyze($content);

            if ($result instanceof AnalyzerResult && $result->score > 0) {
                $details[]         = $result;
                $accumulatedScore += $result->score;
            }
        }

        $finalScore = min(100, $accumulatedScore);

        return new SpamDetectionResult(
            score: $finalScore,
            isSpam: $finalScore >= $threshold,
            threshold: $threshold,
            details: $details,
        );
    }

    /**
     * Shorthand: kembalikan hanya boolean
     */
    public function isSpam(string $content, int $threshold = self::DEFAULT_THRESHOLD): bool
    {
        return $this->analyze($content, $threshold)->isSpam;
    }

    /**
     * Set analyzer default yang digunakan service ini
     *
     * @return ContentAnalyzerInterface[]
     */
    public static function defaultAnalyzers(): array
    {
        return [
            new KeywordAnalyzer(),
            new PhoneNumberAnalyzer(),
            new UrlAnalyzer(),
            new RepeatingContentAnalyzer(),
            new CapsLockAnalyzer(),
            new ExcessiveSymbolsAnalyzer(),
            new ContactPatternAnalyzer(),
        ];
    }
}
