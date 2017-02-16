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
<h3>Surat Permohonan Sertifikat</h3>
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
<th>Alamat</th>
<td>
<?php echo unpenetration($individu['alamat']); ?>
</td>
</tr>
<tr>
<th>Pendidikan</th>
<td>
<?php echo $individu['pendidikan']?>
</td>
</tr>
<tr>
<th>Warganegara / Agama</th>
<td>
<?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
</td>
</tr>
<?php }?>
<tr>
<th>Alamat Kantor Pertanahan</th>
<td>
<input name="alamatkantor" type="text" class="inputbox required" size="12"/>
</td>
</tr>
<tr>
<th>Nomor Telp/HP Pemilik</th>
<td>
<input name="nomor_hp" type="text" class="inputbox required" size="12"/>
</td>
</tr>
<tr>
<th>ATAS NAMA / KUASA </th>
</tr>
<tr>
<th>N A M A</th>
<td><input name="nama_2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Tempat/Tgl. Lahir</th>
<td><input name="tempatlahir_2" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td><input name="pekerjaan_2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Nomor KTP</th>
<td><input name="ktp_2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>alamat</th>
<td><input name="alamat_2" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Nomor Telp/HP Kuasa</th>
<td>
<input name="hpkuasa" type="text" class="inputbox required" size="12"/>
</td>
</tr>
<tr>
<th>Nomor Surat Kuasa</th>
<td>
<input name="nomor_2" type="text" class="inputbox required" size="12"/> Tanggal <input name="tanggal_3" type="text" class="inputbox required" size="12"/>
</td>
</tr>
<tr>
<th>ATAS BIDANG TANAH YANG TERLETAK DI :</th>
</tr>
<tr>
<th>Letak Tanah di </th>
<td><input name="letak" type="text" class="inputbox required" size="40"/></td>
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
<tr>
<th>Nomor Hak</th>
<td>
<input name="nomor_hak" type="text" class="inputbox required" size="12"/> Luas <input name="luashak" type="text" class="inputbox required" size="12"/> M2
</td>
</tr>
<tr>
<th>Peruntukan Tanah</th>
<td>
<input name="tanahuntuk" type="text" class="inputbox required" size="20"/>
</td>
</tr>
</table></div>
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
