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
use App\Services\SpamDetection\ML\SpamClassifierPipeline;
use Illuminate\Support\Facades\Log;

/**
 * Analyzer deteksi spam berbasis model ML lokal (Rubix ML): pipeline
 * TF-IDF n-gram + estimator terpilih (default produksi: logistic
 * regression, lihat SpamClassifierPipeline).
 *
 * Menangkap spam yang lolos rule-based, misalnya promo tanpa kata kunci
 * hitam dan variasi ejaan yang belum ada di daftar keyword.
 *
 * Model dimuat lazy dan di-cache per request. Bila model tidak tersedia,
 * analyze() mengembalikan null sehingga deteksi tetap berjalan dengan
 * analyzer lain.
 */
class MlSpamAnalyzer extends AbstractAnalyzer
{
    /** Ambang probabilitas minimal agar kontribusi skor. */
    private const MIN_PROBABILITY = 0.55;

    /** Cache per request: modelPath => [classifier|null, filemtime]. */
    private static array $cache = [];

    private readonly string $modelPath;

    private ?SpamClassifierPipeline $classifier = null;

    public function __construct(?string $modelPath = null)
    {
        $this->modelPath = $modelPath ?? self::defaultModelPath();
    }

    /**
     * Kosongkan cache model (dipanggil setelah retraining atau di test).
     */
    public static function flushCache(): void
    {
        self::$cache = [];
    }

    /**
     * Path model default dari konfigurasi.
     */
    private static function defaultModelPath(): string
    {
        return (string) config('spam.model_path');
    }

    public function type(): string
    {
        return 'ml_classifier';
    }

    public function maxScore(): int
    {
        return 100;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        $classifier = $this->loadClassifier();

        if ($classifier === null) {
            return null;
        }

        $probability = $classifier->probabilitySpam($content);

        if ($probability < self::MIN_PROBABILITY) {
            return null;
        }

        // p=0.55 -> skor 10, p=0.85 -> 70 (threshold default), p=1.0 -> 100
        $score = (int) round(($probability - 0.5) * 200);

        return $this->result(
            rawScore: $score,
            metadata: [
                'probability' => round($probability, 4),
            ],
        );
    }

    private function loadClassifier(): ?SpamClassifierPipeline
    {
        if ($this->classifier !== null) {
            return $this->classifier;
        }

        $fileMtime = is_file($this->modelPath) ? filemtime($this->modelPath) : 0;
        $cacheKey  = $this->modelPath . '#' . $fileMtime;

        if (array_key_exists($cacheKey, self::$cache)) {
            return $this->classifier = self::$cache[$cacheKey];
        }

        try {
            $classifier = SpamClassifierPipeline::load($this->modelPath);

            return $this->classifier = self::$cache[$cacheKey] = $classifier;
        } catch (\RuntimeException $e) {
            Log::warning('Model deteksi spam tidak dapat dimuat: ' . $e->getMessage());

            // Jangan cache kegagalan permanen - biarkan retry pada request berikutnya
            return $this->classifier = null;
        }
    }
}