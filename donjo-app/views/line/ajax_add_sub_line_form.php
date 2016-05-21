<script type="text/javascript" src="<?=base_url()?>assets/js/jscolor/jscolor.js"></script>
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<table style="width:100%">
<tr>
<th width="100">Nama line</th>
<td><input class="inputbox" type="text" name="nama" value="<?=$line['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Warna</th>
	<td>
		<input class="color inputbox" size="7" value="ff0000" name="color">
	</td>
</tr>
<tr>
	<th>Simbol</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?=$line['simbol']?>" size="20"/>
	</td>
</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Close</button>
        <button class="uibutton confirm" type="submit">Simpan</button>
    </div>
</div>
</form>
