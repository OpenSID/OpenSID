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

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (! function_exists('tgl_indo')) {
    function date_indo($tgl): string
    {
        $ubah    = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah   = explode('-', $ubah);
        $tanggal = $pecah[2];
        $bulan   = bulan($pecah[1]);
        $tahun   = $pecah[0];

        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (! function_exists('bulan')) {
    function bulan($bln)
    {
        switch ($bln) {
            case 1:
                return 'Januari';

            case 2:
                return 'Februari';

            case 3:
                return 'Maret';

            case 04:
                return 'April';

            case 5:
                return 'Mei';

            case 6:
                return 'Juni';

            case 7:
                return 'Juli';

            case 8:
                return 'Agustus';

            case 9:
                return 'September';

            case 10:
                return 'Oktober';

            case 11:
                return 'November';

            case 12:
                return 'Desember';
        }
    }
}

if (! function_exists('bulan2')) {
    function bulan2($bln)
    {
        switch ($bln) {
            case 1:
                return 'Januari';

            case 2:
                return 'Februari';

            case 3:
                return 'Maret';

            case 04:
                return 'April';

            case 5:
                return 'Mei';

            case 6:
                return 'Juni';

            case 7:
                return 'Juli';

            case 8:
                return 'Agustus';

            case 9:
                return 'September';

            case 10:
                return 'Oktober';

            case 11:
                return 'November';

            case 12:
                return 'Desember';
        }
    }
}

//Format Shortdate
if (! function_exists('shortdate_indo')) {
    function shortdate_indo($tgl): string
    {
        $ubah    = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah   = explode('-', $ubah);
        $tanggal = $pecah[2];
        $bulan   = short_bulan($pecah[1]);
        $tahun   = $pecah[0];

        return $tanggal . '/' . $bulan . '/' . $tahun;
    }
}

if (! function_exists('short_bulan')) {
    function short_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return '01';

            case 2:
                return '02';

            case 3:
                return '03';

            case 4:
                return '04';

            case 5:
                return '05';

            case 6:
                return '06';

            case 7:
                return '07';

            case 8:
                return '08';

            case 9:
                return '09';

            case 10:
                return '10';

            case 11:
                return '11';

            case 12:
                return '12';
        }
    }
}

//Format Medium date
if (! function_exists('mediumdate_indo')) {
    function mediumdate_indo($tgl): string
    {
        $ubah    = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah   = explode('-', $ubah);
        $tanggal = $pecah[2];
        $bulan   = medium_bulan($pecah[1]);
        $tahun   = $pecah[0];

        return $tanggal . '-' . $bulan . '-' . $tahun;
    }
}

if (! function_exists('medium_bulan')) {
    function medium_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return 'Jan';

            case 2:
                return 'Feb';

            case 3:
                return 'Mar';

            case 4:
                return 'Apr';

            case 5:
                return 'Mei';

            case 6:
                return 'Jun';

            case 7:
                return 'Jul';

            case 8:
                return 'Ags';

            case 9:
                return 'Sep';

            case 10:
                return 'Okt';

            case 11:
                return 'Nov';

            case 12:
                return 'Des';
        }
    }
}

//Long date indo Format
if (! function_exists('longdate_indo')) {
    function longdate_indo($tanggal): string
    {
        $ubah  = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode('-', $ubah);
        $tgl   = $pecah[2];
        $bln   = $pecah[1];
        $thn   = $pecah[0];
        $bulan = bulan($pecah[1]);

        $nama      = date('l', mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = '';
        if ($nama == 'Sunday') {
            $nama_hari = 'Minggu';
        } elseif ($nama == 'Monday') {
            $nama_hari = 'Senin';
        } elseif ($nama == 'Tuesday') {
            $nama_hari = 'Selasa';
        } elseif ($nama == 'Wednesday') {
            $nama_hari = 'Rabu';
        } elseif ($nama == 'Thursday') {
            $nama_hari = 'Kamis';
        } elseif ($nama == 'Friday') {
            $nama_hari = 'Jumat';
        } elseif ($nama == 'Saturday') {
            $nama_hari = 'Sabtu';
        }

        return $nama_hari . ',' . $tgl . ' ' . $bulan . ' ' . $thn;
    }
}

if (! function_exists('bulan_array')) {
    function bulan_array()
    {
        return [
            [
                'urut'         => 1,
                'nama_pendek'  => medium_bulan(1),
                'nama_panjang' => bulan(1),
            ],
            [
                'urut'         => 2,
                'nama_pendek'  => medium_bulan(2),
                'nama_panjang' => bulan(2),
            ],
            [
                'urut'         => 3,
                'nama_pendek'  => medium_bulan(3),
                'nama_panjang' => bulan(3),
            ],
            [
                'urut'         => 4,
                'nama_pendek'  => medium_bulan(4),
                'nama_panjang' => bulan(4),
            ],
            [
                'urut'         => 5,
                'nama_pendek'  => medium_bulan(5),
                'nama_panjang' => bulan(5),
            ],
            [
                'urut'         => 6,
                'nama_pendek'  => medium_bulan(6),
                'nama_panjang' => bulan(6),
            ],
            [
                'urut'         => 7,
                'nama_pendek'  => medium_bulan(7),
                'nama_panjang' => bulan(7),
            ],
            [
                'urut'         => 8,
                'nama_pendek'  => medium_bulan(8),
                'nama_panjang' => bulan(8),
            ],
            [
                'urut'         => 9,
                'nama_pendek'  => medium_bulan(9),
                'nama_panjang' => bulan(9),
            ],
            [
                'urut'         => 10,
                'nama_pendek'  => medium_bulan(10),
                'nama_panjang' => bulan(10),
            ],
            [
                'urut'         => 11,
                'nama_pendek'  => medium_bulan(11),
                'nama_panjang' => bulan(11),
            ],
            [
                'urut'         => 12,
                'nama_pendek'  => medium_bulan(12),
                'nama_panjang' => bulan(12),
            ],
        ];
    }
}

if (! function_exists('bulan2_array')) {
    function bulan2_array()
    {
        return [
            [
                'urut'         => 1,
                'nama_panjang' => 'Januari',
            ],
            [
                'urut'         => 2,
                'nama_panjang' => 'Februari',
            ],
            [
                'urut'         => 3,
                'nama_panjang' => 'Maret',
            ],
            [
                'urut'         => 4,
                'nama_panjang' => 'April',
            ],
            [
                'urut'         => 5,
                'nama_panjang' => 'Mei',
            ],
            [
                'urut'         => 6,
                'nama_panjang' => 'Juni',
            ],
            [
                'urut'         => 7,
                'nama_panjang' => 'Juli',
            ],
            [
                'urut'         => 8,
                'nama_panjang' => 'Agustus',
            ],
            [
                'urut'         => 9,
                'nama_panjang' => 'September',
            ],
            [
                'urut'         => 10,
                'nama_panjang' => 'Oktober',
            ],
            [
                'urut'         => 11,
                'nama_panjang' => 'November',
            ],
            [
                'urut'         => 12,
                'nama_panjang' => 'Desember',
            ],
        ];
    }
}

// die(json_encode(bulan_array()[1]));

if (! function_exists('kuartal')) {
    function kuartal()
    {
        return [
            [
                'ke'    => 1,
                'bulan' => bulan_array()[0]['nama_panjang'] . ' - ' . bulan_array()[2]['nama_panjang'],
            ],
            [
                'ke'    => 2,
                'bulan' => bulan_array()[3]['nama_panjang'] . ' - ' . bulan_array()[5]['nama_panjang'],
            ],
            [
                'ke'    => 3,
                'bulan' => bulan_array()[6]['nama_panjang'] . ' - ' . bulan_array()[8]['nama_panjang'],
            ],
            [
                'ke'    => 4,
                'bulan' => bulan_array()[9]['nama_panjang'] . ' - ' . bulan_array()[11]['nama_panjang'],
            ],
        ];
    }
}

if (! function_exists('kuartal2')) {
    function kuartal2()
    {
        return [
            [
                'ke'    => 1,
                'bulan' => bulan2_array()[0]['nama_panjang'] . ' - ' . bulan2_array()[2]['nama_panjang'],
            ],
            [
                'ke'    => 2,
                'bulan' => bulan2_array()[3]['nama_panjang'] . ' - ' . bulan2_array()[5]['nama_panjang'],
            ],
            [
                'ke'    => 3,
                'bulan' => bulan2_array()[6]['nama_panjang'] . ' - ' . bulan2_array()[8]['nama_panjang'],
            ],
            [
                'ke'    => 4,
                'bulan' => bulan2_array()[9]['nama_panjang'] . ' - ' . bulan2_array()[11]['nama_panjang'],
            ],
        ];
    }
}

if (! function_exists('get_kuartal')) {
    function get_kuartal($kuartal = null)
    {
        if ($kuartal == null || $kuartal < 0 || $kuartal > 4) {
            return [
                'ke'    => 'undefined',
                'bulan' => 'undefined',
            ];
        }

        return kuartal2()[$kuartal - 1];
    }
}
