<?php
class Core {
	private $fileConfig = 'database.php';
	private $pathFolder = '../application/config';
	private $bigDumpConnection = 'bigdumpconnection.php';
	private $pathConfig;
	function __construct($pathFolder = NULL,$fileConfig = NULL){		
		if(!empty($pathFolder)){
			$this->pathFolder = $pathFolder;
		}
		if(!empty($fileConfig)){
			$this->fileConfig = $fileConfig;
		}
		$this->pathConfig = $this->pathFolder.'/'.$this->fileConfig;
	}
	function getPathConfig(){
		return $this->pathConfig;
	}
	function getPathFolder(){
		return $this->pathFolder;
	}
	function getFileConfig(){
		return $this->fileConfig;
	}
	function checkEmpty($data)
	{
	    if(!empty($data['hostname']) && !empty($data['username']) && !empty($data['database'])  && !empty($data['template'])){
	        return true;
	    }else{
	        return false;
	    }
	}

	function show_message($type,$message) {
		return $message;
	}
	
	function getAllData($data) {
		return $data;
	}

	function write_config($data) {

        $template_path 	= 'templatevthree.php';
		$output_path 	= $this->pathConfig;

		$database_file = file_get_contents($template_path);
		
		$new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
		$new  = str_replace("%USERNAME%",$data['username'],$new);
		$new  = str_replace("%PASSWORD%",$data['password'],$new);
		$new  = str_replace("%DATABASE%",$data['database'],$new);		
		$handle = fopen($output_path,'w+');
		@chmod($output_path,0777);
		
		if(is_writable(dirname($output_path))) {			
			if(fwrite($handle,$new)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	function write_bigdump_config($filename,$data) {        
		$output_path 	= $this->bigDumpConnection;
		$text = <<<HTML
		<?php
			\$params = array(
				'hostname' => '{$data['hostname']}',
				'username' => '{$data['username']}',
				'password' => '{$data['password']}',
				'database' => '{$data['database']}',
				'filename' => '{$filename}'
			); 
		?>
HTML;
				
		$handle = fopen($output_path,'w+');
		@chmod($output_path,0777);
		
		if(is_writable(dirname($output_path))) {			
			if(fwrite($handle,$text)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	

	function checkFile(){
	    $output_path = $this->pathConfig;
	    
	    if (file_exists($output_path)) {
           return true;
        } 
        else{
            return false;
        }
	}
}