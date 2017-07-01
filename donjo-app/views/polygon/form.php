<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Kategori</th>
<td><input class="inputbox" type="text" name="nama" value="<?php echo $polygon['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Warna</th>
	<td>
		<input class="color inputbox" size="7" value="<?php echo $polygon['color']?>" name="color">
	</td>
</tr>
<?php  /*
<tr>
	<th>Simbol</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?php echo $polygon['simbol']?>" size="20"/>
	</td>
</tr>
<th>Tipe polygon</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?php echo $polygon['simbol']?>" size="20"/>
		<div class="uiradio">
			<input type="radio" id="sx1" name="tipe" value="1"/<?php if($polygon['tipe'] == '1' OR $polygon['tipe'] == ''){echo 'checked';}?>>
			<label for="sx1">polygon Atas</label>
			<input type="radio" id="sx2" name="tipe" value="2"/<?php if($polygon['tipe'] == '2'){echo 'checked';}?>>
			<label for="sx2">polygon Kiri</label>
		</div>
	</td>
</tr>
*/?>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>polygon" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
