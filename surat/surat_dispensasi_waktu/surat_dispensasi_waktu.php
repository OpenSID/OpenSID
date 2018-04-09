<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
{id:'<?php echo $data['id']?>',name:'<?php echo $data['nik']." - ".penetration1($data['nama'])?>',info:'<?php echo penetration1($data['alamat'])?>'},
<?php }?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo penetration1($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
$('#'+'main').submit();
}
});

});
</script>


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
<td class="side-menu">
<fieldset>
<legend>Surat Administrasi</legend>
<div id="sidecontent2" class="lmenu">
<ul>
<?php foreach($menu_surat AS $data){?>
       <li <?php  if($data['url_surat']==$lap){?>class="selected"<?php  }?>><a href="<?php echo site_url()?>surat/<?php echo $data['url_surat']?>"><?php echo $data['nama']?></a></li>
<?php }?>
</ul>
</div>
</fieldset>
</td>
<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Dispensasi Waktu</h3>
</div>

<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>NIK / Nama</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>">
<?php if($individu){ //bagian info setelah terpilih?>
  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
<?php }?>
<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
</td>
</tr>
<tr>
<th>Keterangan</th>
<td>
<input name="keterangan" type="text" class="inputbox required" size="40"/>
</td>
</tr>

<tr>
<th>Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></th>
<td>
<select name="pamong">
<option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo $data['pamong_nama']?></font> (<?php echo $data['jabatan']?>)</option>
<?php }?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<option>Kepala Desa</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo $data['jabatan']?></option>
<?php }?>
</select>
</td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="fa fa-print">&nbsp;</span>Cetak</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
