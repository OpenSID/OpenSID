<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
<th align="left" width="120">Sub Kategori</th>
<td>
<input type="text" name="kategori" class="inputbox2 required" size="40" value="<?php echo $subkategori['kategori']?>">
</td>
</tr>

</table>
<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
