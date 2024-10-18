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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

function nested_array_search($needle, $array)
{
    /**
     * Mencari nilai di nested array (array dalam array).
     * Ambil key dari array utama
     *
     * @param mixed $needle
     * @param mixed $array
     */
    foreach ($array as $key => $value) {
        $array_key = array_search($needle, $value, true);
        if ($array_key !== false) {
            return $key;
        }
    }
}

function Parse_Data($data, $p1, $p2): string
{
    $data  = ' ' . $data;
    $hasil = '';
    $awal  = strpos($data, (string) $p1);
    if ($awal != '') {
        $akhir = strpos(strstr($data, (string) $p1), (string) $p2);
        if ($akhir != '') {
            $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
        }
    }

    return $hasil;
}

function Rupiah($nil = 0): string
{
    $nil += 0;
    if (($nil * 100) % 100 == 0) {
        $nil .= '.00';
    } elseif (($nil * 100) % 10 == 0) {
        $nil .= '0';
    }
    $nil  = str_replace('.', ',', $nil);
    $str1 = $nil;
    $str2 = '';
    $dot  = '';
    $str  = strrev($str1);
    $arr  = str_split($str, 3);
    $i    = 0;

    foreach ($arr as $str) {
        $str2 = $str2 . $dot . $str;
        if (strlen($str) == 3 && $i > 0) {
            $dot = '.';
        }
        $i++;
    }
    $rp = strrev($str2);
    if ($rp == '') {
        return 'Rp. 0,00';
    }
    if ($rp <= 0) {
        return 'Rp. 0,00';
    }

    return "Rp. {$rp}";
}

function Rupiah2($nil = 0): string
{
    $nil += 0;
    if (($nil * 100) % 100 == 0) {
        $nil .= '.00';
    } elseif (($nil * 100) % 10 == 0) {
        $nil .= '0';
    }
    $nil  = str_replace('.', ',', $nil);
    $str1 = $nil;
    $str2 = '';
    $dot  = '';
    $str  = strrev($str1);
    $arr  = str_split($str, 3);
    $i    = 0;

    foreach ($arr as $str) {
        $str2 = $str2 . $dot . $str;
        if (strlen($str) == 3 && $i > 0) {
            $dot = '.';
        }
        $i++;
    }
    $rp = strrev($str2);
    if ($rp == '') {
        return '-';
    }
    if ($rp <= 0) {
        return '-';
    }

    return "Rp. {$rp}";
}

function Rupiah3($nil = 0): string
{
    $nil += 0;
    if (($nil * 100) % 100 == 0) {
        $nil .= '.00';
    } elseif (($nil * 100) % 10 == 0) {
        $nil .= '0';
    }
    $nil  = str_replace('.', ',', $nil);
    $str1 = $nil;
    $str2 = '';
    $dot  = '';
    $str  = strrev($str1);
    $arr  = str_split($str, 3);
    $i    = 0;

    foreach ($arr as $str) {
        $str2 = $str2 . $dot . $str;
        if (strlen($str) == 3 && $i > 0) {
            $dot = '.';
        }
        $i++;
    }
    $rp = strrev($str2);
    if ($rp != 0) {
        return "{$rp}";
    }

    return '-';
}

function to_rupiah($inp = ''): string
{
    $outp = str_replace('.', '', $inp);

    return str_replace(',', '.', $outp);
}

function rp($inp = 0): string
{
    return number_format($inp, 2, ',', '.');
}

function rupiah24($angka, string $prefix = 'Rp ', $digit = 2): string
{
    return $prefix . number_format($angka, $digit, ',', '.');
}

function jecho($a, $b, $str): void
{
    if ($a == $b) {
        echo $str;
    }
}

function valid_nik_nokk($nik): void
{
    $length = strlen($nik);
    if ($length < 16) {
        echo 'class="warning"';
    } elseif (get_nik($nik) == 0) {
        echo 'class="danger"';
    }
}

function compared_return($a, $b, $retval = null): void
{
    if ($a === $b) {
        echo 'active';
    }
}

function selected($a, $b, $opt = 0): void
{
    if ($a == $b) {
        if ($opt) {
            echo "checked='checked'";
        } else {
            echo "selected='selected'";
        }
    }
}

function date_is_empty($tgl): bool
{
    return empty($tgl) || substr($tgl, 0, 10) == '0000-00-00';
}

function rev_tgl($tgl, $replace_with = '-')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $ar = explode('-', $tgl);

    return $ar[2] . '-' . $ar[1] . '-' . $ar[0];
}

function penetration($str): string
{
    return str_replace("'", '-', $str);
}

function penetration1($str): string
{
    return str_replace("'", ' ', $str);
}

function unpenetration($str): string
{
    return str_replace('-', "'", $str);
}
function spaceunpenetration($str): string
{
    return str_replace('-', ' ', $str);
}

function bulan()
{
    return [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
}

function getBulan(int $bln)
{
    $bulan = bulan();

    return $bulan[$bln];
}

/**
 * @param mixed $asc
 *
 * @return string[]
 */
function tahun(int $awal = 2018, $asc = false): array
{
    $akhir = date('Y');
    $tahun = [];

    for ($i = $akhir; $i >= $awal; $i--) {
        $tahun[] = $i;
    }

    if ($asc) {
        sort($tahun);
    }

    return $tahun;
}

function nama_bulan($tgl): string
{
    $ar = explode('-', $tgl);
    $nm = getBulan((int) $ar[1]);

    return $ar[0] . ' ' . $nm . ' ' . $ar[2];
}

function hari($tgl): string
{
    $hari = [
        0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];
    $dayofweek = date('w', strtotime($tgl));

    return $hari[$dayofweek];
}

function dua_digit(string $i): string
{
    return $i < 10 ? '0' . $i : $i;
}

function tiga_digit(?string $i): string
{
    if ($i < 10) {
        $o = '00' . $i;
    } elseif ($i < 100) {
        $o = '0' . $i;
    } else {
        $o = $i;
    }

    return $o;
}

function pertumbuhan($a = 1, $b = 1, $c = 1, $d = 1): string
{
    $x = 0;
    $y = 0;
    $z = 0;
    if ($a > 1) {
        $x = (($b - $a) / $a);
    }
    if ($b > 1) {
        $y = (($c - $b) / $b);
    }
    if ($c > 1) {
        $z = (($d - $c) / $c);
    }
    $outp = (($x + $y + $z) / 3) * 100;
    $outp = round($outp, 2);

    return str_replace('.', ',', $outp) . ' %';
}

function koma($a = 1): string
{
    return substr_count($a, '.') !== 0 ? str_replace('.', ',', $a) : number_format($a, 0, ',', '.');
}

function tgl_indo2($tgl, $replace_with = '-')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $tanggal = substr($tgl, 8, 2);
    $jam     = substr($tgl, 11, 8);
    $bulan   = getBulan((int) substr($tgl, 5, 2));
    $tahun   = substr($tgl, 0, 4);

    return $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $jam;
}

function tgl_indo_dari_str($tgl_str, $kosong = '-')
{
    $time = strtotime($tgl_str);

    return $time ? tgl_indo(date('Y m d', strtotime($tgl_str))) : $kosong;
}

function tgl_indo($tgl, $replace_with = '-', string $with_day = '')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $tanggal = substr($tgl, 8, 2);
    $bulan   = getBulan((int) substr($tgl, 5, 2));
    $tahun   = substr($tgl, 0, 4);
    if ($with_day != '') {
        $tanggal = $with_day . ', ' . date('j', strtotime($tgl));
    }

    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function tgl_indo_out($tgl, $replace_with = '-')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }

    if ($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $bulan   = substr($tgl, 5, 2);
        $tahun   = substr($tgl, 0, 4);

        return $tanggal . '-' . $bulan . '-' . $tahun;
    }
}

function tgl_indo_in($tgl, $replace_with = '-')
{
    if (date_is_empty($tgl)) {
        return $replace_with;
    }
    $tanggal = substr($tgl, 0, 2);
    $bulan   = substr($tgl, 3, 2);
    $tahun   = substr($tgl, 6, 4);
    $jam     = substr($tgl, 11);
    $jam     = $jam === '' ? '' : ' ' . $jam;

    return $tahun . '-' . $bulan . '-' . $tanggal . $jam;
}

function waktu_ind($time): string
{
    $str = '';
    if (($time / 360) > 1) {
        $jam = ($time / 360);
        $jam = explode('.', $jam);
        $str .= $jam . ' Jam ';
    }
    if (($time / 60) > 1) {
        $menit = ($time / 60);
        $menit = explode('.', $menit);
        $str .= $menit[0] . ' Menit ';
    }
    $detik = $time % 60;
    $str .= $detik;

    return $str . ' Detik';
}

//time out
function timer(): void
{
    $time                = 2000;
    $_SESSION['timeout'] = time() + $time;
}

function generator($length = 7): string
{
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function cek_login(): bool
{
    $timeout = $_SESSION['timeout'];
    if (time() < $timeout) {
        timer();

        return true;
    }
    unset($_SESSION['timeout']);

    return false;
}

//time out Mandiri set 3 login per 1 menit
function mandiri_timer(): void
{
    $time                        = 60;  //60 detik
    $_SESSION['mandiri_try']     = 4;
    $_SESSION['mandiri_wait']    = 0;
    $_SESSION['mandiri_timeout'] = time() + $time;
}

function mandiri_timeout(): void
{
    (isset($_SESSION['mandiri_timeout'])) ? $timeout = $_SESSION['mandiri_timeout'] : $timeout = null;
    if (time() > $timeout) {
        mandiri_timer();
    }
}

//time out Admin set 3 login per 5 menit
function siteman_timer(): void
{
    $time                        = 300;  //300 detik
    $_SESSION['siteman_try']     = 4;
    $_SESSION['siteman_timeout'] = time() + $time;
}

function siteman_timeout(): void
{
    $timeout = $_SESSION['siteman_timeout'] ?? null;
    if (time() > $timeout) {
        $_SESSION['siteman_wait'] = 0;
    }
}

function get_identitas(): string
{
    return ucwords(setting('sebutan_desa')) . ' : ' . identitas('nama_desa') . ' ' . ucwords(setting('sebutan_kecamatan_singkat')) . ' : ' . identitas('nama_kecamatan') . ' Kab : ' . identitas('nama_kabupaten');
}

//baca data tanpa HTML Tags
function fixTag($varString): string
{
    // edited : filter <i> tag for exception
    return strip_tags($varString, '<i>');
}

// Format tampilan tanggal rentang

function fTampilTgl($sdate, $edate): string
{
    if ($sdate == $edate) {
        $tgl = date('j M Y', strtotime($sdate));
    } elseif ($edate > $sdate) {
        if (date('Y', strtotime($sdate)) === date('Y', strtotime($edate))) {
            if (date('M Y', strtotime($sdate)) === date('M Y', strtotime($edate))) {
                if (date('j M Y', strtotime($sdate)) === date('j M Y', strtotime($edate))) {
                    if (date('j M Y H', strtotime($sdate)) === date('j M Y H', strtotime($edate))) {
                        $tgl = date('j M Y H:i', strtotime($sdate));
                    } else {
                        $tgl = date('j M Y H:i', strtotime($sdate)) . ' - ' . date('H:i', strtotime($edate));
                    }
                } else {
                    $tgl = date('j', strtotime($sdate)) . ' - ' . date('j M Y', strtotime($edate));
                }
            } else {
                $tgl = date('j M', strtotime($sdate)) . ' - ' . date('j M Y', strtotime($edate));
            }
        } else {
            $tgl = date('j M Y', strtotime($sdate)) . ' - ' . date('j M Y', strtotime($edate));
        }
    } else {
        $tgl = fTampilTgl($edate, $sdate);
    }

    return $tgl;
}

// https://stackoverflow.com/questions/19271381/correctly-determine-if-date-string-is-a-valid-date-in-that-format
function validate_date($date, $format = 'd-m-Y'): bool
{
    $d = DateTime::createFromFormat($format, $date);

    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

// Potong teks pada batasan kata
function potong_teks($teks, $panjang): string
{
    $abstrak = fixTag($teks);
    if (strlen($abstrak) > $panjang + 10) {
        return substr($abstrak, 0, strpos($abstrak, ' ', $panjang));
    }

    return $abstrak;
}

function hash_pin($pin = 0): string
{
    $pin = (int) strrev($pin);
    $pin *= 77;
    $pin .= '!#@$#%';

    return md5($pin);
}

/*
 * =======================================
 * Rupiah terbilang
 */
function number_to_words($number, $nol_sen = true): string
{
    $before_comma = trim(to_word($number));
    $after_comma  = trim(comma($number));
    $result       = $before_comma . ($nol_sen ? '' : ' koma ' . $after_comma);

    return ucwords($result . ' Rupiah');
}

function to_word($number): string
{
    $words      = '';
    $arr_number = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas',
    ];

    $unit = ['', 'ribu', 'juta', 'milyar', 'triliun', 'kuadriliun', 'kuintiliun', 'sekstiliun', 'septiliun', 'oktiliun', 'noniliun', 'desiliun', 'undesiliun', 'duodesiliun', 'tredesiliun', 'kuatuordesiliun'];

    if ($number < 12) {
        $words = ' ' . $arr_number[$number];
    } elseif ($number < 20) {
        $words = to_word($number - 10) . ' belas';
    } elseif ($number < 100) {
        $words = to_word(intdiv($number, 10)) . ' puluh' . to_word($number % 10);
    } elseif ($number < 200) {
        $words = ' seratus' . to_word($number - 100);
    } elseif ($number < 1000) {
        $words = to_word(intdiv($number, 100)) . ' ratus' . to_word($number % 100);
    } elseif ($number >= 1000 && $number < 2000) {
        $words = ' seribu' . to_word($number - 1000);
    } else {
        for ($i = count($unit) - 1; $i >= 0; $i--) {
            $divider = 10 ** (3 * $i);
            if ($number < $divider) {
                continue;
            }

            $div = intdiv($number, $divider);
            $mod = $number % $divider;

            $words = to_word($div) . ' ' . $unit[$i] . to_word($mod);
            break;
        }
    }

    return $words;
}

function comma($number): string
{
    $after_comma = stristr($number, ',');
    $arr_number  = [
        'nol',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan', ];

    $results = '';
    $length  = strlen($after_comma);
    $i       = 1;

    while ($i < $length) {
        $get = substr($after_comma, $i, 1);
        $results .= ' ' . $arr_number[$get];
        $i++;
    }

    return $results;
}

function hit($angka): string
{
    $hit = ($angka === null || $angka === '') ? '0' : ribuan($angka);

    return $hit . ' Kali';
}

function ribuan($angka): string
{
    return number_format($angka, 0, '.', '.');
}

// TODO:: Jangan gunakan ini lagi, gunakan fungsi set_words sebagai penggantinya yang lebih dinamis
function set_ucwords($data): string
{
    return set_words($data, 'ucwords');
}

/**
 * Fungsi ini digunakan untuk mengubah kata/kalimat menjadi huruf besar, kecil, dll
 *
 * @param string      $data
 * @param string|null $type
 */
function set_words($data = '', $type = null): string
{
    $exp     = explode(' ', $data);
    $data    = '';
    $counter = count($exp);

    for ($i = 0; $i < $counter; $i++) {
        $txt = $exp[$i];

        switch ($type) {
            case 'ucwords':
                $txt = ucwords(strtolower($exp[$i]));
                break;

            case 'lower':
                $txt = strtolower($exp[$i]);
                break;

            case 'upper':
                $txt = strtoupper($exp[$i]);
                break;

            case 'ucfirst':
                $txt = ($i === 0) ? ucfirst(strtolower($exp[$i])) : $exp[$i];
                break;

            default:
                $txt = $exp[$i];
                break;
        }
        $data .= ' ' . (is_angka_romawi($exp[$i]) ? $exp[$i] : $txt);
    }

    return trim($data);
}

function persen($data, string $simbol = '%', $digit = 2): string
{
    $str = number_format(is_nan($data) ? 0 : (float) ($data * 100), $digit, '.', '');

    return str_replace('.', ',', $str) . $simbol;
}

function persen2($pembilang, $pembagi, $simbol = '%', $digit = 2): string
{
    $data = ($pembagi == 0) ? 0 : $pembilang / $pembagi;

    return persen($data, $simbol, $digit);
}

function persen3($number, $total, $precision = 2)
{
    // Can't divide by zero so let's catch that early.
    if ($total == 0) {
        return 0;
    }

    return round(($number / $total) * 100, $precision) . '%';
}

function sensor_nik_kk($data)
{
    $count = strlen($data);
    if ($count <= 10) {
        return null;
    }

    return substr_replace($data, str_repeat('X', $count - 7), 8, $count - 7);
}

// Asumsi nilai order untuk desc (di model) selalu bernilai asc + 1.
// Contoh: asc untuk nama = 5 maka desc untuk nama = 6
function url_order($o = 1, $url = '', $asc = 1, $text = 'Field'): string
{
    $url  = site_url($url);
    $desc = ($asc + 1);

    switch ($o) {
        case $desc:
            $link = "<a href=\"{$url}/{$asc} \">{$text} <i class='fa fa-sort-asc fa-sm'></i></a>";
            break;

        case $asc:
            $link = "<a href=\"{$url}/{$desc} \">{$text} <i class='fa fa-sort-desc fa-sm'></i></a>";
            break;

        default:
            $link = "<a href=\"{$url}/{$asc} \">{$text} <i class='fa fa-sort fa-sm'></i></a>";
            break;
    }

    return $link;
}

// https://stackoverflow.com/questions/16564650/best-way-to-delete-column-from-multidimensional-array
function delete_col(&$array, $offset): bool
{
    return array_walk($array, static function (&$v) use ($offset): void {
        array_splice($v, $offset, 1);
    });
}
// =======================================

function get_pesan_opendk(): void
{
    $ci = &get_instance();
    if ((! $ci->db->table_exists('pesan') && ! $ci->db->table_exists('pesan_detail')) || empty($ci->setting->api_opendk_key)) {
        return;
    }
    $model_pesan        = new App\Models\Pesan();
    $model_detail_pesan = new App\Models\PesanDetail();
    $id_terakhir        = $model_detail_pesan::latest('id')->first()->id;

    try {
        $client   = new GuzzleHttp\Client();
        $response = $client->post("{$ci->setting->api_opendk_server}/api/v1/pesan/getpesan", [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => "Bearer {$ci->setting->api_opendk_key}",
            ],
            'form_params' => [
                'kode_desa' => kode_wilayah($ci->header['desa']['kode_desa']),
                'id'        => (int) $id_terakhir,
            ],
        ])->getBody()->getContents();
        $data_respon = json_decode($response, null);

        foreach ($data_respon->data as $pesan) {
            $row = [
                'id'         => $pesan->id,
                'judul'      => $pesan->judul,
                'jenis'      => $pesan->jenis,
                'diarsipkan' => $pesan->diarsipkan,
            ];
            $model_pesan::firstOrCreate(['id' => $pesan->id], $row);

            foreach ($pesan->detail_pesan as $pesan_detail) {
                $row = [
                    'id'            => $pesan_detail->id,
                    'pesan_id'      => $pesan_detail->pesan_id,
                    'text'          => $pesan_detail->text,
                    'pengirim'      => $pesan_detail->pengirim,
                    'nama_pengirim' => $pesan_detail->nama_pengirim,
                ];
                $model_detail_pesan::firstOrCreate(['id' => $pesan_detail->id], $row);
                if ($pesan_detail->pengirim == 'kecamatan') {
                    $model_pesan::where('id', '=', $pesan_detail->pesan_id)->update(['sudah_dibaca' => 0]);
                }
            }
        }
    } catch (Exception $e) {
        log_message('error', $e);
    }
}

if (! function_exists('opendk_api')) {
    function opendk_api($path_url = '', $options = [], $method = 'get')
    {
        $ci = &get_instance();

        try {
            $client   = new GuzzleHttp\Client();
            $response = $client->{$method}("{$ci->setting->api_opendk_server}{$path_url}", array_merge(
                [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Authorization'    => "Bearer {$ci->setting->api_opendk_key}",
                    ],
                ],
                $options
            ))->getBody()->getContents();

            $data_respon = json_decode($response, null);
            $notif       = [
                'status' => $data_respon->status,
                'pesan'  => $data_respon->message,
            ];
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            $message = $e->getHandlerContext()['error'];
            $notif   = [
                'status' => 'danger',
                'pesan'  => messageResponseHTML($message),
            ];
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $message = $e->getResponse()->getBody()->getContents();
            $notif   = [
                'status' => 'danger',
                'pesan'  => messageResponseHTML($message),
            ];
        }

        return $notif;
    }
}

if (! function_exists('messageResponseHTML')) {
    function messageResponseHTML($json_msg): string
    {
        $msg  = json_decode($json_msg, 1);
        $html = '<h5>' . $msg['message'] . '</h5>';
        if ($msg['errors']) {
            $html .= '<ul>';

            foreach ($msg['errors'] as $errs) {
                foreach ($errs as $value) {
                    $html .= '<li>' . $value . '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }
}
