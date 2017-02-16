<?php
class Apis extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	public function view($page = 'indeks'){
	if ( ! file_exists(APPPATH.'/views/apis/'.$page.'.php')){
		
		
		echo $page;
	}
}
?>