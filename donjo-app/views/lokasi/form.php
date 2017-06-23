<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
<h3>Edit Properti / Lokasi</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?php  echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Lokasi / Porperti</th>
<td><input class="inputbox" type="text" name="nama" value="<?php  echo $lokasi['nama']?>" size="60"/></td>
</tr>
<tr>
	<th>Kategori</th>
	<td>
		<select name="ref_point">
			<option value="">Kategori</option>
			<?php  foreach($list_point AS $data){?>
			<option <?php  if($lokasi['ref_point']==$data['id']) :?>selected<?php  endif?> value="<?php  echo $data['id']?>"><?php  echo $data['nama']?></option>
			<?php  }?>
		</select>
	</td>
</tr>

<?php  if($lokasi["foto"]!=""){?>
<tr>
	<th>Foto</th>
	<td>
		<div class="userbox-avatar">
			<img src="<?php  echo base_url().LOKASI_FOTO_LOKASI?>kecil_<?php  echo $lokasi['foto']?>"/>
		</div>
	</td>
</tr>
<?php  }?>
<tr>
	<th>Ganti Foto</th>
	<td>
		<input class="" type="file" name="foto" value="<?php  echo $lokasi['foto']?>" size="30"/>
		)* Kosongi jika tidak ingin merubah Foto.
	</td>
</tr>
<tr>
<th width="100">Keterangan</th>
<td><textarea name="desk" style="resize:none;width:400px;height:200px;"><?php  echo $lokasi['desk']?></textarea></td>
</tr>
<tr>
<th>Status</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="enabled" value="1"/<?php  if($lokasi['enabled'] == '1' OR $lokasi['enabled'] == ''){echo 'checked';}?>>
			<label for="sx1">Aktif</label>
			<input type="radio" id="sx2" name="enabled" value="2"/<?php  if($lokasi['enabled'] == '2'){echo 'checked';}?>>
			<label for="sx2">Non Aktif</label>
		</div>
	</td>
</tr>

<?php   /*
<th>Tipe lokasi</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="tipe" value="1"/<?php  if($lokasi['tipe'] == '1' OR $lokasi['tipe'] == ''){echo 'checked';}?>>
			<label for="sx1">lokasi Atas</label>
			<input type="radio" id="sx2" name="tipe" value="2"/<?php  if($lokasi['tipe'] == '2'){echo 'checked';}?>>
			<label for="sx2">lokasi Kiri</label>
		</div>
	</td>
</tr>
*/?>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php  echo site_url()?>plan" class="uibutton icon prev">Kembali</a>
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
