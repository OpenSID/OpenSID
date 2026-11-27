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

namespace App\Console\Commands\Dev;

use App\Services\SpamDetection\ML\SpamClassifierPipeline;
use App\Services\SpamDetection\ML\Transformers\SpamPreprocessorTransformer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Eksperimen A/B n-gram: bandingkan (1,1) vs (1,2) pada dataset yang sama.
 *
 * ALAT EKSPLORASI (bukan untuk production) — sengaja dipertahankan di codebase
 * supaya keputusan n-gram bisa diverifikasi ulang kapan saja, terutama
 * kalau dataset judol referensi di-update di masa depan.
 *
 * Menggunakan SpamClassifierPipeline — class yang sama persis dipakai
 * `opensid:spam-train` — dengan rentang n-gram dikustomisasi lewat parameter
 * create(), BUKAN implementasi pipeline terpisah. Ini memastikan hasil
 * eksperimen selalu mencerminkan perilaku produksi terkini (estimator,
 * batching, progress callback) tanpa risiko divergen diam-diam.
 *
 * Menggunakan pipeline preprocessing, split, dan replikasi seed yang sudah
 * diperbaiki (split dulu unik, replikasi seed hanya train) — sama persis
 * dengan command production `opensid:spam-train`.
 * Juga memuat SMS dataset sebagai source tambahan (mirip production).
 *
 * Contoh:
 *   php artisan opensid:spam-ngram-experiment
 *   php artisan opensid:spam-ngram-experiment --seed-weight=10
 */
class SpamNgramExperimentCommand extends Command
{
    protected $signature = 'opensid:spam-ngram-experiment
        {--dataset= : Path dataset CSV (default: config/spam.php)}
        {--sms-dataset= : Path dataset SMS CSV (default: config/spam.php)}
        {--judol-per-class=2000 : Maksimum sampel judol per kelas (0 = semua)}
        {--sms-per-class=500 : Maksimum sampel SMS per kelas (0 = semua)}
        {--seed-weight=10 : Pengulangan sampel seed OpenSID (samakan dengan default opensid:spam-train)}
        {--seed=42 : Seed deterministik untuk pengacakan data}';

    protected $description = 'Eksperimen A/B n-gram: bandingkan unigram (1,1) vs unigram+bigram (1,2) pada dataset judol + SMS + seed OpenSID (dengan balancing, split dulu unik, replikasi seed hanya train).';

    public function handle(): int
    {
        $datasetPath     = (string) ($this->option('dataset') ?: config('spam.dataset_path'));
        $smsDatasetPath  = (string) ($this->option('sms-dataset') ?: config('spam.sms_dataset_path'));
        $seedPath        = (string) config('spam.seed_path');
        $judolPerClass   = (int) $this->option('judol-per-class');
        $smsPerClass     = (int) $this->option('sms-per-class');
        $seedValue       = (int) $this->option('seed');

        if (! File::exists($datasetPath)) {
            $this->components->error('Dataset tidak ditemukan: '.$datasetPath);
            $this->line('Jalankan: php artisan opensid:spam-train --force (akan mengunduh otomatis)');

            return self::FAILURE;
        }

        if (! File::exists($smsDatasetPath)) {
            $this->components->error('Dataset SMS tidak ditemukan: '.$smsDatasetPath);

            return self::FAILURE;
        }

        if (! File::exists($seedPath)) {
            $this->components->error('Seed tidak ditemukan: '.$seedPath);

            return self::FAILURE;
        }

        $preprocessor = new SpamPreprocessorTransformer();

        $this->components->info('Memuat dataset...');
        $judol = $this->loadAndPrepareSamples($datasetPath, $preprocessor, 'Memuat dataset judol');
        $sms   = $this->loadAndPrepareSamples($smsDatasetPath, $preprocessor, 'Memuat dataset SMS', function (string $label): string {
            return match ($label) {
                '0' => 'not_spam',
                '1' => 'spam',
                '2' => 'spam',
                default => $label,
            };
        });
        $seed  = $this->loadAndPrepareSamples($seedPath, $preprocessor, 'Memuat seed OpenSID');

        // Balance judol per kelas
        $judol = $this->balanceSamples($judol, $judolPerClass, $seedValue);
        $sms   = $this->balanceSamples($sms, $smsPerClass, $seedValue);

        $this->components->twoColumnDetail('Judol (setelah balancing)', $this->describe($judol));
        $this->components->twoColumnDetail('SMS (setelah balancing)', $this->describe($sms));
        $this->components->twoColumnDetail('Seed OpenSID', $this->describe($seed).' ('.number_format(count($seed)).' unik)');

        // Split dulu (unik), baru replikasi seed hanya train
        $split = 0.2;
        [$judolTrain, $judolTest] = $this->splitSamples($judol, $split, $seedValue);
        [$smsTrain, $smsTest]     = $this->splitSamples($sms, $split, $seedValue);
        [$seedTrain, $seedTest]   = $this->splitSamples($seed, $split, $seedValue);

        // Replikasi seed hanya train (weight sama dengan default produksi opensid:spam-train)
        $weight = max(1, (int) $this->option('seed-weight'));
        $seedTrainReplicated = array_merge(...array_fill(0, $weight, $seedTrain));

        $trainSamples = array_merge($judolTrain, $smsTrain, $seedTrainReplicated);
        $testSamples  = array_merge($judolTest, $smsTest, $seedTest);

        mt_srand($seedValue);
        shuffle($trainSamples);
        mt_srand($seedValue + 1);
        shuffle($testSamples);

        $this->components->twoColumnDetail('Total train', $this->describe($trainSamples));
        $this->components->twoColumnDetail('Total test', $this->describe($testSamples));
        $this->newLine();

        // Eksperimen n-gram
        $ngramConfigs = [
            ['min' => 1, 'max' => 1, 'label' => 'Unigram (1,1)'],
            ['min' => 1, 'max' => 2, 'label' => 'Unigram+Bigram (1,2)'],
        ];

        $results = [];

        foreach ($ngramConfigs as $config) {
            $this->components->info('--- '.$config['label'].' ---');

            $classifier = SpamClassifierPipeline::create('gaussian_nb', ngramMin: $config['min'], ngramMax: $config['max']);
            $classifier->train($trainSamples);

            $metrics = $this->evaluate($classifier, $testSamples);
            $results[$config['label']] = $metrics;

            $this->components->twoColumnDetail('Accuracy',  round($metrics['accuracy'] * 100, 1).' %');
            $this->components->twoColumnDetail('Precision', round($metrics['precision'] * 100, 1).' %');
            $this->components->twoColumnDetail('Recall',    round($metrics['recall'] * 100, 1).' %');
            $this->components->twoColumnDetail('F1',        round($metrics['f1'] * 100, 1).' %');
            $this->newLine();
        }

        // Ringkasan perbandingan
        $this->components->info('=== Ringkasan Perbandingan ===');
        $this->table(
            ['Konfigurasi', 'Accuracy', 'Precision', 'Recall', 'F1'],
            array_map(
                fn ($label, $m) => [
                    $label,
                    round($m['accuracy'] * 100, 1).' %',
                    round($m['precision'] * 100, 1).' %',
                    round($m['recall'] * 100, 1).' %',
                    round($m['f1'] * 100, 1).' %',
                ],
                array_keys($results),
                $results
            ),
        );

        // Keputusan
        $bestF1 = max(array_column($results, 'f1'));
        $bestLabel = array_search($bestF1, array_column($results, 'f1'));
        $this->components->info("Konfigurasi terbaik (F1): {$bestLabel} (".round($bestF1 * 100, 1).' %)');

        return self::SUCCESS;
    }

    private function loadAndPrepareSamples(string $path, SpamPreprocessorTransformer $preprocessor, string $message, ?callable $labelMapper = null): array
    {
        $extractor = new \Rubix\ML\Extractors\CSV($path, header: true);
        $columns   = $this->detectColumns($extractor->header());
        $samples   = [];

        $bar = $this->output->createProgressBar(max(1, count(file($path)) - 1));
        $bar->setFormat('  %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->setMessage($message);
        $bar->start();

        $seen = [];

        // Default label mapper (judol format: 0/1 -> not_spam/spam)
        $labelMapper ??= function (string $label): string {
            return match ($label) {
                '1' => 'spam',
                '0' => 'not_spam',
                default => $label,
            };
        };

        foreach ($extractor as $row) {
            $text  = trim((string) ($row[$columns['text']] ?? ''));
            $label = trim((string) ($row[$columns['label']] ?? ''));

            $allowedLabels = ['0', '1', '2', 'spam', 'not_spam'];
            if ($text === '' || ! in_array($label, $allowedLabels, true)) {
                $bar->advance();
                continue;
            }

            $label = $labelMapper($label);

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

    private function balanceSamples(array $samples, int $limit, int $seed): array
    {
        $spam = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'spam'));
        $not  = array_values(array_filter($samples, static fn (array $s): bool => $s['label'] === 'not_spam'));

        $spam = $limit > 0 ? array_slice($spam, 0, $limit) : $spam;

        mt_srand($seed);
        shuffle($not);

        $not = $limit > 0 ? array_slice($not, 0, min($limit, count($spam))) : array_slice($not, 0, count($spam));

        return array_merge($spam, $not);
    }

    /**
     * Split samples deterministically into [train, test] by ratio.
     *
     * @param list<array{text: string, label: string}> $samples
     *
     * @return array{0: list<array{text: string, label: string}>, 1: list<array{text: string, label: string}>}
     */
    private function splitSamples(array $samples, float $split, int $seed): array
    {
        mt_srand($seed);
        shuffle($samples);
        $testCount = max(1, (int) floor(count($samples) * $split));

        return [
            array_slice($samples, $testCount),
            array_slice($samples, 0, $testCount),
        ];
    }

    private function evaluate(SpamClassifierPipeline $classifier, array $samples): array
    {
        $texts  = array_column($samples, 'text');
        $probas = $classifier->probabilitySpamBatch($texts);

        $tp = $fp = $tn = $fn = 0;
        foreach ($samples as $i => $sample) {
            $predicted = $probas[$i] >= 0.5;
            $actual    = $sample['label'] === 'spam';

            if ($actual && $predicted) $tp++;
            elseif (!$actual && $predicted) $fp++;
            elseif (!$actual) $tn++; // $predicted is necessarily false past the arms above
            else $fn++;
        }

        $accuracy  = ($tp + $tn) / max(1, count($samples));
        $precision = $tp / max(1, $tp + $fp);
        $recall    = $tp / max(1, $tp + $fn);
        $f1        = 2 * $precision * $recall / max(1e-9, $precision + $recall);

        return compact('accuracy', 'precision', 'recall', 'f1', 'tp', 'fp', 'tn', 'fn');
    }

    private function detectColumns(array $header): array
    {
        $normalized = array_map(static fn ($c): string => strtolower(trim((string) $c)), $header);
        $textKey = array_search('comment_text', $normalized, true);

        if ($textKey === false) {
            $textKey = array_search('text', $normalized, true);
        }

        $labelKey = array_search('label', $normalized, true);

        if ($textKey === false || $labelKey === false) {
            return ['text' => $header[0] ?? 0, 'label' => $header[1] ?? 1];
        }

        return ['text' => $header[$textKey], 'label' => $header[$labelKey]];
    }

    private function describe(array $samples): string
    {
        $spam = collect($samples)->where('label', 'spam')->count();
        return number_format(count($samples)).' sampel ('.number_format($spam).' spam / '.number_format(count($samples) - $spam).' bukan spam)';
    }
}