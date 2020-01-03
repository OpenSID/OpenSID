<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mandiri_web extends Web_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('web_dokumen_model');
		$this->load->helper('download');
		
		if (!isset($_SESSION['mandiri'])) {
			redirect('first');
		}
	}

  /**
	 * Unduh berkas berdasarkan kolom dokumen.id
	 * @param   integer  $id_dokumen  Id berkas pada koloam dokumen.id
	 * @return  void
	 */
	public function unduh_berkas($id_dokumen, $id_pend)
	{
		// Ambil nama berkas dari database
		$berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen, $id_pend);
		if ($berkas)
			ambilBerkas($berkas, NULL, NULL, LOKASI_DOKUMEN);
		else
			$this->output->set_status_header('404');
	}

}
