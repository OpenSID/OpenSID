<?php

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
	 * Konstruktor
	 */
	public function __construct()
	{
		if (! class_exists('Esyede\Curly')) {
			require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'Curly.php';
		}

		if (! $this->cache) {
			$this->setCacheFolder(FCPATH);
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
		$interval = (int) $interval;
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
		$folder = ltrim($folder, FCPATH);
		$folder = ltrim($folder, ltrim($folder, '/'), '\\');
		$folder = rtrim($folder, rtrim($folder, '/'), '\\');
		$folder = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $folder);

		if (! is_dir($folder) || ! is_writable($folder)) {
			$folder = FCPATH;
		} else {
			$folder = FCPATH.DIRECTORY_SEPARATOR.$folder;
		}

		$this->cache = $folder.DIRECTORY_SEPARATOR.'version.json';

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
		$latest = $this->fixVersioning($this->getLatestVersion());

		return $current < $latest;
	}

	/**
	 * Ambil versi rilis saat ini igunakan user.
	 * Contoh return value: 'v20.06-parsca'
	 *
	 * @return string
	 */
	public function getCurrentVersion()
	{
		return 'v'.ltrim(VERSION, 'v');
	}

	/**
	 * Ambil tag versi dari rilis terbaru.
	 * Contoh return value: 'v20.07'
	 *
	 * @return string
	 */
	public function getLatestVersion()
	{
		return $this->resyncWithOfficialRepository()->tag_name;
	}

	/**
	 * Ambil nama rilis
	 * Contoh return value di rilis v20.07:
	 *  'Sediakan QR Code dan masukkan APBDes secara manual'
	 * @return string
	 */
	public function getReleaseName()
	{
		return $this->resyncWithOfficialRepository()->name;
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
		return $this->resyncWithOfficialRepository()->body;
	}

	/**
	 * Sinkronisasi file cache dengan data di repositori.
	 *
	 * @return array
	 */
	public function resyncWithOfficialRepository()
	{
		if (! $this->api) {
			throw new \Exception('Please specify the API endpoint URL.');
		}

		if ($this->cacheIsOutdated()) {
			\Esyede\Curly::$certificate = FCPATH.DIRECTORY_SEPARATOR.'cacert.pem';

			$options = array(CURLOPT_HTTPHEADER => array('Accept' => 'application/vnd.github.v3+json'));

			$response = \Esyede\Curly::get($this->api, array(), $options);

			if ($response instanceof \stdClass) {
				$response = [
					'tag_name' => $response->body->tag_name,
					'name' => $response->body->name,
					'zipball_url' => $response->body->zipball_url,
					'tarball_url' => $response->body->tarball_url,
					'body' => $response->body->body,
					'created_at' => $response->body->created_at,
					'published_at' => $response->body->published_at,

				];

				$this->writeCache(json_encode($response));
			}
		}

		return json_decode($this->readCache($this->cache));
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
		$file = $this->cache;
		$interval = $this->interval;

		return ! is_file($file) || (is_file($file) && (time() - filemtime($file)) < $interval);
	}

	/**
	 * Ubah versi rilis menjadi integer agar bisa dibandingkan
	 *
	 * @param  string $version
	 *
	 * @return int
	 */
	public function fixVersioning($version)
	{
			$version = preg_replace('/[^0-9.]/', '', $version); // 'v20.07-pasca' -> '20.07'
			$version = str_replace('.', '0', $version); // '20.07' -> '20007'
			$version = (int) $version; // 20007 (integer)

			return $version;
		}

	/**
	 * Buat/timpa file cache jika sudah kadaluwarsa.
	 *
	 * @param  string $cache
	 *
	 * @return void
	 */
	public function writeCache($cache)
	{
		$file = $this->cache;
		$interval = $this->interval;

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
	public function readCache()
	{
		return file_get_contents($this->cache);
	}

	/**
	 * Cek apakah ada koneksi internet atau tidak.
	 *
	 * @return bool
	 */
	public function hasInternetConnection()
	{
		try {
			return false !== @fsockopen('www.google.com', 80);
		} catch (\Exception $e) {
			return false;
		}
	}
}
