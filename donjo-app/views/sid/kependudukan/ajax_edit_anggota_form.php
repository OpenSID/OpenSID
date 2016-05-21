<form action="<?=$form_action?>" method="post" id="validasi">

<div class="content-header">
    <h4>KK No.<?=$kepala_kk['no_kk']?> Keluarga : <?=$kepala_kk['nama']?></h4>
</div>
<table style="width:100%">
<tr>
<th width="100" align="left">NIK</th>
<td>
	<?=$main['nik']?>
</td>
</tr>
<tr>
<th align="left">Nama Penduduk</th>
<td>
	<?=$main['nama']?>
</td>
</tr>
<tr>
<th></th>
<td>
</td>
	</tr>
	<tr>
	<tr>
<th align="left">Hubungan</th>
<td><select name="kk_level" class="required" width="50">
<option value=""> ----- Pilih Hubungan ----- </option>
<?foreach($hubungan as $data){if($data['id']>0){?>
	<option value="<?=$data['id']?>" <?if($data['id']==$main['kk_level']){?>selected<?}?>><?=$data['hubungan']?></option>
<?}}?></select>
</td>
	</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
        <button class="uibutton confirm" type="submit">Simpan</button>
    </div>
</div>
</form>
