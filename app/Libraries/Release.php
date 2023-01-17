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

use Esyede\Curly;
use Exception;
use stdClass;

defined('BASEPATH') || exit('No direct script access allowed');

class Release
{
    /**
     * Api endpoint latest release.
     *
     * @var string
     */
    protected $api;

    /**
     * Lokasi file cache (absolute)
     * Default: [FCPATH]/version.json
     *
     * @var string
     */
    protected $cache;

    /**
     * Interval waktu untuk sinkronisasi ulang data cache dengan yang ada di github.
     * Satuan yang digunakan adalah satuan hari.
     * Default: 7 hari
     *
     * @var int
     */
    protected $interval;

    /**
     * Version
     *
     * @var string
     */
    protected $version;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (! $this->cache) {
            $this->setCacheFolder(config_item('cache_path'));
        }

        if (! $this->interval) {
            $this->setInterval(7);
        }
    }

    /**
     * Set URL endpoint API yang ingin di hit.
     *
     * @param string $url
     */
    public function setApiUrl($url)
    {
        $this->api = $url;

        return $this;
    }

    /**
     * Set interval waktu sinkronisasi cache dengan repo github.
     * Defaultnya 7 hari sekali akan dilakukan pembaruan cache.
     * Gunakan ini jika ingin mengumbah interval sinkronisasinya.
     *
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $interval       = (int) $interval;
        $this->interval = $interval * 86400; // N * 86400 detik (1 hari)

        return $this;
    }

    /**
     * Set lokasi folder cache.
     * Misalkan diisi 'desa/config' maka file cache akan
     * disimpan di [FCPATH]/desa/config/version.json
     * Jika folder tidak ditemuakan atau tidak writable
     * maka akan fallback ke path default (FCPATH/version.json)
     *
     * @param string $folder
     */
    public function setCacheFolder($folder)
    {
        $folder = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
        $folder = str_replace(FCPATH, '', $folder);
        $folder = trim($folder, DIRECTORY_SEPARATOR);

        if (! is_dir($folder) || ! is_writable($folder)) {
            $folder = FCPATH;
        } else {
            $folder = FCPATH . $folder . DIRECTORY_SEPARATOR;
        }

        $this->cache = $folder . 'version.json';

        return $this;
    }

    /**
     * Cek apakah ada rilis baru atau tidak.
     * Caranya dengan membandingkan versi saat ini dengan versi yang ada di repositori.
     *
     * @return bool
     */
    public function isAvailable()
    {
        $current = $this->fixVersioning($this->getCurrentVersion());
        $latest  = $this->fixVersioning($this->getLatestVersion());

        return $current < $latest;
    }

    /**
     * Atur versi yang digunakan saat ini
     * Contoh return value: 'v20.06-pasca'
     *
     * @param string $version
     */
    public function setCurrentVersion($version)
    {
        $this->version = 'v' . ltrim($version ?? VERSION, 'v');

        return $this;
    }

    /**
     * Ambil versi
     *
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->version;
    }

    /**
     * Ambil tag versi dari rilis terbaru.
     * Contoh return value: 'v20.07-premium'
     *
     * @return string
     */
    public function getLatestVersion()
    {
        return $this->resync()->tag_name;
    }

    /**
     * Ambil nama rilis
     * Contoh return value di rilis v20.07:
     *  'Sediakan QR Code dan masukkan APBDes secara manual'
     *
     * @return string
     */
    public function getReleaseName()
    {
        return $this->resync()->name;
    }

    /**
     * Ambil url download rilis
     *
     * @return string
     */
    public function getReleaseDownload()
    {
        // Bisa menggunakan zipball_url, tapi penamaan file dan foldernya tidak sesuai rilis.
        // Jadi digunakan html_url dengan penyesuaian.

        return str_replace('releases/tag', 'archive/refs/tags', $this->resync()->html_url) . '.zip';
    }

    /**
     * Ambil deskripsi rilis (github menamainya body).
     * Contoh return value di rilis v20.07:
     * 'Di rilis ini, versi 20.07, tersedia fitur untuk membuat file QR Code yg bisa dipasang di artikel,
     * surat atau materi lain. Rilis ini juga berisi perbaikan lain yang diminta Komunitas SID ... dst.'
     *
     * @return string
     */
    public function getReleaseBody()
    {
        return $this->convertMarkdownLink($this->resync()->body);
    }

    /**
     * Convert markdown link ke html.
     *
     * @see https://stackoverflow.com/questions/24985530/parsing-a-markdown-style-link-safely
     *
     * @param string $body
     *
     * @return string
     */
    protected function convertMarkdownLink(?string $body = null)
    {
        return preg_replace_callback('/\[(.*?)\]\((.*?)\)/', static function ($matches) {
            return '<a href="' . $matches[2] . '">' . $matches[1] . '</a>';
        }, htmlspecialchars($body));
    }

    /**
     * Sinkronisasi file cache dengan data di repositori.
     *
     * @return object
     */
    public function resync()
    {
        if (! $this->api) {
            throw new Exception('Please specify the API endpoint URL.');
        }

        if ($this->cacheIsOutdated()) {
            Curly::$certificate = FCPATH . 'cacert.pem';

            $options  = [CURLOPT_HTTPHEADER => ['Accept' => 'application/vnd.github.v3+json']];
            $response = Curly::get($this->api, [], $options);

            if ($response instanceof stdClass) {
                $response = [
                    'tag_name'     => $response->body->tag_name,
                    'name'         => $response->body->name,
                    'zipball_url'  => $response->body->zipball_url,
                    'tarball_url'  => $response->body->tarball_url,
                    'html_url'     => $response->body->html_url,
                    'body'         => $response->body->body,
                    'created_at'   => $response->body->created_at,
                    'published_at' => $response->body->published_at,
                ];

                $this->write(json_encode($response));
            }
        }

        return json_decode($this->read($this->cache));
    }

    /**
     * Cek apakah data cache sudah kadaluwarsa atau belum.
     * Cache dianggap kadaluwarsa jika:
     *   - File cachenya belum ada di server kita, atau
     *   - File cache sudah ada tetapi waktu modified-time filenya sudah
     *     lebih dari interval yang ditentukan.
     *
     * @return bool
     */
    public function cacheIsOutdated()
    {
        return ! is_file($this->cache) || (time() > (filemtime($this->cache) + $this->interval));
    }

    /**
     * Ubah versi rilis menjadi integer agar bisa dibandingkan
     * versi rilis (tgl 1) > beta > rev
     *
     * @param string $version
     *
     * @return float
     */
    public function fixVersioning($version)
    {
        $version = preg_replace('/rev/', '05', $version); // 'v22.04-premium-rev01 -> 22.07.05.01
        $version = preg_replace('/beta/', '07', $version); // 'v22.04-premium-beta01 -> 22.07.07.01
        $version = preg_replace('/[^0-9]/', '', $version); // 'v20.07-premium' -> '20.07'
        $patch   = (float) (strlen($version) > 4) ? ('0.' . substr($version, 4, 8)) : 0; // 2007.0501, 2007.0701, 2007

        return (float) substr($version, 0, 4) + $patch;
    }

    /**
     * Buat/timpa file cache jika sudah kadaluwarsa.
     *
     * @param string $cache
     *
     * @return void
     */
    public function write($cache)
    {
        $file = $this->cache;

        if ($this->cacheIsOutdated()) {
            if (is_file($file)) {
                unlink($file);
            }

            file_put_contents($file, $cache, LOCK_EX);
        }
    }

    /**
     * Baca file cache.
     *
     * @return string
     */
    public function read()
    {
        return file_get_contents($this->cache);
    }
}
