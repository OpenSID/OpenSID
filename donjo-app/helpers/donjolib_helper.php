<?php

	/*
		Mencari nilai di nested array (array dalam array).
		Ambil key dari array utama
	*/
	function nested_array_search($needle,$array) {
		foreach ($array as $key => $value) {
			$array_key = array_search($needle, $value);
			if ($array_key !== FALSE) return $key;
		}
	}

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

	function Rupiah($nil=0){
		$nil = $nil + 0;
		if(($nil*100)%100 == 0){
			$nil = $nil.".00";
		}elseif(($nil*100)%10 == 0){
			$nil = $nil."0";
		}
		$nil = str_replace('.', ',', $nil);
		$str1 = $nil;
		$str2= "";
		$dot = "";
		$str = strrev($str1);
		$arr = str_split($str, 3);
		$i=0;
		foreach($arr as $str){
			$str2 = $str2.$dot.$str;
			if(strlen($str)==3 AND $i>0)$dot = '.';
			$i++;
		}
		$rp = strrev($str2);
		if($rp != "" AND $rp > 0){return "Rp. $rp";}else{return "Rp. 0,00";}
	}

	function Rupiah2($nil=0){
		$nil = $nil + 0;
		if(($nil*100)%100 == 0){
			$nil = $nil.".00";
		}elseif(($nil*100)%10 == 0){
			$nil = $nil."0";
		}
		$nil = str_replace('.', ',', $nil);
		$str1 = $nil;
		$str2= "";
		$dot = "";
		$str = strrev($str1);
		$arr = str_split($str, 3);
		$i=0;
		foreach($arr as $str){
			$str2 = $str2.$dot.$str;
			if(strlen($str)==3 AND $i>0)$dot = '.';
			$i++;
		}
		$rp = strrev($str2);
		if($rp != "" AND $rp > 0){return "Rp.$rp";}else{return "-";}
	}

	function Rupiah3($nil=0){
		$nil = $nil + 0;
		if(($nil*100)%100 == 0){
			$nil = $nil.".00";
		}elseif(($nil*100)%10 == 0){
			$nil = $nil."0";
		}
		$nil = str_replace('.',',', $nil);
		$str1 = $nil;
		$str2= "";
		$dot = "";
		$str = strrev($str1);
		$arr = str_split($str, 3);
		$i=0;
		foreach($arr as $str){
			$str2 = $str2.$dot.$str;
			if(strlen($str)==3 AND $i>0)$dot = '.';
			$i++;
		}
		$rp = strrev($str2);
		if($rp != 0){return "$rp";}else{return "-";}
	}

	function jecho($a,$b,$str){
		if($a==$b){
			echo $str;
		}
	}

	function selected($a,$b,$opt=0){
		if($a==$b){
			if($opt)
				echo "checked='checked'";
			else echo "selected='selected'";
		}
	}

	function date_is_empty($tgl) {
		return (is_null($tgl) || substr($tgl, 0, 10)=='0000-00-00');
	}

	function rev_tgl($tgl, $replace_with='-'){
		if (date_is_empty($tgl)) {
			return $replace_with;
		}
		$ar=explode('-',$tgl);
		$o=$ar[2].'-'.$ar[1].'-'.$ar[0];
		return $o;
	}

	function penetration($str){
		$str = str_replace("'","-", $str);
		return $str;
	}

	function penetration1($str){
		$str = str_replace("'"," ", $str);
		return $str;
	}

	function unpenetration($str){
		$str = str_replace("-","'", $str);
		return $str;
	}
	function spaceunpenetration($str){
		$str = str_replace("-"," ", $str);
		return $str;
	}

	function underscore($str){
		$str = str_replace(" ","_", $str);
		return $str;
	}

	function ununderscore($str){
		$str = str_replace("_"," ", $str);
		return $str;
	}

	function bulan($bln){
		$nm = '';
		switch($bln){
			case '1':
				$nm = 'Januari';
				break;
			case '2':
				$nm = 'Februari';
				break;
			case '3':
				$nm = 'Maret';
				break;
			case '4':
				$nm = 'April';
				break;
			case '5':
				$nm = 'Mei';
				break;
			case '6':
				$nm = 'Juni';
				break;
			case '7':
				$nm = 'Juli';
				break;
			case '8':
				$nm = 'Agustus';
				break;
			case '9':
				$nm = 'September';
				break;
			case '10':
				$nm = 'Oktober';
				break;
			case '11':
				$nm = 'November';
				break;
			case '12':
				$nm = 'Desember';
				break;
			default:
				$nm = '';
				break;
		}
		return $nm;
	}

	function nama_bulan($tgl){
		$ar=explode('-',$tgl);

		$nm = '';
		switch($ar[1]){
			case '01':
				$nm = 'Januari';
				break;
			case '02':
				$nm = 'Februari';
				break;
			case '03':
				$nm = 'Maret';
				break;
			case '04':
				$nm = 'April';
				break;
			case '05':
				$nm = 'Mei';
				break;
			case '06':
				$nm = 'Juni';
				break;
			case '07':
				$nm = 'Juli';
				break;
			case '08':
				$nm = 'Agustus';
				break;
			case '09':
				$nm = 'September';
				break;
			case '10':
				$nm = 'Oktober';
				break;
			case '11':
				$nm = 'November';
				break;
			case '12':
				$nm = 'Desember';
				break;
		}

		$o = $ar[0] .' '. $nm .' '. $ar[2];
		return $o;
	}

	function hari($tgl){
    $hari = array(
      0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
    );
		$dayofweek = date('w', $tgl);
    return $hari[$dayofweek];
	}

	function dua_digit($i){
		if($i<10) $o='0'.$i;
			else $o=$i;
		return $o;
	}

	function tiga_digit($i){
		if($i<10) $o='00'.$i;
		else if($i<100) $o='0'.$i;
			else $o=$i;
		return $o;
	}

	function to_rupiah($inp=''){
		$outp = str_replace('.', '', $inp);
		$outp = str_replace(',', '.', $outp);
		return $outp;
	}

	function rp($inp=0){
		return number_format($inp, 2, ',', '.');
	}

	function pertumbuhan($a=1,$b=1,$c=1,$d=1){
		$x=0;
		$y=0;
		$z=0;
		if($a>1) $x = (($b-$a)/$a);
		if($b>1) $y = (($c-$b)/$b);
		if($c>1) $z = (($d-$c)/$c);
		$outp = (($x+$y+$z)/3)*100;
		$outp = round($outp,2);
		$outp = str_replace('.',',',$outp) . ' %';;
		return $outp;
	}

	function koma ($a=1) {
	if(substr_count($a, '.'))

	$a = str_replace(".", ",",$a);
	else $a = number_format($a,0, ',', '.');
	return $a;
	}

	function tgl_indo2($tgl, $replace_with='-') {
		if (date_is_empty($tgl)) {
			return $replace_with;
		}
		$tanggal = substr($tgl,8,2);
		$jam = substr($tgl,11,8);
		$bulan = getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun.' '.$jam;
	}

	function tgl_indo_dari_str($tgl_str) {
		$tanggal = tgl_indo(date('Y m d',strtotime($tgl_str)));
		return $tanggal;
	}

	function tgl_indo($tgl, $replace_with='-') {
		if (date_is_empty($tgl)) {
			return $replace_with;
		}
		$tanggal = substr($tgl,8,2);
		$bulan = getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;
	}

	function tgl_indo_out($tgl, $replace_with='-') {
		if (date_is_empty($tgl)) {
			return $replace_with;
		}

		if($tgl) {
			$tanggal = substr($tgl,8,2);
			$bulan = substr($tgl,5,2);
			$tahun = substr($tgl,0,4);
			return $tanggal.'-'.$bulan.'-'.$tahun;
		}
	}

	function tgl_indo_in($tgl, $replace_with='-') {
		if (date_is_empty($tgl)) {
			return $replace_with;
		}
		$tanggal = substr($tgl,0,2);
		$bulan = substr($tgl,3,2);
		$tahun = substr($tgl,6,4);
		$jam = substr($tgl,11);
		$jam = empty($jam) ? '' : ' '.$jam;
		return $tahun.'-'.$bulan.'-'.$tanggal.$jam;
	}

	function waktu_ind($time){
		$str ="";
			if(($time/360)>1){
				$jam = ($time/360);
				$jam = explode('.',$jam);
				$str .= $jam." Jam ";
			}
			if(($time/60)>1){
				$menit = ($time/60);
				$menit = explode('.',$menit);
				$str .= $menit[0]." Menit ";
			}
			$detik = $time%60;
			$str .= $detik;

			return $str.' Detik';
	}

	function getBulan($bln){
				switch ($bln){
					case 1:
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}

		}

//time out
function timer(){
	$time=2000;
	$_SESSION['timeout']=time()+$time;
}

function generator($length = 7) {
 return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function cek_login(){
	$timeout=$_SESSION['timeout'];
	if(time()<$timeout){
		timer();
		return true;
	}else{
		unset($_SESSION['timeout']);
		return false;
	}
}

//time out Mandiri set 3 login per 5 menit
function mandiri_timer(){
	$time=300;  //300 detik
	$_SESSION['mandiri_try'] = 4;
	$_SESSION['mandiri_wait']=0;
	$_SESSION['mandiri_timeout']=time()+$time;
}

function mandiri_timeout(){
	(isset($_SESSION['mandiri_timeout'])) ? $timeout=$_SESSION['mandiri_timeout'] : $timeout = null;
	if(time()>$timeout){
		mandiri_timer();
	}
}

//time out Admin set 3 login per 5 menit
function siteman_timer(){
	$time=300;  //300 detik
	$_SESSION['siteman_try'] = 4;
	$_SESSION['siteman_wait']=0;
	$_SESSION['siteman_timeout']=time()+$time;
}

function siteman_timeout(){
	(isset($_SESSION['siteman_timeout'])) ? $timeout=$_SESSION['siteman_timeout'] : $timeout = null;
	if(time()>$timeout){
		siteman_timer();
	}
}

function get_identitas(){
	$ci =& get_instance();
	$sql="SELECT * FROM config";
	$a=$ci->db->query($sql);
	$hsl=$a->row_array();
	//print_r($hsl);
	$string = ucwords($ci->setting->sebutan_desa)." : ".$hsl['nama_desa']." ".ucwords($ci->setting->sebutan_kecamatan_singkat)." : ".$hsl['nama_kecamatan']." Kab : ".$hsl['nama_kabupaten'];
	return $string;
}

// fix str aneh utk masuk ke db
// TODO: Jangan pernah gunakan saya lagi bro,,,,,, :p
function fixSQL($str, $encode_ent = false) {
	$str  = @trim($str);
	if($encode_ent) {
		$str = htmlentities($str);
	}

	if (version_compare(phpversion(),'4.3.0') >= 0) {
		if (get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		// FIXME
		if (function_exists('mysql_ping') && @mysql_ping()) {
			$str = mysql_real_escape_string($str);
		} else {
			$str = addslashes($str);
		}

	} else if (!get_magic_quotes_gpc()) {
		$str = addslashes($str);
	}

	return $str;
}

//baca data tanpa HTML Tags
function fixTag($varString){
	return strip_tags($varString);
}

/*
 * Format tampilan tanggal rentang
 * */

function fTampilTgl($sdate,$edate){
	if($sdate==$edate){
		$tgl =  date("j M Y",strtotime($sdate));
	}elseif($edate>$sdate){
		if(date("Y",strtotime($sdate))==date("Y",strtotime($edate))){
			if(date("M Y",strtotime($sdate))==date("M Y",strtotime($edate))){
				if(date("j M Y",strtotime($sdate))==date("j M Y",strtotime($edate))){
					if(date("j M Y H",strtotime($sdate))==date("j M Y H",strtotime($edate))){
						$tgl = date("j M Y H:i",strtotime($sdate));
					}else{
						$tgl = date("j M Y H:i",strtotime($sdate)) ." - ".date("H:i",strtotime($edate));
					}
				}else{
					$tgl = date("j",strtotime($sdate))." - ".date("j M Y",strtotime($edate));
				}
			}else{
				$tgl = date("j M",strtotime($sdate))." - ".date("j M Y",strtotime($edate));
			}
		}else{
			$tgl = date("j M Y",strtotime($sdate))." - ".date("j M Y",strtotime($edate));
		}
	}
	return $tgl;
}

// Potong teks pada batasan kata
function potong_teks($teks, $panjang) {
	$abstrak = fixTag($teks);
	if(strlen($abstrak)>$panjang+10){
		$abstrak = substr($abstrak,0,strpos($abstrak," ",$panjang));
	}
	return $abstrak;
}

	function hash_pin($pin=""){
		$pin = strrev($pin);
		$pin = $pin*77;
		$pin .= "!#@$#%";
		$pin = md5($pin);
		return $pin;
	}
