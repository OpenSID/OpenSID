<?php
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
if($pemilik){
	echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Data Pemilik</legend>
			<dl>
				<dt>Nama Penduduk</dt>
					<dd>: ".$pemilik["nama"]."</dd>
				<dt>NIK</dt>
					<dd>: ".$pemilik["nik"]."</dd>
				<dt>Alamat</dt>
					<dd>: RT ".$pemilik["rt"]." / RT ".$pemilik["rw"]." - ".strtoupper($pemilik["dusun"])."</dd>
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
					<dd>: ".$persil_detail["alamat_ext"]."</dd>
			</dl>
		</fieldset>
	</div>
	";
}
echo "
	<div class=\"form-group\">
		<fieldset>
			<legend>Detail Persil</legend>
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