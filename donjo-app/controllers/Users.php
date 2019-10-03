<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
        
	}      

	public function index()
	{
        // set the flash data error message if there is one
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		// list the users
		$data['users'] = $this->ion_auth->users()->result();
		foreach ($data['users'] as $k => $user)
		{
			$data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

        $this->session->set_flashdata('message', $this->ion_auth->messages());

        $header = $this->header_model->get_config();
        $data['page'] = "users/view_users";
	$this->load->view('header',$header);		
	$this->load->view('dashboard',$data);

        }

	public function create_user()
	{
        if (!$this->ion_auth->is_admin()) 
        {
            return show_error("You Must Be an Administrator To view This Page");
        }
        
	$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $data['identity_column'] = $identity_column;

        $data['groups'] = $this->ion_auth->groups()->result();

        $data['page'] = "users/create_user";
        
        $header = $this->header_model->get_config();
        $this->load->view('header',$header);		
	$this->load->view('dashboard',$data);
        
	}

	// create a new user
	public function add_user()
    {
        $this->form_validation->set_rules('first_name', 'First name','trim|required');
        $this->form_validation->set_rules('last_name', 'Last name','trim|required');
        $this->form_validation->set_rules('username','Username','trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('email','Email','trim|valid_email|required');
        $this->form_validation->set_rules('company','Company','trim|required');
        $this->form_validation->set_rules('phone','Phone No','trim|required');
        $this->form_validation->set_rules('password','Password','trim|min_length[8]|max_length[20]|required');
        $this->form_validation->set_rules('confirm_password','Confirm password','trim|matches[password]|required');
        $this->form_validation->set_rules('group','Group','trim|required');
 
        if($this->form_validation->run() === FALSE)
        {
            $msg = "Please Fill The Form Correctly <br> User name And E-Mail Must Be unique";
            $this->session->set_flashdata('error',$msg);
            redirect('Users/create_user');
        }
        else
        {
            $first_name = $this->input->post('first_name');
            $last_name  = $this->input->post('last_name');
            $username   = $this->input->post('username');
            $email      = $this->input->post('email');
            $company    = $this->input->post('company');
            $phone      = $this->input->post('phone');
            $password   = $this->input->post('password');
            $gp         = $this->input->post('group');
            
            $group = array($gp);
 
            $additional_data = array(
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'date'       => date('y-m-d'),   
                'company'    => $company,   
                'phone'      => $phone   
            );

            if($this->ion_auth->register($username,$password,$email,$additional_data,$group))
            {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('Users','refresh');
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('Users/create_user','refresh');
            }
        }
    }  

    // edit a user
    public function edit_user($id)
    {
        $data['title'] = $this->lang->line('edit_user_heading');

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
        {
            $msg = "You Must Be an Administrator To View This Page.";
            $this->session->set_flashdata('error', $msg);
            redirect('users','refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        $groups=$this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('username', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

        if (isset($_POST) && !empty($_POST))
        {
            // update the password if it was posted
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'username'   => $this->input->post('username'),
                    'email'      => $this->input->post('email'),
                    'company'    => $this->input->post('company'),
                    'phone'      => $this->input->post('phone'),
                );

                // update the password if it was posted
                if ($this->input->post('password'))
                {
                    $data['password'] = $this->input->post('password');
                }

                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin())
                {
                    //Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }

                    }
                }

            // check to see if we are updating the user
               if($this->ion_auth->update($user->id, $data))
                {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages() );

                    if ($this->ion_auth->is_admin())
                    {
                        redirect('users', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }

                }
                else
                {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors() );
                    if ($this->ion_auth->is_admin())
                    {
                        redirect('users', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }

                }
            }
            else
            {
                $msg = "Password do not match";
                $this->session->set_flashdata('error',$msg);
                redirect('users/edit_user/'.$id,'refresh');
            }
        }

        // display the edit user form
        $data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $data['user'] = $user;
        $data['groups'] = $groups;
        $data['currentGroups'] = $currentGroups;

        $data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'required'  => 'required',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'required'  => 'required',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $data['username'] = array(
            'name'  => 'username',
            'id'    => 'username',
            'type'  => 'text',
            'required'  => 'required',
            'class' => "form-control",
            'value' => $this->form_validation->set_value('last_name', $user->username),
        );
        $data['email'] = array(
            'name'  => 'email',
            'id'    => 'email',
            'type'  => 'email',
            'required'  => 'required',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->email),
        );
        $data['company'] = array(
            'name'      => 'company',
            'id'        => 'company',
            'class'     => 'form-control',
            'type'      => 'text',
            'required'  => 'required',
            'value'     => $this->form_validation->set_value('company', $user->company),
        );
        $data['phone'] = array(
            'name'      => 'phone',
            'id'        => 'phone',
            'class'     => 'form-control',
            'required'  => 'required',
            'type'      => 'text',
            'value'     => $this->form_validation->set_value('phone', $user->phone),
        );
        $data['password'] = array(
            'name'  => 'password',
            'id'    => 'password',
            'type'  => 'password',
            'class' => 'form-control',
        );
        $data['password_confirm'] = array(
            'name'  => 'password_confirm',
            'id'    => 'password_confirm',
            'type'  => 'password',
            'class' => 'form-control',
        );

        $data['page'] = "users/edit_user";
	$header = $this->header_model->get_config();
        $this->load->view('header',$header);		
	$this->load->view('dashboard',$data);
    }

    public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
    {

        $this->viewdata = (empty($data)) ? $this->data: $data;

        $view_html = $this->load->view($view, $this->viewdata, $returnhtml);

        if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
    }

    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    // deactivate the user
    public function deactivate($id = NULL)
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();
            $this->data['page'] = "users/deactivate_user";

            $header = $this->header_model->get_config();
            $this->load->view('header',$header);		
	    $this->load->view('dashboard',$this->data);
        }
        else
        {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes')
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
                {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                {
                    $this->ion_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('Users', 'refresh');
        }
    }

    // activate the user
    public function activate($id, $code=false)
    {
        if ($code !== false)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("users", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("users", 'refresh');
        }
    } 
    
     public function Profile()
     {
        $user_id = array('id' => $this->session->userdata('user_id'));

        // get user profile data
        $data['user_profile'] = $this->common_model->update_data($user_id,'users');

        $user = $this->ion_auth->user()->row();
        
        // display the form
        // set the flash data error message if there is one
        $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

        $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

        $data['old_password'] = array(
            'name'     => 'old',
            'id'       => 'old',
            'type'     => 'password',
            'class'    => 'form-control',
            'required' => 'required',
        );
        $data['new_password'] = array(
            'name'     => 'new',
            'id'       => 'new',
            'class'    => 'form-control',
            'type'     => 'password',
            'required' => 'required',
            'minlength'=> 8,
            'pattern'  => '^.{'.$data['min_password_length'].'}.*$',
        );
        $data['new_password_confirm'] = array(
            'name'    => 'new_confirm',
            'id'      => 'new_confirm',
            'class'   => 'form-control',
            'type'    => 'password',
            'required' => 'required',
            'pattern' => '^.{'.$data['min_password_length'].'}.*$',
        );
        $data['user_id'] = array(
            'name'  => 'user_id',
            'id'    => 'user_id',
            'type'  => 'hidden',
            'value' => $user->id,
        );

        $data['page'] = "users/user_profile";
	$header = $this->header_model->get_config();
        $this->load->view('header',$header);		
	$this->load->view('dashboard',$data);
     }

     public function edit_profile()
     {
        if ($this->input->post()) 
        {
           $first_name = post('first_name');
           $last_name  = post('last_name');
           $email      = post('email');
           $mobile_no  = post('mobile_no');

           $data = array(
                         'phone'      => $mobile_no,
                         'first_name' => $first_name,
                         'email'      => $email,
                         'last_name'  => $last_name, 
                         );
           $user_id = $this->session->userdata('user_id');

           $result = $this->common_model->update($user_id,$data,'users');

           if ($result) 
           {
               $msg =  "Profile Updated Successfully";
               $this->session->set_flashdata('success',$msg);
               redirect('users/profile','refresh');
           } else {
               $msg =  "Profile Updated Successfully";
               $this->session->set_flashdata('error',$msg);
               redirect('users/profile','refresh');
           }
           
        } 
        else {

            $user_id = array('id' => $this->session->userdata('user_id'));

            // get user profile data
            $data['user_profile'] = $this->common_model->update_data($user_id,'users');

            $data['page'] = "users/edit_user_profile";
            $header = $this->header_model->get_config();
            $this->load->view('header',$header);		
	     $this->load->view('dashboard',$data);	  
        }
        
        
     }
     
     public function update_avatar()
     {
        $config['upload_path']          = './assets/files/user_pict/';
        $config['allowed_types']        = 'gif|jpg|png';

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('chang_image'))
        {
                $error = array('error' => $this->upload->display_errors());

                pr($error); die();
        }
        else
        {
                $data = array('upload_data' => $this->upload->data());

                $image = $data['upload_data']['file_name'];

        }

        $data = array('user_img' => $image);

        $user_id = $this->session->userdata('user_id');

        $result = $this->common_model->update($user_id,$data,'users');

           if ($result) 
           {
               $msg =  "Image Updated Successfully";
               $this->session->set_flashdata('success',$msg);
               redirect('users/Profile','refresh');
           } else {
               $msg =  "Image Updated Successfully";
               $this->session->set_flashdata('error',$msg);
               redirect('users/Profile','refresh');
           }
     }
}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
