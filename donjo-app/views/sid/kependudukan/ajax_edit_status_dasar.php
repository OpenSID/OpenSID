<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
<?php 
	$sekarang = date("d-m-Y");
?>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%" class="">
<tr>
	<th align="left">Peristiwa Penting</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="sd1" name="status_dasar" value="1"/<?php if($nik['status_dasar'] == '1'){echo 'checked';}?>>
	<label for="sd1"> Hidup </label>
	<input type="radio" id="sd3" name="status_dasar" value="3"/<?php if($nik['status_dasar'] == '3'){echo 'checked';}?>>
	<label for="sd3"> Pindah Ke Luar Desa</label>
	<input type="radio" id="sd4" name="status_dasar" value="2"/<?php if($nik['status_dasar'] == '2'){echo 'checked';}?>>
	<label for="sd4"> Mati </label>
	</div>
	</td>
</tr>
<tr>
	<th>&nbsp;
	</th>
</tr>
<tr>
	<th>
		Tanggal Peristiwa
	</th>
	<td>
	: <input type="text" class="inputbox datepicker" name="tgl_peristiwa" size="18" value="<?php echo $sekarang;?>">
	</td>
</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
 <div class="uibutton-group">
 <button class="uibutton confirm" type="submit">Simpan</button>
 </div>
</div>
</form>