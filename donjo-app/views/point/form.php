<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Kategori</th>
<td><input class="inputbox" type="text" name="nama" value="<?=$point['nama']?>" size="40"/></td>
</tr>
<tr>
	<th>Simbol</th>
	<td>
		<input class="inputbox" type="file" name="simbol" value="<?=$point['simbol']?>" size="20"/>
	</td>
</tr>
<? /*
<th>Tipe point</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="tipe" value="1"/<?if($point['tipe'] == '1' OR $point['tipe'] == ''){echo 'checked';}?>>
			<label for="sx1">point Atas</label>
			<input type="radio" id="sx2" name="tipe" value="2"/<?if($point['tipe'] == '2'){echo 'checked';}?>>
			<label for="sx2">point Kiri</label>
		</div>
	</td>
</tr>
*/?>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?=site_url()?>point" class="uibutton icon prev">Kembali</a>
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
