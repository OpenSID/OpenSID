<?php namespace simplehtmldom;

/**
 * Website: http://sourceforge.net/projects/simplehtmldom/
 * Acknowledge: Jose Solorzano (https://sourceforge.net/projects/php-html/)
 *
 * Licensed under The MIT License
 * See the LICENSE file in the project root for more information.
 *
 * Authors:
 *   S.C. Chen
 *   John Schlick
 *   Rus Carroll
 *   logmanoriginal
 *
 * Contributors:
 *   Yousuke Kumakura
 *   Vadim Voituk
 *   Antcs
 *
 * Version $Rev$
 */

include_once 'HtmlDocument.php';

class HtmlWeb {

	/**
	 * @return HtmlDocument Returns the DOM for a webpage
	 * @return null Returns null if the cURL extension is not loaded and allow_url_fopen=Off
	 * @return null Returns null if the provided URL is invalid (not PHP_URL_SCHEME)
	 * @return null Returns null if the provided URL does not specify the HTTP or HTTPS protocol
	 */
	function load($url)
	{
		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			return null;
		}

		if($scheme = parse_url($url, PHP_URL_SCHEME)) {
			switch(strtolower($scheme)) {
				case 'http':
				case 'https': break;
				default: return null;
			}

			if(extension_loaded('curl')) {
				return $this->load_curl($url);
			} elseif(ini_get('allow_url_fopen')) {
				return $this->load_fopen($url);
			} else {
				error_log(__FUNCTION__ . ' requires either the cURL extension or allow_url_fopen=On in php.ini');
			}
		}

		return null;
	}

	/**
	 * cURL implementation of load
	 */
	private function load_curl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		// There is no guarantee this request will be fulfilled
		// -- https://www.php.net/manual/en/function.curl-setopt.php
		curl_setopt($ch, CURLOPT_BUFFERSIZE, MAX_FILE_SIZE);

		// There is no guarantee this request will be fulfilled
		$header = array(
			'Accept: text/html', // Prefer HTML format
			'Accept-Charset: utf-8', // Prefer UTF-8 encoding
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		$doc = curl_exec($ch);

		if(curl_getinfo($ch, CURLINFO_RESPONSE_CODE) !== 200) {
			return null;
		}

		curl_close($ch);

		if(strlen($doc) > MAX_FILE_SIZE) {
			return null;
		}

		return new HtmlDocument($doc);
	}

	/**
	 * fopen implementation of load
	 */
	private function load_fopen($url)
	{
		// There is no guarantee this request will be fulfilled
		$context = stream_context_create(array('http' => array(
			'header' => array(
				'Accept: text/html', // Prefer HTML format
				'Accept-Charset: utf-8', // Prefer UTF-8 encoding
			),
			'ignore_errors' => true // Always fetch content
		)));

		$doc = file_get_contents($url, false, $context, 0, MAX_FILE_SIZE + 1);

		if(isset($http_response_header)) {
			foreach($http_response_header as $rh) {
				// https://stackoverflow.com/a/1442526
				$parts = explode(' ', $rh, 3);

				if(preg_match('/HTTP\/\d\.\d/', $parts[0])) {
					$code = $parts[1];
				}
			} // Last code is final status

			if(!isset($code) || $code !== '200') {
				return null;
			}
		}

		if(strlen($doc) > MAX_FILE_SIZE) {
			return null;
		}

		return new HtmlDocument($doc);
	}

}
