<script src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.dialog.js"></script>
<table>
	<tr>
		<td>
			<p>Unduh data respon dalam format yang siap diimpor. Gunakan untuk mengisi/mengupdate data respon secara massal atau untuk memasukkan data respon ke aplikasi lain.</p><br>
		</td>
		<td>
			<a href="<?php echo site_url()?>analisis_respon/data_unduh/1" class="uibutton confirm" target="_blank"> Form Excel + Isi Data</a>
		</td>
	</tr>
	<tr>
		<td>
			Unduh form kosong menampilkan daftar kode untuk setiap kolom.
		</td>
		<td>
			<a href="<?php echo site_url()?>analisis_respon/data_unduh/2" class="uibutton confirm" target="_blank"> Form Excel + Kode Data</a>
		</td>
	</tr>
</table>
<div class="buttonpane" style="text-align:right; width:94%;position:absolute;bottom:0px;">
	<div class="uibutton-group">
		<button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
	</div>
</div>
