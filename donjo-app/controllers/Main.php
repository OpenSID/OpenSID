<?php
define('GRUP_ADMINISTRATOR', 1);
define('GRUP_OPERATOR',      2);
define('GRUP_REDAKSI',       3);
define('GRUP_KONTRIBUTOR',   4);
 
class Main extends CI_Controller
{


    function index() {
        $this->session->unset_userdata('request_uri');
        
        if ($this->session->user_id) {
            switch($this->session->role) {
                case GRUP_ADMINISTRATOR:
                case GRUP_OPERATOR:
                    redirect('hom_desa');
                    break;
                
                case GRUP_REDAKSI:
                case GRUP_KONTRIBUTOR:
                    redirect('web');
            }
            return;
        }
        // Jika offline_mode aktif, tidak perlu menampilkan halaman website
        if ($this->setting->offline_mode > 0) {
            redirect('siteman');
        } else {
            redirect('first');
        }
    }
}
