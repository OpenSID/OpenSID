<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

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

	function submit_form_doc(){
		$('#'+'validasi').attr('action','<?php echo $form_action2?>');
		$('#'+'validasi').submit();
	}

	function change_all(total){
		for (var i=1; i < total+1; i++) {
			$('#check'+i).change();
		}
	}

	function pilih_anggota(pilih, no_anggota){
		var kolom = [
			'input[name=kartu'+no_anggota+']',
			'input[name=nama'+no_anggota+']',
			'input[name=nik'+no_anggota+']',
			'input[name=alamat'+no_anggota+']',
			'input[name=tanggallahir'+no_anggota+']',
			'input[name=faskes'+no_anggota+']'
		];
		if (pilih.is(':checked')) {
			$('input[name=nomor'+no_anggota+']').removeAttr("disabled");
			for (var i=0; i < kolom.length; i++) {
				$(kolom[i]).removeClass("non-aktif");
				$(kolom[i]).removeAttr("disabled");
				$(kolom[i]).attr("style","background-color: white;");
			}
		} else {
			$('input[name=nomor'+no_anggota+']').attr("disabled",'disabled');
			for (var i=0; i < kolom.length; i++) {
				$(kolom[i]).val('');
				$(kolom[i]).attr("disabled",'disabled');
				$(kolom[i]).addClass("non-aktif");
				$(kolom[i]).attr("style","background-color: lightgrey;");
			}
		}
	}

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

	/* Di Chrome, paksa style untuk kolom isian autocomplete
		 https://css-tricks.com/snippets/css/change-autocomplete-styles-webkit-browsers/
	*/
	input:-webkit-autofill,
	input:-webkit-autofill:hover,
	input:-webkit-autofill:focus
	input:-webkit-autofill,
	textarea:-webkit-autofill,
	textarea:-webkit-autofill:hover
	textarea:-webkit-autofill:focus,
	select:-webkit-autofill,
	select:-webkit-autofill:hover,
	select:-webkit-autofill:focus {
	  -webkit-box-shadow: 0 0 0px 1000px white inset;
	}
	/* Style untuk kolom isian autocomplete Chrome yang non-aktif */
	input.non-aktif:-webkit-autofill,
	input.non-aktif:-webkit-autofill,
	input.non-aktif:-webkit-autofill:hover,
	input.non-aktif:-webkit-autofill:focus
	input.non-aktif:-webkit-autofill{
	  -webkit-box-shadow: 0 0 0px 1000px lightgrey inset;
	}

</style>
<div id="pageC">
	<table class="inner">
		<tr style="vertical-align:top">
			<td style="background:#ebe9d0;">
				<div id="contentpane">
					<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
						<h3>Formulir Layanan : Surat Keterangan Beda Identitas KIS</h3>
						<div id="form-cari-pemohon">
							<form action="" id="main" name="main" method="POST" class="formular">
								<table class="form">
									<tr>
										<td width="150">NIK / Nama Kepala Keluarga</td>
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
									<?php if($individu){
										include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
									} ?>
									<tr>
										<th>DATA KELUARGA / KK </th>
										<td></td>
									</tr>
									<tr>
										<th colspan="1">Keluarga</th>
										<td colspan="1">
											<div style="margin-left:0px;">
												<table class="list">
													<thead>
														<tr>
															<th class="nostretch">No</th>
															<th><input type="checkbox" class="checkall" onchange="change_all(<?php echo count($anggota);?>);"/></th>
															<th align="left">NIK</th>
															<th align="left">Nama</th>
															<th align="left">Jenis Kelamin</th>
															<th align="left">Tempat Tanggal Lahir</th>
															<th align="center" >Hubungan</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach($anggota AS $key => $data){ ?>
															<tr>
																<td align="center" width="2"><?php echo $key+1?></td>
																<td align="center" width="5">
																	<input id="check<?php echo $key+1?>" type="checkbox" name="id_cb[]" value="'<?php echo $data['nik']?>'" onchange="pilih_anggota($(this), <?php echo $key+1?>);" />
																</td>
																<td><?php echo $data['nik']?></td>
																<td><?php echo unpenetration($data['nama'])?></td>
																<td><?php echo $data['sex']?></td>
																<td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?></td>
																<td><?php echo $data['hubungan']?></td>
															</tr>
														<?php }?>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
									<tr>
										<th>DATA KELUARGA DI KARTU KIS </th>
										<td></td>
									</tr>
									<tr>
										<th colspan="1">Keluarga</th>
										<th colspan="1">
											<table class="list">
											<thead>
												<tr>
													<th align="center">No</th>
													<th align="center" width='95'>No. Kartu</th>
													<th align="center" width='148'>Nama di Kartu</th>
													<th align="center" width='90'>NIK</th>
													<th align="center" width='160'>Alamat di Kartu</th>
													<th align="center" width='55'>Tanggal Lahir</th>
													<th align="center" width='75'>Faskes Tingkat I</th>
												</tr>
											</thead>
											<tbody>
												<?php for($i=1; $i<MAX_ANGGOTA+1; $i++): ?>
													<tr>
														<td style="text-align: center; vertical-align: middle;"> <input name="nomor<?php echo $i?>" type="text" class="inputbox" size="2" value="<?php echo $i?>" readonly disabled="disabled"/></td>
														<td> <input name="kartu<?php echo $i?>" type="text" class="inputbox " size="20" disabled="disabled" style="background-color: lightgrey;"/></td>
														<td> <input name="nama<?php echo $i?>" type="text" class="inputbox " size="33" disabled="disabled" style="background-color: lightgrey;"/></td>
														<td> <input name="nik<?php echo $i?>" type="text" class="inputbox " size="18" disabled="disabled" style="background-color: lightgrey;"/></td>
														<td> <input name="alamat<?php echo $i?>" type="text" class="inputbox " size="36" disabled="disabled" style="background-color: lightgrey;"/></td>
														<td>
																<input name="tanggallahir<?php echo $i?>" type="text" class="inputbox datepicker" size="20" disabled="disabled" style="background-color: lightgrey;"/>
														</td>
														<td> <input name="faskes<?php echo $i?>" type="text" class="inputbox " size="15" disabled="disabled" style="background-color: lightgrey;"/></td>
													</tr>
												<?php endfor; ?>
											</tbody>
											</table>
										</th>
									</tr>

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

									<!-- PERANGKAT DESA -->
									<?php include("donjo-app/views/surat/form/_pamong.php"); ?>

								</table>
							</form>
						</div>
					</div>
					<div class="ui-layout-south panel bottom">
						<div class="left">
							<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
						</div>
				    <div class="right">
			        <div class="uibutton-group">
		            <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
								<?php if (SuratCetak($url)) { ?>
									<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="fa fa-print">&nbsp;</span>Cetak</button>
								<?php } ?>
								<?php if (SuratExport($url)) { ?>
									<button type="button" onclick="submit_form_doc();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button>
								<?php } ?>
			        </div>
				    </div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>