<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
	<th align="left">Nomor Rumah Tangga</th>
	<td>
		<input type="text" name="no_kk" value="<?php echo $kk['no_kk']?>" class="inputbox required">
	</td>
</tr>
</tbody>
 </table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
 <div class="uibutton-group">
 <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span>Tutup</button>
 <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
 </div>
</div>
</form>