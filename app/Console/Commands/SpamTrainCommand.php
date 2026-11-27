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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terkait (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Console\Commands;

use App\Services\SpamDetection\Analyzers\MlSpamAnalyzer;
use App\Services\SpamDetection\ML\SpamClassifierPipeline;
use App\Services\SpamDetection\ML\Transformers\SpamPreprocessorTransformer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Stringable;
use Laravel\Prompts\Prompt;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Extractors\CSV;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Train the spam classifier.
 *
 * Builds a TF-IDF n-gram pipeline following the RubixML Sentiment example
 * [SpamPreprocessorTransformer → WordCountVectorizer → TfIdfTransformer] → Estimator.
 * Persistence uses PersistentModel with RBX (.rbx).
 *
 * Combines three sources: public judol dataset, Indonesian SMS dataset,
 * and the curated OpenSID seed.
 */
class SpamTrainCommand extends Command
{
    protected $signature = 'opensid:spam-train
        {--dataset= : Path dataset CSV berlabel (kolom: comment_id,author,comment_text,label ATAU text,label; label: 0/1 atau spam/not_spam)}
        {--sms-dataset= : Path dataset SMS CSV (default: config/spam.php)}
        {--seed-dataset= : Path dataset seed OpenSID CSV (default: config/spam.php)}
        {--save= : Path output model (default: config/spam.php)}
        {--split=0.2 : Proporsi data uji (hold-out, 0-1)}
        {--seed=42 : Seed deterministik untuk pengacakan data}
        {--judol-per-class=2000 : Maksimum sampel judol per kelas yang dipakai (0 = semua)}
        {--seed-weight=10 : Pengulangan sampel seed OpenSID agar fiturnya masuk vocabulary}
        {--sms-per-class=500 : Maksimum sampel SMS per kelas (0 = semua)}
        {--sms-weight=3 : Pengulangan sampel SMS agar fiturnya masuk vocabulary}
        {--classifier=logistic_regression : Algoritma classifier (gaussian_nb|logistic_regression|svm)}
        {--show-misclassified : Tampilkan teks sampel hold-out yang salah diprediksi, per domain (judol/sms/opensid_seed)}
        {--misclassified-limit=20 : Maksimum baris misclassified yang ditampilkan per domain (0 = semua)}
        {--production-threshold : Evaluasi menggunakan threshold produksi (p >= 0.85, skor >= 70) bukan default p >= 0.5}
        {--dump-vocabulary : Cetak token vocabulary yang mengandung >=5 digit berurutan (indikasi nomor HP/NIK), untuk audit preprocessing}
        {--validate-seeds= : Jalankan training berulang untuk daftar seed (pisahkan koma, mis. 1,2,3,7,42,99) dan cetak ringkasan Seed OpenSID F1 per seed. Tidak menyimpan model.}
        {--force : Timpa model yang sudah ada}';

    protected $description = 'Unduh (bila perlu), latih, dan evaluasi classifier (Rubix ML, TF-IDF n-gram) untuk deteksi spam. Classifier: logistic_regression (default), gaussian_nb, svm.';

    public function handle(): int
    {
        $datasetPath         = (string) ($this->option('dataset') ?: config('spam.dataset_path'));
        $smsDatasetPath      = (string) ($this->option('sms-dataset') ?: config('spam.sms_dataset_path'));
        $seedDatasetPath     = (string) ($this->option('seed-dataset') ?: config('spam.seed_path'));
        $savePath            = (string) ($this->option('save') ?: config('spam.model_path'));
        $split               = max(0.05, min(0.5, (float) $this->option('split')));
        $weight              = max(1, (int) $this->option('seed-weight'));
        $smsWeight           = max(1, (int) $this->option('sms-weight'));
        $seedValue           = (int) $this->option('seed');
        $classifierName      = $this->option('classifier');
        $productionThreshold = (bool) $this->option('production-threshold');

        // Mode validasi multi-seed: evaluasi saja, tidak simpan model
        if ($this->option('validate-seeds') !== null) {
            return $this->runMultiSeedValidation($datasetPath, $smsDatasetPath, $seedDatasetPath, $classifierName);
        }

        // Classifier yang kompatibel dengan fitur TF-IDF kontinu DAN mendukung partial training
        $validClassifiers = ['gaussian_nb', 'logistic_regression', 'svm'];
        if (! in_array($classifierName, $validClassifiers, true)) {
            $this->components->error("Classifier tidak valid: {$classifierName}. Pilihan: ".implode(', ', $validClassifiers));

            return self::FAILURE;
        }

        // Guard untuk SVM: perlu ekstensi PHP SVM
        if ($classifierName === 'svm' && ! extension_loaded('svm')) {
            $this->components->error('Classifier SVM memerlukan ekstensi PHP "svm" (pecl install svm). Silakan install ekstensi atau pilih classifier lain.');

            return self::FAILURE;
        }

        $this->printConfiguration($datasetPath, $smsDatasetPath, $seedDatasetPath, $savePath, $split, $classifierName);

        if (is_file($savePath) && ! $this->option('force')) {
            $this->components->warn('Model sudah ada: '.$savePath);
            $this->line('Gunakan opsi <options=bold>--force</> untuk melatih ulang dan menimpanya.');

            return self::FAILURE;
        }

        if (! is_file($datasetPath) && ! $this->downloadDataset($datasetPath, config('spam.dataset_url'))) {
            return self::FAILURE;
        }

        if (! is_file($smsDatasetPath) && ! $this->downloadDataset($smsDatasetPath, config('spam.sms_dataset_url'))) {
            return self::FAILURE;
        }

        // Global deduplication across all sources to prevent train/test leakage
        $seen = [];

        // Track samples after loading (post-dedup) for logging
        $judol = $this->loadAndPrepareSamples($datasetPath, 'Memuat dataset judol', seen: $seen);

        if ($judol === []) {
            $this->components->error('Dataset judol kosong setelah preprocessing.');

            return self::FAILURE;
        }
        $judolAfterDedup = count($judol);

        $judol = $this->balanceSamples($judol, (int) $this->option('judol-per-class'), $seedValue);

        // Load SMS dataset dengan mapping label: 0→not_spam, 1→spam, 2→spam
        $sms = $this->loadAndPrepareSamples($smsDatasetPath, 'Memuat dataset SMS', function (string $label): string {
            return match ($label) {
                '0'     => 'not_spam',
                '1'     => 'spam',
                '2'     => 'spam',
                default => $label,
            };
        }, seen: $seen);

        if ($sms === []) {
            $this->components->error('Dataset SMS kosong setelah preprocessing.');

            return self::FAILURE;
        }
        $smsAfterDedup = count($sms);

        $sms = $this->balanceSamples($sms, (int) $this->option('sms-per-class'), $seedValue);

        if (! is_file($seedDatasetPath)) {
            $this->components->error('File seed OpenSID tidak ditemukan: '.$seedDatasetPath);

            return self::FAILURE;
        }

        $seed = $this->loadAndPrepareSamples($seedDatasetPath, 'Memuat seed OpenSID', seen: $seen);
        $seedAfterDedup = count($seed);

        // Calculate dedup stats from raw CSV counts vs post-dedup counts
        $judolBeforeDedup = $this->countDataRows($datasetPath);
        $smsBeforeDedup   = $this->countDataRows($smsDatasetPath);
        $seedBeforeDedup  = $this->countDataRows($seedDatasetPath);

        $duplicatesRemoved = [
            'judol' => $judolBeforeDedup - $judolAfterDedup,
            'sms'   => $smsBeforeDedup - $smsAfterDedup,
            'seed'  => $seedBeforeDedup - $seedAfterDedup,
        ];

        $totalBeforeDedup = $judolBeforeDedup + $smsBeforeDedup + $seedBeforeDedup;
        $totalAfterDedup = $judolAfterDedup + $smsAfterDedup + $seedAfterDedup;
        $totalDuplicatesRemoved = array_sum($duplicatesRemoved);

        $this->components->info('Duplikat lintas-sumber dibuang');
        $this->renderTwoColumnTable([
            ['Judol', number_format($judolBeforeDedup).' → '.number_format($judolAfterDedup).' (dibuang '.number_format($duplicatesRemoved['judol']).')'],
            ['SMS', number_format($smsBeforeDedup).' → '.number_format($smsAfterDedup).' (dibuang '.number_format($duplicatesRemoved['sms']).')'],
            ['Seed OpenSID', number_format($seedBeforeDedup).' → '.number_format($seedAfterDedup).' (dibuang '.number_format($duplicatesRemoved['seed']).')'],
            ['Total', number_format($totalBeforeDedup).' → '.number_format($totalAfterDedup).' (dibuang '.number_format($totalDuplicatesRemoved).')'],
        ]);
        $this->newLine();

        // Split judol, SMS, dan seed TERLEBIH DAHULU (sebelum replikasi) untuk mencegah data leakage.
        // Replikasi (--seed-weight, --sms-weight) hanya diterapkan pada partisi TRAIN, bukan test.
        // Evaluasi harus memakai tiap sampel unik apa adanya, sekali saja.
        [$judolTrain, $judolTest] = $this->splitSamples($judol, $split, $seedValue);
        [$smsTrain, $smsTest]     = $this->splitSamples($sms, $split, $seedValue);
        [$seedTrain, $seedTest]   = $this->splitSamples($seed, $split, $seedValue);

        // Tambahkan source identifier untuk breakdown metrics
        $judolTest = array_map(static function (array $s): array {
            $s['source'] = 'judol';
            return $s;
        }, $judolTest);
        $smsTest = array_map(static function (array $s): array {
            $s['source'] = 'sms';
            return $s;
        }, $smsTest);
        $seedTest = array_map(static function (array $s): array {
            $s['source'] = 'opensid_seed';
            return $s;
        }, $seedTest);

        // Replikasi seed dan SMS hanya pada partisi train
        $seedTrainReplicated = array_merge(...array_fill(0, $weight, $seedTrain));
        $smsTrainReplicated = array_merge(...array_fill(0, $smsWeight, $smsTrain));

        // Gabungkan partisi train dan test terpisah
        $trainSamples = array_merge($judolTrain, $seedTrainReplicated, $smsTrainReplicated);
        $testSamples  = array_merge($judolTest, $seedTest, $smsTest);

        // Acak masing-masing partisi dengan seed deterministik
        mt_srand($seedValue);
        shuffle($trainSamples);
        mt_srand($seedValue + 1);
        shuffle($testSamples);

        $this->renderTwoColumnTable([
            ['Judol berimbang', $this->describe($judol)],
            ['SMS berimbang', $this->describe($sms)],
            ['Seed OpenSID', $this->describe($seed).' ('.number_format(count($seed)).' unik)'],
            ['Seed OpenSID x'.$weight, $this->describe($seedTrainReplicated).' (direplikasi '.$weight.'x, hanya pada partisi train)'],
            ['SMS x'.$smsWeight, $this->describe($smsTrainReplicated).' (direplikasi '.$smsWeight.'x, hanya pada partisi train)'],
            ['Total train', $this->describe($trainSamples)],
            ['Total test', $this->describe($testSamples)],
            ['Split hold-out', number_format(count($trainSamples)).' latih / '.number_format(count($testSamples)).' uji'],
        ]);
        $this->newLine();

        $classifier = SpamClassifierPipeline::create($classifierName);

        $this->trainWithProgress($classifier, $trainSamples, 'Melatih model pada data latih');
        $this->printMetrics('Evaluasi hold-out (data yang belum pernah dilihat)', $classifier, $testSamples);
        if ($productionThreshold) {
            $this->printMetrics('Evaluasi hold-out (threshold produksi p≥0.85)', $classifier, $testSamples, true);
        }
        $this->printMetricsBreakdown('Evaluasi per domain (breakdown)', $classifier, $testSamples);

        if ($this->option('show-misclassified')) {
            $this->printMisclassified($classifier, $testSamples, (int) $this->option('misclassified-limit'));
        }

        // Data lengkap untuk retraining produksi: train + test (tanpa replikasi seed/SMS pada test)
        $fullSamples = array_merge($trainSamples, $testSamples);

        $this->trainWithProgress($classifier, $fullSamples, 'Melatih ulang dengan seluruh data (model produksi)');
        $this->printMetrics('Evaluasi on train (seluruh dataset)', $classifier, $fullSamples);
        if ($productionThreshold) {
            $this->printMetrics('Evaluasi on train (threshold produksi p≥0.85)', $classifier, $fullSamples, true);
        }

        $this->components->task('Menyimpan model', function () use ($classifier, $savePath): bool {
            $classifier->save($savePath);

            return is_file($savePath);
        });

        // Invalidate cache so Octane/queue workers pick up new model
        MlSpamAnalyzer::flushCache();

        // Dump vocabulary for audit if requested
        if ($this->option('dump-vocabulary')) {
            $this->dumpNumericVocabulary($classifier);
        }

        $this->newLine();
        $this->components->info('Training selesai.');
        $this->renderTwoColumnTable([
            ['Lokasi model', $savePath],
            ['Ukuran model', round(filesize($savePath) / 1024, 1).' KB'],
        ]);

        return self::SUCCESS;
    }

    /**
     * Latih classifier sambil menampilkan progress bar, memanfaatkan callback
     * onProgress stage-based dari SpamClassifierPipeline::train().
     * Pipeline kini melaporkan 5 tahap (1/5..5/5) sehingga ProgressBar
     * dapat advance realtime di tiap tahap berat (vocabulary, TF-IDF, estimator).
     *
     * @param array<int, array{text: string, label: string}> $samples
     */
    private function trainWithProgress(SpamClassifierPipeline $classifier, array $samples, string $message): void
    {
        $bar = null;

        $classifier->train($samples, onProgress: function (int $step, int $total, string $stage) use (&$bar): void {
            if ($bar === null) {
                $bar = $this->newProgressBar($total, $stage);
                $bar->start();
            } elseif ($bar->getMaxSteps() !== $total) {
                $bar->setMaxSteps($total);
            }

            $bar->setMessage($stage);
            $bar->setProgress($step);
            // Paksa render segera agar tidak tertahan buffer selama operasi berat berikutnya
            $bar->display();
        });

        $bar?->finish();
        $this->newLine();
        $this->renderTwoColumnTable([
            [$message, '<fg=green;options=bold>DONE</>'],
        ]);
    }

    private function newProgressBar(int $max, string $message): ProgressBar
    {
        $bar = $this->output->createProgressBar(max(1, $max));
        $bar->setFormat('  %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage($message);

        return $bar;
    }

    private function printConfiguration(string $datasetPath, string $smsDatasetPath, string $seedDatasetPath, string $savePath, float $split, string $classifier): void
    {
        $this->components->info('Konfigurasi training');
        $this->newLine();

        $rows = [
            ['Dataset judol', $datasetPath],
            ['Dataset SMS', $smsDatasetPath],
            ['Seed OpenSID', $seedDatasetPath],
            ['Output model', $savePath],
            ['Hold-out (--split)', round($split * 100, 1).' %'],
            ['Judol per kelas (--judol-per-class)', number_format((int) $this->option('judol-per-class'))],
            ['SMS per kelas (--sms-per-class)', number_format((int) $this->option('sms-per-class'))],
            ['Berat seed (--seed-weight)', (string) $this->option('seed-weight')],
            ['Berat SMS (--sms-weight)', (string) $this->option('sms-weight')],
            ['Seed acak (--seed)', (string) $this->option('seed')],
            ['Classifier (--classifier)', $classifier],
        ];

        $this->renderTwoColumnTable($rows);
        $this->newLine();
    }

    /**
     * Render two-column layout with dot-fill, responsive to terminal width.
     * Inspired by DevListCommand.
     *
     * @param array<int, array{0: string, 1: string}> $rows
     */
    private function renderTwoColumnTable(array $rows): void
    {
        $columns = Prompt::terminal()->cols();
        $labelWidth = max(array_map(fn ($row) => mb_strwidth($row[0]), $rows));
        $spaceBuffer = 4;

        foreach ($rows as $row) {
            $label = $row[0];
            $value = $row[1];
            $textWidth = mb_strwidth($label) + mb_strwidth($value);
            $dots = str_repeat('.', max(0, $columns - $textWidth - $spaceBuffer));

            // Truncate value if it doesn't fit
            if ($textWidth + $spaceBuffer > $columns) {
                $availableValueWidth = max(
                    0,
                    $columns - mb_strwidth($label) - mb_strwidth($dots) - $spaceBuffer
                );
                $value = (new Stringable($value))->limit($availableValueWidth - 1, '…')->value();
            }

            $this->line(sprintf(
                '  <fg=cyan>%s</> %s <fg=#6C7280>%s</>',
                $label,
                $dots,
                $value
            ));
        }
    }

    /**
     * Sub-sampling berimbang per kelas, masing-masing dibatasi $limit sampel
     * (0 = tanpa batas). Kedua kelas di-shuffle untuk menghindari bias urutan file.
     *
     * @param array<int, array{text: string, label: string}> $samples
     *
     * @return array<int, array{text: string, label: string}>
     */
    private function balanceSamples(array $samples, int $limit, int $seed): array
    {
        $spam = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'spam'));
        $not  = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'not_spam'));

        $spam = $limit > 0 ? array_slice($spam, 0, $limit) : $spam;

        mt_srand($seed);
        shuffle($spam);
        shuffle($not);

        $not = $limit > 0 ? array_slice($not, 0, min($limit, count($spam))) : array_slice($not, 0, count($spam));

        return array_merge($spam, $not);
    }

    /**
     * Baca dataset CSV, preprocess teks, buang duplikat & kosong — satu pass.
     *
     * @param callable(string):string|null $labelMapper Optional mapper untuk mapping label custom
     * @param array<string, true>         $seen         Referensi ke array global deduplication
     *
     * @return array<int, array{text: string, label: string}>
     */
    private function loadAndPrepareSamples(string $path, string $message, ?callable $labelMapper = null, array &$seen = []): array
    {
        $extractor = new CSV($path, header: true);
        $columns   = $this->detectColumns($extractor->header());
        $samples   = [];

        $bar = $this->output->createProgressBar(max(1, $this->countDataRows($path)));
        $bar->setFormat('  %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage($message);
        $bar->start();

        $preprocessor = new SpamPreprocessorTransformer();

        // Default label mapper (judol format: 0/1 -> not_spam/spam)
        $labelMapper ??= function (string $label): string {
            return match ($label) {
                '1'     => 'spam',
                '0'     => 'not_spam',
                default => $label,
            };
        };

        foreach ($extractor as $row) {
            $text  = trim((string) ($row[$columns['text']] ?? ''));
            $label = trim((string) ($row[$columns['label']] ?? ''));

            if ($text === '') {
                $bar->advance();
                continue;
            }

            $label = $labelMapper($label);

            // Validasi label SETELAH mapping (hanya spam/not_spam yang valid)
            $allowedLabels = ['spam', 'not_spam'];
            if (! in_array($label, $allowedLabels, true)) {
                $bar->advance();
                continue;
            }

            $text = $preprocessor->clean($text);

            if ($text !== '' && ! isset($seen[$text])) {
                $seen[$text] = true;
                $samples[]   = ['text' => $text, 'label' => $label];
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $samples;
    }

    /**
     * Bagi sampel menjadi set latih dan set uji (hold-out) dengan proporsi $split.
     * Menggunakan stratified split untuk mempertahankan distribusi kelas.
     * Menggunakan seed deterministik untuk hasil reproducible.
     *
     * @param array<int, array{text: string, label: string}> $samples
     *
     * @return array{0: array<int, array{text: string, label: string}>, 1: array<int, array{text: string, label: string}>}
     */
    private function splitSamples(array $samples, float $split, int $seed = 42): array
    {
        $spam = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'spam'));
        $not  = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'not_spam'));

        mt_srand($seed);
        shuffle($spam);
        mt_srand($seed + 1);
        shuffle($not);

        $spamTestCount = max(1, (int) floor(count($spam) * $split));
        $notTestCount  = max(1, (int) floor(count($not) * $split));

        $spamTrain = array_slice($spam, $spamTestCount);
        $spamTest  = array_slice($spam, 0, $spamTestCount);
        $notTrain  = array_slice($not, $notTestCount);
        $notTest   = array_slice($not, 0, $notTestCount);

        $train = array_merge($spamTrain, $notTrain);
        $test  = array_merge($spamTest, $notTest);

        // Shuffle hasil gabungan
        mt_srand($seed + 2);
        shuffle($train);
        mt_srand($seed + 3);
        shuffle($test);

        return [$train, $test];
    }

    /**
     * @param array<int, array{text: string, label: string}> $samples
     */
    private function describe(array $samples): string
    {
        $spam = collect($samples)->where('label', 'spam')->count();

        return number_format(count($samples)).' sampel ('.number_format($spam).' spam / '.number_format(count($samples) - $spam).' bukan spam)';
    }

    /**
     * Hitung jumlah baris data (tanpa header) dengan membaca file baris per
     * baris. Menghindari file(), yang memuat seluruh isi file ke memori
     * hanya untuk dihitung — boros untuk dataset besar.
     */
    private function countDataRows(string $path): int
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            return 0;
        }

        $lines = 0;
        while (fgets($handle) !== false) {
            $lines++;
        }
        fclose($handle);

        return max(0, $lines - 1);
    }

    private function downloadDataset(string $path, string $url): bool
    {
        $isSms       = $url === config('spam.sms_dataset_url');
        $datasetName = $isSms ? 'sms-spam-indonesia-classification' : 'judol-comment-classification';
        $downloaded  = false;

        $this->components->task("Mengunduh dataset publik ($datasetName)", function () use ($path, $url, &$downloaded): void {
            try {
                $response = Http::timeout(30)
                    ->withOptions(['sink' => $path])
                    ->get($url);
            } catch (\Throwable) {
                $downloaded = false;

                return;
            }

            $downloaded = $response->successful() && is_file($path) && filesize($path) > 0;
        });

        if (! $downloaded) {
            @unlink($path);
            $this->components->error("Gagal mengunduh dataset $datasetName. Periksa koneksi internet lalu coba lagi.");

            return false;
        }

        $this->renderTwoColumnTable([
            ["Dataset $datasetName terunduh", number_format($this->countDataRows($path)).' sampel'],
        ]);

        return true;
    }

    /**
     * Deteksi nama kolom teks dan label dari header.
     *
     * Format didukung: comment_id,author,comment_text,label (judol) dan
     * text,label. Bila tidak dikenal, kolom pertama dan kedua dipakai.
     *
     * @return array{text: string|int, label: string|int}
     */
    private function detectColumns(array $header): array
    {
        $normalized = array_map(static fn ($column): string => strtolower(trim((string) $column)), $header);
        $textKey    = array_search('comment_text', $normalized, true);

        if ($textKey === false) {
            $textKey = array_search('text', $normalized, true);
        }

        $labelKey = array_search('label', $normalized, true);

        if ($textKey === false || $labelKey === false) {
            return ['text' => $header[0] ?? 0, 'label' => $header[1] ?? 1];
        }

        return ['text' => $header[$textKey], 'label' => $header[$labelKey]];
    }

    /**
     * Hitung rate (precision/recall) dari numerator/denominator dan format
     * untuk tampilan. Mengembalikan [nilai_numerik, label_tampilan].
     * Bila denominator 0, rate tidak terdefinisi secara statistik — label
     * "N/A" (bukan "0 %", yang menyesatkan seolah model salah semua padahal
     * model tidak pernah menebak kelas tersebut sama sekali).
     *
     * @return array{0: float, 1: string}
     */
    private function formatRate(int $numerator, int $denominator): array
    {
        if ($denominator === 0) {
            return [0.0, 'N/A'];
        }

        $rate = $numerator / $denominator;

        return [$rate, round($rate * 100, 1).' %'];
    }

    /**
     * Cetak metrik evaluasi menggunakan Rubix CrossValidation Reports untuk threshold
     * produksi (0.85) tetap memakai probabilitySpamBatch + mapping
     * ke label sebelum generate report (custom threshold).
     *
     * @param array<int, array{text: string, label: string, source?: string}> $samples
     */
    private function printMetrics(string $title, SpamClassifierPipeline $classifier, array $samples, bool $productionThreshold = false): void
    {
        if ($samples === []) {
            $this->components->warn($title.' — tidak ada sampel');

            return;
        }

        $texts  = array_column($samples, 'text');
        $labels = array_column($samples, 'label');

        // Prediksi: default 0.5 via Pipeline::predict (idiomatik), produksi 0.85 via proba
        if ($productionThreshold) {
            $probas      = $classifier->probabilitySpamBatch($texts);
            $predictions = array_map(static fn (float $p): string => $p >= 0.85 ? 'spam' : 'not_spam', $probas);
        } else {
            $predictions = $classifier->predictBatch($texts);
        }

        // Best-practice Rubix: MulticlassBreakdown + ConfusionMatrix
        $breakdown = (new MulticlassBreakdown())->generate($predictions, $labels);
        $bArray    = $breakdown->toArray();
        $overall   = $bArray['overall'];
        $spamClass = $bArray['classes']['spam'] ?? null;

        // Accuracy dari overall (rata-rata), Precision/Recall/F1 dari kelas spam
        $accuracyLabel = round(($overall['accuracy'] ?? 0) * 100, 1).' %';

        if ($spamClass !== null) {
            $tp = (int) ($spamClass['true positives'] ?? 0);
            $fp = (int) ($spamClass['false positives'] ?? 0);
            $fn = (int) ($spamClass['false negatives'] ?? 0);
            $tn = (int) ($spamClass['true negatives'] ?? 0);

            [$precisionValue, $precisionLabel] = $this->formatRate($tp, $tp + $fp);
            [$recallValue, $recallLabel]       = $this->formatRate($tp, $tp + $fn);

            // Jika precision atau recall N/A (denominator 0), F1 juga N/A
            $f1Label = ($tp + $fp === 0 || $tp + $fn === 0)
                ? 'N/A'
                : round(2 * $precisionValue * $recallValue / max(1e-9, $precisionValue + $recallValue) * 100, 1).' %';
        } else {
            $tp = $fp = $tn = $fn = 0;
            $precisionLabel = $recallLabel = $f1Label = 'N/A';
        }

        $this->components->info($title);
        $this->renderTwoColumnTable([
            ['Accuracy', $accuracyLabel],
            ['Precision', $precisionLabel],
            ['Recall', $recallLabel],
            ['F1', $f1Label],
            ['TP / FP / TN / FN', "{$tp} / {$fp} / {$tn} / {$fn}"],
        ]);
        $this->newLine();
    }

    /**
     * Cetak metrik breakdown per domain (judol vs seed OpenSID vs SMS).
     * Berguna untuk melihat performa pada domain target produksi (OpenSID)
     * yang mungkin tersembunyi di angka agregat karena ketidakseimbangan jumlah sampel.
     *
     * @param array<int, array{text: string, label: string, source?: string}> $samples
     */
    private function printMetricsBreakdown(string $title, SpamClassifierPipeline $classifier, array $samples): void
    {
        $sources = ['judol', 'sms', 'opensid_seed'];
        $rows    = [];

        // Untuk detail Seed OpenSID
        $seedSpamTp = $seedSpamFp = $seedSpamFn = $seedSpamTn = 0;
        $seedNotSpamTp = $seedNotSpamFp = $seedNotSpamFn = $seedNotSpamTn = 0;
        $seedTotal = 0;
        $seedDomainPrecision = $seedDomainRecall = 0.0;
        $seedSpamPrecision = $seedSpamRecall = 0.0;
        $hasSeedPartition = false;

        foreach ($sources as $source) {
            $sourceSamples = array_values(array_filter($samples, fn (array $s): bool => ($s['source'] ?? '') === $source));
            if ($sourceSamples === []) {
                continue;
            }

            $sourceTexts  = array_column($sourceSamples, 'text');
            $sourceLabels = array_column($sourceSamples, 'label');
            $predictions  = $classifier->predictBatch($sourceTexts);

            $breakdown = (new MulticlassBreakdown())->generate($predictions, $sourceLabels);
            $bArray    = $breakdown->toArray();
            $overall   = $bArray['overall'];
            $classes   = $bArray['classes'];

            $spamMetrics    = $classes['spam'] ?? null;
            $notSpamMetrics = $classes['not_spam'] ?? $classes['not spam'] ?? null;

            $total    = count($sourceSamples);
            $accuracy = round(($overall['accuracy'] ?? 0) * 100, 1).' %';

            if ($spamMetrics !== null) {
                $spamTp = (int) $spamMetrics['true positives'];
                $spamFp = (int) $spamMetrics['false positives'];
                $spamFn = (int) $spamMetrics['false negatives'];
                $spamTn = (int) $spamMetrics['true negatives'];
                [$spamPrecision, $spamPrecisionLabel] = $this->formatRate($spamTp, $spamTp + $spamFp);
                [$spamRecall, $spamRecallLabel]       = $this->formatRate($spamTp, $spamTp + $spamFn);
                $domainPrecision = $spamTp + $spamFp > 0 ? (float) $spamTp / ($spamTp + $spamFp) : 0.0;
                $domainRecall    = $spamTp + $spamFn > 0 ? (float) $spamTp / ($spamTp + $spamFn) : 0.0;
                $domainF1Label   = ($spamTp + $spamFp === 0 || $spamTp + $spamFn === 0)
                    ? 'N/A'
                    : round(2 * $domainPrecision * $domainRecall / max(1e-9, $domainPrecision + $domainRecall) * 100, 1).' %';
            } else {
                $spamTp = $spamFp = $spamFn = $spamTn = 0;
                $spamPrecisionLabel = $spamRecallLabel = $domainF1Label = 'N/A';
                $domainPrecision = $domainRecall = 0.0;
            }

            if ($notSpamMetrics !== null) {
                $notSpamTp = (int) $notSpamMetrics['true positives'];
                $notSpamFp = (int) $notSpamMetrics['false positives'];
                $notSpamFn = (int) $notSpamMetrics['false negatives'];
                $notSpamTn = (int) $notSpamMetrics['true negatives'];
            } else {
                $notSpamTp = $notSpamFp = $notSpamFn = $notSpamTn = 0;
            }

            $sourceLabel = match ($source) {
                'judol'         => 'Judol Pool',
                'sms'           => 'SMS Dataset',
                'opensid_seed'  => 'Seed OpenSID',
            };
            $rows[] = [
                $sourceLabel,
                $accuracy,
                $spamPrecisionLabel,
                $spamRecallLabel,
                $domainF1Label,
                number_format($total),
            ];

            if ($source === 'opensid_seed') {
                $seedSpamTp          = $spamTp;
                $seedSpamFp          = $spamFp;
                $seedSpamFn          = $spamFn;
                $seedSpamTn          = $spamTn;
                $seedNotSpamTp       = $notSpamTp;
                $seedNotSpamFp       = $notSpamFp;
                $seedNotSpamFn       = $notSpamFn;
                $seedNotSpamTn       = $notSpamTn;
                $seedTotal           = $total;
                $seedDomainPrecision = $domainPrecision;
                $seedDomainRecall    = $domainRecall;
                $seedSpamPrecision   = $spamMetrics !== null ? ($spamTp / max(1, $spamTp + $spamFp)) : 0.0;
                $seedSpamRecall      = $spamMetrics !== null ? ($spamTp / max(1, $spamTp + $spamFn)) : 0.0;
                // Jika denominator 0, laporan Rubix memberi 0, tapi formatRate memberi N/A — sinkronkan
                if ($spamTp + $spamFp === 0) {
                    $seedSpamPrecision = 0.0;
                }
                if ($spamTp + $spamFn === 0) {
                    $seedSpamRecall = 0.0;
                }
                $hasSeedPartition    = true;
            }
        }

        if ($rows !== []) {
            $this->components->info($title);
            $this->renderTableResponsive(
                ['Domain', 'Accuracy', 'Precision', 'Recall', 'F1', 'Jumlah'],
                $rows
            );
            $this->newLine();
        }

        if ($hasSeedPartition) {
            $this->components->info('Detail kelas Seed OpenSID (false-positive pada not_spam = kasus mahal)');
            $this->renderTableResponsive(
                ['Kelas', 'Precision', 'Recall', 'TP/FP/FN/TN'],
                [
                    ['Spam', $this->formatRate($seedSpamTp, $seedSpamTp + $seedSpamFp)[1], $this->formatRate($seedSpamTp, $seedSpamTp + $seedSpamFn)[1], "{$seedSpamTp} / {$seedSpamFp} / {$seedSpamFn} / {$seedSpamTn}"],
                    ['Not Spam', $this->formatRate($seedNotSpamTp, $seedNotSpamTp + $seedNotSpamFp)[1], $this->formatRate($seedNotSpamTp, $seedNotSpamTp + $seedNotSpamFn)[1], "{$seedNotSpamTp} / {$seedNotSpamFp} / {$seedNotSpamFn} / {$seedNotSpamTn}"],
                ]
            );
            $this->newLine();

            $spamTotal    = $seedSpamTp + $seedSpamFn;
            $notSpamTotal = $seedNotSpamTp + $seedNotSpamFn;
            if ($spamTotal + $notSpamTotal !== $seedTotal) {
                throw new \RuntimeException("Invarian gagal: total sampel per kelas ({$spamTotal} + {$notSpamTotal}) != total domain {$seedTotal}");
            }
            if (abs($seedDomainPrecision - $seedSpamPrecision) > 1e-9 || abs($seedDomainRecall - $seedSpamRecall) > 1e-9) {
                throw new \RuntimeException("Invarian gagal: domain-level P/R (".round($seedDomainPrecision,4)."/".round($seedDomainRecall,4).") != Spam per-class P/R (".round($seedSpamPrecision,4)."/".round($seedSpamRecall,4).")");
            }
        }
    }

    /**
     * Cetak teks sampel hold-out yang salah diprediksi, per domain,
     * diurutkan dari yang paling "percaya diri tapi salah" (probabilitas
     * paling jauh dari batas keputusan 0.5). Berguna untuk diagnosa
     * kenapa recall/precision domain tertentu (terutama Seed OpenSID,
     * domain produksi) rendah — angka agregat saja tidak menunjukkan pola
     * linguistik di balik kesalahannya.
     *
     * @param array<int, array{text: string, label: string, source?: string}> $samples
     */
    private function printMisclassified(SpamClassifierPipeline $classifier, array $samples, int $limit): void
    {
        if ($samples === []) {
            return;
        }

        $texts  = array_column($samples, 'text');
        $probas = $classifier->probabilitySpamBatch($texts);

        // Seed OpenSID paling penting (domain produksi) — ditampilkan duluan.
        $sources = ['opensid_seed', 'judol', 'sms'];

        foreach ($sources as $source) {
            $misclassified = [];

            foreach ($samples as $index => $sample) {
                if (($sample['source'] ?? '') !== $source) {
                    continue;
                }

                $proba         = $probas[$index];
                $predictedSpam = $proba >= 0.5;
                $actualSpam    = $sample['label'] === 'spam';

                if ($predictedSpam === $actualSpam) {
                    continue;
                }

                $misclassified[] = [
                    'label'     => $sample['label'],
                    'predicted' => $predictedSpam ? 'spam' : 'not_spam',
                    'proba'     => $proba,
                    'text'      => $sample['text'],
                ];
            }

            if ($misclassified === []) {
                continue;
            }

            // Paling percaya diri tapi salah dulu (jarak dari 0.5 terbesar).
            usort($misclassified, static fn (array $a, array $b): int => abs($b['proba'] - 0.5) <=> abs($a['proba'] - 0.5));

            if ($limit > 0) {
                $misclassified = array_slice($misclassified, 0, $limit);
            }

            $sourceLabel = match ($source) {
                'judol'        => 'Judol Pool',
                'sms'          => 'SMS Dataset',
                'opensid_seed' => 'Seed OpenSID',
            };

            $this->components->warn("Salah prediksi — {$sourceLabel} (".count($misclassified).' ditampilkan)');
            $this->renderTableResponsive(
                ['Label asli', 'Prediksi', 'Prob. spam', 'Teks'],
                array_map(fn (array $row): array => [
                    $row['label'],
                    $row['predicted'],
                    round($row['proba'] * 100, 1).' %',
                    $this->truncateForTerminal($row['text']),
                ], $misclassified)
            );
            $this->newLine();
        }
    }

    /**
     * Render table dengan truncate kolom agar muat di terminal.
     * Menggunakan lebar terminal aktual (Prompt::terminal()->cols()).
     */
    private function renderTableResponsive(array $headers, array $rows): void
    {
        if ($rows === []) {
            return;
        }

        $cols = Prompt::terminal()->cols();
        $colCount = count($headers);

        // Hitung lebar minimum per kolom (header + padding)
        $minWidths = [];
        foreach ($headers as $i => $h) {
            $minWidths[$i] = mb_strwidth($h) + 2;
        }

        // Hitung lebar konten maksimum per kolom
        $maxWidths = $minWidths;
        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $w = mb_strwidth((string) $cell);
                if ($w > $maxWidths[$i]) {
                    $maxWidths[$i] = $w;
                }
            }
        }

        // Jika total melebihi lebar terminal, kurangi dari kolom paling lebar
        $totalWidth = array_sum($maxWidths) + ($colCount - 1) * 3; // 3 = " | "
        if ($totalWidth > $cols && $colCount > 0) {
            $excess = $totalWidth - $cols;
            // Kurangi dari kolom terakhir (biasanya kolom teks terpanjang)
            $lastIdx = $colCount - 1;
            $maxWidths[$lastIdx] = max($minWidths[$lastIdx], $maxWidths[$lastIdx] - $excess);
        }

        // Render header
        $headerLine = '';
        foreach ($headers as $i => $h) {
            $headerLine .= str_pad($h, $maxWidths[$i], ' ', STR_PAD_RIGHT);
            if ($i < $colCount - 1) {
                $headerLine .= ' | ';
            }
        }
        $this->line('<fg=cyan;options=bold>'.$headerLine.'</>');
        $this->line(str_repeat('─', min($cols, mb_strwidth($headerLine))));

        // Render rows
        foreach ($rows as $row) {
            $line = '';
            foreach ($row as $i => $cell) {
                $cellStr = (string) $cell;
                $cellStr = $this->truncateToWidth($cellStr, $maxWidths[$i]);
                $line .= str_pad($cellStr, $maxWidths[$i], ' ', STR_PAD_RIGHT);
                if ($i < $colCount - 1) {
                    $line .= ' | ';
                }
            }
            $this->line($line);
        }
    }

    /**
     * Truncate string agar muat di lebar tertentu, tambahkan … jika dipotong.
     */
    private function truncateToWidth(string $text, int $width): string
    {
        if (mb_strwidth($text) <= $width) {
            return $text;
        }
        if ($width <= 3) {
            return '…';
        }

        return mb_strimwidth($text, 0, $width - 1, '…');
    }

    /**
     * Truncate text untuk kolom "Teks" di printMisclassified.
     * Gunakan sisa lebar terminal setelah kolom lain.
     */
    private function truncateForTerminal(string $text): string
    {
        $cols = Prompt::terminal()->cols();
        // Estimasi lebar kolom lain: Label(12) + Prediksi(10) + Prob(12) + separator(9) = ~43
        $otherColsWidth = 43;
        $availableWidth = max(20, $cols - $otherColsWidth);

        return $this->truncateToWidth($text, $availableWidth);
    }

    /**
     * Cetak token vocabulary yang mengandung deretan >=5 digit berurutan —
     * indikasi token nomor HP/NIK. Dipakai untuk memverifikasi apakah
     * mempertahankan digit literal (bukan dinormalisasi ke huruf) benar-benar
     * membuat pola nomor identitas masuk vocabulary, atau tersaring habis
     * oleh minDocumentCount karena nomor jarang identik antar dokumen.
     */
    private function dumpNumericVocabulary(SpamClassifierPipeline $classifier): void
    {
        $numericTokens = array_values(array_filter(
            $classifier->vocabulary(),
            static fn (string $token): bool => (bool) preg_match('/\d{5,}/', $token),
        ));

        $this->components->info('Token vocabulary dengan >=5 digit berurutan');

        if ($numericTokens === []) {
            $this->components->warn('Tidak ada — token numerik panjang tersaring habis dari vocabulary (kemungkinan oleh minDocumentCount).');

            return;
        }

        $this->table(['Token'], array_map(static fn (string $t): array => [$t], array_slice($numericTokens, 0, 50)));
        $this->components->twoColumnDetail('Total token numerik >=5 digit', (string) count($numericTokens));
    }

    /**
     * Jalankan training berulang untuk beberapa nilai --seed, khusus untuk
     * memvalidasi apakah suatu perubahan (mis. L33T_DIGIT_MAP) konsisten
     * menang di banyak pembagian data, bukan menang kebetulan pada satu
     * split. Tidak menyimpan model produksi — murni evaluasi.
     */
    private function runMultiSeedValidation(string $datasetPath, string $smsDatasetPath, string $seedDatasetPath, string $classifierName): int
    {
        $seeds = array_filter(array_map('trim', explode(',', (string) $this->option('validate-seeds'))));

        if ($seeds === []) {
            $this->components->error('--validate-seeds butuh minimal satu nilai, mis. --validate-seeds=1,2,3');

            return self::FAILURE;
        }

        $rows = [];

        foreach ($seeds as $seed) {
            $this->components->info("Seed {$seed}");

            $metrics = $this->trainAndEvaluateForSeed(
                $datasetPath,
                $smsDatasetPath,
                $seedDatasetPath,
                $classifierName,
                (int) $seed,
            );

            $rows[] = [
                $seed,
                round($metrics['accuracy'] * 100, 1).' %',
                round($metrics['precision'] * 100, 1).' %',
                round($metrics['recall'] * 100, 1).' %',
                round($metrics['f1'] * 100, 1).' %',
            ];
        }

        $this->newLine();
        $this->components->info('Ringkasan Seed OpenSID F1 per seed (validasi multi-seed)');
        $this->table(['Seed', 'Accuracy', 'Precision', 'Recall', 'F1'], $rows);

        $f1Values = array_map(static fn (string $v): float => (float) rtrim($v, ' %'), array_column($rows, 4));
        $avgF1 = array_sum($f1Values) / count($f1Values);
        $this->components->twoColumnDetail('Rata-rata F1', round($avgF1, 1).' %');

        return self::SUCCESS;
    }

    /**
     * Training + evaluasi untuk satu seed, mengembalikan metrik Seed OpenSID.
     * Reuse method-method yang sudah ada (load, split, train, evaluate).
     *
     * @return array{accuracy: float, precision: float, recall: float, f1: float}
     */
    private function trainAndEvaluateForSeed(
        string $datasetPath,
        string $smsDatasetPath,
        string $seedDatasetPath,
        string $classifierName,
        int $seedValue
    ): array {
        $split = max(0.05, min(0.5, (float) $this->option('split')));
        $weight = max(1, (int) $this->option('seed-weight'));
        $smsWeight = max(1, (int) $this->option('sms-weight'));

        // Load data (reuse existing logic with global dedup)
        $seen = [];

        $judol = $this->loadAndPrepareSamples($datasetPath, 'Memuat dataset judol', seen: $seen);
        $judol = $this->balanceSamples($judol, (int) $this->option('judol-per-class'), $seedValue);

        $sms = $this->loadAndPrepareSamples($smsDatasetPath, 'Memuat dataset SMS', function (string $label): string {
            return match ($label) {
                '0'     => 'not_spam',
                '1'     => 'spam',
                '2'     => 'spam',
                default => $label,
            };
        }, seen: $seen);
        $sms = $this->balanceSamples($sms, (int) $this->option('sms-per-class'), $seedValue);

        $seed = $this->loadAndPrepareSamples($seedDatasetPath, 'Memuat seed OpenSID', seen: $seen);

        // Stratified split with the given seed
        [$judolTrain, $judolTest] = $this->splitSamples($judol, $split, $seedValue);
        [$smsTrain, $smsTest]     = $this->splitSamples($sms, $split, $seedValue);
        [$seedTrain, $seedTest]   = $this->splitSamples($seed, $split, $seedValue);

        // Add source identifiers
        $judolTest = array_map(static fn (array $s): array => ['source' => 'judol'] + $s, $judolTest);
        $smsTest   = array_map(static fn (array $s): array => ['source' => 'sms'] + $s, $smsTest);
        $seedTest  = array_map(static fn (array $s): array => ['source' => 'opensid_seed'] + $s, $seedTest);

        // Replicate only on train
        $seedTrainReplicated = array_merge(...array_fill(0, $weight, $seedTrain));
        $smsTrainReplicated = array_merge(...array_fill(0, $smsWeight, $smsTrain));

        $trainSamples = array_merge($judolTrain, $seedTrainReplicated, $smsTrainReplicated);
        $testSamples  = array_merge($judolTest, $smsTest, $seedTest);

        // Shuffle with deterministic seeds
        mt_srand($seedValue);
        shuffle($trainSamples);
        mt_srand($seedValue + 1);
        shuffle($testSamples);

        // Train classifier
        $classifier = SpamClassifierPipeline::create($classifierName);
        $classifier->train($trainSamples);

        // Evaluate on test set - extract Seed OpenSID metrics
        $seedTestSamples = array_values(array_filter($testSamples, fn (array $s): bool => $s['source'] === 'opensid_seed'));

        if ($seedTestSamples === []) {
            return [
                'accuracy'  => 0.0,
                'precision' => 0.0,
                'recall'    => 0.0,
                'f1'        => 0.0,
            ];
        }

        $texts       = array_column($seedTestSamples, 'text');
        $labels      = array_column($seedTestSamples, 'label');
        $predictions = $classifier->predictBatch($texts);

        $breakdown = (new MulticlassBreakdown())->generate($predictions, $labels);
        $bArray    = $breakdown->toArray();
        $overall   = $bArray['overall'];
        $spamClass = $bArray['classes']['spam'] ?? null;

        $accuracy  = (float) ($overall['accuracy'] ?? 0);
        $precision = $spamClass !== null ? (float) ($spamClass['precision'] ?? 0) : 0.0;
        $recall    = $spamClass !== null ? (float) ($spamClass['recall'] ?? 0) : 0.0;
        $f1        = $spamClass !== null ? (float) ($spamClass['f1 score'] ?? 0) : 0.0;

        return [
            'accuracy'  => $accuracy,
            'precision' => $precision,
            'recall'    => $recall,
            'f1'        => $f1,
        ];
    }
}
