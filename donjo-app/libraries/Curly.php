<?php
/**
 * Curly - Simple cURL Library for PHP 5.4+
 * Ported to PHP 5.3+ by @esyede
 *
 * @author   Suyadi <suyadi.1992@gmail.com>
 * @license  MIT
 * @see      https://github.com/esyede/curly
 */

namespace Esyede;

class Curly
{
	/**
	 * Activate secure connection?
	 *
	 * @var bool
	 */
	public static $secure = true;

	/**
	 * Path to CA bundle file (absolute).
	 *
	 * @var string
	 */
	public static $certificate;

	/**
	 * Link for downloading CA bundle.
	 *
	 * @var string
	 */
	public static $obtain = 'https://curl.haxx.se/ca/cacert.pem';

	/**
	 * Send a GET request.
	 *
	 * @param string $url
	 * @param array  $params
	 * @param array  $options
	 *
	 * @return \stdClass
	 */
	public static function get($url, array $params = array(), array $options = array())
	{
		return static::request('get', $url, $params, $options);
	}

	/**
	 * Send a POST request.
	 *
	 * @param string $url
	 * @param array  $params
	 * @param array  $options
	 *
	 * @return \stdClass
	 */
	public static function post($url, array $params = array(), array $options = array())
	{
		return static::request('post', $url, $params, $options);
	}

	/**
	 * Send a PUT request.
	 *
	 * @param string $url
	 * @param array  $params
	 * @param array  $options
	 *
	 * @return \stdClass
	 */
	public static function put($url, array $params = array(), array $options = array())
	{
		return static::request('put', $url, $params, $options);
	}

	/**
	 * Send a DELETE request.
	 *
	 * @param string $url
	 * @param array  $params
	 * @param array  $options
	 *
	 * @return \stdClass
	 */
	public static function delete($url, array $params = array(), array $options = array())
	{
		return static::request('delete', $url, $params, $options);
	}

	/**
	 * Send a request.
	 *
	 * @param string $method
	 * @param string $url
	 * @param array  $params
	 * @param array  $options
	 *
	 * @return \stdClass
	 */
	public static function request($method = 'get', $url, array $params = array(), array $options = array())
	{
		if (! static::available()) {
			throw new \RuntimeException('cURL extension is not available.');
		}

		if (! in_array($method, array('get', 'post', 'put', 'delete'))) {
			throw new \Exception('Request method is not supported: '.$method);
		}

			// Set lokasi default penyimpanan sertifikat ssl.
		if (is_null(static::$certificate)) {
			static::$certificate = dirname(__FILE__).DIRECTORY_SEPARATOR.'cacert.pem';
		}

			// Obtain the CA bundle if we do not habe yet.
			// It is important for us to be able to use TLS later on.
		if (! is_file(static::$certificate)) {
			try {
							// Deactivate secure connection because we don't have the CA bundle yet.
							// We will re-activate it later on.
				static::$secure = false;

							// Let's download the CA bundlen.
				static::download(static::$obtain, static::$certificate);

							// Then give it a read permission.
				chmod(static::$certificate, 0644);

							// Re-activate secure connection.
				static::$secure = true;
			} catch (\Throwable $e) {
							// Error on PHP 7+
				throw new \Exception('Unable to obtain CA bundle: '.$e->getMessage());
			} catch (\Exception $e) {
							// Error on PHP 5
				throw new \Exception('Unable to obtain CA bundle: '.$e->getMessage());
			}
		}

		$curl = curl_init();

		// Batasi waktu koneksi dan ambil data supaya tidak menggantung kalau ada error koneksi
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, static::$secure ? 1 : 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, static::$secure ? 2 : 0);
		curl_setopt($curl, CURLOPT_CAINFO, static::$secure ? static::$certificate : null);
		curl_setopt($curl, CURLOPT_CAPATH, static::$secure ? static::$certificate : null);

		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, static::agent());

		$query = empty($params) ? null : http_build_query($params, '', '&', PHP_QUERY_RFC1738);

		switch (strtolower($method)) {
			case 'get':
			$url .= $query ? '?'.$query : '';
			curl_setopt($curl, CURLOPT_HTTPGET, 1);
			break;

			case 'post':
			if ($query) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
			}

							// Check if user passes custom options to the $options parameter.
							// Then add a proper Content-Type header to our query string.
			if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
				$options[CURLOPT_HTTPHEADER] = array_merge(
					$options[CURLOPT_HTTPHEADER],
					array('Content-Type: application/x-www-form-urlencoded')
				);
			} else {
				$options[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded');
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			break;

			case 'put':
			if ($query) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
			}

							// Check if user passes custom options to the $options parameter.
							// Then add a proper Content-Type header to our query string.
			if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
				$options[CURLOPT_HTTPHEADER] = array_merge(
					$options[CURLOPT_HTTPHEADER],
					array('Content-Type: application/x-www-form-urlencoded')
				);
			} else {
				$options[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded');
			}

			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
			break;

			case 'delete':
			if ($query) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
			}

							// Check if the programmer passes custom options to the $options parameter.
							// Then add a proper Content-Type header to our query string.
			if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
				$options[CURLOPT_HTTPHEADER] = array_merge(
					$options[CURLOPT_HTTPHEADER],
					array('Content-Type: application/x-www-form-urlencoded')
				);
			} else {
				$options[CURLOPT_HTTPHEADER] = array('Content-Type: application/x-www-form-urlencoded');
			}

			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			break;

			default:
			throw new \Exception('Usupported request method: ' . strtoupper($method));
			break;
		}

		if (! empty($options)) {
			curl_setopt_array($curl, $options);
		}

		curl_setopt($curl, CURLOPT_URL, $url);

		$body = curl_exec($curl);

		if (false === $body) {
			$code = curl_errno($curl);
			$message = curl_error($curl);

			log_message('error', 'Curl error: ' . $message . ' Code: ' . $code);
			log_message('error', print_r(curl_getinfo($curl), true));

			curl_close($curl);

			// Jangan lempar exception, supaya proses bisa jalan terus
			// throw new \Exception($message, $code);
		}

		$header = (object) curl_getinfo($curl);

		curl_close($curl);

			// Encode response-body into json if the programmer want to.
		if (false !== strpos($header->content_type, '/json')) {
			$body = json_decode($body);
		}

		return (object) compact('header', 'body');
	}

	/**
	 * Download file from given URL.
	 *
	 * @param string $url
	 * @param string $destination
	 * @param array  $options
	 *
	 * @return void
	 */
	public static function download($url, $destination, array $options = array())
	{
		if (! static::available()) {
			throw new \Exception('cURL extension is not available.');
		}

		if (is_file($destination)) {
			throw new \Exception('Destination path already exists: '.$destination);
		}

		$fopen = false;

		if (false === ($fopen = fopen($destination, 'w+'))) {
			throw new \Exception('Unable to open destination file: '.$destination);
		}

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, static::$secure ? 1 : 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, static::$secure ? 2 : 0);
		curl_setopt($curl, CURLOPT_CAINFO, static::$secure ? static::$certificate : null);
		curl_setopt($curl, CURLOPT_CAPATH, static::$secure ? static::$certificate : null);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, static::agent());
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_FILE, $fopen);

		if (! empty($options)) {
			curl_setopt_array($curl, $options);
		}

		$body = curl_exec($curl);

		if (false === $body) {
			$code = curl_errno($curl);
			$message = curl_error($curl);

			curl_close($curl);
			fclose($fopen);

			throw new \Exception($message, $code);
		}

		curl_close($curl);
		fclose($fopen);

		return true;
	}

	/**
	 * Check if cURL extension is enabled.
	 *
	 * @return bool
	 */
	public static function available()
	{
		return extension_loaded('curl') && is_callable('curl_init');
	}

	/**
	 * Create a fake user-agent for our request.
	 * Some site such as github refuses the connection
	 * if it doesn't contains User-Agent header.
	 *
	 * @return string
	 */
	public static function agent()
	{
		$year = (int) gmdate('Y');
		$year = ($year < 2020) ? 2020 : $year;

			// Increase version number based on year.
		$version = 77 + ($year - 2020) + 2;

		$agents = array(
			'Windows' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
			'Linux' => 'Mozilla/5.0 (Linux x86_64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
			'Darwin' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:[v].0) Gecko/20100101 Firefox/[v].0',
			'BSD' => 'Mozilla/5.0 (X11; FreeBSD amd64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
			'Solaris' => 'Mozilla/5.0 (Solaris; Solaris x86_64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
		);

		$platform = static::platform();
		$platform = 'Unknown' === $platform ? 'Linux' : $platform;

		return str_replace('[v]', $version, $agents[$platform]);
	}

	/**
	 * Get server's platform / operating system.
	 *
	 * @return string
	 */
	public static function platform()
	{
		if ('\\' === DIRECTORY_SEPARATOR) {
			return 'Windows';
		}

		$platforms = array(
			'Darwin' => 'Darwin',
			'DragonFly' => 'BSD',
			'FreeBSD' => 'BSD',
			'NetBSD' => 'BSD',
			'OpenBSD' => 'BSD',
			'Linux' => 'Linux',
			'SunOS' => 'Solaris',
		);

		return isset($platforms[PHP_OS]) ? $platforms[PHP_OS] : 'Unknown';
	}
}
