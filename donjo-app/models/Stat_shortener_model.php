<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stat_shortener_model extends CI_Model {

  function add_log( $url_id )
  {
    $data = array(
      'url_id'    => (int) $url_id,
      'created'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert('statistics', $data);
    return $this->db->insert_id();
  }

  public function get_logs( $url_id )
  {
    $this->db->select( array('*', 'COUNT(id) AS sum') );
    $this->db->from('statistics');
    $this->db->where('url_id', (int) $url_id);
    $this->db->group_by('DATE_FORMAT(created, "%m-%y-%d")');
    $this->db->order_by('YEAR(created) ASC, MONTH(created) ASC, DAY(created) ASC');
    $result = $this->db->get()->result_object();

    return (count($result) > 0) ? $result : FALSE;
  }
}
