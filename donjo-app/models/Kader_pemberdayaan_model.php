<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Model Kader Pemberdayaan
 *
 * donjo-app/models/Kader_pemberdayaan_model.php
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
class Kader_pemberdayaan_model extends CI_Model
{
	
	public function insert()
	{
		$post = $this->input->post();
		//$data['nama'] = $post['nama'];
		$data['idpenduduk'] = $post['id'];
		$data['umur'] = $post['umur'];
	//	$data['jeniskelamin'] = $post['jk'];
		if( $post['jk'] == "Laki-laki"){
			$data['jeniskelamin'] = 'L';
		}else{
			$data['jeniskelamin'] = 'P';
		}
		$data['pendidikankursus'] = $post['pendidikankursus'];
		$data['pendidikanahli'] = $post['pendidikankeahlian'];
		//$data['alamat'] = $post['alamat'];
		$data['keterangan'] = $post['keterangan'] ?: null;

		$outp = $this->db->insert('tweb_penduduk_kader_berdaya', $data);
	
		$idpenduduk = $this->input->post('id', true);
		$idpendidikan = $this->input->post('pendidikan', true);
		$pendidikankursus = $this->input->post('pendidikankursus', true);
		$pendidikankeahlian = $this->input->post('pendidikankeahlian', true);
	/*
		$data_group1 = array(
			'nama'=>$pendidikankursus
		);
		    $this->db->insert('tweb_penduduk_kursus', $data_group1);
			
			$data_group2 = array(
				'nama'=>$pendidikankeahlian
			);
				   $this->db->insert('tweb_penduduk_keahlian', $data_group2);
			*/
         
		if ($outp) $_SESSION['success'] = 1;
		else $_SESSION['success'] = -1;
	}
public function ubahkursus($id){
	$post = $this->input->post();
	$table = "tweb_penduduk_kader_berdaya";
	$data_group1['pendidikankursus'] =  $post['pendidikankursus'];
	$data_group1['pendidikanahli'] =  $post['pendidikankeahlian'];
		return $this->db->update($table, $data_group1, array('idpenduduk' => $id));
		//  $this->db->where('id', $id);
		 // $hasil =  $this->db->where('id', $id)->update('tweb_penduduk_kader_berdaya', $data_group1);

}

	public function paging($p = 1, $o = 0)
	{
		$sql = "SELECT count(*) as jml from tweb_penduduk_kader_berdaya";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$jml_data = $row['jml'];

		$this->load->library('paging');
		$cfg['page'] = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);

		return $this->paging;
	}

	public function get_kader($id)
	{
		$this->db->select("*")
		->from('tweb_penduduk_kader_berdaya t')
	//	->join('tweb_penduduk_kader_ahli m', 't.idpenduduk = m.idpenduduk_ahli', 'left')
	//	->join('tweb_penduduk_keahlian n', 'n.id = m.idkeahlian', 'left')
		->join('tweb_penduduk o', 'o.id = t.idpenduduk', 'left')
	//	->join('tweb_penduduk_kader_kursus p', 't.idpenduduk = p.idpenduduk_kursus', 'left')
	//	->join('tweb_penduduk_kursus q', 'q.id = p.idkursus', 'left')
		->where('t.idpenduduk', $id);
	//	->group_by("t.id")
		$sql = $this->db->get();
	//	$sql .= $order_sql;
	//	$sql .= $paging_sql;

		$query = $sql;
		$data = $query->row_array();

	
		return $data;
	}
	
	public function getPenduduk($postData){

		$response = array();
   
		
		  // Select record
		  $this->db->select('*');
		  $this->db->where("nama like '%".$_GET['query']."%'");
   
		  $records = $this->db->get('tweb_penduduk')->result();
		  $json = [];
		  while($row = $records->fetch_assoc()){
			   $json[] = $row['nama'];
		  }
		  $tes = $json;
		  $hsl = array_unique($tes);
		  $res = [];
		  foreach($hsl as $e) {
			  array_push($res, ...explode(",", $e));
		  }
		  $n = array_unique($res);
		  echo json_encode($n);
	 
	}
	public function getUsers($postData){

		$response = array();
   
		if(isset($postData['search']) ){
		  // Select record
		  $this->db->select('*');
		  $this->db->where("nama like '%".$postData['search']."%' ");
   
		  $records = $this->db->get('tweb_penduduk')->result();
   
		  foreach($records as $row ){
			$dt = new DateTime($row->tanggallahir);
			$today = new DateTime('today');
			$umur = $today->diff($dt)->y;
			if ($row->sex == 1){
				$jk = 'Laki-laki';
			}elseif($row->sex == 2){
				$jk = 'Perempuan';
			}else{
				$jk = '';
			}
			 $response[] = array("id"=>$row->id,"value"=>$umur,"label"=>$row->nama,"jk"=>$jk,"alamat"=>$row->alamat_sekarang);
		  }
   
		}
   
		return $response;
	 }
   
	private function list_data_sql()
	{
		$this->db->get()
		->from('tweb_penduduk_kader_berdaya t')
		->join('tweb_penduduk_kader_ahli m', 't.idpenduduk = m.idpenduduk_ahli', 'left')
		->join('tweb_penduduk_keahlian n', 'n.id = m.idkeahlian', 'left')
		->join('tweb_penduduk o', 'o.id = t.idpenduduk', 'left')
		->join('tweb_penduduk_kader_kursus p', 't.idpenduduk = p.idpenduduk_kursus', 'left')
		->join('tweb_penduduk_kursus q', 'q.id = p.idkursus', 'left');
	}
	public function get_kolom($id){
        $this->db->select('*')
        ->where('tweb_penduduk.id',$id)
        ->join('tweb_penduduk_kader_kursus', 'tweb_penduduk_kader_kursus.idpenduduk_kursus = tweb_penduduk.id')
        ->from('tweb_penduduk_kader_kursus');
        return $this->db->get();
       
           }
	public function list_data($offset = 0, $limit = 500)
	{
		
		$paging_sql = ' LIMIT ' . $offset . ',' . $limit;

		$this->db->select("*")
		->from('tweb_penduduk_kader_berdaya t')
		->join('tweb_penduduk o', 'o.id = t.idpenduduk', 'left')
		->order_by('t.id')
		->limit($limit, $offset);
		
		$sql = $this->db->get();
		$query = $sql;
		$data = $query->result_array();

		
		$j = $offset;
		for ($i = 0; $i < count($data); $i++)
		{
			$data[$i]['no'] = $j + 1;
			$j++;
		}
		return $data;
	}

	public function savekaderahli($data){
        $this->db->insert('tweb_penduduk_kader_keahlian', $data);
    }
	private function ambil_data(&$data)
	{
		$kader = array();
		$kader['tgl_agenda'] = $post['keterangan'];
		
		return $kader;
	}
	public function update($id)
	{
		$post = $this->input->post();
		$idpendidikan = $this->input->post('pendidikan', true);
		$pendidikankursus = $this->input->post('pendidikankursus', true);
		$pendidikankeahlian = $this->input->post('pendidikankeahlian', true);
		$kader = $post['keterangan'];
		$hasil = $this->db->where('idpenduduk',$id)->update('tweb_penduduk_kader_berdaya', ['keterangan' => $kader]);
		

		foreach ($pendidikankursus as $key => $didikkursus) {
			$data_group1 = array(
			'idpenduduk_kursus' => $id[$key],
			'idkursus' => $didikkursus,
			'idpendidikan_kk_kursus' => $idpendidikan
			);
		   if($didikkursus!=0){
				$hasil =  $this->db->where('idpenduduk_kursus', $id)->update('tweb_penduduk_kader_kursus', $data_group1);
			}
	   } 
	   
		   foreach ($pendidikankeahlian as $didikahli) {
				$data_group2['idpenduduk_ahli'] = $id;
				$data_group2['idkeahlian'] = $didikahli;
				$data_group2['idpendidikan_kk'] = $idpendidikan;
				if($didikahli!=0){
					$hasil =  $this->db->where('idpenduduk_ahli', $id)->update('tweb_penduduk_kader_ahli', $data_group2);
				}
		    } 

			status_sukses($hasil); 
	}
	function carifill($nama){
		$this->db->where('nama',$nama)
		->from('tweb_penduduk');
        return $this->db->get();
    }
	function carikursus2(){
		$this->db->select('*')
			 ->from('tweb_penduduk_kursus');
			return $this->db->get();
    }
	function carikursus(){
        $this->db->from('tweb_penduduk_kursus')
                ->order_by('nama', 'ASC');
        return $this->db->get();
    }
	function count_by_kursus($id, $kursusid){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('(idpenduduk_kursus="'.$id.'" AND idkursus="'.$kursusid.'" )')
                 ->from('tweb_penduduk_kader_kursus');
        return $this->db->get();
    }
	function count_by_keahlian($id, $keahlianid){
        $this->db->select('COUNT(*) AS hasil')
                 ->where('(idpenduduk_ahli="'.$id.'" AND idkeahlian="'.$keahlianid.'" )')
                 ->from('tweb_penduduk_kader_ahli');
        return $this->db->get();
    }
	function carikeahlian(){
        $this->db->from('tweb_penduduk_keahlian')
                ->order_by('nama', 'ASC');
        return $this->db->get();
    }
	function cari($nama){
	
        $this->db->like('nama', $nama , 'both');
     $this->db->order_by('nama', 'ASC');
       $this->db->limit(10);
        return $this->db->get('tweb_penduduk')->result();
    }

	public function cari_judul($nama)
	{
	 $this->db->like('nama',$nama);
	 $query=$this->db->get('tweb_penduduk');
	 return $query->result();
	}
	


	public function delete($id)
	{
	
		$hapuskader['kader'] = $this->deletekader('idpenduduk', $id);
		return $hapuskader;
	}
	public function delete_all()
	{
		$id_cb = $_POST['id_cb'];
		if (count($id_cb))
		{
			$list_id = implode(",", $id_cb);
			$this->db->query("DELETE FROM tweb_penduduk_kader_berdaya WHERE idpenduduk IN (" . $list_id . ")");
			$outp = true;
		}
		else
			$outp = false;

		status_sukses($outp); 
	}

	function deletekursus($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete('tweb_penduduk_kader_kursus');
    }
	function deleteahli($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete('tweb_penduduk_kader_ahli');
    }
	function deletekader($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete('tweb_penduduk_kader_berdaya');
    }

	public function get_data($id = 0)
	{
		$sql = "SELECT u.*, p.nama as nama
			FROM tweb_desa_pamong u
			LEFT JOIN tweb_penduduk p ON u.id_pend = p.id
			WHERE pamong_id = ?";
		$query = $this->db->query($sql, $id);
		$data  = $query->row_array();
		$data['pamong_niap_nip'] = (!empty($data['pamong_nip']) and $data['pamong_nip'] != '-') ? $data['pamong_nip'] : $data['pamong_niap'];
		if (!empty($data['id_pend']))
		{
			
			$data['pamong_nama'] = $data['nama'];
		}
		return $data;
	 }
	 

}
