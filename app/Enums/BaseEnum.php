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

namespace App\Enums;

use ReflectionClass;
use Throwable;

defined('BASEPATH') || exit('No direct script access allowed');

abstract class BaseEnum
{
    protected static $items = [];

    public static function all(): array
    {
        try {
            return static::$items[static::class] ??= (new ReflectionClass(static::class))->getConstants();
        } catch (Throwable) {
            return [];
        }
    }

    public static function keys(): array
    {
        return array_keys(static::all());
    }

    public static function values(): array
    {
        return array_values(static::all());
    }

    public static function hasKey(string $key): bool
    {
        return array_key_exists($key, static::all());
    }

    public static function hasValue(mixed $value): bool
    {
        return in_array($value, static::all());
    }

    public static function valueOf(mixed $key, mixed $default = null)
    {
        return static::all()[$key] ?? $default;
    }

    public static function keysOf(mixed $value): array
    {
        $keys = [];

        foreach (static::all() as $k => $v) {
            if ($v == $value) {
                $keys[] = $k;
            }
        }

        return $keys;
    }

    public static function keyOf(mixed $value, mixed $default = null)
    {
        return static::keysOf($value)[0] ?? $default;
    }

    public static function randomKey()
    {
        return array_rand(static::all());
    }

    public static function randomKeyExceptValues(array $values = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array(static::all()[$key], $values));

        return $key;
    }

    public static function randomKeyExceptKeys(array $keys = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array($key, $keys));

        return $key;
    }

    public static function randomValue()
    {
        return static::all()[array_rand(static::all())];
    }

    public static function randomValueExceptValues(array $values = [])
    {
        do {
            $value = static::all()[array_rand(static::all())];
        } while (in_array($value, $values));

        return $value;
    }

    public static function randomValueExceptKeys(array $keys = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array($key, $keys));

        return static::all()[$key];
    }

    public static function allToJson(): string
    {
        return json_encode(static::all());
    }

    // =============================
    // WRAPPER COUNT
    // =============================

    /**
     * Menghitung jumlah item dalam enum
     *
     * @return int Jumlah total konstanta dalam enum
     */
    public static function count(): int
    {
        return count(static::all());
    }

    // =============================
    // TRANSFORMASI VALUE - SEMUA
    // =============================

    public static function valuesToUpper(): array
    {
        return static::transformValues('strtoupper');
    }

    public static function valuesToLower(): array
    {
        return static::transformValues('strtolower');
    }

    public static function valuesToUcfirst(): array
    {
        return static::transformValues(static fn ($v): string => ucfirst(strtolower((string) $v)));
    }

    public static function valuesToUcwords(): array
    {
        return static::transformValues(static fn ($v): string => ucwords(strtolower((string) $v)));
    }

    // =============================
    // TRANSFORMASI VALUE - TUNGGAL
    // =============================

    public static function valueToUpper(string $key, mixed $default = null): mixed
    {
        return strtoupper((string) static::valueOf($key, $default));
    }

    public static function valueToLower(string $key, mixed $default = null): mixed
    {
        return strtolower((string) static::valueOf($key, $default));
    }

    public static function valueToUcfirst(string $key, mixed $default = null): mixed
    {
        return ucfirst(strtolower((string) static::valueOf($key, $default)));
    }

    public static function valueToUcwords(string $key, mixed $default = null): mixed
    {
        return ucwords(strtolower((string) static::valueOf($key, $default)));
    }

    // =============================
    // TRANSFORMASI KEY - SEMUA
    // =============================

    public static function keysToUpper(): array
    {
        return static::transformKeys('strtoupper');
    }

    public static function keysToLower(): array
    {
        return static::transformKeys('strtolower');
    }

    public static function keysToUcfirst(): array
    {
        return static::transformKeys(static fn ($k): string => ucfirst(strtolower((string) $k)));
    }

    public static function keysToUcwords(): array
    {
        return static::transformKeys(static fn ($k): string => ucwords(strtolower((string) $k)));
    }

    // =============================
    // TRANSFORMASI KEY - TUNGGAL
    // =============================

    public static function keyToUpper(string $value, mixed $default = null): mixed
    {
        return strtoupper((string) static::keyOf($value, $default));
    }

    public static function keyToLower(string $value, mixed $default = null): mixed
    {
        return strtolower((string) static::keyOf($value, $default));
    }

    public static function keyToUcfirst(string $value, mixed $default = null): mixed
    {
        return ucfirst(strtolower((string) static::keyOf($value, $default)));
    }

    public static function keyToUcwords(string $value, mixed $default = null): mixed
    {
        return ucwords(strtolower((string) static::keyOf($value, $default)));
    }

    // =============================
    // WRAPPER UMUM
    // =============================

    public static function transformValues(callable $callback): array
    {
        return array_map($callback, static::all());
    }

    public static function transformKeys(callable $callback): array
    {
        $items  = static::all();
        $result = [];

        foreach ($items as $k => $v) {
            $result[$callback($k)] = $v;
        }

        return $result;
    }
}
