<?php if(empty($new)){?>
<script>
$(function(){
 var nik = {};
 nik.results = [
<?php foreach($penduduk_lepas as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>{id:0,name:'<a href="form/0/1"><h5>Tambah Data Penduduk Baru</h5></a>',info:''}
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
<?php }?>

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

<?php if(empty($new)){?><tr>
<th width="120">Nomor KK</th>
<td><input class="inputbox <?php if($new > 0 AND $rt_sel > 0){?>required<?php }?>" type="text" name="no_kk" id="no_kk" size="25" value="<?php echo $kk['no_kk']?>"></td>
</tr><?php }?>

<tr>
<?php if($new){?><th width="120">Data Keluarga Baru</th><?php }else{?><th>NIK / Nama Kepala KK</th><?php }?>
<td>
<div id="nik_kepala" name="nik_kepala"></div>
</td>
</tr>
<?php if($kk){?>

<?php }elseif($new){?>
<input type="hidden" name="new" value="1">

<tr>
  <th width="100"><?php echo ucwords($this->setting->sebutan_dusun)?></th>
  <td><select name="dusun" onchange="formAction('mainform','<?php echo site_url('keluarga/form/0/1')?>')" <?php if($dusun){?>class="required"<?php }?>>
    <option value="">Pilih <?php echo ucwords($this->setting->sebutan_dusun)?></option>
    <?php foreach($dusun as $data){?>
      <option value="<?php echo $data['dusun']?>" <?php if($dus_sel==$data['dusun']){?>selected<?php }?>><?php echo unpenetration(ununderscore($data['dusun']))?></option>
    <?php }?></select>
  </td>
</tr>

<tr>
  <th>RW</th>
  <td><select name="rw" onchange="formAction('mainform','<?php echo site_url('keluarga/form/0/1')?>')" <?php if($rw){?>class="required"<?php }?>>
    <option value="">Pilih RW</option>
    <?php foreach($rw as $data){?>
      <option value="<?php echo $data['rw']?>" <?php if($rw_sel==$data['rw']){?>selected<?php }?>><?php echo $data['rw']?></option>
    <?php }?></select>
  </td>
</tr>

<tr>
  <th>RT</th>
  <td><select name="rt" onchange="formAction('mainform','<?php echo site_url('keluarga/form/0/1')?>')" <?php if($rt){?>class="required"<?php }?>>
    <option value="">Pilih RT</option>
    <?php foreach($rt as $data){?>
      <option value="<?php echo $data['id']?>" <?php if($rt_sel==$data['id']){?>selected<?php }?>><?php echo $data['rt']?></option>
    <?php }?></select>
  </td>
</tr>

<?php if($rt_sel){?>

<tr>
  <th>Alamat</th>
  <td>
    <input name="alamat" type="text" class="inputbox" size="60" value="<?php echo $penduduk['alamat']?>"/>
  </td>
</tr>
<tr>
  <th width="120">Nomor KK</th>
  <?php
  // $penduduk dipakai kalau validasi data gagal
    if ($penduduk):
      $no_kk = $penduduk['no_kk'];
    else:
      $no_kk = $kk['no_kk'];
    endif;
  ?>
  <td><input class="inputbox required" type="text" name="no_kk" id="no_kk" size="25" value="<?php echo $no_kk ?>"/></td>
</tr>

<tr>
  <th>Data Kepala Keluarga</th>
  <td>&nbsp;</td>
</tr>

<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>

<?php }
}?></table>
</div>



<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div>
</form>
</div>
</td></tr>
</table>
</div>
