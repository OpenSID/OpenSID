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

use Carbon\Carbon;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $CI = &get_instance();

        $factory = new \Jenssegers\Blade\Blade(config_item('views_blade'), config_item('cache_blade'));

        if (func_num_args() === 0) {
            return $factory;
        }

        $factory->share([
            'auth'         => $CI->session->isAdmin,
            'controller'   => $CI->controller,
            'desa'         => \App\Models\Config::first(),
            'list_setting' => $CI->list_setting,
            'modul'        => $CI->header['modul'],
            'modul_ini'    => $CI->modul_ini,
            'notif'        => [
                'surat'      => $CI->header['notif_permohonan_surat'],
                'inbox'      => $CI->header['notif_inbox'],
                'komentar'   => $CI->header['notif_komentar'],
                'langganan'  => $CI->header['notif_langganan'],
                'pengumuman' => $CI->header['notif_pengumuman'],
            ],
            'kategori'      => $CI->header['kategori'],
            'sub_modul_ini' => $CI->sub_modul_ini,
            'session'       => $CI->session,
            'setting'       => $CI->setting,
            'token'         => $CI->security->get_csrf_token_name(),
        ]);

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
    function can($akses, $controller = '')
    {
        $CI = &get_instance();
        $CI->load->model('user_model');

        if (empty($controller)) {
            $controller = $CI->controller;
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

        if ($params && $getSetting->{$params}) {
            return $getSetting->{$params};
        }

        return $getSetting;
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

// SebuatanDesa('Surat [Desa]');
if (! function_exists('SebuatanDesa')) {
    function SebuatanDesa($params = null)
    {
        return str_replace('[Desa]', ucwords(setting('sebutan_desa')), $params);
    }
}
