<?php
/*
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
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('data_persil/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Pengelolaan Data Persil <?php echo $desa['nama_desa'];?></legend>
			<div id="contentpane">
				<div id="maincontent" class="ui-layout-center" style="padding:0 3em 0 0;">

			<?php
			if($_SESSION["success"]==1){
				echo "
				<div>
				".$_SESSION["pesan"]."
				</div>";
				$_SESSION["success"]==0;
			}
			?>

<?php

if($persil_detail['jenis_pemilik'] != '2'){
	echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Data Pemilik</legend>
			<dl>
				<dt>Nama Penduduk</dt>
					<dd>: ".$persil_detail["namapemilik"]."</dd>
				<dt>NIK</dt>
					<dd>: ".$persil_detail["nik"]."</dd>
				<dt>Alamat</dt>
					<dd>: RT ".$persil_detail["rt"]." / RT ".$persil_detail["rw"]." - ".strtoupper($persil_detail["dusun"])."</dd>
			</dl>
		</fieldset>
	</div>
	";
}else{
	echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Data Pemilik</legend>
			<dl>
				<dt>NAMA PEMILIK</dt>
					<dd>: ".$persil_detail["namapemilik"]."</dd>
				<dt>ALAMAT PEMILIK</dt>
					<dd>: ".$persil_detail["alamat_luar"]."</dd>
			</dl>
		</fieldset>
	</div>
	";

}

echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Rincian Persil</legend>
			<dl>
				<dt>Nomor Persil</dt>
					<dd>: ".$persil_detail["nopersil"]."</dd>
				<dt>Keterangan Persil</dt>
					<dd>: ".$persil_jenis[$persil_detail["persil_jenis_id"]][0]."
					<br />".$persil_jenis[$persil_detail["persil_jenis_id"]][1]."</dd>
				<dt>Luas Tanah</dt>
					<dd>: ".$persil_detail["luas"]." m<sup>2</sup></dd>
				<dt>Kelas Tanah</dt>
					<dd>: ".$persil_detail["kelas"]."</dd>
				<dt>Peruntukan</dt>
					<dd>: ".$persil_peruntukan[$persil_detail["persil_peruntukan_id"]][0]."
					<br />".$persil_peruntukan[$persil_detail["persil_peruntukan_id"]][1]."</dd>
				<dt>Nomor SPPT PBB</dt>
					<dd>: ".$persil_detail["no_sppt_pbb"]."</dd>
				<dt>Lokasi</dt>
					<dd>: RT ".$persil_detail["rt"]." / RW ".$persil_detail["rt"]." - ".$persil_detail["dusun"]."</dd>
			</dl>
		</fieldset>
	</div>";
?>
				<div style="height:10em;"></div>
				</div>
			</div>
		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('data_persil/panduan.php');
		?>
		</td>
	</tr>
</table>
</div>
