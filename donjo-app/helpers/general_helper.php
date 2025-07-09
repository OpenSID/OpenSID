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

use App\Models\Config;
use App\Models\Komentar;
use App\Models\Menu;
use App\Models\Modul;
use App\Models\SettingAplikasi;
use App\Models\User;
use App\Models\Widget;
use App\Repositories\SettingAplikasiRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

/**
 * VERSI
 *
 * Versi OpenSID
 */
define('VERSION', '2508.0.0');

/**
 * VERSI_DATABASE
 * Ubah setiap kali mengubah struktur database atau melakukan proses rilis (tgl 01)
 * Simpan nilai ini di tabel migrasi untuk menandakan sudah migrasi ke versi ini
 * Versi database = [yyyymmdd][nomor urut dua digit]
 * [nomor urut dua digit] : 01 => rilis umum, 51 => rilis bugfix, 71 => rilis premium,
 *
 * Varsi database jika premium = 2025061501, jika umum = 2024101651 (6 bulan setelah rilis premium, namun rilis beta)
 */
define('VERSI_DATABASE', '2025080101');

if (! function_exists('asset')) {
    function asset($uri = '', $default = true)
    {
        if ($default) {
            $uri = 'assets/' . $uri;
        }
        $path = FCPATH . $uri;

        return base_url($uri . '?v' . md5_file($path));
    }
}

if (! function_exists('set_session')) {
    function set_session($key = 'success', $value = '')
    {
        return ci()->session->set_flashdata($key, $value);
    }
}

if (! function_exists('session')) {
    function session($nama = '')
    {
        return ci()->session->flashdata($nama);
    }
}

if (! function_exists('can')) {
    /**
     * Cek akses user
     *
     * @param string|null $akses
     * @param string|null $slugModul
     * @param bool        $adminOnly
     * @param mixed       $demoOnly
     *
     * @return array|bool
     */
    function can($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false)
    {
        if (null === $slugModul) {
            $slugModul = ci()->akses_modul ?? (ci()->sub_modul_ini ?? ci()->modul_ini);
        }

        return Gate::allows("{$slugModul}:{$akses}", [$akses, $slugModul, $adminOnly, $demoOnly]);
    }
}

if (! function_exists('isCan')) {
    /**
     * Cek akses user
     *
     * @param string|null $akses
     * @param string|null $slugModul
     * @param bool        $adminOnly
     * @param mixed       $demoOnly
     */
    function isCan($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false): void
    {
        $pesan = 'Anda tidak memiliki akses untuk halaman tersebut!';
        if (! can('b', $slugModul, $adminOnly, $demoOnly)) {
            set_session('error', $pesan);
            session_error($pesan);

            redirect('beranda');
        } elseif (! can($akses, $slugModul, $adminOnly, $demoOnly)) {
            set_session('error', $pesan);
            session_error($pesan);

            redirect(ci()->controller);
        }
    }
}

// response()->json(array_data);
if (! function_exists('json')) {
    function json($content = [], $header = 200): void
    {
        ci()->output
            ->set_status_header($header)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($content, JSON_THROW_ON_ERROR))
            ->_display();

        exit();
    }
}

// redirect()->ci_route('example')->with('success', 'information');
if (! function_exists('redirect_with')) {
    function redirect_with($key = 'success', $value = '', $to = '', $autodismis = null)
    {
        set_session($key, $value);

        if ($autodismis) {
            set_session('autodismiss', true);
        }

        if (empty($to)) {
            $to = ci()->aliasController ?? ci()->controller;
        }

        return redirect($to);
    }
}

// ci_route('example');
if (! function_exists('ci_route')) {
    function ci_route($to = null, $params = null)
    {
        if (in_array($to, [null, '', '/'])) {
            return site_url();
        }

        $to = str_replace('.', '/', $to);

        if (null !== $params) {
            if (is_array($params)) {
                $params = implode('/', $params);
            }
            $to .= '/' . $params;
        }

        return site_url($to);
    }
}

if (! function_exists('setting')) {
    /**
     * Mengambil nilai dari pengaturan aplikasi.
     *
     * @param mixed|null $key
     * @param mixed|null $value
     *
     * @return mixed|null
     */
    function setting($key = null, $value = null)
    {
        $getSetting = ci()->setting;

        if ($key === null) {
            return $getSetting;
        }

        if ($value === null) {
            return $getSetting->{$key} ?? null;
        }

        return $getSetting->{$key} = $value;
    }
}

// hapus_cache('cache_id');
if (! function_exists('hapus_cache')) {
    function hapus_cache($params = null)
    {
        if ($params) {
            return ci()->cache->hapus_cache_untuk_semua($params);
        }

        return false;
    }
}

if (! function_exists('calculate_days')) {
    /**
     * Calculate minute between 2 date.
     *
     * @return int
     */
    function calculate_days(string $dateStart, string $format = 'Y-m-d')
    {
        return abs(Carbon::createFromFormat($format, $dateStart)->getTimestamp() - Carbon::now()->getTimestamp()) / (60 * 60 * 24);
    }
}

if (! function_exists('calculate_date_intervals')) {
    /**
     * Calculate list dates interval to minutes.
     *
     * @return int
     */
    function calculate_date_intervals(array $date)
    {
        $reference = Carbon::now();
        $endTime   = clone $reference;

        foreach ($date as $dateInterval) {
            if (empty($dateInterval)) {
                continue;
            }
            $endTime = $endTime->add(DateInterval::createFromDateString(calculate_days($dateInterval) . 'days'));
        }

        return $reference->diff($endTime)->days;
    }
}

// Parsedown
if (! function_exists('parsedown')) {
    /**
     * Parsedown.
     *
     * @param string|null $params
     *
     * @return Parsedown|string
     */
    function parsedown($params = null)
    {
        $parsedown = new Parsedown();

        if (null !== $params) {
            return $parsedown->text(file_get_contents(FCPATH . $params));
        }

        return $parsedown;
    }
}

// SebutanDesa('Surat [Desa]');
if (! function_exists('SebutanDesa')) {
    function SebutanDesa($params = null)
    {
        $replaceWord = ['[Desa]', '[desa]', '[Pemerintah Desa]', '[dusun]'];
        if (! Str::contains($params, $replaceWord)) return $params;

        // Tidak bisa gunakan helper setting karena value belum di load
        $setting = SettingAplikasi::whereIn('key', ['sebutan_desa', 'sebutan_pemerintah_desa', 'sebutan_dusun'])->pluck('value', 'key')->toArray();

        return str_replace(
            $replaceWord,
            [ucwords($setting['sebutan_desa']), ucwords($setting['sebutan_desa']), ucwords($setting['sebutan_pemerintah_desa']), ucwords($setting['sebutan_dusun'])],
            $params
        );
    }
}

if (! function_exists('underscore')) {
    /**
     * Membuat spasi menjadi underscore atau sebaliknya
     *
     * @param string $str           string yang akan dibuat spasi
     * @param bool   $to_underscore true jika ingin membuat spasi menjadi underscore, false jika sebaliknya
     * @param bool   $lowercase     true jika ingin mengubah huruf menjadi kecil semua
     *
     * @return string string yang sudah dibuat spasi
     */
    function underscore($str, $to_underscore = true, $lowercase = false): string
    {
        // membersihkan string di akhir dan di awal
        $str = trim($str);

        // membuat text lowercase jika diperlukan
        if ($lowercase) {
            $str = MB_ENABLED ? mb_strtolower($str) : strtolower($str);
        }

        // menyajikan hasil akhir
        return $to_underscore ? str_replace(' ', '_', $str) : str_replace('_', ' ', $str);
    }
}

if (! function_exists('akun_demo')) {
    /**
     * Membuat batasan agar akun demo tidak dapat dihapus pada demo_mode
     *
     * @param int   $id
     * @param mixed $redirect
     */
    function akun_demo($id, $redirect = true)
    {
        if (config_item('demo_mode') && in_array($id, array_keys(config_item('demo_akun')))) {
            if ($redirect) {
                redirect_with('error', 'Tidak dapat mengubah / menghapus akun demo');
            }

            return true;
        }
    }
}

if (! function_exists('folder')) {
    /**
     * Membuat folder jika tidak tersedia
     *
     * @param string     $folder
     * @param string     $permissions
     * @param mixed|null $htaccess
     * @param array|null $extra
     */
    function folder($folder = null, $permissions = 0755, $htaccess = null, array $extra = []): bool
    {
        $hasil = true;

        ci()->load->helper('file');

        $folder = FCPATH . $folder;

        // Buat folder
        $hasil = is_dir($folder) || mkdir($folder, $permissions, true);

        if ($hasil) {
            if ($htaccess !== null) {
                write_file($folder . '.htaccess', config_item($htaccess), 'x');
            }

            // File index.html
            write_file($folder . 'index.html', config_item('index_html'), 'x');

            foreach ($extra as $value) {
                $file    = realpath($value);
                $newfile = realpath($folder) . DIRECTORY_SEPARATOR . basename($value);

                copy($file, $newfile);
            }

            return true;
        }

        return false;
    }
}

if (! function_exists('folder_desa')) {
    /**
     * Membuat folder desa dan isinya
     */
    function folder_desa(): bool
    {
        ci()->load->config('installer');
        $list_folder = array_merge(config_item('desa'), config_item('lainnya'));

        // Buat folder dan subfolder desa
        foreach ($list_folder as $folder => $lainnya) {
            folder($folder, $lainnya[0], $lainnya[1], $lainnya[2] ?? []);
        }

        write_file(LOKASI_CONFIG_DESA . 'config.php', config_item('config'), 'x');
        write_file(LOKASI_CONFIG_DESA . 'database.php', config_item('database'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman.css', config_item('siteman_css'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman_mandiri.css', config_item('siteman_mandiri_css'), 'x');
        write_file(DESAPATH . 'app_key', set_app_key(), 'x');

        // set config app.key untuk proses intall
        config()->set('app.key', get_app_key());

        return true;
    }
}

if (! function_exists('ci_auth')) {
    /**
     * Ambil data user login
     *
     * @param mixed|null $params
     */
    function ci_auth($params = null)
    {
        $CI = &get_instance();

        if (null !== $params) {
            return $CI->session->isAdmin->{$params};
        }

        return $CI->session->isAdmin;
    }
}

if (! function_exists('ci_db')) {
    function ci_db()
    {
        return ci()->db;
    }
}

/**
 * Dipanggil untuk setiap kode isian ditemukan,
 * dan diganti dengan kata pengganti yang huruf besar/kecil mengikuti huruf kode isian.
 * Berdasarkan contoh di http://stackoverflow.com/questions/19317493/php-preg-replace-case-insensitive-match-with-case-sensitive-replacement
 *
 * @param string $dari
 * @param string $ke
 * @param string $str
 *
 * @return void
 */
if (! function_exists('case_replace')) {
    function case_replace($dari, $ke, $str)
    {
        $replacer = static function (array $matches) use ($ke) {
            // Remove brackets from the match
            $matches = array_map(static fn ($match) => preg_replace('/[\[\]]/', '', $match), $matches);

            // Apply case transformation
            return caseWord($matches[0], $ke);
        };

        // Escape brackets and forward slashes in the search pattern
        $dari = str_replace(['[', ']', '/'], ['\\[', '\\]', '\\/'], $dari);

        // Perform case-insensitive replacement with a callback
        return preg_replace_callback('/(' . $dari . ')/i', $replacer, $str);
    }
}

if (! function_exists('kirim_versi_opensid')) {
    function kirim_versi_opensid($kode_desa): void
    {
        if (! config_item('demo_mode') && ! empty($kode_desa) && ENVIRONMENT === 'production') {
            $ci = get_instance();
            $ci->load->driver('cache');

            $versi = AmbilVersi();
            if ($versi != $ci->cache->file->get('versi_app_cache')) {
                try {
                    $client = new GuzzleHttp\Client();
                    $client->post(config_item('server_layanan') . '/api/v1/pelanggan/catat-versi', [
                        'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                        'form_params' => [
                            'kode_desa' => kode_wilayah($kode_desa),
                            'versi'     => $versi,
                        ],
                    ])
                        ->getBody();
                    $ci->cache->file->save('versi_app_cache', $versi);
                } catch (Exception $e) {
                    log_message('error', $e);
                }
            }
        }
    }
}

if (! function_exists('kotak')) {
    function kotak(?string $data_kolom, int $max_kolom = 26): string
    {
        $view = '';

        for ($i = 0; $i < $max_kolom; $i++) {
            $view .= '<td class="kotak padat tengah">';
            if (isset($data_kolom[$i])) {
                $view .= strtoupper($data_kolom[$i]);
            } else {
                $view .= '&nbsp;';
            }
            $view .= '</td>';
        }

        return $view;
    }
}

if (! function_exists('checklist')) {
    function checklist($kondisi_1, $kondisi_2): string
    {
        $view = '<td class="kotak padat tengah">';
        if ($kondisi_1 == $kondisi_2) {
            $view .= '<img src="' . FCPATH . 'assets/images/check.png' . '" height="10" width="10"/>';
        }

        return $view . '</td>';
    }
}

if (! function_exists('create_tree_folder')) {
    function create_tree_folder($arr, string $baseDir)
    {
        if (! empty($arr)) {
            $tmp = '<ul class="tree-folder">';

            foreach ($arr as $i => $val) {
                if (is_array($val)) {
                    $permission     = decoct(fileperms($baseDir . DIRECTORY_SEPARATOR . $i) & 0777);
                    $iconPermission = $permission === decoct(DESAPATHPERMISSION) ? '<i class="fa fa-check-circle-o fa-lg pull-right" style="color:green"></i>' : '<i class="fa fa-times-circle-o fa-lg pull-right" style="color:red"></i>';
                    $liClass        = $permission === decoct(DESAPATHPERMISSION) ? 'text-green' : 'text-red';
                    $tmp .= '<li class="' . $liClass . '"  data-path="' . preg_replace('/\/+/', '/', $baseDir . DIRECTORY_SEPARATOR . $i) . '">' . $i . '(' . $permission . ') ' . $iconPermission;
                    $tmp .= create_tree_folder($val, $baseDir . $i);
                    $tmp .= '</li>';
                }
            }

            return $tmp . '</ul>';
        }
    }
}

if (! function_exists('generatePengikut')) {
    function generatePengikut($pengikut, $keterangan): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NIK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Nama Lengkap</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Jenis Kelamin</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Tempat Lahir</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Tanggal Lahir</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">SHDK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Keterangan</th>
                        </tr>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">1</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">2</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">3</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">4</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">5</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">6</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">7</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">8</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;

        foreach ($pengikut as $data) {
            $html .= '
                            <tr>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:3%">' . $no++ . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:18%">' . $data->nik . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%" nowrap>' . $data->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:7%" nowrap>' . $data->jenisKelamin->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:10%" nowrap>' . $data->tempatlahir . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:5%" nowrap>' . tgl_indo_out($data->tanggallahir) . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:8%" nowrap>' . $data->pendudukHubungan->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:20%">' . ($keterangan[$data->id] ?? '') . '</td>
                            </tr>
                            ';
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}

if (! function_exists('generatePengikutSuratKIS')) {
    function generatePengikutSuratKIS($pengikut): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NAMA</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NIK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">JENIS <br/>KELAMIN</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">TEMPAT <br/>TANGGAL LAHIR</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">PEKERJAAN</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">ALAMAT</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;

        foreach ($pengikut as $data) {
            $html .= '
                            <tr>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:3%">' . $no++ . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:18%">' . $data->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%" nowrap>' . $data->nik . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:11%" nowrap>' . $data->jenisKelamin->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:11%" nowrap>' . $data->tempatlahir . ', ' . tgl_indo_out($data->tanggallahir) . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%" nowrap>' . $data->pekerjaan->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:20%">' . $data->alamat_wilayah . '</td>
                            </tr>
                            ';
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}

if (! function_exists('generatePengikutKartuKIS')) {
    function generatePengikutKartuKIS($kis): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO. KARTU</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NAMA DI KARTU</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NIK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">ALAMAT DI KARTU</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">TANGGAL LAHIR</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">FASKES <br/>TINGKAT I</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;

        foreach ($kis as $data) {
            $html .= '
                            <tr>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:3%">' . $no++ . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:18%">' . $data['kartu'] . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%" nowrap>' . $data['nama'] . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:17%" nowrap>' . $data['nik'] . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%" nowrap>' . $data['alamat'] . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%" nowrap>' . $data['tanggallahir'] . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:13%">' . $data['faskes'] . '</td>
                            </tr>
                            ';
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}

if (! function_exists('generatePengikutPindah')) {
    function generatePengikutPindah($pengikut): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NIK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NAMA</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">MASA BERLAKU <br/>KTP S/D</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">SHDK</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;

        foreach ($pengikut as $data) {
            $html .= '
                            <tr>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:3%">' . $no++ . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:25%" nowrap>' . $data->nik . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:25%">' . $data->nama . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:22%" nowrap> Seumur Hidup</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:25%">' . $data->pendudukHubungan->nama . '</td>
                            </tr>
                            ';
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}
function tidak_ada_data($col = 12, string $message = 'Data Tidak Tersedia'): void
{
    $html = '
        <tr>
            <td class="text-center" colspan="' . $col . '">' . $message . '</td>
        </tr>';
    echo $html;
}

if (! function_exists('data_lengkap')) {
    function data_lengkap(): bool
    {
        $CI = &get_instance();

        return (bool) $CI->setting->tgl_data_lengkap_aktif;
    }
}

if (! function_exists('buat_class')) {
    function buat_class($class1 = '', $class2 = '', $required = false): string
    {
        $onlyClass = '';
        preg_match('/class="([^"]+)"/', $class1, $match);
        if ($match) {
            $onlyClass = $match[1];
        }

        $onlyAttributes = preg_replace('/class="[^"]+"/', '', $class1);

        if (empty($class2) || $class2 === null) {
            $class2 = 'form-control input-sm';
        }

        if ($required) {
            $onlyClass .= ' required';
        }

        return 'class="' . $class2 . ' ' . $onlyClass . '" ' . $onlyAttributes;
    }
}

if (! function_exists('cek_lokasi_peta')) {
    function cek_lokasi_peta(array $wilayah): bool
    {
        if ($wilayah['dusun'] == '-') {
            $wilayah = identitas();
        }

        return $wilayah['path'] && ($wilayah['lat'] && ! empty($wilayah['lng']));
    }
}

if (! function_exists('config_email')) {
    function config_email()
    {
        return [
            'active'    => (int) setting('email_notifikasi'),
            'protocol'  => setting('email_protocol'),
            'smtp_host' => setting('email_smtp_host'),
            'smtp_user' => setting('email_smtp_user'),
            'smtp_pass' => setting('email_smtp_pass'),
            'smtp_port' => (int) setting('email_smtp_port'),
        ];
    }
}

// source: https://stackoverflow.com/questions/12553160/getting-visitors-country-from-their-ip
if (! function_exists('geoip_info')) {
    function geoip_info($ip = null, $purpose = 'location', $deep_detect = true)
    {
        $output = null;
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }
        $purpose    = str_replace(['name', "\n", "\t", ' ', '-', '_'], null, strtolower(trim($purpose)));
        $support    = ['country', 'countrycode', 'state', 'region', 'city', 'location', 'address'];
        $continents = [
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America',
        ];
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case 'location':
                        $output = [
                            'city'           => @$ipdat->geoplugin_city,
                            'state'          => @$ipdat->geoplugin_regionName,
                            'country'        => @$ipdat->geoplugin_countryName,
                            'country_code'   => @$ipdat->geoplugin_countryCode,
                            'continent'      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            'continent_code' => @$ipdat->geoplugin_continentCode,
                        ];
                        break;

                    case 'address':
                        $address = [$ipdat->geoplugin_countryName];
                        if (@$ipdat->geoplugin_regionName !== '') {
                            $address[] = $ipdat->geoplugin_regionName;
                        }
                        if (@$ipdat->geoplugin_city !== '') {
                            $address[] = $ipdat->geoplugin_city;
                        }
                        $output = implode(', ', array_reverse($address));
                        break;

                    case 'city':
                        $output = @$ipdat->geoplugin_city;
                        break;

                    case 'state':

                    case 'region':
                        $output = @$ipdat->geoplugin_regionName;
                        break;

                    case 'country':
                        $output = @$ipdat->geoplugin_countryName;
                        break;

                    case 'countrycode':
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }

        return $output;
    }
}

if (! function_exists('batal')) {
    function batal(): string
    {
        return '<button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>';
    }
}

if (! function_exists('sensorEmail')) {
    function sensorEmail($email): string
    {
        if (! $email || null === $email) {
            return '';
        }
        $atPosition = strpos($email, '@');

        $firstPart  = substr($email, 0, 2);
        $secondPart = substr($email, 1, $atPosition - 2);
        $lastPart   = substr($email, $atPosition);

        return $firstPart . str_repeat('*', strlen($secondPart)) . $lastPart;
    }
}

if (! function_exists('gis_simbols')) {
    function gis_simbols()
    {
        $simbols = DB::table('gis_simbol')->where('config_id', identitas('id'))->get('simbol');

        return $simbols->map(static fn ($item): array => (array) $item)->toArray();
    }
}

if (! function_exists('admin_menu')) {
    /**
     * admin_menu untuk menampilkan menu admin yang aktif.
     *
     * @return mixed
     */
    function admin_menu()
    {
        $grupId = ci_auth()->id_grup;

        return cache()->rememberForever("{$grupId}_admin_menu", static fn () => (new Modul())->tree($grupId)->toArray());
    }
}

if (! function_exists('menu_tema')) {
    /**
     * admin_menu untuk menampilkan menu admin yang aktif.
     *
     * @return mixed
     */
    function menu_tema()
    {
        return cache()->rememberForever('menu_tema', static fn () => (new Menu())->tree()->toArray());
    }
}

if (! function_exists('createDropdownMenu')) {
    function createDropdownMenu($menuData, $level = 0): void
    {
        if ($level) {
            echo '<ul class="dropdown-menu">';
        }

        foreach ($menuData as $item) {
            $level++;
            echo '<li class="dropdown"><a class="dropdown-toggle" href="' . $item['link_url'] . '">' . $item['nama'] . '</a>';
            if (! empty($item['childrens'])) {
                createDropdownMenu($item['childrens'], $level);
            }
            echo '</li>';
        }
        if ($level) {
            echo '</ul>';
        }
    }
}

/**
 * Fungsi untuk memecah nama dan gelar
 *
 * @param string $nama
 *
 * @return array
 */
// TODO:: Masih bermasalah untuk nama dengan singkatan, misalnya M., Muh. Moh., A. karena akan terbaca sebagai gelar depan
if (! function_exists('pecah_nama_gelar')) {
    function pecah_nama_gelar($nama): array
    {
        $result = [];

        // Split the input string by comma
        $parts = explode(',', $nama);

        // Remove leading and trailing whitespace from each part
        foreach ($parts as &$part) {
            $part = trim($part);
        }

        // Determine the components based on the number of parts
        if (count($parts) === 1) {
            // Case: Single part
            $result['nama'] = $parts[0];
        } else {
            // Case: More than one part
            $gelar_depan    = '';
            $nama           = '';
            $gelar_belakang = '';

            // Check for prefix (gelar_depan)
            $firstPart   = trim($parts[0]);
            $dotPosition = strrpos($firstPart, '.');
            if ($dotPosition !== false) {
                $gelar_depan = substr($firstPart, 0, $dotPosition + 1);
                $nama        = trim(substr($firstPart, $dotPosition + 1));
            } else {
                $nama = $firstPart;
            }
            // Combine the rest as gelar_belakang
            $counter = count($parts);

            // Combine the rest as gelar_belakang
            for ($i = 1; $i < $counter; $i++) {
                $gelar_belakang .= ($i > 1 ? ', ' : '') . $parts[$i];
            }

            $result['gelar_depan']    = $gelar_depan;
            $result['nama']           = $nama;
            $result['gelar_belakang'] = $gelar_belakang;
        }

        return $result;
    }
}

if (! function_exists('invalid_tags')) {
    function invalid_tags()
    {
        return [
            '<center>',
            '<article>',
            '<aside>',
            '<details>',
            '<figcaption>',
            '<figure>',
            '<header>',
            '<main>',
            '<nav>',
            '<section>',
            '<time>',
        ];
    }
}

if (! function_exists('reset_auto_increment')) {
    /**
     * Reset auto increment.
     *
     * @param string $table
     * @param string $column
     *
     * @return void
     */
    function reset_auto_increment($table, $column = 'id')
    {
        $max_id = DB::table($table)->max($column);
        DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = " . ($max_id + 1));
    }
}

// TODO:: Hapus ini jika sudah menggunakan ORM Laravel semua
if (! function_exists('shortcut_cache')) {
    function shortcut_cache()
    {
        User::pluck('id')->each(static function ($id) {
            cache()->forget('shortcut_' . $id);
        });
    }
}

if (! function_exists('emptyData')) {
    function emptyData($fields): array
    {
        $data = [];

        foreach ($fields as $key => $value) {
            $data[$value] = '';
        }

        return $data;
    }
}

if (! function_exists('total_jumlah')) {
    function total_jumlah($data, $column)
    {
        return array_reduce($data->toArray(), static fn ($carry, $item) => $carry + $item[$column], 0);
    }
}

if (! function_exists('truncateText')) {
    function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . '...';
        }

        return $text;
    }
}

// auth_mandiri
if (! function_exists('auth_mandiri')) {
    function auth_mandiri($params = null)
    {
        $CI = &get_instance();

        if (null !== $params) {
            return $CI->session->auth_mandiri->{$params};
        }

        return $CI->session->auth_mandiri;
    }
}

// format_penomoran_surat
if (! function_exists('format_penomoran_surat')) {
    function format_penomoran_surat($isGlobal = false, $formatGlobal = '', $formatLocal = '')
    {
        if ($isGlobal == false && ! empty($formatLocal)) {
            return $formatLocal;
        }

        return $formatGlobal;
    }
}

/**
 * Fungsi untuk menghapus folder beserta isinya
 * Termasuk folder tersembunyi
 *
 * @param string $dirPath
 *
 * @return bool
 */
if (! function_exists('deleteDir')) {
    function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            return false;
        }

        // Memastikan izin semua file dan folder diubah sehingga dapat dihapus
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            // Ubah izin file dan folder agar dapat dihapus
            chmod($item->getRealPath(), 0777);

            if ($item->isDir()) {
                rmdir($item->getRealPath());
            } else {
                unlink($item->getRealPath());
            }
        }

        // Hapus direktori utama setelah isi dihapus
        rmdir($dirPath);

        return true;
    }
}

if (! function_exists('create_tree_file')) {
    function create_tree_file($arr, string $baseDir)
    {
        if (! empty($arr)) {
            $tmp = '<ul class="tree-folder">';

            foreach ($arr as $i => $val) {
                $iconPermission = '<i class="fa fa-times-circle-o fa-lg pull-right" style="color:red"></i>';
                $liClass        = 'text-red';
                $currentPath    = is_array($val) ? $i : $val;
                $tmp .= '<li class="' . $liClass . '"  data-path="' . preg_replace('/\/+/', '/', $baseDir . DIRECTORY_SEPARATOR . $currentPath) . '">' . $currentPath . ' ' . $iconPermission;
                $tmp .= create_tree_file($val, $baseDir . $i);
                $tmp .= '</li>';
            }

            return $tmp . '</ul>';
        }
    }
}

if (! function_exists('getWidgetSetting')) {
    /**
     * Ambil setting widget
     *
     * @param int $namaWidget
     * @param int $opsi       (optional)
     */
    function getWidgetSetting($namaWidget, $opsi = null)
    {
        return Widget::getSetting($namaWidget, $opsi);
    }
}

if (! function_exists('bacaKomentar')) {
    /**
     * jumlah baca komentar pada artikel
     *
     * @param int $idArtikel
     */
    function bacaKomentar($idArtikel)
    {
        // return $this->db->query("SELECT * FROM komentar WHERE id_artikel = '".$data['id']."'");
        return Komentar::jumlahBaca($idArtikel);
    }
}
