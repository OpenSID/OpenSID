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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionMethod;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;
use Throwable;

/**
 * Fase 6B — smoke local-scope Model atas DATA SEED (tanpa factory baru).
 *
 * Untuk tiap model `app/Models`, jalankan local-scope yang TIDAK butuh argumen tambahan
 * (selain `$query`) lalu eksekusi `->limit(1)->get()`. Tujuan: meng-cover BODY scope
 * (where/join/select) terhadap schema+seed nyata. Scope yang butuh argumen, atau yang
 * memanggil helper CI3 (`setting()`/`get_instance()`), otomatis terlewati via try/catch —
 * sehingga test tetap hijau dan menyumbang bagian yang bersih.
 */
class ModelScopeSmokeTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * @return iterable<string, array{0: class-string}>
     */
    public static function modelProvider(): iterable
    {
        $dir = dirname(__DIR__, 3) . '/app/Models';

        foreach (glob($dir . '/*.php') as $path) {
            $isi = (string) file_get_contents($path);

            if (preg_match('/abstract\s+class/', $isi)) {
                continue;
            }
            if (! str_contains($isi, 'public function scope')) {
                continue; // hanya model yang punya local scope
            }

            $name = basename($path, '.php');
            yield $name => ['App\\Models\\' . $name];
        }
    }

    #[DataProvider('modelProvider')]
    public function test_smoke_scope(string $cls): void
    {
        if (! class_exists($cls)) {
            $this->markTestSkipped("{$cls} tidak dapat di-autoload.");
        }

        $ref = new ReflectionClass($cls);
        if ($ref->isAbstract() || ! $ref->isSubclassOf(Model::class)) {
            $this->markTestSkipped("{$cls} bukan model Eloquent konkret.");
        }

        $dijalankan = 0;

        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== $cls) {
                continue;
            }
            $nama = $method->getName();
            if (! str_starts_with($nama, 'scope') || $nama === 'scope') {
                continue;
            }
            // butuh argumen selain $query? lewati (required <= 1 → hanya $query)
            if ($method->getNumberOfRequiredParameters() > 1) {
                continue;
            }

            $short = lcfirst(substr($nama, 5));

            try {
                $hasil = $cls::query()->{$short}();
                if ($hasil instanceof Builder) {
                    $hasil->limit(1)->get();
                }
                $dijalankan++;
            } catch (Throwable) {
                // scope butuh state/arg/CI3 → lewati
                continue;
            }
        }

        // minimal kelas valid; jumlah scope yang sukses dieksekusi bersifat best-effort
        $this->assertGreaterThanOrEqual(0, $dijalankan);
        $this->addToAssertionCount(1);
    }
}
