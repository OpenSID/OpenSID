<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama polygon</th>
<td><input class="inputbox" type="text" name="nama" value="<?=$polygon['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Warna</th>
	<td>
		<input class="color inputbox" size="7" value="<?=$polygon['color']?>" name="color">
	</td>
</tr>
<tr>
	<th>Simbol</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?=$polygon['simbol']?>" size="20"/>
	</td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?=site_url()?>polygon" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
