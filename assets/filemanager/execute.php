<?php
$config = include 'config/config.php';
//TODO switch to array
extract($config, EXTR_OVERWRITE);

include 'include/utils.php';

if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager")
{
	response(trans('forbiden').AddErrorLocation())->send();
	exit;
}

if (strpos($_POST['path'],'/')===0
	|| strpos($_POST['path'],'../')!==FALSE
	|| strpos($_POST['path'],'./')===0
	|| strpos($_POST['path'],'..\\')!==FALSE
	|| strpos($_POST['path'],'.\\')===0)
{
	response(trans('wrong path'.AddErrorLocation()))->send();
	exit;
}

if (isset($_SESSION['RF']['language']) && file_exists('lang/' . basename($_SESSION['RF']['language']) . '.php'))
{
	$languages = include 'lang/languages.php';
	if(array_key_exists($_SESSION['RF']['language'],$languages)){
		include 'lang/' . basename($_SESSION['RF']['language']) . '.php';
	}else{
		response(trans('Lang_Not_Found').AddErrorLocation())->send();
		exit;
	}
}
else
{
	response(trans('Lang_Not_Found').AddErrorLocation())->send();
	exit;
}

$ftp = ftp_con($config);

$base = $current_path;
$path = $base.$_POST['path'];
$cycle = TRUE;
$max_cycles = 50;
$i = 0;
while($cycle && $i<$max_cycles)
{
	$i++;
	if ($path == $base)  $cycle=FALSE;

	if (file_exists($path."config.php"))
	{
		require_once $path."config.php";
		$cycle = FALSE;
	}
	$path = fix_dirname($path)."/";
}

$path = $current_path.$_POST['path'];
$path_thumb = $thumbs_base_path.$_POST['path'];

if($ftp){
	$path = $ftp_base_folder.$upload_dir.$_POST['path'];
	$path_thumb = $ftp_base_folder.$ftp_thumbs_dir.$_POST['path'];
}

if (isset($_POST['name']))
{
	$name = fix_filename($_POST['name'],$config);
	if (strpos($name,'../') !== FALSE || strpos($name,'..\\') !== FALSE)
	{
		response(trans('wrong name').AddErrorLocation())->send();
		exit;
	}
}

$info = pathinfo($path);
if (isset($info['extension']) && !(isset($_GET['action']) && $_GET['action']=='delete_folder') && !in_array(strtolower($info['extension']), $ext) && $_GET['action'] != 'create_file')
{
	response(trans('wrong extension').AddErrorLocation())->send();
	exit;
}

if (isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'delete_file':
			if ($delete_files){
				if($ftp){
					try{
						$ftp->delete("/".$path);
						@$ftp->delete("/".$path_thumb);
					}catch(FtpClient\FtpException $e){
						return;
					}
				}else{

					unlink($path);
					if (file_exists($path_thumb)){
						unlink($path_thumb);
					}
				}

				$info=pathinfo($path);
				if (!$ftp && $relative_image_creation){
					foreach($relative_path_from_current_pos as $k=>$path)
					{
						if ($path!="" && $path[strlen($path)-1]!="/") $path.="/";

						if (file_exists($info['dirname']."/".$path.$relative_image_creation_name_to_prepend[$k].$info['filename'].$relative_image_creation_name_to_append[$k].".".$info['extension']))
						{
							unlink($info['dirname']."/".$path.$relative_image_creation_name_to_prepend[$k].$info['filename'].$relative_image_creation_name_to_append[$k].".".$info['extension']);
						}
					}
				}

				if (!$ftp && $fixed_image_creation)
				{
					foreach($fixed_path_from_filemanager as $k=>$path)
					{
						if ($path!="" && $path[strlen($path)-1] != "/") $path.="/";

						$base_dir=$path.substr_replace($info['dirname']."/", '', 0, strlen($current_path));
						if (file_exists($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension']))
						{
							unlink($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension']);
						}
					}
				}
			}
			break;
		case 'delete_folder':
			if ($delete_folders){

				if($ftp){
					deleteDir($path,$ftp,$config);
					deleteDir($path_thumb,$ftp,$config);
				}else{
					if (is_dir($path_thumb))
					{
						deleteDir($path_thumb);
					}

					if (is_dir($path))
					{
						deleteDir($path);
						if ($fixed_image_creation)
						{
							foreach($fixed_path_from_filemanager as $k=>$paths){
								if ($paths!="" && $paths[strlen($paths)-1] != "/") $paths.="/";

								$base_dir=$paths.substr_replace($path, '', 0, strlen($current_path));
								if (is_dir($base_dir)) deleteDir($base_dir);
							}
						}
					}
				}
			}
			break;
		case 'create_folder':
			if ($create_folders)
			{

				$name = fix_filename($_POST['name'],$config);
				$path .= $name;
				$path_thumb .= $name;
				create_folder(fix_path($path,$config),fix_path($path_thumb,$config),$ftp,$config);
			}
			break;
		case 'rename_folder':
			if ($rename_folders){
				$name=fix_filename($name,$config);
				$name=str_replace('.','',$name);

				if (!empty($name)){
					if (!rename_folder($path,$name,$ftp,$config))
					{
						response(trans('Rename_existing_folder').AddErrorLocation())->send();
						exit;
					}
					rename_folder($path_thumb,$name,$ftp,$config);
					if (!$ftp && $fixed_image_creation){
						foreach($fixed_path_from_filemanager as $k=>$paths){
							if ($paths!="" && $paths[strlen($paths)-1] != "/") $paths.="/";

							$base_dir=$paths.substr_replace($path, '', 0, strlen($current_path));
							rename_folder($base_dir,$name,$ftp,$config);
						}
					}
				} else {
					response(trans('Empty_name').AddErrorLocation())->send();
					exit;
				}
			}
			break;
		case 'create_file':
			if ($create_text_files === FALSE) {
				response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))).AddErrorLocation())->send();
				exit;
			}

			if (!isset($editable_text_file_exts) || !is_array($editable_text_file_exts)){
				$editable_text_file_exts = array();
			}

			// check if user supplied extension
			if (strpos($name, '.') === FALSE){
				response(trans('No_Extension').' '.sprintf(trans('Valid_Extensions'), implode(', ', $editable_text_file_exts)).AddErrorLocation())->send();
				exit;
			}

			// correct name
			$old_name = $name;
			$name=fix_filename($name,$config);
			if (empty($name))
			{
				response(trans('Empty_name').AddErrorLocation())->send();
				exit;
			}

			// check extension
			$parts = explode('.', $name);
			if (!in_array(end($parts), $editable_text_file_exts)) {
				response(trans('Error_extension').' '.sprintf(trans('Valid_Extensions'), implode(', ', $editable_text_file_exts)), 400)->send();
				exit;
			}

			$content = $_POST['new_content'];

			if($ftp){
				$tmp = time().$name;
				file_put_contents($tmp, $content);
				$ftp->put("/".$path.$name, $tmp, FTP_BINARY);
				unlink($tmp);
				response(trans('File_Save_OK'))->send();
			}else{
				if (!checkresultingsize(strlen($content))) {
					response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
					exit;
				}
				// file already exists
				if (file_exists($path.$name)) {
					response(trans('Rename_existing_file').AddErrorLocation())->send();
					exit;
				}

				if (@file_put_contents($path.$name, $content) === FALSE) {
					response(trans('File_Save_Error').AddErrorLocation())->send();
					exit;
				} else {
					if (is_function_callable('chmod') !== FALSE){
						chmod($path.$name, 0644);
					}
					response(trans('File_Save_OK'))->send();
					exit;
				}
			}

			break;
		case 'rename_file':
			if ($rename_files){
				$name=fix_filename($name,$config);
				if (!empty($name))
				{
					if (!rename_file($path,$name,$ftp,$config))
					{
						response(trans('Rename_existing_file').AddErrorLocation())->send();
						exit;
					}

					rename_file($path_thumb,$name,$ftp,$config);

					if ($fixed_image_creation)
					{
						$info=pathinfo($path);

						foreach($fixed_path_from_filemanager as $k=>$paths)
						{
							if ($paths!="" && $paths[strlen($paths)-1] != "/") $paths.="/";

							$base_dir = $paths.substr_replace($info['dirname']."/", '', 0, strlen($current_path));
							if (file_exists($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension']))
							{
								rename_file($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension'],$fixed_image_creation_name_to_prepend[$k].$name.$fixed_image_creation_to_append[$k],$ftp,$config);
							}
						}
					}
				} else {
					response(trans('Empty_name').AddErrorLocation())->send();
					exit;
				}
			}
			break;
		case 'duplicate_file':
			if ($duplicate_files)
			{
				$name=fix_filename($name,$config);
				if (!empty($name))
				{
					if (!$ftp && !checkresultingsize(filesize($path))) {
						response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
						exit;
					}
					if (!duplicate_file($path,$name,$ftp,$config))
					{
						response(trans('Rename_existing_file').AddErrorLocation())->send();
						exit;
					}

					duplicate_file($path_thumb,$name,$ftp,$config);

					if (!$ftp && $fixed_image_creation)
					{
						$info=pathinfo($path);
						foreach($fixed_path_from_filemanager as $k=>$paths)
						{
							if ($paths!="" && $paths[strlen($paths)-1] != "/") $paths.= "/";

							$base_dir=$paths.substr_replace($info['dirname']."/", '', 0, strlen($current_path));

							if (file_exists($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension']))
							{
								duplicate_file($base_dir.$fixed_image_creation_name_to_prepend[$k].$info['filename'].$fixed_image_creation_to_append[$k].".".$info['extension'],$fixed_image_creation_name_to_prepend[$k].$name.$fixed_image_creation_to_append[$k]);
							}
						}
					}
				} else {
					response(trans('Empty_name').AddErrorLocation())->send();
					exit;
				}
			}
			break;

		case 'paste_clipboard':
			if ( ! isset($_SESSION['RF']['clipboard_action'], $_SESSION['RF']['clipboard']['path'])
				|| $_SESSION['RF']['clipboard_action'] == ''
				|| $_SESSION['RF']['clipboard']['path'] == '')
			{
				response()->send();
				exit;
			}

			$action = $_SESSION['RF']['clipboard_action'];
			$data = $_SESSION['RF']['clipboard'];


			if($ftp){
				if($_POST['path']!=""){
					$path.=DIRECTORY_SEPARATOR;
					$path_thumb.=DIRECTORY_SEPARATOR;
				}
				$path_thumb .= basename($data['path']);
				$path .= basename($data['path']) ;
				$data['path_thumb'] = DIRECTORY_SEPARATOR.$config['ftp_base_folder'].$config['ftp_thumbs_dir'].$data['path'];
				$data['path'] = DIRECTORY_SEPARATOR.$config['ftp_base_folder'].$config['upload_dir'].$data['path'];
			}else{
				$data['path_thumb'] = $thumbs_base_path.$data['path'];
				$data['path'] = $current_path.$data['path'];
			}

			$pinfo = pathinfo($data['path']);

			// user wants to paste to the same dir. nothing to do here...
			if ($pinfo['dirname'] == rtrim($path, DIRECTORY_SEPARATOR)) {
				response()->send();
				exit;
			}

			// user wants to paste folder to it's own sub folder.. baaaah.
			if (is_dir($data['path']) && strpos($path, $data['path']) !== FALSE){
				response()->send();
				exit;
			}

			// something terribly gone wrong
			if ($action != 'copy' && $action != 'cut'){
				response(trans('wrong action').AddErrorLocation())->send();
				exit;
			}
			if($ftp){
				if ($action == 'copy')
				{
					$tmp = time().basename($data['path']);
					$ftp->get($tmp, $data['path'], FTP_BINARY);
					$ftp->put(DIRECTORY_SEPARATOR.$path, $tmp, FTP_BINARY);
					unlink($tmp);

					if(url_exists($data['path_thumb'])){
						$tmp = time().basename($data['path_thumb']);
						@$ftp->get($tmp, $data['path_thumb'], FTP_BINARY);
						@$ftp->put(DIRECTORY_SEPARATOR.$path_thumb, $tmp, FTP_BINARY);
						unlink($tmp);
					}

				} elseif ($action == 'cut') {
					$ftp->rename($data['path'], DIRECTORY_SEPARATOR.$path);
					if(url_exists($data['path_thumb'])){
						@$ftp->rename($data['path_thumb'], DIRECTORY_SEPARATOR.$path_thumb);
					}
				}
			}else{
				// check for writability
				if (is_really_writable($path) === FALSE || is_really_writable($path_thumb) === FALSE){
					response(trans('Dir_No_Write').'<br/>'.str_replace('../','',$path).'<br/>'.str_replace('../','',$path_thumb).AddErrorLocation())->send();
					exit;
				}

				// check if server disables copy or rename
				if (is_function_callable(($action == 'copy' ? 'copy' : 'rename')) === FALSE){
					response(sprintf(trans('Function_Disabled'), ($action == 'copy' ? (trans('Copy')) : (trans('Cut')))).AddErrorLocation())->send();
					exit;
				}
				if ($action == 'copy')
				{
					list($sizeFolderToCopy,$fileNum,$foldersCount) = folder_info($path,false);
					if (!checkresultingsize($sizeFolderToCopy)) {
						response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
						exit;
					}
					rcopy($data['path'], $path);
					rcopy($data['path_thumb'], $path_thumb);
				} elseif ($action == 'cut') {
					rrename($data['path'], $path);
					rrename($data['path_thumb'], $path_thumb);

					// cleanup
					if (is_dir($data['path']) === TRUE){
						rrename_after_cleaner($data['path']);
						rrename_after_cleaner($data['path_thumb']);
					}
				}
			}

			// cleanup
			$_SESSION['RF']['clipboard']['path'] = NULL;
			$_SESSION['RF']['clipboard_action'] = NULL;

			break;
		case 'chmod':
			$mode = $_POST['new_mode'];
			$rec_option = $_POST['is_recursive'];
			$valid_options = array('none', 'files', 'folders', 'both');
			$chmod_perm = ($_POST['folder'] ? $chmod_dirs : $chmod_files);

			// check perm
			if ($chmod_perm === FALSE) {
				response(sprintf(trans('File_Permission_Not_Allowed'), (is_dir($path) ? (trans('Folders')) : (trans('Files')) )).AddErrorLocation())->send();
				exit;
			}
			// check mode
			if (!preg_match("/^[0-7]{3}$/", $mode)){
				response(trans('File_Permission_Wrong_Mode').AddErrorLocation())->send();
				exit;
			}
			// check recursive option
			if (!in_array($rec_option, $valid_options)){
				response(trans("wrong option").AddErrorLocation())->send();
				exit;
			}
			// check if server disabled chmod
			if (!$ftp && is_function_callable('chmod') === FALSE){
				response(sprintf(trans('Function_Disabled'), 'chmod').AddErrorLocation())->send();
				exit;
			}

			$mode = "0".$mode;
			$mode = octdec($mode);
			if($ftp){
				$ftp->chmod($mode, "/".$path);
			}else{
				rchmod($path, $mode, $rec_option);
			}

			break;
		case 'save_text_file':
			$content = $_POST['new_content'];
			// $content = htmlspecialchars($content); not needed
			// $content = stripslashes($content);

			if($ftp){
				$tmp = time();
				file_put_contents($tmp, $content);
				try{
					$ftp->put("/".$path, $tmp, FTP_BINARY);
				}catch(FtpClient\FtpException $e){
					echo $e->getMessage();
				}
				unlink($tmp);
				response(trans('File_Save_OK'))->send();
			}else{
				// no file
				if (!file_exists($path)) {
					response(trans('File_Not_Found').AddErrorLocation())->send();
					exit;
				}

				// not writable or edit not allowed
				if (!is_writable($path) || $edit_text_files === FALSE) {
					response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))).AddErrorLocation())->send();
					exit;
				}

				if (!checkresultingsize(strlen($content))) {
					response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
					exit;
				}
				if (@file_put_contents($path, $content) === FALSE) {
					response(trans('File_Save_Error').AddErrorLocation())->send();
					exit;
				} else {
					response(trans('File_Save_OK'))->send();
					exit;
				}
			}

			break;
		default:
			response(trans('wrong action').AddErrorLocation())->send();
			exit;
	}
}
?>