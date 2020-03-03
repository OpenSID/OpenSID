<?php
class Migrasi_2003_ke_2004 extends CI_model {

	public function up()
	{
		//ganti nama folder surat
		$lama = "surat";
		$baru = "template-surat";
		$desa = "desa";
		rename($lama, $baru);
		rename($desa.'/'.$lama, $desa.'/'.$baru);	
	}
}
