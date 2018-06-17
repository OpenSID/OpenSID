<style type="text/css">
	th { text-align: left; }
</style>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>

<form action="<?php echo $form_action?>" method="post" id="validasi">
	<table id="status-dasar" style="width:100%" class="">
		<tr>
			<th align="left">Status dasar penduduk</th>
			<td>:</td>
			<td>
				<?= $log_status_dasar['status'] ?>
			</td>
		</tr>
		<tr><th colspan="3">&nbsp;</th></tr>
		<tr>
			<th>Tanggal Peristiwa</th>
			<td>:</td>
			<td>
				<input type="text" class="inputbox datepicker" name="tgl_peristiwa" size="18" value="<?php echo $log_status_dasar['tgl_peristiwa'];?>">
			</td>
		</tr>
		<tr><th colspan="3">&nbsp;</th></tr>
		<tr>
			<th align="left">Catatan Peristiwa</th>
			<td>:</td>
			<td>
				<input type="text" class="inputbox" name="catatan" size="60" value="<?php echo $log_status_dasar['catatan'] ?>"
			</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<td>*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</td>
		</tr>
	</table>
	<div class="buttonpane" style="float: right;">
    <div class="uibutton-group">
      <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
      <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
	</div>
</form>
