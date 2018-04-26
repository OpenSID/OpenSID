<?php
/*
 * apis.php
 * 
 * VIEW untuk API
 * 
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

/*
 * Standar Query
 * 
 * f = FORMAT : xml/json
 * w = data : "wilayah"
 * d = data : "kk, penduduk"
 * 
 * */

$format=@$_REQUEST["f"];
$format = (isset($format))? $format : "json";

$data=@$_REQUEST["d"];

$hasil = array();
if(empty($data)){
	$hasil["error"] = true;
	$hasil["kode"] = 100;
	$hasil["pesan"] = "Anda belum memasukkan perintah permintaan data, silakan baca di http://sid.web.id/dokumentasi/apis.html";
}else{
	echo $data."disini";
}

if($format=="json"){
	echo json_encode($hasil);
}else{
	$xml = new SimpleXMLElement('<sid/>');
	array_walk_recursive($hasil,array($xml,'addChild'));
	
}

?>
