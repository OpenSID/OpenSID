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
</div>
</fieldset>

	</td>
		<td style="background:#fff;padding:5px;">

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
 <h3>Surat Permohonan Akte Lahir</h3>
</div>
   <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">

<tr>
  <th>NIK / Nama kepala Keluarga</th>
  <td>
  <form action="" id="main" name="main" method="POST">
  <div id="nik" name="nik"></div>
  </form>
</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>"  class="inputbox required" >
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
  <th>Nomor Surat</th>
  <td><input name="nomor" type="text" class="inputbox required" size="12"/></td>
</tr>
<tr>
  <th>Nama Anak</th>
  <td><input name="nama_anak" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
  <th>Tempat Tanggal lahir</th>
  <td><input name="tempatlahir_anak" type="text" class="inputbox required" size="10"/>
  <input name="tanggallahir_anak" type="text" class="inputbox required datepicker" size="10"/></td>
</tr>
<tr>
  <th>Hari Lahir</th>
  <td><input name="harilahir_anak" type="text" class="inputbox required" size="5"/></td>
</tr>
<tr>
  <th>Alamat</th>
  <td><input name="alamat_anak" type="text" class="inputbox required" size="40"/></td>
</tr>
<tr>
  <th>Nama Ayah</th>
  <td><input name="nama_ayah" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
  <th>Nama Ibu</th>
  <td><input name="nama_ibu" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
  <th>Alamat</th>
  <td><input name="nama_ortu" type="text" class="inputbox required" size="40"/></td>
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
