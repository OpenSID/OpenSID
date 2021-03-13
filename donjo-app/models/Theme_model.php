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

	// Mengambil latar belakang website ubahan
	public function latar_website()
	{
		$ubahan_tema = "desa/pengaturan/{$this->theme}/images/latar_website.jpg";
		$bawaan_tema = "$this->theme_folder/$this->theme/assets/css/images/latar_website.jpg";
		$latar_website = is_file($ubahan_tema) ? $ubahan_tema : $bawaan_tema;
		$latar_website = is_file($latar_website) ? $latar_website : NULL;
		return $latar_website;
	}

	public function lokasi_latar_website()
	{
		$folder = "desa/pengaturan/{$this->theme}/images/";
		mkdir($folder, 0775, true);
		return $folder;
	}

	// Mengambil latar belakang login ubahan
	public function latar_login()
	{
		$ubahan = LATAR_LOGIN . "latar_login.jpg";
		$latar_login = is_file($ubahan) ? $ubahan : NULL;
		return $latar_login;
	}

	// Mengambil latar belakang login mandiri ubahan
	public function latar_login_mandiri()
	{
		$ubahan = LATAR_LOGIN . "latar_login_mandiri.jpg";
		$latar_login_mandiri = is_file($ubahan) ? $ubahan : NULL;
		return $latar_login_mandiri;
	}

}
?>
