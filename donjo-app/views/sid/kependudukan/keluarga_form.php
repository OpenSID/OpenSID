<?if(empty($new)){?>
<script>
$(function(){
 var nik = {};
 nik.results = [
<?foreach($penduduk as $data){?>
{id:'<?=$data['id']?>',name:"<?=$data['nik']." - ".($data['nama'])?>",info:"<?=($data['alamat'])?>"},
<?}?>{id:0,name:'<a href="form/0/1"><h5>Tambah Data Penduduk Baru</h5></a>',info:''}
 ];
nik.total = nik.results.length;

$('#nik_kepala').flexbox(nik, {
resultTemplate: '<div><label></label>{name}</div><div>{info}</div>',
watermark: 'Ketik no nik di sini..',
 width: 260,
 noResultsText :'<a href="form/0/1"><h5>Tambah Data Penduduk Baru</h5></a>',
 onSelect: function(){
$('#mainform').form.submit();
 }
});
$("#nik_detail").show();
});
</script>
<?}?>

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
<form id="mainform" name="mainform" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<table class="form">
<?if(empty($new)){?><tr>
<th width="120">Nomor KK</th>
<td><input class="inputbox <?if($new > 0 AND $rt_sel > 0){?>required<?}?>" type="text" name="no_kk" id="no_kk" size="25" value="<?=$kk['no_kk']?>"></td>
</tr><?}?>

<tr>
<?if($new){?><th width="120">Data Kepala KK Baru</th><?}else{?><th>NIK / Nama Kepala KK</th><?}?>
<td>
<div id="nik_kepala" name="nik_kepala"></div>
</td>
</tr>
<?if($kk){?>

<?}elseif($new){?>
<input type="hidden" name="new" value="1">

<tr>
<th width="100">Dusun</th>
<td><select name="dusun" onchange="formAction('mainform','<?=site_url('keluarga/form/0/1')?>')" <?if($dusun){?>class="required"<?}?>>
<option value="">Pilih Dusun</option>
<?foreach($dusun as $data){?>
<option value="<?=$data['dusun']?>" <?if($dus_sel==$data['dusun']){?>selected<?}?>><?=unpenetration(ununderscore($data['dusun']))?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>RW</th>
<td><select name="rw" onchange="formAction('mainform','<?=site_url('keluarga/form/0/1')?>')" <?if($rw){?>class="required"<?}?>>
<option value="">Pilih RW</option>
<?foreach($rw as $data){?>
<option value="<?=$data['rw']?>" <?if($rw_sel==$data['rw']){?>selected<?}?>><?=$data['rw']?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>RT</th>
<td><select name="rt" onchange="formAction('mainform','<?=site_url('keluarga/form/0/1')?>')" <?if($rt){?>class="required"<?}?>>
<option value="">Pilih RT</option>
<?foreach($rt as $data){?>
<option value="<?=$data['id']?>" <?if($rt_sel==$data['id']){?>selected<?}?>><?=$data['rt']?></option>
<?}?></select>
</td>
</tr>
<?if($rt_sel){?>




<tr>
<th class="top">Foto</th>
<td>
<div class="userbox-avatar">
<img src="<?=base_url()?>assets/images/photo/kuser.png" alt=""/>
</div>
</td>
</tr>

<tr>
<th>Ganti Foto</th>
<td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongi jika tidak ingin merubah foto)</span></td>
</tr>

<tr>
<th width="120">Nomor KK</th>
<td><input class="inputbox required" type="text" name="no_kk" id="no_kk" size="25" value="<?=$kk['no_kk']?>"></td>
</tr>

<tr>
<th>Nama</th>
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
<?foreach($agama as $data){?>
<option value="<?=$data['id']?>"><?=$data['nama']?></option>
<?}?></select>
</td>
</tr> 

<tr>
<th>Pendidikan dalam KK</th>
<td><select name="pendidikan_kk_id">
<option value="">Pilih Pendidikan</option>
<?foreach($pendidikan_kk as $data){?>
<option value="<?=$data['id']?>"><?=$data['nama']?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Pendidikan sedang ditempuh</th>
<td><select name="pendidikan_sedang_id">
<option value="">Pilih Pendidikan</option>
<? foreach($pendidikan_sedang as $data){?>
<option value="<?=$data['id']?>"><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Pekerjaan</th>
<td><select name="pekerjaan_id">
<option value="">Pilih Pekerjaan</option>
<?foreach($pekerjaan as $data){?>
<option value="<?=$data['id']?>"><?=$data['nama']?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Status Perkawinan</th>
<td><select name="status_kawin">
<option value="">Pilih Status</option>
<?foreach($kawin as $data){?>
<option value="<?=$data['id']?>"><?=strtoupper($data['nama'])?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Hubungan dalam Keluarga</th>
<td><select name="kk_level">
<option value="">Pilih Hubungan</option>
<?foreach($hubungan as $data){?>
<option value="<?=$data['id']?>"><?=$data['nama']?></option>
<?}?></select>
</td>
</tr>

<tr>
<th>Kewarganegaraan</th>
<td><select name="warganegara_id">
<option value="">Pilih warganegara</option>
<?foreach($warganegara as $data){?>
<option value="<?=$data['id']?>"><?=strtoupper($data['nama'])?></option>
<?}?></select>
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
<?foreach($golongan_darah as $data){?>
<option value="<?=$data['id']?>"><?=strtoupper($data['nama'])?></option>
<?}?></select>
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


<?}
}?></table>
</div>



<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?=site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
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
