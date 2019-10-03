<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	public function template_view($data = NULL)
	{
		$this->load->view('dashboard',$data);
	}

}

/* End of file Template.php */
/* Location: ./application/controllers/Template.php */

/* End of file Template.php */
/* Location: ./application/controllers/Template.php */
