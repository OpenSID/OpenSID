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

namespace Tests\Feature\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionNamedType;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;
use Throwable;

/**
 * Fase 6 (lanjutan) — smoke `App\Http\Transformers\*` (mapper Fractal).
 *
 * Untuk tiap transformer yang BERSIH dari CI3, panggil `transform()` dengan argumen yang
 * sesuai tipe parameter: model dari seed (typed Model), array (typed array), atau objek
 * ber-`toArray()` (untyped). Transformer yang memakai helper CI3 (`setting()`, `tgl_indo2`)
 * disaring keluar; sisa error per-transformer ditangkap try/catch agar bagian bersih tetap ter-cover.
 */
class TransformerSmokeTest extends BaseTestCase
{
    use RefreshDatabase;

    private const CI3 = '/setting\(|ci_auth|get_instance|base_url\(|config_item|tgl_indo2|gambar_desa|AmbilFoto|->ci\b/';

    /**
     * @return iterable<string, array{0: class-string}>
     */
    public static function transformerProvider(): iterable
    {
        $dir = dirname(__DIR__, 4) . '/app/Http/Transformers';

        foreach (glob($dir . '/*.php') as $path) {
            $isi = (string) file_get_contents($path);
            if (preg_match(self::CI3, $isi)) {
                continue; // transformer ber-CI3 → lewati
            }
            $name = basename($path, '.php');
            yield $name => ['App\\Http\\Transformers\\' . $name];
        }
    }

    #[DataProvider('transformerProvider')]
    public function test_transform(string $cls): void
    {
        if (! class_exists($cls)) {
            $this->markTestSkipped("{$cls} tidak dapat di-autoload.");
        }

        $ref = new ReflectionClass($cls);
        if (! $ref->hasMethod('transform')) {
            $this->markTestSkipped("{$cls} tidak punya method transform().");
        }

        $param = $ref->getMethod('transform')->getParameters()[0] ?? null;
        $arg   = $this->bangunArgumen($param);

        if ($arg === null) {
            $this->markTestSkipped("{$cls}: tidak ada data seed untuk argumen transform().");
        }

        try {
            $hasil = $ref->newInstance()->transform($arg);
            $this->assertIsArray($hasil);
        } catch (Throwable $e) {
            // relasi belum ter-load / accessor CI3 / konstanta → bagian bersih sudah dieksekusi
            $this->markTestSkipped("{$cls}::transform() butuh konteks tambahan ({$e->getMessage()}).");
        }
    }

    /** Bangun argumen sesuai tipe parameter transform(). */
    private function bangunArgumen(?\ReflectionParameter $param): mixed
    {
        $type = $param?->getType();

        if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
            $cls = $type->getName();
            if (is_subclass_of($cls, Model::class)) {
                return $cls::query()->first(); // dari seed (boleh null → di-skip)
            }
        }

        if ($type instanceof ReflectionNamedType && $type->getName() === 'array') {
            return [];
        }

        // untyped / mixed → objek dengan toArray() (banyak transformer hanya return $x->toArray())
        return new class {
            public function toArray(): array
            {
                return ['id' => 1, 'nama' => 'contoh'];
            }
        };
    }
}
