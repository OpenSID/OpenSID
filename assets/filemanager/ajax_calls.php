<?php

$config = include 'config/config.php';
//TODO switch to array
extract($config, EXTR_OVERWRITE);

require_once 'include/utils.php';

if ($_SESSION['RF']["verify"] != "RESPONSIVEfilemanager")
{
	response(trans('forbiden').AddErrorLocation())->send();
	exit;
}
$languages = include 'lang/languages.php';

if (isset($_SESSION['RF']['language']) && file_exists('lang/' . basename($_SESSION['RF']['language']) . '.php'))
{
	if(array_key_exists($_SESSION['RF']['language'],$languages)){
		include 'lang/' . basename($_SESSION['RF']['language']) . '.php';
	}else{
		response(trans('Lang_Not_Found').AddErrorLocation())->send();
		exit;
	}
} else {
	response(trans('Lang_Not_Found').AddErrorLocation())->send();
	exit;
}
$ftp = ftp_con($config);

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'new_file_form':
			echo trans('Filename') . ': <input type="text" id="create_text_file_name" style="height:30px"> <select id="create_text_file_extension" style="margin:0;width:100px;">';
			foreach($config['editable_text_file_exts'] as $ext){
				echo '<option value=".'.$ext.'">.'.$ext.'</option>';
			}
			echo '</select><br><hr><textarea id="textfile_create_area" style="width:100%;height:150px;"></textarea>';
		break;
		case 'view':
			if(isset($_GET['type']))
			{
				$_SESSION['RF']["view_type"] = $_GET['type'];
			}
			else
			{
				response(trans('view type number missing').AddErrorLocation())->send();
				exit;
			}
			break;
		case 'filter':
			if (isset($_GET['type']))
			{
				if (isset($remember_text_filter) && $remember_text_filter)
				{
					$_SESSION['RF']["filter"] = $_GET['type'];
				}
			}
			else {
				response(trans('view type number missing').AddErrorLocation())->send();
				exit;
			}
			break;
		case 'sort':
			if (isset($_GET['sort_by']))
			{
				$_SESSION['RF']["sort_by"] = $_GET['sort_by'];
			}

			if (isset($_GET['descending']))
			{
				$_SESSION['RF']["descending"] = $_GET['descending'];
			}
			break;
		case 'image_size': // not used
			$pos = strpos($_POST['path'], $upload_dir);
			if ($pos !== false)
			{
				$info = getimagesize(substr_replace($_POST['path'], $current_path, $pos, strlen($upload_dir)));
				response($info)->send();
				exit;
			}
			break;
		case 'save_img':
			$info = pathinfo($_POST['name']);

			if (
				strpos($_POST['path'], '/') === 0
				|| strpos($_POST['path'], '../') !== false
				|| strpos($_POST['path'], '..\\') !== false
				|| strpos($_POST['path'], './') === 0
				|| (strpos($_POST['url'], 'http://s3.amazonaws.com/feather') !== 0 && strpos($_POST['url'], 'https://s3.amazonaws.com/feather') !== 0)
				|| $_POST['name'] != fix_filename($_POST['name'], $config)
				|| ! in_array(strtolower($info['extension']), array( 'jpg', 'jpeg', 'png' ))
			)
			{
				response(trans('wrong data').AddErrorLocation())->send();
				exit;
			}
			$image_data = get_file_by_url($_POST['url']);
			if ($image_data === false)
			{
				response(trans('Aviary_No_Save').AddErrorLocation())->send();
				exit;
			}

			if (!checkresultingsize(strlen($image_data))) {
				response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
				exit;
			}
			if($ftp){

				$temp = tempnam('/tmp','RF');
				unlink($temp);
				$temp .=".".substr(strrchr($_POST['url'],'.'),1);
				file_put_contents($temp,$image_data);

				$ftp->put($ftp_base_folder.$upload_dir . $_POST['path'] . $_POST['name'], $temp, FTP_BINARY);

				create_img($temp,$temp,122,91);
				$ftp->put($ftp_base_folder.$ftp_thumbs_dir. $_POST['path'] . $_POST['name'], $temp, FTP_BINARY);

				unlink($temp);
			}else{

				file_put_contents($current_path . $_POST['path'] . $_POST['name'],$image_data);
				create_img($current_path . $_POST['path'] . $_POST['name'], $thumbs_base_path.$_POST['path'].$_POST['name'], 122, 91);
				// TODO something with this function cause its blowing my mind
				new_thumbnails_creation(
					$current_path.$_POST['path'],
					$current_path.$_POST['path'].$_POST['name'],
					$_POST['name'],
					$current_path,
					$relative_image_creation,
					$relative_path_from_current_pos,
					$relative_image_creation_name_to_prepend,
					$relative_image_creation_name_to_append,
					$relative_image_creation_width,
					$relative_image_creation_height,
					$relative_image_creation_option,
					$fixed_image_creation,
					$fixed_path_from_filemanager,
					$fixed_image_creation_name_to_prepend,
					$fixed_image_creation_to_append,
					$fixed_image_creation_width,
					$fixed_image_creation_height,
					$fixed_image_creation_option
				);
			}
			break;
		case 'extract':
			if (	strpos($_POST['path'], '/') === 0 
				|| strpos($_POST['path'], '../') !== false 
				|| strpos($_POST['path'], '..\\') !== false 
				|| strpos($_POST['path'], './') === 0)
			{
				response(trans('wrong path'.AddErrorLocation()))->send();
				exit;
			}

			if($ftp){
				$path = $ftp_base_url.$upload_dir . $_POST['path'];
				$base_folder = $ftp_base_url.$upload_dir . fix_dirname($_POST['path']) . "/";
			}else{
				$path = $current_path . $_POST['path'];
				$base_folder = $current_path . fix_dirname($_POST['path']) . "/";
			}

			$info = pathinfo($path);

			if($ftp){
				$tempDir = tempdir();
				$temp = tempnam($tempDir,'RF');
				unlink($temp);
				$temp .=".".$info['extension'];
				$handle = fopen($temp, "w");
				fwrite($handle, file_get_contents($path));
				fclose($handle);
				$path = $temp;
				$base_folder = $tempDir."/";
			}

			$info = pathinfo($path);

			switch ($info['extension'])
			{
				case "zip":
					$zip = new ZipArchive;
					if ($zip->open($path) === true)
					{
						//get total size
						$sizeTotalFinal = 0;
						for ($i = 0; $i < $zip->numFiles; $i++)
						{
							$aStat = $zip->statIndex($i);
							$sizeTotalFinal += $aStat['size'];
						}
						if (!checkresultingsize($sizeTotalFinal)) {
							response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
							exit;
						}

						//make all the folders
						for ($i = 0; $i < $zip->numFiles; $i++)
						{
							$OnlyFileName = $zip->getNameIndex($i);
							$FullFileName = $zip->statIndex($i);
							if (substr($FullFileName['name'], -1, 1) == "/")
							{
								create_folder($base_folder . $FullFileName['name']);
							}
						}
						//unzip into the folders
						for ($i = 0; $i < $zip->numFiles; $i++)
						{
							$OnlyFileName = $zip->getNameIndex($i);
							$FullFileName = $zip->statIndex($i);

							if ( ! (substr($FullFileName['name'], -1, 1) == "/"))
							{
								$fileinfo = pathinfo($OnlyFileName);
								if (in_array(strtolower($fileinfo['extension']), $ext))
								{
									copy('zip://' . $path . '#' . $OnlyFileName, $base_folder . $FullFileName['name']);
								}
							}
						}
						$zip->close();
					} else {
						response(trans('Zip_No_Extract').AddErrorLocation())->send();
						exit;
					}

					break;

				case "gz":
					// No resulting size pre-control available
					$p = new PharData($path);
					$p->decompress(); // creates files.tar

					break;

				case "tar":
					// No resulting size pre-control available
					// unarchive from the tar
					$phar = new PharData($path);
					$phar->decompressFiles();
					$files = array();
					check_files_extensions_on_phar($phar, $files, '', $ext);
					$phar->extractTo($base_folder, $files, true);

					break;

				default:
					response(trans('Zip_Invalid').AddErrorLocation())->send();
					exit;
			}

			if($ftp){
				unlink($path);
				$ftp->putAll($base_folder, "/".$ftp_base_folder . $upload_dir . fix_dirname($_POST['path']), FTP_BINARY);
				deleteDir($base_folder);
			}


			break;
		case 'media_preview':
			if($ftp){
				$preview_file = $ftp_base_url.$upload_dir . $_GET['file'];
			}else{
				$preview_file = $current_path . $_GET["file"];
			}
			$info = pathinfo($preview_file);
			ob_start();
			?>
			<div id="jp_container_1" class="jp-video " style="margin:0 auto;">
				<div class="jp-type-single">
				<div id="jquery_jplayer_1" class="jp-jplayer"></div>
				<div class="jp-gui">
					<div class="jp-video-play">
					<a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
					</div>
					<div class="jp-interface">
					<div class="jp-progress">
						<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
					<div class="jp-controls-holder">
						<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
						</ul>
						<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
						</div>
						<ul class="jp-toggles">
						<li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>
						<li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>
						<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
						<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
						</ul>
					</div>
					<div class="jp-title" style="display:none;">
						<ul>
						<li></li>
						</ul>
					</div>
					</div>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="https://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
				</div>
			</div>
			<?php if(in_array(strtolower($info['extension']), $ext_music)): ?>

				<script type="text/javascript">
					$(document).ready(function(){

					$("#jquery_jplayer_1").jPlayer({
						ready: function () {
						$(this).jPlayer("setMedia", {
						title:"<?php $_GET['title']; ?>",
							mp3: "<?php echo $preview_file; ?>",
							m4a: "<?php echo $preview_file; ?>",
						oga: "<?php echo $preview_file; ?>",
							wav: "<?php echo $preview_file; ?>"
						});
						},
						swfPath: "js",
					solution:"html,flash",
						supplied: "mp3, m4a, midi, mid, oga,webma, ogg, wav",
					smoothPlayBar: true,
					keyEnabled: false
					});
					});
				</script>

			<?php elseif(in_array(strtolower($info['extension']), $ext_video)):	?>

				<script type="text/javascript">
				$(document).ready(function(){

				$("#jquery_jplayer_1").jPlayer({
					ready: function () {
					$(this).jPlayer("setMedia", {
						title:"<?php $_GET['title']; ?>",
						m4v: "<?php echo $preview_file; ?>",
						ogv: "<?php echo $preview_file; ?>",
						flv: "<?php echo $preview_file; ?>"
					});
					},
					swfPath: "js",
					solution:"html,flash",
					supplied: "mp4, m4v, ogv, flv, webmv, webm",
					smoothPlayBar: true,
					keyEnabled: false
				});

				});
			</script>

			<?php endif;

			$content = ob_get_clean();

			response($content)->send();
			exit;

			break;
		case 'copy_cut':
			if ($_POST['sub_action'] != 'copy' && $_POST['sub_action'] != 'cut')
			{
				response(trans('wrong sub-action').AddErrorLocation())->send();
				exit;
			}

			if (strpos($_POST['path'],'../') !== FALSE
				|| strpos($_POST['path'],'./') !== FALSE 
				|| strpos($_POST['path'],'..\\') !== FALSE
				|| strpos($_POST['path'],'.\\') !== FALSE )
			{
				response(trans('wrong path'.AddErrorLocation()))->send();
				exit;
			}

			if (trim($_POST['path']) == '')
			{
				response(trans('no path').AddErrorLocation())->send();
				exit;
			}

			$msg_sub_action = ($_POST['sub_action'] == 'copy' ? trans('Copy') : trans('Cut'));
			$path = $current_path . $_POST['path'];

			if (is_dir($path))
			{
				// can't copy/cut dirs
				if ($copy_cut_dirs === false)
				{
					response(sprintf(trans('Copy_Cut_Not_Allowed'), $msg_sub_action, trans('Folders')).AddErrorLocation())->send();
					exit;
				}

				list($sizeFolderToCopy,$fileNum,$foldersCount) = folder_info($path,false);
				// size over limit
				if ($copy_cut_max_size !== false && is_int($copy_cut_max_size)) {
					if (($copy_cut_max_size * 1024 * 1024) < $sizeFolderToCopy) {
						response(sprintf(trans('Copy_Cut_Size_Limit'), $msg_sub_action, $copy_cut_max_size).AddErrorLocation())->send();
						exit;
					}
				}

				// file count over limit
				if ($copy_cut_max_count !== false && is_int($copy_cut_max_count))
				{
					if ($copy_cut_max_count < $fileNum)
					{
						response(sprintf(trans('Copy_Cut_Count_Limit'), $msg_sub_action, $copy_cut_max_count).AddErrorLocation())->send();
						exit;
					}
				}

				if (!checkresultingsize($sizeFolderToCopy)) {
					response(sprintf(trans('max_size_reached'),$MaxSizeTotal).AddErrorLocation())->send();
					exit;
				}
			} else {
				// can't copy/cut files
				if ($copy_cut_files === false)
				{
					response(sprintf(trans('Copy_Cut_Not_Allowed'), $msg_sub_action, trans('Files')).AddErrorLocation())->send();
					exit;
				}
			}

			$_SESSION['RF']['clipboard']['path'] = $_POST['path'];
			$_SESSION['RF']['clipboard_action'] = $_POST['sub_action'];
			break;
		case 'clear_clipboard':
			$_SESSION['RF']['clipboard'] = null;
			$_SESSION['RF']['clipboard_action'] = null;
			break;
		case 'chmod':
			if($ftp){
				$path = $ftp_base_url . $upload_dir . $_POST['path'];
				if (
					($_POST['folder']==1 && $chmod_dirs === false)
					|| ($_POST['folder']==0 && $chmod_files === false)
					|| (is_function_callable("chmod") === false) )
				{
					response(sprintf(trans('File_Permission_Not_Allowed'), (is_dir($path) ? trans('Folders') : trans('Files')), 403).AddErrorLocation())->send();
					exit;
				}
				$info = $_POST['permissions'];
			}else{
				$path = $current_path . $_POST['path'];
				if (
					(is_dir($path) && $chmod_dirs === false)
					|| (is_file($path) && $chmod_files === false)
					|| (is_function_callable("chmod") === false) )
				{
					response(sprintf(trans('File_Permission_Not_Allowed'), (is_dir($path) ? trans('Folders') : trans('Files')), 403).AddErrorLocation())->send();
					exit;
				}

				$perms = fileperms($path) & 0777;

			    $info = '-';

				// Owner
				$info .= (($perms & 0x0100) ? 'r' : '-');
				$info .= (($perms & 0x0080) ? 'w' : '-');
				$info .= (($perms & 0x0040) ?
				            (($perms & 0x0800) ? 's' : 'x' ) :
				            (($perms & 0x0800) ? 'S' : '-'));

				// Group
				$info .= (($perms & 0x0020) ? 'r' : '-');
				$info .= (($perms & 0x0010) ? 'w' : '-');
				$info .= (($perms & 0x0008) ?
				            (($perms & 0x0400) ? 's' : 'x' ) :
				            (($perms & 0x0400) ? 'S' : '-'));

				// World
				$info .= (($perms & 0x0004) ? 'r' : '-');
				$info .= (($perms & 0x0002) ? 'w' : '-');
				$info .= (($perms & 0x0001) ?
				            (($perms & 0x0200) ? 't' : 'x' ) :
				            (($perms & 0x0200) ? 'T' : '-'));

			}


			$ret = '<div id="files_permission_start">
			<form id="chmod_form">
				<table class="table file-perms-table">
					<thead>
						<tr>
							<td></td>
							<td>r&nbsp;&nbsp;</td>
							<td>w&nbsp;&nbsp;</td>
							<td>x&nbsp;&nbsp;</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>'.trans('User').'</td>
							<td><input id="u_4" type="checkbox" data-value="4" data-group="user" '.(substr($info, 1,1)=='r' ? " checked" : "").'></td>
							<td><input id="u_2" type="checkbox" data-value="2" data-group="user" '.(substr($info, 2,1)=='w' ? " checked" : "").'></td>
							<td><input id="u_1" type="checkbox" data-value="1" data-group="user" '.(substr($info, 3,1)=='x' ? " checked" : "").'></td>
						</tr>
						<tr>
							<td>'.trans('Group').'</td>
							<td><input id="g_4" type="checkbox" data-value="4" data-group="group" '.(substr($info, 4,1)=='r' ? " checked" : "").'></td>
							<td><input id="g_2" type="checkbox" data-value="2" data-group="group" '.(substr($info, 5,1)=='w' ? " checked" : "").'></td>
							<td><input id="g_1" type="checkbox" data-value="1" data-group="group" '.(substr($info, 6,1)=='x' ? " checked" : "").'></td>
						</tr>
						<tr>
							<td>'.trans('All').'</td>
							<td><input id="a_4" type="checkbox" data-value="4" data-group="all" '.(substr($info, 7,1)=='r' ? " checked" : "").'></td>
							<td><input id="a_2" type="checkbox" data-value="2" data-group="all" '.(substr($info, 8,1)=='w' ? " checked" : "").'></td>
							<td><input id="a_1" type="checkbox" data-value="1" data-group="all" '.(substr($info, 9,1)=='x' ? " checked" : "").'></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3"><input type="text" class="input-block-level" name="chmod_value" id="chmod_value" value="" data-def-value=""></td>
						</tr>
					</tbody>
				</table>';

			if ((!$ftp && is_dir($path)) )
			{
				$ret .= '<div class="hero-unit" style="padding:10px;">'.trans('File_Permission_Recursive').'<br/><br/>
						<ul class="unstyled">
							<li><label class="radio"><input value="none" name="apply_recursive" type="radio" checked> '.trans('No').'</label></li>
							<li><label class="radio"><input value="files" name="apply_recursive" type="radio"> '.trans('Files').'</label></li>
							<li><label class="radio"><input value="folders" name="apply_recursive" type="radio"> '.trans('Folders').'</label></li>
							<li><label class="radio"><input value="both" name="apply_recursive" type="radio"> '.trans('Files').' & '.trans('Folders').'</label></li>
						</ul>
						</div>';
			}

			$ret .= '</form></div>';

			response($ret)->send();
			exit;

			break;
		case 'get_lang':
			if ( ! file_exists('lang/languages.php'))
			{
				response(trans('Lang_Not_Found').AddErrorLocation())->send();
				exit;
			}

			$languages = include 'lang/languages.php';
			if ( ! isset($languages) || ! is_array($languages))
			{
				response(trans('Lang_Not_Found').AddErrorLocation())->send();
				exit;
			}

			$curr = $_SESSION['RF']['language'];

			$ret = '<select id="new_lang_select">';
			foreach ($languages as $code => $name)
			{
				$ret .= '<option value="' . $code . '"' . ($code == $curr ? ' selected' : '') . '>' . $name . '</option>';
			}
			$ret .= '</select>';

			response($ret)->send();
			exit;

			break;
		case 'change_lang':
			$choosen_lang = (!empty($_POST['choosen_lang']))? $_POST['choosen_lang']:"en_EN";

			if(array_key_exists($choosen_lang,$languages)){
				if ( ! file_exists('lang/' . $choosen_lang . '.php'))
				{
					response(trans('Lang_Not_Found').AddErrorLocation())->send();
					exit;
				}else{
					$_SESSION['RF']['language'] = $choosen_lang;
				}
			}

			break;
		case 'cad_preview':
			if($ftp){
				$selected_file = $ftp_base_url.$upload_dir . $_GET['file'];
			}else{
				$selected_file = $current_path . $_GET['file'];

				if ( ! file_exists($selected_file))
				{
					response(trans('File_Not_Found').AddErrorLocation())->send();
					exit;
				}
			}
			if($ftp){
				$url_file = $selected_file;
			}else{
				$url_file = $base_url . $upload_dir . str_replace($current_path, '', $_GET["file"]);
			}

			$cad_url = urlencode($url_file);
			$cad_html = "<iframe src=\"//sharecad.org/cadframe/load?url=" . $url_file . "\" class=\"google-iframe\" scrolling=\"no\"></iframe>";
			$ret = $cad_html;
			response($ret)->send();
			break;
		case 'get_file': // preview or edit
			$sub_action = $_GET['sub_action'];
			$preview_mode = $_GET["preview_mode"];

			if ($sub_action != 'preview' && $sub_action != 'edit')
			{
				response(trans('wrong action').AddErrorLocation())->send();
				exit;
			}

			if($ftp){
				$selected_file = ($sub_action == 'preview' ? $ftp_base_url.$upload_dir . $_GET['file'] : $ftp_base_url.$upload_dir . $_POST['path']);
			}else{
				$selected_file = ($sub_action == 'preview' ? $current_path . $_GET['file'] : $current_path . $_POST['path']);

				if ( ! file_exists($selected_file))
				{
					response(trans('File_Not_Found').AddErrorLocation())->send();
					exit;
				}
			}

			$info = pathinfo($selected_file);

			if ($preview_mode == 'text')
			{
				$is_allowed = ($sub_action == 'preview' ? $preview_text_files : $edit_text_files);
				$allowed_file_exts = ($sub_action == 'preview' ? $previewable_text_file_exts : $editable_text_file_exts);
			} elseif ($preview_mode == 'viewerjs') {
				$is_allowed = $viewerjs_enabled;
				$allowed_file_exts = $viewerjs_file_exts;
			} elseif ($preview_mode == 'google') {
				$is_allowed = $googledoc_enabled;
				$allowed_file_exts = $googledoc_file_exts;
			}

			if ( ! isset($allowed_file_exts) || ! is_array($allowed_file_exts))
			{
				$allowed_file_exts = array();
			}

			if ( ! in_array($info['extension'], $allowed_file_exts)
				|| ! isset($is_allowed)
				|| $is_allowed === false
				|| (!$ftp && ! is_readable($selected_file))
			)
			{
				response(sprintf(trans('File_Open_Edit_Not_Allowed'), ($sub_action == 'preview' ? strtolower(trans('Open')) : strtolower(trans('Edit')))).AddErrorLocation())->send();
				exit;
			}
			if ($sub_action == 'preview')
			{
				if ($preview_mode == 'text')
				{
					// get and sanities
					$data = file_get_contents($selected_file);
					$data = htmlspecialchars(htmlspecialchars_decode($data));
					$ret = '';

					if ( ! in_array($info['extension'],$previewable_text_file_exts_no_prettify))
					{
						$ret .= '<script src="https://rawgit.com/google/code-prettify/master/loader/run_prettify.js?autoload=true&skin=sunburst"></script>';
						$ret .= '<?prettify lang='.$info['extension'].' linenums=true?><pre class="prettyprint"><code class="language-'.$info['extension'].'">'.$data.'</code></pre>';
					} else {
						$ret .= '<pre class="no-prettify">'.$data.'</pre>';
					}

				}
				elseif ($preview_mode == 'google' || $preview_mode == 'viewerjs') {
					if($ftp){
						$url_file = $selected_file;
					}else{
						$url_file = $base_url . $upload_dir . str_replace($current_path, '', $_GET["file"]);
					}

					$googledoc_url = urlencode($url_file);
					$googledoc_html = "<iframe src=\"https://docs.google.com/viewer?url=" . $url_file . "&embedded=true\" class=\"google-iframe\"></iframe>";
					$ret = $googledoc_html;
				}
			} else {
				$data = stripslashes(htmlspecialchars(file_get_contents($selected_file)));
				$ret = '<textarea id="textfile_edit_area" style="width:100%;height:300px;">'.$data.'</textarea>';
			}

			response($ret)->send();
			exit;

			break;
		default:
			response(trans('no action passed').AddErrorLocation())->send();
			exit;
	}
} else {
	response(trans('no action passed').AddErrorLocation())->send();
	exit;
}
?>