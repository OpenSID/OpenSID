<form id="validasi" action="<?php  echo $form_action?>" method="POST" enctype="multipart/form-data">
<table style="width:100%">
<tr>
<th width="100">Nama garis</th>
<td><input class="inputbox" type="text" name="nama" value="<?php  echo $garis['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Simbol</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?php  echo $garis['simbol']?>" size="20"/>
	</td>
</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
