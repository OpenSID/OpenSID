<?php class Feed_model extends CI_Model{

	function __construct(){
		$this->load->database();
	}
	public function list_feeds()
	{
		$sql   = "SELECT a.*,u.nama AS owner,k.kategori AS kategori 
							FROM artikel a LEFT JOIN user u ON a.id_user = u.id 
							LEFT JOIN kategori k ON a.id_kategori = k.id 
							WHERE (a.enabled=1) AND (a.judul <> '') ORDER BY a.id DESC LIMIT 0,20";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		$hasil = array();
		$i=0;
		if($query->num_rows()>0)
		{
			while($i<count($data))
			{
				$hasil[$i]['no'] = $data[$i]['id'];
				$hasil[$i]['tgl'] = $data[$i]['tgl_upload'];
				$hasil[$i]["judul"]= $data[$i]['judul'];
				$hasil[$i]['feed_url'] = site_url("feed/");
				$hasil[$i]['url'] = site_url("first/artikel/".$data[$i]["id"]."/");

				$str_isi = fixTag($data[$i]['isi']);
				if(strlen($str_isi) > 300)
				{
					$hasil[$i]['isi'] = substr($str_isi,0,strpos($str_isi," ",260))."...";
				}else{
					$hasil[$i]['isi'] = $str_isi;
				}
				

				$hasil[$i]['author'] = $data[$i]['owner'];
				$hasil[$i]['kategori'] = $data[$i]['kategori'];
				$i++;
			}
		}else{
			$hasil  = false;
		}
		return $hasil;
	}
}

?>
 
