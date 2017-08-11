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
<h3>Surat Biodata Penduduk</h3>
</div>

<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
	<tr>
		<td colspan="2" style="height: auto;">
    	<div class="box-perhatian">
      	<p><strong>Form ini menghasilkan:<br><br>
      	(1) surat biodata untuk pemohon<br>
      	(2) lampiran F-1.01 FORMULIR ISIAN BIODATA PENDUDUK UNTUK WNI untuk keluarga pemohon<br><br>
      	Pastikan semua biodata pemohon beserta keluarga sudah lengkap sebelum mencetak surat dan lampiran.<br>
      	Untuk melengkapi data itu, ubah data pemohon dan anggota keluarganya di form isian penduduk di modul Penduduk.
      	</strong></p>
    	</div>
    </td>
  </tr>
<tr>
<th>NIK / Nama Pemohon</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?> "  class="inputbox required">
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
		<th colspan="1" style="vertical-align: top;">Anggota Keluarga</th>
		<td colspan="1">
			<div style="margin-left:0px;">
				<table class="list">
					<thead>
						<tr>
							<th>No</th>
							<th align="left" width='70'>NIK</th>
							<th align="left" width='100'>Nama</th>
							<th align="left" width='30' align="center">Jenis Kelamin</th>
							<th width="70" align="left" >Umur</th>
							<th width="70" align="left" >Hubungan</th>
						</tr>
					</thead>

					<tbody>
						<?php if($anggota!=NULL){
							$i=0;?>
							<?php  foreach($anggota AS $data){ $i++;?>
								<tr>
			            <td align="center" width="2"><?php echo $i?></td>
									<td><?php echo $data['nik']?></td>
									<td><?php echo unpenetration($data['nama'])?></td>
									<td><?php echo $data['sex']?></td>
									<td><?php echo $data['umur']?></td>
									<td><?php echo $data['hubungan']?></td>
							</tr>
							<?php }?>
						<?php }?>
					</tbody>
				</table>
			</div>
		</td>
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
