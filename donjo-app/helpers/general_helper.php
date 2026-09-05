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
    function can($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false, ?User $user = null)
    {
        if (null === $slugModul) {
            $slugModul = ci()->akses_modul ?? (ci()->sub_modul_ini ?? ci()->modul_ini);
        }

        if (null !== $user) {
            return Gate::forUser($user)->allows("{$slugModul}:{$akses}", [$akses, $slugModul, $adminOnly, $demoOnly]);
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
    function isCan($akses = null, $slugModul = null, $adminOnly = false, $demoOnly = false, ?User $user = null): void
    {
        $pesan = 'Anda tidak memiliki akses untuk halaman tersebut!';
        if (! can('b', $slugModul, $adminOnly, $demoOnly, $user)) {
            set_session('error', $pesan);
            session_error($pesan);

            redirect('beranda');
        } elseif (! can($akses, $slugModul, $adminOnly, $demoOnly, $user)) {
            set_session('error', $pesan);
            session_error($pesan);

            redirect(ci()->controller);
        }
    }
}

if (! function_exists('isMultiDB')) {
    /**
     * Cek apakah aplikasi menggunakan multi database
     *
     * @return void
     */
    function isMultiDB()
    {
        if (setting('multi_desa')) {
            $pesan = 'Anda tidak memiliki akses untuk halaman tersebut!';
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

if (! function_exists('ci_route')) {
    /**
     * Mengkonversi dot notation menjadi path URL dan mendukung parameter tambahan.
     *
     * @param string|null       $to     Route destination (dapat menggunakan dot notation seperti 'controller.method')
     * @param array|string|null $params Parameter tambahan untuk URL (array akan di-implode dengan '/')
     *
     * @return string
     *
     * @example
     * ```php
     * // Basic usage
     * echo ci_route(); // Output: site_url()
     * echo ci_route('home'); // Output: site_url('home')
     *
     * // Dot notation conversion
     * echo ci_route('user.profile'); // Output: site_url('user/profile')
     *
     * // With parameters
     * echo ci_route('user.edit', '123'); // Output: site_url('user/edit/123')
     * echo ci_route('user.edit', ['123', 'profile']); // Output: site_url('user/edit/123/profile')
     *
     * // Bypass processing for index.php routes
     * echo ci_route('index.php/database'); // Output: site_url('index.php/database')
     * ```
     */
    function ci_route($to = null, $params = null)
    {
        if (in_array($to, [null, '', '/'])) {
            return site_url();
        }

        if (strpos($to, 'index.php') !== false) {
            return site_url($to);
        }

        $to = str_replace('.', '/', $to);

        if ($params !== null) {
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
        if (! ci()->setting) {
            SettingAplikasiRepository::applySettingCI(ci());
        }

        $setting = ci()->setting;

        if (null === $key) {
            return $setting;
        }

        if (null === $value) {
            return $setting->{$key} ?? null;
        }

        return $setting->{$key} = $value;
    }
}

// hapus_cache('cache_id');
if (! function_exists('hapus_cache')) {
    function hapus_cache($params = null)
    {
        ci()->load->driver('cache', ['adapter' => 'file', 'backup' => 'dummy']);

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

if (! function_exists('SebutanDesa')) {
    /**
     * Mengganti kata [Desa], [desa], [Pemerintah Desa], [dusun] sesuai pengaturan.
     *
     * @param string|null $params
     *
     * @return string|null
     */
    function SebutanDesa($params = null)
    {
        $replaceWord = ['[Desa]', '[desa]', '[Pemerintah Desa]', '[dusun]'];
        if (! Str::contains($params, $replaceWord)) {
            return $params;
        }

        // Tidak bisa gunakan helper setting karena value belum di load
        $setting = SettingAplikasi::whereIn('key', ['sebutan_desa', 'sebutan_pemerintah_desa', 'sebutan_dusun', 'default_tampil_peta_infrastruktur'])->pluck('value', 'key')->toArray();

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
        if (empty($folder) || ! is_string($folder)) {
            return false;
        }

        ci()->load->helper('file');

        $folderPath = FCPATH . $folder;

        // Buat folder
        $hasil = is_dir($folderPath) || mkdir($folderPath, $permissions, true);

        if ($hasil) {
            if ($htaccess !== null) {
                write_file($folderPath . '.htaccess', config_item($htaccess), 'x');
            }

            // File index.html
            write_file($folderPath . 'index.html', config_item('index_html'), 'x');

            foreach ($extra as $value) {
                $file    = realpath($value);
                $newfile = realpath($folderPath) . DIRECTORY_SEPARATOR . basename($value);

                if ($file && $newfile) {
                    copy($file, $newfile);
                }
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

if (! function_exists('case_replace')) {
    /**
     * Melakukan penggantian teks dengan mempertahankan pola huruf besar/kecil dari teks asli.
     *
     * Fungsi ini mencari kemunculan pola tertentu dan menggantinya dengan string pengganti,
     * sambil mempertahankan pola huruf (besar/kecil) dari teks yang cocok asli.
     *
     * @param string $dari Pola/teks pencarian yang akan diganti
     * @param string $ke   Teks pengganti
     * @param string $str  String input tempat penggantian akan dilakukan
     *
     * @return string String yang telah dimodifikasi dengan penggantian diterapkan, mempertahankan pola huruf
     *
     * @see http://stackoverflow.com/questions/19317493/php-preg-replace-case-insensitive-match-with-case-sensitive-replacement
     */
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
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:7%" nowrap>' . $data->jenis_kelamin . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:10%" nowrap>' . $data->tempatlahir . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:5%" nowrap>' . tgl_indo_out($data->tanggallahir) . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:8%" nowrap>' . $data->penduduk_hubungan . '</td>
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
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:11%" nowrap>' . $data->jenis_kelamin . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:11%" nowrap>' . $data->tempatlahir . ', ' . tgl_indo_out($data->tanggallahir) . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%" nowrap>' . $data->pekerjaan . '</td>
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

if (! function_exists('generatePengikutSuratPI')) {
    function generatePengikutSuratPI($pengikut): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NO</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NAMA</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">NIK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">SHDK</th>
                            <th style="border-color: #000000; border-style: solid; border-collapse: collapse">Keterangan</th>
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
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:20%">' . $data->penduduk_hubungan . '</td>
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:20%">' . $data->ket . '</td>
                            </tr>
                            ';
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}

// perubahan identitas penduduk - Pendidikan dan Pekerjaan
if (! function_exists('generatePengikutPiPendidikanPekerjaan')) {
    function generatePengikutPiPendidikanPekerjaan($semua_anggota, $perubahan_data): string
    {
        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th rowspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">No</th>
                            <th colspan="6" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Elemen Data</th>
                            <th rowspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Keterangan</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Pendidikan</th>
                            <th colspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Pekerjaan </th>
                        </tr>
                        <tr>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Semula</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Menjadi</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Dasar Perubahan</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Semula</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Menjadi</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Dasar Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;
        if (! empty($semua_anggota)) {
            foreach ($semua_anggota as $anggota) {
                $perubahan = $perubahan_data[$anggota->nik] ?? null;
                $html .= '
                    <tr>
                        <td style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse; width:3%; font-size: 8pt;">' . $no++ . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:18%; font-size: 8pt;">' . ($perubahan['pendidikan_semula'] ?? '-') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%; font-size: 8pt;">' . ($perubahan['pendidikan_menjadi'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:17%; font-size: 8pt;">' . ($perubahan['pendidikan_dasar_perubahan'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%; font-size: 8pt;">' . ($perubahan['pekerjaan_semula'] ?? '-') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%; font-size: 8pt;">' . ($perubahan['pekerjaan_menjadi'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%; font-size: 8pt;">' . ($perubahan['pekerjaan_dasar_perubahan'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:13%; font-size: 8pt;">' . ($perubahan['keterangan'] ?? '') . '</td>
                    </tr>
                    ';
            }
        }

        return $html . '
                    </tbody>
                </table>
            ';
    }
}

// perubahan identitas penduduk - Agama dan Lainnya
if (! function_exists('generatePengikutPiAgamaLainnya')) {
    function generatePengikutPiAgamaLainnya($semua_anggota, $perubahan_data, $lainnya_pilihan = []): string
    {
        $lainnya_text = 'Lainnya, yaitu: ';
        if (! empty($lainnya_pilihan)) {
            $enum_values     = App\Enums\PerubahanDataPiEnum::valuesToUpper();
            $selected_values = array_map(static fn ($key) => $enum_values[$key] ?? '', $lainnya_pilihan);
            $lainnya_text .= implode(', ', array_filter($selected_values));
        }

        $html = '
                <table width="100%" border=1 style="font-size:8pt;text-align:center; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th rowspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">No</th>
                            <th colspan="6" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Elemen Data</th>
                            <th rowspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Keterangan</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Agama</th>
                            <th colspan="3" style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">' . $lainnya_text . '</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Semula</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Menjadi</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Dasar Perubahan</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Semula</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Menjadi</th>
                            <th style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse">Dasar Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>';
        $no = 1;
        if (! empty($semua_anggota)) {
            foreach ($semua_anggota as $anggota) {
                $perubahan = $perubahan_data[$anggota->nik] ?? null;
                $html .= '
                    <tr>
                        <td style="text-align: center;border-color: #000000; border-style: solid; border-collapse: collapse; width:3%; font-size: 8pt;">' . $no++ . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:18%; font-size: 8pt;">' . ((! empty($perubahan['agama_menjadi']) && ! empty($perubahan['agama_dasar_perubahan'])) ? ($perubahan['agama_semula'] ?? '-') : '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%; font-size: 8pt;">' . ($perubahan['agama_menjadi'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:17%; font-size: 8pt;">' . ($perubahan['agama_dasar_perubahan'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%; font-size: 8pt;">' . ($perubahan['lainnya_semula'] ?? '-') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:16%; font-size: 8pt;">' . ($perubahan['lainnya_menjadi'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:15%; font-size: 8pt;">' . ($perubahan['lainnya_dasar_perubahan'] ?? '') . '</td>
                        <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:13%; font-size: 8pt;">' . ($perubahan['keterangan'] ?? '') . '</td>
                    </tr>
                    ';
            }
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
                                <td style="border-color: #000000; border-style: solid; border-collapse: collapse; width:25%">' . $data->penduduk_hubungan . '</td>
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

if (! function_exists('geoip_info')) {
    /**
     * Mengambil informasi geolokasi berdasarkan alamat IP menggunakan layanan.
     *
     * @param string|null $ip          Alamat IP yang ingin dicek. Jika null, akan menggunakan IP dari request.
     * @param string      $purpose     Tujuan pengambilan data: location, address, city, state, region, country, countrycode.
     * @param bool        $deep_detect Jika true, akan memeriksa HTTP_X_FORWARDED_FOR dan HTTP_CLIENT_IP untuk IP asli.
     *
     * @see https://api.ipbase.com/v1/json/
     *
     * @return array|string|null
     */
    function geoip_info($ip = null, $purpose = 'location', $deep_detect = true)
    {
        $output = null;

        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            if ($deep_detect) {
                if (filter_var($_SERVER['HTTP_X_FORWARDED_FOR'] ?? null, FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (filter_var($_SERVER['HTTP_CLIENT_IP'] ?? null, FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }

        $purpose = str_replace(['name', "\n", "\t", ' ', '-', '_'], '', strtolower(trim($purpose)));
        $support = ['country', 'countrycode', 'state', 'region', 'city', 'location', 'address'];

        // Mapping country code to continent
        $continents = [
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America',
        ];

        // Simple continent detection based on country code
        $countryContinentMap = [
            'ID' => 'AS', 'MY' => 'AS', 'SG' => 'AS', 'TH' => 'AS', 'VN' => 'AS', 'PH' => 'AS',
            'CN' => 'AS', 'JP' => 'AS', 'KR' => 'AS', 'IN' => 'AS', 'BD' => 'AS', 'PK' => 'AS',
            'US' => 'NA', 'CA' => 'NA', 'MX' => 'NA', 'BR' => 'SA', 'AR' => 'SA', 'CL' => 'SA',
            'GB' => 'EU', 'DE' => 'EU', 'FR' => 'EU', 'IT' => 'EU', 'ES' => 'EU', 'NL' => 'EU',
            'AU' => 'OC', 'NZ' => 'OC', 'EG' => 'AF', 'ZA' => 'AF', 'NG' => 'AF', 'KE' => 'AF',
        ];

        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            try {
                $client = new GuzzleHttp\Client([
                    'timeout' => 1.5,
                ]);

                $response = $client->get("https://api.ipbase.com/v1/json/{$ip}");
                $ipdat    = json_decode($response->getBody()->getContents());

                if (empty($ipdat->country_code)) {
                    return null;
                }

                // Determine continent based on country code
                $continentCode = $countryContinentMap[$ipdat->country_code] ?? null;
                $continent     = $continentCode ? $continents[$continentCode] : null;

                switch ($purpose) {
                    case 'location':
                        $output = [
                            'city'           => $ipdat->city ?? null,
                            'state'          => $ipdat->region_name ?? null,
                            'country'        => $ipdat->country_name ?? null,
                            'country_code'   => $ipdat->country_code ?? null,
                            'continent'      => $continent,
                            'continent_code' => $continentCode,
                        ];
                        break;

                    case 'address':
                        $address = array_filter([
                            $ipdat->city ?? null,
                            $ipdat->region_name ?? null,
                            $ipdat->country_name ?? null,
                        ]);
                        $output = $address ? implode(', ', array_reverse($address)) : null;
                        break;

                    case 'city':
                        $output = $ipdat->city ?? null;
                        break;

                    case 'state':
                    case 'region':
                        $output = $ipdat->region_name ?? null;
                        break;

                    case 'country':
                        $output = $ipdat->country_name ?? null;
                        break;

                    case 'countrycode':
                        $output = $ipdat->country_code ?? null;
                        break;

                    default:
                        $output = null;
                        break;
                }
            } catch (GuzzleHttp\Exception\RequestException $e) {
                logger()->warning($e->getMessage());
            } catch (Throwable $e) {
                logger()->error($e->getMessage());
            }
        }

        return $output;
    }
}

if (! function_exists('batal')) {
    /**
     * Generate a cancel/reset button.
     */
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
    /**
     * Memotong teks jika melebihi panjang maksimum dan menambahkan elipsis.
     *
     * @param string $text      Teks yang akan dipotong
     * @param int    $maxLength Panjang maksimum teks
     *
     * @return string Teks yang sudah dipotong
     */
    function truncateText($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . '...';
        }

        return $text;
    }
}

if (! function_exists('auth_mandiri')) {
    /**
     * Ambil data auth mandiri dari session.
     *
     * @param string|null $params (optional) Nama properti spesifik yang ingin diambil
     *
     * @return mixed Objek auth_mandiri atau nilai properti spesifik
     */
    function auth_mandiri($params = null)
    {
        $CI = &get_instance();

        if (null !== $params) {
            return $CI->session->auth_mandiri->{$params};
        }

        return $CI->session->auth_mandiri;
    }
}

if (! function_exists('format_penomoran_surat')) {
    /**
     * Memilih format penomoran surat berdasarkan pengaturan global atau lokal.
     *
     * @param bool   $isGlobal     Menentukan apakah menggunakan format global (true) atau lokal (false)
     * @param string $formatGlobal Format penomoran surat global
     * @param string $formatLocal  Format penomoran surat lokal
     *
     * @return string Format penomoran surat yang dipilih
     */
    function format_penomoran_surat($isGlobal = false, $formatGlobal = '', $formatLocal = '')
    {
        if ($isGlobal == false && ! empty($formatLocal)) {
            return $formatLocal;
        }

        return $formatGlobal;
    }
}

if (! function_exists('deleteDir')) {
    /**
     * Menghapus direktori beserta isinya secara rekursif.
     *
     * @param string $dirPath Path direktori yang akan dihapus
     *
     * @return bool True jika berhasil, false jika gagal
     */
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
    /**
     * Membuat struktur pohon file dan folder dalam format HTML.
     *
     * @param array  $arr     Array yang berisi struktur file dan folder
     * @param string $baseDir Direktori dasar untuk path file
     *
     * @return string|null HTML yang merepresentasikan struktur pohon
     */
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
        return Komentar::jumlahBaca($idArtikel);
    }
}

if (! function_exists('buildTree')) {
    /**
     * Membangun struktur pohon dari array datar berdasarkan kolom parent dan referensi.
     *
     * @param array  $elements        Array data datar
     * @param string $parentColumn    Nama kolom yang menunjukkan parent (default: 'parent_id')
     * @param string $referenceColumn Nama kolom yang menjadi referensi (default: 'id')
     * @param mixed  $parentId        ID parent untuk memulai (default: null)
     *
     * @return array Struktur pohon
     */
    function buildTree(array $elements, $parentColumn = 'parent_id', $referenceColumn = 'id', $parentId = null)
    {
        $branch = [];

        foreach ($elements as &$element) {
            if ($element[$parentColumn] === $parentId) {
                $children = buildTree($elements, $parentColumn, $referenceColumn, $element[$referenceColumn]);
                if ($children) {
                    $element['children'] = $children;
                } else {
                    $element['children'] = [];
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}

if (! function_exists('compressPng')) {
    /**
     * Kompresi gambar PNG
     *
     * @param string $path    Path file gambar PNG
     * @param int    $quality Kualitas kompresi (0-9), default 9 (terbaik)
     *
     * @return void
     */
    function compressPng($path, $quality = 9)
    {
        $image = imagecreatefrompng($path);
        if ($image) {
            // Simpan ulang dengan kompresi maksimal (9 = terbaik)
            imagepng($image, $path, $quality);
            imagedestroy($image);
        }
    }

    if (! function_exists('unserialize_flip')) {
    /**
     * Unserialize string lalu balik key <-> value
     *
     * @param string $str
     *
     * @return array
     */
    function unserialize_flip($str)
    {
        $arr = @unserialize($str);

        if (is_array($arr)) {
            return array_flip($arr);
        }

        return [];
    }
}

}

if (! function_exists('unserialize_flip')) {
    /**
     * Unserialize string lalu balik key <-> value
     *
     * @param string $str
     *
     * @return array
     */
    function unserialize_flip($str)
    {
        $arr = @unserialize($str);

        if (is_array($arr)) {
            return array_flip($arr);
        }

        return [];
    }
}

if (! function_exists('sensorNama')) {
    /**
     * Sensor nama dengan mengganti karakter tengah dengan '*'
     *
     * @param string $nama
     * @param string $replaceChar Karakter pengganti, default '*'
     *
     * @return string
     */
    function sensorNama($nama, $replaceChar = '*')
    {
        if (! $nama) return '';

        $nama    = trim($nama); // Hapus spasi depan/belakang
        $panjang = strlen($nama);

        if ($panjang <= 1) return $nama;

        $pertama  = $nama[0];
        $terakhir = $nama[$panjang - 1];
        $tengah   = str_repeat($replaceChar, $panjang - 2);

        return $pertama . $tengah . $terakhir;
    }
}
