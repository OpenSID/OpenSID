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
        <input name="nama" type="text" class="inputbox" size="40" value="<?php echo $dokumen['nama']?>"/>
			</td>
		</tr>
		<input type="hidden" name="old_file" value="<?php echo $dokumen['satuan']?>">
		<tr>
			<td>Berkas Dokumen</td>
			<td>
        <input type="file" name="satuan" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah dokumen)</span>
			</td>
		</tr>
	</table>
	<input type="hidden" name="id_pend" value="<?php echo $penduduk['id']?>"/>
	<div class="ui-layout-south panel bottom bawah">
		<div class="right">
			<div class="uibutton-group">
				<button class="uibutton confirm" type="submit">Simpan</button>
			</div>
		</div>
	</div>
</form>