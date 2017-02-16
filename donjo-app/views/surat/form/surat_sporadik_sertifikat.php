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
<h3>Surat SPORADIK Sertifikat</h3>
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
<tr>
<th>Tempat Tanggal Lahir (Umur)</th>
<td>
<?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
</td>
</tr>
<tr>
<th>Pekerjaan</th>
<td>
<?php echo $individu['pekerjaan']?>
</td>
</tr>
<tr>
<th>No. KTP/Domisili</th>
<td>
<?php echo $individu['nik']?>
</td>
</tr>
<tr>
<th>Alamat</th>
<td>
<?php echo unpenetration($individu['alamat']); ?>
</td>
</tr>
<?php }?>
<tr>
<th>ATAS BIDANG TANAH YANG TERLETAK DI :</th>
</tr>
<tr>
<th>Jalan </th>
<td><input name="jalan" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>RT/RW</th>
<td><input name="rtrw" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Desa / Kelurahan</th>
<td><input name="desalurah" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Kecamatan</th>
<td><input name="camat-2" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Kabupaten / Kota</th>
<td><input name="kab-2" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>NIB</th>
<td><input name="nib" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Luas Tanah</th>
<td><input name="luashak" type="text" class="inputbox required" size="12"/> M2
</tr>
<tr>
<th>Status Tanah</th>
<td><input name="statustanah" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Dipergunakan</th>
<td>
<input name="tanahuntuk" type="text" class="inputbox required" size="40"/>
</td>
</tr>
<tr>
<th>BATAS-BATAS</th>
</tr>
<tr>
<th>Sebelah Utara</th>
<td>
<input name="utara" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Sebelah Timur</th>
<td><input name="timur" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Sebelah Selatan</th>
<td>
<input name="selatan" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Sebelah Barat</th>
<td><input name="barat" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Tanah di Peroleh dari :</th>
<td><input name="peroleh" type="text" class="inputbox required" size="30"/>Sejak Tahun <input name="perolehtahun" type="text" class="inputbox required" size="5"/></td>
</tr>
<tr>
<th> SAKSI I</TH>
</tr>
<tr>
<th>Nama</th>
<td><input name="namasaksi1" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>umur</th>
<td><input name="umursaksi1" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td>
<input name="pekerjaansaksi1" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>alamat</th>
<td>
<input name="alamatsaksi1" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th> SAKSI II</TH>
</tr>
<tr>
<th>Nama</th>
<td><input name="namasaksi2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>umur</th>
<td><input name="umursaksi2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td>
<input name="pekerjaansaksi2" type="text" class="inputbox required" size="30"/>
</td>
</tr>
<tr>
<th>alamat</th>
<td>
<input name="alamatsaksi2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
<tr>
<th>N I P</th>
<td>
<select name="pamong_nip"  class="inputbox required">
<option value="">Pilih No NIP</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['pamong_nip'])?></option>
<?php }?>
</select>

</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['jabatan'])?></option>
<?php }?>
</select>
</td>
</tr>
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
<button class="uibutton" type="reset">Clear</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
