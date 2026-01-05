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

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QueryDetector
{
    public Collection $queries;

    /**
     * @var array
     */
    private $excepts = [
        \App\Models\Pamong::class   => ['penduduk'],
        \App\Models\Keluarga::class => ['wilayah'],
    ];

    public function __construct()
    {
        $this->resetQueries();
    }

    public function boot()
    {
        if (! $this->isEnabled()) {
            return;
        }

        DB::listen(function ($query) {
            $backtrace = collect(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 30));

            $this->logQuery($query, $backtrace);

            $this->output();
        });
    }

    public function isEnabled(): bool
    {
        return ENVIRONMENT === 'development';
    }

    public function parseTrace($index, array $trace)
    {
        $frame = (object) [
            'index'    => $index,
            'name'     => null,
            'fullPath' => null,
            'line'     => $trace['line'] ?? '?',
            'class'    => $trace['class'] ?? null,
            'function' => $trace['function'] ?? null,
            'type'     => $trace['type'] ?? '->',
        ];

        if (isset($trace['class'], $trace['file'])
            && ! $this->fileIsInExcludedPath($trace['file'])
        ) {
            $frame->name     = $this->normalizeFilename($trace['file']);
            $frame->fullPath = str_replace('/', '\\', $trace['file']);

            return $frame;
        }

        return false;
    }

    /**
     * Add exceptions for specific model-relation combinations
     */
    public function except(string $model, array $relations): self
    {
        $this->excepts[$model] = $relations;

        return $this;
    }

    private function resetQueries()
    {
        $this->queries = Collection::make();
    }

    private function logQuery(QueryExecuted $query, Collection $backtrace)
    {
        try {
            $modelTrace = $backtrace->first(static fn ($trace) => Arr::get($trace, 'object') instanceof Builder);

            // The query is coming from an Eloquent model
            if (null !== $modelTrace) {
                /*
                 * Relations get resolved by either calling the "getRelationValue" method on the model,
                 * or if the class itself is a Relation.
                 */
                $relation = $backtrace->first(static fn ($trace) => Arr::get($trace, 'function') === 'getRelationValue' || Arr::get($trace, 'class') === Relation::class);

                // We try to access a relation
                if (is_array($relation) && isset($relation['object'])) {
                    $relationName = '';
                    $relationType = '';
                    $relatedModel = '';

                    if ($relation['class'] === Relation::class) {
                        $model        = get_class($relation['object']->getParent());
                        $relatedModel = get_class($relation['object']->getRelated());
                        $relationType = class_basename(get_class($relation['object']));

                        // Simplified relation name detection
                        $relationName = $this->getSimpleRelationName($relation['object']);
                    } else {
                        $model        = get_class($relation['object']);
                        $relationName = $relation['args'][0] ?? 'unknown';

                        // Simplified relation type detection
                        $relationType = 'HasRelation';
                        $relatedModel = 'unknown';
                    }

                    $sources = $this->findSource($backtrace);

                    if (empty($sources)) {
                        return;
                    }

                    $key   = md5($query->sql . $model . $relationName . $sources[0]->name . $sources[0]->line);
                    $count = Arr::get($this->queries, "{$key}.count", 0);
                    $time  = Arr::get($this->queries, "{$key}.time", 0);

                    $this->queries[$key] = [
                        'count'        => ++$count,
                        'time'         => $time + $query->time,
                        'query'        => $query->sql,
                        'actualQuery'  => Str::replaceArray('?', collect($query->bindings)->map(static fn ($binding) => is_numeric($binding) ? $binding : "'{$binding}'")->toArray(), $query->sql),
                        'model'        => $model,
                        'relatedModel' => $relatedModel,
                        'relation'     => $relationName,
                        'relationType' => $relationType,
                        'sources'      => $sources,
                    ];
                }
            }
        } catch (Exception $e) {
        }
    }

    private function findSource($stack)
    {
        $sources = [];

        foreach ($stack as $index => $trace) {
            $sources[] = $this->parseTrace($index, $trace);
        }

        return array_values(array_filter($sources));
    }

    /**
     * Check if the given file is to be excluded from analysis
     *
     * @param string $file
     *
     * @return bool
     */
    private function fileIsInExcludedPath($file)
    {
        $excludedPaths = [
            '/vendor/illuminate/database',
            '/vendor/illuminate/events',
        ];

        $normalizedPath = str_replace('\\', '/', $file);

        foreach ($excludedPaths as $excludedPath) {
            if (strpos($normalizedPath, $excludedPath) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Shorten the path by removing the relative links and base dir
     *
     * @param string $path
     */
    private function normalizeFilename($path): string
    {
        if (file_exists($path)) {
            $path = realpath($path);
        }

        return str_replace(base_path(), '', $path);
    }

    private function getDetectedQueries(): Collection
    {
        $queries = $this->queries->values();

        foreach ($this->excepts as $parentModel => $relations) {
            foreach ($relations as $relation) {
                $queries = $queries->reject(static fn ($query) => $query['model'] === $parentModel && $query['relation'] === $relation);
            }
        }

        $queries = $queries->where('count', '>', 1)->values();

        return $queries;
    }

    private function output()
    {
        $detectedQueries = $this->getDetectedQueries();

        if ($detectedQueries->isEmpty()) {
            return;
        }

        // Only log queries that haven't been logged yet
        static $loggedKeys = [];

        foreach ($detectedQueries as $query) {
            $logKey = md5($query['model'] . $query['relation'] . $query['query']);

            if (! in_array($logKey, $loggedKeys)) {
                $this->logSingleQuery($query);
                $loggedKeys[] = $logKey;
            }
        }
    }

    /**
     * Log a single N+1 query issue with detailed information
     *
     * @param mixed $detectedQuery
     */
    private function logSingleQuery($detectedQuery)
    {
        $modelName        = class_basename($detectedQuery['model']);
        $relatedModelName = class_basename($detectedQuery['relatedModel']);
        $relationType     = $detectedQuery['relationType'] ?? 'Unknown';

        $relationInfo = "{$modelName}->{$detectedQuery['relation']} ({$relationType} -> {$relatedModelName})";

        $firstSource = $detectedQuery['sources'][0] ?? null;
        $location    = $firstSource ? ($firstSource->fullPath ?? $firstSource->name) . ':' . $firstSource->line : 'unknown';

        $shortTitle = "N+1 query detected in {$modelName}->{$detectedQuery['relation']} | Fix: ->with('{$detectedQuery['relation']}')";
        $message    = "N+1 query detected in {$modelName}->{$detectedQuery['relation']}";
        $logOutput  = $shortTitle . PHP_EOL;

        $logOutput .= "{\"exception\":\"[object] (N1QueryException(code: 0): {$message} at {$location})" . PHP_EOL;
        $logOutput .= "Relation: {$relationInfo}" . PHP_EOL;
        $logOutput .= "Performance: executed {$detectedQuery['count']} times, {$detectedQuery['time']}ms total" . PHP_EOL;
        $logOutput .= "Query: {$detectedQuery['actualQuery']}" . PHP_EOL;

        $logOutput .= '[stacktrace]' . PHP_EOL;

        foreach ($detectedQuery['sources'] as $index => $source) {
            $fullPath = $source->fullPath ?? $source->name;

            // Build more informative method call like Laravel format
            $methodCall = '';
            if ($source->class && $source->function) {
                // Use full class name like Laravel, then method
                $fullClassName = $source->class;
                $methodCall    = "{$fullClassName}{$source->type}{$source->function}()";
            } else {
                // Fallback if no class/function info available
                $methodCall = 'Unknown->method()';
            }

            $logOutput .= sprintf(
                '#%d %s(%d): %s',
                $index,
                $fullPath,
                $source->line,
                $methodCall
            ) . PHP_EOL;
        }

        $logOutput .= '#' . count($detectedQuery['sources']) . ' {main}' . PHP_EOL;
        $logOutput .= '"}';

        Log::channel('query')->warning($logOutput);
    }

    /**
     * Get relation name safely without invoking methods
     *
     * @param mixed $relationObject
     */
    private function getSimpleRelationName($relationObject): string
    {
        try {
            // Directly get relation name from related model class
            $relatedClass = get_class($relationObject->getRelated());

            return strtolower(class_basename($relatedClass));
        } catch (Exception $e) {
            return 'unknown';
        }
    }
}
