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

	<td style="background:#fff;">
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Formulir Layanan: Surat Keterangan</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="40%">NIK / Nama</td>
							<td width="60%">
								<div id="nik" name="nik"></div>
							</td>
						</tr>
					</table>
					</form>
				</div>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php if($individu){?>
							<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
						<?php
						}
						?>
						<tr>
							<th width="40%">Nomor Surat</th>
							<td width="60%"><input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span></td>
						</tr>
						<tr>
							<th>Keperluan</th>
							<td><textarea name="keperluan" class=" required" style="resize: none; height:80px; width:300px;" size="500"></textarea></td>
						</tr>
						<tr>
							<th>Keterangan</th>
							<td><input name="keterangan" type="text" class="inputbox" size="40"/></td>
						</tr>
						<tr>
							<th>Berlaku</th>
							<td><input name="berlaku_dari" type="text" class="inputbox required datepicker" size="20"/> sampai <input name="berlaku_sampai" type="text" class="inputbox datepicker " size="20"/></td>
						</tr>
						<tr>
							<th>Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></th>
							<td><select name="pamong"  class="inputbox required">
								<option value="">Pilih Staf Pemerintah <?php echo ucwords(config_item('sebutan_desa'))?></option>
								<?php foreach($pamong AS $data){?>
									<option value="<?php echo $data['pamong_nama']?>"><?php echo $data['pamong_nama']?>(<?php echo $data['jabatan']?>)</option>
								<?php }?>
								</select></td>
						</tr>
						<tr>
							<th>Sebagai</th>
							<td><select name="jabatan"  class="inputbox required">
								<option value="">Pilih Jabatan</option>
								<?php foreach($pamong AS $data){?>
									<option ><?php echo $data['jabatan']?></option>
								<?php }?>
								</select></td>
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
							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
						</div>
					</div>
				</div>
			</form>
			</td>
		</tr>
	</table>
</div>
