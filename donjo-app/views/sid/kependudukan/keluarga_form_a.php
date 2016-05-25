<style>
table.form.detail th{
 padding:5px;
 background:#fafafa;
 border-right:1px solid #eee;
}
table.form.detail td{
 padding:5px;
}
</style>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
<h3>Form Manajemen KK</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<table class="form">
<input name="id_kk" type="hidden" value="<?php echo $id_kk?>">
<input name="kk_level" type="hidden" value="0">
<input name="id_cluster" type="hidden" value="<?php echo $kk['id_cluster']?>">
<tr>
<th width="150">No. KK</th>
<td><?php echo $kk['no_kk']?></td>
</tr>

<tr>
<th>Kepala KK</th>
<td><?php echo unpenetration($kk['nama'])?></td>
</tr>

<tr>
<th>Dusun</th>
<td><?php echo ununderscore(unpenetration($kk['dusun']))?></td>
</tr>

<tr>
<th>RW</th>
<td><?php echo $kk['rw']?></td>
</tr>

<tr>
<th>RT</th>
<td><?php echo $kk['rt']?></td>
</tr>


<tr>
<th class="top">Foto</th>
<td>
<div class="userbox-avatar">
<img src="<?php echo base_url()?>assets/images/photo/kuser.png" alt=""/>
</div>
</td>
</tr>

<tr>
<th>Ganti Foto</th>
<td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongi jika tidak ingin merubah foto)</span></td>
</tr>

<tr>
<th width="150">Nama</th>
<td><input name="nama" type="text" class="inputbox required" size="60"/></td>
</tr>

<tr>
<th>NIK</th>
<td><input name="nik" type="text" class="inputbox required" size="30"/></td>
</tr>

<tr>
<th>Jenis Kelamin</th>
<td>
<div class="uiradio">
<input type="radio" id="sx1" name="sex" value="1"/ checked>
<label for="sx1">Laki-laki</label>
<input type="radio" id="sx2" name="sex" value="2"/>
<label for="sx2">Perempuan</label>
</div>
</td>
</tr>

<tr>
<th>Tempat Lahir</th>
<td><input name="tempatlahir" type="text" class="inputbox" size="65"/></td>
</tr>

<tr>
<th>Tanggal Lahir</th>
<td><input name="tanggallahir" type="text" class="inputbox datepicker" size="20"/></td>
</tr>
 
<tr>
<th>Agama</th>
<td><select name="agama_id" class="required">
<option value="">Pilih Agama</option>
<?php foreach($agama as $data){?>
<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
<?php }?></select>
</td>
</tr> 

<tr>
<th>Pendidikan dalam KK</th>
<td><select name="pendidikan_kk_id">
<option value="">Pilih Pendidikan</option>
<?php foreach($pendidikan_kk as $data){?>
<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
<?php }?></select>
</td>
</tr>

<tr>
<th>Pekerjaan</th>
<td><select name="pekerjaan_id">
<option value="">Pilih Pekerjaan</option>
<?php foreach($pekerjaan as $data){?>
<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
<?php }?></select>
</td>
</tr>

<tr>
<th>Status Perkawinan</th>
<td><select name="status_kawin">
<option value="">Pilih Status</option>
<?php foreach($kawin as $data){?>
<option value="<?php echo $data['id']?>"><?php echo strtoupper($data['nama'])?></option>
<?php }?></select>
</td>
</tr>

<tr>
<th>Hubungan dalam Keluarga</th>
<td><select name="kk_level">
<option value="">Pilih Hubungan</option>
<?php foreach($hubungan as $data){?>
<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
<?php }?></select>
</td>
</tr>

<tr>
<th>Kewarganegaraan</th>
<td><select name="warganegara_id">
<option value="">Pilih warganegara</option>
<?php foreach($warganegara as $data){?>
<option value="<?php echo $data['id']?>"><?php echo strtoupper($data['nama'])?></option>
<?php }?></select>
</td>
</tr>  

<tr>
<th>No Pasport</th>
<td><input name="dokumen_pasport" type="text" class="inputbox" size="20" /></td>
</tr>

<tr>
<th>No Kitas/Kitap</th>
<td><input name="dokumen_kitas" type="text" class="inputbox" size="20"/></td>
</tr>

<tr>
<th>NIK Ayah</th>
<td><input name="ayah_nik" type="text" class="inputbox" size="30" /></td>
</tr>

<tr>
<th>NIK Ibu</th>
<td><input name="ibu_nik" type="text" class="inputbox" size="30" /></td>
</tr>

<tr>
<th>Nama Ayah</th>
<td><input name="nama_ayah" type="text" class="inputbox" size="60" /></td>
</tr>

<tr>
<th>Nama Ibu</th>
<td><input name="nama_ibu" type="text" class="inputbox" size="60" /></td>
</tr>

<tr>
<th>Golongan Darah</th>
<td><select name="golongan_darah_id" class="required">
<option value="">Pilih Golongan Darah</option>
<?php foreach($golongan_darah as $data){?>
<option value="<?php echo $data['id']?>"><?php echo strtoupper($data['nama'])?></option>
<?php }?></select>
</td>
</tr>


<tr>
<th>Status</th>
<td>
<div class="uiradio">
<input type="radio" id="group1" name="status" value="1" checked><label for="group1">Tetap</label>
<input type="radio" id="group2" name="status" value="2"><label for="group2">Tidak Aktif</label>
</div>
</td>
</tr>


</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> 
</form> 
</div>
</td></tr>
</table>
</div>
