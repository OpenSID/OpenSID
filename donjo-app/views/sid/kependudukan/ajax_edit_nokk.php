<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
	<th align="left">Nomor KK</th>
	<td>
		<input type="text" name="no_kk" value="<?php echo $kk['no_kk']?>" class="inputbox required">
	</td>
</tr>
<?php /*
<tr>
	<th align="left">Raskin</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="rs1" name="raskin" value="1"/<?php if($kk['raskin'] == '1'){echo 'checked';}?>>
	<label for="rs1">Ya</label>
	<input type="radio" id="rs2" name="raskin" value="2"/<?php if($kk['raskin'] == '2' OR $kk['raskin'] == ''){echo 'checked';}?>>
	<label for="rs2">Tidak</label>
	</div>
	</td>
</tr>
<tr>
	<th align="left">BLSM</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="rs1" name="id_blt" value="1"/<?php if($kk['id_blt'] == '1'){echo 'checked';}?>>
	<label for="rs1">Ya</label>
	<input type="radio" id="rs2" name="id_blt" value="2"/<?php if($kk['id_blt'] == '2' OR $kk['id_blt'] == ''){echo 'checked';}?>>
	<label for="rs2">Tidak</label>
	</div>
	</td>
</tr>
<tr>
	<th align="left">PKH</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="rs1" name="id_pkh" value="1"/<?php if($kk['id_pkh'] == '1'){echo 'checked';}?>>
	<label for="rs1">Ya</label>
	<input type="radio" id="rs2" name="id_pkh" value="2"/<?php if($kk['id_pkh'] == '2' OR $kk['id_pkh'] == ''){echo 'checked';}?>>
	<label for="rs2">Tidak</label>
	</div>
	</td>
</tr>
<tr>
	<th align="left">Bedah Rumah</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="rs1" name="id_bedah_rumah" value="1"/<?php if($kk['id_bedah_rumah'] == '1'){echo 'checked';}?>>
	<label for="rs1">Ya</label>
	<input type="radio" id="rs2" name="id_bedah_rumah" value="2"/<?php if($kk['id_bedah_rumah'] == '2' OR $kk['id_bedah_rumah'] == ''){echo 'checked';}?>>
	<label for="rs2">Tidak</label>
	</div>
	</td>
</tr>
*/ ?>
</tbody>
 </table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
 <div class="uibutton-group">
 <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
 <button class="uibutton confirm" type="submit">Simpan</button>
 </div>
</div>
</form>