<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
	<table class="form">
		<tr>
			<td colspan='2'>
				Pastikan format berkas telah sesuai <?php echo $jml ?>
			</td>
		</tr>
		<tr>
			<th>Unggah Form Excel</th>
			<td><input name="respon" type="file" /></td>
		</tr>
	</table>
	<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
		<div class="uibutton-group">
			<button class="uibutton confirm" type="submit">Simpan</button>
		</div>
	</div>
</form>
