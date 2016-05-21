<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Edit Properti / garis</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Nama garis / Porperti</th>
<td><input class="inputbox" type="text" name="nama" value="<?=$garis['nama']?>" size="60"/></td>
</tr>
<tr>
	<th>Kategori</th>
	<td>
		<select name="ref_line">
			<option value="">Kategori</option>
			<?foreach($list_line AS $data){?>
			<option <?if($garis['ref_line']==$data['id']) :?>selected<?endif?> value="<?=$data['id']?>"><?=$data['nama']?></option>
			<?}?>
		</select>
	</td>
</tr>

<?if($garis["foto"]!=""){?>
<tr>
	<th>Foto</th>
	<td>
		<div class="userbox-avatar">
			<img src="<?=base_url()?>assets/images/gis/garis/kecil_<?=$garis['foto']?>"/>
		</div>
	</td>
</tr>
<?}?>
<tr>
	<th>Ganti Foto</th>
	<td>
		<input class="inputbox" type="file" name="foto" value="<?=$garis['foto']?>" size="30"/>
		)* Kosongi jika tidak ingin merubah Foto.
	</td>
</tr>
<tr>
<th>Status</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="enabled" value="1"/<?if($garis['enabled'] == '1' OR $garis['enabled'] == ''){echo 'checked';}?>>
			<label for="sx1">Aktif</label>
			<input type="radio" id="sx2" name="enabled" value="2"/<?if($garis['enabled'] == '2'){echo 'checked';}?>>
			<label for="sx2">Non Aktif</label>
		</div>
	</td>
</tr>

<? /*
<th>Tipe garis</th>
	<td>
		<div class="uiradio">
			<input type="radio" id="sx1" name="tipe" value="1"/<?if($garis['tipe'] == '1' OR $garis['tipe'] == ''){echo 'checked';}?>>
			<label for="sx1">garis Atas</label>
			<input type="radio" id="sx2" name="tipe" value="2"/<?if($garis['tipe'] == '2'){echo 'checked';}?>>
			<label for="sx2">garis Kiri</label>
		</div>
	</td>
</tr>
*/?>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?=site_url()?>garis" class="uibutton icon prev">Kembali</a>
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
