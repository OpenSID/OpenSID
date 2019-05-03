<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Provinsi_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  public function list_data()
  {
    $data = $this->db->select('*')->from('provinsi')
      ->order_by('nama')->get()->result_array();
    return $data;
  }
}
