<?php
/**
 * File ini:
 *
 * Class untuk memeriksa dan mengambil rilis OpenSID di Github
 *
 * donjo-app/libraries/Release.php
 *
 */

/**
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

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
		if (! class_exists('Esyede\Curly'))
		{
			require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'Curly.php';
		}

		if (! $this->cache)
		{
			$this->set_cache_folder(FCPATH);
		}

		if (! $this->interval)
		{
			$this->set_interval(7);
		}
	}

	/**
	 * Set URL endpoint API yang ingin di hit.
	 *
	 * @param string $url
	 */
	public function set_api_url($url)
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
	public function set_interval($interval)
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
	public function set_cache_folder($folder)
	{
		$folder = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
		$folder = str_replace(FCPATH, '', $folder);
		$folder = trim($folder, DIRECTORY_SEPARATOR);

		if (! is_dir($folder) || ! is_writable($folder))
		{
			$folder = FCPATH;
		}
		else
		{
			$folder = FCPATH.$folder.DIRECTORY_SEPARATOR;
		}

		$this->cache = $folder.'version.json';

		return $this;
	}

	/**
	 * Cek apakah ada rilis baru atau tidak.
	 * Caranya dengan membandingkan versi saat ini dengan versi yang ada di repositori.
	 *
	 * @return bool
	 */
	public function is_available()
	{
		$current = $this->fix_versioning($this->get_current_version());
		$latest = $this->fix_versioning($this->get_latest_version());

		return $current < $latest;
	}

	/**
	 * Ambil versi rilis saat ini igunakan user.
	 * Contoh return value: 'v20.06-parsca'
	 *
	 * @return string
	 */
	public function get_current_version()
	{
		return 'v'.ltrim(VERSION, 'v');
	}

	/**
	 * Ambil tag versi dari rilis terbaru.
	 * Contoh return value: 'v20.07'
	 *
	 * @return string
	 */
	public function get_latest_version()
	{
		return $this->resync()->tag_name;
	}

	/**
	 * Ambil nama rilis
	 * Contoh return value di rilis v20.07:
	 *  'Sediakan QR Code dan masukkan APBDes secara manual'
	 * @return string
	 */
	public function get_release_name()
	{
		return $this->resync()->name;
	}

	/**
	 * Ambil deskripsi rilis (github menamainya body).
	 * Contoh return value di rilis v20.07:
	 * 'Di rilis ini, versi 20.07, tersedia fitur untuk membuat file QR Code yg bisa dipasang di artikel,
	 * surat atau materi lain. Rilis ini juga berisi perbaikan lain yang diminta Komunitas SID ... dst.'
	 *
	 * @return string
	 */
	public function get_release_body()
	{
		return $this->resync()->body;
	}

	/**
	 * Sinkronisasi file cache dengan data di repositori.
	 *
	 * @return array
	 */
	public function resync()
	{
		if (! $this->api)
		{
			throw new \Exception('Please specify the API endpoint URL.');
		}

		if ($this->cache_is_outdated())
		{
			\Esyede\Curly::$certificate = FCPATH.'cacert.pem';

			$options = [CURLOPT_HTTPHEADER => ['Accept' => 'application/vnd.github.v3+json']];
			$response = \Esyede\Curly::get($this->api, [], $options);

			if ($response instanceof \stdClass)
			{
				$response = [
					'tag_name' => $response->body->tag_name,
					'name' => $response->body->name,
					'zipball_url' => $response->body->zipball_url,
					'tarball_url' => $response->body->tarball_url,
					'body' => $response->body->body,
					'created_at' => $response->body->created_at,
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
	public function cache_is_outdated()
	{
    return ! is_file($this->cache) || (time() > (filemtime($this->cache) + $this->interval));
	}

	/**
	 * Ubah versi rilis menjadi integer agar bisa dibandingkan
	 *
	 * @param  string $version
	 *
	 * @return int
	 */
	public function fix_versioning($version)
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
	public function write($cache)
	{
		$file = $this->cache;
		$interval = $this->interval;

		if ($this->cache_is_outdated())
		{
			if (is_file($file))
			{
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
