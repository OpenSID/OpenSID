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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Class Date_conv
 *
 * Class for date conversion in Gregorian-Julian-Hijri calendar.
 * This class originally adopted from hijri-dates library (https://github.com/GeniusTS/hijri-dates/blob/master/src/Converter.php)
 *
 * @license MIT
 */
class DateConv
{
    private $months = [
        'Muharram',
        'Safar',
        "Rabi'ul Awal",
        "Rabi'ul akhir",
        'Jumadil Awal',
        'Jumadil Akhir',
        'Rajab',
        "Sya'ban",
        'Ramadhan',
        'Syawal',
        'Dzulqaidah',
        'Dzulhijjah',
    ];

    /**
     * The Julian Day for a given Gregorian date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return float
     */
    public function gregorianToJulian($year, $month, $day)
    {
        if ($month < 3) {
            $year--;
            $month += 12;
        }

        $a = floor($year / 100.0);
        $b = ($year === 1582 && ($month > 10 || ($month === 10 && $day > 4)) ? -10 : ($year === 1582 && $month === 10 ? 0 : ($year < 1583 ? 0 : 2 - $a + floor($a / 4.0))));

        return floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $b - 1524;
    }

    /**
     * The Julian Day for a given Hijri date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return float
     */
    public function hijriToJulian($year, $month, $day)
    {
        return floor((11 * $year + 3) / 30) + floor(354 * $year) + floor(30 * $month) - floor(($month - 1) / 2) + $day + 1948440 - 386;
    }

    /**
     * The Gregorian date Day for a given Julian
     *
     * @param float $julianDay
     *
     * @return array
     */
    public function julianToGregorian($julianDay)
    {
        $b = 0;
        if ($julianDay > 2299160) {
            $a = floor(($julianDay - 1867216.25) / 36524.25);
            $b = 1 + $a - floor($a / 4.0);
        }
        $bb    = $julianDay + $b + 1524;
        $cc    = floor(($bb - 122.1) / 365.25);
        $dd    = floor(365.25 * $cc);
        $ee    = floor(($bb - $dd) / 30.6001);
        $day   = ($bb - $dd) - floor(30.6001 * $ee);
        $month = $ee - 1;
        if ($ee > 13) {
            $cc++;
            $month = $ee - 13;
        }
        $year = $cc - 4716;

        return ['year' => (int) $year, 'month' => (int) $month, 'day' => (int) $day];
    }

    /**
     * The Hijri date Day for a given Julian
     *
     * @param float $julianDay
     *
     * @return array
     */
    public function julianToHijri($julianDay)
    {
        $y          = 10631.0 / 30.0;
        $epochAstro = 1948084;
        $shift1     = 8.01 / 60.0;
        $z          = $julianDay - $epochAstro;
        $cyc        = floor($z / 10631.0);
        $z          = $z - 10631 * $cyc;
        $j          = floor(($z - $shift1) / $y);
        $z          = $z - floor($j * $y + $shift1);
        $year       = 30 * $cyc + $j;
        $month      = (int) floor(($z + 28.5001) / 29.5);
        if ($month === 13) {
            $month = 12;
        }
        $day = $z - floor(29.5001 * $month - 29);

        return ['year' => (int) $year, 'month' => (int) $month, 'day' => (int) $day];
    }

    public function gregorianToHijri($year, $month, $day)
    {
        $jd = $this->gregorianToJulian($year, $month, $day);

        return $this->julianToHijri($jd);
    }

    public function HijriDateId($format, $time = null)
    {
        //greg
        [$Y, $n, $j] = explode('/', date('Y/n/j', $time ?: time()));
        // hijri
        $hijri = $this->gregorianToHijri($Y, $n, $j);

        $n = $hijri['month'];
        $Y = $hijri['year'];
        $j = $hijri['day'];
        $F = $this->months[$n];

        return strtr($format, compact('F', 'Y', 'j', 'n')) . ' H';
    }
}
