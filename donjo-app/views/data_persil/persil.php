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
<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('data_persil/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Daftar Data Persil <?php echo $desa["nama_desa"];?></legend>
			<div id="contentpane">
				<div id="maincontent" class="ui-layout-center" style="padding:0 3em 0 0;">

					<form id="mainform" name="mainform" action="" method="post">
							<div class="left">
								<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('data_persil/search')?>');$('#'+'mainform').submit();}" />
								<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('data_persil/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
							</div>
					</form>
			<?php
			if($_SESSION["success"]==1){
				echo "<div>".$_SESSION["pesan"]."</div>";
				$_SESSION["success"]=0;
				$_SESSION["pesan"]="";
			}
			?>

<?php
/*
 * List Data
 *
 * */

if($persil){
	if(count($persil)>0){
		echo "
			<div class=\"table-panel top\">
				<table class=\"list\">
					<thead><tr><th>#</th><th style=\"width:120px;\"></th>
					<th>Nama Pemilik</th><th>NIK</th>
					<th>NO Persil</th>
					<th>Luas (m<sup>2</sup>)</th>
					<th>Nomor SPPT PBB</th>
					</tr></thead>
					<tbody>
		";
		$nomer =0;
		foreach($persil as $key=>$item){
			$nomer++;
			echo "<tr>
			<td class=\"angka\">".$nomer."</td>
			<td><div class=\"uibutton-group\">
					<a class=\"uibutton tipsy south fa-tipis\" href=\"". site_url("data_persil/detail/".$item["id"])."\" title=\"Rincian\"><span class=\"fa fa-list\"></span> Rincian</a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/create/".$item["id"])."\" title=\"Ubah\"><span class=\"fa fa-pencil\"></span></a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/hapus/".$item["id"])."\" title=\"Hapus Data\" target=\"confirm\" message=\"Apakah Anda Yakin?\" header=\"Hapus Data\"><span class=\"fa fa-trash\"></span></a>
				</div></td>
			<td>".$item["namapemilik"]."</td>
			<td>".$item["nik"]."</td>
			<td>".$item["nopersil"]."</td>
			<td>".$item["luas"]."</td>
			<td><a href=\"#\">".$item["no_sppt_pbb"]."</a></td>
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
		<div class=\"box-body\">Silakan ditambahkan data persil dengan menggunakan formulir dari menu <a href=\"".site_url("data_persil/create")."\"><i class=\"fa fa-plus\"></i> Tambah Data Persil Baru</a></div>
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
