<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
<h3>Form Data Penduduk</h3>
</div>
<div id="contentpane">

<form id="mainform" name="mainform" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<?if(empty($penduduk)){?>
<tr>
<th width="100">Dusun</th>
<td><select name="dusun" onchange="formAction('mainform','<?=site_url('penduduk/form')?>')" <?if($dusun){?>class="required"<?}?>>
<option value="">Pilih Dusun</option>
<?foreach($dusun as $data){?>
<option value="<?=$data['dusun']?>" <?if($dus_sel==$data['dusun']){?>selected<?}?>><?=unpenetration(ununderscore($data['dusun']))?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>RW</th>
<td><select name="rw" onchange="formAction('mainform','<?=site_url('penduduk/form')?>')" <?if($rw){?>class="required"<?}?>>
<option value="">Pilih RW</option>
<?foreach($rw as $data){?>
<option value="<?=$data['rw']?>" <?if($rw_sel==$data['rw']){?>selected<?}?>><?=$data['rw']?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>RT</th>
<td><select name="rt" onchange="formAction('mainform','<?=site_url('penduduk/form')?>')" <?if($rt){?>class="required"<?}?>>
<option value="">Pilih RT</option>
<?foreach($rt as $data){?>
<option value="<?=$data['id']?>" <?if($rt_sel==$data['id']){?>selected<?}?>><?=$data['rt']?></option>
<?}?></select>
</td>
</tr>
<?}?>
<?if(!empty($rt_sel) OR (!empty($penduduk))){?>

<tr>
<th class="top">Foto</th>
<td>
<div class="userbox-avatar">
<?if($penduduk['foto']){?>
<img src="<?=base_url()?>assets/images/photo/kecil_<?=$penduduk['foto']?>" alt=""/>
<?}else{?>
<img src="<?=base_url()?>assets/images/photo/kuser.png" alt=""/>
<?}?>
</div>
</td>

<input type="hidden" name="old_foto" value="<?=$penduduk['foto']?>">
</tr>

<tr>
<th>Ganti Foto</th>
<td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah foto)</span></td>
</tr>

<tr>
<th width="100">Nama</th>
<td><input name="nama" type="text" class="inputbox required" size="60" value="<?=strtoupper(unpenetration($penduduk['nama']))?>"/></td>
</tr>

<tr>
<th>NIK</th>
<td><input name="nik" type="text" class="inputbox required" size="30" value="<?=$penduduk['nik']?>"/></td>
</tr>

<tr>
<th>Akta Kelahiran</th>
<td><input name="akta_lahir" type="text" class="inputbox" size="30" value="<?=$penduduk['akta_lahir']?>"/></td>
</tr>

<tr>
<th>Jenis Kelamin</th>
<td>
<div class="uiradio">
<input type="radio" id="sx1" name="sex" value="1" <?if($penduduk['id_sex'] == '1' OR $penduduk['id_sex'] == ''){echo 'checked';}?>>
<label for="sx1">Laki-laki</label>
<input type="radio" id="sx2" name="sex" value="2" <?if($penduduk['id_sex'] == '2'){echo 'checked';}?>>
<label for="sx2">Perempuan</label>
</div>
</td>
</tr>

<tr>
<th>Tempat Lahir</th>
<td><input name="tempatlahir" type="text" class="inputbox" size="65"  value="<?=strtoupper($penduduk['tempatlahir'])?>"/></td>
</tr>  
 
<tr>
<th>Tanggal Lahir</th>
<td><input name="tanggallahir" type="text" class="inputbox datepicker" size="20"  value="<?=$penduduk['tanggallahir']?>"/></td>
</tr> 

<tr>
<th>Agama</th>
<td><select name="agama_id" class="required">
<option value="">Pilih Agama</option>
<?foreach($agama as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['agama_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr> 

<tr>
<th>Pendidikan dalam KK</th>
<td><select name="pendidikan_kk_id">
<option value="">Pilih Pendidikan</option>
<?foreach($pendidikan_kk as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['pendidikan_kk_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Pendidikan sedang ditempuh</th>
<td><select name="pendidikan_sedang_id">
<option value="">Pilih Pendidikan</option>
<? foreach($pendidikan_sedang as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['pendidikan_sedang_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Pekerjaan</th>
<td><select name="pekerjaan_id">
<option value="">Pilih Pekerjaan</option>
<? foreach($pekerjaan as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['pekerjaan_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>  
 
<tr>
<th>Status Kawin</th>
<td><select name="status_kawin">
<option value="">Pilih Status</option>
<?foreach($kawin as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['status_kawin']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>  

<tr>
<th>Hubungan dalam Keluarga</th>
<td><select name="kk_level">
<option value="">Pilih Hubungan</option>
<?foreach($hubungan as $data){?>
<option value="<?=$data['id']?>"<?if($penduduk['kk_level']==$data['id']){?> selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>


<tr>
<th>Warganegara</th>
<td><select name="warganegara_id">
<option value="">Pilih warganegara</option>
<?foreach($warganegara as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['warganegara_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>  
 
 
<tr>
<th>Dokumen Paspor</th>
<td><input name="dokumen_pasport" type="text" class="inputbox" size="20"  value="<?=strtoupper($penduduk['dokumen_pasport'])?>"/></td>
</tr>

<tr>
<th>Dokumen KITAS</th>
<td><input name="dokumen_kitas" type="text" class="inputbox" size="20"  value="<?=strtoupper($penduduk['dokumen_kitas'])?>"/></td>
</tr>

<tr>
<th>NIK Ayah</th>
<td><input name="ayah_nik" type="text" class="inputbox" size="30"  value="<?=$penduduk['ayah_nik']?>"/></td>
</tr>  
 
<tr>
<th>NIK Ibu</th>
<td><input name="ibu_nik" type="text" class="inputbox" size="30"  value="<?=$penduduk['ibu_nik']?>"/></td>
</tr>

<tr>
<th>Nama Ayah</th>
<td><input name="nama_ayah" type="text" class="inputbox" size="60"  value="<?=strtoupper(unpenetration($penduduk['nama_ayah']))?>"/></td>
</tr>  
 
<tr>
<th>Nama Ibu</th>
<td><input name="nama_ibu" type="text" class="inputbox" size="60"  value="<?=strtoupper(unpenetration($penduduk['nama_ibu']))?>"/></td>
</tr>


<tr>
<th>Golongan Darah</th>
<td><select name="golongan_darah_id" class="required">
<option value="">Pilih Golongan Darah</option>
<?foreach($golongan_darah as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['golongan_darah_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr> 

<tr>
<th>Status</th>
<td>
<div class="uiradio">
<?$ch='checked';?>
<input type="radio" id="group1" name="status" value="3" checked/<?if($penduduk['status'] == '3' OR $penduduk['status'] == ''){echo $ch;}?>><label for="group1">Pendatang</label>
<input type="radio" id="group2" name="status" value="1" checked><label for="group2">Tetap</label>
<input type="radio" id="group3" name="status" value="2"><label for="group3">Tidak Aktif</label>
</div>
</td>
</tr>

<tr>
<th>Alamat Sebelumnya</th>
<td><input name="alamat_sebelumnya" type="text" class="inputbox" size="60"  value="<?=strtoupper($penduduk['alamat_sebelumnya'])?>"/></td>
</tr>

<tr>
<th>Alamat Sekarang</th>
<td><input name="alamat_sekarang" type="text" class="inputbox" size="60"  value="<?=strtoupper($penduduk['alamat_sekarang'])?>"/></td>
</tr>

<tr>
<th>Cacat</th>
<td><select name="cacat_id">
<option value="">Pilih Jenis</option>
<?foreach($cacat as $data){?>
<option value="<?=$data['id']?>" <?if($penduduk['cacat_id']==$data['id']){?>selected<?}?>><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>   

<tr>
<th>Status kehamilan</th>
<td>
<div class="uiradio">

<input type="radio" id="sh2" name="hamil" value="0"/<?if($penduduk['hamil'] == '0' OR $penduduk['hamil'] == ''){echo 'checked';}?>>
<label for="sh2">Tidak hamil</label>
<input type="radio" id="sh1" name="hamil" value="1"/<?if($penduduk['hamil'] == '1' ){echo 'checked';}?>>
<label for="sh1">hamil</label>
</div>
</td>
</tr>

<tr>
	<th>JAMKESMAS</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="jkm1" name="jamkesmas" value="1"/<?if($penduduk['jamkesmas'] == '1'){echo 'checked';}?>>
	<label for="jkm1">Ya</label>
	<input type="radio" id="jkm3" name="jamkesmas" value="3"/<?if($penduduk['jamkesmas'] == '3'){echo 'checked';}?>>
	<label for="jkm3">Lainnya</label>
	<input type="radio" id="jkm2" name="jamkesmas" value="2"/<?if($penduduk['jamkesmas'] == '2' OR $penduduk['jamkesmas'] == ''){echo 'checked';}?>>
	<label for="jkm2">Tidak</label>
	</div>
	</td>
</tr>



<?}?>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>penduduk" class="uibutton icon prev">Kembali</a>
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
