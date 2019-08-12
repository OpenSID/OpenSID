<?php

// https://itsolutionstuff.com/post/how-to-create-dynamic-sitemap-in-php-codeigniterexample.html

defined('BASEPATH') OR exit('No direct script access allowed');


class Sitemap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->load->database();
		$query = $this->db
			->select('a.*, YEAR(tgl_upload) as thn, MONTH(tgl_upload) as bln, DAY(tgl_upload) as hri')
			->from("artikel a")
			->get();
		$data['artikel'] = $query->result();

		$this->load->view('sitemap', $data);
	}
}
