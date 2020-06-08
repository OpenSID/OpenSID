<?php
$time = time();

$config = include 'config/config.php';

if (USE_ACCESS_KEYS == true){
	if (!isset($_GET['akey'], $config['access_keys']) || empty($config['access_keys'])){
		die('Access Denied!');
	}

    $_GET['akey'] = strip_tags(preg_replace("/[^a-zA-Z0-9\._-]/", '', $_GET['akey']));

	if (!in_array($_GET['akey'], $config['access_keys'])){
		die('Access Denied!');
	}
}

$_SESSION['RF']["verify"] = "RESPONSIVEfilemanager";

if (isset($_POST['submit'])) {
    include 'upload.php';
} else {
    $available_languages = include 'lang/languages.php';

    list($preferred_language) = array_values(array_filter(array(
        isset($_GET['lang']) ? $_GET['lang'] : null,
        isset($_SESSION['RF']['language']) ? $_SESSION['RF']['language'] : null,
        $config['default_language']
    )));

    if (array_key_exists($preferred_language, $available_languages)) {
        $_SESSION['RF']['language'] = $preferred_language;
    } else {
        $_SESSION['RF']['language'] = $config['default_language'];
    }
}

include 'include/utils.php';

$subdir_path = '';

if (isset($_GET['fldr']) && !empty($_GET['fldr'])) {
    $subdir_path = rawurldecode(trim(strip_tags($_GET['fldr']), "/"));
} elseif (isset($_SESSION['RF']['fldr']) && !empty($_SESSION['RF']['fldr'])) {
    $subdir_path = rawurldecode(trim(strip_tags($_SESSION['RF']['fldr']), "/"));
}

if (checkRelativePath($subdir_path)) {
    $subdir = strip_tags($subdir_path) . "/";
    $_SESSION['RF']['fldr'] = $subdir_path;
    $_SESSION['RF']["filter"] = '';
} else {
    $subdir = '';
}

if ($subdir == "") {
    if (!empty($_COOKIE['last_position']) && strpos($_COOKIE['last_position'], '.') === FALSE) {
        $subdir = trim($_COOKIE['last_position']);
    }
}
//remember last position
setcookie('last_position', $subdir, time() + (86400 * 7));

if ($subdir == "/") { $subdir = ""; }

// If hidden folders are specified
if (count($config['hidden_folders'])) {
    // If hidden folder appears in the path specified in URL parameter "fldr"
    $dirs = explode('/', $subdir);
    foreach ($dirs as $dir) {
        if ($dir !== '' && in_array($dir, $config['hidden_folders'])) {
            // Ignore the path
            $subdir = "";
            break;
        }
    }
}

if ($config['show_total_size']) {
    list($sizeCurrentFolder, $fileCurrentNum, $foldersCurrentCount) = folder_info($config['current_path'], false);
}

/***
 * SUB-DIR CODE
 ***/
if (!isset($_SESSION['RF']["subfolder"])) {
    $_SESSION['RF']["subfolder"] = '';
}
$rfm_subfolder = '';

if (!empty($_SESSION['RF']["subfolder"])
    && strpos($_SESSION['RF']["subfolder"], "/") !== 0
    && strpos($_SESSION['RF']["subfolder"], '.') === FALSE
) {
    $rfm_subfolder = $_SESSION['RF']['subfolder'];
}

if ($rfm_subfolder != "" && $rfm_subfolder[strlen($rfm_subfolder) - 1] != "/") {
    $rfm_subfolder .= "/";
}

$ftp = ftp_con($config);

if (($ftp && !$ftp->isDir($config['ftp_base_folder'] . $config['upload_dir'] . $rfm_subfolder . $subdir)) || (!$ftp && !file_exists($config['current_path'] . $rfm_subfolder . $subdir))) {
    $subdir = '';
    $rfm_subfolder = "";
}


$cur_dir		= $config['upload_dir'].$rfm_subfolder.$subdir;
$cur_dir_thumb	= $config['thumbs_upload_dir'].$rfm_subfolder.$subdir;
$thumbs_path	= $config['thumbs_base_path'].$rfm_subfolder.$subdir;
$parent			= $rfm_subfolder.$subdir;

if ($ftp) {
    $cur_dir = $config['ftp_base_folder'] . $cur_dir;
    $cur_dir_thumb = $config['ftp_base_folder'] . $cur_dir_thumb;
    $thumbs_path = str_replace(array('/..', '..'), '', $cur_dir_thumb);
    $parent = $config['ftp_base_folder'] . $parent;
}

if (!$ftp) {
    $cycle = TRUE;
    $max_cycles = 50;
    $i = 0;
    while ($cycle && $i < $max_cycles) {
        $i++;

        if ($parent == "./") {
            $parent = "";
        }

        if (file_exists($config['current_path'] . $parent . "config.php")) {
            $configTemp = include $config['current_path'] . $parent . 'config.php';
            $config = array_merge($config, $configTemp);
            $cycle = FALSE;
        }

        if ($parent == "") {
            $cycle = FALSE;
        } else {
            $parent = fix_dirname($parent) . "/";
        }
    }

    if (!is_dir($thumbs_path)) {
        create_folder(FALSE, $thumbs_path, $ftp, $config);
    }
}

$multiple = null;

if (isset($_GET['multiple'])) {
    if ($_GET['multiple'] == 1) {
        $multiple = 1;
        $config['multiple_selection'] = true;
        $config['multiple_selection_action_button'] = true;
    } elseif ($_GET['multiple'] == 0) {
        $multiple = 0;
        $config['multiple_selection'] = false;
        $config['multiple_selection_action_button'] = false;
    }
}

if (isset($_GET['callback'])) {
    $callback = strip_tags($_GET['callback']);
    $_SESSION['RF']["callback"] = $callback;
} else {
    $callback = 0;

    if (isset($_SESSION['RF']["callback"])) {
        $callback = $_SESSION['RF']["callback"];
    }
}

$popup = isset($_GET['popup']) ? strip_tags($_GET['popup']) : 0;
//Sanitize popup
$popup = !!$popup;

$crossdomain = isset($_GET['crossdomain']) ? strip_tags($_GET['crossdomain']) : 0;
//Sanitize crossdomain
$crossdomain=!!$crossdomain;

//view type
if(!isset($_SESSION['RF']["view_type"]))
{
    $view = $config['default_view'];
    $_SESSION['RF']["view_type"] = $view;
}

if (isset($_GET['view']))
{
    $view = fix_get_params($_GET['view']);
    $_SESSION['RF']["view_type"] = $view;
}

$view = $_SESSION['RF']["view_type"];

//filter
$filter = "";
if(isset($_SESSION['RF']["filter"]))
{
    $filter = $_SESSION['RF']["filter"];
}

if(isset($_GET["filter"]))
{
    $filter = fix_get_params($_GET["filter"]);
}

if (!isset($_SESSION['RF']['sort_by']))
{
    $_SESSION['RF']['sort_by'] = 'name';
}

if (isset($_GET["sort_by"])) {
    $sort_by = $_SESSION['RF']['sort_by'] = fix_get_params($_GET["sort_by"]);
} else {
    $sort_by = $_SESSION['RF']['sort_by'];
}


if (!isset($_SESSION['RF']['descending'])) {
    $_SESSION['RF']['descending'] = TRUE;
}

if (isset($_GET["descending"])) {
    $descending = $_SESSION['RF']['descending'] = fix_get_params($_GET["descending"]) == 1;
} else {
    $descending = $_SESSION['RF']['descending'];
}

$boolarray = array(false => 'false', true => 'true');

$return_relative_url = isset($_GET['relative_url']) && $_GET['relative_url'] == "1" ? true : false;

if (!isset($_GET['type'])) {
    $_GET['type'] = 0;
}

$extensions = null;
if (isset($_GET['extensions'])) {
    $extensions = json_decode(urldecode($_GET['extensions']));
    $ext_tmp = array();
    foreach ($extensions as $extension) {
        $extension = fix_strtolower($extension);
        if (check_file_extension($extension, $config)) {
            $ext_tmp[] = $extension;
        }
    }
    if ($extensions) {
        $ext = $ext_tmp;
        $config['ext'] = $ext_tmp;
        $config['show_filter_buttons'] = false;
    }
}

if (isset($_GET['editor'])) {
    $editor = strip_tags($_GET['editor']);
} else {
    $editor = $_GET['type'] == 0 ? null : 'tinymce';
}

$field_id = isset($_GET['field_id']) ? fix_get_params($_GET['field_id']) : null;
$type_param = fix_get_params($_GET['type']);
$apply = null;

if ($multiple) {
    $apply = 'apply_multiple';
}

if ($type_param == 1) {
    $apply_type = 'apply_img';
} elseif ($type_param == 2) {
    $apply_type = 'apply_link';
} elseif ($type_param == 0 && !$field_id) {
    $apply_type = 'apply_none';
} elseif ($type_param == 3) {
    $apply_type = 'apply_video';
} else {
    $apply_type = 'apply';
}

if(!$apply){
    $apply = $apply_type;
}

$get_params = array(
    'editor'        => $editor,
    'type'          => $type_param,
    'lang'          => $lang,
    'popup'         => $popup,
    'crossdomain'   => $crossdomain,
    'extensions'    => ($extensions) ? urlencode(json_encode($extensions)) : null ,
    'field_id'      => $field_id,
    'multiple'      => $multiple,
    'relative_url'  => $return_relative_url,
    'akey'          => (isset($_GET['akey']) && $_GET['akey'] != '' ? $_GET['akey'] : 'key')
);
if (isset($_GET['CKEditorFuncNum'])) {
    $get_params['CKEditorFuncNum'] = $_GET['CKEditorFuncNum'];
    $get_params['CKEditor'] = (isset($_GET['CKEditor']) ? $_GET['CKEditor'] : '');
}
$get_params['fldr'] ='';

$get_params = http_build_query($get_params);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="robots" content="noindex,nofollow">
        <title>Responsive FileManager</title>
        <link rel="shortcut icon" href="img/ico/favicon.ico">
        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        <link rel="stylesheet" href="css/jquery.fileupload.css">
        <link rel="stylesheet" href="css/jquery.fileupload-ui.css">
        <!-- CSS adjustments for browsers with JavaScript disabled -->
        <noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
        <noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jplayer/2.7.1/skin/blue.monday/jplayer.blue.monday.min.css" />
        <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css">
        <link href="css/style.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css" />
        <!--[if lt IE 8]>
        <style>
            .img-container span, .img-container-mini span {
                display: inline-block;
                height: 100%;
            }
        </style>
        <![endif]-->

        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script src="js/plugins.js?v=<?php echo $version; ?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jplayer/2.9.2/jplayer/jquery.jplayer.min.js"></script>
        <link type="text/css" href="https://uicdn.toast.com/tui-color-picker/v2.2.0/tui-color-picker.css" rel="stylesheet">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.js"></script>
        <script type="text/javascript" src="https://uicdn.toast.com/tui.code-snippet/v1.5.0/tui-code-snippet.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
        <script type="text/javascript" src="https://uicdn.toast.com/tui-color-picker/v2.2.0/tui-color-picker.js"></script>
        <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script>
        <script src="js/modernizr.custom.js"></script>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
        <![endif]-->

        <script type="text/javascript">
            var ext_img=new Array('<?php echo implode("','", $config['ext_img'])?>');
            var image_editor= <?php echo $config['tui_active']?"true":"false";?>;
        </script>

        
        <script src="js/include.js?v=<?php echo $version; ?>"></script>
</head>
<body>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-load-image/2.18.0/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="js/jquery.fileupload-process.js"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="js/jquery.fileupload-image.js"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="js/jquery.fileupload-audio.js"></script>
    <!-- The File Upload video preview plugin -->
    <script src="js/jquery.fileupload-video.js"></script>
    <!-- The File Upload validation plugin -->
    <script src="js/jquery.fileupload-validate.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="js/jquery.fileupload-ui.js"></script>

    <input type="hidden" id="ftp" value="<?php echo !!$ftp; ?>" />
    <input type="hidden" id="popup" value="<?php echo $popup;?>" />
    <input type="hidden" id="callback" value="<?php echo $callback; ?>" />
    <input type="hidden" id="crossdomain" value="<?php echo $crossdomain;?>" />
    <input type="hidden" id="editor" value="<?php echo $editor;?>" />
    <input type="hidden" id="view" value="<?php echo $view;?>" />
    <input type="hidden" id="subdir" value="<?php echo $subdir;?>" />
    <input type="hidden" id="field_id" value="<?php echo $field_id;?>" />
    <input type="hidden" id="multiple" value="<?php echo $multiple;?>" />
    <input type="hidden" id="type_param" value="<?php echo $type_param;?>" />
    <input type="hidden" id="upload_dir" value="<?php echo $config['upload_dir'];?>" />
    <input type="hidden" id="cur_dir" value="<?php echo $cur_dir;?>" />
    <input type="hidden" id="cur_dir_thumb" value="<?php echo $cur_dir_thumb;?>" />
    <input type="hidden" id="insert_folder_name" value="<?php echo trans('Insert_Folder_Name');?>" />
    <input type="hidden" id="rename_existing_folder" value="<?php echo trans('Rename_existing_folder');?>" />
    <input type="hidden" id="new_folder" value="<?php echo trans('New_Folder');?>" />
    <input type="hidden" id="ok" value="<?php echo trans('OK');?>" />
    <input type="hidden" id="cancel" value="<?php echo trans('Cancel');?>" />
    <input type="hidden" id="rename" value="<?php echo trans('Rename');?>" />
    <input type="hidden" id="lang_duplicate" value="<?php echo trans('Duplicate');?>" />
    <input type="hidden" id="duplicate" value="<?php if($config['duplicate_files']) echo 1; else echo 0;?>" />
    <input type="hidden" id="base_url" value="<?php echo $config['base_url']?>"/>
    <input type="hidden" id="ftp_base_url" value="<?php echo $config['ftp_base_url']?>"/>
    <input type="hidden" id="fldr_value" value="<?php echo $subdir;?>"/>
    <input type="hidden" id="sub_folder" value="<?php echo $rfm_subfolder;?>"/>
    <input type="hidden" id="return_relative_url" value="<?php echo $return_relative_url == true ? 1 : 0;?>"/>
    <input type="hidden" id="file_number_limit_js" value="<?php echo $config['file_number_limit_js'];?>" />
    <input type="hidden" id="sort_by" value="<?php echo $sort_by;?>" />
    <input type="hidden" id="descending" value="<?php echo $descending?1:0;?>" />
    <input type="hidden" id="current_url" value="<?php echo str_replace(array('&filter='.$filter,'&sort_by='.$sort_by,'&descending='.intval($descending)),array(''),$config['base_url'].htmlspecialchars($_SERVER['REQUEST_URI']));?>" />
    <input type="hidden" id="lang_show_url" value="<?php echo trans('Show_url');?>" />
    <input type="hidden" id="copy_cut_files_allowed" value="<?php if($config['copy_cut_files']) echo 1; else echo 0;?>" />
    <input type="hidden" id="copy_cut_dirs_allowed" value="<?php if($config['copy_cut_dirs']) echo 1; else echo 0;?>" />
    <input type="hidden" id="copy_cut_max_size" value="<?php echo $config['copy_cut_max_size'];?>" />
    <input type="hidden" id="copy_cut_max_count" value="<?php echo $config['copy_cut_max_count'];?>" />
    <input type="hidden" id="lang_copy" value="<?php echo trans('Copy');?>" />
    <input type="hidden" id="lang_cut" value="<?php echo trans('Cut');?>" />
    <input type="hidden" id="lang_paste" value="<?php echo trans('Paste');?>" />
    <input type="hidden" id="lang_paste_here" value="<?php echo trans('Paste_Here');?>" />
    <input type="hidden" id="lang_paste_confirm" value="<?php echo trans('Paste_Confirm');?>" />
    <input type="hidden" id="lang_files" value="<?php echo trans('Files');?>" />
    <input type="hidden" id="lang_folders" value="<?php echo trans('Folders');?>" />
    <input type="hidden" id="lang_files_on_clipboard" value="<?php echo trans('Files_ON_Clipboard');?>" />
    <input type="hidden" id="clipboard" value="<?php echo ((isset($_SESSION['RF']['clipboard']['path']) && trim($_SESSION['RF']['clipboard']['path']) != null) ? 1 : 0);?>" />
    <input type="hidden" id="lang_clear_clipboard_confirm" value="<?php echo trans('Clear_Clipboard_Confirm');?>" />
    <input type="hidden" id="lang_file_permission" value="<?php echo trans('File_Permission');?>" />
    <input type="hidden" id="chmod_files_allowed" value="<?php if($config['chmod_files']) echo 1; else echo 0;?>" />
    <input type="hidden" id="chmod_dirs_allowed" value="<?php if($config['chmod_dirs']) echo 1; else echo 0;?>" />
    <input type="hidden" id="lang_lang_change" value="<?php echo trans('Lang_Change');?>" />
    <input type="hidden" id="edit_text_files_allowed" value="<?php if($config['edit_text_files']) echo 1; else echo 0;?>" />
    <input type="hidden" id="lang_edit_file" value="<?php echo trans('Edit_File');?>" />
    <input type="hidden" id="lang_new_file" value="<?php echo trans('New_File');?>" />
    <input type="hidden" id="lang_filename" value="<?php echo trans('Filename');?>" />
    <input type="hidden" id="lang_file_info" value="<?php echo fix_strtoupper(trans('File_info'));?>" />
    <input type="hidden" id="lang_edit_image" value="<?php echo trans('Edit_image');?>" />
    <input type="hidden" id="lang_error_upload" value="<?php echo trans('Error_Upload');?>" />
    <input type="hidden" id="lang_select" value="<?php echo trans('Select');?>" />
    <input type="hidden" id="lang_extract" value="<?php echo trans('Extract');?>" />
    <input type="hidden" id="extract_files" value="<?php if($config['extract_files']) echo 1; else echo 0;?>" />
    <input type="hidden" id="transliteration" value="<?php echo $config['transliteration']?"true":"false";?>" />
    <input type="hidden" id="convert_spaces" value="<?php echo $config['convert_spaces']?"true":"false";?>" />
    <input type="hidden" id="replace_with" value="<?php echo $config['convert_spaces']? $config['replace_with'] : "";?>" />
    <input type="hidden" id="lower_case" value="<?php echo $config['lower_case']?"true":"false";?>" />
    <input type="hidden" id="show_folder_size" value="<?php echo $config['show_folder_size'];?>" />
    <input type="hidden" id="add_time_to_img" value="<?php echo $config['add_time_to_img'];?>" />
<?php if($config['upload_files']){ ?>
<!-- uploader div start -->
<div class="uploader">
    <div class="flex">
        <div class="text-center">
            <button class="btn btn-inverse close-uploader"><i class="icon-backward icon-white"></i> <?php echo trans('Return_Files_List')?></button>
        </div>
        <div class="space10"></div>
        <div class="tabbable upload-tabbable"> <!-- Only required for left/right tabs -->
            <div class="container1">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#baseUpload" data-toggle="tab"><?php echo trans('Upload_base');?></a></li>
                <?php if($config['url_upload']){ ?>
                <li><a href="#urlUpload" data-toggle="tab"><?php echo trans('Upload_url');?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="baseUpload">
                    <!-- The file upload form used as target for the file upload widget -->
                    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                        <div class="container2">
                            <div class="fileupload-buttonbar">
                                 <!-- The global progress state -->
                                <div class="fileupload-progress">
                                    <!-- The global progress bar -->
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                        <div class="bar bar-success" style="width:0%;"></div>
                                    </div>
                                    <!-- The extended global progress state -->
                                    <div class="progress-extended"></div>
                                </div>
                                <div class="text-center">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span><?php echo trans('Upload_add_files');?></span>
                                        <input type="file" name="files[]" multiple="multiple">
                                    </span>
                                    <button type="submit" class="btn btn-primary start">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span><?php echo trans('Upload_start');?></span>
                                    </button>
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process"></span>
                                </div>
                            </div>
                            <!-- The table listing the files available for upload/download -->
                            <div id="filesTable">
                                <table role="presentation" class="table table-striped table-condensed small"><tbody class="files"></tbody></table>
                            </div>
                            <div class="upload-help"><?php echo trans('Upload_base_help');?></div>
                        </div>
                    </form>
                    <!-- The template to display files available for upload -->
                    <script id="template-upload" type="text/x-tmpl">
                    {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-upload">
                            <td>
                                <span class="preview"></span>
                            </td>
                            <td>
                                <p class="name">{%=file.relativePath%}{%=file.name%}</p>
                                <strong class="error text-danger"></strong>
                            </td>
                            <td>
                                <p class="size">Processing...</p>
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar bar-success" style="width:0%;"></div></div>
                            </td>
                            <td>
                                {% if (!i && !o.options.autoUpload) { %}
                                    <button class="btn btn-primary start" disabled style="display:none">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Start</span>
                                    </button>
                                {% } %}
                                {% if (!i) { %}
                                    <button class="btn btn-link cancel">
                                        <i class="icon-remove"></i>
                                    </button>
                                {% } %}
                            </td>
                        </tr>
                    {% } %}
                    </script>
                    <!-- The template to display files available for download -->
                    <script id="template-download" type="text/x-tmpl">
                    {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-download">
                            <td>
                                <span class="preview">
                                    {% if (file.error) { %}
                                    <i class="icon icon-remove"></i>
                                    {% } else { %}
                                    <i class="icon icon-ok"></i>
                                    {% } %}
                                </span>
                            </td>
                            <td>
                                <p class="name">
                                    {% if (file.url) { %}
                                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                    {% } else { %}
                                        <span>{%=file.name%}</span>
                                    {% } %}
                                </p>
                                {% if (file.error) { %}
                                    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                                {% } %}
                            </td>
                            <td>
                                <span class="size">{%=o.formatFileSize(file.size)%}</span>
                            </td>
                            <td></td>
                        </tr>
                    {% } %}
                    </script>
                </div>
                <?php if($config['url_upload']){ ?>
                <div class="tab-pane" id="urlUpload">
                    <br/>
                    <form class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="url"><?php echo trans('Upload_url');?></label>
                            <div class="controls">
                                <input type="text" class="input-block-level" id="url" placeholder="<?php echo trans('Upload_url');?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-primary" id="uploadURL"><?php echo  trans('Upload_file');?></button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- uploader div end -->

<?php } ?>
        <div class="container-fluid">

<?php
$class_ext = '';
$src = '';
if ($ftp) {
    try {
        $files = $ftp->scanDir($config['ftp_base_folder'] . $config['upload_dir'] . $rfm_subfolder . $subdir);
        if (!$ftp->isDir($config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $rfm_subfolder . $subdir)) {
            create_folder(false, $config['ftp_base_folder'] . $config['ftp_thumbs_dir'] . $rfm_subfolder . $subdir, $ftp, $config);
        }
    } catch (FtpClient\FtpException $e) {
        echo "Error: ";
        echo $e->getMessage();
        echo "<br/>Please check configurations";
        die();
    }
} else {
    $files = scandir($config['current_path'] . $rfm_subfolder . $subdir);
}

$n_files = count($files);

//php sorting
$sorted = array();
//$current_folder=array();
//$prev_folder=array();
$current_files_number = 0;
$current_folders_number = 0;

foreach ($files as $k => $file) {
    if ($ftp) {
        $date = strtotime($file['day'] . " " . $file['month'] . " " . date('Y') . " " . $file['time']);
        $size = $file['size'];
        if ($file['type'] == 'file') {
            $current_files_number++;
            $file_ext = substr(strrchr($file['name'], '.'), 1);
            $is_dir = false;
        } else {
            $current_folders_number++;
            $file_ext = trans('Type_dir');
            $is_dir = true;
        }
        $sorted[$k] = array(
            'is_dir' => $is_dir,
            'file' => $file['name'],
            'file_lcase' => strtolower($file['name']),
            'date' => $date,
            'size' => $size,
            'permissions' => $file['permissions'],
            'extension' => fix_strtolower($file_ext)
        );
    } else {


        if ($file != "." && $file != "..") {
            if (is_dir($config['current_path'] . $rfm_subfolder . $subdir . $file)) {
                $date = filemtime($config['current_path'] . $rfm_subfolder . $subdir . $file);
                $current_folders_number++;
                if ($config['show_folder_size']) {
                    list($size, $nfiles, $nfolders) = folder_info($config['current_path'] . $rfm_subfolder . $subdir . $file, false);
                } else {
                    $size = 0;
                }
                $file_ext = trans('Type_dir');
                $sorted[$k] = array(
                    'is_dir' => true,
                    'file' => $file,
                    'file_lcase' => strtolower($file),
                    'date' => $date,
                    'size' => $size,
                    'permissions' => '',
                    'extension' => fix_strtolower($file_ext)
                );

                if ($config['show_folder_size']) {
                    $sorted[$k]['nfiles'] = $nfiles;
                    $sorted[$k]['nfolders'] = $nfolders;
                }
            } else {
                $current_files_number++;
                $file_path = $config['current_path'] . $rfm_subfolder . $subdir . $file;
                $date = filemtime($file_path);
                $size = filesize($file_path);
                $file_ext = substr(strrchr($file, '.'), 1);
                $sorted[$k] = array(
                    'is_dir' => false,
                    'file' => $file,
                    'file_lcase' => strtolower($file),
                    'date' => $date,
                    'size' => $size,
                    'permissions' => '',
                    'extension' => strtolower($file_ext)
                );
            }
        }
    }
}

function filenameSort($x, $y)
{
    global $descending;

    if ($x['is_dir'] !== $y['is_dir']) {
        return $y['is_dir'];
    } else {
        return ($descending)
            ? $x['file_lcase'] < $y['file_lcase']
            : $x['file_lcase'] >= $y['file_lcase'];
    }
}

function dateSort($x, $y)
{
    global $descending;

    if ($x['is_dir'] !== $y['is_dir']) {
        return $y['is_dir'];
    } else {
        return ($descending)
            ? $x['date'] < $y['date']
            : $x['date'] >= $y['date'];
    }
}

function sizeSort($x, $y)
{
    global $descending;

    if ($x['is_dir'] !== $y['is_dir']) {
        return $y['is_dir'];
    } else {
        return ($descending)
            ? $x['size'] < $y['size']
            : $x['size'] >= $y['size'];
    }
}

function extensionSort($x, $y)
{
    global $descending;

    if ($x['is_dir'] !== $y['is_dir']) {
        return $y['is_dir'];
    } else {
        return ($descending)
            ? $x['extension'] < $y['extension']
            : $x['extension'] >= $y['extension'];
    }
}

switch ($sort_by) {
    case 'date':
        usort($sorted, 'dateSort');
        break;
    case 'size':
        usort($sorted, 'sizeSort');
        break;
    case 'extension':
        usort($sorted, 'extensionSort');
        break;
    default:
        usort($sorted, 'filenameSort');
        break;
}

if ($subdir != "") {
    $sorted = array_merge(array(array('file' => '..')), $sorted);
}

$files = $sorted;
?>
<!-- header div start -->
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <div class="brand"><?php echo trans('Toolbar');?></div>
        <div class="nav-collapse collapse">
        <div class="filters">
            <div class="row-fluid">
            <div class="span4 half">
                <?php if($config['upload_files']){ ?>
                <button class="tip btn upload-btn" title="<?php echo  trans('Upload_file');?>"><i class="rficon-upload"></i></button>
                <?php } ?>
                <?php if($config['create_text_files']){ ?>
                <button class="tip btn create-file-btn" title="<?php echo  trans('New_File');?>"><i class="icon-plus"></i><i class="icon-file"></i></button>
                <?php } ?>
                <?php if($config['create_folders']){ ?>
                <button class="tip btn new-folder" title="<?php echo  trans('New_Folder')?>"><i class="icon-plus"></i><i class="icon-folder-open"></i></button>
                <?php } ?>
                <?php if($config['copy_cut_files'] || $config['copy_cut_dirs']){ ?>
                <button class="tip btn paste-here-btn" title="<?php echo trans('Paste_Here');?>"><i class="rficon-clipboard-apply"></i></button>
                <button class="tip btn clear-clipboard-btn" title="<?php echo trans('Clear_Clipboard');?>"><i class="rficon-clipboard-clear"></i></button>
                <?php } ?>
                <div id="multiple-selection" style="display:none;">
                <?php if($config['multiple_selection']){ ?>
                <?php if($config['delete_files']){ ?>
                <button class="tip btn multiple-delete-btn" title="<?php echo trans('Erase');?>" data-confirm="<?php echo trans('Confirm_del');?>"><i class="icon-trash"></i></button>
                <?php } ?>
                <button class="tip btn multiple-select-btn" title="<?php echo trans('Select_All');?>"><i class="icon-check"></i></button>
                <button class="tip btn multiple-deselect-btn" title="<?php echo trans('Deselect_All');?>"><i class="icon-ban-circle"></i></button>
                <?php if($apply_type!="apply_none" && $config['multiple_selection_action_button']){ ?>
                <button class="btn multiple-action-btn btn-inverse" data-function="<?php echo $apply_type;?>"><?php echo trans('Select'); ?></button>
                <?php } ?>
                <?php } ?>
                </div>
            </div>
            <div class="span2 half view-controller">
                <button class="btn tip<?php if($view==0) echo " btn-inverse";?>" id="view0" data-value="0" title="<?php echo trans('View_boxes');?>"><i class="icon-th <?php if($view==0) echo "icon-white";?>"></i></button>
                <button class="btn tip<?php if($view==1) echo " btn-inverse";?>" id="view1" data-value="1" title="<?php echo trans('View_list');?>"><i class="icon-align-justify <?php if($view==1) echo "icon-white";?>"></i></button>
                <button class="btn tip<?php if($view==2) echo " btn-inverse";?>" id="view2" data-value="2" title="<?php echo trans('View_columns_list');?>"><i class="icon-fire <?php if($view==2) echo "icon-white";?>"></i></button>
            </div>
            <div class="span6 entire types">
                <span><?php echo trans('Filters');?>:</span>
                <?php if($_GET['type']!=1 && $_GET['type']!=3 && $config['show_filter_buttons']){ ?>
                    <?php if(count($config['ext_file'])>0 or false){ ?>
                <input id="select-type-1" name="radio-sort" type="radio" data-item="ff-item-type-1" checked="checked"  class="hide"  />
                <label id="ff-item-type-1" title="<?php echo trans('Files');?>" for="select-type-1" class="tip btn ff-label-type-1"><i class="icon-file"></i></label>
                    <?php } ?>
                    <?php if(count($config['ext_img'])>0 or false){ ?>
                <input id="select-type-2" name="radio-sort" type="radio" data-item="ff-item-type-2" class="hide"  />
                <label id="ff-item-type-2" title="<?php echo trans('Images');?>" for="select-type-2" class="tip btn ff-label-type-2"><i class="icon-picture"></i></label>
                    <?php } ?>
                    <?php if(count($config['ext_misc'])>0 or false){ ?>
                <input id="select-type-3" name="radio-sort" type="radio" data-item="ff-item-type-3" class="hide"  />
                <label id="ff-item-type-3" title="<?php echo trans('Archives');?>" for="select-type-3" class="tip btn ff-label-type-3"><i class="icon-inbox"></i></label>
                    <?php } ?>
                    <?php if(count($config['ext_video'])>0 or false){ ?>
                <input id="select-type-4" name="radio-sort" type="radio" data-item="ff-item-type-4" class="hide"  />
                <label id="ff-item-type-4" title="<?php echo trans('Videos');?>" for="select-type-4" class="tip btn ff-label-type-4"><i class="icon-film"></i></label>
                    <?php } ?>
                    <?php if(count($config['ext_music'])>0 or false){ ?>
                <input id="select-type-5" name="radio-sort" type="radio" data-item="ff-item-type-5" class="hide"  />
                <label id="ff-item-type-5" title="<?php echo trans('Music');?>" for="select-type-5" class="tip btn ff-label-type-5"><i class="icon-music"></i></label>
                    <?php } ?>
                <?php } ?>
                <input accesskey="f" type="text" class="filter-input <?php echo (($_GET['type']!=1 && $_GET['type']!=3) ? '' : 'filter-input-notype');?>" id="filter-input" name="filter" placeholder="<?php echo fix_strtolower(trans('Text_filter'));?>..." value="<?php echo $filter;?>"/><?php if($n_files>$config['file_number_limit_js']){ ?><label id="filter" class="btn"><i class="icon-play"></i></label><?php } ?>

                <input id="select-type-all" name="radio-sort" type="radio" data-item="ff-item-type-all" class="hide"  />
                <label id="ff-item-type-all" title="<?php echo trans('All');?>" <?php if($_GET['type']==1 || $_GET['type']==3){ ?>style="visibility: hidden;" <?php } ?> data-item="ff-item-type-all" for="select-type-all" style="margin-rigth:0px;" class="tip btn btn-inverse ff-label-type-all"><?php echo trans('All');?></label>

            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- header div end -->

    <!-- breadcrumb div start -->

    <div class="row-fluid">
    <?php
    $link = "dialog.php?" . $get_params;
    ?>
    <ul class="breadcrumb">
    <li class="pull-left"><a href="<?php echo $link?>/"><i class="icon-home"></i></a></li>
    <li><span class="divider">/</span></li>
    <?php
    $bc=explode("/",$subdir);
    $tmp_path='';
    if(!empty($bc))
    foreach($bc as $k=>$b){
        $tmp_path.=$b."/";
        if($k==count($bc)-2){
    ?> <li class="active"><?php echo $b?></li><?php
        }elseif($b!=""){ ?>
        <li><a href="<?php echo $link.$tmp_path?>"><?php echo $b?></a></li><li><span class="divider"><?php echo "/";?></span></li>
    <?php }
    }
    ?>

    <li class="pull-right"><a class="btn-small" href="javascript:void('')" id="info"><i class="icon-question-sign"></i></a></li>
    <?php if($config['show_language_selection']){ ?>
    <li class="pull-right"><a class="btn-small" href="javascript:void('')" id="change_lang_btn"><i class="icon-globe"></i></a></li>
    <?php } ?>
    <li class="pull-right"><a id="refresh" class="btn-small" href="dialog.php?<?php echo $get_params.$subdir."&".uniqid() ?>"><i class="icon-refresh"></i></a></li>

	<li class="pull-right">
		<div class="btn-group">
		<a class="btn dropdown-toggle sorting-btn" data-toggle="dropdown" href="#">
		<i class="icon-signal"></i>
		<span class="caret"></span>
		</a>
		<ul class="dropdown-menu pull-left sorting">
			<li class="text-center"><strong><?php echo trans('Sorting') ?></strong></li>
		<li><a class="sorter sort-name <?php if($sort_by=="name"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="name"><?php echo trans('Filename');?></a></li>
		<li><a class="sorter sort-date <?php if($sort_by=="date"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="date"><?php echo trans('Date');?></a></li>
		<li><a class="sorter sort-size <?php if($sort_by=="size"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="size"><?php echo trans('Size');?></a></li>
		<li><a class="sorter sort-extension <?php if($sort_by=="extension"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="extension"><?php echo trans('Type');?></a></li>
		</ul>
		</div>
	</li>
	<li><small class="hidden-phone">(<span id="files_number"><?php echo $current_files_number."</span> ".trans('Files')." - <span id='folders_number'>".$current_folders_number."</span> ".trans('Folders');?>)</small></li>
	<?php if($config['show_total_size']){ ?>
	<li><small class="hidden-phone"><span title="<?php echo trans('total size').$config['MaxSizeTotal'];?>"><?php echo trans('total size').": ".makeSize($sizeCurrentFolder).(($config['MaxSizeTotal'] !== false && is_int($config['MaxSizeTotal']))? '/'.$config['MaxSizeTotal'].' '.trans('MB'):'');?></span></small>
	</li>
	<?php } ?>
	</ul>
	</div>
	<!-- breadcrumb div end -->
	<div class="row-fluid ff-container">
	<div class="span12">
		<?php if( ($ftp && !$ftp->isDir($config['ftp_base_folder'].$config['upload_dir'].$rfm_subfolder.$subdir))  || (!$ftp && @opendir($config['current_path'].$rfm_subfolder.$subdir)===FALSE)){ ?>
		<br/>
		<div class="alert alert-error">There is an error! The upload folder there isn't. Check your config.php file. </div>
		<?php }else{ ?>
		<h4 id="help"><?php echo trans('Swipe_help');?></h4>
		<?php if(isset($config['folder_message'])){ ?>
		<div class="alert alert-block"><?php echo $config['folder_message'];?></div>
		<?php } ?>
		<?php if($config['show_sorting_bar']){ ?>
		<!-- sorter -->
		<div class="sorter-container <?php echo "list-view".$view;?>">
		<div class="file-name"><a class="sorter sort-name <?php if($sort_by=="name"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="name"><?php echo trans('Filename');?></a></div>
		<div class="file-date"><a class="sorter sort-date <?php if($sort_by=="date"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="date"><?php echo trans('Date');?></a></div>
		<div class="file-size"><a class="sorter sort-size <?php if($sort_by=="size"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="size"><?php echo trans('Size');?></a></div>
		<div class='img-dimension'><?php echo trans('Dimension');?></div>
		<div class='file-extension'><a class="sorter sort-extension <?php if($sort_by=="extension"){ echo ($descending)?"descending":"ascending"; } ?>" href="javascript:void('')" data-sort="extension"><?php echo trans('Type');?></a></div>
		<div class='file-operations'><?php echo trans('Operations');?></div>
		</div>
		<?php } ?>

        <input type="hidden" id="file_number" value="<?php echo $n_files;?>" />
        <!--ul class="thumbnails ff-items"-->
        <ul class="grid cs-style-2 <?php echo "list-view".$view;?>" id="main-item-container">
        <?php


        foreach ($files as $file_array) {
            $file=$file_array['file'];
            if($file == '.' || ( substr($file, 0, 1) == '.' && isset( $file_array[ 'extension' ] ) && $file_array[ 'extension' ] == fix_strtolower(trans( 'Type_dir' ) )) || (isset($file_array['extension']) && $file_array['extension']!=fix_strtolower(trans('Type_dir'))) || ($file == '..' && $subdir == '') || in_array($file, $config['hidden_folders']) || ($filter!='' && $n_files>$config['file_number_limit_js'] && $file!=".." && stripos($file,$filter)===false)){
                continue;
            }
            $new_name=fix_filename($file,$config);
            if($ftp && $file!='..' && $file!=$new_name){
                //rename
                rename_folder($config['current_path'].$subdir.$file,$new_name,$ftp,$config);
                $file=$new_name;
            }
            //add in thumbs folder if not exist
            if($file!='..'){
                if(!$ftp && !file_exists($thumbs_path.$file)){
                    create_folder(false,$thumbs_path.$file,$ftp,$config);
                }
            }

            $class_ext = 3;
            if($file=='..' && trim($subdir) != '' ){
            $src = explode("/",$subdir);
            unset($src[count($src)-2]);
            $src=implode("/",$src);
            if($src=='') $src="/";
                }
                elseif ($file!='..') {
                    $src = $subdir . $file."/";
                }

            ?>
                <li data-name="<?php echo $file ?>" class="<?php if($file=='..') echo 'back'; else echo 'dir';?> <?php if(!$config['multiple_selection']){ ?>no-selector<?php } ?>" <?php if(($filter!='' && stripos($file,$filter)===false)) echo ' style="display:none;"';?>><?php
                $file_prevent_rename = false;
                $file_prevent_delete = false;
                if (isset($filePermissions[$file])) {
                $file_prevent_rename = isset($filePermissions[$file]['prevent_rename']) && $filePermissions[$file]['prevent_rename'];
                $file_prevent_delete = isset($filePermissions[$file]['prevent_delete']) && $filePermissions[$file]['prevent_delete'];
                }
                ?><figure data-name="<?php echo $file ?>" data-path="<?php echo $rfm_subfolder.$subdir.$file;?>" class="<?php if($file=="..") echo "back-";?>directory" data-type="<?php if($file!=".."){ echo "dir"; } ?>">
                <?php if($file==".."){ ?>
                    <input type="hidden" class="path" value="<?php echo str_replace('.','',dirname($rfm_subfolder.$subdir));?>"/>
                    <input type="hidden" class="path_thumb" value="<?php echo dirname($thumbs_path)."/";?>"/>
                <?php } ?>
                <a class="folder-link" href="dialog.php?<?php echo $get_params.rawurlencode($src)."&".($callback?'callback='.$callback."&":'').uniqid() ?>">
                    <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                            <img class="directory-img" data-src="img/<?php echo $config['icon_theme'];?>/folder<?php if($file==".."){ echo "_back"; }?>.png" />
                            </div>
                    </div>
                    <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                            <span></span>
                            <img class="directory-img" data-src="img/<?php echo $config['icon_theme'];?>/folder<?php if($file==".."){ echo "_back"; }?>.png" />
                            </div>
                    </div>
            <?php if($file==".."){ ?>
                    <div class="box no-effect">
                    <h4><?php echo trans('Back') ?></h4>
                    </div>
                    </a>

            <?php }else{ ?>
                    </a>
                    <div class="box">
                    <h4 class="<?php if($config['ellipsis_title_after_first_row']){ echo "ellipsis"; } ?>"><a class="folder-link" data-file="<?php echo $file ?>" href="dialog.php?<?php echo $get_params.rawurlencode($src)."&".uniqid() ?>"><?php echo $file;?></a></h4>
                    </div>
                    <input type="hidden" class="name" value="<?php echo $file_array['file_lcase'];?>"/>
                    <input type="hidden" class="date" value="<?php echo $file_array['date'];?>"/>
                    <input type="hidden" class="size" value="<?php echo $file_array['size'];?>"/>
                    <input type="hidden" class="extension" value="<?php echo fix_strtolower(trans('Type_dir'));?>"/>
                    <div class="file-date"><?php echo date(trans('Date_type'),$file_array['date']);?></div>
                    <?php if($config['show_folder_size']){ ?>
                        <div class="file-size"><?php echo makeSize($file_array['size']);?></div>
                        <input type="hidden" class="nfiles" value="<?php echo $file_array['nfiles'];?>"/>
                        <input type="hidden" class="nfolders" value="<?php echo $file_array['nfolders'];?>"/>
                    <?php } ?>
                    <div class='file-extension'><?php echo fix_strtolower(trans('Type_dir'));?></div>
                    <figcaption>
                        <a href="javascript:void('')" class="tip-left edit-button rename-file-paths <?php if($config['rename_folders'] && !$file_prevent_rename) echo "rename-folder";?>" title="<?php echo trans('Rename')?>" data-folder="1" data-permissions="<?php echo $file_array['permissions']; ?>">
                        <i class="icon-pencil <?php if(!$config['rename_folders'] || $file_prevent_rename) echo 'icon-white';?>"></i></a>
                        <a href="javascript:void('')" class="tip-left erase-button <?php if($config['delete_folders'] && !$file_prevent_delete) echo "delete-folder";?>" title="<?php echo trans('Erase')?>" data-confirm="<?php echo trans('Confirm_Folder_del');?>" >
                        <i class="icon-trash <?php if(!$config['delete_folders'] || $file_prevent_delete) echo 'icon-white';?>"></i>
                        </a>
                    </figcaption>
            <?php } ?>
                </figure>
            </li>
            <?php
            }


            $files_prevent_duplicate = array();
            foreach ($files as $nu=>$file_array) {
                $file=$file_array['file'];

                if($file == '.' || $file == '..' || $file_array['extension']==fix_strtolower(trans('Type_dir')) || !check_extension($file_array['extension'],$config) || ($filter!='' && $n_files>$config['file_number_limit_js'] && stripos($file,$filter)===false))
                    continue;
                foreach ( $config['hidden_files'] as $hidden_file ) {
                    if ( fnmatch($hidden_file, $file, FNM_PATHNAME) ) {
                        continue 2;
                    }
                }
                $filename=substr($file, 0, '-' . (strlen($file_array['extension']) + 1));
                if(strlen($file_array['extension'])===0){
                    $filename = $file;
                }
                if(!$ftp){
                    $file_path=$config['current_path'].$rfm_subfolder.$subdir.$file;
                    //check if file have illegal caracter

                    if($file!=fix_filename($file,$config)){
                        $file1=fix_filename($file,$config);
                        $file_path1=($config['current_path'].$rfm_subfolder.$subdir.$file1);
                        if(file_exists($file_path1)){
                            $i = 1;
                            $info=pathinfo($file1);
                            while(file_exists($config['current_path'].$rfm_subfolder.$subdir.$info['filename'].".[".$i."].".$info['extension'])) {
                                $i++;
                            }
                            $file1=$info['filename'].".[".$i."].".$info['extension'];
                            $file_path1=($config['current_path'].$rfm_subfolder.$subdir.$file1);
                        }

                        $filename=substr($file1, 0, '-' . (strlen($file_array['extension']) + 1));
                        if(strlen($file_array['extension'])===0){
                            $filename = $file1;
                        }
                        rename_file($file_path,fix_filename($filename,$config),$ftp,$config);
                        $file=$file1;
                        $file_array['extension']=fix_filename($file_array['extension'],$config);
                        $file_path=$file_path1;
                    }
                }else{
                    $file_path = $config['ftp_base_url'].$config['upload_dir'].$rfm_subfolder.$subdir.$file;
                }

                $is_img=false;
                $is_video=false;
                $is_audio=false;
                $show_original=false;
                $show_original_mini=false;
                $mini_src="";
                $src_thumb="";
                if(in_array($file_array['extension'], $config['ext_img'])){
                    $src = $file_path;
                    $is_img=true;

                    $img_width = $img_height = "";
                    if($ftp){
                        $mini_src = $src_thumb = $config['ftp_base_url'].$config['ftp_thumbs_dir'].$subdir. $file;
                        $creation_thumb_path = "/".$config['ftp_base_folder'].$config['ftp_thumbs_dir'].$subdir. $file;
                    }else{

                        $creation_thumb_path = $mini_src = $src_thumb = $thumbs_path. $file;

                        if (!file_exists($src_thumb)) {
                            if (!create_img($file_path, $creation_thumb_path, 122, 91, 'crop', $config)) {
                                $src_thumb = $mini_src = "";
                            }
                        }
                        //check if is smaller than thumb
                        list($img_width, $img_height, $img_type, $attr)=@getimagesize($file_path);
                        if($img_width<122 && $img_height<91){
                            $src_thumb=$file_path;
                            $show_original=true;
                        }

                        if($img_width<45 && $img_height<38){
                            $mini_src=$config['current_path'].$rfm_subfolder.$subdir.$file;
                            $show_original_mini=true;
                        }
                    }
                }
                $is_icon_thumb=false;
                $is_icon_thumb_mini=false;
                $no_thumb=false;
                if($src_thumb==""){
                    $no_thumb=true;
                    if(file_exists('img/'.$config['icon_theme'].'/'.$file_array['extension'].".jpg")){
                        $src_thumb ='img/'.$config['icon_theme'].'/'.$file_array['extension'].".jpg";
                    }else{
                        $src_thumb = "img/".$config['icon_theme']."/default.jpg";
                    }
                    $is_icon_thumb=true;
                }
                if($mini_src==""){
                $is_icon_thumb_mini=false;
                }

                $class_ext=0;
                if (in_array($file_array['extension'], $config['ext_video'])) {
                    $class_ext = 4;
                    $is_video=true;
                }elseif (in_array($file_array['extension'], $config['ext_img'])) {
                    $class_ext = 2;
                }elseif (in_array($file_array['extension'], $config['ext_music'])) {
                    $class_ext = 5;
                    $is_audio=true;
                }elseif (in_array($file_array['extension'], $config['ext_misc'])) {
                    $class_ext = 3;
                }else{
                    $class_ext = 1;
                }
                if((!($_GET['type']==1 && !$is_img) && !(($_GET['type']==3 && !$is_video) && ($_GET['type']==3 && !$is_audio))) && $class_ext>0){
?>
            <li class="ff-item-type-<?php echo $class_ext;?> file <?php if(!$config['multiple_selection']){ ?>no-selector<?php } ?>"  data-name="<?php echo $file;?>" <?php if(($filter!='' && stripos($file,$filter)===false)) echo ' style="display:none;"';?>><?php
            $file_prevent_rename = false;
            $file_prevent_delete = false;
            if (isset($filePermissions[$file])) {
            if (isset($filePermissions[$file]['prevent_duplicate']) && $filePermissions[$file]['prevent_duplicate']) {
                $files_prevent_duplicate[] = $file;
            }
            $file_prevent_rename = isset($filePermissions[$file]['prevent_rename']) && $filePermissions[$file]['prevent_rename'];
            $file_prevent_delete = isset($filePermissions[$file]['prevent_delete']) && $filePermissions[$file]['prevent_delete'];
            }
            ?>
            <figure data-name="<?php echo $file ?>" data-path="<?php echo $rfm_subfolder.$subdir.$file;?>" data-type="<?php if($is_img){ echo "img"; }else{ echo "file"; } ?>">
            <?php if($config['multiple_selection']){ ?><div class="selector">
                        <label class="cont">
                            <input type="checkbox" class="selection" name="selection[]" value="<?php echo $file;?>">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <?php } ?>
                <a href="javascript:void('')" class="link" data-file="<?php echo $file;?>" data-function="<?php echo $apply;?>">
                <div class="img-precontainer">
                    <?php if($is_icon_thumb){ ?><div class="filetype"><?php echo $file_array['extension'] ?></div><?php } ?>
                    
                    <div class="img-container">
                        <img class="<?php echo $show_original ? "original" : "" ?><?php echo $is_icon_thumb ? " icon" : "" ?>" data-src="<?php echo $src_thumb;?>">
                    </div>
                </div>
                <div class="img-precontainer-mini <?php if($is_img) echo 'original-thumb' ?>">
                    <?php if($config['multiple_selection']){ ?>
                    <?php } ?>
                    <div class="filetype <?php echo $file_array['extension'] ?> <?php if(in_array($file_array['extension'], $config['editable_text_file_exts'])) echo 'edit-text-file-allowed' ?> <?php if(!$is_icon_thumb){ echo "hide"; }?>"><?php echo $file_array['extension'] ?></div>
                    <div class="img-container-mini">
                    <?php if($mini_src!=""){ ?>
                    <img class="<?php echo $show_original_mini ? "original" : "" ?><?php echo $is_icon_thumb_mini ? " icon" : "" ?>" data-src="<?php echo $mini_src;?>">
                    <?php } ?>
                    </div>
                </div>
                <?php if($is_icon_thumb){ ?>
                <div class="cover"></div>
                <?php } ?>
                <div class="box">
                <h4 class="<?php if($config['ellipsis_title_after_first_row']){ echo "ellipsis"; } ?>">
                <?php echo $filename;?></h4>
                </div></a>
                <input type="hidden" class="date" value="<?php echo $file_array['date'];?>"/>
                <input type="hidden" class="size" value="<?php echo $file_array['size'] ?>"/>
                <input type="hidden" class="extension" value="<?php echo $file_array['extension'];?>"/>
                <input type="hidden" class="name" value="<?php echo $file_array['file_lcase'];?>"/>
                <div class="file-date"><?php echo date(trans('Date_type'),$file_array['date'])?></div>
                <div class="file-size"><?php echo makeSize($file_array['size'])?></div>
                <div class='img-dimension'><?php if($is_img){ echo $img_width."x".$img_height; } ?></div>
                <div class='file-extension'><?php echo $file_array['extension'];?></div>
                <figcaption>
                    <form action="force_download.php" method="post" class="download-form" id="form<?php echo $nu;?>">
                    <input type="hidden" name="path" value="<?php echo $rfm_subfolder.$subdir?>"/>
                    <input type="hidden" class="name_download" name="name" value="<?php echo $file?>"/>

                    <a title="<?php echo trans('Download')?>" class="tip-right" href="javascript:void('')" <?php if($config['download_files']) echo "onclick=\"$('#form".$nu."').submit();\"" ?>><i class="icon-download <?php if(!$config['download_files']) echo 'icon-white'; ?>"></i></a>

                    <?php if($is_img && $src_thumb!=""){ ?>
                    <a class="tip-right preview" title="<?php echo trans('Preview')?>" data-featherlight="<?php echo $src;?>"  href="#"><i class=" icon-eye-open"></i></a>
                    <?php }elseif(($is_video || $is_audio) && in_array($file_array['extension'],$config['jplayer_exts'])){ ?>
                    <a class="tip-right modalAV <?php if($is_audio){ echo "audio"; }else{ echo "video"; } ?>"
                    title="<?php echo trans('Preview')?>" data-url="ajax_calls.php?action=media_preview&title=<?php echo $filename;?>&file=<?php echo $rfm_subfolder.$subdir.$file;?>"
                    href="javascript:void('');" ><i class=" icon-eye-open"></i></a>
                    <?php }elseif(in_array($file_array['extension'],$config['cad_exts'])){ ?>
                    <a class="tip-right file-preview-btn" title="<?php echo trans('Preview')?>" data-url="ajax_calls.php?action=cad_preview&title=<?php echo $filename;?>&file=<?php echo $rfm_subfolder.$subdir.$file;?>"
                    href="javascript:void('');" ><i class=" icon-eye-open"></i></a>
                    <?php }elseif($config['preview_text_files'] && in_array($file_array['extension'],$config['previewable_text_file_exts'])){ ?>
                    <a class="tip-right file-preview-btn" title="<?php echo trans('Preview')?>" data-url="ajax_calls.php?action=get_file&sub_action=preview&preview_mode=text&title=<?php echo $filename;?>&file=<?php echo $rfm_subfolder.$subdir.$file;?>"
                    href="javascript:void('');" ><i class=" icon-eye-open"></i></a>
                    <?php }elseif($config['googledoc_enabled'] && in_array($file_array['extension'],$config['googledoc_file_exts'])){ ?>
                    <a class="tip-right file-preview-btn" title="<?php echo trans('Preview')?>" data-url="ajax_calls.php?action=get_file&sub_action=preview&preview_mode=google&title=<?php echo $filename;?>&file=<?php echo $rfm_subfolder.$subdir.$file;?>"
                    href="docs.google.com;" ><i class=" icon-eye-open"></i></a>
                    <?php }else{ ?>
                    <a class="preview disabled"><i class="icon-eye-open icon-white"></i></a>
                    <?php } ?>
                    <a href="javascript:void('')" class="tip-left edit-button rename-file-paths <?php if($config['rename_files'] && !$file_prevent_rename) echo "rename-file";?>" title="<?php echo trans('Rename')?>" data-folder="0" data-permissions="<?php echo $file_array['permissions']; ?>">
                    <i class="icon-pencil <?php if(!$config['rename_files'] || $file_prevent_rename) echo 'icon-white';?>"></i></a>

                    <a href="javascript:void('')" class="tip-left erase-button <?php if($config['delete_files'] && !$file_prevent_delete) echo "delete-file";?>" title="<?php echo trans('Erase')?>" data-confirm="<?php echo trans('Confirm_del');?>">
                    <i class="icon-trash <?php if(!$config['delete_files'] || $file_prevent_delete) echo 'icon-white';?>"></i>
                    </a>
                    </form>
                </figcaption>
            </figure>
        </li>
            <?php
            }
            }

    ?></div>
        </ul>
        <?php } ?>
    </div>
    </div>
</div>

<script>
    var files_prevent_duplicate = [];
    <?php foreach ($files_prevent_duplicate as $key => $value): ?>
    files_prevent_duplicate[<?php echo $key;?>] = '<?php echo $value;?>';
    <?php endforeach;?>
</script>

    <!-- loading div start -->
    <div id="loading_container" style="display:none;">
        <div id="loading" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
        <img id="loading_animation" src="img/storing_animation.gif" alt="loading" style="z-index:10001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%">
    </div>
    <!-- loading div end -->

    <!-- player div start -->
    <div class="modal hide" id="previewAV">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo trans('Preview'); ?></h3>
        </div>
        <div class="modal-body">
            <div class="row-fluid body-preview">
            </div>
        </div>
    </div>

    <!-- player div end -->
    <?php if ( $config['tui_active'] ) { ?>

        <div id="tui-image-editor" style="height: 800px;" class="hide">
            <canvas></canvas>
        </div>

        <script>
            var tuiTheme = {
                <?php foreach ($config['tui_defaults_config'] as $aopt_key => $aopt_val) {
                    if ( !empty($aopt_val) ) {
                        echo "'$aopt_key':".json_encode($aopt_val).",";
                    }
                } ?>
            }; 
        </script>

        <script>
        if (image_editor) { 
            //TUI initial init with a blank image (Needs to be initiated before a dynamic image can be loaded into it)
            var imageEditor = new tui.ImageEditor('#tui-image-editor', {
                includeUI: {
                     loadImage: {
                        path: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                        name: 'Blank'
                     },
                     theme: tuiTheme,
                     initMenu: 'filter',
                     menuBarPosition: '<?php echo $config['tui_position'] ?>'
                 },
                cssMaxWidth: 1000, // Component default value: 1000
                cssMaxHeight: 800,  // Component default value: 800
                selectionStyle: {
                    cornerSize: 20,
                    rotxatingPointOffset: 70
                }
            });
            //cache loaded image
            imageEditor.loadImageFromURL = (function() {
                var cached_function = imageEditor.loadImageFromURL;
                function waitUntilImageEditorIsUnlocked(imageEditor) {
                    return new Promise((resolve,reject)=>{
                        const interval = setInterval(()=>{
                            if (!imageEditor._invoker._isLocked) {
                                clearInterval(interval);
                                resolve();
                            }
                        }, 100);
                    })
                }
                return function() {
                    return waitUntilImageEditorIsUnlocked(imageEditor).then(()=>cached_function.apply(this, arguments));
                };
            })();

            //Replace Load button with exit button
            $('.tui-image-editor-header-buttons div').
            replaceWith('<button class="tui-image-editor-exit-btn" ><?php echo trans('Image_Editor_Exit');?></button>');
            $('.tui-image-editor-exit-btn').on('click', function() {
                exitTUI();
            });
            //Replace download button with save
            $('.tui-image-editor-download-btn').
            replaceWith('<button class="tui-image-editor-save-btn" ><?php echo trans('Image_Editor_Save');?></button>');
            $('.tui-image-editor-save-btn').on('click', function() {
                saveTUI();
            });

            function exitTUI()
            {
                imageEditor.clearObjects();
                imageEditor.discardSelection();
                $('#tui-image-editor').addClass('hide');
            }

            function saveTUI()
            {
                show_animation();
                newURL = imageEditor.toDataURL();
                $.ajax({
                    type: "POST",
                    url: "ajax_calls.php?action=save_img",
                    data: { url: newURL, path:$('#sub_folder').val()+$('#fldr_value').val(), name:$('#tui-image-editor').attr('data-name') }
                }).done(function( msg ) {
                    exitTUI();
                    d = new Date();
                    $("figure[data-name='"+$('#tui-image-editor').attr('data-name')+"']").find('.img-container img').each(function(){
                    $(this).attr('src',$(this).attr('src')+"?"+d.getTime());
                    });
                    $("figure[data-name='"+$('#tui-image-editor').attr('data-name')+"']").find('figcaption a.preview').each(function(){
                    $(this).attr('data-url',$(this).data('url')+"?"+d.getTime());
                    });
                    hide_animation();
                });
                return false;
            }
        }
        </script>
    <?php } ?>
    <script>
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
        if (isAndroid) {
            $('li').draggable({disabled: true});
        }
    </script>
</body>
</html>
