<?php
/**
 * PHP QR Code porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	dwi.setiyadi@gmail.com
 * @porting author	zeppyshiddiq@gmail.com (for back_color and fore_color)
 * @original author	http://phpqrcode.sourceforge.net/
 *
 * @version		1.0
 */

class Ciqrcode
{
	var $cacheable = true;
	var $cachedir = 'application/cache/';
	var $errorlog = 'application/logs/';
	var $quality = true;
	var $size = 1024;

	function __construct($config = array()) {
		// call original library
		include "qrcode/qrconst.php";
		include "qrcode/qrtools.php";
		include "qrcode/qrspec.php";
		include "qrcode/qrimage.php";
		include "qrcode/qrinput.php";
		include "qrcode/qrbitstream.php";
		include "qrcode/qrsplit.php";
		include "qrcode/qrrscode.php";
		include "qrcode/qrmask.php";
		include "qrcode/qrencode.php";
		include "qrcode/qrvect.php";

		$this->initialize($config);
	}

	public function initialize($config = array()) {
		$this->cacheable = (isset($config['cacheable'])) ? $config['cacheable'] : $this->cacheable;
		$this->cachedir = (isset($config['cachedir'])) ? $config['cachedir'] : FCPATH.$this->cachedir;
		$this->errorlog = (isset($config['errorlog'])) ? $config['errorlog'] : FCPATH.$this->errorlog;
		$this->quality = (isset($config['quality'])) ? $config['quality'] : $this->quality;
		$this->size = (isset($config['size'])) ? $config['size'] : $this->size;

		// use cache - more disk reads but less CPU power, masks and format templates are stored there
		if (!defined('QR_CACHEABLE')) define('QR_CACHEABLE', $this->cacheable);

		// used when QR_CACHEABLE === true
		if (!defined('QR_CACHE_DIR')) define('QR_CACHE_DIR', $this->cachedir);

		// default error logs dir
		if (!defined('QR_LOG_DIR')) define('QR_LOG_DIR', $this->errorlog);

		// if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code
		if ($this->quality) {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', true);
		} else {
			if (!defined('QR_FIND_BEST_MASK')) define('QR_FIND_BEST_MASK', false);
			if (!defined('QR_DEFAULT_MASK')) define('QR_DEFAULT_MASK', $this->quality);
		}

		// if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly
		if (!defined('QR_FIND_FROM_RANDOM')) define('QR_FIND_FROM_RANDOM', false);

		// maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images
		if (!defined('QR_PNG_MAXIMUM_SIZE')) define('QR_PNG_MAXIMUM_SIZE',  $this->size);
	}

	public function generate($params = array()) {

		$params['data'] = (isset($params['data'])) ? $params['data'] : 'QR Code Library';
		if (isset($params['savename'])) {
			$level = 'L';
			if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];

			$size = 4;
			if (isset($params['size'])) $size = min(max((int)$params['size'], 1), 10);

			$back_color = 0x000000;
			if (isset($params['back_color'])) $back_color = $params['back_color'];

			$fore_color = 0xFFFFFF;
			if (isset($params['fore_color'])) $fore_color = $params['fore_color'];

			QRcode::png($params['data'], $params['savename'], $level, $size, 2, $back_color, $fore_color);
			return $params['savename'];
		} else {
			$level = 'L';
			if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];

			$size = 4;
			if (isset($params['size'])) $size = min(max((int)$params['size'], 1), 10);

			$back_color = 0x000000;
			if (isset($params['back_color'])) $back_color = $params['back_color'];

			$fore_color = 0xFFFFFF;
			if (isset($params['fore_color'])) $fore_color = $params['fore_color'];

			QRcode::png($params['data'], NULL, $level, $size, 2, $back_color, $fore_color);
		}
	}
}

/* end of file */
