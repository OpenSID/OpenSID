<?php
if (session_id() == '') session_start();

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');
date_default_timezone_set('Asia/Makassar');

/*
|--------------------------------------------------------------------------
| Optional security
|--------------------------------------------------------------------------
|
| if set to true only those will access RF whose url contains the access key(akey) like:
| <input type="button" href="../filemanager/dialog.php?field_id=imgField&lang=en_EN&akey=myPrivateKey" value="Files">
| in tinymce a new parameter added: filemanager_access_key:"myPrivateKey"
| example tinymce config:
|
| tiny init ...
| external_filemanager_path:"../filemanager/",
| filemanager_title:"Filemanager" ,
| filemanager_access_key:"myPrivateKey" ,
| ...
|
*/

/*
	Password untuk File Manager disimpan di folder desa, jika ada
*/
if (file_exists('../../desa/config/config.php')) {
	include('../../desa/config/config.php');
}
$config['file_manager'] = isset($config['file_manager']) ? $config['file_manager'] : '';
define('USE_ACCESS_KEYS', !empty($config['file_manager'])); // TRUE or FALSE

/*
|--------------------------------------------------------------------------
| DON'T COPY THIS VARIABLES IN FOLDERS config.php FILES
|--------------------------------------------------------------------------
*/

define('DEBUG_ERROR_MESSAGE', true); // TRUE or FALSE

/*
|--------------------------------------------------------------------------
| Path configuration
|--------------------------------------------------------------------------
| In this configuration the folder tree is
| root
|    |- source <- upload folder
|    |- thumbs <- thumbnail folder [must have write permission (755)]
|    |- filemanager
|    |- js
|    |   |- tinymce
|    |   |   |- plugins
|    |   |   |   |- responsivefilemanager
|    |   |   |   |   |- plugin.min.js
*/

/*
 Untuk mencari folder aplikasi di localhost. Asumsi $_SERVER['REQUEST_URI'] mempunyai nilai
 seperti:
 '/OpenSID/assets/filemanager/dialog.php?type=1&descending=false&lang=undefined&akey=KunciDesa'
*/
$folder_app = strtolower(substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/assets')));

$config = array(

	/*
	|--------------------------------------------------------------------------
	| DON'T TOUCH (base url (only domain) of site).
	|--------------------------------------------------------------------------
	|
	| without final / (DON'T TOUCH)
	|
	*/

    //jika di lokalhost gunakan konfigurasi ini, dan ganti /{opensid304}/assets' dengan nama folder opensid anda
//	'base_url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && ! in_array(strtolower($_SERVER['HTTPS']), array( 'off', 'no' ))) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].'/opensid304/assets',

   'base_url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && ! in_array(strtolower($_SERVER['HTTPS']), array( 'off', 'no' ))) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $folder_app . '/assets',

	/*
	|--------------------------------------------------------------------------
	| path from base_url to base of upload folder
	|--------------------------------------------------------------------------
	|
	| with start and final /
	|
	*/
	'upload_dir' => '/../desa/upload/media/',
	/*
	|--------------------------------------------------------------------------
	| relative path from filemanager folder to upload folder
	|--------------------------------------------------------------------------
	|
	| with final /
	|
	*/
	'current_path' => '../../desa/upload/media/',

	/*
	|--------------------------------------------------------------------------
	| relative path from filemanager folder to thumbs folder
	|--------------------------------------------------------------------------
	|
	| with final /
	| DO NOT put inside upload folder
	|
	*/
	'thumbs_base_path' => '../../desa/upload/thumbs/',


	/*
	|--------------------------------------------------------------------------
	| FTP configuration BETA VERSION
	|--------------------------------------------------------------------------
	|
	| If you want enable ftp use write these parametres otherwise leave empty
	| Remember to set base_url properly to point in the ftp server domain and
	| upload dir will be ftp_base_folder + upload_dir so without final /
	|
	*/
	'ftp_host'         => false,
	'ftp_user'         => "user",
	'ftp_pass'         => "pass",
	'ftp_base_folder'  => "base_folder",
	'ftp_base_url'     => "http://site to ftp root",
	/* --------------------------------------------------------------------------
	| path from ftp_base_folder to base of thumbs folder with start and final |
	|--------------------------------------------------------------------------*/
	'ftp_thumbs_dir' => '/thumbs/',
	'ftp_ssl' => false,
	'ftp_port' => 21,


	// 'ftp_host'         => "s108707.gridserver.com",
	// 'ftp_user'         => "test@responsivefilemanager.com",
	// 'ftp_pass'         => "Test.1234",
	// 'ftp_base_folder'  => "/domains/responsivefilemanager.com/html",


	/*
	|--------------------------------------------------------------------------
	| Access keys
	|--------------------------------------------------------------------------
	|
	| add access keys eg: array('myPrivateKey', 'someoneElseKey');
	| keys should only containt (a-z A-Z 0-9 \ . _ -) characters
	| if you are integrating lets say to a cms for admins, i recommend making keys randomized something like this:
	| $username = 'Admin';
	| $salt = 'dsflFWR9u2xQa' (a hard coded string)
	| $akey = md5($username.$salt);
	| DO NOT use 'key' as access key!
	| Keys are CASE SENSITIVE!
	|
	*/

	'access_keys' => array($config['file_manager']),

	//--------------------------------------------------------------------------------------------------------
	// YOU CAN COPY AND CHANGE THESE VARIABLES INTO FOLDERS config.php FILES TO CUSTOMIZE EACH FOLDER OPTIONS
	//--------------------------------------------------------------------------------------------------------

	/*
	|--------------------------------------------------------------------------
	| Maximum size of all files in source folder
	|--------------------------------------------------------------------------
	|
	| in Megabytes
	|
	*/
	'MaxSizeTotal' => false,

	/*
	|--------------------------------------------------------------------------
	| Maximum upload size
	|--------------------------------------------------------------------------
	|
	| in Megabytes
	|
	*/
	'MaxSizeUpload' => 8,

	/*
	|--------------------------------------------------------------------------
	| File and Folder permission
	|--------------------------------------------------------------------------
	|
	*/
	'fileFolderPermission' => 0755,


	/*
	|--------------------------------------------------------------------------
	| default language file name
	|--------------------------------------------------------------------------
	*/
	'default_language' => "en_EN",

	/*
	|--------------------------------------------------------------------------
	| Icon theme
	|--------------------------------------------------------------------------
	|
	| Default available: ico and ico_dark
	| Can be set to custom icon inside filemanager/img
	|
	*/
	'icon_theme' => "ico",


	//Show or not total size in filemanager (is possible to greatly increase the calculations)
	'show_total_size'						=> false,
	//Show or not show folder size in list view feature in filemanager (is possible, if there is a large folder, to greatly increase the calculations)
	'show_folder_size'						=> false,
	//Show or not show sorting feature in filemanager
	'show_sorting_bar'						=> true,
	//Show or not show filters button in filemanager
	'show_filter_buttons'                   => true,
	//Show or not language selection feature in filemanager
	'show_language_selection'				=> true,
	//active or deactive the transliteration (mean convert all strange characters in A..Za..z0..9 characters)
	'transliteration'						=> false,
	//convert all spaces on files name and folders name with $replace_with variable
	'convert_spaces'						=> false,
	//convert all spaces on files name and folders name this value
	'replace_with'							=> "_",
	//convert to lowercase the files and folders name
	'lower_case'							=> false,

	//Add ?484899493349 (time value) to returned images to prevent cache
	'add_time_to_img'                       => false,

	// -1: There is no lazy loading at all, 0: Always lazy-load images, 0+: The minimum number of the files in a directory
	// when lazy loading should be turned on.
	'lazy_loading_file_number_threshold'	=> 0,


	//*******************************************
	//Images limit and resizing configuration
	//*******************************************

	// set maximum pixel width and/or maximum pixel height for all images
	// If you set a maximum width or height, oversized images are converted to those limits. Images smaller than the limit(s) are unaffected
	// if you don't need a limit set both to 0
	'image_max_width'                         => 0,
	'image_max_height'                        => 0,
	'image_max_mode'                          => 'auto',
	/*
	#  $option:  0 / exact = defined size;
	#            1 / portrait = keep aspect set height;
	#            2 / landscape = keep aspect set width;
	#            3 / auto = auto;
	#            4 / crop= resize and crop;
	*/

	//Automatic resizing //
	// If you set $image_resizing to TRUE the script converts all uploaded images exactly to image_resizing_width x image_resizing_height dimension
	// If you set width or height to 0 the script automatically calculates the other dimension
	// Is possible that if you upload very big images the script not work to overcome this increase the php configuration of memory and time limit
	'image_resizing'                          => false,
	'image_resizing_width'                    => 0,
	'image_resizing_height'                   => 0,
	'image_resizing_mode'                     => 'auto', // same as $image_max_mode
	'image_resizing_override'                 => false,
	// If set to TRUE then you can specify bigger images than $image_max_width & height otherwise if image_resizing is
	// bigger than $image_max_width or height then it will be converted to those values


	//******************
	//
	// WATERMARK IMAGE
	//
	//Watermark url or false
	'image_watermark'                          => false,
	# Could be a pre-determined position such as:
	#           tl = top left,
	#           t  = top (middle),
	#           tr = top right,
	#           l  = left,
	#           m  = middle,
	#           r  = right,
	#           bl = bottom left,
	#           b  = bottom (middle),
	#           br = bottom right
	#           Or, it could be a co-ordinate position such as: 50x100
	'image_watermark_position'                 => 'br',
	# padding: If using a pre-determined position you can
	#         adjust the padding from the edges by passing an amount
	#         in pixels. If using co-ordinates, this value is ignored.
	'image_watermark_padding'                 => 0,

	//******************
	// Default layout setting
	//
	// 0 => boxes
	// 1 => detailed list (1 column)
	// 2 => columns list (multiple columns depending on the width of the page)
	// YOU CAN ALSO PASS THIS PARAMETERS USING SESSION VAR => $_SESSION['RF']["VIEW"]=
	//
	//******************
	'default_view'                            => 0,

	//set if the filename is truncated when overflow first row
	'ellipsis_title_after_first_row'          => true,

	//*************************
	//Permissions configuration
	//******************
	'delete_files'                            => true,
	'create_folders'                          => true,
	'delete_folders'                          => true,
	'upload_files'                            => true,
	'rename_files'                            => true,
	'rename_folders'                          => true,
	'duplicate_files'                         => true,
	'copy_cut_files'                          => true, // for copy/cut files
	'copy_cut_dirs'                           => true, // for copy/cut directories
	'chmod_files'                             => true, // change file permissions
	'chmod_dirs'                              => true, // change folder permissions
	'preview_text_files'                      => true, // eg.: txt, log etc.
	'edit_text_files'                         => true, // eg.: txt, log etc.
	'create_text_files'                       => true, // only create files with exts. defined in $editable_text_file_exts

	// you can preview these type of files if $preview_text_files is true
	'previewable_text_file_exts'              => array( "bsh", "c","css", "cc", "cpp", "cs", "csh", "cyc", "cv", "htm", "html", "java", "js", "m", "mxml", "perl", "pl", "pm", "py", "rb", "sh", "xhtml", "xml","xsl" ),
	'previewable_text_file_exts_no_prettify'  => array( 'txt', 'log' ),

	// you can edit these type of files if $edit_text_files is true (only text based files)
	// you can create these type of files if $create_text_files is true (only text based files)
	// if you want you can add html,css etc.
	// but for security reasons it's NOT RECOMMENDED!
	'editable_text_file_exts'                 => array( 'txt', 'log', 'xml', 'html', 'css', 'htm', 'js' ),

	// Preview with Google Documents
	'googledoc_enabled'                       => true,
	'googledoc_file_exts'                     => array( 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ),

	// Preview with Viewer.js
	'viewerjs_enabled'                        => true,
	'viewerjs_file_exts'                      => array( 'pdf', 'odt', 'odp', 'ods' ),

	// defines size limit for paste in MB / operation
	// set 'FALSE' for no limit
	'copy_cut_max_size'                       => 100,
	// defines file count limit for paste / operation
	// set 'FALSE' for no limit
	'copy_cut_max_count'                      => 200,
	//IF any of these limits reached, operation won't start and generate warning

	//**********************
	//Allowed extensions (lowercase insert)
	//**********************
	'ext_img'                                 => array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg' ), //Images
	'ext_file'                                => array( 'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'xhtml', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'mdb', 'accdb', 'ppt', 'pptx', 'odt', 'ots', 'ott', 'odb', 'odg', 'otp', 'otg', 'odf', 'ods', 'odp', 'css', 'ai', 'kmz','dwg', 'dxf', 'hpgl', 'plt', 'spl', 'step', 'stp', 'iges', 'igs', 'sat', 'cgm'), //Files
	'ext_video'                               => array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" ), //Video
	'ext_music'                               => array( 'mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' ), //Audio
	'ext_misc'                                => array( 'zip', 'rar', 'gz', 'tar', 'iso', 'dmg' ), //Archives

	/******************
	* AVIARY config
	*******************/
	'aviary_active'                           => true,
	'aviary_apiKey'                           => "2444282ef4344e3dacdedc7a78f8877d",
	'aviary_language'                         => "en",
	'aviary_theme'                            => "light",
	'aviary_tools'                            => "all",
	'aviary_maxSize'                          => "1400",
	// Add or modify the Aviary options below as needed - they will be json encoded when added to the configuration so arrays can be utilized as needed

	//The filter and sorter are managed through both javascript and php scripts because if you have a lot of
	//file in a folder the javascript script can't sort all or filter all, so the filemanager switch to php script.
	//The plugin automatic swich javascript to php when the current folder exceeds the below limit of files number
	'file_number_limit_js'                    => 500,

	//**********************
	// Hidden files and folders
	//**********************
	// set the names of any folders you want hidden (eg "hidden_folder1", "hidden_folder2" ) Remember all folders with these names will be hidden (you can set any exceptions in config.php files on folders)
	'hidden_folders'                          => array(),
	// set the names of any files you want hidden. Remember these names will be hidden in all folders (eg "this_document.pdf", "that_image.jpg" )
	'hidden_files'                            => array( 'config.php' ),

	/*******************
	* URL upload
	*******************/
	'url_upload'                             => true,

	/*******************
	* JAVA upload
	*******************/
	'java_upload'                             => true,
	'JAVAMaxSizeUpload'                       => 200, //Gb


	//************************************
	//Thumbnail for external use creation
	//************************************


	// New image resized creation with fixed path from filemanager folder after uploading (thumbnails in fixed mode)
	// If you want create images resized out of upload folder for use with external script you can choose this method,
	// You can create also more than one image at a time just simply add a value in the array
	// Remember than the image creation respect the folder hierarchy so if you are inside source/test/test1/ the new image will create at
	// path_from_filemanager/test/test1/
	// PS if there isn't write permission in your destination folder you must set it
	//
	'fixed_image_creation'                    => false, //activate or not the creation of one or more image resized with fixed path from filemanager folder
	'fixed_path_from_filemanager'             => array( '../test/', '../test1/' ), //fixed path of the image folder from the current position on upload folder
	'fixed_image_creation_name_to_prepend'    => array( '', 'test_' ), //name to prepend on filename
	'fixed_image_creation_to_append'          => array( '_test', '' ), //name to appendon filename
	'fixed_image_creation_width'              => array( 300, 400 ), //width of image (you can leave empty if you set height)
	'fixed_image_creation_height'             => array( 200, '' ), //height of image (you can leave empty if you set width)
	/*
	#             $option:     0 / exact = defined size;
	#                          1 / portrait = keep aspect set height;
	#                          2 / landscape = keep aspect set width;
	#                          3 / auto = auto;
	#                          4 / crop= resize and crop;
	*/
	'fixed_image_creation_option'             => array( 'crop', 'auto' ), //set the type of the crop


	// New image resized creation with relative path inside to upload folder after uploading (thumbnails in relative mode)
	// With Responsive filemanager you can create automatically resized image inside the upload folder, also more than one at a time
	// just simply add a value in the array
	// The image creation path is always relative so if i'm inside source/test/test1 and I upload an image, the path start from here
	//
	'relative_image_creation'                 => false, //activate or not the creation of one or more image resized with relative path from upload folder
	'relative_path_from_current_pos'          => array( './', './' ), //relative path of the image folder from the current position on upload folder
	'relative_image_creation_name_to_prepend' => array( '', '' ), //name to prepend on filename
	'relative_image_creation_name_to_append'  => array( '_thumb', '_thumb1' ), //name to append on filename
	'relative_image_creation_width'           => array( 300, 400 ), //width of image (you can leave empty if you set height)
	'relative_image_creation_height'          => array( 200, '' ), //height of image (you can leave empty if you set width)
	/*
	#             $option:     0 / exact = defined size;
	#                          1 / portrait = keep aspect set height;
	#                          2 / landscape = keep aspect set width;
	#                          3 / auto = auto;
	#                          4 / crop= resize and crop;
	*/
	'relative_image_creation_option'          => array( 'crop', 'crop' ), //set the type of the crop


	// Remember text filter after close filemanager for future session
	'remember_text_filter'                    => false,

);

return array_merge(
	$config,
	array(
		'MaxSizeUpload' => ((int)(ini_get('post_max_size')) < $config['MaxSizeUpload'])
			? (int)(ini_get('post_max_size')) : $config['MaxSizeUpload'],
		'ext'=> array_merge(
			$config['ext_img'],
			$config['ext_file'],
			$config['ext_misc'],
			$config['ext_video'],
			$config['ext_music']
		),
		// For a list of options see: https://developers.aviary.com/docs/web/setup-guide#constructor-config
		'aviary_defaults_config' => array(
			'apiKey'     => $config['aviary_apiKey'],
			'language'   => $config['aviary_language'],
			'theme'      => $config['aviary_theme'],
			'tools'      => $config['aviary_tools'],
			'maxSize'    => $config['aviary_maxSize']
		),
	)
);
?>
