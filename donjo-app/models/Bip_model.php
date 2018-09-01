<?php

class Bip_model extends CI_Model{

	function __construct($data){
		parent::__construct();
		ini_set('memory_limit', '512M');
		set_time_limit(3600);
		$this->load->helper('excel');
		$this->format_bip = $this->cari_format_bip($data);
		$this->data = $data;
	}

	/**
	 * Tentunkan format BIP yang akan digunakan
	 *
	 * @access	private
	 * @param		sheet		data excel berisi bip
	 * @return	model 	format BIP yang akan digunakan
	 */
	private function cari_format_bip($data){
		$data_sheet = $data->sheets[0]['cells'];
		if ($data_sheet[1][1] == "BUKU INDUK PENDUDUK WNI") {
		  require_once APPPATH.'/models/bip2016_model.php';
			return new BIP2016_Model();
		} elseif (strpos($data_sheet[1][2],"BUKU INDUK KEPENDUDUKAN") !== FALSE AND strpos($data_sheet[1][2],"(DAFTAR  KELUARGA)") !== FALSE) {
		  require_once APPPATH.'/models/bip2016_luwutimur_model.php';
			return new BIP2016_Luwutimur_Model();
		} elseif (strpos($data_sheet[1][16],"Wjb KTP") !== FALSE AND strpos($data_sheet[1][17],"KTP-eL") !== FALSE) {
		  require_once APPPATH.'/models/bip_ektp_model.php';
			return new BIP_Ektp_Model();
		} else {
		  require_once APPPATH.'/models/bip2012_model.php';
			return new BIP2012_Model();
		}
	}

	function impor_bip(){
		$this->format_bip->impor_data_bip($this->data);
	}

}

?>
