<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
<?php
	if ($log_status_dasar['tgl_peristiwa']!='') {
		$sekarang = $log_status_dasar['tgl_peristiwa'];
	} else {
		$sekarang = date("d-m-Y");
	}
?>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table id="status-dasar" style="width:100%" class="">
<tr>
	<th align="left">Peristiwa Penting :</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="sd1" name="status_dasar" value="1"/<?php if($nik['status_dasar_id'] == '1'){echo 'checked';}?>>
	<label for="sd1"> Hidup </label>
	<input type="radio" id="sd2" name="status_dasar" value="4"/<?php if($nik['status_dasar_id'] == '4'){echo 'checked';}?>>
	<label for="sd2"> Hilang</label>
	<input type="radio" id="sd3" name="status_dasar" value="3"/<?php if($nik['status_dasar_id'] == '3'){echo 'checked';}?>>
	<label for="sd3"> Pindah Ke Luar Desa</label>
	<input type="radio" id="sd4" name="status_dasar" value="2"/<?php if($nik['status_dasar_id'] == '2'){echo 'checked';}?>>
	<label for="sd4"> Mati </label>
	</div>
	</td>
</tr>
<tr><th>&nbsp;</th></tr>
<tr>
	<th>Tanggal Peristiwa :</th>
	<td>
		<input type="text" class="inputbox datepicker" name="tgl_peristiwa" size="18" value="<?php echo $sekarang;?>">
	</td>
</tr>
<tr><th>&nbsp;</th></tr>
<tr>
	<th align="left">Catatan Peristiwa :</th>
	<td>
		<input type="text" class="inputbox" name="catatan" size="60" value="<?php echo $log_status_dasar['catatan'] ?>"
	</td>
</tr>
<tr>
	<th>&nbsp;</th>
	<td>*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</td>
</tr>
</table>
<div class="buttonpane" style="float: right;">
    <div class="uibutton-group">
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
