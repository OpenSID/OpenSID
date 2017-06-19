<?php
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
								<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('data_persil/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search"></span> Cari</button>
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
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/detail/".$item["id"])."\" title=\"Detail\"><span class=\"icon-list icon-large\"></span> Detail</a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/create/".$item["id"])."\" title=\"Ubah\"><span class=\"icon-pencil icon-large\"></span></a>
					<a class=\"uibutton tipsy south\" href=\"". site_url("data_persil/hapus/".$item["id"])."\" title=\"Hapus Data\" target=\"confirm\" message=\"Apakah Anda Yakin?\" header=\"Hapus Data\"><span class=\"icon-trash icon-large\"></span></a>
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
		<div class=\"box-body\">Silakan ditambahkan data persil dengan menggunakan formulir dari menu <a href=\"".site_url("data_persil/create")."\"><i class=\"icon-plus\"></i> Tambah Data Persil Baru</a></div>
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
