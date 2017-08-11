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

		$('#showData').click(function(){
			$('tr.hide').show();
			$('#showData').hide();
			$('#hideData').show();
		});

		$('#hideData').click(function(){
			$('tr.hide').hide();
			$('#hideData').hide();
			$('#showData').show();
		});

		$('#hideData').hide();
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
	tr .hide{
		display:none;
	}
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td style="background:#fff;">
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Formulir Layanan : Surat Keterangan Tidak Mampu</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="200">NIK / Nama Pemohon</td>
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
							include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
							?>
							<tr>
								<th>Data Keluarga / KK </th>
								<td>
									<a class='uibutton special' id='showData'>Tampilkan</a>
									<a class='uibutton' id='hideData'>Sembunyikan</a>
								</td>
							</tr>

							<tr class="hide">
								<th colspan="1">Keluarga</th>
								<td colspan="1">
									<div style="margin-left:0px;">
										<table class="list">
											<thead>
												<tr>
													<th>No</th>
													<th align="left" width='70'>NIK</th>
													<th align="left" width='100'>Nama</th>
													<th align="left" width='30' align="center">Jenis Kelamin</th>
													<th align="left" width='30' align="center">Tempat Tanggal Lahir</th>
													<th width="70" align="left" >Hubungan</th>
													<th width="70" align="left" >Status Kawin</th>

												</tr>
											</thead>
											<tbody>
												<?php
												if($anggota!=NULL){
													$i=0;?>
												<?php foreach($anggota AS $data){
													if($data['kk_level'] == 1) continue;
													$i++;?>
													<tr>
														<td align="center" width="2"><?php echo $i?></td>
														<td><?php echo $data['nik']?></td>
														<td><?php echo unpenetration($data['nama'])?></td>
														<td><?php echo $data['sex']?></td>
														<td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?></td>
														<td><?php echo $data['hubungan']?></td>
														<td><?php echo $data['status_kawin']?></td>

													</tr>
												<?php }?>
												<?php }?>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						<?php }?>
	<tr>
		<th width="200">Nomor Surat</th>
		<td>
			<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
		</td>
	</tr>
	<tr>
		<th>Surat Keterangan ini dibuat untuk keperluan</th>
		<td>
			<input name="keperluan" type="text" class="inputbox required" size="60"/>
		</td>
	</tr>
	<tr>
		<th>Tertanda Atas Nama</th>
		<td>
			<select name="atas_nama"  type="text" class="inputbox">
				<option value="">Atas Nama</option>
				<option value="An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
				<option value="Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
			</select>
		</td>
	</tr>
	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
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

			<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
			<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
		</div>
	</div>
</div>
</form>
</div>
</td></tr></table>
</div>