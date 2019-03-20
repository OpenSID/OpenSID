<?php class Theme_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * Tema sistem ada di subfolder themes/
	 * Tema buatan sistem ada di subfolder desa/themes/
	*/
	public function list_all()
	{
		$tema_sistem = glob('themes/*' , GLOB_ONLYDIR);
		$tema_desa = glob('desa/themes/*' , GLOB_ONLYDIR);
		$tema_semua = array_merge($tema_sistem, $tema_desa);
		$list_tema = array();
		foreach ($tema_semua as $tema){
			$list_tema[] = str_replace('themes/', '', $tema);
		}
		return $list_tema;
	}

}
?>
