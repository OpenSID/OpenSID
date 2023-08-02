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

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @see https://github.com/ikarius6/codeigniter-trusted-hosts
 */
class MY_Config extends CI_Config
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        $this->config = &get_config();

        // Set the base_url automatically if none was provided
        if (empty($this->config['base_url'])) {
            // The regular expression is only a basic validation for a valid "Host" header.
            // It's not exhaustive, only checks for valid characters.
            if (isset($_SERVER['HTTP_HOST']) && preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $_SERVER['HTTP_HOST'])) {
                //Check if the SERVER_HOST is a trusted host to avoid HTTP Host header attacks
                $trusted = false;
                if (! empty($this->config['trusted_hosts'])) {
                    foreach ($this->config['trusted_hosts'] as $trusted_host) {
                        $parsed_url        = parse_url(trim($trusted_host));
                        $path_explode      = explode('/', $parsed_url['path'], 2);
                        $real_trusted_host = trim($parsed_url['host'] ?? array_shift($path_explode));
                        if ($trusted = preg_match('/^((.*?)\\.)?' . $real_trusted_host . '$/i', $_SERVER['HTTP_HOST'])) {
                            break;
                        }
                    }
                } else {
                    $trusted = true;
                }

                if ($trusted) {
                    $base_url = (is_https() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
                        . substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
                } else {
                    $_SERVER['HTTP_HOST'] = 'localhost';
                    $base_url             = 'http://localhost/';
                }
            } else {
                $base_url = 'http://localhost/';
            }

            $this->set_item('base_url', $base_url);
        }

        log_message('info', 'Config Class Initialized');
    }
}
