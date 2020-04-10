<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dokumen_web extends Web_Controller
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('web_dokumen_model');
		$this->load->helper('download');
	}

  /**
	 * Unduh berkas berdasarkan kolom dokumen.id
	 * @param   integer  $id_dokumen  Id berkas pada koloam dokumen.id
	 * @return  void
	 */
	public function unduh_berkas($id_dokumen)
	{
		// Ambil nama berkas dari database
		$berkas = $this->web_dokumen_model->get_nama_berkas($id_dokumen);
		if ($berkas)
			ambilBerkas($berkas, NULL, NULL, LOKASI_DOKUMEN);
		else
			$this->output->set_status_header('404');
	}
}
