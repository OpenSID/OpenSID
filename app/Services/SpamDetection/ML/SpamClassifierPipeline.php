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

namespace App\Services\SpamDetection\ML;

use App\Services\SpamDetection\ML\Transformers\SpamPreprocessorTransformer;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Classifiers\LogisticRegression;
use Rubix\ML\Classifiers\SVC;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Pipeline;
use Rubix\ML\Serializers\RBX;
use Rubix\ML\Tokenizers\NGram;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\WordCountVectorizer;
use RuntimeException;

/**
 * Text classification pipeline for spam detection.
 *
 * TF-IDF n-gram pipeline with a serialized preprocessor,
 * following the RubixML Sentiment example:
 * Sentiment uses [TextNormalizer → WordCountVectorizer → TfIdfTransformer → ZScaleStandardizer] → MLP.
 * This implementation uses [SpamPreprocessorTransformer → WordCountVectorizer → TfIdfTransformer] → Estimator.
 *
 * Persistence is handled via PersistentModel with Filesystem and RBX serializer (.rbx).
 */
final class SpamClassifierPipeline
{
    /** Maximum n-gram vocabulary size. */
    public const MAX_VOCABULARY = 5000;

    /** Minimum n-gram size. */
    public const NGRAM_MIN = 1;

    /** Maximum n-gram size. */
    public const NGRAM_MAX = 2;

    private readonly SpamPreprocessorTransformer $preprocessor;

    private ?Pipeline $pipeline = null;

    private ?WordCountVectorizer $vectorizer = null;

    /** @var callable(): object */
    private $estimatorFactory;

    public function __construct(
        ?callable $estimatorFactory = null,
        private readonly int $ngramMin = self::NGRAM_MIN,
        private readonly int $ngramMax = self::NGRAM_MAX,
    ) {
        $this->preprocessor     = new SpamPreprocessorTransformer();
        $this->estimatorFactory = $estimatorFactory ?? static fn () => new GaussianNB();
    }

    /**
     * Create a pipeline with the given classifier.
     *
     * @throws RuntimeException
     */
    public static function create(string $classifierName, int $ngramMin = self::NGRAM_MIN, int $ngramMax = self::NGRAM_MAX): self
    {
        $estimatorFactory = match ($classifierName) {
            'gaussian_nb'         => static fn () => new GaussianNB(),
            'logistic_regression' => static fn () => new LogisticRegression(),
            'svm'                 => extension_loaded('svm') ? static fn () => new SVC() : throw new RuntimeException('SVM memerlukan ekstensi PHP "svm"'),
            default               => throw new RuntimeException("Classifier tidak dikenal: {$classifierName}"),
        };

        return new self($estimatorFactory, $ngramMin, $ngramMax);
    }

    /**
     * Train the pipeline.
     *
     * Raw text is passed to the pipeline; the preprocessor transformer
     * handles normalization internally. Progress is reported per-stage
     * (stage-based) agar Laravel console ProgressBar dapat advance
     * secara realtime, bukan hanya 1/1 blocking.
     *
     * Tahapan (total 5):
     * 1/5 Membersihkan teks & validasi
     * 2/5 Membangun vocabulary (WordCountVectorizer fit)
     * 3/5 Menghitung TF-IDF (TfIdfTransformer fit)
     * 4/5 Melatih classifier (Estimator train)
     * 5/5 Menyelesaikan
     *
     * @param array<int, array{text: string, label: string}> $samples
     * @param callable(int $step, int $total, string $message): void|null $onProgress
     */
    public function train(array $samples, ?callable $onProgress = null): void
    {
        $total = 5;
        $stage = 0;

        $emit = static function (string $message) use (&$stage, $total, $onProgress): void {
            $stage++;

            if ($onProgress !== null) {
                $onProgress($stage, $total, $message);
            }
        };

        $emit('Membersihkan teks');

        $texts  = [];
        $labels = [];

        foreach ($samples as $sample) {
            if ($sample['label'] !== 'spam' && $sample['label'] !== 'not_spam') {
                throw new RuntimeException("Label tidak dikenal: {$sample['label']} (harus spam|not_spam)");
            }

            $raw     = $sample['text'];
            $cleaned = $this->preprocessor->clean($raw);

            if ($cleaned === '') {
                continue;
            }

            $texts[]  = $raw;
            $labels[] = $sample['label'];
        }

        $classCounts = array_count_values($labels);

        if (($classCounts['spam'] ?? 0) === 0 || ($classCounts['not_spam'] ?? 0) === 0) {
            throw new RuntimeException('Dataset harus memuat minimal 1 sampel spam dan 1 sampel not_spam.');
        }

        // Rubix samples are feature vectors: wrap each text in a 1-element array,
        // same shape the inference path builds (see probabilitySpamBatch/predictBatch).
        $samples = array_map(static fn (string $t): array => [$t], $texts);

        $dataset = new Labeled($samples, $labels);

        $vectorizer = new WordCountVectorizer(
            maxVocabularySize: self::MAX_VOCABULARY,
            minDocumentCount: 2,
            maxDocumentRatio: 0.8,
            tokenizer: new NGram($this->ngramMin, $this->ngramMax),
        );

        $this->vectorizer = $vectorizer;

        $tfidf = new TfIdfTransformer();

        $estimator = ($this->estimatorFactory)();

        $this->pipeline = new Pipeline(
            [$this->preprocessor, $vectorizer, $tfidf],
            $estimator
        );

        // Pipeline::train() asli melakukan loop fit+apply per transformer
        // secara atomik tanpa hook. Untuk progress realtime, kita bongkar
        // menjadi tahap manual dengan emit sebelum operasi berat.
        $emit('Membangun vocabulary');

        // Preprocessor tidak Stateful, hanya transform. Vectorizer adalah
        // tahap paling berat (tokenisasi + pembangunan vocab).
        $dataset->apply($this->preprocessor);
        $vectorizer->fit($dataset);
        $dataset->apply($vectorizer);

        $emit('Menghitung TF-IDF');
        $tfidf->fit($dataset);
        $dataset->apply($tfidf);

        $emit('Melatih classifier');
        $estimator->train($dataset);

        $emit('Menyelesaikan');
    }

    /**
     * Get spam probability for a single text.
     */
    public function probabilitySpam(string $content): float
    {
        return $this->probabilitySpamBatch([$content])[0] ?? 0.0;
    }

    /**
     * Get spam probabilities for multiple texts.
     *
     * @param list<string> $contents
     *
     * @return list<float>
     */
    public function probabilitySpamBatch(array $contents): array
    {
        $this->ensureTrained();

        if ($contents === []) {
            return [];
        }

        [$nonEmpty, $indexes, $results] = $this->prepareBatch($contents, 0.0);

        if ($nonEmpty === []) {
            return $results;
        }

        $samples = array_map(static fn (string $t): array => [$t], $nonEmpty);
        $probas  = $this->pipeline->proba(new Unlabeled($samples));

        foreach ($indexes as $i => $index) {
            $results[$index] = (float) ($probas[$i]['spam'] ?? 0.0);
        }

        return $results;
    }

    /**
     * Predict label for a single text.
     */
    public function predict(string $content): string
    {
        return $this->predictBatch([$content])[0] ?? 'not_spam';
    }

    /**
     * Predict labels for multiple texts.
     *
     * @param list<string> $contents
     *
     * @return list<string>
     */
    public function predictBatch(array $contents): array
    {
        $this->ensureTrained();

        if ($contents === []) {
            return [];
        }

        [$nonEmpty, $indexes, $results] = $this->prepareBatch($contents, 'not_spam');

        if ($nonEmpty === []) {
            return $results;
        }

        $samples = array_map(static fn (string $t): array => [$t], $nonEmpty);
        $preds   = $this->pipeline->predict(new Unlabeled($samples));

        foreach ($indexes as $i => $index) {
            $results[$index] = $preds[$i] === 'spam' ? 'spam' : 'not_spam';
        }

        return $results;
    }

    /**
     * Prepare batch for inference.
     *
     * Filters empty texts after normalization and preserves original indexes.
     *
     * @param list<string> $contents
     *
     * @return array{0: list<string>, 1: list<int>, 2: array<int,mixed>}
     */
    private function prepareBatch(array $contents, mixed $emptyValue): array
    {
        $results  = array_fill(0, count($contents), $emptyValue);
        $nonEmpty = [];
        $indexes  = [];

        foreach ($contents as $index => $content) {
            if ($this->preprocessor->clean($content) !== '') {
                $nonEmpty[] = $content;
                $indexes[]  = $index;
            }
        }

        return [$nonEmpty, $indexes, $results];
    }

    private function ensureTrained(): void
    {
        if ($this->pipeline === null) {
            throw new RuntimeException('Classifier belum dilatih.');
        }
    }

    public function save(string $path): void
    {
        if ($this->pipeline === null) {
            throw new RuntimeException('Classifier belum dilatih.');
        }

        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        (new PersistentModel($this->pipeline, new Filesystem($path), new RBX()))->save();
    }

    public static function load(string $path): self
    {
        if (! is_file($path)) {
            throw new RuntimeException("Model tidak ditemukan: {$path}");
        }

        try {
            $persistent = PersistentModel::load(new Filesystem($path), new RBX());
        } catch (\Throwable $e) {
            throw new RuntimeException("Model tidak valid: {$path} ({$e->getMessage()})");
        }

        $base = $persistent->base();

        if (! $base instanceof Pipeline) {
            throw new RuntimeException("Model tidak valid: {$path} (bukan Rubix ML Pipeline)");
        }

        $classifier           = new self();
        $classifier->pipeline = $base;

        foreach ($base->params()['transformers'] ?? [] as $transformer) {
            if ($transformer instanceof WordCountVectorizer) {
                $classifier->vectorizer = $transformer;
                break;
            }
        }

        return $classifier;
    }

    /**
     * Get fitted vocabulary for debugging.
     *
     * @return list<string>
     */
    public function vocabulary(): array
    {
        if ($this->vectorizer === null) {
            throw new RuntimeException('Classifier belum dilatih (vectorizer tidak tersedia).');
        }

        $vocabularies = $this->vectorizer->vocabularies();
        if ($vocabularies === null) {
            return [];
        }

        $firstColumnVocab = array_values($vocabularies)[0] ?? [];

        return array_values($firstColumnVocab);
    }
}
