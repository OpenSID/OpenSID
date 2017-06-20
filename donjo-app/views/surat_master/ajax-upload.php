<style>
.bawah{
	position:absolute;
	bottom:10px;
	right:10px;
	width:430px;
}
</style>
<form action="<?php echo $form_action?>" method="POST" id="validasi" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
	<table width="100%">
		<tr>
			<td width="150">Upload Template</td>
			<td>
				<input type="file" name="foto" /><span style="color: #aaa;">(File harus dalam format .rtf)</span>
			</td>
		</tr>
	</table>
</div>
<div class="ui-layout-south panel bottom bawah">
	<div class="right">
        <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
		<button class="uibutton confirm" type="submit"><span class="fa fa-upload"></span> Upload</button>
		</div>
	</div>
</div>
</form>
