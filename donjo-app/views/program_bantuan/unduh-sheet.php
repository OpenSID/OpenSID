<?php
/*
 * create.php
 *
 * Backend View untuk Nulis Program Bantuan Baru
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

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename="."bantuan_".urlencode($peserta[0]["nama"]).".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Peserta Program <?php echo $peserta[0]["nama"];?></title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<style type="text/css">
	.textx{
	  mso-number-format:"\@";
	}
</style>
</head>
<body>
<!-- Print Body -->
<div id="body">
	<div class="header">
		<label><?php echo get_identitas()?></label>
		<h3> Daftar Peserta Program <?php echo $peserta[0]["nama"];?></h3>
		<p>
			<strong>Sasaran Peserta: </strong><?php echo $sasaran[$peserta[0]["sasaran"]];?><br>
			<strong>Masa Berlaku: </strong><?php echo fTampilTgl($peserta[0]["sdate"],$peserta[0]["edate"]);?>
		</p>
		<div><?php echo $peserta[0]["ndesc"];?></div>
	</div>

	<div id="table">
	<table class="border thick">
		<thead>
			<tr class="border thick">
				<th rowspan="2">No</th>
				<th rowspan="2"><?php echo $peserta[0]["judul_peserta"]?></th>
				<th rowspan="2">No. Kartu Peserta</th>
				<th rowspan="2"><?php echo $peserta[0]["judul_peserta_info"]?></th>
				<th rowspan="2">Alamat</th>
				<th colspan="5" style="text-align: center;">Identitas di Kartu Peserta</th>
			</tr>
			<tr>
				<th>NIK</th>
				<th>Nama</th>
				<th>Tempat Lahir</th>
				<th>Tanggal Lahir</th>
				<th>Alamat</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=1;
			foreach ($peserta[1] as $key=>$item){
				echo "<tr><td>".$i."</td>
					<td class='textx'>".$item["nik"]."</td>
					<td class='textx'>".$item["no_id_kartu"]."</td>
					<td>".$item["nama"]."</td>
					<td>".$item["info"]."</td>
					<td class='textx'>".$item["kartu_nik"]."</td>
					<td>".$item["kartu_nama"]."</td>
					<td>".$item["kartu_tempat_lahir"]."</td>
					<td class='textx'>".tgl_indo_out($item["kartu_tanggal_lahir"])."</td>
					<td>".$item["kartu_alamat"]."</td>
				</tr>";
				$i++;
			}
			?>
		</tbody>
	</table>
	</div>
</div>
</body>
</html>
