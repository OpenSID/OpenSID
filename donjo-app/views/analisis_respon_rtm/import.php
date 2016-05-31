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
			<td width="150">Import Analisis Respon</td>
			<td>
				<input type="file" name="foto" /><span style="color: #aaa;">(File harus dalam format .xls)</span>
			</td>
		</tr>
	</table>
</div>
<div class="ui-layout-south panel bottom bawah">
	<div class="right">
        <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Close</button>
		<button class="uibutton confirm" type="submit">Upload</button>
		</div>
	</div>
</div>
</form>