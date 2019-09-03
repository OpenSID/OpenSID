<?php

class Core {
function write_config($domain) {
	$template_path 	= 'config/config.php';
	$output_path	= '../donjo-app/config/config.php';
	$config_file = file_get_contents($template_path);
	$saved  = str_replace("%DEFAULT_URL%",$domain,$config_file);
	$handle= fopen($output_path,'w+');
	@chmod($output_path,0777);
	if(is_writable($output_path)) {
		if(fwrite($handle,$saved)) {
			@chmod($output_path,0644);
			return true;
		} else {
			return false;
		}

	}
	
	else {
		return false;
	}
}
	function validate_post($data)
	{
		return !empty($data['hostname']) && !empty($data['username']) && !empty($data['database']);
		
	}
	function show_message($type,$message) {
		return $message;
	}
	function write_database($data) {
		$template_path 	= 'config/database_donjo.php';
		$output_path	= '../donjo-app/config/database.php';
		$database_file = file_get_contents($template_path);
		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);
		$handle = fopen($output_path,'w+');
		@chmod($output_path,0777);
		if(is_writable($output_path)) {
			if(fwrite($handle,$new)) {
				return true;
			} else {
				return false;
			}

		} 
		else {
			return false;
		}
	}
	function write_database1($data) {
		$template_path 	= 'config/database_desa_contoh.php';
		$output_path	= '../desa-contoh/config/database.php';
		$database_file = file_get_contents($template_path);
		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);
		$handle = fopen($output_path,'w+');
		@chmod($output_path,0777);
		if(is_writable($output_path)) {
			if(fwrite($handle,$new)) {
				return true;
			} else {
				return false;
			}

		} 
		else {
			return false;
		}
	}


}