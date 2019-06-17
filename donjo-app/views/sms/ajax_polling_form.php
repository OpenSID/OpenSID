<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
	<tr>
		<th>Nama Polling</th>
		<td><input name="nama_polling" type="text" class="inputbox required"  size="30" maxlength='20' value="<?php   if ($main){ foreach($main as $data): echo $data['nama_polling'];  endforeach; }?>"/>
</td>
	</tr>
	<tr>
		<th>Keterangan</th>
		<td><textarea name="ket_polling" class=" required" style="resize: none; height:100px; width:250px;" size="300" maxlength='200'><?php   if ($main){ foreach($main as $data): echo $data['ket_polling'];  endforeach; }?></textarea>
</td>
	</tr>
</table>

<div class="buttonpane"  style="text-align: right; width:400px;position:absolute;bottom:0px;>
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
