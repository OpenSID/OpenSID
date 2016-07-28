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
<h3>Surat Persetujuan Mempelai</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;" >
<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<table class="form">

<tr>
	<th>Nomor Surat</th>
	<td><input name="nomor" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>DATA SUAMI (Berasal dari desa)			:</th>
</tr>
<tr>
<th>Nama Suami</th>
<td>
<select name="suami"  class="inputbox ">
<option value="">Pilih Penduduk</option>

<?php  foreach($laki AS $data){?>
<option value="<?php echo $data['id']?>" ><font style="bold">  <?php echo $data['nama']?></font> -(<?php echo $data['nik']?>)</option>
<?php  }?>
</select>
*) Diisi jika suami berasal dari dalam desa</td>
</tr>

<th>DATA SUAMI (Berasal dari luar desa)		:</th>
<tr>
	<th>Nama Lengkap</th>
	<td><input name="nama_suami" type="text" class="inputbox " size="30"/>*) Diisi jika suami berasal dari luar desa</td>
</tr>
<tr>
	<th>Bin</th>
	<td><input name="bin_suami" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
	<th>Tempat Tanggal Lahir</th>
	<td><input name="tempatlahir_suami" type="text" class="inputbox " size="30"/>
	<input name="tanggallahir_suami" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
	<th>Warganegara</th>
	<td><input name="wn_suami" type="text" class="inputbox " size="15"/></td>
</tr>
<tr>
	<th>Agama</th>
	<td><input name="agama_suami" type="text" class="inputbox " size="15"/></td>
</tr>
<tr>
	<th>Pekerjaan</th>
	<td><input name="pekerjaan_suami" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
	<th>Tempat Tinggal</th>
	<td><input name="tempat_tinggal_suami" type="text" class="inputbox " size="40"/></td>
</tr>
<tr>
<th>DATA ISTRI (Berasal dari desa)			:</th>
</tr>
<tr>
<th>Nama Istri</th>
<td>
<select name="istri"  class="inputbox ">
<option value="">Pilih Penduduk</option>

<?php  foreach($perempuan AS $data){?>
<option value="<?php echo $data['id']?>" ><font style="bold">  <?php echo $data['nama']?></font> -(<?php echo $data['nik']?>)</option>
<?php  }?>
</select>
*) Diisi jika istri berasal dari dalam desa</td>
</tr>
<th>DATA ISTRI (Berasal dari luar desa)		:</th>
</tr>
<tr>
	<th>Nama Lengkap</th>
	<td><input name="nama_istri" type="text" class="inputbox " size="30"/>*) Diisi jika istri berasal dari luar desa</td>
</tr>
<tr>
	<th>Bin</th>
	<td><input name="bin_istri" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
	<th>Tempat Tanggal Lahir</th>
	<td><input name="tempatlahir_istri" type="text" class="inputbox " size="30"/>
	<input name="tanggallahir_istri" type="text" class="inputbox  datepicker" size="20"/></td>
</tr>
<tr>
	<th>Warganegara</th>
	<td><input name="wn_istri" type="text" class="inputbox " size="15"/></td>
</tr>
<tr>
	<th>Agama</th>
	<td><input name="agama_istri" type="text" class="inputbox " size="15"/></td>
</tr>
<tr>
	<th>Pekerjaan</th>
	<td><input name="pekerjaan_istri" type="text" class="inputbox " size="30"/></td>
</tr>
<tr>
	<th>Tempat Tinggal</th>
	<td><input name="tempat_tinggal_istri" type="text" class="inputbox " size="40"/></td>
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
