<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
  	<tr>
		<th>Rentang</th>
		<td><input name="dari" type="text" class="inputbox required"  size="11" maxlength='15' value="<?php echo $rentang['dari']?>"/> - 
		<input name="sampai" type="text" class="inputbox required"  size="11" maxlength='15' value="<?php echo $rentang['sampai']?>"/></td>
	</tr>
</table>

<div class="buttonpane"  style="text-align: right; width:400px;position:absolute;bottom:0px;>
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
