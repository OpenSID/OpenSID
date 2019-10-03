<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->config('ion_auth');
		$this->load->model(array('common_model','users_modal'));
		$this->load->module('template');

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
	}

	/*
		* Send Email to specific user
	*/
	public function index()
	{
		$name       = post('name');
		$from_email = post('from_email');
		$to         = post('to');
		$subject    = post('subject');
		$msg        = post('msg');

        $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );
            
        $this->email->initialize($config);    
	
		$this->email->from($from_email, $name);
		$this->email->to($to);

		$this->email->subject($subject);
		$this->email->message($msg);


	 	$result = $this->email->send();

	 	if ($result) 
	 	{
	 		$msg = "Your Email Send is Successfully Send";
	 		$this->session->set_flashdata('success',$msg);
	 		redirect('users','refresh');
	 	}
	 	else
	 	{
	 		$msg = "Error! Can not Send Email";
	 		$this->session->set_flashdata('error', $msg);
	 		redirect('users','refresh');
	 	}
	}

	/*
		* Get user data for Email
	*/
	public function get_user_email($value='')
	{
		$where_id = array('id' => $value);

		$result = $this->common_model->getAllData('users','email',$where_id);

		echo json_encode($result);
	}
	/*
		* Get all user for Email
	*/
	public function email_members($value='')
	{
		// list the users
		$data['users'] = $this->ion_auth->users()->result();

		$data['page'] = "email/email_members";
		$this->template->template_view($data);	
	}

	/*
		* Send Email to Selected Members
		* Send Email to Group
	*/

	public function send_email($value='')
	{
		if (post("check") == 'members') 
		{
			if (empty(post('foo'))) 
			{
				$msg = "Please Select at least one user to send Email";
				$this->session->set_flashdata('error',$msg);
				redirect('email/email_members','refresh');	
			}

			$name     = $this->config->item('site_title','ion_auth');
			$email_to = implode(',', post('foo'));
			$from     = $this->config->item('admin_email','ion_auth');
			$subject  = post('title');
			$message  = post('msg');

			$config = array(
								'mailtype' => 'html',
								'charset'  => 'utf-8',
								'wordwrap' => TRUE
							);
				
			$this->load->library('email', $config);

			$this->email->from($from,$name);
			$this->email->to($email_to);
			$this->email->subject($subject);
			$this->email->message($message);
			$send = $this->email->send();
				
			if($send) 
			{
				$msg = "Your Email is Send Successfully to Users";
				$this->session->set_flashdata('success',$msg);
				redirect('email/email_members','refresh');
			}
			else
			{
				$msg = "Email Can Not Send";
				$this->session->set_flashdata('error',$msg);
				redirect('email/email_members','refresh');
			}
		}
		elseif (post("check") == 'group')
		{
			$name     = $this->config->item('site_title','ion_auth');
			$from     = $this->config->item('admin_email','ion_auth');
			$subject  = post('title');
			$message  = post('msg');

			$group    = post('group_name');

			$get_emails = $this->users_modal->get_group_users($group);

			$arr = array();

			foreach ($get_emails as $key => $value) 
			{	
				array_push($arr, $value->email);
			}

			$email_to = implode(',', $arr);

			$config = array(
								'mailtype' => 'html',
								'charset'  => 'utf-8',
								'wordwrap' => TRUE
							);
				
			$this->load->library('email', $config);

			$this->email->from($from,$name);
			$this->email->to($email_to);
			$this->email->subject($subject);
			$this->email->message($message);
			$send = $this->email->send();
				
			if($send) 
			{
				$msg = "Your Email is Send Successfully to Group";
				$this->session->set_flashdata('success',$msg);
				redirect('email/email_members','refresh');
			}
			else
			{
				$msg = "Email Can Not Send";
				$this->session->set_flashdata('error',$msg);
				redirect('email/email_members','refresh');
			}
		}
		else
		{
			$msg = "Error! Please Contact with Admin";
			$this->session->set_flashdata('error',$msg);
			redirect('email/email_members','refresh');
		}
	}
}

/* End of file Email.php */
/* Location: ./application/modules/users/controllers/Email.php */
