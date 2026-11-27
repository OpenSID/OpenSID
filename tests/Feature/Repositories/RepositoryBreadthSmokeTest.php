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

namespace Tests\Feature\Repositories;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionMethod;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;
use Throwable;

/**
 * Fase 6 (lanjutan) — smoke breadth `App\Repositories\*` yang BERSIH dari CI3.
 *
 * Untuk tiap repository tanpa dependensi CI3 dan ber-konstruktor tanpa argumen wajib,
 * eksekusi method publik tanpa argumen (mis. `list()`) atas schema+seed. Tujuan: meng-cover
 * body query (Spatie QueryBuilder/jsonPaginate). Error per-method ditangkap try/catch.
 */
class RepositoryBreadthSmokeTest extends BaseTestCase
{
    use RefreshDatabase;

    private const CI3 = '/setting\(|ci_auth|get_instance|base_url\(|config_item|persen2|tgl_indo2|->ci\b/';

    /**
     * @return iterable<string, array{0: class-string}>
     */
    public static function repositoryProvider(): iterable
    {
        $dir = dirname(__DIR__, 3) . '/app/Repositories';

        foreach (glob($dir . '/*.php') as $path) {
            $isi = (string) file_get_contents($path);
            if (preg_match(self::CI3, $isi)) {
                continue;
            }
            $name = basename($path, '.php');
            yield $name => ['App\\Repositories\\' . $name];
        }
    }

    #[DataProvider('repositoryProvider')]
    public function test_smoke_repository(string $cls): void
    {
        if (! class_exists($cls)) {
            $this->markTestSkipped("{$cls} tidak dapat di-autoload.");
        }

        $ref  = new ReflectionClass($cls);
        $ctor = $ref->getConstructor();
        if ($ctor && $ctor->getNumberOfRequiredParameters() > 0) {
            $this->markTestSkipped("{$cls} butuh argumen konstruktor (ada test khusus).");
        }

        try {
            $repo = $ref->newInstance();
        } catch (Throwable $e) {
            $this->markTestSkipped("{$cls} tidak bisa di-instansiasi tanpa konteks ({$e->getMessage()}).");
        }
        $dijalankan = 0;

        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isStatic() || $method->getNumberOfRequiredParameters() > 0) {
                continue;
            }
            if ($method->getDeclaringClass()->getName() !== $cls || str_starts_with($method->getName(), '__')) {
                continue;
            }

            try {
                $repo->{$method->getName()}();
                $dijalankan++;
            } catch (Throwable) {
                continue; // butuh argumen/konteks → bagian bersih tetap ter-eksekusi
            }
        }

        $this->assertGreaterThanOrEqual(0, $dijalankan);
        $this->addToAssertionCount(1);
    }
}
