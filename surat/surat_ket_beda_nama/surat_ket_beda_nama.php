<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

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
<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Beda Identitas</h3>
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
<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
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
<th>IDENTITAS KEDUA</th>
</tr>
<tr>
<th>Identitas dalam (nama kartu)</th>
<td><input name="kartu" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Nomor identitas</th>
<td><input name="identitas" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Nama</th>
<td><input name="nama" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir" type="text" class="inputbox required datepicker" size="20"/></td>
</tr>
<tr>
<th>Jenis Kelamin</th>
<td><input name="sex" type="text" class="inputbox required" size="10"/></td>
</tr>
<tr>
<th>Alamat</th>
<td><input name="alamat" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Agama</th>
<td><input name="agama" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td><input name="pekerjaan" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Keterangan</th>
<td><input name="keterangan" type="text" class="inputbox required" size="60"/></td>
</tr>
<tr>
<th>Perbedaan</th>
<td><input name="perbedaan" type="text" class="inputbox required" size="40"/></td>
</tr>

	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
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
