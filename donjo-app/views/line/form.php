<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Kategori</th>
<td><input class="inputbox" type="text" name="nama" value="<?php echo $line['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Warna</th>
	<td>
		<input class="color inputbox" size="7" value="<?php echo $line['color']?>" name="color">
	</td>
</tr>
<?php 
?>
</table>
</div>
 
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>line" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">

<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>