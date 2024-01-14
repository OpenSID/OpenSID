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

use App\Models\Config;
use App\Models\JamKerja;
use App\Models\Kehadiran;
use App\Models\UserGrup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null                                   $view
     * @param array|\Illuminate\Contracts\Support\Arrayable $data
     * @param array                                         $mergeData
     *
     * @return Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $CI = &get_instance();

        $factory = new \Jenssegers\Blade\Blade(config_item('views_blade'), config_item('cache_blade'));

        if (func_num_args() === 0) {
            return $factory;
        }

        $factory->directive('selected', static function ($condition) {
            return "<?= ({$condition}) ? 'selected' : ''; ?>";
        });

        $factory->directive('checked', static function ($condition) {
            return "<?= ({$condition}) ? 'checked' : ''; ?>";
        });

        $factory->directive('disabled', static function ($condition) {
            return "<?= ({$condition}) ? 'disabled' : ''; ?>";
        });

        $factory->directive('active', static function ($condition) {
            return "<?= ({$condition}) ? 'active' : ''; ?>";
        });

        $factory->directive('display', static function ($condition) {
            return "<?= ({$condition}) ? 'show' : 'hide'; ?>";
        });

        if ($CI->session->db_error['code'] === 1049) {
            $CI->session->error_db = null;
            $CI->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception', 'sudah_mulai']);
        } else {
            $factory->share([
                'ci'           => get_instance(),
                'auth'         => $CI->session->isAdmin,
                'controller'   => $CI->controller,
                'desa'         => identitas(),
                'list_setting' => $CI->list_setting,
                'modul'        => $CI->header['modul'],
                'modul_ini'    => $CI->modul_ini,
                'notif'        => [
                    'surat'           => $CI->header['notif_permohonan_surat'],
                    'opendkpesan'     => $CI->header['notif_pesan_opendk'],
                    'inbox'           => $CI->header['notif_inbox'],
                    'komentar'        => $CI->header['notif_komentar'],
                    'langganan'       => $CI->header['notif_langganan'],
                    'pengumuman'      => $CI->header['notif_pengumuman'],
                    'permohonansurat' => $CI->header['notif_permohonan'],
                ],
                'kategori'             => $CI->header['kategori'],
                'sub_modul_ini'        => $CI->sub_modul_ini,
                'session'              => $CI->session,
                'setting'              => $CI->setting,
                'token'                => $CI->security->get_csrf_token_name(),
                'perbaharui_langganan' => $CI->header['perbaharui_langganan'] ?? null,
            ]);
        }

        echo $factory->render($view, $data, $mergeData);
    }
}

if (! function_exists('set_session')) {
    function set_session($key = 'success', $value = '')
    {
        return get_instance()->session->set_flashdata($key, $value);
    }
}

if (! function_exists('session')) {
    function session($nama = '')
    {
        return get_instance()->session->flashdata($nama);
    }
}

if (! function_exists('can')) {
    function can($akses, $controller = '', $admin_only = false)
    {
        $CI = &get_instance();
        $CI->load->model('user_model');

        if (empty($controller)) {
            $controller = $CI->controller;
        }

        if ($admin_only && $CI->grup != $CI->user_model->id_grup(UserGrup::ADMINISTRATOR)) {
            return false;
        }

        return $CI->user_model->hak_akses($CI->grup, $controller, $akses);
    }
}

// response()->json(array_data);
if (! function_exists('json')) {
    function json($content = [], $header = 200)
    {
        get_instance()->output
            ->set_status_header($header)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($content))
            ->_display();

        exit();
    }
}

// redirect()->route('example')->with('success', 'information');
if (! function_exists('redirect_with')) {
    function redirect_with($key = 'success', $value = '', $to = '')
    {
        set_session($key, $value);

        if (empty($to)) {
            $to = get_instance()->controller;
        }

        return redirect($to);
    }
}

// route('example');
if (! function_exists('route')) {
    function route($to = null, $params = null)
    {
        if (in_array($to, [null, '', '/'])) {
            return site_url();
        }

        $to = str_replace('.', '/', $to);

        if (null !== $params) {
            $to .= '/' . $params;
        }

        return site_url($to);
    }
}

// setting('sebutan_desa');
if (! function_exists('setting')) {
    function setting($params = null)
    {
        $getSetting = get_instance()->setting;

        if ($params && ! empty($getSetting)) {
            if (property_exists($getSetting, $params)) {
                return $getSetting->{$params};
            }

            return null;
        }

        return $getSetting;
    }
}

// identitas('nama_desa');
if (! function_exists('identitas')) {
    /**
     * Get identitas desa.
     *
     * @return object|string
     */
    function identitas(?string $params = null)
    {
        $cache    = 'identitas_desa';
        $instance = get_instance();

        if (null === $instance->cache) {
            return null;
        }

        $identitas = $instance->cache->pakai_cache(static function () {
            if (Schema::hasColumn('config', 'app_key') && DB::table('config')->where('app_key', get_app_key())->exists()) {
                return Config::appKey()->first();
            }

            return null;
        }, $cache, 24 * 60 * 60);

        if ($params) {
            return $identitas->{$params};
        }

        return $identitas;
    }
}

// hapus_cache('cache_id');
if (! function_exists('hapus_cache')) {
    function hapus_cache($params = null)
    {
        if ($params) {
            return get_instance()->cache->hapus_cache_untuk_semua($params);
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
            $endTime = $endTime->add(DateInterval::createFromDateString(calculate_days($dateInterval) . 'days'));
        }

        return $reference->diff($endTime)->days;
    }
}

// Parsedown
if (! function_exists('parsedown')) {
    function parsedown($params = null)
    {
        $parsedown = new \App\Libraries\Parsedown();

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
        return str_replace(['[Desa]', '[desa]'], ucwords(setting('sebutan_desa')), $params);
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
    function underscore($str, $to_underscore = true, $lowercase = false)
    {
        // membersihkan string di akhir dan di awal
        $str = trim($str);

        // membuat text lowercase jika diperlukan
        if ($lowercase) {
            $str = MB_ENABLED ? mb_strtolower($str) : strtolower($str);
        }

        if ($to_underscore) {
            // mengganti spasi dengan underscore
            $str = str_replace(' ', '_', $str);
        } else {
            // mengganti underscore dengan spasi
            $str = str_replace('_', ' ', $str);
        }

        // menyajikan hasil akhir
        return $str;
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
                session_error(', tidak dapat mengubah / menghapus akun demo');
                redirect($_SERVER['HTTP_REFERER']);
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
     */
    function folder($folder = null, $permissions = 0755, $htaccess = null)
    {
        $hasil = true;

        get_instance()->load->helper('file');

        $folder = FCPATH . $folder;

        // Buat folder
        $hasil = is_dir($folder) || mkdir($folder, $permissions, true);

        if ($hasil) {
            if ($htaccess !== null) {
                write_file($folder . '.htaccess', config_item($htaccess), 'x');
            }

            // File index.hmtl
            write_file($folder . 'index.html', config_item('index_html'), 'x');

            return true;
        }

        return false;
    }
}

if (! function_exists('folder_desa')) {
    /**
     * Membuat folder desa dan isinya
     */
    function folder_desa()
    {
        get_instance()->load->config('installer');
        $list_folder = array_merge(config_item('desa'), config_item('lainnya'));

        // Buat folder dan subfolder desa
        foreach ($list_folder as $folder => $lainnya) {
            folder($folder, $lainnya[0], $lainnya[1]);
        }

        // Buat file offline_mode.php, config.php dan database.php awal
        write_file(LOKASI_CONFIG_DESA . 'config.php', config_item('config'), 'x');
        write_file(LOKASI_CONFIG_DESA . 'database.php', config_item('database'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman.css', config_item('siteman_css'), 'x');
        write_file(DESAPATH . 'pengaturan/siteman/siteman_mandiri.css', config_item('siteman_mandiri_css'), 'x');
        write_file(DESAPATH . 'offline_mode.php', config_item('offline_mode'), 'x');
        write_file(DESAPATH . 'app_key', set_app_key(), 'x');

        return true;
    }
}

if (! function_exists('auth')) {
    /**
     * Ambil data user login
     *
     * @param mixed|null $params
     */
    function auth($params = null)
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
        return get_instance()->db;
    }
}

if (! function_exists('cek_kehadiran')) {
    /**
     * Cek perangkat lupa absen
     */
    function cek_kehadiran()
    {
        if (Schema::hasTable('kehadiran_jam_kerja') && (! empty(setting('rentang_waktu_kehadiran')) || setting('rentang_waktu_kehadiran'))) {
            $cek_libur = JamKerja::libur()->first();
            $cek_jam   = JamKerja::jamKerja()->first();
            $kehadiran = Kehadiran::where('status_kehadiran', 'hadir')->where('jam_keluar', null)->get();
            if ($kehadiran->count() > 0 && ($cek_jam != null || $cek_libur != null)) {
                foreach ($kehadiran as $data) {
                    Kehadiran::lupaAbsen($data->tanggal);
                }
            }
        }
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
        $replacer = static function ($matches) use ($ke) {
            $matches = array_map(static function ($match) {
                return preg_replace('/[\\[\\]]/', '', $match);
            }, $matches);

            // Huruf kecil semua
            if (ctype_lower($matches[0][0])) {
                return strtolower($ke);
            }

            // Huruf besar semua
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][1])) {
                return strtoupper($ke);
            }

            // Huruf besar diawal kata
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][2])) {
                return ucwords(strtolower($ke));
            }

            // Normal
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][strlen($matches) - 1])) {
                return $ke;
            }

            // Huruf besar diawal kalimat
            if (ctype_upper($matches[0][0])) {
                return ucfirst(strtolower($ke));
            }
        };

        $dari = str_replace('[', '\\[', $dari);

        $result = preg_replace_callback('/(' . $dari . ')/i', $replacer, $str);

        if (preg_match('/pendidikan/i', strtolower($dari))) {
            $result = kasus_lain('pendidikan', $result);
        } elseif (preg_match('/pekerjaan/i', strtolower($dari))) {
            $result = kasus_lain('pekerjaan', $result);
        }

        return $result;
    }
}

if (! function_exists('kirim_versi_opensid')) {
    function kirim_versi_opensid()
    {
        $ci = get_instance();
        if (empty($ci->header['desa']['kode_desa'])) {
            return;
        }

        $ci->load->driver('cache');

        $versi = AmbilVersi();

        if ($versi != $ci->cache->file->get('versi_app_cache')) {
            try {
                $client = new \GuzzleHttp\Client();
                $client->post(config_item('server_layanan') . '/api/v1/pelanggan/catat-versi', [
                    'headers'     => ['X-Requested-With' => 'XMLHttpRequest'],
                    'form_params' => [
                        'kode_desa' => kode_wilayah($ci->header['desa']['kode_desa']),
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

if (! function_exists('kotak')) {
    function kotak($data_kolom, $max_kolom = 26)
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
    function checklist($kondisi_1, $kondisi_2)
    {
        $view = '<td class="kotak padat tengah">';
        if ($kondisi_1 == $kondisi_2) {
            $view .= '<img src="' . base_url('assets/images/check.png') . '" height="10" width="10"/>';
        }
        $view .= '</td>';

        return $view;
    }
}
