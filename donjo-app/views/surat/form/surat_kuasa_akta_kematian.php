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
			onSelect: function(){$('#'+'main').submit();}  
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
				<h3>Formulir Layanan : Surat Kuasa AKTA Kematian</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="200">NIK / Nama</td>
							<td>
								<div id="nik" name="nik"></div>
							</td>
						</tr>
					</table>
					</form>
				</div>
				</br>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php 
						if($individu){ 
							?>
							<tr>
								<th width="200">Tempat Tanggal Lahir (Umur)</th>
								<td>
									<?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
								</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td><?php echo unpenetration($individu['alamat']); ?></td>
							</tr>
							<tr>
								<th>Pendidikan</th>
								<td><?php echo $individu['pendidikan']; ?></td>
							</tr>
							<tr>
								<th>Warganegara / Agama</th>
								<td><?php echo $individu['warganegara']?> / <?php echo $individu['agama']?></td>
							</tr>
							<tr>
								<th>Dokumen Kelengkapan / Syarat</th>
								<td>
									<a header="Dokumen" target="ajax-modal" rel="dokumen" href="<?php echo site_url("penduduk/dokumen_list/$individu[id]")?>" class="uibutton special">Daftar Dokumen</a><a target="_blank" href="<?php echo site_url("penduduk/dokumen/$individu[id]")?>" class="uibutton confirm">Manajemen Dokumen</a> )* Atas Nama <?php echo $individu['nama']?> [<?php echo $individu['nik']?>]</td>
							</tr>
						<?php 
						}
						?>
						<tr>
							<th><b><u>YANG MENERIMA KUASA</u></b></th>
						</tr>
						<tr>
							<th width="200">Nama</th>
							<td ><input name="nama_wali" type="text" class="inputbox " size="60"/></td>
						</tr>
						<tr>
							<th>NIK</th>
							<td><input name="nik_wali" type="text" class="inputbox " size="20"/></td>
						</tr>
						<tr>
							<th>Tempat/Tanggal Lahir</th>
							<td><input name="tptlhr_wali" type="text" class="inputbox " size="30"/>/<input name="tgllhr_wali" type="text" class="inputbox  datepicker" size="20"/></td>
						</tr>
						<tr>
							<th>Jenis Kelamin</th>
							<td><input name="kelamin_wali" type="text" class="inputbox " size="10"/></td>
						</tr>
						<tr>
							<th>Agama</th>
							<td><input name="agama_wali" type="text" class="inputbox " size="15"/></td>
						</tr>
						<tr>
							<th>Pekerjaan</th>
							<td><input name="pekerjaan_wali" type="text" class="inputbox " size="40"/></td>
						</tr>
						<tr>
							<th>Alamat</th>
							<td><input name="alamat_wali" type="text" class="inputbox " size="70"/></td>
						</tr>
						<tr>
							<th><b><u>DATA ALMARHUM/AH</u></b></th>
						</tr>
						<tr>
							<th>Nama Almarhum/ah</th><td><input name="nama_mati" type="text" class="inputbox " size="60"/></td>
						</tr>
						<tr>
							<th>NIK Almarhum/ah</th><td><input name="nik_mati" type="text" class="inputbox " size="20"/></td>
						</tr>
						<tr>
							<th>&nbsp;</th>
						</tr>
						<tr>
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
			</div>
				<div class="ui-layout-south panel bottom">
					<div class="left">
						<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
					</div>
					<div class="right">
						<div class="uibutton-group">
							<button class="uibutton" type="reset">Clear</button>
							<!--<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>-->
							
							<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
						</div>
					</div>
				</div>
			</form>
			</td>
		</tr>
	</table>
</div>