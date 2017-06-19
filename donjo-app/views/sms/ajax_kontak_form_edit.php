<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table>
	<tr>
		<th>Nama</th>
		<td><input name="id_pend" type="hidden" class="inputbox " value="<?php echo $kontak['id_pend']?>" size="30" maxlength='15'/><?php  echo unpenetration($kontak['nama']);?></td>
	</tr>	
	<tr>
		<th>No HP</th>
		<td><input name="no_hp" type="text" class="inputbox required" value="<?php echo $kontak['no_hp']?>" size="30" maxlength='15'/></td>
	</tr>
</table>

<div class="buttonpane"  style="text-align: right; width:400px;position:absolute;bottom:0px;>
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
