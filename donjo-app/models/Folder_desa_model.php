<?php class Folder_desa_model extends CI_Model {

/*
	Dimasukkan di autoload. Supaya folder desa dibuat secara otomatis menggunakan
	desa-contoh apabila belum ada. Yaitu pada pertama kali menginstall OpenSID.

	Perubahan folder desa pada setiap rilis dilakukan sebagai bagian dari
	Database > Migrasi DB.
*/
	public function __construct()
	{
		parent::__construct();
		$this->periksa_folder_desa();
	}

	public function periksa_folder_desa()
	{
		$this->salin_contoh();
	}

	// Tambahkan index.html di setiap sub-folder, supaya tidak bisa diakses langsung
	// Gunakan file index.html yang disediakan di desa-contoh
	public function amankan_folder_desa($src='desa')
	{
    if (!file_exists($src.'/index.html'))
    {
      copy('desa-contoh/index.html', $src.'/index.html');
    }
    foreach (scandir($src) as $file)
    {
      $srcfile = rtrim($src, '/') . '/' . $file;
      if (!is_readable($srcfile))
       {
        continue;
      }
      if ($file != '.' && $file != '..')
      {
        if (is_dir($srcfile))
        {
          if (!file_exists($srcfile.'/index.html'))
          {
            copy('desa-contoh/index.html', $srcfile.'/index.html');
          }
          $this->amankan_folder_desa($srcfile);
        }
      }
    }
  }

	private function salin_contoh()
	{
		if (!file_exists('desa'))
		{
			mkdir('desa');
			xcopy('desa-contoh', 'desa');
		}
	}

	public function salin_file($cek = 'desa/upload', $cari = '.htaccess', $contoh = 'desa-contoh/upload/media/.htaccess')
	{
		if (!file_exists("$cek/$cari")) copy($contoh, "$cek/$cari");

		foreach (glob("$cek/*", GLOB_ONLYDIR) as $folder)
		{
			$file = "$folder/$cari";

			if (!file_exists($file)) copy($contoh, $file);

			$this->salin_file("$folder");
		}
	}
}
