<?php

use App\Models\Bantuan;
use App\Enums\Statistik\StatistikEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('laporan_penduduk_model');
    }

    public function index($slug = null)
    {
        $key = StatistikEnum::keyFromSlug($slug);

        if (!$this->web_menu_model->menu_aktif('statistik/' . $key)) {
            show_404();
        }

        $data = $this->includes;

        $data['heading']          = StatistikEnum::labelFromSlug($slug);
        $data['stat']             = $this->laporan_penduduk_model->list_data($key);
        $data['tipe']             = 0;
        $data['slug_aktif']       = $slug;

        $this->_get_common_data($data);

        $this->set_template('layouts/stat.tpl.php');
        $this->load->view($this->template, $data);
    }
}
