<?php class Analisis_Laporan_Penduduk_Model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	
	function autocomplete(){
		$sql   = "SELECT dusun_nama FROM tweb_wil_dusun";
		$query = $this->db->query($sql);
		$data  = $query->result_array();
		
		$i=0;
		$outp='';
		while($i<count($data)){
			$outp .= ",'" .$data[$i]['dusun_nama']. "'";
			$i++;
		}
		$outp = strtolower(substr($outp, 1));
		$outp = '[' .$outp. ']';
		return $outp;
	}
	
	function search_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_sql= " AND u.nama LIKE '$kw'";
			return $search_sql;
			}
		}

	function jenis_sql(){		
		if(isset($_SESSION['jenis'])){
			$kh = $_SESSION['jenis'];
			$jenis_sql= " AND jenis = $kh";
		return $jenis_sql;
		}
	}

	function tahun_sql(){		
		if(isset($_SESSION['tahun'])){
			$kh = $_SESSION['tahun'];
			$tahun_sql= " AND tahun = $kh";
		return $tahun_sql;
		}
	}

	function bulan_sql(){		
		if(isset($_SESSION['bulan'])){
			$kh = $_SESSION['bulan'];
			$bulan_sql= " AND bulan = $kh";
		return $bulan_sql;
		}
	}
	function search_penduduk_sql(){
		if(isset($_SESSION['cari'])){
		$cari = $_SESSION['cari'];
			$kw = $this->db->escape_like_str($cari);
			$kw = '%' .$kw. '%';
			$search_keluarga_sql= " AND (tb1.nama LIKE '$kw' OR nik LIKE '$kw')";
			return $search_keluarga_sql;
			}
		}
	function pagingy($p=1,$o=0){
	
		$sql      = "select count(id) as id from (SELECT (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') as hasil,u.* FROM tweb_penduduk u   order by (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') desc) as tb1 where 1 ";
		
		$sql .= $this->search_penduduk_sql();
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	function paging($lap=0,$p=1,$o=0){
		
		
			
		$sql      = "SELECT COUNT(id) as id FROM master_analisis_penduduk WHERE 1 ";
		
	
		//$sql     .= $this->jenis_sql();     
		//$sql     .= $this->tahun_sql();     
		//$sql     .= $this->bulan_sql();     
		$query    = $this->db->query($sql);
		$row      = $query->row_array();
		$jml_data = $row['id'];
		
		$this->load->library('paging');
		$cfg['page']     = $p;
		$cfg['per_page'] = $_SESSION['per_page'];
		$cfg['num_rows'] = $jml_data;
		$this->paging->init($cfg);
		
		return $this->paging;
	}
	
	function list_graph(){
		$sql   = "SELECT ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='1'),0) as jan,
		 ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='2'),0) as feb,
		  ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='3'),0) as mar,
		   ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='4'),0) as apr,
		    ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='5'),0) as mei,
		     ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='6'),0) as jun,
		      ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='7'),0) as jul,
		       ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='8'),0) as ags,
		       ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='9'),0) as sep,
		         ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='10'),0) as okt,
		          ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='11'),0) as nov,
		           ifnull((SELECT AVG(hasil) FROM hasil_analisis_penduduk WHERE tahun='$_SESSION[tahun]' AND bulan='12'),0) as des 
		           FROM hasil_analisis_penduduk where tahun='$_SESSION[tahun]'  ";
//		$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
	
		$data = $query->row_array();
		//print_r($data);
		return $data;
	
	}
	
        function list_graph_tanya($id=''){
		$sql   = "SELECT a.id as idmaster, a.nama as pertanyaan, b.id as idanalisis, b.nama as jawaban, 
		ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='1'),0) as jan,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='2'),0) as feb,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='3'),0) as mar,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='4'),0) as apr,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='5'),0) as mei,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='6'),0) as jun,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='7'),0) as jul,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='8'),0) as ags,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='9'),0) as sep,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='10'),0) as okt,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='11'),0) as nov,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='12'),0) as des
		FROM master_analisis_penduduk a LEFT JOIN sub_analisis_penduduk b ON a.id=b.id_master WHERE a.id='$id' ";
		$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
	
		$data = $query->result_array();
		//print_r($data);
		return $data;
	
	}

         function list_graph_jawab($id='',$id2=''){
		$sql   = "SELECT a.id as idmaster, a.nama as pertanyaan, b.id as idanalisis, b.nama as jawaban, 
		ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='1'),0) as jan,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='2'),0) as feb,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='3'),0) as mar,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='4'),0) as apr,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='5'),0) as mei,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='6'),0) as jun,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='7'),0) as jul,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='8'),0) as ags,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='9'),0) as sep,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='10'),0) as okt,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='11'),0) as nov,
		 ifnull((SELECT COUNT(id_pend) FROM analisis_penduduk WHERE id_master=a.id AND id_sub_analisis=b.id AND tahun='$_SESSION[tahun]' AND bulan='12'),0) as des
		FROM master_analisis_penduduk a LEFT JOIN sub_analisis_penduduk b ON a.id=b.id_master WHERE a.id='$id' AND b.id='$id2'";
		$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
	
		$data = $query->result_array();
		//print_r($data);
		return $data;
	
	}

	function get_tanya($id=''){
		$sql   = "SELECT * FROM master_analisis_penduduk WHERE id='$id' ";
		$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
	
		$data = $query->row_array();
		//print_r($data);
		return $data;
	
	}
	function get_jawab($id=''){
		$sql   = "SELECT * FROM sub_analisis_penduduk WHERE id='$id' ";
		//$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
	
		$data = $query->row_array();
		//print_r($data);
		return $data;
	
	}
	function list_data($lap=0,$o=0,$offset=0,$limit=500){
	
		//Paging SQL
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;

		$sql="SELECT * FROM (SELECT a.id as id_master, b.id as id_sub, a.nama as pertanyaan, b.nama as jawaban,bulan, tahun, (SELECT (COUNT(id)) FROM analisis_penduduk where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun]) as jml_responden, 
			(SELECT (COUNT(x.id)) FROM analisis_penduduk x INNER JOIN tweb_penduduk y ON x.id_pend=y.id where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun] and sex='1') as laki,
			(SELECT (COUNT(x.id)) FROM analisis_penduduk x INNER JOIN tweb_penduduk y ON x.id_pend=y.id where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun] and sex='2') as perempuan  FROM master_analisis_penduduk a LEFT JOIN sub_analisis_penduduk b  ON a.id=b.id_master LEFT JOIN analisis_penduduk d ON b.id=d.id_sub_analisis  and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun] GROUP BY a.id, b.id, a.nama , b.nama ,bulan, tahun,(SELECT (COUNT(id)) as jml_responden FROM analisis_keluarga where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun]),(SELECT (COUNT(x.id))  as laki FROM analisis_penduduk x INNER JOIN tweb_penduduk y ON x.id_pend=y.id where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun] and sex='1'),(SELECT (COUNT(x.id)) as perempuan FROM analisis_penduduk x INNER JOIN tweb_penduduk y ON x.id_pend=y.id where id_master=a.id and id_sub_analisis=b.id and bulan=$_SESSION[bulan] and tahun=$_SESSION[tahun] and sex='2')) AS TB WHERE 1  ";
	
		//$sql .= $this->search_sql();
		//$sql .= $order_sql;
		//$sql     .= $this->tahun_sql();
		//$sql     .= $this->bulan_sql();
		$sql .= $paging_sql;
		//print_r($sql);
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		$x=$offset;
		while($i<count($data)){
			//$data[$i]['no']=$j+1;
			
			if($data[$i]['jumlah']<1)
				$data[$i]['jumlah']="-";
			
			if($data[$i]['laki']<1)
				$data[$i]['laki']="-";
			
			if($data[$i]['perempuan']<1)
				$data[$i]['perempuan']="-";

			$data[$i]['tanya']=$gakosong;
			if($data[$i]['tanya']==$data[$i]['pertanyaan']){
				$data[$i]['tanya']="";
			}else{
				$data[$i]['tanya']=$data[$i]['pertanyaan'];
				$gakosong=$data[$i]['pertanyaan'];				
			}
			if($data[$i]['tanya']<>""){
				$data[$i]['no']=$x+1;
				$x++;
			}
			$i++;
			$j++;
		}
		return $data;
	}
	function list_data_a($o=0,$offset=0,$limit=500){
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
$sql   = "select * from (SELECT (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') as hasil,'$_SESSION[bulan]' as bulan,'$_SESSION[tahun]' as tahun,u.* FROM tweb_penduduk u  order by (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') desc) as tb1 where 1 ";
		$sql .= $this->search_penduduk_sql();
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		$i=0;
		while($i<count($data)){
			$sql1   = "SELECT * FROM master_analisis_penduduk WHERE aktif = 1 ";
			
			$sql1 .= $this->jenis_sql();
			//$sql1 .= $paging_sql;
			$query1 = $this->db->query($sql1);
			$data1 =  $query1->result_array();
			
			$j=0;
			while($j<count($data1)){
//				$sql2   = "SELECT * FROM (SELECT s.nama as nama, s.nilai as nilai,  (s.nilai)/100 as hasil, k.tahun, k.bulan FROM analisis_penduduk k LEFT JOIN sub_analisis_penduduk s ON k.id_sub_analisis = s.id LEFT JOIN master_analisis_penduduk m ON k.id_master=m.id WHERE k.id_master = ? AND k.id_pend = ? ORDER BY bulan DESC,tahun DESC LIMIT 1) AS TB WHERE 1  ";
				$sql2   = "SELECT * FROM (SELECT s.nama as nama, s.nilai as nilai, (s.nilai)/100 as hasil, bulan, tahun FROM analisis_penduduk k LEFT JOIN sub_analisis_penduduk s ON k.id_sub_analisis = s.id LEFT JOIN master_analisis_penduduk m ON k.id_master=m.id WHERE k.id_master = ? AND k.id_pend = ?  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') AS TB WHERE 1  ";
				//$sql2 .= $this->tahun_sql();
				//$sql2 .= $this->bulan_sql();
				$query2 = $this->db->query($sql2,array($data1[$j]['id'],$data[$i]['id']));
				$data2 =  $query2->row_array();
				$data[$i]['jawaban'][$j] = $data2;
				$j++;
			}
			$data[$i]['no'] = $i+1;
			$i++;
		}
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	
	}
	function list_data_b($o=0,$offset=0,$limit=500){
		
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
		
$sql   = "select * from (SELECT (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') as hasil,'$_SESSION[bulan]' as bulan,'$_SESSION[tahun]' as tahun,u.* FROM tweb_penduduk u  order by (SELECT hasil from hasil_analisis_penduduk where id_pend=u.id  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') desc) as tb1 where 1 ";
		$sql .= $this->search_penduduk_sql();
		$sql .= $paging_sql;
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		$i=0;
		while($i<count($data)){
			$sql1   = "SELECT * FROM master_analisis_penduduk WHERE aktif = 1 ";
			
			$sql1 .= $this->jenis_sql();
			//$sql1 .= $paging_sql;
			$query1 = $this->db->query($sql1);
			$data1 =  $query1->result_array();
			
			$j=0;
			while($j<count($data1)){
//				$sql2   = "SELECT * FROM (SELECT s.nama as nama, s.nilai as nilai,  (s.nilai)/100 as hasil, k.tahun, k.bulan FROM analisis_penduduk k LEFT JOIN sub_analisis_penduduk s ON k.id_sub_analisis = s.id LEFT JOIN master_analisis_penduduk m ON k.id_master=m.id WHERE k.id_master = ? AND k.id_pend = ? ORDER BY bulan DESC,tahun DESC LIMIT 1) AS TB WHERE 1  ";
				$sql2   = "SELECT * FROM (SELECT s.nama as nama, s.nilai as nilai, (s.nilai) as hasil, bulan, tahun FROM analisis_penduduk k LEFT JOIN sub_analisis_penduduk s ON k.id_sub_analisis = s.id LEFT JOIN master_analisis_penduduk m ON k.id_master=m.id WHERE k.id_master = ? AND k.id_pend = ?  AND bulan=$_SESSION[bulan] AND tahun='$_SESSION[tahun]') AS TB WHERE 1  ";
				//$sql2 .= $this->tahun_sql();
				//$sql2 .= $this->bulan_sql();
				$query2 = $this->db->query($sql2,array($data1[$j]['id'],$data[$i]['id']));
				$data2 =  $query2->row_array();
				$data[$i]['jawaban'][$j] = $data2;
				$j++;
			}
			$data[$i]['no'] = $i+1;
			$i++;
		}
		
		//Formating Output
		$i=0;
		$j=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			$i++;
			$j++;
		}
		return $data;
	
	}
	function list_pertanyaan_penduduk(){
		$sql   = "SELECT * FROM master_analisis_penduduk WHERE aktif = 1 ";
		$sql .= $this->jenis_sql();
		$query = $this->db->query($sql);
		$data = $query->result_array();
	
		return $data;
	
	}
	function get_config(){	
		$sql   = "SELECT * FROM config WHERE 1";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		return $data;
	}
function list_data_detail($stat=0,$jwb=0,$thn='',$bln='',$jns=0,$lap=0,$o=0,$offset=0,$limit=500){
	
		$paging_sql = ' LIMIT ' .$offset. ',' .$limit;
			
			$thnsql= "  AND tahun=$thn ";			
			$blnsql= "  AND bulan=$bln  ";	
		if($jns==1){$jnssql=" AND k.sex=1 ";}
		elseif($jns==2){$jnssql=" AND k.sex=2 ";}		
		else{$jnssql="  ";}

		$sql   = "SELECT k.*, p.nama as pendidikan,pk.nama as pekerjaan, kw.nama as status_kawin, sex.nama as jk  FROM analisis_penduduk a LEFT JOIN tweb_penduduk k ON a.id_pend=k.id  LEFT JOIN master_analisis_penduduk m ON a.id_master=m.id LEFT JOIN sub_analisis_penduduk s ON a.id_sub_analisis=s.id LEFT JOIN tweb_penduduk_pendidikan p ON k.pendidikan_id=p.id LEFT JOIN tweb_penduduk_pekerjaan pk ON k.pekerjaan_id=pk.id LEFT JOIN tweb_penduduk_kawin kw ON k.status_kawin=kw.id LEFT JOIN tweb_penduduk_sex sex ON k.sex=sex.id WHERE  a.id_master=$stat AND id_sub_analisis=$jwb  ";  
		
		$sql .= $thnsql;
		$sql .= $blnsql;
		$sql .= $jnssql;
		//$sql .= $paging_sql;
		//print_r($sql);
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		//Formating Output
		$i=0;
		$j=$offset;
		$x=$offset;
		while($i<count($data)){
			$data[$i]['no']=$j+1;
			
			$i++;
			$j++;
			
		}

		return $data;
	}
	
	function get_master_analisis_penduduk($id=0){
		$sql   = "SELECT * FROM master_analisis_penduduk WHERE id=$id";
		$query = $this->db->query($sql);
		$data['tanya']=$query->row_array();
		return $data;
	}

	function get_sub_analisis_penduduk($id=0){
		$sql   = "SELECT * FROM sub_analisis_penduduk WHERE id=$id";
		$query = $this->db->query($sql);
		$data['jawab']=$query->row_array();
		return $data;
	}

}

?>
