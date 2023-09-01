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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use COM;
use Exception;
use InvalidArgumentException;
use Throwable;

// Library asli pada https://github.com/esyede/rakit/blob/master/system/hash.php
class Hash
{
    /**
     * Buat hash password.
     * Method ini diadaptasi dari https://github.com/ircmaxell/password-compat.
     *
     * @param string $password
     * @param int    $cost
     *
     * @return string
     */
    public static function make($password, $cost = 10)
    {
        if (! is_int($cost) || $cost < 4 || $cost > 31) {
            throw new Exception('Cost parameter must be an integer between 4 to 31.');
        }

        if (! function_exists('crypt')) {
            throw new Exception('Crypt must be loaded to use the hashing library.');
        }

        if (null === $password || is_int($password)) {
            $password = (string) $password;
        }

        if (! is_string($password)) {
            throw new Exception('Password must be a string.');
        }

        $buffer = self::bytes(16);
        $salt   = strtr(
            rtrim(base64_encode($buffer), '='),
            'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
            './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        );

        $hash = crypt($password, sprintf('$2y$%02d$', $cost) . mb_substr($salt, 0, 22, '8bit'));

        if (! is_string($hash) || 60 !== mb_strlen((string) $hash, '8bit')) {
            throw new Exception('Malformatted password hash result.');
        }

        return $hash;
    }

    /**
     * Cek cocok atau tidaknya sebuah password dengan hashnya.
     * Method ini diadaptasi dari https://github.com/ircmaxell/password-compat.
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function check($password, $hash)
    {
        if (! function_exists('crypt')) {
            throw new Exception('Crypt must be loaded to use the hashing library.');
        }

        $crypt = crypt($password, $hash);

        if (
            ! is_string($crypt)
            || mb_strlen($crypt, '8bit') !== mb_strlen($hash, '8bit')
            || mb_strlen($crypt, '8bit') <= 13
        ) {
            return false;
        }

        $status = 0;
        $length = mb_strlen($crypt, '8bit');

        for ($i = 0; $i < $length; $i++) {
            $status |= (ord($crypt[$i]) ^ ord($hash[$i]));
        }

        return 0 === $status;
    }

    /**
     * Cek apakah hash yang dihasilkan masih lemah berdasarkan cost yang diberikan.
     * Method ini diadaptasi dari https://github.com/ircmaxell/password-compat.
     *
     * @param string $hash
     * @param int    $cost
     *
     * @return bool
     */
    public static function weak($hash, $cost = 10)
    {
        $hash = (string) $hash;

        if (! is_int($cost) || $cost < 4 || $cost > 31) {
            throw new Exception('Cost parameter must be an integer between 4 to 31.');
        }

        if ('$2y$' !== mb_substr($hash, 0, 4, '8bit') || 60 !== mb_strlen($hash, '8bit')) {
            return false;
        }

        [$strength] = sscanf($hash, '$2y$%d$');

        return $cost !== $strength;
    }

    /**
     * Hasilkan byte acak yang aman secara kriptografi.
     * Method ini diadaptasi dari https://github.com/paragonie/random-compat.
     *
     * @param int $length
     *
     * @return string
     */
    public static function bytes($length)
    {
        if (! is_int($length)) {
            throw new InvalidArgumentException('Bytes length must be a positive integer');
        }

        if ($length < 1) {
            throw new InvalidArgumentException('Bytes length must be greater than zero');
        }

        if ($length > PHP_INT_MAX) {
            throw new InvalidArgumentException('Bytes length is too large');
        }

        $unix    = ('/' === DIRECTORY_SEPARATOR);
        $windows = ('\\' === DIRECTORY_SEPARATOR);
        $bytes   = false;

        // Gunakan openssl.
        $bytes = openssl_random_pseudo_bytes($length, $strong);

        if (false !== $strong && false !== $bytes) {
            if ($length === mb_strlen((string) $bytes, '8bit')) {
                return $bytes;
            }
        }

        // Openssl gagal, coba /dev/urandom (unix)
        if ($unix) {
            $urandom = true;
            $basedir = ini_get('open_basedir');

            if (! empty($basedir)) {
                $paths   = explode(PATH_SEPARATOR, strtolower((string) $basedir));
                $urandom = ([] !== array_intersect(['/dev', '/dev/', '/dev/urandom'], $paths));
                unset($paths);
            }

            if ($urandom && @is_readable('/dev/urandom')) {
                $file  = fopen('/dev/urandom', 'rb');
                $read  = 0;
                $local = '';

                while ($read < $length) {
                    $local .= fread($file, $length - $read);
                    $read = mb_strlen((string) $local, '8bit');
                }

                fclose($file);
                $bytes = str_pad($bytes, $length, "\0") ^ str_pad($local, $length, "\0");
            }

            if ($read >= $length && $length === mb_strlen((string) $bytes, '8bit')) {
                return $bytes;
            }
        }

        // /dev/urandom juga masih saja gagal, coba CAPICOM (windows)
        if ($windows && class_exists('\COM', false)) {
            try {
                $com   = new COM('CAPICOM.Utilities.1');
                $count = 0;

                do {
                    $bytes .= base64_decode((string) $com->GetRandom($length, 0));

                    if (mb_strlen($bytes, '8bit') >= $length) {
                        $bytes = mb_substr($bytes, 0, $length, '8bit');
                    }

                    $count++;
                } while ($count < $length);
            } catch (Throwable $e) {
                $bytes = false;
            } catch (Exception $e) {
                $bytes = false;
            }

            if ($bytes && is_string($bytes) && $length === mb_strlen($bytes, '8bit')) {
                return $bytes;
            }
        }

        // Tidak ada lagi yang bisa digunakan. Menyerah.
        throw new Exception('There is no suitable CSPRNG installed on your system');
    }
}
