<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo spaceunpenetration($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
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
<div  id="sidecontent2" class="lmenu">
<ul>
<?php foreach($menu_surat AS $data){?>
        <li <?php  if($data['url_surat']==$lap){?>class="selected"<?php  }?>><a href="<?php echo site_url()?>surat/<?php echo $data['url_surat']?>"><?php echo unpenetration($data['nama'])?></a></li>
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
<h3>Surat Persetujuan Mempelai (N-3) Manual</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>NIK / NAMA CALON SUAMI</th>
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
<input name="nomor" type="text" class="inputbox required" size="10"/>
</td>
</tr>
<tr>
	<td>DATA CALON ISTRI : </td>
</tr>
<tr>
<th>Nama Lengkap</th>
<td>
<input name="nama_pasangan" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>Binti</th>
<td>
<input name="nama_ayah_pasangan" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_pasangan" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_pasangan" type="text" class="inputbox required datepicker" size="20"/>
</td>
</tr><tr>
<tr>
<th>Kewarganegaraan</th>
<td>
<input name="wn_pasangan" type="text" class="inputbox required" size="10"/>
</td>
<tr>
<th>Agama</th>
<td>
<input name="agama_pasangan" type="text" class="inputbox required" size="15"/>
</td>
</tr>
</tr>
<th>Pekerjaan</th>
<td>
<input name="pekerjaan_pasangan" type="text" class="inputbox required" size="35"/>
</td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_pasangan" type="text" class="inputbox required" size="100"/>
</td>
</tr>
<tr>
</td></tr></table>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
