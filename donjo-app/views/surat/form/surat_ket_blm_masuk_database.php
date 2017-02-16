<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
 {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];
nik.total = nik.results.length;
$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: 'Ketik nama / nik di sini..',
width: 260,
noResultsText :'Tidak ada nama / nik yang sesuai..',
onSelect: function() {
$('#'+'main').submit();
} 
});
});
</script>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Form Manajemen KK : <?php echo $kepala_kk['nama']?></h3>
</div>
<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="15%"><B><U>Nama Kepala KK</B></U></th></td>
							<td><div id="nik" name="nik"></div></td>
						</tr>
					</table>
					</form>
				</div>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php 
						if($individu){ 
							?>
							<tr>
								<th width="15%">Tempat Tanggal Lahir (Umur)</th>
								<td><?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)</td>
							</tr>
							<tr>
								<th width="15%">Alamat</th>
								<td><?php echo unpenetration($individu['alamat']); ?></td>
							</tr>
							<tr>
								<th width="15%">Pendidikan</th>
								<td><?php echo $individu['pendidikan']; ?></td>
							</tr>
							<tr>
								<th width="15%">Warganegara / Agama</th>
								<td><?php echo $individu['warganegara']?> / <?php echo $individu['agama']?></td>
							</tr>
							<tr>
								<th width="15%">No. KK</th>
								<td><?php echo $individu['no_kk']; ?></td>
							</tr>
							<tr>
								<th></th>
							</tr>
						<?php }	?>
					</table>
<div id="contentpane">
<form id="mainform" name="mainform" action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table width="100%" cellpadding="3" cellspacing="4">
<div align="center">
<h4>No. <?php echo unpenetration($kepala_kk['no_kk']=$individu['no_kk']) ?> </h4> 
</div>
<tr>
<td width="100">Alamat</td>
<td width="200">: <?php echo strtoupper(unpenetration(ununderscore($kepala_kk['dusun']=$individu['dusun']))) ?></td>
<td width="120">Kabupaten</td>
<td width="150">: <?php echo strtoupper(unpenetration($desa['nama_kabupaten'])) ?></td>
</tr>
<tr>
<td>RT/RW</td>
<td>: <?php echo unpenetration($kepala_kk['rt']=$individu['rt']) ?> / <?php echo unpenetration($kepala_kk['rw']=$individu['rw']) ?> </td>
<td>Kode Pos</td>
<td>: <?php echo $desa['kode_pos'] ?></td>
</tr>
<tr>
<td>Kelurahan/Desa</td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_desa'])) ?></td>
<td>Propinsi</td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_propinsi'])) ?></td>
</tr>
<tr>
<td>Kecamatan</td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_kecamatan'])) ?></td>
<td>Jumlah Anggota Keluarga</td>
<td>: <?php echo count($main)?></td>
</tr>
</table>
<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>
<table class="list" style="width:100%">
<thead>
<tr>
<th>No</th>
<th align="left" width='180'>Nama</th>
<th align="left">NIK</th>
<th align="left" width='100'>Jenis Kelamin</th>
<th align="left" width='100'>Tempat Lahir</th>
<th align="left" width='80'>Tanggal Lahir</th>
<th align="left" width='100'>Agama</th>
<th align="left" width='100'>Pendidikan</th>
<th align="left" width='100'>Pekerjaan</th>
</tr>
</thead>
<tbody>
<?php foreach($main as $data): ?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td><?php echo strtoupper(unpenetration($data['nama']))?></td>
<td><?php echo $data['nik']?></td>
<td><?php echo $data['sex']?></td> 
<td><?php echo $data['tempatlahir']?></td> 
<td><?php echo $data['tanggallahir']?></td> 
<td><?php echo $data['agama']?></td> 
<td><?php echo $data['pendidikan']?></td> 
<td><?php echo $data['pekerjaan']?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<table class="list" style="width:100%">
<thead>
<tr>
<th>No</th>
<th align="left" width='100'>Status Perkawinan</th>
<th align="left" width='130'>Status Hubungan dalam Keluarga</th>
<th align="left" width='100'>Kewarganegaraan</th>
<th align="left" width='100'>No. Paspor</th>
<th align="left" width='100'>No. KITAS / KITAP</th>
<th align="left" width='100'>Nama Ayah</th>
<th align="left" width='100'>Nama Ibu</th>
<th align="left" width='100'>Golongan darah</th>
</tr>
</thead>
<tbody>
<?php foreach($main as $data): ?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td><?php echo $data['status_kawin']?></td>
<td><?php echo $data['hubungan']?></td>
<td><?php echo $data['warganegara']?></td> 
<td><?php echo $data['dokumen_pasport']?></td>
<td><?php echo $data['dokumen_kitas']?></td> 
<td><?php echo strtoupper($data['nama_ayah'])?></td> 
<td><?php echo strtoupper($data['nama_ibu'])?></td> 
<td><?php echo $data['golongan_darah']?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<table width="100%" cellpadding="3" cellspacing="4">
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr>
<td width="100"></td>
<td width="400"></td>
<td align="center" width="150"><?php echo unpenetration($desa['nama_desa']) ?>, <?php echo tgl_indo(date("Y m d"))?></td>
</tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>
<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>
</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<a href="<?php echo site_url("keluarga/cetak_kk/$id_kk")?>" target="_blank" class="uibutton special">Cetak</a>
<a href="<?php echo site_url("keluarga/doc_kk/$id_kk")?>" target="_blank" class="uibutton confirm">Export</a>
</div>
</div>
</div> 
</form> 
</div>
</td></tr>
</table>
</div>


<!--<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
 {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];
nik.total = nik.results.length;
$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: 'Ketik nama / nik di sini..',
width: 260,
noResultsText :'Tidak ada nama / nik yang sesuai..',
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
	<td style="background:#fff;"> 
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Formulir Layanan : Surat Keterangan Belum Masuk Database</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="15%"><B><U>NIK / Nama PELAPOR</B></U></th></td>
							<td><div id="nik" name="nik"></div></td>
						</tr>
					</table>
					</form>
				</div>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php 
						if($individu){ 
							?>
							<tr>
								<th width="15%">Tempat Tanggal Lahir (Umur)</th>
								<td><?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)</td>
							</tr>
							<tr>
								<th width="15%">Alamat</th>
								<td><?php echo unpenetration($individu['alamat']); ?></td>
							</tr>
							<tr>
								<th width="15%">Pendidikan</th>
								<td><?php echo $individu['pendidikan']; ?></td>
							</tr>
							<tr>
								<th width="15%">Warganegara / Agama</th>
								<td><?php echo $individu['warganegara']?> / <?php echo $individu['agama']?></td>
							</tr>
							<tr>
								<th></th>
							</tr>
						<?php }	?>
					</table>
					
							<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>
					<table class="list" style="width:100%">
					<thead>
						<tr>
							<th align="center" width="5%">No</th>
							<th align="center" width="25%">Nama</th>
							<th align="center" width="15%">NIK</th>
							<th align="center" width="10%">Jenis Kelamin</th>
							<th align="center" width="15%">Tempat Lahir</th>
							<th align="center" width="10%">Tanggal Lahir</th>
							<th align="center" width="20%">Status Hubungan dalam Keluarga</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($main as $data): ?>
						<tr>
							<td align="center" width="5%"><?php echo $data['no']?></td>
							<td width="25%"><?php echo strtoupper(unpenetration($data['nama']))?></td>
							<td width="15%"><?php echo $data['nik']?></td>
							<td width="10%"><?php echo $data['sex']?></td> 
							<td width="15%"><?php echo $data['tempatlahir']?></td> 
							<td width="10%"><?php echo $data['tanggallahir']?></td> 
							<td width="20%"><?php echo $data['hubungan']?></td> 
						</tr>
							<?php endforeach; ?>
					</tbody>
					</table>
						
						<tr><th><B><U>DATA PELAPOR</B></U></th></tr>
						<tr><th><B><U>ANGGOTA KELELUARGA 1</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru1" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru1" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru1" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru1" type="text" class="inputbox required" size="40"/></td></tr>
						
						<tr><th><B><U>ANGGOTA KELELUARGA 2</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru2" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru2" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru2" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru2" type="text" class="inputbox required" size="40"/></td></tr>
						
						<tr><th><B><U>ANGGOTA KELELUARGA 3</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru3" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru3" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru3" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru3" type="text" class="inputbox required" size="40"/></td></tr>
						
						<tr><th><B><U>ANGGOTA KELELUARGA 4</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru4" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru4" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru4" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru4" type="text" class="inputbox required" size="40"/></td></tr>
						
						<tr><th><B><U>ANGGOTA KELELUARGA 5</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru5" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru5" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru5" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru5" type="text" class="inputbox required" size="40"/></td></tr>
						
						<tr><th><B><U>ANGGOTA KELELUARGA 6</B></U></th></tr>
						<tr><th>Nama</th><td><input name="nama_baru6" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Tempat/Tgl. Lahir</th><td><input name="tpt_baru6" type="text" class="inputbox required" size="40"/>, <input name="tgl_baru6" type="text" class="inputbox required datepicker" size="15"/></td></tr>
						<tr><th>Hubungan Keluarga</th><td><input name="hubkel_baru6" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th></th></tr>
						<tr><th>Nama Saksi I</th><td><input name="saksi_baru1" type="text" class="inputbox required" size="40"/></td></tr>
						<tr><th>Nama Saksi II</th><td><input name="saksi_baru2" type="text" class="inputbox required" size="40"/></td></tr>
						<tr>
						
						<table class="form">
						<tr>
							<th>Nomor Surat</th>
							<td><input name="nomor" type="text" class="inputbox " size="20"/></td>
						</tr>
							<th>Staf Pemerintah Desa</th>
							<td><select name="pamong"  class="inputbox required">
								<option value="">Pilih Staf Pemerintah Desa</option>
								<?php foreach($pamong AS $data){?>
									<option value="<?php echo $data['pamong_nama']?>"><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
								<?php }?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Sebagai</th>
							<td><select name="jabatan"  class="inputbox required">
								<option value="">Pilih Jabatan</option>
								<?php foreach($pamong AS $data){?>
									<option ><?php echo $data['jabatan']?></option>
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
							
							<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
						</div>
					</div>
				</div>
				</form>
	</td>
	</tr>
</table>
</div>-->