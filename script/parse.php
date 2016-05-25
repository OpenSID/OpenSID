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
?> 