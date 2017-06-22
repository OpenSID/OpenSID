<?php
/*
 * persil_jenis.php
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
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('data_persil/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Pengelolaan Data Jenis Persil</legend>
			<div id="contentpane">
				<div id="maincontent" class="ui-layout-center" style="padding:0 3em 0 0;">


<?php
/*
 * Form Add/Edit
 *
 * */

if($persil_jenis_detail){
	$nama = $persil_jenis_detail[$id]["nama"];
	$ndesc = $persil_jenis_detail[$id]["ndesc"];
}else{
	$nama = "";
	$ndesc = "";
	$id = 0;
}

echo form_open('data_persil/persil_jenis')."\n";
echo "
	<div class=\"form-group\">
		<label>Nama Jenis Persil</label>
		<input type=\"text\" class=\"form-control\" name=\"nama\" id=\"nama\" placeholder=\"Tuliskan Jenis Persil\" value=\"".$nama."\"/>
	</div>
	<div class=\"form-group\">
		<label>Keterangan</label>
		<textarea class=\"form-control\" name=\"ndesc\" id=\"ndesc\">".$ndesc."</textarea>
	</div>
	<div class=\"form-group\">
		<div class=\"uibutton-group\">
		<input type=\"hidden\" name=\"id\" value=\"".$id."\"/>
		<input type=\"submit\" class=\"uibutton confirm\" name=\"tombol\" id=\"tombol\" value=\"Simpan\"/>
		<input type=\"reset\" class=\"uibutton\" name=\"tombolreset\" id=\"tombolreset\" value=\"Batal\"/>
		</div>
	</div>

";
echo "</form>";
?>

<?php
/*
 * List Data
 *
 * */
if($persil_jenis){
	if(count($persil_jenis)>0){
		echo "
			<div class=\"table-panel top\">
				<table class=\"list\">
					<thead><tr><th>#</th><th style=\"width:120px;\"></th><th style=\"width:200px;\">Nama</th><th>Keterangan</th></tr></thead>
					<tbody>
		";
		$nomer =0;
		foreach($persil_jenis as $key=>$item){
			$nomer++;
			echo "<tr>
			<td>".$nomer."</td>
			<td>
				<div class=\"uibutton-group\">
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/jenis/".$key) ."\" title=\"Rincian\"><span class=\"icon-list\"></span> Rincian</a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/persil_jenis/".$key) ."\" title=\"Ubah\"><span class=\"icon-pencil\"></span></a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/hapus_persil_jenis/".$key) ."\" title=\"Hapus Data\" target=\"confirm\" message=\"Apakah Anda Yakin?\" header=\"Hapus Data\"><span class=\"icon-trash\"></span></a>
				</div>
			</td>
			<td><a href=\"".site_url('data_persil/jenis/'.$key.'/')."\">".$item[0]."</a></td>
			<td>".$item[1]."</td>
			</tr>";
		}
		echo "
					</tbody>
				</table>
			</div>
		";
	}
}else{
	echo "
	<div class=\"box box-warning\">
		<h3 class=\"box-header\">Belum ada Data</h3>
		<div class=\"box-body\">Silakan ditambahkan data Jenis Persil dengan menggunakan formulir dari menu <a href=\"".site_url("data_persil/persil_jenis")."\"><i class=\"icon-plus\"></i> Tambah Data Jenis Persil</a></div>
	</div>
	";
}
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

