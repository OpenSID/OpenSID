<?php

use App\Models\Config;

defined('BASEPATH') || exit('No direct script access allowed');

class Ganti_password extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        $id                  = $_SESSION['user'];
        $data['main']        = $this->user_model->get_user($id);
        $data['header']      = Config::first();
        $data['latar_login'] = to_base64(default_file(LATAR_SITEMAN, DEFAULT_LATAR_SITEMAN));
        $this->load->view('setting_pwd', $data);
    }

    public function update($id = '')
    {
        if ($this->user_model->update_password($id)) {
            log_message('error', 'aaa');
            redirect('hom_sid');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}
