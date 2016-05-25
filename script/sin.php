<?php

function Parse_Data($data,$p1,$p2){
    $data=" ".$data;
    $hasil="";
    $awal=strpos($data,$p1);
    if($awal!=""){
        $akhir=strpos(strstr($data,$p1),$p2);
        if($akhir!=""){
            $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
        }
    }
    return $hasil;    
}

$server = "localhost";
$username = "root";
$password = "";
$database = "db_simpa";

mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");

set_time_limit(300);
$newLine="\r\n";
$i=0;
$inset = "INSERT INTO absen (id_pegawai,cek,verify,type,work) VALUES ";
$qry = mysql_query("SELECT * FROM machine WHERE autosin=1");

while ($m = mysql_fetch_array($qry)) {
	$ip = $m['ip'];
	$key = $m['key'];
	
	$str = exec("ping -n 1 -w 1 $ip", $input, $result);
	if($result==0){
		$pry = mysql_query("SELECT id FROM pegawai WHERE aktif = 1");

		while ($p = mysql_fetch_array($pry)) {
			$Connect = fsockopen($ip,"80",$errno,$errstr,1);
			if($Connect){
				$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">$key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">$p[id]</PIN></Arg></GetAttLog>";		
				fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
				fputs($Connect, "Content-Type: text/xml".$newLine);
				fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
				fputs($Connect, $soap_request.$newLine); 
				$buffer="";
				while($Response=fgets($Connect, 8192)){
					$buffer.=$Response;
				}
			}
			$buffer=Parse_Data($Response,"<GetAttLogResponse>","</GetAttLogResponse>");
			
			if(count($buffer)>3){
				$in = "";
				$buffer=explode("\r\n",$buffer);  
				for($a=(count($buffer)-3);$a<(count($buffer)-1);$a++){
					$data=Parse_Data($buffer[$a],"<Row>","</Row>");
					$PIN=Parse_Data($data,"<PIN>","</PIN>");
					$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
					$Verified=Parse_Data($data,"<Verified>","</Verified>");
					$Status=Parse_Data($data,"<Status>","</Status>");
					$WorkCode =Parse_Data($data,"<WorkCode>","</WorkCode>");
					$in .= "($PIN,'".$DateTime."',$Verified,$Status,$WorkCode),";
				}
				$x = strlen($in);
				$in[$x-1] =";";
				mysql_query($inset.$in);
			}
		}
	}
}
?>