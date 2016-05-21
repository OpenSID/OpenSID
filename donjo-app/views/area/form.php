<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Edit Properti / area</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama area / Porperti</th>
<td><input class="inputbox" type="text" name="nama" value="<?=$area['nama']?>" size="60"/></td>
</tr>
<tr>
	<th>Kategori</th>
	<td>
		<select name="ref_polygon">
			<option value="">Kategori</option>
			<?foreach($list_polygon AS $data){?>
			<option <?if($area['ref_polygon']==$data['id']) :?>selected<?endif?> value="<?=$data['id']?>"><?=$data['nama']?></option>
			<?}?>
		</select>
	</td>
</tr>

<?if($area["foto"]!=""){?>
<tr>
	<th>Foto</th>
	<td>
		<img src="<?=base_url()?>assets/images/gis/area/sedang_<?=$area['foto']?>" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;" />

	</td>
</tr>
<?}?>
<tr>
	<th>Ganti Foto</th>
	<td>
		<input class="inputbox" type="file" name="foto" value="<?=$area['foto']?>" size="30"/>
		)* Kosongi jika tidak ingin merubah Foto.
	</td>
</tr>
<tr>
<th width="100">Keterangan</th>
<td><textarea name="desk" style="resize:none;width:400px;height:200px;"><?=$area['desk']?></textarea></td>
</tr>
<tr>
<th>Status</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="enabled" value="1"/<?if($area['enabled'] == '1' OR $area['enabled'] == ''){echo 'checked';}?>>
			<label for="sx1">Aktif</label>
			<input type="radio" id="sx2" name="enabled" value="2"/<?if($area['enabled'] == '2'){echo 'checked';}?>>
			<label for="sx2">Non Aktif</label>
		</div>
	</td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?=site_url()?>area" class="uibutton icon prev">Kembali</a>
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
