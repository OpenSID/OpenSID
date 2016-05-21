<?php
//a:9:{s:4:"lang";N;s:9:"auth_pass";s:32:"b078c98974a707884364ccce839846ad";s:8:"quota_mb";i:0;s:17:"upload_ext_filter";a:0:{}s:19:"download_ext_filter";a:0:{}s:15:"error_reporting";s:1:"0";s:7:"fm_root";s:7:"C:/www/";s:17:"cookie_cache_time";i:2592000;s:7:"version";s:5:"0.9.8";}

// +--------------------------------------------------
// | Header and Globals
// +--------------------------------------------------	
	$charset = "UTF-8";
    //@setlocale(LC_CTYPE, 'C');
    header("Pragma: no-cache");
    header("Cache-Control: no-store");
	header("Content-Type: text/html; charset=".$charset);
	//@ini_set('default_charset', $charset);
    if (@get_magic_quotes_gpc()) {
        function stripslashes_deep($value){
            return is_array($value)? array_map('stripslashes_deep', $value):$value;
        }
        $_POST = array_map('stripslashes_deep', $_POST);
        $_GET = array_map('stripslashes_deep', $_GET);
        $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    }
	// Server Vars
    function get_client_ip() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP']) $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED']) $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED']) $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR']) $ipaddress = $_SERVER['REMOTE_ADDR'];
		// proxy transparente não esconde o IP local, colocando ele após o IP da rede, separado por vírgula
		if (strpos($ipaddress, ',') !== false) {
		    $ips = explode(',', $ipaddress);
		    $ipaddress = trim($ips[0]);
		}
		if ($ipaddress == '::1') $ipaddress = '';
        return $ipaddress;
    }		
    $ip = get_client_ip();
    $islinux = !(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    function getServerURL() {
        $url = ($_SERVER["HTTPS"] == "on")?"https://":"http://";
        $url .= $_SERVER["SERVER_NAME"]; // $_SERVER["HTTP_HOST"] is equivalent
        if ($_SERVER["SERVER_PORT"] != "80") $url .= ":".$_SERVER["SERVER_PORT"];
        return $url;
    }
    function getCompleteURL() {
        return getServerURL().$_SERVER["REQUEST_URI"];
    }
    $url = getCompleteURL();
    $url_info = parse_url($url);
	if( !isset($_SERVER['DOCUMENT_ROOT']) ) {
		if ( isset($_SERVER['SCRIPT_FILENAME']) ) $path = $_SERVER['SCRIPT_FILENAME'];
		elseif ( isset($_SERVER['PATH_TRANSLATED']) ) $path = str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']);
		$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($path, 0, 0-strlen($_SERVER['PHP_SELF'])));
	}
	$doc_root = str_replace('//','/',str_replace(DIRECTORY_SEPARATOR,'/',$_SERVER["DOCUMENT_ROOT"]));
    $fm_self = $doc_root.$_SERVER["PHP_SELF"];
    $path_info = pathinfo($fm_self);
	// Register Globals
	$blockKeys = array('_SERVER','_SESSION','_GET','_POST','_COOKIE','charset','ip','islinux','url','url_info','doc_root','fm_self','path_info');
    foreach ($_GET as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
    foreach ($_POST as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
    foreach ($_COOKIE as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
// +--------------------------------------------------
// | Config
// +--------------------------------------------------
    $cfg = new config();
    $cfg->load();
    switch ($error_reporting){
        case 0: error_reporting(0); @ini_set("display_errors",0); break;
        case 1: error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR); @ini_set("display_errors",1); break;
        case 2: error_reporting(E_ALL); @ini_set("display_errors",1); break;
    }
    if (!isset($current_dir)){
        $current_dir = $path_info["dirname"]."/";
        if (!$islinux) $current_dir = ucfirst($current_dir);
        //@chmod($current_dir,0755);
    } else $current_dir = format_path($current_dir);
    // Auto Expand Local Path
    if (!isset($expanded_dir_list)){
        $expanded_dir_list = "";
        $mat = explode("/",$path_info["dirname"]);
        for ($x=0;$x<count($mat);$x++) $expanded_dir_list .= ":".$mat[$x];
        setcookie("expanded_dir_list", $expanded_dir_list, 0, "/");
    }
    if (!isset($fm_current_root)){
        if (strlen($fm_root)) $fm_current_root = $fm_root;
        else {
            if (!$islinux) $fm_current_root = ucfirst($path_info["dirname"]."/");
            else $fm_current_root = $doc_root."/";
        }
        setcookie("fm_current_root", $fm_current_root, 0, "/");
    } elseif (isset($set_fm_current_root)) {
        if (!$islinux) $fm_current_root = ucfirst($set_fm_current_root);
        setcookie("fm_current_root", $fm_current_root, 0, "/");
    }
    if (!isset($resolveIDs)){
        setcookie("resolveIDs", 0, time()+$cookie_cache_time, "/");
    } elseif (isset($set_resolveIDs)){
        $resolveIDs=($resolveIDs)?0:1;
        setcookie("resolveIDs", $resolveIDs, time()+$cookie_cache_time, "/");
    }
    if ($resolveIDs){
        exec("cat /etc/passwd",$mat_passwd);
        exec("cat /etc/group",$mat_group);
    }
    $fm_color['Bg'] = "000000";
    $fm_color['Text'] = "2ae22e";
    $fm_color['Link'] = "ffffff";
    $fm_color['Entry'] = "777777";
    $fm_color['Over'] = "C0EBFD";
    $fm_color['Mark'] = "f4f58d";
    foreach($fm_color as $tag=>$color){
        $fm_color[$tag]=strtolower($color);
    }
// +--------------------------------------------------
// | File Manager Actions
// +--------------------------------------------------
if ($loggedon==$auth_pass){
    switch ($frame){
        case 1: break; // Empty Frame
        case 2: frame2(); break;
        case 3: frame3(); break;
        default:
            switch($action){
                case 1: logout(); break;
                case 2: config_form(); break;
                case 3: download(); break;
                case 4: view(); break;
                case 5: server_info(); break;
                case 6: execute_cmd(); break;
                case 7: edit_file_form(); break;
                case 8: chmod_form(); break;
                case 9: shell_form(); break;
                case 10: upload_form(); break;
                case 11: execute_file(); break;
                default: frameset();
            }
    }
} else {
    if (isset($pass)) login();
    else login_form();
}
// +--------------------------------------------------
// | Config Class
// +--------------------------------------------------
class config {
    var $data;
    var $filename;
    function config(){
        global $fm_self;
        $this->data = array(
            'lang'=>'en',
            'auth_pass'=>md5(''),
            'quota_mb'=>0,
            'upload_ext_filter'=>array(),
            'download_ext_filter'=>array(),
            'error_reporting'=>1,
            'fm_root'=>'',
            'cookie_cache_time'=>60*60*24*30, // 30 Days
            'version'=>'0.9.8'
            );
        $data = false;
        $this->filename = $fm_self;
        if (file_exists($this->filename)){
            $mat = file($this->filename);
            $objdata = trim(substr($mat[1],2));
            if (strlen($objdata)) $data = unserialize($objdata);
        }
        if (is_array($data)&&count($data)==count($this->data)) $this->data = $data;
        else $this->save();
    }
    function save(){
        $objdata = "<?php".chr(13).chr(10)."//".serialize($this->data).chr(13).chr(10);
        if (strlen($objdata)){
            if (file_exists($this->filename)){
                $mat = file($this->filename);
                if ($fh = @fopen($this->filename, "w")){
                    @fputs($fh,$objdata,strlen($objdata));
                    for ($x=2;$x<count($mat);$x++) @fputs($fh,$mat[$x],strlen($mat[$x]));
                    @fclose($fh);
                }
            }
        }
    }
    function load(){
        foreach ($this->data as $key => $val) $GLOBALS[$key] = $val;
    }
}
// +--------------------------------------------------
// | Internationalization
// +--------------------------------------------------
function et($tag){
    global $lang;

    // English - by Fabricio Seger Kolling
    $en['Version'] = 'Version';
    $en['DocRoot'] = 'Document Root';
    $en['FLRoot'] = 'File Manager Root';
    $en['Name'] = 'Name';
    $en['And'] = 'and';
    $en['Enter'] = 'Enter';
    $en['Send'] = 'Send';
    $en['Refresh'] = 'Refresh';
    $en['SaveConfig'] = 'Save Configurations';
    $en['SavePass'] = 'Save Password';
    $en['SaveFile'] = 'Save File';
    $en['Save'] = 'Save';
    $en['Leave'] = 'Leave';
    $en['Edit'] = 'Edit';
    $en['View'] = 'View';
    $en['Config'] = 'Config';
    $en['Ren'] = 'Rename';
    $en['Rem'] = 'Delete';
    $en['Compress'] = 'Compress';
    $en['Decompress'] = 'Decompress';
    $en['ResolveIDs'] = 'Resolve IDs';
    $en['Move'] = 'Move';
    $en['Copy'] = 'Copy';
    $en['ServerInfo'] = 'Server Info';
    $en['CreateDir'] = 'Create Directory';
    $en['CreateArq'] = 'Create File';
    $en['ExecCmd'] = 'Execute Command';
    $en['Upload'] = 'Upload';
    $en['UploadEnd'] = 'Upload Finished';
    $en['Perm'] = 'Perm';
    $en['Perms'] = 'Permissions';
    $en['Owner'] = 'Owner';
    $en['Group'] = 'Group';
    $en['Other'] = 'Other';
    $en['Size'] = 'Size';
    $en['Date'] = 'Date';
    $en['Type'] = 'Type';
    $en['Free'] = 'free';
    $en['Shell'] = 'Shell';
    $en['Read'] = 'Read';
    $en['Write'] = 'Write';
    $en['Exec'] = 'Execute';
    $en['Apply'] = 'Apply';
    $en['StickyBit'] = 'Sticky Bit';
    $en['Pass'] = 'Password';
    $en['Lang'] = 'Language';
    $en['File'] = 'File';
    $en['File_s'] = 'file(s)';
    $en['Dir_s'] = 'directory(s)';
    $en['To'] = 'to';
    $en['Destination'] = 'Destination';
    $en['Configurations'] = 'Configurations';
    $en['JSError'] = 'JavaScript Error';
    $en['NoSel'] = 'There are no selected itens';
    $en['SelDir'] = 'Select the destination directory on the left tree';
    $en['TypeDir'] = 'Enter the directory name';
    $en['TypeArq'] = 'Enter the file name';
    $en['TypeCmd'] = 'Enter the command';
    $en['TypeArqComp'] = 'Enter the file name.\\nThe extension will define the compression type.\\nEx:\\nnome.zip\\nnome.tar\\nnome.bzip\\nnome.gzip';
    $en['RemSel'] = 'DELETE selected itens';
    $en['NoDestDir'] = 'There is no selected destination directory';
    $en['DestEqOrig'] = 'Origin and destination directories are equal';
    $en['InvalidDest'] = 'Destination directory is invalid';
    $en['NoNewPerm'] = 'New permission not set';
    $en['CopyTo'] = 'COPY to';
    $en['MoveTo'] = 'MOVE to';
    $en['AlterPermTo'] = 'CHANGE PERMISSIONS to';
    $en['ConfExec'] = 'Confirm EXECUTE';
    $en['ConfRem'] = 'Confirm DELETE';
    $en['EmptyDir'] = 'Empty directory';
    $en['IOError'] = 'I/O Error';
    $en['FileMan'] = 'Donjo X-File Manager';
    $en['TypePass'] = 'Enter the password';
    $en['InvPass'] = 'Invalid Password';
    $en['ReadDenied'] = 'Read Access Denied';
    $en['FileNotFound'] = 'File not found';
    $en['AutoClose'] = 'Close on Complete';
    $en['OutDocRoot'] = 'File beyond DOCUMENT_ROOT';
    $en['NoCmd'] = 'Error: Command not informed';
    $en['ConfTrySave'] = 'File without write permisson.\\nTry to save anyway';
    $en['ConfSaved'] = 'Configurations saved';
    $en['PassSaved'] = 'Password saved';
    $en['FileDirExists'] = 'File or directory already exists';
    $en['NoPhpinfo'] = 'Function phpinfo disabled';
    $en['NoReturn'] = 'no return';
    $en['FileSent'] = 'File sent';
    $en['SpaceLimReached'] = 'Space limit reached';
    $en['InvExt'] = 'Invalid extension';
    $en['FileNoOverw'] = 'File could not be overwritten';
    $en['FileOverw'] = 'File overwritten';
    $en['FileIgnored'] = 'File ignored';
    $en['ChkVer'] = 'Check for new version';
    $en['ChkVerAvailable'] = 'New version, click here to begin download!!';
    $en['ChkVerNotAvailable'] = 'No new version available. :(';
    $en['ChkVerError'] = 'Connection Error.';
    $en['Website'] = 'Website';
    $en['SendingForm'] = 'Sending files, please wait';
    $en['NoFileSel'] = 'No file selected';
    $en['SelAll'] = 'All';
    $en['SelNone'] = 'None';
    $en['SelInverse'] = 'Inverse';
    $en['Selected_s'] = 'selected';
    $en['Total'] = 'total';
    $en['Partition'] = 'Partition';
    $en['RenderTime'] = 'Time to render this page';
    $en['Seconds'] = 'sec';
    $en['ErrorReport'] = 'Error Reporting';

    $lang_ = $$lang;
    if (isset($lang_[$tag])) return html_encode($lang_[$tag]);
    //else return "[$tag]"; // So we can know what is missing
    return $en[$tag];
}
// +--------------------------------------------------
// | File System
// +--------------------------------------------------
function total_size($arg) {
    $total = 0;
    if (file_exists($arg)) {
        if (is_dir($arg)) {
            $handle = opendir($arg);
            while($aux = readdir($handle)) {
                if ($aux != "." && $aux != "..") $total += total_size($arg."/".$aux);
            }
            @closedir($handle);
        } else $total = filesize($arg);
    }
    return $total;
}
function total_delete($arg) {
    if (file_exists($arg)) {
        @chmod($arg,0755);
        if (is_dir($arg)) {
            $handle = opendir($arg);
            while($aux = readdir($handle)) {
                if ($aux != "." && $aux != "..") total_delete($arg."/".$aux);
            }
            @closedir($handle);
            rmdir($arg);
        } else unlink($arg);
    }
}
function total_copy($orig,$dest) {
    $ok = true;
    if (file_exists($orig)) {
        if (is_dir($orig)) {
            mkdir($dest,0755);
            $handle = opendir($orig);
            while(($aux = readdir($handle))&&($ok)) {
                if ($aux != "." && $aux != "..") $ok = total_copy($orig."/".$aux,$dest."/".$aux);
            }
            @closedir($handle);
        } else $ok = copy((string)$orig,(string)$dest);
    }
    return $ok;
}
function total_move($orig,$dest) {
    // Just why doesn't it has a MOVE alias?!
    return rename((string)$orig,(string)$dest);
}
function download(){
    global $current_dir,$filename;
    $file = $current_dir.$filename;
    if(file_exists($file)){
        $is_denied = false;
        foreach($download_ext_filter as $key=>$ext){
            if (eregi($ext,$filename)){
                $is_denied = true;
                break;
            }
        }
        if (!$is_denied){
            $size = filesize($file);
            header("Content-Type: application/save");
            header("Content-Length: $size");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Transfer-Encoding: binary");
            if ($fh = fopen("$file", "rb")){
                fpassthru($fh);
                fclose($fh);
            } else alert(et('ReadDenied').": ".$file);
        } else alert(et('ReadDenied').": ".$file);
    } else alert(et('FileNotFound').": ".$file);
}
function execute_cmd(){
    global $cmd;
    header("Content-type: text/plain");
    if (strlen($cmd)){
        echo "# ".$cmd."\n";
        exec($cmd,$mat);
        if (count($mat)) echo trim(implode("\n",$mat));
        else echo "exec(\"$cmd\") ".et('NoReturn')."...";
    } else echo et('NoCmd');
}
function execute_file(){
    global $current_dir,$filename;
    header("Content-type: text/plain");
    $file = $current_dir.$filename;
    if(file_exists($file)){
        echo "# ".$file."\n";
        exec($file,$mat);
        if (count($mat)) echo trim(implode("\n",$mat));
    } else alert(et('FileNotFound').": ".$file);
}
function save_upload($temp_file,$filename,$dir_dest) {
    global $upload_ext_filter;
    $filename = remove_special_chars($filename);
    $file = $dir_dest.$filename;
    $filesize = filesize($temp_file);
    $is_denied = false;
    foreach($upload_ext_filter as $key=>$ext){
        if (eregi($ext,$filename)){
            $is_denied = true;
            break;
        }
    }
    if (!$is_denied){
        if (!check_limit($filesize)){
            if (file_exists($file)){
                if (unlink($file)){
                    if (copy($temp_file,$file)){
                        @chmod($file,0755);
                        $out = 6;
                    } else $out = 2;
                } else $out = 5;
            } else {
                if (copy($temp_file,$file)){
                    @chmod($file,0755);
                    $out = 1;
                } else $out = 2;
            }
        } else $out = 3;
    } else $out = 4;
    return $out;
}
function zip_extract(){
  global $cmd_arg,$current_dir,$islinux;
  $zip = zip_open($current_dir.$cmd_arg);
  if ($zip) {
    while ($zip_entry = zip_read($zip)) {
        if (zip_entry_filesize($zip_entry)) {
            $complete_path = $path.dirname(zip_entry_name($zip_entry));
            $complete_name = $path.zip_entry_name($zip_entry);
            if(!file_exists($complete_path)) {
                $tmp = '';
                foreach(explode('/',$complete_path) AS $k) {
                    $tmp .= $k.'/';
                    if(!file_exists($tmp)) {
                        @mkdir($current_dir.$tmp, 0755);
                    }
                }
            }
            if (zip_entry_open($zip, $zip_entry, "r")) {
                if ($fd = fopen($current_dir.$complete_name, 'w')){
                    fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                    fclose($fd);
                } else echo "fopen($current_dir.$complete_name) error<br>";
                zip_entry_close($zip_entry);
            } else echo "zip_entry_open($zip,$zip_entry) error<br>";
        }
    }
    zip_close($zip);
  }
}
// +--------------------------------------------------
// | Data Formating
// +--------------------------------------------------
function html_encode($str){
	global $charSet;
	$str = preg_replace(array('/&/', '/</', '/>/', '/"/'), array('&amp;', '&lt;', '&gt;', '&quot;'), $str);  // Bypass PHP to allow any charset!!
    $str = htmlentities($str, ENT_QUOTES, $charSet, false);
	return $str;
}
function rep($x,$y){
  if ($x) {
    $aux = "";
    for ($a=1;$a<=$x;$a++) $aux .= $y;
    return $aux;
  } else return "";
}
function str_zero($arg1,$arg2){
    if (strstr($arg1,"-") == false){
        $aux = intval($arg2) - strlen($arg1);
        if ($aux) return rep($aux,"0").$arg1;
        else return $arg1;
    } else {
        return "[$arg1]";
    }
}
function replace_double($sub,$str){
    $out=str_replace($sub.$sub,$sub,$str);
    while ( strlen($out) != strlen($str) ){
        $str=$out;
        $out=str_replace($sub.$sub,$sub,$str);
    }
    return $out;
}
function remove_special_chars($str){
    $str = trim($str);
    $str = strtr($str,"¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ!@#%&*()[]{}+=?",
                      "YuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy_______________");
    $str = str_replace("..","",str_replace("/","",str_replace("\\","",str_replace("\$","",$str))));
    return $str;
}
function format_path($str){
    global $islinux;
    $str = trim($str);
    $str = str_replace("..","",str_replace("\\","/",str_replace("\$","",$str)));
    $done = false;
    while (!$done) {
        $str2 = str_replace("//","/",$str);
        if (strlen($str) == strlen($str2)) $done = true;
        else $str = $str2;
    }
    $tam = strlen($str);
    if ($tam){
        $last_char = $tam - 1;
        if ($str[$last_char] != "/") $str .= "/";
        if (!$islinux) $str = ucfirst($str);
    }
    return $str;
}
function array_csort() {
  $args = func_get_args();
  $marray = array_shift($args);
  $msortline = "return(array_multisort(";
   foreach ($args as $arg) {
       $i++;
       if (is_string($arg)) {
          foreach ($marray as $row) {
               $sortarr[$i][] = $row[$arg];
           }
       } else {
          $sortarr[$i] = $arg;
       }
       $msortline .= "\$sortarr[".$i."],";
   }
   $msortline .= "\$marray));";
   eval($msortline);
   return $marray;
}
function show_perms( $P ) {
   $sP = "<b>";
   if($P & 0x1000) $sP .= 'p';            // FIFO pipe
   elseif($P & 0x2000) $sP .= 'c';        // Character special
   elseif($P & 0x4000) $sP .= 'd';        // Directory
   elseif($P & 0x6000) $sP .= 'b';        // Block special
   elseif($P & 0x8000) $sP .= '&minus;';  // Regular
   elseif($P & 0xA000) $sP .= 'l';        // Symbolic Link
   elseif($P & 0xC000) $sP .= 's';        // Socket
   else $sP .= 'u';                       // UNKNOWN
   $sP .= "</b>";
   // owner - group - others
   $sP .= (($P & 0x0100) ? 'r' : '&minus;') . (($P & 0x0080) ? 'w' : '&minus;') . (($P & 0x0040) ? (($P & 0x0800) ? 's' : 'x' ) : (($P & 0x0800) ? 'S' : '&minus;'));
   $sP .= (($P & 0x0020) ? 'r' : '&minus;') . (($P & 0x0010) ? 'w' : '&minus;') . (($P & 0x0008) ? (($P & 0x0400) ? 's' : 'x' ) : (($P & 0x0400) ? 'S' : '&minus;'));
   $sP .= (($P & 0x0004) ? 'r' : '&minus;') . (($P & 0x0002) ? 'w' : '&minus;') . (($P & 0x0001) ? (($P & 0x0200) ? 't' : 'x' ) : (($P & 0x0200) ? 'T' : '&minus;'));
   return $sP;
}
function format_size($arg) {
    if ($arg>0){
        $j = 0;
        $ext = array(" bytes"," Kb"," Mb"," Gb"," Tb");
        while ($arg >= pow(1024,$j)) ++$j;
        return round($arg / pow(1024,$j-1) * 100) / 100 . $ext[$j-1];
    } else return "0 bytes";
}
function get_size($file) {
    return format_size(filesize($file));
}
function check_limit($new_filesize=0) {
    global $fm_current_root;
    global $quota_mb;
    if($quota_mb){
        $total = total_size($fm_current_root);
        if (floor(($total+$new_filesize)/(1024*1024)) > $quota_mb) return true;
    }
    return false;
}
function get_user($arg) {
    global $mat_passwd;
    $aux = "x:".trim($arg).":";
    for($x=0;$x<count($mat_passwd);$x++){
        if (strstr($mat_passwd[$x],$aux)){
         $mat = explode(":",$mat_passwd[$x]);
         return $mat[0];
        }
    }
    return $arg;
}
function get_group($arg) {
    global $mat_group;
    $aux = "x:".trim($arg).":";
    for($x=0;$x<count($mat_group);$x++){
        if (strstr($mat_group[$x],$aux)){
         $mat = explode(":",$mat_group[$x]);
         return $mat[0];
        }
    }
    return $arg;
}
function uppercase($str){
	global $charset;
    return mb_strtoupper($str, $charset);
}
function lowercase($str){
	global $charset;
    return mb_strtolower($str, $charset);
}
// +--------------------------------------------------
// | Interface
// +--------------------------------------------------
function html_header($header=""){
    global $charset,$fm_color;
    echo "
	<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\">
    <head>    
    <meta http-equiv=\"content-type\" content=\"text/html; charset=".$charset."\" />	
	<title>Donjo File Manager</title>
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        function Is(){
            this.appname = navigator.appName;
            this.appversion = navigator.appVersion;
            this.platform = navigator.platform;
            this.useragent = navigator.userAgent.toLowerCase();
            this.ie = ( this.appname == 'Microsoft Internet Explorer' );
            if (( this.useragent.indexOf( 'mac' ) != -1 ) || ( this.platform.indexOf( 'mac' ) != -1 )){
                this.sisop = 'mac';
            } else if (( this.useragent.indexOf( 'windows' ) != -1 ) || ( this.platform.indexOf( 'win32' ) != -1 )){
                this.sisop = 'windows';
            } else if (( this.useragent.indexOf( 'inux' ) != -1 ) || ( this.platform.indexOf( 'linux' ) != -1 )){
                this.sisop = 'linux';
            }
        }
        var is = new Is();
        function enterSubmit(keypressEvent,submitFunc){
            var kCode = (is.ie) ? keypressEvent.keyCode : keypressEvent.which
            if( kCode == 13) eval(submitFunc);
        }
        function getCookieVal (offset) {
            var endstr = document.cookie.indexOf (';', offset);
            if (endstr == -1) endstr = document.cookie.length;
            return unescape(document.cookie.substring(offset, endstr));
        }
        function getCookie (name) {
            var arg = name + '=';
            var alen = arg.length;
            var clen = document.cookie.length;
            var i = 0;
            while (i < clen) {
                var j = i + alen;
                if (document.cookie.substring(i, j) == arg) return getCookieVal (j);
                i = document.cookie.indexOf(' ', i) + 1;
                if (i == 0) break;
            }
            return null;
        }
        function setCookie (name, value, expires) {
            var argv = setCookie.arguments;
            var argc = setCookie.arguments.length;
            var expires = (argc > 2) ? argv[2] : null;
            var path = (argc > 3) ? argv[3] : null;
            var domain = (argc > 4) ? argv[4] : null;
            var secure = (argc > 5) ? argv[5] : false;
            document.cookie = name + '=' + escape (value) +
            ((expires == null) ? '' : ('; expires=' + expires.toGMTString())) +
            ((path == null) ? '' : ('; path=' + path)) +
            ((domain == null) ? '' : ('; domain=' + domain)) +
            ((secure == true) ? '; secure' : '');
        }
        function delCookie (name) {
            var exp = new Date();
            exp.setTime (exp.getTime() - 1);
            var cval = getCookie (name);
            document.cookie = name + '=' + cval + '; expires=' + exp.toGMTString();
        }
        var frameWidth, frameHeight;
        function getFrameSize(){
            if (self.innerWidth){
                frameWidth = self.innerWidth;
                frameHeight = self.innerHeight;
            }else if (document.documentElement && document.documentElement.clientWidth){
                frameWidth = document.documentElement.clientWidth;
                frameHeight = document.documentElement.clientHeight;
            }else if (document.body){
                frameWidth = document.body.clientWidth;
                frameHeight = document.body.clientHeight;
            }else return false;
            return true;
        }
        getFrameSize();
    //-->
    </script>
    $header
    </head>
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        var W = screen.width;
        var H = screen.height;
        var FONTSIZE = 0;
        switch (W){
            case 640:
                FONTSIZE = 8;
            break;
            case 800:
                FONTSIZE = 10;
            break;
            case 1024:
                FONTSIZE = 12;
            break;
            default:
                FONTSIZE = 14;
            break;
        }
    ";
    echo replace_double(" ",str_replace(chr(13),"",str_replace(chr(10),"","
        document.writeln('
        <style type=\"text/css\">
		.buto{
            background-color: #111111;
		}
		
        body {
            font-family : Courier;
            font-size: '+FONTSIZE+'px;
            font-weight : normal;
            color: #".$fm_color['Text'].";
            background-color: #".$fm_color['Bg'].";
        }
        table {
            font-family : Courier;
            font-size: '+FONTSIZE+'px;
            font-weight : normal;
            color: #".$fm_color['Text'].";
            cursor: default;
        }
        input {
            font-family : Courier;
            font-size: '+FONTSIZE+'px;
            font-weight : normal;
            color: #".$fm_color['Text'].";
        }
        textarea {
            font-family : Courier;
            font-size: 12px;
            font-weight : normal;
            color: #".$fm_color['Text'].";
        }
        a {
            font-family : Courier;
            font-size : '+FONTSIZE+'px;
            font-weight : bold;
            text-decoration: none;
            color: #".$fm_color['Text'].";
        }
        a:link {
            color: #".$fm_color['Text'].";
        }
        a:visited {
            color: #".$fm_color['Text'].";
        }
        a:hover {
            color: #".$fm_color['Link'].";
        }
        a:active {
            color: #".$fm_color['Text'].";
        }
        tr.entryUnselected {
            background-color: #".$fm_color['Entry'].";
        }
        tr.entryUnselected:hover {
            background-color: #".$fm_color['Over'].";
        }
        tr.entrySelected {
            background-color: #".$fm_color['Mark'].";
        }
        </style>
        ');
    ")));
    echo "
    //-->
    </script>
    ";
}
function reloadframe($ref,$frame_number,$Plus=""){
    global $current_dir,$path_info;
    echo "
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        ".$ref.".frame".$frame_number.".location.href='".$path_info["basename"]."?frame=".$frame_number."&current_dir=".$current_dir.$Plus."';
    //-->
    </script>
    ";
}
function alert($arg){
    echo "
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        alert('$arg');
    //-->
    </script>
    ";
}
function tree($dir_before,$dir_current,$indice){
    global $fm_current_root, $current_dir, $islinux;
    global $expanded_dir_list;
    $indice++;
    $num_dir = 0;
    $dir_name = str_replace($dir_before,"",$dir_current);
    $dir_before = str_replace("//","/",$dir_before);
    $dir_current = str_replace("//","/",$dir_current);
    $is_denied = false;
    if ($islinux) {
        $denied_list = "/proc#/dev";
        $mat = explode("#",$denied_list);
        foreach($mat as $key => $val){
            if ($dir_current == $val){
                $is_denied = true;
                break;
            }
        }
        unset($mat);
    }
    if (!$is_denied){
        if ($handle = @opendir($dir_current)){
            // Permitido
            while ($file = readdir($handle)){
                if ($file != "." && $file != ".." && is_dir("$dir_current/$file"))
                    $mat_dir[] = $file;
            }
            @closedir($handle);
            if (count($mat_dir)){
                sort($mat_dir,SORT_STRING);
                // with Sub-dir
                if ($indice != 0){
                    for ($aux=1;$aux<$indice;$aux++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                if ($dir_before != $dir_current){
                    if (strstr($expanded_dir_list,":$dir_current/$dir_name")) $op_str = "[–]";
                    else $op_str = "[+]";
                    echo "<nobr><a href=\"JavaScript:go_dir('$dir_current/$dir_name')\">$op_str</a> <a href=\"JavaScript:go('$dir_current')\">$dir_name</a></nobr><br>\n";
                } else {
                    echo "<nobr><a href=\"JavaScript:go('$dir_current')\">$fm_current_root</a></nobr><br>\n";
                }
                for ($x=0;$x<count($mat_dir);$x++){
                    if (($dir_before == $dir_current)||(strstr($expanded_dir_list,":$dir_current/$dir_name"))){
                        tree($dir_current."/",$dir_current."/".$mat_dir[$x],$indice);
                    } else flush();
                }
            } else {
              // no Sub-dir
              if ($dir_before != $dir_current){
                for ($aux=1;$aux<$indice;$aux++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<b>[&nbsp;&nbsp;]</b>";
                echo "<nobr><a href=\"JavaScript:go('$dir_current')\"> $dir_name</a></nobr><br>\n";
              } else {
                echo "<nobr><a href=\"JavaScript:go('$dir_current')\"> $fm_current_root</a></nobr><br>\n";
              }
            }
        } else {
            // denied
            if ($dir_before != $dir_current){
                for ($aux=1;$aux<$indice;$aux++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<b>[&nbsp;&nbsp;]</b>";
                echo "<nobr><a href=\"JavaScript:go('$dir_current')\"><font color=red> $dir_name</font></a></nobr><br>\n";
            } else {
                echo "<nobr><a href=\"JavaScript:go('$dir_current')\"><font color=red> $fm_current_root</font></a></nobr><br>\n";
            }

        }
    } else {
        // denied
        if ($dir_before != $dir_current){
            for ($aux=1;$aux<$indice;$aux++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "<b>[&nbsp;&nbsp;]</b>";
            echo "<nobr><a href=\"JavaScript:go('$dir_current')\"><font color=red> $dir_name</font></a></nobr><br>\n";
        } else {
            echo "<nobr><a href=\"JavaScript:go('$dir_current')\"><font color=red> $fm_current_root</font></a></nobr><br>\n";
        }
    }
}
function show_tree(){
    global $fm_current_root,$path_info,$setflag,$islinux,$cookie_cache_time;
    html_header("
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        function saveFrameSize(){
            if (getFrameSize()){
                var exp = new Date();
                exp.setTime(exp.getTime()+$cookie_cache_time);
                setCookie('leftFrameWidth',frameWidth,exp);
            }
        }
        window.onresize = saveFrameSize;
    //-->
    </script>");
    echo "<body marginwidth=\"0\" marginheight=\"0\">\n";
    echo "
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        // Disable text selection, binding the onmousedown, but not for some elements, it must work.
        function disableTextSelection(e){
			var type = String(e.target.type);
			return (type.indexOf('select') != -1 || type.indexOf('button') != -1 || type.indexOf('input') != -1 || type.indexOf('radio') != -1);
		}
        function enableTextSelection(){return true}
        if (is.ie) document.onselectstart=new Function('return false')
        else {
            document.body.onmousedown=disableTextSelection
            document.body.onclick=enableTextSelection
        }
        var flag = ".(($setflag)?"true":"false")."
        function set_flag(arg) {
            flag = arg;
        }
        function go_dir(arg) {
            var setflag;
            setflag = (flag)?1:0;
            document.location.href='".addslashes($path_info["basename"])."?frame=2&setflag='+setflag+'&current_dir=".addslashes($current_dir)."&ec_dir='+arg;
        }
        function go(arg) {
            if (flag) {
                parent.frame3.set_dir_dest(arg+'/');
                flag = false;
            } else {
                parent.frame3.location.href='".addslashes($path_info["basename"])."?frame=3&current_dir='+arg+'/';
            }
        }
        function set_fm_current_root(arg){
            document.location.href='".addslashes($path_info["basename"])."?frame=2&set_fm_current_root='+escape(arg);
        }
        function atualizar(){
            document.location.href='".addslashes($path_info["basename"])."?frame=2';
        }
    //-->
    </script>
    ";
    echo "<table width=\"100%\" height=\"100%\" border=0 cellspacing=0 cellpadding=5>\n";
    echo "<form><tr valign=top height=10><td>";
    if (!$islinux){
        echo "<select name=drive onchange=\"set_fm_current_root(this.value)\">";
        $aux="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for($x=0;$x<strlen($aux);$x++){
			if ($handle = opendir($aux[$x].":/")){
    			@closedir($handle);
	            if (strstr(uppercase($fm_current_root),$aux[$x].":/")) $is_sel="selected";
	            else $is_sel="";
	            echo "<option $is_sel value=\"".$aux[$x].":/\">".$aux[$x].":/";
			}
        }
        echo "</select> ";
    }
    echo "<input type=button class=buto value=".et('Refresh')." onclick=\"atualizar()\"></tr></form>";
    echo "<tr valign=top><td>";
            clearstatcache();
            tree($fm_current_root,$fm_current_root,-1,0);
    echo "</td></tr>";
    echo "
        <form name=\"login_form\" action=\"".$path_info["basename"]."\" method=\"post\" target=\"_parent\">
        <input type=hidden name=action value=1>
        <tr>
        <td height=10 colspan=2><input type=submit class=buto value=\"".et('Leave')."\">
        </tr>
        </form>
    ";
    echo "</table>\n";
    echo "</body>\n</html>";
}
function getmicrotime(){
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
function dir_list_form() {
    global $fm_current_root,$current_dir,$quota_mb,$resolveIDs,$order_dir_list_by,$islinux,$cmd_name,$ip,$path_info,$fm_color;
    $ti = getmicrotime();
    clearstatcache();
    $out = "<table border=0 cellspacing=1 cellpadding=4 width=\"100%\" bgcolor=\"#eeeeee\">\n";
    if ($opdir = @opendir($current_dir)) {
        $has_files = false;
        $entry_count = 0;
        $total_size = 0;
        $entry_list = array();
        while ($file = readdir($opdir)) {
          if (($file != ".")&&($file != "..")){
			$entry_list[$entry_count]["size"] = 0;
			$entry_list[$entry_count]["sizet"] = 0;
			$entry_list[$entry_count]["type"] = "none";
            if (is_file($current_dir.$file)){
                $ext = lowercase(strrchr($file,"."));
                $entry_list[$entry_count]["type"] = "file";
                // Função filetype() returns only "file"...
                $entry_list[$entry_count]["size"] = filesize($current_dir.$file);
                $entry_list[$entry_count]["sizet"] = format_size($entry_list[$entry_count]["size"]);
                if (strstr($ext,".")){
                    $entry_list[$entry_count]["ext"] = $ext;
                    $entry_list[$entry_count]["extt"] = $ext;
                } else {
                    $entry_list[$entry_count]["ext"] = "";
                    $entry_list[$entry_count]["extt"] = "&nbsp;";
                }
                $has_files = true;
            } elseif (is_dir($current_dir.$file)) {
                // Recursive directory size disabled
                // $entry_list[$entry_count]["size"] = total_size($current_dir.$file);
                $entry_list[$entry_count]["size"] = 0;
                $entry_list[$entry_count]["sizet"] = "&nbsp;";
                $entry_list[$entry_count]["type"] = "dir";
            }
            $entry_list[$entry_count]["name"] = $file;
            $entry_list[$entry_count]["date"] = date("Ymd", filemtime($current_dir.$file));
            $entry_list[$entry_count]["time"] = date("his", filemtime($current_dir.$file));
            $entry_list[$entry_count]["datet"] = date("d/m/y h:i", filemtime($current_dir.$file));
            if ($islinux && $resolveIDs){
                $entry_list[$entry_count]["p"] = show_perms(fileperms($current_dir.$file));
                $entry_list[$entry_count]["u"] = get_user(fileowner($current_dir.$file));
                $entry_list[$entry_count]["g"] = get_group(filegroup($current_dir.$file));
            } else {
                $entry_list[$entry_count]["p"] = base_convert(fileperms($current_dir.$file),10,8);
                $entry_list[$entry_count]["p"] = substr($entry_list[$entry_count]["p"],strlen($entry_list[$entry_count]["p"])-3);
                $entry_list[$entry_count]["u"] = fileowner($current_dir.$file);
                $entry_list[$entry_count]["g"] = filegroup($current_dir.$file);
            }
            $total_size += $entry_list[$entry_count]["size"];
            $entry_count++;
          }
        }
        @closedir($opdir);

        if($entry_count){
            $or1="1A";
            $or2="2D";
            $or3="3A";
            $or4="4A";
            $or5="5A";
            $or6="6D";
            $or7="7D";
            switch($order_dir_list_by){
                case "1A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or1="1D"; break;
                case "1D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_DESC); $or1="1A"; break;
                case "2A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"p",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC); $or2="2D"; break;
                case "2D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"p",SORT_STRING,SORT_DESC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC); $or2="2A"; break;
                case "3A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC); $or3="3D"; break;
                case "3D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_DESC,"g",SORT_STRING,SORT_ASC); $or3="3A"; break;
                case "4A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_ASC,"u",SORT_STRING,SORT_DESC); $or4="4D"; break;
                case "4D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"g",SORT_STRING,SORT_DESC,"u",SORT_STRING,SORT_DESC); $or4="4A"; break;
                case "5A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"size",SORT_NUMERIC,SORT_ASC); $or5="5D"; break;
                case "5D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"size",SORT_NUMERIC,SORT_DESC); $or5="5A"; break;
                case "6A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"date",SORT_STRING,SORT_ASC,"time",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or6="6D"; break;
                case "6D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"date",SORT_STRING,SORT_DESC,"time",SORT_STRING,SORT_DESC,"name",SORT_STRING,SORT_ASC); $or6="6A"; break;
                case "7A": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"ext",SORT_STRING,SORT_ASC,"name",SORT_STRING,SORT_ASC); $or7="7D"; break;
                case "7D": $entry_list = array_csort ($entry_list,"type",SORT_STRING,SORT_ASC,"ext",SORT_STRING,SORT_DESC,"name",SORT_STRING,SORT_ASC); $or7="7A"; break;
            }
        }
        $out .= "
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
        function go(arg) {
            document.location.href='".addslashes($path_info["basename"])."?frame=3&current_dir=".addslashes($current_dir)."'+arg+'/';
        }
        function resolveIDs() {
            document.location.href='".addslashes($path_info["basename"])."?frame=3&set_resolveIDs=1&current_dir=".addslashes($current_dir)."';
        }
        var entry_list = new Array();
        // Custom object constructor
        function entry(name, type, size, selected){
            this.name = name;
            this.type = type;
            this.size = size;
            this.selected = false;
        }
        // Declare entry_list for selection procedures";
        foreach ($entry_list as $i=>$data){
            $out .= "\nentry_list['entry$i'] = new entry('".addslashes($data["name"])."', '".$data["type"]."', ".$data["size"].", false);";
        }
        $out .= "
        // Select/Unselect Rows OnClick/OnMouseOver
        var lastRows = new Array(null,null);
        function selectEntry(Row, Action){
            if (multipleSelection){
                // Avoid repeated onmouseover events from same Row ( cell transition )
                if (Row != lastRows[0]){
                    if (Action == 'over') {
                        if (entry_list[Row.id].selected){
                            if (unselect(entry_list[Row.id])) {
                                Row.className = 'entryUnselected';
                            }
                            // Change the last Row when you change the movement orientation
                            if (lastRows[0] != null && lastRows[1] != null){
                                var LastRowID = lastRows[0].id;
                                if (Row.id == lastRows[1].id){
                                    if (unselect(entry_list[LastRowID])) {
                                        lastRows[0].className = 'entryUnselected';
                                    }
                                }
                            }
                        } else {
                            if (select(entry_list[Row.id])){
                                Row.className = 'entrySelected';
                            }
                            // Change the last Row when you change the movement orientation
                            if (lastRows[0] != null && lastRows[1] != null){
                                var LastRowID = lastRows[0].id;
                                if (Row.id == lastRows[1].id){
                                    if (select(entry_list[LastRowID])) {
                                        lastRows[0].className = 'entrySelected';
                                    }
                                }
                            }
                        }
                        lastRows[1] = lastRows[0];
                        lastRows[0] = Row;
                    }
                }
            } else {
                if (Action == 'click') {
                    var newClassName = null;
                    if (entry_list[Row.id].selected){
                        if (unselect(entry_list[Row.id])) newClassName = 'entryUnselected';
                    } else {
                        if (select(entry_list[Row.id])) newClassName = 'entrySelected';
                    }
                    if (newClassName) {
                        lastRows[0] = lastRows[1] = Row;
                        Row.className = newClassName;
                    }
                }
            }
            return true;
        }
        // Disable text selection and bind multiple selection flag
        var multipleSelection = false;
        if (is.ie) {
            document.onselectstart=new Function('return false');
            document.onmousedown=switch_flag_on;
            document.onmouseup=switch_flag_off;
            // Event mouseup is not generated over scrollbar.. curiously, mousedown is.. go figure.
            window.onscroll=new Function('multipleSelection=false');
            window.onresize=new Function('multipleSelection=false');
        } else {
            if (document.layers) window.captureEvents(Event.MOUSEDOWN);
            if (document.layers) window.captureEvents(Event.MOUSEUP);
            window.onmousedown=switch_flag_on;
            window.onmouseup=switch_flag_off;
        }
        // Using same function and a ternary operator couses bug on double click
        function switch_flag_on(e) {
            if (is.ie){
                multipleSelection = (event.button == 1);
            } else {
                multipleSelection = (e.which == 1);
            }
			var type = String(e.target.type);
			return (type.indexOf('select') != -1 || type.indexOf('button') != -1 || type.indexOf('input') != -1 || type.indexOf('radio') != -1);
        }
        function switch_flag_off(e) {
            if (is.ie){
                multipleSelection = (event.button != 1);
            } else {
                multipleSelection = (e.which != 1);
            }
            lastRows[0] = lastRows[1] = null;
            update_sel_status();
            return false;
        }
        var total_dirs_selected = 0;
        var total_files_selected = 0;
        function unselect(Entry){
            if (!Entry.selected) return false;
            Entry.selected = false;
            sel_totalsize -= Entry.size;
            if (Entry.type == 'dir') total_dirs_selected--;
            else total_files_selected--;
            return true;
        }
        function select(Entry){
            if(Entry.selected) return false;
            Entry.selected = true;
            sel_totalsize += Entry.size;
            if(Entry.type == 'dir') total_dirs_selected++;
            else total_files_selected++;
            return true;
        }
        function is_anything_selected(){
            var selected_dir_list = new Array();
            var selected_file_list = new Array();
            for(var x=0;x<".(integer)count($entry_list).";x++){
                if(entry_list['entry'+x].selected){
                    if(entry_list['entry'+x].type == 'dir') selected_dir_list.push(entry_list['entry'+x].name);
                    else selected_file_list.push(entry_list['entry'+x].name);
                }
            }
            document.form_action.selected_dir_list.value = selected_dir_list.join('<|*|>');
            document.form_action.selected_file_list.value = selected_file_list.join('<|*|>');
            return (total_dirs_selected>0 || total_files_selected>0);
        }
        function format_size (arg) {
            var resul = '';
            if (arg>0){
                var j = 0;
                var ext = new Array(' bytes',' Kb',' Mb',' Gb',' Tb');
                while (arg >= Math.pow(1024,j)) ++j;
                resul = (Math.round(arg/Math.pow(1024,j-1)*100)/100) + ext[j-1];
            } else resul = 0;
            return resul;
        }
        var sel_totalsize = 0;
        function update_sel_status(){
            var t = total_dirs_selected+' ".et('Dir_s')." ".et('And')." '+total_files_selected+' ".et('File_s')." ".et('Selected_s')." = '+format_size(sel_totalsize);
            //document.getElementById(\"sel_status\").innerHTML = t;
            window.status = t;
        }
        // Select all/none/inverse
        function selectANI(Butt){
        	cancel_copy_move();
            for(var x=0;x<". (integer)count($entry_list).";x++){
                var Row = document.getElementById('entry'+x);
                var newClassName = null;
                switch (Butt.value){
                    case '".et('SelAll')."':
                        if (select(entry_list[Row.id])) newClassName = 'entrySelected';
                    break;
                    case '".et('SelNone')."':
                        if (unselect(entry_list[Row.id])) newClassName = 'entryUnselected';
                    break;
                    case '".et('SelInverse')."':
                        if (entry_list[Row.id].selected){
                            if (unselect(entry_list[Row.id])) newClassName = 'entryUnselected';
                        } else {
                            if (select(entry_list[Row.id])) newClassName = 'entrySelected';
                        }
                    break;
                }
                if (newClassName) {
                    Row.className = newClassName;
                }
            }
            if (Butt.value == '".et('SelAll')."'){
                for(var i=0;i<2;i++){
                    document.getElementById('ANI'+i).value='".et('SelNone')."';
                }
            } else if (Butt.value == '".et('SelNone')."'){
                for(var i=0;i<2;i++){
                    document.getElementById('ANI'+i).value='".et('SelAll')."';
                }
            }
            update_sel_status();
            return true;
        }
        function download(arg){
            parent.frame1.location.href='".addslashes($path_info["basename"])."?action=3&current_dir=".addslashes($current_dir)."&filename='+escape(arg);
        }
        function upload(){
            var w = 400;
            var h = 250;
            window.open('".addslashes($path_info["basename"])."?action=10&current_dir=".addslashes($current_dir)."', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
        }
        function execute_cmd(){
            var arg = prompt('".et('TypeCmd').".');
            if(arg.length>0){
                if(confirm('".et('ConfExec')." \\' '+arg+' \\' ?')) {
                    var w = 800;
                    var h = 600;
                    window.open('".addslashes($path_info["basename"])."?action=6&current_dir=".addslashes($current_dir)."&cmd='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
            }
        }
        function decompress(arg){
            if(confirm('".uppercase(et('Decompress'))." \\' '+arg+' \\' ?')) {
                document.form_action.action.value = 72;
                document.form_action.cmd_arg.value = arg;
                document.form_action.submit();
            }
        }
        function execute_file(arg){
            if(arg.length>0){
                if(confirm('".et('ConfExec')." \\' '+arg+' \\' ?')) {
                    var w = 800;
                    var h = 600;
                    window.open('".addslashes($path_info["basename"])."?action=11&current_dir=".addslashes($current_dir)."&filename='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
                }
            }
        }
        function edit_file(arg){
            var w = 1024;
            var h = 768;
            // if(confirm('".uppercase(et('Edit'))." \\' '+arg+' \\' ?'))
            window.open('".addslashes($path_info["basename"])."?action=7&current_dir=".addslashes($current_dir)."&filename='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
        }
        function config(){
            var w = 650;
            var h = 400;
            window.open('".addslashes($path_info["basename"])."?action=2', 'win_config', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
        }
        function server_info(arg){
            var w = 800;
            var h = 600;
            window.open('".addslashes($path_info["basename"])."?action=5', 'win_serverinfo', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
        }
        function shell(){
            var w = 800;
            var h = 600;
            window.open('".addslashes($path_info["basename"])."?action=9', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
        }
        function view(arg){
            var w = 800;
            var h = 600;
            if(confirm('".uppercase(et('View'))." \\' '+arg+' \\' ?')) window.open('".addslashes($path_info["basename"])."?action=4&current_dir=".addslashes($current_dir)."&filename='+escape(arg), '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=yes,toolbar=no,menubar=no,location=yes');
        }
        function rename(arg){
            var nome = '';
            if (nome = prompt('".uppercase(et('Ren'))." \\' '+arg+' \\' ".et('To')." ...')) document.location.href='".addslashes($path_info["basename"])."?frame=3&action=3&current_dir=".addslashes($current_dir)."&old_name='+escape(arg)+'&new_name='+escape(nome);
        }
        function set_dir_dest(arg){
            document.form_action.dir_dest.value=arg;
            if (document.form_action.action.value.length>0) test(document.form_action.action.value);
            else alert('".et('JSError').".');
        }
        function sel_dir(arg){
            document.form_action.action.value = arg;
            document.form_action.dir_dest.value='';
            if (!is_anything_selected()) alert('".et('NoSel').".');
            else {
                if (!getCookie('sel_dir_warn')) {
                    //alert('".et('SelDir').".');
                    document.cookie='sel_dir_warn'+'='+escape('true')+';';
                }
                set_sel_dir_warn(true);
                parent.frame2.set_flag(true);
            }
        }
		function set_sel_dir_warn(b){
        	document.getElementById(\"sel_dir_warn\").style.display=(b?'':'none');
		}
		function cancel_copy_move(){
           	set_sel_dir_warn(false);
           	parent.frame2.set_flag(false);
		}
        function chmod_form(){
            cancel_copy_move();
            document.form_action.dir_dest.value='';
            document.form_action.chmod_arg.value='';
            if (!is_anything_selected()) alert('".et('NoSel').".');
            else {
                var w = 280;
                var h = 180;
                window.open('".addslashes($path_info["basename"])."?action=8', '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=no,resizable=yes,status=no,toolbar=no,menubar=no,location=no');
            }
        }
        function set_chmod_arg(arg){
            cancel_copy_move();
            if (!is_anything_selected()) alert('".et('NoSel').".');
            else {
            	document.form_action.dir_dest.value='';
            	document.form_action.chmod_arg.value=arg;
            	test(9);
			}
        }
        function test_action(){
            if (document.form_action.action.value != 0) return true;
            else return false;
        }
        function test_prompt(arg){
        	cancel_copy_move();
			var erro='';
            var conf='';
            if (arg == 1){
                document.form_action.cmd_arg.value = prompt('".et('TypeDir').".');
            } else if (arg == 2){
                document.form_action.cmd_arg.value = prompt('".et('TypeArq').".');
            } else if (arg == 71){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else document.form_action.cmd_arg.value = prompt('".et('TypeArqComp')."');
            }
            if (erro!=''){
                document.form_action.cmd_arg.focus();
                alert(erro);
            } else if(document.form_action.cmd_arg.value.length>0) {
                document.form_action.action.value = arg;
                document.form_action.submit();
            }
        }
        function strstr(haystack,needle){
            var index = haystack.indexOf(needle);
            return (index==-1)?false:index;
        }
        function valid_dest(dest,orig){
            return (strstr(dest,orig)==false)?true:false;
        }
        // ArrayAlert - Selection debug only
        function aa(){
            var str = 'selected_dir_list:\\n';
            for (x=0;x<selected_dir_list.length;x++){
                str += selected_dir_list[x]+'\\n';
            }
            str += '\\nselected_file_list:\\n';
            for (x=0;x<selected_file_list.length;x++){
                str += selected_file_list[x]+'\\n';
            }
            alert(str);
        }
        function test(arg){
        	cancel_copy_move();
            var erro='';
            var conf='';
            if (arg == 4){
                if (!is_anything_selected()) erro = '".et('NoSel').".\\n';
                conf = '".et('RemSel')." ?\\n';
            } else if (arg == 5){
                if (!is_anything_selected()) erro = '".et('NoSel').".\\n';
                else if(document.form_action.dir_dest.value.length == 0) erro = '".et('NoDestDir').".';
                else if(document.form_action.dir_dest.value == document.form_action.current_dir.value) erro = '".et('DestEqOrig').".';
                else if(!valid_dest(document.form_action.dir_dest.value,document.form_action.current_dir.value)) erro = '".et('InvalidDest').".';
                conf = '".et('CopyTo')." \\' '+document.form_action.dir_dest.value+' \\' ?\\n';
            } else if (arg == 6){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else if(document.form_action.dir_dest.value.length == 0) erro = '".et('NoDestDir').".';
                else if(document.form_action.dir_dest.value == document.form_action.current_dir.value) erro = '".et('DestEqOrig').".';
                else if(!valid_dest(document.form_action.dir_dest.value,document.form_action.current_dir.value)) erro = '".et('InvalidDest').".';
                conf = '".et('MoveTo')." \\' '+document.form_action.dir_dest.value+' \\' ?\\n';
            } else if (arg == 9){
                if (!is_anything_selected()) erro = '".et('NoSel').".';
                else if(document.form_action.chmod_arg.value.length == 0) erro = '".et('NoNewPerm').".';
                //conf = '".et('AlterPermTo')." \\' '+document.form_action.chmod_arg.value+' \\' ?\\n';
            }
            if (erro!=''){
                document.form_action.cmd_arg.focus();
                alert(erro);
            } else if(conf!='') {
                if(confirm(conf)) {
                    document.form_action.action.value = arg;
                    document.form_action.submit();
                } else {
                    set_sel_dir_warn(false);
				}
            } else {
                document.form_action.action.value = arg;
                document.form_action.submit();
            }
        }
        //-->
        </script>";
        $out .= "
        <form name=\"form_action\" action=\"".$path_info["basename"]."\" method=\"post\" onsubmit=\"return test_action();\">
            <input type=hidden name=\"frame\" value=3>
            <input type=hidden name=\"action\" value=0>
            <input type=hidden name=\"dir_dest\" value=\"\">
            <input type=hidden name=\"chmod_arg\" value=\"\">
            <input type=hidden name=\"cmd_arg\" value=\"\">
            <input type=hidden name=\"current_dir\" value=\"$current_dir\">
            <input type=hidden name=\"dir_before\" value=\"$dir_before\">
            <input type=hidden name=\"selected_dir_list\" value=\"\">
            <input type=hidden name=\"selected_file_list\" value=\"\">";
        $out .= "
            <tr>
            <td bgcolor=\"#333333\" colspan=50><nobr>
            <input type=button class=buto onclick=\"config()\" value=\"".et('Config')."\">
            <input type=button class=buto onclick=\"server_info()\" value=\"".et('ServerInfo')."\">
            <input type=button class=buto onclick=\"test_prompt(1)\" value=\"".et('CreateDir')."\">
            <input type=button class=buto onclick=\"test_prompt(2)\" value=\"".et('CreateArq')."\">
            <input type=button class=buto onclick=\"execute_cmd()\" value=\"".et('ExecCmd')."\">
            <input type=button class=buto onclick=\"upload()\" value=\"".et('Upload')."\">
            <input type=button class=buto onclick=\"shell()\" value=\"".et('Shell')."\">
            <b>$ip</b>
            </nobr>";
        $uplink = "";
        if ($current_dir != $fm_current_root){
            $mat = explode("/",$current_dir);
            $dir_before = "";
            for($x=0;$x<(count($mat)-2);$x++) $dir_before .= $mat[$x]."/";
            $uplink = "<a href=\"".$path_info["basename"]."?frame=3&current_dir=$dir_before\"><<</a> ";
        }
        if($entry_count){
            $out .= "
                <tr bgcolor=\"#333333\"><td colspan=50><nobr>$uplink <a href=\"".$path_info["basename"]."?frame=3&current_dir=$current_dir\">$current_dir</a></nobr>
                <tr>
                <td bgcolor=\"#333333\" colspan=50><nobr>
                    <input type=\"button\" class=\"buto\" class=\"buto\" style=\"width:80\" onclick=\"selectANI(this)\" id=\"ANI0\" value=\"".et('SelAll')."\">
                    <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"selectANI(this)\" value=\"".et('SelInverse')."\">
                    <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"test(4)\" value=\"".et('Rem')."\">
                    <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"sel_dir(5)\" value=\"".et('Copy')."\">
                    <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"sel_dir(6)\" value=\"".et('Move')."\">
                    <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"test_prompt(71)\" value=\"".et('Compress')."\">";
            if ($islinux) $out .= "
                    <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"resolveIDs()\" value=\"".et('ResolveIDs')."\">";
            $out .= "
                    <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"chmod_form()\" value=\"".et('Perms')."\">";
            $out .= "
                </nobr></td>
                </tr>
				<tr>
                <td bgcolor=\"#333333\" colspan=50 id=\"sel_dir_warn\" style=\"display:none\"><nobr><font color=\"red\">".et('SelDir')."...</font></nobr></td>
                </tr>";
            $file_count = 0;
            $dir_count = 0;
            $dir_out = array();
            $file_out = array();
            $max_opt = 0;
            foreach ($entry_list as $ind=>$dir_entry) {
                $file = $dir_entry["name"];
                if ($dir_entry["type"]=="dir"){
                    $dir_out[$dir_count] = array();
                    $dir_out[$dir_count][] = "
                        <tr ID=\"entry$ind\" class=\"entryUnselected\" onmouseover=\"selectEntry(this, 'over');\" onmousedown=\"selectEntry(this, 'click');\">
                        <td><nobr><a href=\"JavaScript:go('".addslashes($file)."')\">$file</a></nobr></td>";
                    $dir_out[$dir_count][] = "<td>".$dir_entry["p"]."</td>";
                    if ($islinux) {
                        $dir_out[$dir_count][] = "<td><nobr>".$dir_entry["u"]."</nobr></td>";
                        $dir_out[$dir_count][] = "<td><nobr>".$dir_entry["g"]."</nobr></td>";
                    }
                    $dir_out[$dir_count][] = "<td><nobr>".$dir_entry["sizet"]."</nobr></td>";
                    $dir_out[$dir_count][] = "<td><nobr>".$dir_entry["datet"]."</nobr></td>";
                    if ($has_files) $dir_out[$dir_count][] = "<td>&nbsp;</td>";
                    // Opções de diretório
                    if ( is_writable($current_dir.$file) ) $dir_out[$dir_count][] = "
                        <td align=center><a href=\"JavaScript:if(confirm('".et('ConfRem')." \\'".addslashes($file)."\\' ?')) document.location.href='".addslashes($path_info["basename"])."?frame=3&action=8&cmd_arg=".addslashes($file)."&current_dir=".addslashes($current_dir)."'\">".et('Rem')."</a>";
                    if ( is_writable($current_dir.$file) ) $dir_out[$dir_count][] = "
                        <td align=center><a href=\"JavaScript:rename('".addslashes($file)."')\">".et('Ren')."</a>";
                    if (count($dir_out[$dir_count])>$max_opt){
                        $max_opt = count($dir_out[$dir_count]);
                    }
                    $dir_count++;
                } else {
                    $file_out[$file_count] = array();
                    $file_out[$file_count][] = "
                        <tr ID=\"entry$ind\" class=\"entryUnselected\" onmouseover=\"selectEntry(this, 'over');\" onmousedown=\"selectEntry(this, 'click');\">
                        <td><nobr><a href=\"JavaScript:download('".addslashes($file)."')\">$file</a></nobr></td>";
                    $file_out[$file_count][] = "<td>".$dir_entry["p"]."</td>";
                    if ($islinux) {
                        $file_out[$file_count][] = "<td><nobr>".$dir_entry["u"]."</nobr></td>";
                        $file_out[$file_count][] = "<td><nobr>".$dir_entry["g"]."</nobr></td>";
                    }
                    $file_out[$file_count][] = "<td><nobr>".$dir_entry["sizet"]."</nobr></td>";
                    $file_out[$file_count][] = "<td><nobr>".$dir_entry["datet"]."</nobr></td>";
                    $file_out[$file_count][] = "<td>".$dir_entry["extt"]."</td>";
                    // Opções de arquivo
                    if ( is_writable($current_dir.$file) ) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:if(confirm('".uppercase(et('Rem'))." \\'".addslashes($file)."\\' ?')) document.location.href='".addslashes($path_info["basename"])."?frame=3&action=8&cmd_arg=".addslashes($file)."&current_dir=".addslashes($current_dir)."'\">".et('Rem')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if ( is_writable($current_dir.$file) ) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:rename('".addslashes($file)."')\">".et('Ren')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if ( is_readable($current_dir.$file) && (strpos(".wav#.mp3#.mid#.avi#.mov#.mpeg#.mpg#.rm#.iso#.bin#.img#.dll#.psd#.fla#.swf#.class#.ppt#.tif#.tiff#.pcx#.jpg#.gif#.png#.wmf#.eps#.bmp#.msi#.exe#.com#.rar#.tar#.zip#.bz2#.tbz2#.bz#.tbz#.bzip#.gzip#.gz#.tgz#", $dir_entry["ext"]."#" ) === false)) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:edit_file('".addslashes($file)."')\">".et('Edit')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if ( is_readable($current_dir.$file) && (strpos(".txt#.sys#.bat#.ini#.conf#.swf#.php#.php3#.asp#.html#.htm#.jpg#.gif#.png#.bmp#", $dir_entry["ext"]."#" ) !== false)) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:view('".addslashes($file)."');\">".et('View')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if ( is_readable($current_dir.$file) && strlen($dir_entry["ext"]) && (strpos(".tar#.zip#.bz2#.tbz2#.bz#.tbz#.bzip#.gzip#.gz#.tgz#", $dir_entry["ext"]."#" ) !== false)) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:decompress('".addslashes($file)."')\">".et('Decompress')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if ( is_readable($current_dir.$file) && strlen($dir_entry["ext"]) && (strpos(".exe#.com#.sh#.bat#", $dir_entry["ext"]."#" ) !== false)) $file_out[$file_count][] = "
                                <td align=center><a href=\"javascript:execute_file('".addslashes($file)."')\">".et('Exec')."</a>";
                    else $file_out[$file_count][] = "<td>&nbsp;</td>";
                    if (count($file_out[$file_count])>$max_opt){
                        $max_opt = count($file_out[$file_count]);
                    }
                    $file_count++;
                }
            }
            if ($dir_count){
                $out .= "
                <tr>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or1&current_dir=$current_dir\">".et('Name')."</a></nobr></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or2&current_dir=$current_dir\">".et('Perm')."</a></nobr></td>";
                if ($islinux) $out .= "
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or3&current_dir=$current_dir\">".et('Owner')."</a></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or4&current_dir=$current_dir\">".et('Group')."</a></nobr></td>";
                $out .= "
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or5&current_dir=$current_dir\">".et('Size')."</a></nobr></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or6&current_dir=$current_dir\">".et('Date')."</a></nobr></td>";
                if ($file_count) $out .= "
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or7&current_dir=$current_dir\">".et('Type')."</a></nobr></td>";
                $out .= "
                      <td bgcolor=\"#528356\" colspan=50>&nbsp;</td>
                </tr>";

            }
            foreach($dir_out as $k=>$v){
                while (count($dir_out[$k])<$max_opt) {
                    $dir_out[$k][] = "<td>&nbsp;</td>";
                }
                $out .= implode($dir_out[$k]);
                $out .= "</tr>";
            }
            if ($file_count){
                $out .= "
                <tr>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or1&current_dir=$current_dir\">".et('Name')."</a></nobr></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or2&current_dir=$current_dir\">".et('Perm')."</a></nobr></td>";
                if ($islinux) $out .= "
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or3&current_dir=$current_dir\">".et('Owner')."</a></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or4&current_dir=$current_dir\">".et('Group')."</a></nobr></td>";
                $out .= "
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or5&current_dir=$current_dir\">".et('Size')."</a></nobr></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or6&current_dir=$current_dir\">".et('Date')."</a></nobr></td>
                      <td bgcolor=\"#528356\"><nobr><a href=\"".$path_info["basename"]."?frame=3&or_by=$or7&current_dir=$current_dir\">".et('Type')."</a></nobr></td>
                      <td bgcolor=\"#528356\" colspan=50>&nbsp;</td>
                </tr>";

            }
            foreach($file_out as $k=>$v){
                while (count($file_out[$k])<$max_opt) {
                    $file_out[$k][] = "<td>&nbsp;</td>";
                }
                $out .= implode($file_out[$k]);
                $out .= "</tr>";
            }
            $out .= "
                <tr>
                <td bgcolor=\"#333333\" colspan=50><nobr>
                      <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"selectANI(this)\" id=\"ANI1\" value=\"".et('SelAll')."\">
                      <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"selectANI(this)\" value=\"".et('SelInverse')."\">
                      <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"test(4)\" value=\"".et('Rem')."\">
                      <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"sel_dir(5)\" value=\"".et('Copy')."\">
                      <input type=\"button\" class=\"buto\" style=\"width:80\" onclick=\"sel_dir(6)\" value=\"".et('Move')."\">
                      <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"test_prompt(71)\" value=\"".et('Compress')."\">";
            if ($islinux) $out .= "
                      <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"resolveIDs()\" value=\"".et('ResolveIDs')."\">";
            $out .= "
                      <input type=\"button\" class=\"buto\" style=\"width:100\" onclick=\"chmod_form()\" value=\"".et('Perms')."\">";
            $out .= "
                </nobr></td>
                </tr>";
            $out .= "
            </form>";
            $out .= "
                <tr><td bgcolor=\"#333333\" colspan=50><b>$dir_count ".et('Dir_s')." ".et('And')." $file_count ".et('File_s')." = ".format_size($total_size)."</td></tr>";
            if ($quota_mb) {
                $out .= "
                <tr><td bgcolor=\"#333333\" colspan=50><b>".et('Partition').": ".format_size(($quota_mb*1024*1024))." ".et('Total')." - ".format_size(($quota_mb*1024*1024)-total_size($fm_current_root))." ".et('Free')."</td></tr>";
            } else {
                $out .= "
                <tr><td bgcolor=\"#333333\" colspan=50><b>".et('Partition').": ".format_size(disk_total_space($current_dir))." ".et('Total')." - ".format_size(disk_free_space($current_dir))." ".et('Free')."</td></tr>";
            }
            $tf = getmicrotime();
            $tt = ($tf - $ti);
            $out .= "
                <tr><td bgcolor=\"#333333\" colspan=50><b>".et('RenderTime').": ".substr($tt,0,strrpos($tt,".")+5)." ".et('Seconds')."</td></tr>";
            $out .= "
            <script language=\"Javascript\" type=\"text/javascript\">
            <!--
                update_sel_status();
            //-->
            </script>";
        } else {
            $out .= "
            <tr>
            <td bgcolor=\"#333333\" width=\"1%\">$uplink<td bgcolor=\"#333333\" colspan=50><nobr><a href=\"".$path_info["basename"]."?frame=3&current_dir=$current_dir\">$current_dir</a></nobr>
            <tr><td bgcolor=\"#333333\" colspan=50>".et('EmptyDir').".</tr>";
        }
    } else $out .= "<tr><td><font color=red>".et('IOError').".<br>$current_dir</font>";
    $out .= "</table>";
    echo $out;
}
function upload_form(){
    global $_FILES,$current_dir,$dir_dest,$fechar,$quota_mb,$path_info;
    $num_uploads = 5;
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">";
    if (count($_FILES)==0){
        echo "
        <table height=\"100%\" border=0 cellspacing=0 cellpadding=2 align=center>
        <form name=\"upload_form\" action=\"".$path_info["basename"]."\" method=\"post\" ENCTYPE=\"multipart/form-data\">
        <input type=hidden name=dir_dest value=\"$current_dir\">
        <input type=hidden name=action value=10>
        <tr><th colspan=2>".et('Upload')."</th></tr>
        <tr><td align=right><b>".et('Destination').":<td><b><nobr>$current_dir</nobr>";
        for ($x=0;$x<$num_uploads;$x++){
            echo "<tr><td width=1 align=right><b>".et('File').":<td><nobr><input type=\"file\" name=\"file$x\"></nobr>";
            $test_js .= "(document.upload_form.file$x.value.length>0)||";
        }
        echo "
        <input type=button class=buto value=\"".et('Send')."\" onclick=\"test_upload_form()\"></nobr>
        <tr><td> <td><input type=checkbox name=fechar value=\"1\"> <a href=\"JavaScript:troca();\">".et('AutoClose')."</a>
        <tr><td colspan=2> </td></tr>
        </form>
        </table>
        <script language=\"Javascript\" type=\"text/javascript\">
        <!--
            function troca(){
                if(document.upload_form.fechar.checked){document.upload_form.fechar.checked=false;}else{document.upload_form.fechar.checked=true;}
            }
            foi = false;
            function test_upload_form(){
                if(".substr($test_js,0,strlen($test_js)-2)."){
                    if (foi) alert('".et('SendingForm')."...');
                    else {
                        foi = true;
                        document.upload_form.submit();
                    }
                } else alert('".et('NoFileSel').".');
            }
            window.moveTo((window.screen.width-400)/2,((window.screen.height-200)/2)-20);
        //-->
        </script>";
    } else {
        $out = "<tr><th colspan=2>".et('UploadEnd')."</th></tr>
                <tr><th colspan=2><nobr>".et('Destination').": $dir_dest</nobr>";
        for ($x=0;$x<$num_uploads;$x++){
            $temp_file = $_FILES["file".$x]["tmp_name"];
            $filename = $_FILES["file".$x]["name"];
            if (strlen($filename)) $resul = save_upload($temp_file,$filename,$dir_dest);
            else $resul = 7;
            switch($resul){
                case 1:
                $out .= "<tr><td><b>".str_zero($x+1,3).".<font color=green><b> ".et('FileSent').":</font><td>".$filename."</td></tr>\n";
                break;
                case 2:
                $out .= "<tr><td colspan=2><font color=red><b>".et('IOError')."</font></td></tr>\n";
                $x = $upload_num;
                break;
                case 3:
                $out .= "<tr><td colspan=2><font color=red><b>".et('SpaceLimReached')." ($quota_mb Mb)</font></td></tr>\n";
                $x = $upload_num;
                break;
                case 4:
                $out .= "<tr><td><b>".str_zero($x+1,3).".<font color=red><b> ".et('InvExt').":</font><td>".$filename."</td></tr>\n";
                break;
                case 5:
                $out .= "<tr><td><b>".str_zero($x+1,3).".<font color=red><b> ".et('FileNoOverw')."</font><td>".$filename."</td></tr>\n";
                break;
                case 6:
                $out .= "<tr><td><b>".str_zero($x+1,3).".<font color=green><b> ".et('FileOverw').":</font><td>".$filename."</td></tr>\n";
                break;
                case 7:
                $out .= "<tr><td colspan=2><b>".str_zero($x+1,3).".<font color=red><b> ".et('FileIgnored')."</font></td></tr>\n";
            }
        }
        if ($fechar) {
            echo "
            <script language=\"Javascript\" type=\"text/javascript\">
            <!--
                window.close();
            //-->
            </script>
            ";
        } else {
            echo "
            <table height=\"100%\" border=0 cellspacing=0 cellpadding=2 align=center>
            $out
            <tr><td colspan=2> </td></tr>
            </table>
            <script language=\"Javascript\" type=\"text/javascript\">
            <!--
                window.focus();
            //-->
            </script>
            ";
        }
    }
    echo "</body>\n</html>";
}
function chmod_form(){
    html_header("
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
    function octalchange()
    {
        var val = document.chmod_form.t_total.value;
        var stickybin = parseInt(val.charAt(0)).toString(2);
        var ownerbin = parseInt(val.charAt(1)).toString(2);
        while (ownerbin.length<3) { ownerbin=\"0\"+ownerbin; };
        var groupbin = parseInt(val.charAt(2)).toString(2);
        while (groupbin.length<3) { groupbin=\"0\"+groupbin; };
        var otherbin = parseInt(val.charAt(3)).toString(2);
        while (otherbin.length<3) { otherbin=\"0\"+otherbin; };
        document.chmod_form.sticky.checked = parseInt(stickybin.charAt(0));
        document.chmod_form.owner4.checked = parseInt(ownerbin.charAt(0));
        document.chmod_form.owner2.checked = parseInt(ownerbin.charAt(1));
        document.chmod_form.owner1.checked = parseInt(ownerbin.charAt(2));
        document.chmod_form.group4.checked = parseInt(groupbin.charAt(0));
        document.chmod_form.group2.checked = parseInt(groupbin.charAt(1));
        document.chmod_form.group1.checked = parseInt(groupbin.charAt(2));
        document.chmod_form.other4.checked = parseInt(otherbin.charAt(0));
        document.chmod_form.other2.checked = parseInt(otherbin.charAt(1));
        document.chmod_form.other1.checked = parseInt(otherbin.charAt(2));
        calc_chmod(1);
    };

    function calc_chmod(nototals)
    {
      var users = new Array(\"owner\", \"group\", \"other\");
      var totals = new Array(\"\",\"\",\"\");
      var syms = new Array(\"\",\"\",\"\");

        for (var i=0; i<users.length; i++)
        {
            var user=users[i];
            var field4 = user + \"4\";
            var field2 = user + \"2\";
            var field1 = user + \"1\";
            var symbolic = \"sym_\" + user;
            var number = 0;
            var sym_string = \"\";
            var sticky = \"0\";
            var sticky_sym = \" \";
            if (document.chmod_form.sticky.checked){
                sticky = \"1\";
                sticky_sym = \"t\";
            }
            if (document.chmod_form[field4].checked == true) { number += 4; }
            if (document.chmod_form[field2].checked == true) { number += 2; }
            if (document.chmod_form[field1].checked == true) { number += 1; }

            if (document.chmod_form[field4].checked == true) {
                sym_string += \"r\";
            } else {
                sym_string += \"-\";
            }
            if (document.chmod_form[field2].checked == true) {
                sym_string += \"w\";
            } else {
                sym_string += \"-\";
            }
            if (document.chmod_form[field1].checked == true) {
                sym_string += \"x\";
            } else {
                sym_string += \"-\";
            }

            totals[i] = totals[i]+number;
            syms[i] =  syms[i]+sym_string;

      };
        if (!nototals) document.chmod_form.t_total.value = sticky + totals[0] + totals[1] + totals[2];
        document.chmod_form.sym_total.value = syms[0] + syms[1] + syms[2] + sticky_sym;
    }
    function sticky_change(){
        document.chmod_form.sticky.checked = !(document.chmod_form.sticky.checked);
    }
	function apply_chmod(){
        if (confirm('".et('AlterPermTo')." \\' '+document.chmod_form.t_total.value+' \\' ?\\n')){
            window.opener.set_chmod_arg(document.chmod_form.t_total.value);
			window.close();
		}
	}

    window.onload=octalchange
    window.moveTo((window.screen.width-400)/2,((window.screen.height-200)/2)-20);
    //-->
    </script>");
    echo "<body marginwidth=\"0\" marginheight=\"0\">
    <form name=\"chmod_form\">
    <TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"4\" ALIGN=CENTER>
    <tr><th colspan=4>".et('Perms')."</th></tr>
    <TR ALIGN=\"LEFT\" VALIGN=\"MIDDLE\">
    <TD><input type=\"text\" name=\"t_total\" value=\"0755\" size=\"4\" onKeyUp=\"octalchange()\"> </TD>
    <TD><input type=\"text\" name=\"sym_total\" value=\"\" size=\"12\" READONLY=\"1\"></TD>
    </TR>
    </TABLE>
    <table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" ALIGN=CENTER>
    <tr bgcolor=\"#333333\">
    <td WIDTH=\"60\" align=\"left\"> </td>
    <td WIDTH=\"55\" align=\"center\" style=\"color:#FFFFFF\"><b>".et('Owner')."
    </b></td>
    <td WIDTH=\"55\" align=\"center\" style=\"color:#FFFFFF\"><b>".et('Group')."
    </b></td>
    <td WIDTH=\"55\" align=\"center\" style=\"color:#FFFFFF\"><b>".et('Other')."
    <b></td>
    </tr>
    <tr bgcolor=\"#333333\">
    <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#FFFFFF\">".et('Read')."</td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"owner4\" value=\"4\" onclick=\"calc_chmod()\">
    </td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#FFFFFF\"><input type=\"checkbox\" name=\"group4\" value=\"4\" onclick=\"calc_chmod()\">
    </td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"other4\" value=\"4\" onclick=\"calc_chmod()\">
    </td>
    </tr>
    <tr bgcolor=\"#333333\">
    <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#FFFFFF\">".et('Write')."</td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"owner2\" value=\"2\" onclick=\"calc_chmod()\"></td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#FFFFFF\"><input type=\"checkbox\" name=\"group2\" value=\"2\" onclick=\"calc_chmod()\">
    </td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"other2\" value=\"2\" onclick=\"calc_chmod()\">
    </td>
    </tr>
    <tr bgcolor=\"#333333\">
    <td WIDTH=\"60\" align=\"left\" nowrap BGCOLOR=\"#FFFFFF\">".et('Exec')."</td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"owner1\" value=\"1\" onclick=\"calc_chmod()\">
    </td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#FFFFFF\"><input type=\"checkbox\" name=\"group1\" value=\"1\" onclick=\"calc_chmod()\">
    </td>
    <td WIDTH=\"55\" align=\"center\" bgcolor=\"#EEEEEE\">
    <input type=\"checkbox\" name=\"other1\" value=\"1\" onclick=\"calc_chmod()\">
    </td>
    </tr>
    </TABLE>
    <TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"4\" ALIGN=CENTER>
    <tr><td colspan=2><input type=checkbox name=sticky value=\"1\" onclick=\"calc_chmod()\"> <a href=\"JavaScript:sticky_change();\">".et('StickyBit')."</a><td colspan=2 align=right><input type=button class=buto value=\"".et('Apply')."\" onClick=\"apply_chmod()\"></tr>
    </table>
    </form>
    </body>\n</html>";
}
function get_mime_type($ext = ''){
    $mimes = array(
      'hqx'   =>  'application/mac-binhex40',
      'cpt'   =>  'application/mac-compactpro',
      'doc'   =>  'application/msword',
      'bin'   =>  'application/macbinary',
      'dms'   =>  'application/octet-stream',
      'lha'   =>  'application/octet-stream',
      'lzh'   =>  'application/octet-stream',
      'exe'   =>  'application/octet-stream',
      'class' =>  'application/octet-stream',
      'psd'   =>  'application/octet-stream',
      'so'    =>  'application/octet-stream',
      'sea'   =>  'application/octet-stream',
      'dll'   =>  'application/octet-stream',
      'oda'   =>  'application/oda',
      'pdf'   =>  'application/pdf',
      'ai'    =>  'application/postscript',
      'eps'   =>  'application/postscript',
      'ps'    =>  'application/postscript',
      'smi'   =>  'application/smil',
      'smil'  =>  'application/smil',
      'mif'   =>  'application/vnd.mif',
      'xls'   =>  'application/vnd.ms-excel',
      'ppt'   =>  'application/vnd.ms-powerpoint',
      'pptx'  =>  'application/vnd.ms-powerpoint',
      'wbxml' =>  'application/vnd.wap.wbxml',
      'wmlc'  =>  'application/vnd.wap.wmlc',
      'dcr'   =>  'application/x-director',
      'dir'   =>  'application/x-director',
      'dxr'   =>  'application/x-director',
      'dvi'   =>  'application/x-dvi',
      'gtar'  =>  'application/x-gtar',
      'php'   =>  'application/x-httpd-php',
      'php4'  =>  'application/x-httpd-php',
      'php3'  =>  'application/x-httpd-php',
      'phtml' =>  'application/x-httpd-php',
      'phps'  =>  'application/x-httpd-php-source',
      'js'    =>  'application/x-javascript',
      'swf'   =>  'application/x-shockwave-flash',
      'sit'   =>  'application/x-stuffit',
      'tar'   =>  'application/x-tar',
      'tgz'   =>  'application/x-tar',
      'xhtml' =>  'application/xhtml+xml',
      'xht'   =>  'application/xhtml+xml',
      'zip'   =>  'application/zip',
      'mid'   =>  'audio/midi',
      'midi'  =>  'audio/midi',
      'mpga'  =>  'audio/mpeg',
      'mp2'   =>  'audio/mpeg',
      'mp3'   =>  'audio/mpeg',
      'aif'   =>  'audio/x-aiff',
      'aiff'  =>  'audio/x-aiff',
      'aifc'  =>  'audio/x-aiff',
      'ram'   =>  'audio/x-pn-realaudio',
      'rm'    =>  'audio/x-pn-realaudio',
      'rpm'   =>  'audio/x-pn-realaudio-plugin',
      'ra'    =>  'audio/x-realaudio',
      'rv'    =>  'video/vnd.rn-realvideo',
      'wav'   =>  'audio/x-wav',
      'bmp'   =>  'image/bmp',
      'gif'   =>  'image/gif',
      'jpeg'  =>  'image/jpeg',
      'jpg'   =>  'image/jpeg',
      'jpe'   =>  'image/jpeg',
      'png'   =>  'image/png',
      'tiff'  =>  'image/tiff',
      'tif'   =>  'image/tiff',
      'css'   =>  'text/css',
      'html'  =>  'text/html',
      'htm'   =>  'text/html',
      'shtml' =>  'text/html',
      'txt'   =>  'text/plain',
      'text'  =>  'text/plain',
      'log'   =>  'text/plain',
      'rtx'   =>  'text/richtext',
      'rtf'   =>  'text/rtf',
      'xml'   =>  'text/xml',
      'xsl'   =>  'text/xml',
      'mpeg'  =>  'video/mpeg',
      'mpg'   =>  'video/mpeg',
      'mpe'   =>  'video/mpeg',
      'qt'    =>  'video/quicktime',
      'mov'   =>  'video/quicktime',
      'avi'   =>  'video/x-msvideo',
      'movie' =>  'video/x-sgi-movie',
      'doc'   =>  'application/msword',
      'docx'  =>  'application/msword',
      'word'  =>  'application/msword',
      'xl'    =>  'application/excel',
      'xls'   =>  'application/excel',
      'xlsx'  =>  'application/excel',
      'eml'   =>  'message/rfc822'
    );
    return (!isset($mimes[lowercase($ext)])) ? 'application/octet-stream' : $mimes[lowercase($ext)];
}
function view(){
    global $doc_root,$path_info,$url_info,$current_dir,$islinux,$filename,$passthru;
	if (intval($passthru)){
	    $file = $current_dir.$filename;
	    if(file_exists($file)){
	        $is_denied = false;
	        foreach($download_ext_filter as $key=>$ext){
	            if (eregi($ext,$filename)){
	                $is_denied = true;
	                break;
	            }
	        }
	        if (!$is_denied){
                if ($fh = fopen("$file", "rb")){
	                fclose($fh);
					$ext = pathinfo($file, PATHINFO_EXTENSION);
					$ctype = get_mime_type($ext);
					if ($ctype == "application/octet-stream") $ctype = "text/plain";
					header("Pragma: public");
					header("Expires: 0");
					header("Connection: close");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: public");
					header("Content-Description: File Transfer");
					header("Content-Type: ".$ctype);
				    header("Content-Disposition: inline; filename=\"".pathinfo($file, PATHINFO_BASENAME)."\";");
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".filesize($file));
					@readfile($file);
					exit();
	            } else alert(et('ReadDenied').": ".$file);
	        } else alert(et('ReadDenied').": ".$file);
	    } else alert(et('FileNotFound').": ".$file);
        echo "
	    <script language=\"Javascript\" type=\"text/javascript\">
	    <!--
	        window.close();
	    //-->
	    </script>";
	} else {
	    html_header();
	    echo "<body marginwidth=\"0\" marginheight=\"0\">";
	    $is_reachable_thru_webserver = (stristr($current_dir,$doc_root)!==false);
	    if ($is_reachable_thru_webserver){
	        $url = $url_info["scheme"]."://".$url_info["host"];
	        if (strlen($url_info["port"])) $url .= ":".$url_info["port"];
	        // Malditas variaveis de sistema!! No windows doc_root é sempre em lowercase... cadê o str_ireplace() ??
	        $url .= str_replace($doc_root,"","/".$current_dir).$filename;
	    } else {
			$url = addslashes($path_info["basename"])."?action=4&current_dir=".addslashes($current_dir)."&filename=".addslashes($filename)."&passthru=1";
	    }
        echo "
	    <script language=\"Javascript\" type=\"text/javascript\">
	    <!--
        	window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
	        document.location.href='$url';
	    //-->
	    </script>
    	</body>\n</html>";
	}
}
function edit_file_form(){
    global $current_dir,$filename,$file_data,$save_file,$path_info;
    $file = $current_dir.$filename;
    if ($save_file){
        $fh=fopen($file,"w");
        fputs($fh,$file_data,strlen($file_data));
        fclose($fh);
    }
    $fh=fopen($file,"r");
    $file_data=fread($fh, filesize($file));
    fclose($fh);
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">
    <table border=0 cellspacing=0 cellpadding=5 align=center>
    <form name=\"edit_form\" action=\"".$path_info["basename"]."\" method=\"post\">
    <input type=hidden name=action value=\"7\">
    <input type=hidden name=save_file value=\"1\">
    <input type=hidden name=current_dir value=\"$current_dir\">
    <input type=hidden name=filename value=\"$filename\">
    <tr><th colspan=2>".$file."</th></tr>
    <tr><td colspan=2><textarea name=file_data style='width:1000px;height:680px;'>".html_encode($file_data)."</textarea></td></tr>
    <tr><td><input type=button class=buto value=\"".et('Refresh')."\" onclick=\"document.edit_form_refresh.submit()\"></td><td align=right><input type=button class=buto value=\"".et('SaveFile')."\" onclick=\"go_save()\"></td></tr>
    </form>
    <form name=\"edit_form_refresh\" action=\"".$path_info["basename"]."\" method=\"post\">
    <input type=hidden name=action value=\"7\">
    <input type=hidden name=current_dir value=\"$current_dir\">
    <input type=hidden name=filename value=\"$filename\">
    </form>
    </table>
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        window.moveTo((window.screen.width-1024)/2,((window.screen.height-728)/2)-20);
        function go_save(){";
    if (is_writable($file)) {
        echo "
        document.edit_form.submit();";
    } else {
        echo "
        if(confirm('".et('ConfTrySave')." ?')) document.edit_form.submit();";
    }
    echo "
        }
    //-->
    </script>
    </body>\n</html>";
}
function config_form(){
    global $cfg;
    global $current_dir,$fm_self,$doc_root,$path_info,$fm_current_root,$lang,$error_reporting,$version;
    global $config_action,$newpass,$newlang,$newerror,$newfm_root;
    $Warning = "";
    switch ($config_action){
        case 1:
            if ($fh = fopen("http://phpfm.sf.net/latest.php","r")){
                $data = "";
                while (!feof($fh)) $data .= fread($fh,1024);
                fclose($fh);
                $data = unserialize($data);
                $ChkVerWarning = "<tr><td align=right> ";
                if (is_array($data)&&count($data)){
                    $ChkVerWarning .= "<a href=\"JavaScript:open_win('http://sourceforge.net')\">
                    <img src=\"http://sourceforge.net/sflogo.php?group_id=114392&type=1\" width=\"88\" height=\"31\" style=\"border: 1px solid #AAAAAA\" alt=\"SourceForge.net Logo\" />
					</a>";
                    if (str_replace(".","",$data['version'])>str_replace(".","",$cfg->data['version'])) $ChkVerWarning .= "<td><a href=\"JavaScript:open_win('http://prdownloads.sourceforge.net/phpfm/phpFileManager-".$data['version'].".zip?download')\"><font color=green>".et('ChkVerAvailable')."</font></a>";
                    else $ChkVerWarning .= "<td><font color=red>".et('ChkVerNotAvailable')."</font>";
                } else $ChkVerWarning .= "<td><font color=red>".et('ChkVerError')."</font>";
            } else $ChkVerWarning .= "<td><font color=red>".et('ChkVerError')."</font>";
        break;
        case 2:
            $reload = false;
            if ($cfg->data['lang'] != $newlang){
                $cfg->data['lang'] = $newlang;
                $lang = $newlang;
                $reload = true;
            }
            if ($cfg->data['error_reporting'] != $newerror){
                $cfg->data['error_reporting'] = $newerror;
                $error_reporting = $newerror;
                $reload = true;
            }
            $newfm_root = format_path($newfm_root);
            if ($cfg->data['fm_root'] != $newfm_root){
                $cfg->data['fm_root'] = $newfm_root;
                if (strlen($newfm_root)) $current_dir = $newfm_root;
                else $current_dir = $path_info["dirname"]."/";
                setcookie("fm_current_root", $newfm_root , 0 , "/");
                $reload = true;
            }
            $cfg->save();
            if ($reload){
                reloadframe("window.opener.parent",2);
                reloadframe("window.opener.parent",3);
            }
            $Warning1 = et('ConfSaved')."...";
        break;
        case 3:
            if ($cfg->data['auth_pass'] != md5($newpass)){
                $cfg->data['auth_pass'] = md5($newpass);
                setcookie("loggedon", md5($newpass) , 0 , "/");
            }
            $cfg->save();
            $Warning2 = et('PassSaved')."...";
        break;
    }
    html_header();
    echo "<body marginwidth=\"0\" marginheight=\"0\">\n";
    echo "
    <table border=0 cellspacing=0 cellpadding=5 align=center width=\"100%\">
    <tr><td colspan=2 align=center><b>".uppercase(et('Configurations'))."</b></td></tr>
    </table>
    <table border=0 cellspacing=0 cellpadding=5 align=center width=\"100%\">
	<form>
    <tr><td align=right width=\"1%\">".et('Version').":<td>$version (".get_size($fm_self).")</td></tr>
    <tr><td align=right>".et('Website').":<td><a href=\"JavaScript:open_win('http://phpfm.sf.net')\">http://phpfm.sf.net</a>&nbsp;&nbsp;&nbsp;<input type=button class=buto value=\"".et('ChkVer')."\" onclick=\"test_config_form(1)\"></td></tr>
	</form>";
    if (strlen($ChkVerWarning)) echo $ChkVerWarning.$data['warnings'];
    echo "
 	<style type=\"text/css\">
		.buymeabeer {
		    background: url('http://phpfm.sf.net/img/buymeabeer.png') 0 0 no-repeat;
		    text-indent: -9999px;
		    width: 128px;
		    height: 31px;
            border: none;
   			cursor: hand;
   			cursor: pointer;
		}
		.buymeabeer:hover {
		    background: url('http://phpfm.sf.net/img/buymeabeer.png') 0 -31px no-repeat;
		}
	</style>
	<tr><td align=right>Like this project?</td><td>
	<form name=\"buymeabeer_form\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
		<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
		<input type=\"hidden\" name=\"business\" value=\"dulldusk@gmail.com\">
		<input type=\"hidden\" name=\"lc\" value=\"BR\">
		<input type=\"hidden\" name=\"item_name\" value=\"A Beer\">
		<input type=\"hidden\" name=\"button_subtype\" value=\"services\">
		<input type=\"hidden\" name=\"currency_code\" value=\"USD\">
		<input type=\"hidden\" name=\"tax_rate\" value=\"0.000\">
		<input type=\"hidden\" name=\"shipping\" value=\"0.00\">
		<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest\">
        <input type=\"submit\" class=\"buymeabeer\" value=\"buy me a beer\">
	        <input type=\"hidden\" name=\"buyer_credit_promo_code\" value=\"\">
	        <input type=\"hidden\" name=\"buyer_credit_product_category\" value=\"\">
	        <input type=\"hidden\" name=\"buyer_credit_shipping_method\" value=\"\">
	        <input type=\"hidden\" name=\"buyer_credit_user_address_change\" value=\"\">
	        <input type=\"hidden\" name=\"tax\" value=\"0\">
			<input type=\"hidden\" name=\"no_shipping\" value=\"1\">
	        <input type=\"hidden\" name=\"return\" value=\"http://phpfm.sf.net\">
	        <input type=\"hidden\" name=\"cancel_return\" value=\"http://phpfm.sf.net\">
	</form>
	</td></tr>
    <form name=\"config_form\" action=\"".$path_info["basename"]."\" method=\"post\">
    <input type=hidden name=action value=2>
    <input type=hidden name=config_action value=0>
    <tr><td align=right width=1><nobr>".et('DocRoot').":</nobr><td>".$doc_root."</td></tr>
    <tr><td align=right><nobr>".et('FLRoot').":</nobr><td><input type=text size=60 name=newfm_root value=\"".$cfg->data['fm_root']."\" onkeypress=\"enterSubmit(event,'test_config_form(2)')\"></td></tr>
    <tr><td align=right>".et('ErrorReport').":<td><select name=newerror>
	<option value=\"0\">Disabled
	<option value=\"1\">Show Errors
	<option value=\"2\">Show Errors, Warnings and Notices
	</select></td></tr>
    <tr><td> <td><input type=button class=buto value=\"".et('SaveConfig')."\" onclick=\"test_config_form(2)\">";
    if (strlen($Warning1)) echo " <font color=red>$Warning1</font>";
    echo "
    <tr><td align=right>".et('Pass').":<td><input type=text size=30 name=newpass value=\"\" onkeypress=\"enterSubmit(event,'test_config_form(3)')\"></td></tr>
    <tr><td> <td><input type=button class=buto value=\"".et('SavePass')."\" onclick=\"test_config_form(3)\">";
    if (strlen($Warning2)) echo " <font color=red>$Warning2</font>";
    echo "</td></tr>";
    echo "
    </form>
    </table>
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        function set_select(sel,val){
            for(var x=0;x<sel.length;x++){
                if(sel.options[x].value==val){
                    sel.options[x].selected=true;
                    break;
                }
            }
        }
        set_select(document.config_form.newlang,'".$cfg->data['lang']."');
        set_select(document.config_form.newerror,'".$cfg->data['error_reporting']."');
        function test_config_form(arg){
            document.config_form.config_action.value = arg;
            document.config_form.submit();
        }
        function open_win(url){
            var w = 800;
            var h = 600;
            window.open(url, '', 'width='+w+',height='+h+',fullscreen=no,scrollbars=yes,resizable=yes,status=yes,toolbar=yes,menubar=yes,location=yes');
        }
        window.moveTo((window.screen.width-600)/2,((window.screen.height-400)/2)-20);
        window.focus();
    //-->
    </script>
    ";
    echo "</body>\n</html>";
}
function shell_form(){
    global $current_dir,$shell_form,$cmd_arg,$path_info;
    $data_out = "";
    if (strlen($cmd_arg)){
        exec($cmd_arg,$mat);
        if (count($mat)) $data_out = trim(implode("\n",$mat));
    }
    switch ($shell_form){
        case 1:
            html_header();
            echo "
            <body marginwidth=\"0\" marginheight=\"0\">
            <table border=0 cellspacing=0 cellpadding=0 align=center>
            <form name=\"data_form\">
            <tr><td><textarea name=data_out rows=36 cols=105 READONLY=\"1\"></textarea></td></tr>
            </form>
            </table>
            </body></html>";
        break;
        case 2:
            html_header();
            echo "
            <body marginwidth=\"0\" marginheight=\"0\">
            <table border=0 cellspacing=0 cellpadding=0 align=center>
            <form name=\"shell_form\" action=\"".$path_info["basename"]."\" method=\"post\">
            <input type=hidden name=current_dir value=\"$current_dir\">
            <input type=hidden name=action value=\"9\">
            <input type=hidden name=shell_form value=\"2\">
            <tr><td align=center><input type=text size=90 name=cmd_arg></td></tr>
            </form>";
            echo "
            <script language=\"Javascript\" type=\"text/javascript\">
            <!--";
            if (strlen($data_out)) echo "
                var val = '# ".html_encode($cmd_arg)."\\n".html_encode(str_replace("<","[",str_replace(">","]",str_replace("\n","\\n",str_replace("'","\'",str_replace("\\","\\\\",$data_out))))))."\\n';
                parent.frame1.document.data_form.data_out.value += val;
				parent.frame1.document.data_form.data_out.scrollTop = parent.frame1.document.data_form.data_out.scrollHeight;";
            echo "
                document.shell_form.cmd_arg.focus();
            //-->
            </script>
            ";
            echo "
            </table>
            </body></html>";
        break;
        default:
            html_header("
            <script language=\"Javascript\" type=\"text/javascript\">
            <!--
                window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
            //-->
            </script>");
            echo "
            <frameset rows=\"570,*\" framespacing=\"0\" frameborder=no>
                <frame src=\"".$path_info["basename"]."?action=9&shell_form=1\" name=frame1 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
                <frame src=\"".$path_info["basename"]."?action=9&shell_form=2\" name=frame2 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
            </frameset>
            </html>";
    }
}
function server_info(){
    if (!@phpinfo()) echo et('NoPhpinfo')."...";
    echo "<br><br>";
	    $a=ini_get_all();
	    $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $output.="<tr><th colspan=2>ini_get_all()</td></tr>";
	    while(list($key, $value)=each($a)) {
	        list($k, $v)= each($a[$key]);
	        $output.="<tr><td align=right>$key</td><td>$v</td></tr>";
	    }
	    $output.="</table>";
	echo $output;
    echo "<br><br>";
	    $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
	    $output.="<tr><th colspan=2>\$_SERVER</td></tr>";
	    foreach ($_SERVER as $k=>$v) {
	        $output.="<tr><td align=right>$k</td><td>$v</td></tr>";
	    }
	    $output.="</table>";
	echo $output;
    echo "<br><br>";
    echo "<table border=1 cellspacing=0 cellpadding=4 align=center>";
    $safe_mode=trim(ini_get("safe_mode"));
    if ((strlen($safe_mode)==0)||($safe_mode==0)) $safe_mode=false;
    else $safe_mode=true;
    $is_windows_server = (uppercase(substr(PHP_OS, 0, 3)) === 'WIN');
    echo "<tr><td colspan=2>".php_uname();
    echo "<tr><td>safe_mode<td>".($safe_mode?"on":"off");
    if ($is_windows_server) echo "<tr><td>sisop<td>Windows<br>";
    else echo "<tr><td>sisop<td>Linux<br>";
    echo "</table><br><br><table border=1 cellspacing=0 cellpadding=4 align=center>";
    $display_errors=ini_get("display_errors");
    $ignore_user_abort = ignore_user_abort();
    $max_execution_time = ini_get("max_execution_time");
    $upload_max_filesize = ini_get("upload_max_filesize");
    $memory_limit=ini_get("memory_limit");
    $output_buffering=ini_get("output_buffering");
    $default_socket_timeout=ini_get("default_socket_timeout");
    $allow_url_fopen = ini_get("allow_url_fopen");
    $magic_quotes_gpc = ini_get("magic_quotes_gpc");
    ignore_user_abort(true);
    ini_set("display_errors",0);
    ini_set("max_execution_time",0);
    ini_set("upload_max_filesize","10M");
    ini_set("memory_limit","20M");
    ini_set("output_buffering",0);
    ini_set("default_socket_timeout",30);
    ini_set("allow_url_fopen",1);
    ini_set("magic_quotes_gpc",0);
    echo "<tr><td> <td>Get<td>Set<td>Get";
    echo "<tr><td>display_errors<td>$display_errors<td>0<td>".ini_get("display_errors");
    echo "<tr><td>ignore_user_abort<td>".($ignore_user_abort?"on":"off")."<td>on<td>".(ignore_user_abort()?"on":"off");
    echo "<tr><td>max_execution_time<td>$max_execution_time<td>0<td>".ini_get("max_execution_time");
    echo "<tr><td>upload_max_filesize<td>$upload_max_filesize<td>10M<td>".ini_get("upload_max_filesize");
    echo "<tr><td>memory_limit<td>$memory_limit<td>20M<td>".ini_get("memory_limit");
    echo "<tr><td>output_buffering<td>$output_buffering<td>0<td>".ini_get("output_buffering");
    echo "<tr><td>default_socket_timeout<td>$default_socket_timeout<td>30<td>".ini_get("default_socket_timeout");
    echo "<tr><td>allow_url_fopen<td>$allow_url_fopen<td>1<td>".ini_get("allow_url_fopen");
    echo "<tr><td>magic_quotes_gpc<td>$magic_quotes_gpc<td>0<td>".ini_get("magic_quotes_gpc");
    echo "</table><br><br>";
    echo "
    <script language=\"Javascript\" type=\"text/javascript\">
    <!--
        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
        window.focus();
    //-->
    </script>";
    echo "</body>\n</html>";
}
// +--------------------------------------------------
// | Session
// +--------------------------------------------------
function logout(){
    setcookie("loggedon",0,0,"/");
    login_form();
}
function login(){
    global $pass,$auth_pass,$path_info;
    if (md5(trim($pass)) == $auth_pass){
        setcookie("loggedon",$auth_pass,0,"/");
        header ("Location: ".$path_info["basename"]."");
    } else header ("Location: ".$path_info["basename"]."?erro=1");
}
function login_form(){
    global $erro,$auth_pass,$path_info;
    html_header();
    echo "<body onLoad=\"if(parent.location.href != self.location.href){ parent.location.href = self.location.href } return true;\">\n";
    if ($auth_pass != md5("")){
        echo "
        <table border=0 cellspacing=0 cellpadding=5>
            <form name=\"login_form\" action=\"".$path_info["basename"]."\" method=\"post\">
            <tr>
            <td><b>".et('FileMan')."</b>
            </tr>
            <tr>
            <td align=left><font size=4>".et('TypePass').".</font>
            </tr>
            <tr>
            <td><input name=pass type=password size=10> <input type=submit class=buto value=\"".et('Send')."\">
            </tr>
        ";
        if (strlen($erro)) echo "
            <tr>
            <td align=left><font color=red size=4>".et('InvPass').".</font>
            </tr>
        ";
        echo "
            </form>
        </table>
             <script language=\"Javascript\" type=\"text/javascript\">
             <!--
             document.login_form.pass.focus();
             //-->
             </script>
        ";
    } else {
        echo "
        <table border=0 cellspacing=0 cellpadding=5>
            <form name=\"login_form\" action=\"".$path_info["basename"]."\" method=\"post\">
            <input type=hidden name=frame value=3>
            <input type=hidden name=pass value=\"\">
            <tr>
            <td><b>".et('FileMan')."</b>
            </tr>
            <tr>
            <td><input type=submit class=buto value=\"".et('Enter')."\">
            </tr>
            </form>
        </table>
        ";
    }
    echo "</body>\n</html>";
}
function frame3(){
    global $islinux,$cmd_arg,$chmod_arg,$zip_dir,$fm_current_root,$cookie_cache_time;
    global $dir_dest,$current_dir,$dir_before;
    global $selected_file_list,$selected_dir_list,$old_name,$new_name;
    global $action,$or_by,$order_dir_list_by;
    if (!isset($order_dir_list_by)){
        $order_dir_list_by = "1A";
        setcookie("order_dir_list_by", $order_dir_list_by , time()+$cookie_cache_time , "/");
    } elseif (strlen($or_by)){
        $order_dir_list_by = $or_by;
        setcookie("order_dir_list_by", $or_by , time()+$cookie_cache_time , "/");
    }
    html_header();
    echo "<body>\n";
    if ($action){
        switch ($action){
            case 1: // create dir
            if (strlen($cmd_arg)){
                $cmd_arg = format_path($current_dir.$cmd_arg);
                if (!file_exists($cmd_arg)){
                    @mkdir($cmd_arg,0755);
                    @chmod($cmd_arg,0755);
                    reloadframe("parent",2,"&ec_dir=".$cmd_arg);
                } else alert(et('FileDirExists').".");
            }
            break;
            case 2: // create arq
            if (strlen($cmd_arg)){
                $cmd_arg = $current_dir.$cmd_arg;
                if (!file_exists($cmd_arg)){
                    if ($fh = @fopen($cmd_arg, "w")){
                        @fclose($fh);
                    }
                    @chmod($cmd_arg,0644);
                } else alert(et('FileDirExists').".");
            }
            break;
            case 3: // rename arq ou dir
            if ((strlen($old_name))&&(strlen($new_name))){
                rename($current_dir.$old_name,$current_dir.$new_name);
                if (is_dir($current_dir.$new_name)) reloadframe("parent",2);
            }
            break;
            case 4: // delete sel
            if(strstr($current_dir,$fm_current_root)){
                if (strlen($selected_file_list)){
                    $selected_file_list = explode("<|*|>",$selected_file_list);
                    if (count($selected_file_list)) {
                        for($x=0;$x<count($selected_file_list);$x++) {
                            $selected_file_list[$x] = trim($selected_file_list[$x]);
                            if (strlen($selected_file_list[$x])) total_delete($current_dir.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                        }
                    }
                }
                if (strlen($selected_dir_list)){
                    $selected_dir_list = explode("<|*|>",$selected_dir_list);
                    if (count($selected_dir_list)) {
                        for($x=0;$x<count($selected_dir_list);$x++) {
                            $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                            if (strlen($selected_dir_list[$x])) total_delete($current_dir.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                        }
                        reloadframe("parent",2);
                    }
                }
            }
            break;
            case 5: // copy sel
            if (strlen($dir_dest)){
                if(uppercase($dir_dest) != uppercase($current_dir)){
                    if (strlen($selected_file_list)){
                        $selected_file_list = explode("<|*|>",$selected_file_list);
                        if (count($selected_file_list)) {
                            for($x=0;$x<count($selected_file_list);$x++) {
                                $selected_file_list[$x] = trim($selected_file_list[$x]);
                                if (strlen($selected_file_list[$x])) total_copy($current_dir.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                            }
                        }
                    }
                    if (strlen($selected_dir_list)){
                        $selected_dir_list = explode("<|*|>",$selected_dir_list);
                        if (count($selected_dir_list)) {
                            for($x=0;$x<count($selected_dir_list);$x++) {
                                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                                if (strlen($selected_dir_list[$x])) total_copy($current_dir.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                            }
                            reloadframe("parent",2);
                        }
                    }
                    $current_dir = $dir_dest;
                }
            }
            break;
            case 6: // move sel
            if (strlen($dir_dest)){
                if(uppercase($dir_dest) != uppercase($current_dir)){
                    if (strlen($selected_file_list)){
                        $selected_file_list = explode("<|*|>",$selected_file_list);
                        if (count($selected_file_list)) {
                            for($x=0;$x<count($selected_file_list);$x++) {
                                $selected_file_list[$x] = trim($selected_file_list[$x]);
                                if (strlen($selected_file_list[$x])) total_move($current_dir.$selected_file_list[$x],$dir_dest.$selected_file_list[$x]);
                            }
                        }
                    }
                    if (strlen($selected_dir_list)){
                        $selected_dir_list = explode("<|*|>",$selected_dir_list);
                        if (count($selected_dir_list)) {
                            for($x=0;$x<count($selected_dir_list);$x++) {
                                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                                if (strlen($selected_dir_list[$x])) total_move($current_dir.$selected_dir_list[$x],$dir_dest.$selected_dir_list[$x]);
                            }
                            reloadframe("parent",2);
                        }
                    }
                    $current_dir = $dir_dest;
                }
            }
            break;
            case 71: // compress sel
            if (strlen($cmd_arg)){
                ignore_user_abort(true);
                ini_set("display_errors",0);
                ini_set("max_execution_time",0);
                $zipfile=false;
                if (strstr($cmd_arg,".tar")) $zipfile = new tar_file($cmd_arg);
                elseif (strstr($cmd_arg,".zip")) $zipfile = new zip_file($cmd_arg);
                elseif (strstr($cmd_arg,".bzip")) $zipfile = new bzip_file($cmd_arg);
                elseif (strstr($cmd_arg,".gzip")) $zipfile = new gzip_file($cmd_arg);
                if ($zipfile){
                    $zipfile->set_options(array('basedir'=>$current_dir,'overwrite'=>1,'level'=>3));
                    if (strlen($selected_file_list)){
                        $selected_file_list = explode("<|*|>",$selected_file_list);
                        if (count($selected_file_list)) {
                            for($x=0;$x<count($selected_file_list);$x++) {
                                $selected_file_list[$x] = trim($selected_file_list[$x]);
                                if (strlen($selected_file_list[$x])) $zipfile->add_files($selected_file_list[$x]);
                            }
                        }
                    }
                    if (strlen($selected_dir_list)){
                        $selected_dir_list = explode("<|*|>",$selected_dir_list);
                        if (count($selected_dir_list)) {
                            for($x=0;$x<count($selected_dir_list);$x++) {
                                $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                                if (strlen($selected_dir_list[$x])) $zipfile->add_files($selected_dir_list[$x]);
                            }
                        }
                    }
                    $zipfile->create_archive();
                }
                unset($zipfile);
            }
            break;
            case 72: // decompress arq
            if (strlen($cmd_arg)){
                if (file_exists($current_dir.$cmd_arg)){
                    $zipfile=false;
                    if (strstr($cmd_arg,".zip")) zip_extract();
                    elseif (strstr($cmd_arg,".bzip")||strstr($cmd_arg,".bz2")||strstr($cmd_arg,".tbz2")||strstr($cmd_arg,".bz")||strstr($cmd_arg,".tbz")) $zipfile = new bzip_file($cmd_arg);
                    elseif (strstr($cmd_arg,".gzip")||strstr($cmd_arg,".gz")||strstr($cmd_arg,".tgz")) $zipfile = new gzip_file($cmd_arg);
                    elseif (strstr($cmd_arg,".tar")) $zipfile = new tar_file($cmd_arg);
                    if ($zipfile){
                        $zipfile->set_options(array('basedir'=>$current_dir,'overwrite'=>1));
                        $zipfile->extract_files();
                    }
                    unset($zipfile);
                    reloadframe("parent",2);
                }
            }
            break;
            case 8: // delete arq/dir
            if (strlen($cmd_arg)){
                if (file_exists($current_dir.$cmd_arg)) total_delete($current_dir.$cmd_arg);
                if (is_dir($current_dir.$cmd_arg)) reloadframe("parent",2);
            }
            break;
            case 9: // CHMOD
            if((strlen($chmod_arg) == 4)&&(strlen($current_dir))){
                if ($chmod_arg[0]=="1") $chmod_arg = "0".$chmod_arg;
                else $chmod_arg = "0".substr($chmod_arg,strlen($chmod_arg)-3);
                $new_mod = octdec($chmod_arg);
                if (strlen($selected_file_list)){
                    $selected_file_list = explode("<|*|>",$selected_file_list);
                    if (count($selected_file_list)) {
                        for($x=0;$x<count($selected_file_list);$x++) {
                            $selected_file_list[$x] = trim($selected_file_list[$x]);
                            if (strlen($selected_file_list[$x])) @chmod($current_dir.$selected_file_list[$x],$new_mod);
                        }
                    }
                }
                if (strlen($selected_dir_list)){
                    $selected_dir_list = explode("<|*|>",$selected_dir_list);
                    if (count($selected_dir_list)) {
                        for($x=0;$x<count($selected_dir_list);$x++) {
                            $selected_dir_list[$x] = trim($selected_dir_list[$x]);
                            if (strlen($selected_dir_list[$x])) @chmod($current_dir.$selected_dir_list[$x],$new_mod);
                        }
                    }
                }
            }
            break;
        }
        if ($action != 10) dir_list_form();
    } else dir_list_form();
    echo "</body>\n</html>";
}
function frame2(){
    global $expanded_dir_list,$ec_dir;
    if (!isset($expanded_dir_list)) $expanded_dir_list = "";
    if (strlen($ec_dir)){
        if (strstr($expanded_dir_list,":".$ec_dir)) $expanded_dir_list = str_replace(":".$ec_dir,"",$expanded_dir_list);
        else $expanded_dir_list .= ":".$ec_dir;
        setcookie("expanded_dir_list", $expanded_dir_list , 0 , "/");
    }
    show_tree();
}
function frameset(){
    global $path_info,$leftFrameWidth;
    if (!isset($leftFrameWidth)) $leftFrameWidth = 300;
    html_header();
    echo "
    <frameset cols=\"".$leftFrameWidth.",*\" framespacing=\"0\">
        <frameset rows=\"0,*\" framespacing=\"0\" frameborder=\"0\">
            <frame src=\"".$path_info["basename"]."?frame=1\" name=frame1 border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\">
            <frame src=\"".$path_info["basename"]."?frame=2\" name=frame2 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
        </frameset>
        <frame src=\"".$path_info["basename"]."?frame=3\" name=frame3 border=\"0\" marginwidth=\"0\" marginheight=\"0\">
    </frameset>
    </html>";
}
// +--------------------------------------------------
// | Open Source Contributions
// +--------------------------------------------------
 /*-------------------------------------------------
 | TAR/GZIP/BZIP2/ZIP ARCHIVE CLASSES 2.0
 | By Devin Doucette
 | Copyright (c) 2004 Devin Doucette
 | Email: darksnoopy@shaw.ca
 +--------------------------------------------------
 | Email bugs/suggestions to darksnoopy@shaw.ca
 +--------------------------------------------------
 | This script has been created and released under
 | the GNU GPL and is free to use and redistribute
 | only if this copyright statement is not removed
 +--------------------------------------------------
 | Limitations:
 | - Only USTAR archives are officially supported for extraction, but others may work.
 | - Extraction of bzip2 and gzip archives is limited to compatible tar files that have
 | been compressed by either bzip2 or gzip.  For greater support, use the functions
 | bzopen and gzopen respectively for bzip2 and gzip extraction.
 | - Zip extraction is not supported due to the wide variety of algorithms that may be
 | used for compression and newer features such as encryption.
 +--------------------------------------------------
 */
class archive
{
    function archive($name)
    {
        $this->options = array(
            'basedir'=>".",
            'name'=>$name,
            'prepend'=>"",
            'inmemory'=>0,
            'overwrite'=>0,
            'recurse'=>1,
            'storepaths'=>1,
            'level'=>3,
            'method'=>1,
            'sfx'=>"",
            'type'=>"",
            'comment'=>""
        );
        $this->files = array();
        $this->exclude = array();
        $this->storeonly = array();
        $this->error = array();
    }

    function set_options($options)
    {
        foreach($options as $key => $value)
        {
            $this->options[$key] = $value;
        }
        if(!empty($this->options['basedir']))
        {
            $this->options['basedir'] = str_replace("\\","/",$this->options['basedir']);
            $this->options['basedir'] = preg_replace("/\/+/","/",$this->options['basedir']);
            $this->options['basedir'] = preg_replace("/\/$/","",$this->options['basedir']);
        }
        if(!empty($this->options['name']))
        {
            $this->options['name'] = str_replace("\\","/",$this->options['name']);
            $this->options['name'] = preg_replace("/\/+/","/",$this->options['name']);
        }
        if(!empty($this->options['prepend']))
        {
            $this->options['prepend'] = str_replace("\\","/",$this->options['prepend']);
            $this->options['prepend'] = preg_replace("/^(\.*\/+)+/","",$this->options['prepend']);
            $this->options['prepend'] = preg_replace("/\/+/","/",$this->options['prepend']);
            $this->options['prepend'] = preg_replace("/\/$/","",$this->options['prepend']) . "/";
        }
    }

    function create_archive()
    {
        $this->make_list();

        if($this->options['inmemory'] == 0)
        {
            $Pwd = getcwd();
            chdir($this->options['basedir']);
            if($this->options['overwrite'] == 0 && file_exists($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip"? ".tmp" : "")))
            {
                $this->error[] = "File {$this->options['name']} already exists.";
                chdir($Pwd);
                return 0;
            }
            else if($this->archive = @fopen($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip"? ".tmp" : ""),"wb+"))
            {
                chdir($Pwd);
            }
            else
            {
                $this->error[] = "Could not open {$this->options['name']} for writing.";
                chdir($Pwd);
                return 0;
            }
        }
        else
        {
            $this->archive = "";
        }

        switch($this->options['type'])
        {
        case "zip":
            if(!$this->create_zip())
            {
                $this->error[] = "Could not create zip file.";
                return 0;
            }
            break;
        case "bzip":
            if(!$this->create_tar())
            {
                $this->error[] = "Could not create tar file.";
                return 0;
            }
            if(!$this->create_bzip())
            {
                $this->error[] = "Could not create bzip2 file.";
                return 0;
            }
            break;
        case "gzip":
            if(!$this->create_tar())
            {
                $this->error[] = "Could not create tar file.";
                return 0;
            }
            if(!$this->create_gzip())
            {
                $this->error[] = "Could not create gzip file.";
                return 0;
            }
            break;
        case "tar":
            if(!$this->create_tar())
            {
                $this->error[] = "Could not create tar file.";
                return 0;
            }
        }

        if($this->options['inmemory'] == 0)
        {
            fclose($this->archive);
            @chmod($this->options['name'],0644);
            if($this->options['type'] == "gzip" || $this->options['type'] == "bzip")
            {
                unlink($this->options['basedir'] . "/" . $this->options['name'] . ".tmp");
            }
        }
    }

    function add_data($data)
    {
        if($this->options['inmemory'] == 0)
        {
            fwrite($this->archive,$data);
        }
        else
        {
            $this->archive .= $data;
        }
    }

    function make_list()
    {
        if(!empty($this->exclude))
        {
            foreach($this->files as $key => $value)
            {
                foreach($this->exclude as $current)
                {
                    if($value['name'] == $current['name'])
                    {
                        unset($this->files[$key]);
                    }
                }
            }
        }
        if(!empty($this->storeonly))
        {
            foreach($this->files as $key => $value)
            {
                foreach($this->storeonly as $current)
                {
                    if($value['name'] == $current['name'])
                    {
                        $this->files[$key]['method'] = 0;
                    }
                }
            }
        }
        unset($this->exclude,$this->storeonly);
    }


    function add_files($list)
    {
        $temp = $this->list_files($list);
        foreach($temp as $current)
        {
            $this->files[] = $current;
        }
    }

    function exclude_files($list)
    {
        $temp = $this->list_files($list);
        foreach($temp as $current)
        {
            $this->exclude[] = $current;
        }
    }

    function store_files($list)
    {
        $temp = $this->list_files($list);
        foreach($temp as $current)
        {
            $this->storeonly[] = $current;
        }
    }

    function list_files($list)
    {
        if(!is_array($list))
        {
            $temp = $list;
            $list = array($temp);
            unset($temp);
        }

        $files = array();

        $Pwd = getcwd();
        chdir($this->options['basedir']);

        foreach($list as $current)
        {
            $current = str_replace("\\","/",$current);
            $current = preg_replace("/\/+/","/",$current);
            $current = preg_replace("/\/$/","",$current);
            if(strstr($current,"*"))
            {
                $regex = preg_replace("/([\\\^\$\.\[\]\|\(\)\?\+\{\}\/])/","\\\\\\1",$current);
                $regex = str_replace("*",".*",$regex);
                $dir = strstr($current,"/")? substr($current,0,strrpos($current,"/")) : ".";
                $temp = $this->parse_dir($dir);
                foreach($temp as $current2)
                {
                    if(preg_match("/^{$regex}$/i",$current2['name']))
                    {
                        $files[] = $current2;
                    }
                }
                unset($regex,$dir,$temp,$current);
            }
            else if(@is_dir($current))
            {
                $temp = $this->parse_dir($current);
                foreach($temp as $file)
                {
                    $files[] = $file;
                }
                unset($temp,$file);
            }
            else if(@file_exists($current))
            {
                $files[] = array('name'=>$current,'name2'=>$this->options['prepend'] .
                    preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($current,"/"))?
                    substr($current,strrpos($current,"/") + 1) : $current),'type'=>0,
                    'ext'=>substr($current,strrpos($current,".")),'stat'=>stat($current));
            }
        }

        chdir($Pwd);

        unset($current,$Pwd);

        usort($files,array("archive","sort_files"));

        return $files;
    }

    function parse_dir($dirname)
    {
        if($this->options['storepaths'] == 1 && !preg_match("/^(\.+\/*)+$/",$dirname))
        {
            $files = array(array('name'=>$dirname,'name2'=>$this->options['prepend'] .
                preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($dirname,"/"))?
                substr($dirname,strrpos($dirname,"/") + 1) : $dirname),'type'=>5,'stat'=>stat($dirname)));
        }
        else
        {
            $files = array();
        }
        $dir = @opendir($dirname);

        while($file = @readdir($dir))
        {
            if($file == "." || $file == "..")
            {
                continue;
            }
            else if(@is_dir($dirname."/".$file))
            {
                if(empty($this->options['recurse']))
                {
                    continue;
                }
                $temp = $this->parse_dir($dirname."/".$file);
                foreach($temp as $file2)
                {
                    $files[] = $file2;
                }
            }
            else if(@file_exists($dirname."/".$file))
            {
                $files[] = array('name'=>$dirname."/".$file,'name2'=>$this->options['prepend'] .
                    preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($dirname."/".$file,"/"))?
                    substr($dirname."/".$file,strrpos($dirname."/".$file,"/") + 1) : $dirname."/".$file),'type'=>0,
                    'ext'=>substr($file,strrpos($file,".")),'stat'=>stat($dirname."/".$file));
            }
        }

        @closedir($dir);

        return $files;
    }

    function sort_files($a,$b)
    {
        if($a['type'] != $b['type'])
        {
            return $a['type'] > $b['type']? -1 : 1;
        }
        else if($a['type'] == 5)
        {
            return strcmp(strtolower($a['name']),strtolower($b['name']));
        }
        else
        {
            if($a['ext'] != $b['ext'])
            {
                return strcmp($a['ext'],$b['ext']);
            }
            else if($a['stat'][7] != $b['stat'][7])
            {
                return $a['stat'][7] > $b['stat'][7]? -1 : 1;
            }
            else
            {
                return strcmp(strtolower($a['name']),strtolower($b['name']));
            }
        }
        return 0;
    }

    function download_file()
    {
        if($this->options['inmemory'] == 0)
        {
            $this->error[] = "Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.";
            return;
        }
        switch($this->options['type'])
        {
        case "zip":
            header("Content-type:application/zip");
            break;
        case "bzip":
            header("Content-type:application/x-compressed");
            break;
        case "gzip":
            header("Content-type:application/x-compressed");
            break;
        case "tar":
            header("Content-type:application/x-tar");
        }
        $header = "Content-disposition: attachment; filename=\"";
        $header .= strstr($this->options['name'],"/")? substr($this->options['name'],strrpos($this->options['name'],"/") + 1) : $this->options['name'];
        $header .= "\"";
        header($header);
        header("Content-length: " . strlen($this->archive));
        header("Content-transfer-encoding: binary");
        header("Cache-control: no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        print($this->archive);
    }
}

class tar_file extends archive
{
    function tar_file($name)
    {
        $this->archive($name);
        $this->options['type'] = "tar";
    }

    function create_tar()
    {
        $Pwd = getcwd();
        chdir($this->options['basedir']);

        foreach($this->files as $current)
        {
            if($current['name'] == $this->options['name'])
            {
                continue;
            }
            if(strlen($current['name2']) > 99)
            {
                $Path = substr($current['name2'],0,strpos($current['name2'],"/",strlen($current['name2']) - 100) + 1);
                $current['name2'] = substr($current['name2'],strlen($Path));
                if(strlen($Path) > 154 || strlen($current['name2']) > 99)
                {
                    $this->error[] = "Could not add {$Path}{$current['name2']} to archive because the filename is too long.";
                    continue;
                }
            }
            $block = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12",$current['name2'],decoct($current['stat'][2]),
                sprintf("%6s ",decoct($current['stat'][4])),sprintf("%6s ",decoct($current['stat'][5])),
                sprintf("%11s ",decoct($current['stat'][7])),sprintf("%11s ",decoct($current['stat'][9])),
                "        ",$current['type'],"","ustar","00","Unknown","Unknown","","",!empty($Path)? $Path : "","");

            $checksum = 0;
            for($i = 0; $i < 512; $i++)
            {
                $checksum += ord(substr($block,$i,1));
            }
            $checksum = pack("a8",sprintf("%6s ",decoct($checksum)));
            $block = substr_replace($block,$checksum,148,8);

            if($current['stat'][7] == 0)
            {
                $this->add_data($block);
            }
            else if($fp = @fopen($current['name'],"rb"))
            {
                $this->add_data($block);
                while($temp = fread($fp,1048576))
                {
                    $this->add_data($temp);
                }
                if($current['stat'][7] % 512 > 0)
                {
                    $temp = "";
                    for($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
                    {
                        $temp .= "\0";
                    }
                    $this->add_data($temp);
                }
                fclose($fp);
            }
            else
            {
                $this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
            }
        }

        $this->add_data(pack("a512",""));

        chdir($Pwd);

        return 1;

    }

    function extract_files()
    {
        $Pwd = getcwd();
        chdir($this->options['basedir']);

        if($fp = $this->open_archive())
        {
            if($this->options['inmemory'] == 1)
            {
                $this->files = array();
            }

            while($block = fread($fp,512))
            {
                $temp = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100temp/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp",$block);
                $file = array(
                    'name'=>$temp['prefix'] . $temp['name'],
                    'stat'=>array(
                        2=>$temp['mode'],
                        4=>octdec($temp['uid']),
                        5=>octdec($temp['gid']),
                        7=>octdec($temp['size']),
                        9=>octdec($temp['mtime']),
                    ),
                    'checksum'=>octdec($temp['checksum']),
                    'type'=>$temp['type'],
                    'magic'=>$temp['magic'],
                );
                if($file['checksum'] == 0x00000000)
                {
                    break;
                }
                else if($file['magic'] != "ustar")
                {
                    $this->error[] = "This script does not support extracting this type of tar file.";
                    break;
                }
                $block = substr_replace($block,"        ",148,8);
                $checksum = 0;
                for($i = 0; $i < 512; $i++)
                {
                    $checksum += ord(substr($block,$i,1));
                }
                if($file['checksum'] != $checksum)
                {
                    $this->error[] = "Could not extract from {$this->options['name']}, it is corrupt.";
                }

                if($this->options['inmemory'] == 1)
                {
                    $file['data'] = fread($fp,$file['stat'][7]);
                    fread($fp,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
                    unset($file['checksum'],$file['magic']);
                    $this->files[] = $file;
                }
                else
                {
                    if($file['type'] == 5)
                    {
                        if(!is_dir($file['name']))
                        {
                            mkdir($file['name'],0755);
                            //mkdir($file['name'],$file['stat'][2]);
                            //chown($file['name'],$file['stat'][4]);
                            //chgrp($file['name'],$file['stat'][5]);
                        }
                    }
                    else if($this->options['overwrite'] == 0 && file_exists($file['name']))
                    {
                        $this->error[] = "{$file['name']} already exists.";
                    }
                    else if($new = @fopen($file['name'],"wb"))
                    {
                        fwrite($new,fread($fp,$file['stat'][7]));
                        fread($fp,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
                        fclose($new);
                        @chmod($file['name'],0644);
                        //chmod($file['name'],$file['stat'][2]);
                        //chown($file['name'],$file['stat'][4]);
                        //chgrp($file['name'],$file['stat'][5]);
                    }
                    else
                    {
                        $this->error[] = "Could not open {$file['name']} for writing.";
                    }
                }
                unset($file);
            }
        }
        else
        {
            $this->error[] = "Could not open file {$this->options['name']}";
        }

        chdir($Pwd);
    }

    function open_archive()
    {
        return @fopen($this->options['name'],"rb");
    }
}

class gzip_file extends tar_file
{
    function gzip_file($name)
    {
        $this->tar_file($name);
        $this->options['type'] = "gzip";
    }

    function create_gzip()
    {
        if($this->options['inmemory'] == 0)
        {
            $Pwd = getcwd();
            chdir($this->options['basedir']);
            if($fp = gzopen($this->options['name'],"wb{$this->options['level']}"))
            {
                fseek($this->archive,0);
                while($temp = fread($this->archive,1048576))
                {
                    gzwrite($fp,$temp);
                }
                gzclose($fp);
                chdir($Pwd);
            }
            else
            {
                $this->error[] = "Could not open {$this->options['name']} for writing.";
                chdir($Pwd);
                return 0;
            }
        }
        else
        {
            $this->archive = gzencode($this->archive,$this->options['level']);
        }

        return 1;
    }

    function open_archive()
    {
        return @gzopen($this->options['name'],"rb");
    }
}

class bzip_file extends tar_file
{
    function bzip_file($name)
    {
        $this->tar_file($name);
        $this->options['type'] = "bzip";
    }

    function create_bzip()
    {
        if($this->options['inmemory'] == 0)
        {
            $Pwd = getcwd();
            chdir($this->options['basedir']);
            if($fp = bzopen($this->options['name'],"wb"))
            {
                fseek($this->archive,0);
                while($temp = fread($this->archive,1048576))
                {
                    bzwrite($fp,$temp);
                }
                bzclose($fp);
                chdir($Pwd);
            }
            else
            {
                $this->error[] = "Could not open {$this->options['name']} for writing.";
                chdir($Pwd);
                return 0;
            }
        }
        else
        {
            $this->archive = bzcompress($this->archive,$this->options['level']);
        }

        return 1;
    }

    function open_archive()
    {
        return @bzopen($this->options['name'],"rb");
    }
}

class zip_file extends archive
{
    function zip_file($name)
    {
        $this->archive($name);
        $this->options['type'] = "zip";
    }

    function create_zip()
    {
        $files = 0;
        $offset = 0;
        $central = "";

        if(!empty($this->options['sfx']))
        {
            if($fp = @fopen($this->options['sfx'],"rb"))
            {
                $temp = fread($fp,filesize($this->options['sfx']));
                fclose($fp);
                $this->add_data($temp);
                $offset += strlen($temp);
                unset($temp);
            }
            else
            {
                $this->error[] = "Could not open sfx module from {$this->options['sfx']}.";
            }
        }

        $Pwd = getcwd();
        chdir($this->options['basedir']);

        foreach($this->files as $current)
        {
            if($current['name'] == $this->options['name'])
            {
                continue;
            }
            $translate =  array('Ç'=>pack("C",128),'ü'=>pack("C",129),'é'=>pack("C",130),'â'=>pack("C",131),'ä'=>pack("C",132),
                                'à'=>pack("C",133),'å'=>pack("C",134),'ç'=>pack("C",135),'ê'=>pack("C",136),'ë'=>pack("C",137),
                                'è'=>pack("C",138),'ï'=>pack("C",139),'î'=>pack("C",140),'ì'=>pack("C",141),'Ä'=>pack("C",142),
                                'Å'=>pack("C",143),'É'=>pack("C",144),'æ'=>pack("C",145),'Æ'=>pack("C",146),'ô'=>pack("C",147),
                                'ö'=>pack("C",148),'ò'=>pack("C",149),'û'=>pack("C",150),'ù'=>pack("C",151),'_'=>pack("C",152),
                                'Ö'=>pack("C",153),'Ü'=>pack("C",154),'£'=>pack("C",156),'¥'=>pack("C",157),'_'=>pack("C",158),
                                'ƒ'=>pack("C",159),'á'=>pack("C",160),'í'=>pack("C",161),'ó'=>pack("C",162),'ú'=>pack("C",163),
                                'ñ'=>pack("C",164),'Ñ'=>pack("C",165));
            $current['name2'] = strtr($current['name2'],$translate);

            $timedate = explode(" ",date("Y n j G i s",$current['stat'][9]));
            $timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) |
                ($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);

            $block = pack("VvvvV",0x04034b50,0x000A,0x0000,(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate);

            if($current['stat'][7] == 0 && $current['type'] == 5)
            {
                $block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000);
                $block .= $current['name2'] . "/";
                $this->add_data($block);
                $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                    (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                    0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
                $central .= $current['name2'] . "/";
                $files++;
                $offset += (31 + strlen($current['name2']));
            }
            else if($current['stat'][7] == 0)
            {
                $block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000);
                $block .= $current['name2'];
                $this->add_data($block);
                $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                    (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                    0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
                $central .= $current['name2'];
                $files++;
                $offset += (30 + strlen($current['name2']));
            }
            else if($fp = @fopen($current['name'],"rb"))
            {
                $temp = fread($fp,$current['stat'][7]);
                fclose($fp);
                $crc32 = crc32($temp);
                if(!isset($current['method']) && $this->options['method'] == 1)
                {
                    $temp = gzcompress($temp,$this->options['level']);
                    $size = strlen($temp) - 6;
                    $temp = substr($temp,2,$size);
                }
                else
                {
                    $size = strlen($temp);
                }
                $block .= pack("VVVvv",$crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000);
                $block .= $current['name2'];
                $this->add_data($block);
                $this->add_data($temp);
                unset($temp);
                $central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
                    (isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
                    $crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,0x00000000,$offset);
                $central .= $current['name2'];
                $files++;
                $offset += (30 + strlen($current['name2']) + $size);
            }
            else
            {
                $this->error[] = "Could not open file {$current['name']} for reading. It was not added.";
            }
        }

        $this->add_data($central);

        $this->add_data(pack("VvvvvVVv",0x06054b50,0x0000,0x0000,$files,$files,strlen($central),$offset,
            !empty($this->options['comment'])? strlen($this->options['comment']) : 0x0000));

        if(!empty($this->options['comment']))
        {
            $this->add_data($this->options['comment']);
        }

        chdir($Pwd);

        return 1;
    }
}
// +--------------------------------------------------
// | THE END
// +--------------------------------------------------
?>