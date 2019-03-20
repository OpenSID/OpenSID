<?php class Keuangan_model extends CI_model {

	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Hapus tabel klasifikasi_surat dan ganti isinya
	 * dengan data dari berkas csv.
	 * Baris pertama berisi nama kolom tabel.
	*/
	public function impor($file)
	{
		ini_set('auto_detect_line_endings', '1');
		if (($handle = fopen($file, "r")) == FALSE)
		{
			$_SESSION['success'] = -1;
			$_SESSION['error_msg'] = 'Berkas tidak ada atau bermasalah';
			return;
		}
    $dbName = $_SERVER["DOCUMENT_ROOT"] . "products\products.mdb";
    if (!file_exists($dbName)) {
        die("Could not find database file.");
    }
    $db = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbName; Uid=; Pwd=;");
    print_r($db);die();

	}

}
