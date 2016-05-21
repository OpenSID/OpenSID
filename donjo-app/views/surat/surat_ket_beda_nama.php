<script>
$(function(){
var nik = {};
nik.results = [
<?foreach($penduduk as $data){?>
{id:'<?=$data['id']?>',name:"<?=$data['nik']." - ".($data['nama'])?>",info:"<?=($data['alamat'])?>"},
<?}?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?if($individu){?>'<?=$individu['nik']?> - <?=spaceunpenetration($individu['nama'])?>'<?}else{?>'Ketik no nik di sini..'<?}?>,
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
<?foreach($menu_surat AS $data){?>
        <li <? if($data['url_surat']==$lap){?>class="selected"<? }?>><a href="<?=site_url()?>surat/<?=$data['url_surat']?>"><?=$data['nama']?></a></li>
<?}?>
</ul>
</div>
</fieldset>
</td>
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

<form id="validasi" action="<?=$form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?=$individu['id']?>" class="inputbox required" >
<?if($individu){ //bagian info setelah terpilih?>
<tr>
<th>Tempat Tanggal Lahir (Umur)</th>
<td>
<?=$individu['tempatlahir']?> <?=tgl_indo($individu['tanggallahir'])?> (<?=$individu['umur']?> Tahun)
</td>
</tr>
<tr>
<th>Alamat</th>
<td>
<?=unpenetration($individu['alamat']); ?>
</td>
</tr>
<tr>
<th>Pendidikan</th>
<td>
<?=$individu['pendidikan']?>
</td>
</tr>
<tr>
<th>Warganegara / Agama</th>
<td>
<?=$individu['warganegara']?> / <?=$individu['agama']?>
</td>
</tr>
<?}?>
<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="12"/>
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

<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>
<?foreach($pamong AS $data){?>
<option value="<?=$data['pamong_id']?>"><font style="bold"><?=unpenetration($data['pamong_nama'])?></font> (<?=unpenetration($data['jabatan'])?>)</option>
<?}?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?foreach($pamong AS $data){?>
<option ><?=unpenetration($data['jabatan'])?></option>
<?}?>
</select>
</td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">     
<a href="<?=site_url()?>sid_wilayah" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Cetak</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
