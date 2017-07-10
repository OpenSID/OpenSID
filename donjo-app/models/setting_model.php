<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_Model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
    $pre = array();
    $CI = &get_instance();

    if ($this->setting) return;
    if ($this->config->item("useDatabaseConfig")) {

      // Terpaksa menjalankan migrasi, karena apabila tabel setting_aplikasi
      // belum ada, aplikasi tidak bisa di-load, karena model ini di-autoload
      if (!$this->db->table_exists('setting_aplikasi') ) {
        $this->load->model('database_model');
        $this->database_model->migrasi_db_cri();
      }

      $pr = $this->db->order_by('key')->get("setting_aplikasi")->result();
      foreach($pr as $p)
      {
        $pre[addslashes($p->key)] = addslashes($p->value);
      }
    }
    else
    {
      $pre = (object) $CI->config->config;
    }
    $CI->setting = (object) $pre;
    $CI->list_setting = $pr; // Untuk tampilan daftar setting
  }

  function update($data){
    $_SESSION['success']=1;

    foreach ($data as $key => $value) {
      // Update setting yang diubah
      if ($this->setting->$key != $value) {
        $outp = $this->db->where('key', $key)->update('setting_aplikasi', array('key'=>$key, 'value'=>$value));
        $this->setting->$key = $value;
        if(!$outp) $_SESSION['success']=-1;
      }
    }
  }

  function update_slider(){
    $_SESSION['success']=1;
    $this->setting->sumber_gambar_slider = $this->input->post('pilihan_sumber');
    $outp = $this->db->where('key','sumber_gambar_slider')->update('setting_aplikasi', array('value'=>$this->input->post('pilihan_sumber')));
    if(!$outp) $_SESSION['success']=-1;
  }

}