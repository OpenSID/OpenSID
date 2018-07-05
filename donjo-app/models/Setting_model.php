<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends CI_Model
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
    $this->apply_setting();
  }

  // Setting untuk PHP
  private function apply_setting(){
    //  https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
    date_default_timezone_set($this->setting->timezone);//ganti ke timezone lokal
    // Ambil google api key dari desa/config/config.php kalau tidak ada di database
    if (empty($this->setting->google_key)){
      $this->setting->google_key = config_item('google_key');
    }
    // Ambil dev_tracker dari desa/config/config.php kalau tidak ada di database
    if (empty($this->setting->dev_tracker)){
      $this->setting->dev_tracker = config_item('dev_tracker');
    }
    $this->setting->user_admin = config_item('user_admin');
    // Kalau folder tema ubahan tidak ditemukan, ganti dengan tema default
    $pos = strpos($this->setting->web_theme, 'desa/');
    if ($pos !== false)
    {
      $folder = FCPATH . '/desa/themes/' . substr($this->setting->web_theme, $pos + strlen('desa/'));
      if (!file_exists($folder))
      {
        $this->setting->web_theme = "default";
      }
    }
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
    $this->apply_setting();
  }

  function update_slider(){
    $_SESSION['success']=1;
    $this->setting->sumber_gambar_slider = $this->input->post('pilihan_sumber');
    $outp = $this->db->where('key','sumber_gambar_slider')->update('setting_aplikasi', array('value'=>$this->input->post('pilihan_sumber')));
    if(!$outp) $_SESSION['success']=-1;
  }

}
