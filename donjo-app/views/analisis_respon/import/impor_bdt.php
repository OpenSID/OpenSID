<table>
	<tr>
		<td colspan='2'>
			<p>Impor Data BDT 2015 menggunakan format data yang diperoleh dari TNP2K. Contoh format data ada di tautan berikut.</p><br>
		</td>
	</tr>
	<tr>
		<td width='90'>
			<a href="<?php echo site_url()?>analisis_respon/unduh_form_bdt" class="uibutton confirm" target="_blank"> Form Data BDT 2015 </a>
			<br><br>
		</td>
	</tr>
</table>
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
	<table class="form">
		<tr>
			<td colspan='2'>
				Pastikan format berkas telah sesuai <?php echo $jml ?>
			</td>
		</tr>
		<tr>
			<th>Pilih berkas Data BDT 2015</th>
			<td><input name="bdt" type="file" /></td>
		</tr>
	</table>
	<div class="buttonpane" style="text-align: right;">
		<div class="uibutton-group right">
			<button class="uibutton confirm" type="submit">Impor</button>
		</div>
	</div>
</form>
