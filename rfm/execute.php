<?php
$config = include 'config/config.php';

include 'include/utils.php';

if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager") {
    response(trans('forbidden') . AddErrorLocation())->send();
    exit;
}

if (!checkRelativePath($_POST['path'])) {
    response(trans('wrong path') . AddErrorLocation())->send();
    exit;
}

if (isset($_SESSION['RF']['language']) && file_exists('lang/' . basename($_SESSION['RF']['language']) . '.php')) {
    $languages = include 'lang/languages.php';
    if (array_key_exists($_SESSION['RF']['language'], $languages)) {
        include 'lang/' . basename($_SESSION['RF']['language']) . '.php';
    } else {
        response(trans('Lang_Not_Found') . AddErrorLocation())->send();
        exit;
    }
} else {
    response(trans('Lang_Not_Found') . AddErrorLocation())->send();
    exit;
}

$ftp = ftp_con($config);

$base = $config['current_path'];
$path = $base . $_POST['path'];
$cycle = true;
$max_cycles = 50;
$i = 0;

while ($cycle && $i < $max_cycles) {
    $i++;
    if ($path == $base) {
        $cycle = false;
    }

    if (file_exists($path . "config.php")) {
        require_once $path . "config.php";
        $cycle = false;
    }
    $path = fix_dirname($path) . "/";
}

function returnPaths(string $_path, $_name, array $config)
{
    global $ftp;
    $path = $config['current_path'] . $_path;
    $path_thumb = $config['thumbs_base_path'] . $_path;
    $name = null;
    if ($ftp) {
        $path = $config['ftp_base_folder'] . $config['upload_dir'] . $_path;
        $path_thumb = $config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $_path;
    }
    if ($_name) {
        $name = fix_filename($_name, $config);
        if (strpos($name, '../') !== false || strpos($name, '..\\') !== false) {
            response(trans('wrong name') . AddErrorLocation())->send();
            exit;
        }
    }
    return [$path, $path_thumb, $name];
}

if(isset($_POST['paths'])){
	$paths = $paths_thumb = $names = [];
	foreach ($_POST['paths'] as $key => $path) {
		if (!checkRelativePath($path))
		{
			response(trans('wrong path').AddErrorLocation())->send();
			exit;
		}
		$name = null;
		if(isset($_POST['names'][$key])){
			$name = $_POST['names'][$key];
		}
		[$path, $path_thumb, $name] = returnPaths($path,$name,$config);
		$paths[] = $path;
		$paths_thumb[] = $path_thumb;
		$names = $name;
	}
} else {
	$name = null;
	if(isset($_POST['name'])){
		$name = $_POST['name'];
	}
	[$path, $path_thumb, $name] = returnPaths($_POST['path'],$name,$config);

}

$info = pathinfo($path);
if (isset($info['extension']) && !(isset($_GET['action']) && $_GET['action'] == 'delete_folder') &&
    !check_extension($info['extension'], $config)
    && $_GET['action'] != 'create_file') {
    response(trans('wrong extension') . AddErrorLocation())->send();
    exit;
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete_file':

            deleteFile($path, $path_thumb, $config);

            break;

        case 'delete_files':
            foreach ($paths as $key => $p) {
                deleteFile($p, $paths_thumb[$key], $config);
            }

			break;
		case 'delete_folder':
			if ($config['delete_folders']){

				if($ftp){
					deleteDir($path,$ftp,$config);
					deleteDir($path_thumb,$ftp,$config);
				}else{
					if (is_dir($path_thumb))
					{
						deleteDir($path_thumb,NULL,$config);
					}

					if (is_dir($path))
					{
						deleteDir($path,NULL,$config);
						if ($config['fixed_image_creation'])
						{
							foreach($config['fixed_path_from_filemanager'] as $paths){
								if ($paths != "" && $paths[strlen($paths)-1] != "/") {
            $paths.="/";
        }

								$base_dir=$paths.substr_replace($path, '', 0, strlen($config['current_path']));
								if (is_dir($base_dir)) {
            deleteDir($base_dir,NULL,$config);
        }
							}
						}
					}
				}
			}
			break;
		case 'create_folder':
			if ($config['create_folders'])
			{

				$name = fix_filename($_POST['name'],$config);
				$path .= $name;
				$path_thumb .= $name;
				$res = create_folder(fix_path($path,$config),fix_path($path_thumb,$config),$ftp,$config);
				if(!$res){
					response(trans('Rename_existing_folder').AddErrorLocation())->send();
				}
			}
			break;
		case 'rename_folder':
			if ($config['rename_folders']){
                if(!is_dir($path)) {
                    response(trans('wrong path').AddErrorLocation())->send();
                    exit;
                }
                $name = fix_filename($name, $config);
                $name = str_replace('.', '', $name);

                if (!empty($name)) {
                    if (!rename_folder($path, $name, $ftp, $config)) {
                        response(trans('Rename_existing_folder') . AddErrorLocation())->send();
                        exit;
                    }
                    rename_folder($path_thumb, $name, $ftp, $config);
                    if (!$ftp && $config['fixed_image_creation']) {
                        foreach ($config['fixed_path_from_filemanager'] as $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }

                            $base_dir = $paths . substr_replace($path, '', 0, strlen($config['current_path']));
                            rename_folder($base_dir, $name, $ftp, $config);
                        }
                    }
                } else {
                    response(trans('Empty_name') . AddErrorLocation())->send();
                    exit;
                }
            }
            break;

        case 'create_file':
            if ($config['create_text_files'] === false) {
                response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))) . AddErrorLocation())->send();
                exit;
            }

            if (!isset($config['editable_text_file_exts']) || !is_array($config['editable_text_file_exts'])) {
                $config['editable_text_file_exts'] = [];
            }

            // check if user supplied extension
            if (strpos($name, '.') === false) {
                response(trans('No_Extension') . ' ' . sprintf(trans('Valid_Extensions'), implode(', ', $config['editable_text_file_exts'])) . AddErrorLocation())->send();
                exit;
            }

            // correct name
            $old_name = $name;
            $name = fix_filename($name, $config);
            if ($name === '') {
                response(trans('Empty_name') . AddErrorLocation())->send();
                exit;
            }

            // check extension
            $parts = explode('.', $name);
            if (!in_array(end($parts), $config['editable_text_file_exts'])) {
                response(trans('Error_extension') . ' ' . sprintf(trans('Valid_Extensions'), implode(', ', $config['editable_text_file_exts'])) . AddErrorLocation(), 400)->send();
                exit;
            }

            $content = $_POST['new_content'];

            if ($ftp) {
                $temp = tempnam('/tmp', 'RF');
                file_put_contents($temp, $content);
                $ftp->put("/" . $path . $name, $temp, FTP_BINARY);
                unlink($temp);
                response(trans('File_Save_OK'))->send();
            } else {
                if (!checkresultingsize(strlen($content))) {
                    response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                    exit;
                }
                // file already exists
                if (file_exists($path . $name)) {
                    response(trans('Rename_existing_file') . AddErrorLocation())->send();
                    exit;
                }

                if (@file_put_contents($path . $name, $content) === false) {
                    response(trans('File_Save_Error') . AddErrorLocation())->send();
                    exit;
                }
                if (is_function_callable('chmod')) {
                    chmod($path . $name, 0644);
                }
                response(trans('File_Save_OK'))->send();
                exit;
            }

            break;

        case 'rename_file':
            if ($config['rename_files']) {
                $name = fix_filename($name, $config);
                if ($name !== '') {
                    if (!rename_file($path, $name, $ftp, $config)) {
                        response(trans('Rename_existing_file') . AddErrorLocation())->send();
                        exit;
                    }

                    rename_file($path_thumb, $name, $ftp, $config);

                    if ($config['fixed_image_creation']) {
                        $info = pathinfo($path);

                        foreach ($config['fixed_path_from_filemanager'] as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }

                            $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen($config['current_path']));
                            if (file_exists($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'])) {
                                rename_file($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'], $config['fixed_image_creation_name_to_prepend'][$k] . $name . $config['fixed_image_creation_to_append'][$k], $ftp, $config);
                            }
                        }
                    }
                } else {
                    response(trans('Empty_name') . AddErrorLocation())->send();
                    exit;
                }
            }
            break;

        case 'duplicate_file':
            if ($config['duplicate_files']) {
                $name = fix_filename($name, $config);
                if ($name !== '') {
                    if (!$ftp && !checkresultingsize(filesize($path))) {
                        response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                        exit;
                    }
                    if (!duplicate_file($path, $name, $ftp, $config)) {
                        response(trans('Rename_existing_file') . AddErrorLocation())->send();
                        exit;
                    }

                    duplicate_file($path_thumb, $name, $ftp, $config);

                    if (!$ftp && $config['fixed_image_creation']) {
                        $info = pathinfo($path);
                        foreach ($config['fixed_path_from_filemanager'] as $k => $paths) {
                            if ($paths != "" && $paths[strlen($paths) - 1] != "/") {
                                $paths .= "/";
                            }

                            $base_dir = $paths . substr_replace($info['dirname'] . "/", '', 0, strlen($config['current_path']));

                            if (file_exists($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'])) {
                                duplicate_file($base_dir . $config['fixed_image_creation_name_to_prepend'][$k] . $info['filename'] . $config['fixed_image_creation_to_append'][$k] . "." . $info['extension'], $config['fixed_image_creation_name_to_prepend'][$k] . $name . $config['fixed_image_creation_to_append'][$k]);
                            }
                        }
                    }
                } else {
                    response(trans('Empty_name') . AddErrorLocation())->send();
                    exit;
                }
            }
            break;

        case 'paste_clipboard':
            if (!isset($_SESSION['RF']['clipboard_action'], $_SESSION['RF']['clipboard']['path'])
                || $_SESSION['RF']['clipboard_action'] == ''
                || $_SESSION['RF']['clipboard']['path'] == '') {
                response()->send();
                exit;
            }

            $action = $_SESSION['RF']['clipboard_action'];
            $data = $_SESSION['RF']['clipboard'];


            if ($ftp) {
                if ($_POST['path'] != "") {
                    $path .= DIRECTORY_SEPARATOR;
                    $path_thumb .= DIRECTORY_SEPARATOR;
                }
                $path_thumb .= basename($data['path']);
                $path .= basename($data['path']);
                $data['path_thumb'] = DIRECTORY_SEPARATOR . $config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $data['path'];
                $data['path'] = DIRECTORY_SEPARATOR . $config['ftp_base_folder'] . $config['upload_dir'] . $data['path'];
            } else {
                $data['path_thumb'] = $config['thumbs_base_path'] . $data['path'];
                $data['path'] = $config['current_path'] . $data['path'];
            }

            $pinfo = pathinfo($data['path']);

            // user wants to paste to the same dir. nothing to do here...
            if ($pinfo['dirname'] == rtrim($path, DIRECTORY_SEPARATOR)) {
                response()->send();
                exit;
            }

            // user wants to paste folder to it's own sub folder.. baaaah.
            if (is_dir($data['path']) && strpos($path, $data['path']) !== false) {
                response()->send();
                exit;
            }

            // something terribly gone wrong
            if ($action != 'copy' && $action != 'cut') {
                response(trans('wrong action') . AddErrorLocation())->send();
                exit;
            }
            if ($ftp) {
                if ($action == 'copy') {
                    $tmp = time() . basename($data['path']);
                    $ftp->get($tmp, $data['path'], FTP_BINARY);
                    $ftp->put(DIRECTORY_SEPARATOR . $path, $tmp, FTP_BINARY);
                    unlink($tmp);

                    if (url_exists($data['path_thumb'])) {
                        $tmp = time() . basename($data['path_thumb']);
                        @$ftp->get($tmp, $data['path_thumb'], FTP_BINARY);
                        @$ftp->put(DIRECTORY_SEPARATOR . $path_thumb, $tmp, FTP_BINARY);
                        unlink($tmp);
                    }
                } elseif ($action == 'cut') {
                    $ftp->rename($data['path'], DIRECTORY_SEPARATOR . $path);
                    if (url_exists($data['path_thumb'])) {
                        @$ftp->rename($data['path_thumb'], DIRECTORY_SEPARATOR . $path_thumb);
                    }
                }
            } else {
                // check for writability
                if (is_really_writable($path) === false || is_really_writable($path_thumb) === false) {
                    response(trans('Dir_No_Write') . '<br/>' . str_replace('../', '', $path) . '<br/>' . str_replace('../', '', $path_thumb) . AddErrorLocation())->send();
                    exit;
                }

                // check if server disables copy or rename
                if (is_function_callable(($action == 'copy' ? 'copy' : 'rename')) === false) {
                    response(sprintf(trans('Function_Disabled'), ($action == 'copy' ? (trans('Copy')) : (trans('Cut')))) . AddErrorLocation())->send();
                    exit;
                }
                if ($action == 'copy') {
                    [$sizeFolderToCopy, $fileNum, $foldersCount] = folder_info($path, false);
                    if (!checkresultingsize($sizeFolderToCopy)) {
                        response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                        exit;
                    }
                    rcopy($data['path'], $path);
                    rcopy($data['path_thumb'], $path_thumb);
                } elseif ($action == 'cut') {
                    rrename($data['path'], $path);
                    rrename($data['path_thumb'], $path_thumb);

					// cleanup
					if (is_dir($data['path'])){
						rrename_after_cleaner($data['path']);
						rrename_after_cleaner($data['path_thumb']);
					}
				}
			}

            // cleanup
            $_SESSION['RF']['clipboard']['path'] = null;
            $_SESSION['RF']['clipboard_action'] = null;

            break;

        case 'chmod':
            $mode = $_POST['new_mode'];
            $rec_option = $_POST['is_recursive'];
            $valid_options = ['none', 'files', 'folders', 'both'];
            $chmod_perm = ($_POST['folder'] ? $config['chmod_dirs'] : $config['chmod_files']);

            // check perm
            if ($chmod_perm === false) {
                response(sprintf(trans('File_Permission_Not_Allowed'), (is_dir($path) ? (trans('Folders')) : (trans('Files')))) . AddErrorLocation())->send();
                exit;
            }
            // check mode
            if (!preg_match("/^[0-7]{3}$/", $mode)) {
                response(trans('File_Permission_Wrong_Mode') . AddErrorLocation())->send();
                exit;
            }
            // check recursive option
            if (!in_array($rec_option, $valid_options)) {
                response(trans("wrong option") . AddErrorLocation())->send();
                exit;
            }
            // check if server disabled chmod
            if (!$ftp && is_function_callable('chmod') === false) {
                response(sprintf(trans('Function_Disabled'), 'chmod') . AddErrorLocation())->send();
                exit;
            }

            $mode = "0" . $mode;
            $mode = octdec($mode);
            if ($ftp) {
                $ftp->chmod($mode, "/" . $path);
            } else {
                rchmod($path, $mode, $rec_option);
            }

            break;

        case 'save_text_file':
            $content = $_POST['new_content'];
            // $content = htmlspecialchars($content); not needed
            // $content = stripslashes($content);

            if ($ftp) {
                $tmp = time();
                file_put_contents($tmp, $content);
                $ftp->put("/" . $path, $tmp, FTP_BINARY);
                unlink($tmp);
                response(trans('File_Save_OK'))->send();
            } else {
                // no file
                if (!file_exists($path)) {
                    response(trans('File_Not_Found') . AddErrorLocation())->send();
                    exit;
                }

                // not writable or edit not allowed
                if (!is_writable($path) || $config['edit_text_files'] === false) {
                    response(sprintf(trans('File_Open_Edit_Not_Allowed'), strtolower(trans('Edit'))) . AddErrorLocation())->send();
                    exit;
                }

                if (!checkresultingsize(strlen($content))) {
                    response(sprintf(trans('max_size_reached'), $config['MaxSizeTotal']) . AddErrorLocation())->send();
                    exit;
                }
                if (@file_put_contents($path, $content) === false) {
                    response(trans('File_Save_Error') . AddErrorLocation())->send();
                    exit;
                }
                response(trans('File_Save_OK'))->send();
                exit;
            }

            break;

        default:
            response(trans('wrong action') . AddErrorLocation())->send();
            exit;
    }
}
