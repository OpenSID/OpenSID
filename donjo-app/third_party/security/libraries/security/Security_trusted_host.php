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

class Security_trusted_host
{
    /**
     * List of all loaded config values
     *
     * @var array
     */
    public $config = [];

    public function __construct()
    {
        $this->config = &get_config();
    }

    public function handle(): void
    {
        if (! isset($_SERVER['HTTP_HOST']) || empty($this->config['trusted_hosts'])) {
            return;
        }

        $isValidHost = preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $_SERVER['HTTP_HOST']);

        if (! $isValidHost) {
            log_message('error', sprintf('Untrusted Host "%s".', htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8')));
            show_error(null, 400);
        }

        $trustedHosts = $this->config['trusted_hosts'] ?? [];

        foreach ($trustedHosts as $trustedHost) {
            $parsedUrl       = parse_url(trim($trustedHost));
            $realTrustedHost = trim($parsedUrl['host'] ?? '');
            if ($realTrustedHost === '') {
                continue;
            }
            if ($realTrustedHost === '0') {
                continue;
            }
            if (! preg_match('/^((.*?)\\.)?' . preg_quote($realTrustedHost) . '$/i', $_SERVER['HTTP_HOST'])) {
                continue;
            }

            return;
        }

        log_message('error', sprintf('Untrusted Host "%s".', htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8')));
        show_error(null, 400);
    }
}
