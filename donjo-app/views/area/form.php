<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
<h3>Edit Properti / area</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama area / Porperti</th>
<td><input class="inputbox" type="text" name="nama" value="<?php echo $area['nama']?>" size="60"/></td>
</tr>
<tr>
	<th>Kategori</th>
	<td>
		<select name="ref_polygon">
			<option value="">Kategori</option>
			<?php foreach($list_polygon AS $data){?>
			<option <?php if($area['ref_polygon']==$data['id']) :?>selected<?php endif?> value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
			<?php }?>
		</select>
	</td>
</tr>

<?php if($area["foto"]!=""){?>
<tr>
	<th>Foto</th>
	<td>
		<img src="<?php echo base_url().LOKASI_FOTO_AREA?>sedang_<?php echo $area['foto']?>" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;" />

	</td>
</tr>
<?php }?>
<tr>
	<th>Ganti Foto</th>
	<td>
		<input class="inputbox" type="file" name="foto" value="<?php echo $area['foto']?>" size="30"/>
		)* Kosongi jika tidak ingin merubah Foto.
	</td>
</tr>
<tr>
<th width="100">Keterangan</th>
<td><textarea name="desk" style="resize:none;width:400px;height:200px;"><?php echo $area['desk']?></textarea></td>
</tr>
<tr>
<th>Status</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="enabled" value="1"/<?php if($area['enabled'] == '1' OR $area['enabled'] == ''){echo 'checked';}?>>
			<label for="sx1">Aktif</label>
			<input type="radio" id="sx2" name="enabled" value="2"/<?php if($area['enabled'] == '2'){echo 'checked';}?>>
			<label for="sx2">Non Aktif</label>
		</div>
	</td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>area" class="uibutton icon prev">Kembali</a>
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
