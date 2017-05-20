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
<h3>Surat Keterangan Untuk Nikah Wanita</h3>
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

<th>Jika wanita, terangkan gadis atau janda</th>
<td>
<input name="perawan" type="text" class="inputbox " size="40"/>
</td>
</tr>

<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
</td>
</tr>
<tr>
<th>DATA AYAH (Isi jika ayah bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama</th>
<td><input name="nama_ayah" type="text" class="inputbox " size="30"/></td>
</tr>
<td></td>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir_ayah" type="text" class="inputbox " size="30"/>
<input name="tanggallahir_ayah" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_ayah" type="text" class="inputbox " size="15"/></td>
<th>Agama</th>
<td><input name="agama_ayah" type="text" class="inputbox " size="15"/></td>
<th>Pekerjaan</th>
<td><input name="pek_ayah" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ayah" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>
<th>DATA IBU (Isi jika ibu bukan warga <?php echo strtolower(config_item('sebutan_desa'))?> ini)</th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama</th>
<td><input name="nama_ibu" type="text" class="inputbox " size="30"/></td></tr>
<td></td>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir_ibu" type="text" class="inputbox " size="30"/>
<input name="tanggallahir_ibu" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_ibu" type="text" class="inputbox " size="15"/></td>
<th>Agama</th>
<td><input name="agama_ibu" type="text" class="inputbox " size="15"/></td>
<th>Pekerjaan</th>
<td><input name="pek_ibu" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ibu" type="text" class="inputbox " size="80"/></td>
</tr>

<tr>
<th>DATA PASANGAN TERDAHULU </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Suami terdahulu</th>
<td><input name="suami_dulu" type="text" class="inputbox " size="40"/></td>
</tr>
<tr>
<th>Bin :</th><td><input name="bin" type="text" class="inputbox " size="40"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tmptlahir_suami_dulu" type="text" class="inputbox " size="30"/>
<input name="tgllahir_suami_dulu" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_suami_dulu" type="text" class="inputbox " size="15"/></td>
<th>Agama</th>
<td><input name="agama_suami_dulu" type="text" class="inputbox " size="15"/></td>
<th>Pekerjaan</th>
<td><input name="pek_suami_dulu" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_suami_dulu" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>
<th>Keterangan Suami Terdahulu</th>
<td><input name="ket_suami_dulu" type="text" class="inputbox " size="80"/></td>
</tr>

<tr>
<th>DATA CALON PASANGAN : </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Lengkap</th>
<td><input name="nama_pasangan" type="text" class="inputbox required" size="30"/></td>
</td>
</tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_pasangan" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_pasangan" type="text" class="inputbox required datepicker" size="20"/>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_pasangan" type="text" class="inputbox required" size="15"/>
<th>Agama</th>
<td><input name="agama_pasangan" type="text" class="inputbox required" size="15"/>
<th>Pekerjaan</th>
<td><input name="pekerjaan_pasangan" type="text" class="inputbox required" size="15"/>
</td>
</tr><tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_pasangan" type="text" class="inputbox required" size="80"/>
</td>
</tr>
<th>Keterangan Pasangan, terangkan Jejaka atau Duda</th>
<td>
<input name="ket_pasangan" type="text" class="inputbox required" size="40"/>
</td>
</tr>

<th>DATA AYAH CALON PASANGAN : </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Ayah Lengkap</th>
<td><input name="ayah_pasangan" type="text" class="inputbox required" size="30"/></td>
</td>
</tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_ayah_pasangan" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_ayah_pasangan" type="text" class="inputbox required datepicker" size="20"/>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_ayah_pasangan" type="text" class="inputbox required" size="15"/>
<th>Agama</th>
<td><input name="agama_ayah_pasangan" type="text" class="inputbox required" size="15"/>
<th>Pekerjaan</th>
<td><input name="pekerjaan_ayah_pasangan" type="text" class="inputbox required" size="15"/>
</td>
</tr><tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_ayah_pasangan" type="text" class="inputbox required" size="80"/>
</td>
</tr>

<th>DATA IBU CALON PASANGAN : </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Ibu Pasangan</th>
<td><input name="ibu_pasangan" type="text" class="inputbox required" size="30"/></td>
</td>
</tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_ibu_pasangan" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_ibu_pasangan" type="text" class="inputbox required datepicker" size="20"/>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_ibu_pasangan" type="text" class="inputbox required" size="15"/>
<th>Agama</th>
<td><input name="agama_ibu_pasangan" type="text" class="inputbox required" size="15"/>
<th>Pekerjaan</th>
<td><input name="pekerjaan_ibu_pasangan" type="text" class="inputbox required" size="15"/>
</td>
</tr><tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_ibu_pasangan" type="text" class="inputbox required" size="80"/>
</td>
</tr>

<th>DATA ISTRI CALON PASANGAN DULU : </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Istri Pasangan Dulu</th>
<td><input name="istri_dulu" type="text" class="inputbox required" size="30"/> Binti : <input name="binti" type="text" class="inputbox " size="40"/></td>
</td>
</tr>
<th>Tempat Tanggal Lahir</th>
<td>
<input name="tempatlahir_istri_dulu" type="text" class="inputbox required" size="30"/>
<input name="tanggallahir_istri_dulu" type="text" class="inputbox required datepicker" size="20"/>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_istri_dulu" type="text" class="inputbox required" size="15"/>
<th>Agama</th>
<td><input name="agama_istri_dulu" type="text" class="inputbox required" size="15"/>
<th>Pekerjaan</th>
<td><input name="pekerjaan_istri_dulu" type="text" class="inputbox required" size="15"/>
</td>
</tr><tr>
<th>Tempat Tinggal</th>
<td>
<input name="alamat_istri_dulu" type="text" class="inputbox required" size="80"/>
</td>
</tr>
<th>Keterangan Istri Dulu</th>
<td>
<input name="ket_istri_dulu" type="text" class="inputbox required" size="80"/>
</td>
</tr>

<th>DATA PERNIKAHAN : </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Hari, Tanggal, Jam</th>
	<td><input name="hari" type="text" class="inputbox required" size="15"/>,
	<input name="tanggal" type="text" class="inputbox required datepicker" size="15"/>,
	<input name="jam" type="text" class="inputbox required" size="10"/></td>
</tr>
<tr>
	<th>Mas Kawin</th>
	<td><input name="mas_kawin" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
	<th>Tunai / Hutang</th>
	<td><input name="tunai" type="text" class="inputbox required" size="10" value="tunai"/></td>
</tr>
<tr>
	<th>Tempat</th>
	<td><input name="tempat" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
	<th>Jumlah Lampiran</th>
	<td><input name="jml_lampiran" type="text" class="inputbox required" size="10"/></td>
</tr>
<tr>
	<th>Lampiran 1</th>
	<td><input name="lampiran1" type="text" class="inputbox " size="40" value="Surat Keterangan Untuk Nikah (model N-1)"/></td>
	<th>Lampiran 2</th>
	<td><input name="lampiran2" type="text" class="inputbox " size="40" value="Surat Keterangan Asal Usul (model N-2)"/></td>
</tr>
<tr>
	<th>Lampiran 3</th>
	<td><input name="lampiran3" type="text" class="inputbox " size="40" value="Surat Persetujuan Mempelai (model N-3)"/></td>
	<th>Lampiran 4</th>
	<td><input name="lampiran4" type="text" class="inputbox " size="40" value="Surat Keterangan Tentang Orang Tua (model N-4)"/></td>
</tr>
<tr>
	<th>Lampiran 5</th>
	<td><input name="lampiran5" type="text" class="inputbox " size="40" value="Surat Keterangan Izin Orang Tua (model N-5)"/></td>
	<th>Lampiran 6</th>
	<td><input name="lampiran6" type="text" class="inputbox " size="40" value=""/></td>
</tr>
<tr>
	<th>Lampiran 7</th>
	<td><input name="lampiran7" type="text" class="inputbox " size="40" value=""/></td>
	<th>Lampiran 8</th>
	<td><input name="lampiran8" type="text" class="inputbox " size="40" value=""/></td>
</tr>
<tr>
<th>DATA WALI NIKAH </th>
<td></td>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
</tr>
<tr>
<th>Nama Wali Nikah</th>
<td><input name="nama_wali" type="text" class="inputbox " size="30"/></td>
<th>Bin :</th><td></th><input name="bin_wali" type="text" class="inputbox " size="30"/></td>
</tr>
<td></td>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="tempatlahir_wali" type="text" class="inputbox " size="30"/>
<input name="tanggallahir_wali" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
<th>Warganegara</th>
<td><input name="wn_wali" type="text" class="inputbox " size="15"/></td>
<th>Agama</th>
<td><input name="agama_wali" type="text" class="inputbox " size="15"/></td>
<th>Pekerjaan</th>
<td><input name="pek_wali" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_wali" type="text" class="inputbox " size="80"/></td>
</tr>
<tr>
<th>Hubungan Dengan Wali</th>
<td><input name="hub_wali" type="text" class="inputbox " size="60"/></td>
</tr>
<tr>

<tr>
<th>Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></th>
<td>
<select name="pamong"  class="inputbox required" >
<option value="">Pilih Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
</select>
</td>
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
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
