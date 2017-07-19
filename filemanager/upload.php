<?php
if (!isset($config)){
  $config = include 'config/config.php';
  //TODO switch to array
  extract($config, EXTR_OVERWRITE);
}
include 'include/utils.php';

if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager")
{
	response(trans('forbiden').AddErrorLocation(), 403)->send();
	exit;
}

include 'include/mime_type_lib.php';

if (isset($_POST['path']))
{
   $storeFolder = $_POST['path'];
   $storeFolderThumb = $_POST['path_thumb'];
}
else
{
   $storeFolder = $current_path.$_POST["fldr"]; // correct for when IE is in Compatibility mode
   $storeFolderThumb = $thumbs_base_path.$_POST["fldr"];
}

$ftp=ftp_con($config);
if($ftp){
	$source_base = $ftp_base_folder.$upload_dir;
	$thumb_base = $ftp_base_folder.$ftp_thumbs_dir;
	$path_pos  = strpos($storeFolder,$source_base);
	$thumb_pos = strpos($storeFolderThumb,$thumb_base);

}else{
	$source_base = $current_path;
	$thumb_base = $thumbs_base_path;
	$path_pos  = strpos($storeFolder,$source_base);
	$thumb_pos = strpos($storeFolderThumb,$thumb_base);
}

if ($path_pos!==0
	|| $thumb_pos !==0
	|| strpos($storeFolderThumb,'../',strlen($thumb_base)) !== FALSE
	|| strpos($storeFolderThumb,'./',strlen($thumb_base)) !== FALSE
	|| strpos($storeFolder,'../',strlen($source_base)) !== FALSE
	|| strpos($storeFolder,'./',strlen($source_base)) !== FALSE
	|| strpos($storeFolderThumb,'..\\',strlen($thumb_base)) !== FALSE
	|| strpos($storeFolderThumb,'.\\',strlen($thumb_base)) !== FALSE
	|| strpos($storeFolder,'..\\',strlen($source_base)) !== FALSE
	|| strpos($storeFolder,'.\\',strlen($source_base)) !== FALSE )
{
	response(trans('wrong path'.AddErrorLocation()))->send();
	exit;
}

$path = $storeFolder;
$cycle = TRUE;
$max_cycles = 50;
$i = 0;
while ($cycle && $i < $max_cycles)
{
	$i++;
	if ($path == $current_path) $cycle = FALSE;
	if (file_exists($path."config.php"))
	{
		require_once $path."config.php";
		$cycle = FALSE;
	}
	$path = fix_dirname($path).'/';
}


if ( ! empty($_FILES) || isset($_POST['url']))
{
	if(isset($_POST['url'])){
		$temp = tempnam('/tmp','RF');
		$handle = fopen($temp, "w");
		fwrite($handle, file_get_contents($_POST['url']));
		fclose($handle);
		$_FILES['file']= array(
			'name' => basename($_POST['url']),
			'tmp_name' => $temp,
			'size' => filesize($temp),
			'type' => explode(".", strtolower($temp))
		);
	}

	$info = pathinfo($_FILES['file']['name']);
	$mime_type = $_FILES['file']['type'];
	if (function_exists('mime_content_type')){
		$mime_type = mime_content_type($_FILES['file']['tmp_name']);
	}elseif(function_exists('finfo_open')){
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);
	}else{
		include 'include/mime_type_lib.php';
		$mime_type = get_file_mime_type($_FILES['file']['tmp_name']);
	}

	$extension = get_extension_from_mime($mime_type);

	if($extension=='so'){
		$extension = $info['extension'];
	}

	if (in_array(fix_strtolower($extension), $ext))
	{
		$tempFile = $_FILES['file']['tmp_name'];
		$targetPath = $storeFolder;
		$targetPathThumb = $storeFolderThumb;
		$_FILES['file']['name'] = fix_filename($info['filename'].".".$extension,$config);
		// LowerCase
		if ($lower_case)
		{
			$_FILES['file']['name'] = fix_strtolower($_FILES['file']['name']);
		}
		// Gen. new file name if exists
		if (file_exists($targetPath.$_FILES['file']['name']))
		{
			$i = 1;
			$info = pathinfo($_FILES['file']['name']);

			// append number
			while(file_exists($targetPath.$info['filename']."_".$i.".".$extension)) {
				$i++;
			}
			$_FILES['file']['name'] = $info['filename']."_".$i.".".$extension;
		}

		$targetFile =  $targetPath. $_FILES['file']['name'];
		$targetFileThumb =  $targetPathThumb. $_FILES['file']['name'];

		// check if image (and supported)
		if (in_array(fix_strtolower($extension),$ext_img)) $is_img=TRUE;
		else $is_img=FALSE;

		if (!checkresultingsize($_FILES['file']['size'])) {
			response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation(), 406)->send();
			exit;
		}

		// upload
		if($ftp){
			$targetFile =  tempnam('/tmp','RF').$_FILES['file']['name'];
			if ($is_img)
			{
				$targetFileThumb =  tempnam('/tmp','RF').$_FILES['file']['name'];
			}
		}

		if(is_uploaded_file($tempFile)){
			move_uploaded_file($tempFile,$targetFile);
		}else{
			copy($tempFile,$targetFile);
			unlink($tempFile);
		}
		chmod($targetFile, $fileFolderPermission);

		if ($is_img)
		{
			if(isset($image_watermark) && $image_watermark){
				require_once('include/php_image_magician.php');

				$magicianObj = new imageLib($targetFile);
				$magicianObj -> addWatermark($image_watermark, $image_watermark_position,  $image_watermark_padding);

				$magicianObj -> saveImage($targetFile);
			}

			$memory_error = FALSE;
			if ( $extension != 'svg' && !create_img($targetFile, $targetFileThumb, 122, 91))
			{
				$memory_error = TRUE;
			}
			else
			{

				// TODO something with this long function baaaah...
				if( !$ftp && ! new_thumbnails_creation($targetPath,$targetFile,$_FILES['file']['name'],$current_path,$relative_image_creation,$relative_path_from_current_pos,$relative_image_creation_name_to_prepend,$relative_image_creation_name_to_append,$relative_image_creation_width,$relative_image_creation_height,$relative_image_creation_option,$fixed_image_creation,$fixed_path_from_filemanager,$fixed_image_creation_name_to_prepend,$fixed_image_creation_to_append,$fixed_image_creation_width,$fixed_image_creation_height,$fixed_image_creation_option))
				{
					$memory_error = TRUE;
				}
				else
				{
					$imginfo = getimagesize($targetFile);
					$srcWidth = $imginfo[0];
					$srcHeight = $imginfo[1];

					// resize images if set
					if ($image_resizing)
					{
						if ($image_resizing_width == 0) // if width not set
						{
							if ($image_resizing_height == 0)
							{
								$image_resizing_width = $srcWidth;
								$image_resizing_height = $srcHeight;
							}
							else
							{
								$image_resizing_width = $image_resizing_height*$srcWidth/$srcHeight;
							}
						}
						elseif ($image_resizing_height == 0) // if height not set
						{
							$image_resizing_height = $image_resizing_width*$srcHeight/$srcWidth;
						}

						// new dims and create
						$srcWidth = $image_resizing_width;
						$srcHeight = $image_resizing_height;
						create_img($targetFile, $targetFile, $image_resizing_width, $image_resizing_height, $image_resizing_mode);
					}

					//max resizing limit control
					$resize = FALSE;
					if ($image_max_width != 0 && $srcWidth > $image_max_width && $image_resizing_override === FALSE)
					{
						$resize = TRUE;
						$srcWidth = $image_max_width;

						if ($image_max_height == 0) $srcHeight = $image_max_width*$srcHeight/$srcWidth;
					}

					if ($image_max_height != 0 && $srcHeight > $image_max_height && $image_resizing_override === FALSE){
						$resize = TRUE;
						$srcHeight = $image_max_height;

						if ($image_max_width == 0) $srcWidth = $image_max_height*$srcWidth/$srcHeight;
					}

					if ($resize){ create_img($targetFile, $targetFile, $srcWidth, $srcHeight, $image_max_mode); }
				}
			}

			// not enough memory
			if ($memory_error)
			{
				unlink($targetFile);
				response(trans("Not enought Memory").AddErrorLocation(), 406)->send();
				exit();
			}
		}

		if($ftp){

			$ftp->put($targetPath. $_FILES['file']['name'], $targetFile, FTP_BINARY);
			unlink($targetFile);
			if ($is_img)
			{
				$ftp->put($targetPathThumb. $_FILES['file']['name'], $targetFileThumb, FTP_BINARY);
				unlink($targetFileThumb);
			}
		}
		echo $_FILES['file']['name'];
	}
	else // file ext. is not in the allowed list
	{
		response(trans("Error_extension").AddErrorLocation(), 406)->send();

		exit();
	}
}
else // no files to upload
{
	response(trans("no file").AddErrorLocation(), 405)->send();
	exit();
}

// redirect
if (isset($_POST['submit']))
{
	$query = http_build_query(array(
		'type'	  	=> $_POST['type'],
		'lang'	  	=> $_POST['lang'],
		'popup'			=> $_POST['popup'],
		'field_id'  => $_POST['field_id'],
		'fldr'	  	=> $_POST['fldr'],
	));

	header("location: dialog.php?" . $query);
}
