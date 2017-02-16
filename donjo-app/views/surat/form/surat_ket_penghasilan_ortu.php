<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php  foreach($penduduk as $data){?>
				{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php  }?>
		];
		
		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php  if($individu){?>'<?php echo $individu['nik']?> - <?php echo ($individu['nama'])?>'<?php  }else{?>'Ketik no nik di sini..'<?php  }?>,
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
<?php /*
*/?>
<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<h3>Surat Keterangan Penghasilan Orang Tua </h3>
<table class="form">
<tr>
<th width="150">NIK / Nama</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>
<form id="validasi" action="" method="POST" target="_blank">
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
							<th width="200">Nomor Surat</th>
							<td ><input name="nomor" type="text" class="inputbox required" size="12"/></td>
						</tr>
						<tr>
							<th width="200">Data Anak</th>
							<tr>
							<th width="200">Nama Lengkap</th>
							<td ><input name="nama" type="text" class="inputbox required" size="40"/></td>
						</tr>
						<th width="200">NIK</th>
						<td ><input name="no_ktp" type="text" class="inputbox required" size="20"/></td>
						<tr>
<tr>
<th>Tempat Tanggal Lahir </th>
<td ><input name="ttl" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Jenis Kelamin </th>
<td ><input name="jk" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Agama </th>
<td ><input name="agama" type="text" class="inputbox required" size="20"/></td>
</tr>
<tr>
<th>Status </th>
<td ><input name="status_kawin" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>pendidikan </th>
<td ><input name="pendidikan" type="text" class="inputbox required" size="35"/></td>
</tr>
<tr>
<th>Pekerjaan </th>
<td ><input name="pekerjaan" type="text" class="inputbox required" size="30"/></td>
</tr>
<tr>
<th>Kewarganegaraan </th>
<td ><input name="wn" type="text" class="inputbox required" size="15"/></td>
</tr>
<tr>
<th>Penghasilan</th>
<td ><input name="rp" type="text" class="inputbox required" size="25"/></td>
</tr>
<tr>
<th>Terbilang</th>
<td ><input name="terbilang" type="text" class="inputbox required" size="40"/></td>
</tr>
							<tr>
							<th>Surat Keterangan ini dibuat untuk keperluan</th>
							<td ><input name="keperluan" type="text" class="inputbox required" size="80"/></td>
							</td>
						</tr>
<tr>
							<th>Nama Sekolah</th>
							<td ><input name="nama_sekolah" type="text" class="inputbox required" size="50"/></td>
							</td>
						</tr>
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