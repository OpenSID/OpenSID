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
<div id="sidecontent2"  class="lmenu">
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
<h3>Surat Keterangan Kelahiran Sejak 1 Januari 1986 Pengganti Ponis Pengadilan -Manual-</h3>
</div>

  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr></tr>
<tr>
<th>NIK /Nama Anak</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>
<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>"  class="inputbox required">
<?php if($individu){ //bagian info setelah terpilih?>
  <?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
<?php }?>
<tr>
<th>Nomor Surat</th>
<td><input name="nomor" type="text" class="inputbox required" size="12"/></td>
</tr>
<tr>
<th>Anak Ke</th>
<td><input name="anak_ke" type="text" class="inputbox required" size="25"/></td>
</tr>
<tr>
<th>Data Ayah</th>
<tr>
<th>Nama Ayah</th>
<td><input name="nama_ayah" type="text" class="inputbox required" size="50"/></td>
</tr>
<tr>
<th>NIK</th>
<td><input name="nik_ayah" type="text" class="inputbox required" size="20"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="ttl_ayah" type="text" class="inputbox required" size="35"/></td>
</tr>
<tr>
<th>Umur</th>
<td><input name="umur_ayah" type="text" class="inputbox required" size="3"/>Tahun</td>
</tr>
<tr>
<th>WNI</th>
<td><input name="wni_ayah" type="text" class="inputbox required" size="9"/></td>
</tr>
<tr>
<th>Agama</th>
<td><input name="agama_ayah" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td><input name="pekerjaan_ayah" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ayah" type="text" class="inputbox required" size="100"/></td>
</tr>
<tr>
<th>Data IBU</th>
<tr>
<th>Nama IBU</th>
<td><input name="nama_ibu" type="text" class="inputbox required" size="50"/></td>
</tr>
<tr>
<th>NIK</th>
<td><input name="nik_ibu" type="text" class="inputbox required" size="20"/></td>
</tr>
<tr>
<th>Tempat Tanggal Lahir</th>
<td><input name="ttl_ibu" type="text" class="inputbox required" size="35"/></td>
</tr>
<tr>
<th>Umur</th>
<td><input name="umur_ibu" type="text" class="inputbox required" size="3"/>Tahun</td>
</tr>
<tr>
<th>WNI</th>
<td><input name="wni_ibu" type="text" class="inputbox required" size="9"/></td>
</tr>
<tr>
<th>Agama</th>
<td><input name="agama_ibu" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Pekerjaan</th>
<td><input name="pekerjaan_ibu" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Tempat Tinggal</th>
<td><input name="alamat_ibu" type="text" class="inputbox required" size="100"/></td>
</tr>
<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>

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
<tr>
<th>NIP/NIAP</th>
<td>
<select name="pamong_nip"  class="inputbox required">
<option value="">Pilih NIP/NIAP</option>
<?php foreach($pamong AS $data){?>
<option> <?php echo unpenetration($data['pamong_nip'])?> </option>
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
							
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
