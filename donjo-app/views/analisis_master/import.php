<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
	<table class="form">
		<tr>
			<td colspan='2'><ul>
				<li>Data yang dibutuhkan untuk Import dengan memenuhi aturan data sebagai berikut <a href="<?php echo base_url()?>assets/import/analisis.xls" class="uibutton special">Aturan Data</a></li>
				<li>Contoh urutan format dapat dilihat pada tautan berikut <a href="<?php echo base_url()?>assets/import/ppls2.xls"  class="uibutton special">Contoh</a></li>
			</ul></td>
		</tr>
		<tr>
			<th>File Master Analisis</th>
			<td><input name="userfile" type="file" /></td>
		</tr>
	</table>
	<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
		<div class="uibutton-group">
			<button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
		</div>
	</div>
</form>
