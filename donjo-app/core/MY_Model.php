<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

  /**
   * Constructor
   */
  function __construct()
  {
    parent::__construct();
	}

	// Konversi url menu menjadi slug tanpa mengubah data
	public function menu_slug($url)
	{
		$cut = explode('/', $url);
		// $CI = & get_instance();

		switch ($cut[0])
		{
			case 'artikel':
				$this->load->model('first_artikel_m');
				$data = $this->first_artikel_m->get_artikel($cut[1]);

				$url = ($data) ? ($cut[0].'/'.buat_slug($data)) : ($url);
				break;

			default:
				$url = 'first/'.$url;
				break;
		}

		return site_url($url);
	}

}