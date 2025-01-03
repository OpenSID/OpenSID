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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
    /**
     * All the items declared in enum
     *
     * @var array
     */
    protected static $items = [];

    /**
     * Get all the items in the enum
     */
    public static function all(): array
    {
        try {
            return static::$items[static::class] ?? (static::$items[static::class] = (new ReflectionClass(static::class))->getConstants());
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * Get all the declared keys
     */
    public static function keys(): array
    {
        return array_keys(static::all());
    }

    /**
     * Get all the declared values
     */
    public static function values(): array
    {
        return array_values(static::all());
    }

    /**
     * Check if the given key declared in the enum or not
     */
    public static function hasKey(string $key): bool
    {
        return array_key_exists($key, static::all());
    }

    /**
     * Check if the given value declared in the enum or not
     */
    public static function hasValue(mixed $value): bool
    {
        return in_array($value, static::all());
    }

    /**
     * Get value of the given key
     *
     * @return mixed|null
     */
    public static function valueOf(mixed $key, mixed $default = null)
    {
        return static::all()[$key] ?? $default;
    }

    /**
     * Get related keys of the given value
     */
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

    /**
     * Get only the first related key of the given value
     *
     * @return mixed|null
     */
    public static function keyOf(mixed $value, mixed $default = null)
    {
        return static::keysOf($value)[0] ?? $default;
    }

    /**
     * Get a random key
     *
     * @return mixed
     */
    public static function randomKey()
    {
        return array_rand(static::all());
    }

    /**
     * Get a random key except given values
     *
     * @return array|int|string
     */
    public static function randomKeyExceptValues(array $values = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array(static::all()[$key], $values));

        return $key;
    }

    /**
     * Get a random key except given keys
     *
     * @return array|int|string
     */
    public static function randomKeyExceptKeys(array $keys = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array($key, $keys));

        return $key;
    }

    /**
     * Get a random value
     *
     * @return mixed
     */
    public static function randomValue()
    {
        return static::all()[array_rand(static::all())];
    }

    /**
     * Get a random value except given values
     *
     * @return mixed
     */
    public static function randomValueExceptValues(array $values = [])
    {
        do {
            $value = static::all()[array_rand(static::all())];
        } while (in_array($value, $values));

        return $value;
    }

    /**
     * Get a random value except given keys
     *
     * @return mixed
     */
    public static function randomValueExceptKeys(array $keys = [])
    {
        do {
            $key = array_rand(static::all());
        } while (in_array($key, $keys));

        return static::all()[$key];
    }

    /**
     * Get all the items in the enum as json
     */
    public static function allToJson(): string
    {
        return json_encode(static::all());
    }
}
