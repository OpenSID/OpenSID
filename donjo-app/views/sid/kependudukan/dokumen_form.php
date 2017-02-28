<style>
.bawah{
	position:absolute;
	bottom:10px;
	right:10px;
	width:430px;
}
</style>
<form action="<?php echo $form_action?>" method="POST" id="validasi" enctype="multipart/form-data">
	<table class="form">
		<tr>
			<td width="90">Nama / Jenis Dokumen</td>
			<td>
				<input class="inputbox" type="text" name="nama" size="40" />
			</td>
		</tr>
		<tr>
			<td>Berkas Dokumen</td>
			<td>
				<input type="file" name="satuan" />
			</td>
		</tr>
	</table>
<input type="hidden" name="id_pend" value="<?php echo $penduduk['id']?>"/>
<div class="ui-layout-south panel bottom bawah">
	<div class="right">
 <div class="uibutton-group">
		<button class="uibutton confirm" type="submit">Upload</button>
		</div>
	</div>
</div>
</form>