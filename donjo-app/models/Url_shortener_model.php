<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model untuk modul URL-Shortener ()
 *
 * donjo-app/models/Url_shortener_model.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

class Url_shortener_model extends CI_Model {

  public function url_pendek($url)
  {
    $id = $this->add_url($url);
    $url_data = $this->get_url_by_id($id);
    $data['url_data'] = $url_data;
    $output = site_url('v/'.$url_data->alias);
    return $output;
  }

  public function add_url($url)
  {
    $data = array(
      'url'       => (string) $url,
      'alias'     => (string) $this->random_code(6),
      'created'   => date('Y-m-d H:i:s'),
    );
    $this->db->insert('urls', $data);
    return $this->db->insert_id();
  }

  public function get_url_by_id($id)
  {
    $this->db->select('*');
    $this->db->from('urls');
    $this->db->where('id', (int) $id);
    $result = $this->db->get()->row_object();
    return (count($result) > 0) ? $result : FALSE;
  }

  public function get_url($alias)
  {
    $this->db->select('*');
    $this->db->from('urls');
    $this->db->where('alias', (string) $alias);
    $result = $this->db->get()->row_object();
    return (count($result) > 0) ? $result : FALSE;
  }

  public function random_code($length)
  {
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
  }

  public function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++)
    {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function seeded_shuffle(array &$items, $seed = false)
  {
    $items = array_values($items);
    mt_srand($seed ? $seed : time());
    for ($i = count($items) - 1; $i > 0; $i--)
    {
      $j = mt_rand(0, $i);
      list($items[$i], $items[$j]) = array($items[$j], $items[$i]);
    }
  }

  public function seeded_unshuffle(array &$items, $seed)
  {
    $items = array_values($items);
    mt_srand($seed);
    $indices = [];
    for ($i = count($items) - 1; $i > 0; $i--)
    {
      $indices[$i] = mt_rand(0, $i);
    }
    foreach (array_reverse($indices, true) as $i => $j)
    {
      list($items[$i], $items[$j]) = [$items[$j], $items[$i]];
    }
  }

  public function encode_id( $id, $seed, $length = 9)
  {
    $string = $id . $this->generateRandomString($length - strlen($id));
    $arr = (str_split($string));
    $this->seeded_shuffle($arr, $seed);
    return implode("",$arr);
  }

  public function decode_id( $encoded_id, $seed, $length = 6)
  {
    $arr = str_split($encoded_id);
    $this->seeded_unshuffle( $arr, $seed);
    return substr(implode("", $arr), 0, $length);
  }

}
