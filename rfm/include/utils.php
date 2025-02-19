<?php

if (!isset($_SESSION['RF']) || $_SESSION['RF']["verify"] != "RESPONSIVEfilemanager")
{
	die('forbiden');
}
require dirname(__FILE__) . '/Response.php';

if ( ! function_exists('response'))
{
	/**
	* Response construction helper
	*
	* @param string $content
	* @param int    $statusCode
	* @param array  $headers
	*
	* @return \Response|\Illuminate\Http\Response
	*/
	function response($content = '', $statusCode = 200, $headers = array())
	{
		$responseClass = class_exists('Illuminate\Http\Response') ? '\Illuminate\Http\Response' : 'Response';

		return new $responseClass($content, $statusCode, $headers);
	}
}

if ( ! function_exists('trans'))
{

	/**
	* Translate language variable
	*
	* @param $var string name
	*
	* @return string translated variable
	*/
	function trans($var)
	{
		global $lang_vars;

		return (array_key_exists($var, $lang_vars)) ? $lang_vars[ $var ] : $var;
	}

	// language
	if ( ! isset($_SESSION['RF']['language'])
		|| file_exists('lang/' . basename($_SESSION['RF']['language']) . '.php') === false
		|| ! is_readable('lang/' . basename($_SESSION['RF']['language']) . '.php')
	)
	{
		$lang = $config['default_language'];

		if (isset($_GET['lang']) && $_GET['lang'] != 'undefined' && $_GET['lang'] != '')
		{
			$lang = fix_get_params($_GET['lang']);
			$lang = trim($lang);
		}

		if ($lang != $config['default_language'])
		{
			$path_parts = pathinfo($lang);
			$lang = $path_parts['basename'];
			$languages = include 'lang/languages.php';
		}

		// add lang file to session for easy include
		$_SESSION['RF']['language'] = $lang;
	}
	else
	{
		if(file_exists('lang/languages.php')){
			$languages = include 'lang/languages.php';
		}else{
			$languages = include '../lang/languages.php';
		}

		if(array_key_exists($_SESSION['RF']['language'],$languages)){
			$lang = $_SESSION['RF']['language'];
		}else{
			response('Lang_Not_Found'.AddErrorLocation())->send();
			exit;
		}

	}
	if(file_exists('lang/' . $lang . '.php')){
		$lang_vars = include 'lang/' . $lang . '.php';
	}else{
		$lang_vars = include '../lang/' . $lang . '.php';
	}

	if ( ! is_array($lang_vars))
	{
		$lang_vars = array();
	}
}


function checkRelativePathPartial($path){
	if (strpos($path, '../') !== false
        || strpos($path, './') !== false
        || strpos($path, '/..') !== false
        || strpos($path, '..\\') !== false
        || strpos($path, '\\..') !== false
        || strpos($path, '.\\') !== false
        || $path === ".."
    ){
		return false;
    }
    return true;
}

/**
* Check relative path
*
* @param  string  $path
*
* @return boolean is it correct?
*/
function checkRelativePath($path){
	$path_correct = checkRelativePathPartial($path);
	if($path_correct){
		$path_decoded = rawurldecode($path);
		$path_correct = checkRelativePathPartial($path_decoded);
	}
    return $path_correct;
}

/**
* Check if the given path is an upload dir based on config
*
* @param  string  $path
* @param  array $config
*
* @return boolean is it an upload dir?
*/
function isUploadDir($path, $config){
	$upload_dir = $config['current_path'];
	$thumbs_dir = $config['thumbs_base_path'];
	if (realpath($path) === realpath($upload_dir) || realpath($path) === realpath($thumbs_dir))
	{
		return true;
	}
	return false;
}

/**
* Delete file
*
* @param  string  $path
* @param  string $path_thumb
* @param  array $config
*
* @return null
*/
function deleteFile($path,$path_thumb,$config){
	if ($config['delete_files']){
		$ftp = ftp_con($config);
		if($ftp){
			try{
				$ftp->delete("/".$path);
				@$ftp->delete("/".$path_thumb);
			}catch(FtpClient\FtpException $e){
				return;
			}
		}else{
			if (file_exists($path)){
				unlink($path);
			}
			if (file_exists($path_thumb)){
				unlink($path_thumb);
			}
		}

		$info=pathinfo($path);
		if (!$ftp && $config['relative_image_creation']){
			foreach($config['relative_path_from_current_pos'] as $k=>$path)
			{
				if ($path!="" && $path[strlen($path)-1]!="/") $path.="/";

				if (file_exists($info['dirname']."/".$path.$config['relative_image_creation_name_to_prepend'][$k].$info['filename'].$config['relative_image_creation_name_to_append'][$k].".".$info['extension']))
				{
					unlink($info['dirname']."/".$path.$config['relative_image_creation_name_to_prepend'][$k].$info['filename'].$config['relative_image_creation_name_to_append'][$k].".".$info['extension']);
				}
			}
		}

		if (!$ftp && $config['fixed_image_creation'])
		{
			foreach($config['fixed_path_from_filemanager'] as $k=>$path)
			{
				if ($path!="" && $path[strlen($path)-1] != "/") $path.="/";

				$base_dir=$path.substr_replace($info['dirname']."/", '', 0, strlen($config['current_path']));
				if (file_exists($base_dir.$config['fixed_image_creation_name_to_prepend'][$k].$info['filename'].$config['fixed_image_creation_to_append'][$k].".".$info['extension']))
				{
					unlink($base_dir.$config['fixed_image_creation_name_to_prepend'][$k].$info['filename'].$config['fixed_image_creation_to_append'][$k].".".$info['extension']);
				}
			}
		}
	}
}

/**
* Delete directory
*
* @param  string  $dir
*
* @return  bool
*/
function deleteDir($dir,$ftp = null, $config = null)
{
	if($ftp){

		try{
			$ftp->rmdir($dir);
			return true;

		}catch(FtpClient\FtpException $e){
			return null;
		}

	}else{
		if ( ! file_exists($dir) || isUploadDir($dir, $config))
		{
			return false;
		}
		if ( ! is_dir($dir))
		{
			return unlink($dir);
		}
		foreach (scandir($dir) as $item)
		{
			if ($item == '.' || $item == '..')
			{
				continue;
			}
			if ( ! deleteDir($dir . DIRECTORY_SEPARATOR . $item))
			{
				return false;
			}
		}
	}

	return rmdir($dir);
}

/**
* Make a file copy
*
* @param  string  $old_path
* @param  string  $name      New file name without extension
*
* @return  bool
*/
function duplicate_file( $old_path, $name, $ftp = null, $config = null )
{
	$info = pathinfo($old_path);
	$new_path = $info['dirname'] . "/" . $name . "." . $info['extension'];
	if($ftp){
		try{
			$tmp = time().$name . "." . $info['extension'];
			$ftp->get($tmp, "/".$old_path, FTP_BINARY);
			$ftp->put("/".$new_path, $tmp, FTP_BINARY);
			unlink($tmp);
			return true;

		}catch(FtpClient\FtpException $e){
			return null;
		}
	}else{
		if (file_exists($old_path) && is_file($old_path))
		{
			if (file_exists($new_path) && $old_path == $new_path)
			{
				return false;
			}

			return copy($old_path, $new_path);
		}
	}
}


/**
* Rename file
*
* @param  string  $old_path         File to rename
* @param  string  $name             New file name without extension
* @param  bool    $transliteration
*
* @return bool
*/
function rename_file($old_path, $name, $ftp = null, $config = null)
{
	$name = fix_filename($name, $config);
	$info = pathinfo($old_path);
	$new_path = $info['dirname'] . "/" . $name . "." . $info['extension'];
	if($ftp){
		try{
			return $ftp->rename("/".$old_path, "/".$new_path);
		}catch(FtpClient\FtpException $e){
			return false;
		}
	}else{
		if (file_exists($old_path) && is_file($old_path))
		{
			$new_path = $info['dirname'] . "/" . $name . "." . $info['extension'];
			if (file_exists($new_path) && $old_path == $new_path)
			{
				return false;
			}

			return rename($old_path, $new_path);
		}
	}
}


function url_exists($url){
    if (!$fp = curl_init($url)) return false;
    return true;
}


function tempdir() {
    $tempfile=tempnam(sys_get_temp_dir(),'');
    if (file_exists($tempfile)) { unlink($tempfile); }
    mkdir($tempfile);
    if (is_dir($tempfile)) { return $tempfile; }
}


/**
* Rename directory
*
* @param  string  $old_path         Directory to rename
* @param  string  $name             New directory name
* @param  bool    $transliteration
*
* @return bool
*/
function rename_folder($old_path, $name, $ftp = null, $config = null)
{
	$name = fix_filename($name, $config, true);
	$new_path = fix_dirname($old_path) . "/" . $name;
	if($ftp){
		if($ftp->chdir("/".$old_path)){
			if(@$ftp->chdir($new_path)){
				return false;
			}
			return $ftp->rename("/".$old_path, "/".$new_path);
		}
	}else{
		if (file_exists($old_path) && is_dir($old_path) && !isUploadDir($old_path, $config))
		{
			if (file_exists($new_path) && $old_path == $new_path)
			{
				return false;
			}
			return rename($old_path, $new_path);
		}
	}
}

function ftp_con($config){
	if(isset($config['ftp_host']) && $config['ftp_host']){
		// *** Include the class
		include('include/FtpClient.php');
		include('include/FtpException.php');
		include('include/FtpWrapper.php');

		$ftp = new \FtpClient\FtpClient();
		try{
			$ftp->connect($config['ftp_host'],$config['ftp_ssl'],$config['ftp_port']);
			$ftp->login($config['ftp_user'], $config['ftp_pass']);
			$ftp->pasv(true);
			return $ftp;
		}catch(FtpClient\FtpException $e){
			echo "Error: ";
			echo $e->getMessage();
			echo " to server ";
			$tmp = $e->getTrace();
			echo $tmp[0]['args'][0];
			echo "<br/>Please check configurations";
			die();
		}
	}else{
		return false;
	}
}

/**
* Create new image from existing file
*
* @param  string  $imgfile    Source image file name
* @param  string  $imgthumb   Thumbnail file name
* @param  int     $newwidth   Thumbnail width
* @param  int     $newheight  Optional thumbnail height
* @param  string  $option     Type of resize
*
* @return bool
* @throws \Exception
*/
function create_img($imgfile, $imgthumb, $newwidth, $newheight = null, $option = "crop",$config = array())
{
	$result = false;
	if(isset($config['ftp_host']) && $config['ftp_host']){
		if(url_exists($imgfile)){
			$temp = tempnam('/tmp','RF');
			unlink($temp);
			$temp .=".".substr(strrchr($imgfile,'.'),1);
			$handle = fopen($temp, "w");
			fwrite($handle, file_get_contents($imgfile));
			fclose($handle);
			$imgfile= $temp;
			$save_ftp = $imgthumb;
			$imgthumb = $temp;
		}
	}
	if(file_exists($imgfile) || strpos($imgfile,'http')===0){
		if (strpos($imgfile,'http')===0 || image_check_memory_usage($imgfile, $newwidth, $newheight))
		{
			require_once('php_image_magician.php');
			try{
				$magicianObj = new imageLib($imgfile);
				$magicianObj->resizeImage($newwidth, $newheight, $option);
				$magicianObj->saveImage($imgthumb, 80);
			}catch (Exception $e){
				return $e->getMessage();
			}
			$result = true;
		}
	}
	if($result && isset($config['ftp_host']) && $config['ftp_host'] ){
		$ftp->put($save_ftp, $imgthumb, FTP_BINARY);
		unlink($imgthumb);
	}

	return $result;
}

/**
* Convert convert size in bytes to human readable
*
* @param  int  $size
*
* @return  string
*/
function makeSize($size)
{
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
	$u = 0;
	while ((round($size / 1024) > 0) && ($u < 4))
	{
		$size = $size / 1024;
		$u++;
	}

	return (number_format($size, 0) . " " . trans($units[ $u ]));
}

/**
* Determine directory size
*
* @param  string  $path
*
* @return  int
*/
function folder_info($path,$count_hidden=true)
{
	global $config;
	$total_size = 0;
	$files = scandir($path);
	$cleanPath = rtrim($path, '/') . '/';
	$files_count = 0;
	$folders_count = 0;
	foreach ($files as $t)
	{
		if ($t != "." && $t != "..")
		{
			if ($count_hidden or !(in_array($t,$config['hidden_folders']) or in_array($t,$config['hidden_files'])))
			{
				$currentFile = $cleanPath . $t;
				if (is_dir($currentFile))
				{
					list($size,$tmp,$tmp1) = folder_info($currentFile);
					$total_size += $size;
					$folders_count ++;
				}
				else
				{
					$size = filesize($currentFile);
					$total_size += $size;
					$files_count++;
				}
			}
		}
	}

	return array($total_size,$files_count,$folders_count);
}
/**
* Get number of files in a directory
*
* @param  string  $path
*
* @return  int
*/
function filescount($path,$count_hidden=true)
{
	global $config;
	$total_count = 0;
	$files = scandir($path);
	$cleanPath = rtrim($path, '/') . '/';

	foreach ($files as $t)
	{
		if ($t != "." && $t != "..")
		{
			if ($count_hidden or !(in_array($t,$config['hidden_folders']) or in_array($t,$config['hidden_files'])))
			{
				$currentFile = $cleanPath . $t;
				if (is_dir($currentFile))
				{
					$size = filescount($currentFile);
					$total_count += $size;
				}
				else
				{
					$total_count += 1;
				}
			}
		}
	}

	return $total_count;
}
/**
* check if the current folder size plus the added size is over the overall size limite
*
* @param  int  $sizeAdded
*
* @return  bool
*/
function checkresultingsize($sizeAdded)
{
    global $config;

	if ($config['MaxSizeTotal'] !== false && is_int($config['MaxSizeTotal'])) {
		list($sizeCurrentFolder,$fileCurrentNum,$foldersCurrentCount) = folder_info($config['current_path'],false);
		// overall size over limit
		if (($config['MaxSizeTotal'] * 1024 * 1024) < ($sizeCurrentFolder + $sizeAdded)) {
			return false;
		}
	}
	return true;
}

/**
* Create directory for images and/or thumbnails
*
* @param  string  $path
* @param  string  $path_thumbs
*/
function create_folder($path = null, $path_thumbs = null,$ftp = null,$config = null)
{
	if($ftp){
		$ftp->mkdir($path);
		$ftp->mkdir($path_thumbs);
	}else{
		if(file_exists($path) || file_exists($path_thumbs)){
			return false;
		}
		$oldumask = umask(0);
		$permission = 0755;
		if(isset($config['folderPermission'])){
			$permission = $config['folderPermission'];
		}
		if ($path && !file_exists($path))
		{
			mkdir($path, $permission, true);
		} // or even 01777 so you get the sticky bit set
		if ($path_thumbs)
		{
			mkdir($path_thumbs, $permission, true) or die("$path_thumbs cannot be found");
		} // or even 01777 so you get the sticky bit set
		umask($oldumask);
		return true;
	}
}

/**
* Get file extension present in directory
*
* @param  string  $path
* @param  string  $ext
*/
function check_files_extensions_on_path($path, $ext)
{
	if ( ! is_dir($path))
	{
		$fileinfo = pathinfo($path);
		if ( ! in_array(mb_strtolower($fileinfo['extension']), $ext))
		{
			unlink($path);
		}
	}
	else
	{
		$files = scandir($path);
		foreach ($files as $file)
		{
			check_files_extensions_on_path(trim($path, '/') . "/" . $file, $ext);
		}
	}
}


/**
* Check file extension 
*
* @param  string  $extension
* @param  array   $config
*/

function check_file_extension($extension,$config){
	$check = false;
	if (!$config['ext_blacklist']) {
		if(in_array(mb_strtolower($extension), $conf['ext'])){
			$check = true;
		}
    } else {
    	if(!in_array(mb_strtolower($extension), $conf['ext_blacklist'])){
			$check = true;
		}
    }

	if($config['files_without_extension'] && $extension == ''){
		$check = true;
	}

	return $check;
}


/**
* Get file extension present in PHAR file
*
* @param  string  $phar
* @param  array   $files
* @param  string  $basepath
* @param  string  $ext
*/
function check_files_extensions_on_phar($phar, &$files, $basepath, $config)
{
	foreach ($phar as $file)
	{
		if ($file->isFile())
		{
			if (check_file_extension($file->getExtension()))
			{
				$files[] = $basepath . $file->getFileName();
			}
		}
		else
		{
			if ($file->isDir())
			{
				$iterator = new DirectoryIterator($file);
				check_files_extensions_on_phar($iterator, $files, $basepath . $file->getFileName() . '/', $config);
			}
		}
	}
}

/**
* Cleanup input
*
* @param  string  $str
*
* @return  string
*/
function fix_get_params($str)
{
	return strip_tags(preg_replace("/[^a-zA-Z0-9\.\[\]_| -]/", '', $str));
}


/**
* Check extension
*
* @param  string  $extension
* @param  array   $config
*
* @return bool
*/
function check_extension($extension,$config){
	$extension = fix_strtolower($extension);
	if((!$config['ext_blacklist'] && !in_array($extension, $config['ext'])) || ($config['ext_blacklist'] && in_array($extension, $config['ext_blacklist']))){
		return false;
	}
	return true;
}




/**
* Sanitize filename
*
* @param  string  $str
*
* @return string
*/
function sanitize($str)
{
	return strip_tags(htmlspecialchars($str));
}

/**
* Cleanup filename
*
* @param  string  $str
* @param  bool    $transliteration
* @param  bool    $convert_spaces
* @param  string  $replace_with
* @param  bool    $is_folder
*
* @return string
*/
function fix_filename($str, $config, $is_folder = false)
{
	$str = sanitize($str);
	if ($config['convert_spaces'])
	{
		$str = str_replace(' ', $config['replace_with'], $str);
	}

	if ($config['transliteration'])
	{
		if (!mb_detect_encoding($str, 'UTF-8', true))
		{
			$str = utf8_encode($str);
		}
		if (function_exists('transliterator_transliterate'))
		{
			$str = transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
		}
		else
		{
			$str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
		}

		$str = preg_replace("/[^a-zA-Z0-9\.\[\]_| -]/", '', $str);
	}

	$str = str_replace(array( '"', "'", "/", "\\" ), "", $str);
	$str = strip_tags($str);

	// Empty or incorrectly transliterated filename.
	// Here is a point: a good file UNKNOWN_LANGUAGE.jpg could become .jpg in previous code.
	// So we add that default 'file' name to fix that issue.
	if (!$config['empty_filename'] && strpos($str, '.') === 0 && $is_folder === false)
	{
		$str = 'file' . $str;
	}

	return trim($str);
}

/**
* Cleanup directory name
*
* @param  string  $str
*
* @return  string
*/
function fix_dirname($str)
{
	return str_replace('~', ' ', dirname(str_replace(' ', '~', $str)));
}

/**
* Correct strtoupper handling
*
* @param  string  $str
*
* @return  string
*/
function fix_strtoupper($str)
{
	if (function_exists('mb_strtoupper'))
	{
		return mb_strtoupper($str);
	}
	else
	{
		return strtoupper($str);
	}
}

/**
* Correct strtolower handling
*
* @param  string  $str
*
* @return  string
*/
function fix_strtolower($str)
{
	if (function_exists('mb_strtoupper'))
	{
		return mb_strtolower($str);
	}
	else
	{
		return strtolower($str);
	}
}

function fix_path($path, $config)
{
	$info = pathinfo($path);
	$tmp_path = $info['dirname'];
	$str = fix_filename($info['filename'], $config);
	if ($tmp_path != "")
	{
		return $tmp_path . DIRECTORY_SEPARATOR . $str;
	}
	else
	{
		return $str;
	}
}

/**
* @param  $current_path
* @param  $fld
*
* @return  bool
*/
function config_loading($current_path, $fld)
{
	if (file_exists($current_path . $fld . ".config"))
	{
		require_once($current_path . $fld . ".config");

		return true;
	}
	echo "!!!!" . $parent = fix_dirname($fld);
	if ($parent != "." && ! empty($parent))
	{
		config_loading($current_path, $parent);
	}

	return false;
}

/**
* Check if memory is enough to process image
*
* @param  string  $img
* @param  int     $max_breedte
* @param  int     $max_hoogte
*
* @return bool
*/
function image_check_memory_usage($img, $max_breedte, $max_hoogte)
{
	if (file_exists($img))
	{
		$K64 = 65536; // number of bytes in 64K
		$memory_usage = memory_get_usage();
		if(ini_get('memory_limit') > 0 ){
			
			$mem = ini_get('memory_limit');
			$memory_limit = 0;
			if (strpos($mem, 'M') !== false) $memory_limit = abs(intval(str_replace(array('M'), '', $mem) * 1024 * 1024));
			if (strpos($mem, 'G') !== false) $memory_limit = abs(intval(str_replace(array('G'), '', $mem) * 1024 * 1024 * 1024));
			
			$image_properties = getimagesize($img);
			$image_width = $image_properties[0];
			$image_height = $image_properties[1];
			if (isset($image_properties['bits']))
				$image_bits = $image_properties['bits'];
			else
				$image_bits = 0;
			$image_memory_usage = $K64 + ($image_width * $image_height * ($image_bits >> 3) * 2);
			$thumb_memory_usage = $K64 + ($max_breedte * $max_hoogte * ($image_bits >> 3) * 2);
			$memory_needed = abs(intval($memory_usage + $image_memory_usage + $thumb_memory_usage));

			if ($memory_needed > $memory_limit)
			{
				return false;
			}
		}
		return true;
	}
	return false;
}

/**
* Check is string is ended with needle
*
* @param  string  $haystack
* @param  string  $needle
*
* @return  bool
*/
if(!function_exists('ends_with')){
	function ends_with($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
}

/**
* TODO REFACTOR THIS!
*
* @param $targetPath
* @param $targetFile
* @param $name
* @param $current_path
* @param $config
*   relative_image_creation
*   relative_path_from_current_pos
*   relative_image_creation_name_to_prepend
*   relative_image_creation_name_to_append
*   relative_image_creation_width
*   relative_image_creation_height
*   relative_image_creation_option
*   fixed_image_creation
*   fixed_path_from_filemanager
*   fixed_image_creation_name_to_prepend
*   fixed_image_creation_to_append
*   fixed_image_creation_width
*   fixed_image_creation_height
*   fixed_image_creation_option
*
* @return bool
*/
function new_thumbnails_creation($targetPath, $targetFile, $name, $current_path, $config)
{
	//create relative thumbs
	$all_ok = true;

	$info = pathinfo($name);
	$info['filename'] = fix_filename($info['filename'],$config);
	if ($config['relative_image_creation'])
	{
		foreach ($config['relative_path_from_current_pos'] as $k => $path)
		{
			if ($path != "" && $path[ strlen($path) - 1 ] != "/")
			{
				$path .= "/";
			}
			if ( ! file_exists($targetPath . $path))
			{
				create_folder($targetPath . $path, false);
			}
			if ( ! ends_with($targetPath, $path))
			{
				if ( ! create_img($targetFile, $targetPath . $path . $config['relative_image_creation_name_to_prepend'][ $k ] . $info['filename'] . $config['relative_image_creation_name_to_append'][ $k ] . "." . $info['extension'], $config['relative_image_creation_width'][ $k ], $config['relative_image_creation_height'][ $k ], $config['relative_image_creation_option'][ $k ]))
				{
					$all_ok = false;
				}
			}
		}
	}

	//create fixed thumbs
	if ($config['fixed_image_creation'])
	{
		foreach ($config['fixed_path_from_filemanager'] as $k => $path)
		{
			if ($path != "" && $path[ strlen($path) - 1 ] != "/")
			{
				$path .= "/";
			}
			$base_dir = $path . substr_replace($targetPath, '', 0, strlen($current_path));
			if ( ! file_exists($base_dir))
			{
				create_folder($base_dir, false);
			}
			if ( ! create_img($targetFile, $base_dir . $config['fixed_image_creation_name_to_prepend'][ $k ] . $info['filename'] . $config['fixed_image_creation_to_append'][ $k ] . "." . $info['extension'], $config['fixed_image_creation_width'][ $k ], $config['fixed_image_creation_height'][ $k ], $config['fixed_image_creation_option'][ $k ]))
			{
				$all_ok = false;
			}
		}
	}

	return $all_ok;
}


/**
* Get a remote file, using whichever mechanism is enabled
*
* @param  string  $url
*
* @return  bool|mixed|string
*/
function get_file_by_url($url)
{
	if (ini_get('allow_url_fopen'))
	{
		$arrContextOptions=array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);
		return file_get_contents($url, false, stream_context_create($arrContextOptions));
	}
	if ( ! function_exists('curl_version'))
	{
		return false;
	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

/**
* test for dir/file writability properly
*
* @param  string  $dir
*
* @return  bool
*/
if ( ! function_exists('is_really_writable'))
{
	function is_really_writable($dir)
	{
		$dir = rtrim($dir, '/');
		// linux, safe off
		if (DIRECTORY_SEPARATOR == '/' && @ini_get("safe_mode") == false)
		{
			return is_writable($dir);
		}

		// Windows, safe ON. (have to write a file :S)
		if (is_dir($dir))
		{
			$dir = $dir . '/' . md5(mt_rand(1, 1000) . mt_rand(1, 1000));

			if (($fp = @fopen($dir, 'ab')) === false)
			{
				return false;
			}

			fclose($fp);
			@chmod($dir, 0755);
			@unlink($dir);

			return true;
		}
		elseif ( ! is_file($dir) || ($fp = @fopen($dir, 'ab')) === false)
		{
			return false;
		}

		fclose($fp);

		return true;
	}
}

/**
* Check if a function is callable.
* Some servers disable copy,rename etc.
*
* @parm  string  $name
*
* @return  bool
*/
function is_function_callable($name)
{
	if (function_exists($name) === false)
	{
		return false;
	}
	$disabled = explode(',', ini_get('disable_functions'));

	return ! in_array($name, $disabled);
}

/**
* recursivly copies everything
*
* @param  string  $source
* @param  string  $destination
* @param  bool    $is_rec
*/
function rcopy($source, $destination, $is_rec = false)
{
	if (is_dir($source))
	{
		if ($is_rec === false)
		{
			$pinfo = pathinfo($source);
			$destination = rtrim($destination, '/') . DIRECTORY_SEPARATOR . $pinfo['basename'];
		}
		if (is_dir($destination) === false)
		{
			mkdir($destination, 0755, true);
		}

		$files = scandir($source);
		foreach ($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				rcopy($source . DIRECTORY_SEPARATOR . $file, rtrim($destination, '/') . DIRECTORY_SEPARATOR . $file, true);
			}
		}
	}
	else
	{
		if (file_exists($source))
		{
			if (is_dir($destination) === true)
			{
				$pinfo = pathinfo($source);
				$dest2 = rtrim($destination, '/') . DIRECTORY_SEPARATOR . $pinfo['basename'];
			}
			else
			{
				$dest2 = $destination;
			}

			copy($source, $dest2);
		}
	}
}




/**
* recursivly renames everything
*
* I know copy and rename could be done with just one function
* but i split the 2 because sometimes rename fails on windows
* Need more feedback from users and refactor if needed
*
* @param  string  $source
* @param  string  $destination
* @param  bool    $is_rec
*/
function rrename($source, $destination, $is_rec = false)
{
	if (is_dir($source))
	{
		if ($is_rec === false)
		{
			$pinfo = pathinfo($source);
			$destination = rtrim($destination, '/') . DIRECTORY_SEPARATOR . $pinfo['basename'];
		}
		if (is_dir($destination) === false)
		{
			mkdir($destination, 0755, true);
		}

		$files = scandir($source);
		foreach ($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				rrename($source . DIRECTORY_SEPARATOR . $file, rtrim($destination, '/') . DIRECTORY_SEPARATOR . $file, true);
			}
		}
	}
	else
	{
		if (file_exists($source))
		{
			if (is_dir($destination) === true)
			{
				$pinfo = pathinfo($source);
				$dest2 = rtrim($destination, '/') . DIRECTORY_SEPARATOR . $pinfo['basename'];
			}
			else
			{
				$dest2 = $destination;
			}

			rename($source, $dest2);
		}
	}
}

// On windows rename leaves folders sometime
// This will clear leftover folders
// After more feedback will merge it with rrename
function rrename_after_cleaner($source)
{
	$files = scandir($source);

	foreach ($files as $file)
	{
		if ($file != "." && $file != "..")
		{
			if (is_dir($source . DIRECTORY_SEPARATOR . $file))
			{
				rrename_after_cleaner($source . DIRECTORY_SEPARATOR . $file);
			}
			else
			{
				unlink($source . DIRECTORY_SEPARATOR . $file);
			}
		}
	}

	return rmdir($source);
}

/**
* Recursive chmod
* @param  string  $source
* @param  int     $mode
* @param  string  $rec_option
* @param  bool    $is_rec
*/
function rchmod($source, $mode, $rec_option = "none", $is_rec = false)
{
	if ($rec_option == "none")
	{
		chmod($source, $mode);
	}
	else
	{
		if ($is_rec === false)
		{
			chmod($source, $mode);
		}

		$files = scandir($source);

		foreach ($files as $file)
		{
			if ($file != "." && $file != "..")
			{
				if (is_dir($source . DIRECTORY_SEPARATOR . $file))
				{
					if ($rec_option == "folders" || $rec_option == "both")
					{
						chmod($source . DIRECTORY_SEPARATOR . $file, $mode);
					}
					rchmod($source . DIRECTORY_SEPARATOR . $file, $mode, $rec_option, true);
				}
				else
				{
					if ($rec_option == "files" || $rec_option == "both")
					{
						chmod($source . DIRECTORY_SEPARATOR . $file, $mode);
					}
				}
			}
		}
	}
}

/**
* @param  string  $input
* @param  bool    $trace
* @param  bool    $halt
*/
function debugger($input, $trace = false, $halt = false)
{
	ob_start();

	echo "<br>----- DEBUG DUMP -----";
	echo "<pre>";
	var_dump($input);
	echo "</pre>";

	if ($trace)
	{
		if (is_php('5.3.6'))
		{
			$debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		}
		else
		{
			$debug = debug_backtrace(false);
		}

		echo "<br>-----STACK TRACE-----";
		echo "<pre>";
		var_dump($debug);
		echo "</pre>";
	}

	echo "</pre>";
	echo "---------------------------<br>";

	$ret = ob_get_contents();
	ob_end_clean();

	echo $ret;

	if ($halt == true)
	{
		exit();
	}
} 

/**
* @param  string  $version
*
* @return  bool
*/
if ( ! function_exists('is_php')) {
	function is_php($version = '5.0.0')
	{
		static $phpVer;
		$version = (string) $version;

		if ( ! isset($phpVer[ $version ]))
		{
			$phpVer[ $version ] = (version_compare(PHP_VERSION, $version) < 0) ? false : true;
		}

		return $phpVer[ $version ];
	}
}

/**
* Return the caller location if set in config.php
* @param  string  $version
*
* @return  bool
*/
function AddErrorLocation()
{
	if (defined('DEBUG_ERROR_MESSAGE') and DEBUG_ERROR_MESSAGE) {
		$pile=debug_backtrace();
		return " (@".$pile[0]["file"]."#".$pile[0]["line"].")";
	}
	return "";
}
