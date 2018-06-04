<?php
/*
 * tes.php
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>untitled</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 1.23.1" />
</head>

<body>
<?php
echo(__FILE__);
echo($_SERVER['DOCUMENT_ROOT']);

	$rel = str_replace($_SERVER['DOCUMENT_ROOT'],"",__DIR__);
	$rel = str_replace("tiny_mce/plugins/jbimages","",$rel);

echo "<h2>".$rel."images</h2>";

$config['upload_path']= str_replace("tiny_mce/plugins/jbimages","images",__DIR__);

echo $config['upload_path'];

$str_rel = str_replace($_SERVER['DOCUMENT_ROOT'],"",__DIR__);
$str_rel = str_replace("tiny_mce/plugins/jbimages","",$str_rel);

$config['img_path'] = $rel.'images'; // Relative to domain name


if (isset($_SERVER['DOCUMENT_ROOT'])){
$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $config['img_path'];
} else {
$config['upload_path'] = dirname(__FILE__) . $config['img_path'];
}
echo "<br />Hello World ";
echo "<br />Image Path: ".$config['img_path']."";
echo "<br />Server Name: ".$_SERVER["SERVER_NAME"]."";
echo "<br />Dirname: ".	dirname('/index.html')."";

echo "<br />UploadPath: ".$config['upload_path']."";

if (is_dir($config['upload_path'])){
	echo "<br />Is Directory";
	if (is_writable($config['upload_path']) ){
		echo "<br />Is Writable";
	} else {
		echo "<br />Is NOT writable";
	}
} else {
	echo "<br />Is Not a Directory";
}
?>	
</body>

</html>
