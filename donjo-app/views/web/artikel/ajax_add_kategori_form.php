<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
<th align="left" width="120">Nama Kategori</th>
<td>
<input type="text" name="kategori" class="inputbox2 required" size="40" value="">
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
