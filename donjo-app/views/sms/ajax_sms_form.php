<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
	<tr>
		<th>No. Tujuan</th>
		<td><input name="DestinationNumber" type="text" class="inputbox required" value="<?php echo $sms['DestinationNumber']?>" size="30" maxlength='15'/></td>
	</tr>
	<tr>
		<th width="100">Isi Pesan</th>
		<td><textarea name="TextDecoded" class=" required" style="resize: none; height:200px; width:280px;" size="1000" maxlength='160'><?php echo $sms['TextDecoded']?></textarea></td>
	</tr>
</table>

<div class="buttonpane" style="text-align: right;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-paper-plane"></span> Kirim</button>
    </div>
</div>
</form>
