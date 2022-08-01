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

defined('BASEPATH') || exit('No direct script access allowed');

class Data_publik
{
    /**
     * Api endpoint data publik.
     *
     * @var string
     */
    protected $api;

    protected $nama_api;

    /**
     * Lokasi file cache (absolute)
     * Default: [FCPATH]/[$nama_api].json
     *
     * @var string
     */
    protected $cache;

    /**
     * Interval waktu untuk sinkronisasi ulang data cache dengan yang ada di API.
     * Satuan yang digunakan adalah satuan hari.
     * Default: 7 hari
     *
     * @var int
     */
    protected $interval;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (! class_exists('Esyede\Curly')) {
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'Curly.php';
        }

        if (! $this->cache) {
            $this->set_cache_folder(FCPATH);
        }

        if (! $this->interval) {
            $this->set_interval(7);
        }
    }

    /**
     * Set URL endpoint API yang ingin di hit.
     *
     * @param string $url
     * @param mixed  $nama_api
     */
    public function set_api_url($url, $nama_api)
    {
        if (! $url) {
            throw new \Exception('Harap sediakan URL untuk API endpoint.');
        }
        if (! $nama_api) {
            throw new \Exception('Harap sediakan nama untuk API endpoint.');
        }
        $this->api      = $url;
        $this->nama_api = $nama_api;

        return $this;
    }

    /**
     * Set interval waktu sinkronisasi cache dengan data API.
     * Defaultnya 7 hari sekali akan dilakukan pembaruan cache.
     * Gunakan ini jika ingin mengubah interval sinkronisasinya.
     *
     * @param int $interval
     */
    public function set_interval($interval)
    {
        $interval       = (int) $interval;
        $this->interval = $interval * 86400; // N * 86400 detik (1 hari)

        return $this;
    }

    /**
     * Set lokasi folder cache.
     * Misalkan diisi 'desa/config' maka file cache akan
     * disimpan di [FCPATH]/desa/config/[nama_api].json
     * Jika folder tidak ditemuakan atau tidak writable
     * maka akan fallback ke path default (FCPATH/[nama_api].json)
     *
     * @param string $folder
     */
    public function set_cache_folder($folder)
    {
        $folder = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
        $folder = str_replace(FCPATH, '', $folder);
        $folder = trim($folder, DIRECTORY_SEPARATOR);

        if (! is_dir($folder) || ! is_writable($folder)) {
            $folder = FCPATH;
        } else {
            $folder = FCPATH . $folder . DIRECTORY_SEPARATOR;
        }

        $this->cache = $folder . $this->nama_api . '.json';

        return $this;
    }

    /**
     * Ambil data url
     *
     * @param bool  $no_cache
     * @param mixed $secure
     *
     * @return string
     */
    public function get_url_content($no_cache = false, $secure = true)
    {
        if (! $this->api) {
            throw new \Exception('Please specify the API endpoint URL.');
        }
        // Jika $no_cache paksa ambil baru
        return $no_cache ? $this->get_content($secure) : $this->resync($secure);
    }

    /**
     * Sinkronisasi file cache dengan data di repositori.
     *
     * @return array
     */
    public function resync()
    {
        if ($this->cache_is_outdated()) {
            $response = $this->get_content($secure);
            $this->write(json_encode($response));
        }

        return json_decode($this->read($this->cache));
    }

    private function get_content($secure = true)
    {
        if ($secure) {
            \Esyede\Curly::$certificate = FCPATH . 'cacert.pem';
        } else {
            \Esyede\Curly::$secure = false;
        }

        $options = [];

        return \Esyede\Curly::get($this->api, [], $options);
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
    public function cache_is_outdated()
    {
        return ! is_file($this->cache) || (time() > (filemtime($this->cache) + $this->interval));
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
        $file     = $this->cache;
        $interval = $this->interval;

        if ($this->cache_is_outdated()) {
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

    /**
     * Cek apakah ada koneksi internet atau tidak.
     *
     * @return bool
     */
    public function has_internet_connection()
    {
        return cek_koneksi_internet();
    }
}
