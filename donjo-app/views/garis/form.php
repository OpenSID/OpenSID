<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div class="content-header">
<h3>Edit Properti / garis</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?php  echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama Garis/Porperti</th>
<td><input class="inputbox" type="text" name="nama" value="<?php  echo $garis['nama']?>" size="60"/></td>
</tr>
<tr>
	<th>Kategori</th>
	<td>
		<select name="ref_line">
			<option value="">Kategori</option>
			<?php  foreach($list_line AS $data){?>
			<option <?php  if($garis['ref_line']==$data['id']) :?>selected<?php  endif?> value="<?php  echo $data['id']?>"><?php  echo $data['nama']?></option>
			<?php  }?>
		</select>
	</td>
</tr>

<?php  if($garis["foto"]!=""){?>
<tr>
	<th>Foto</th>
	<td>
		<div class="userbox-avatar">
			<img src="<?php  echo base_url().LOKASI_FOTO_GARIS?>kecil_<?php  echo $garis['foto']?>"/>
		</div>
	</td>
</tr>
<?php  }?>
<tr>
	<th>Ganti Foto</th>
	<td>
		<input class="" type="file" name="foto" value="<?php  echo $garis['foto']?>" size="30"/>
		)* Kosongi jika tidak ingin merubah Foto.
	</td>
</tr>
<tr>
<th>Status</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="enabled" value="1"/<?php  if($garis['enabled'] == '1' OR $garis['enabled'] == ''){echo 'checked';}?>>
			<label for="sx1">Aktif</label>
			<input type="radio" id="sx2" name="enabled" value="2"/<?php  if($garis['enabled'] == '2'){echo 'checked';}?>>
			<label for="sx2">Non Aktif</label>
		</div>
	</td>
</tr>

<?php   /*
<th>Tipe garis</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="tipe" value="1"/<?php  if($garis['tipe'] == '1' OR $garis['tipe'] == ''){echo 'checked';}?>>
			<label for="sx1">garis Atas</label>
			<input type="radio" id="sx2" name="tipe" value="2"/<?php  if($garis['tipe'] == '2'){echo 'checked';}?>>
			<label for="sx2">garis Kiri</label>
		</div>
	</td>
</tr>
*/?>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php  echo site_url()?>garis" class="uibutton icon prev">Kembali</a>
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
