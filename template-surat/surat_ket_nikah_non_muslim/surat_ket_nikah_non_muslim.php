<script language="javascript" type="text/javascript">

	$(document).ready(function() {
		$('#reset_form').on('click', function() {
			$('#nomor').val('');
			$('#calon_pria').val('2');
			$('#calon_wanita').val('2');
			$('#calon_saksi1').val('2');
			$('#calon_saksi2').val('2');
			$('#calon_saksi2').val('2');
		});
	});

	function calon_wanita_asal(asal) {
		$('#calon_wanita').val(asal);
		if (asal == 1) {
			$('.wanita_desa').show();
			$('.wanita_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_wanita_2').removeClass('active');
		} else {
			$('.wanita_desa').hide();
			$('.wanita_luar_desa').show();
		 	$('#id_wanita').val('*'); // Hapus $id_wanita
			submit_form_ambil_data();
		}
	}

	function calon_pria_asal(asal) {
		$('#calon_pria').val(asal);
		if (asal == 1) {
			$('.pria_desa').show();
			$('.pria_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_pria_2').removeClass('active');
		} else {
			$('.pria_desa').hide();
			$('.pria_luar_desa').show();
			$('#id_pria').val('*'); // Hapus $id_pria
			submit_form_ambil_data();
		}
	}

	function calon_saksi1_asal(asal) {
		$('#calon_saksi1').val(asal);
		if (asal == 1) {
			$('.saksi1_desa').show();
			$('.saksi1_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_saksi1_2').removeClass('active');
		} else {
			$('.saksi1_desa').hide();
			$('.saksi1_luar_desa').show();
			$('#id_saksi1').val('*'); // Hapus $id_saksi1
			submit_form_ambil_data();
		}
	}


	function calon_saksi2_asal(asal) {
		$('#calon_saksi2').val(asal);
		if (asal == 1) {
			$('.saksi2_desa').show();
			$('.saksi2_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_saksi2_2').removeClass('active');
		} else {
			$('.saksi2_desa').hide();
			$('.saksi2_luar_desa').show();
			$('#id_saksi2').val('*'); // Hapus $id_saksi2
			submit_form_ambil_data();
		}
	}

	function calon_saksi2_asal(asal) {
		$('#calon_saksi2').val(asal);
		if (asal == 1) {
			$('.saksi2_desa').show();
			$('.saksi2_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_saksi2_2').removeClass('active');
		} else {
			$('.saksi2_desa').hide();
			$('.saksi2_luar_desa').show();
			$('#id_saksi2').val('*'); // Hapus $id_saksi2
			submit_form_ambil_data();
		}
	}

	function submit_form_ambil_data() {
		$('input').removeClass('required');
		$('select').removeClass('required');
		$('#'+'validasi').attr('action','')
		$('#'+'validasi').attr('target','')
		$('#'+'validasi').submit();
	}

	function istri_dulu(status) {
		// Untuk calon pria luar desa pilihan 'duda'
		if (status.toUpperCase() == 'duda'.toUpperCase()) {
			$('.istri_dulu').show();
			$('.istri_dulu').attr('disabled', false);
		} else {
			$('.istri_dulu').hide();
			$('.istri_dulu').attr('disabled', true);
		}
	}

	function status_beristri(status) {
		istri_dulu(status);
		if (status.toUpperCase() == 'beristri'.toUpperCase())
		{
			$('#beristri').show();
		} else {
			$('#beristri').hide();
		}
	}

	function cerai_mati(status) {
		// Untuk calon wanita luar desa pilihan hanya 'perawan' atau 'janda'
		if (status.toUpperCase() == 'janda'.toUpperCase()) {
			$('.cerai_mati').show();
			$('.suami_dulu').attr('disabled', false);
		} else {
			$('.cerai_mati').hide();
			$('.suami_dulu').attr('disabled', true);
		}
	}

</script>
<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<input type="hidden" name="sex_pria" value="Laki-laki">
							<input type="hidden" name="sex_wanita" value="Perempuan">
							<div class="col-md-12">
								<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
								<?php $jenis_pasangan = "Istri"; ?>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">A. CALON PASANGAN PRIA</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label for="calon_pria_1" class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($pria)): ?>active<?php endif ?>">
											<input id="calon_pria_1" type="radio" name="calon_pria" class="form-check-input" type="radio" value="1" <?php if (!empty($pria)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_pria_asal(this.value);"> Warga Desa
										</label>
										<label for="calon_pria_2" class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($pria)): ?>active<?php endif; ?>">
											<input id="calon_pria_2" type="radio" name="calon_pria" class="form-check-input" type="radio" value="2" <?php if (empty($pria)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_pria_asal(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group pria_desa" <?php if (empty($pria)): ?>style="display: none;"<?php endif; ?>>
									<label for="pria_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>A.1 DATA CALON PASANGAN PRIA WARGA DESA</strong></label>
								</div>
								<div class="form-group pria_desa" <?php if (empty($pria)): ?>style="display: none;"<?php endif; ?>>
									<input id="calon_pria" name="calon_pria" type="hidden" value=""/>

									<label for="pria_desa" class="col-sm-3 control-label"><strong>NIK / Nama :</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2" id="id_pria" name="id_pria" style ="width:100%;" onchange="submit_form_ambil_data(this.id);">
											<option value="">-- Cari NIK / Nama--</option>
											<?php foreach ($laki as $data): ?>
												<option value="<?= $data['id']?>" <?= selected($pria['nik'], $data['nik']); ?>>NIK :<?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php if ($pria): ?>
									<?php $individu = $pria;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php if (empty($pria)): ?>
									<div class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>A.1 DATA CALON PASANGAN PRIA LUAR DESA</strong></label>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Nama Lengkap / NIK KTP</strong></label>
										<div class="col-sm-5 col-lg-6">
											<input name="nama_pria" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_pria']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="no_ktp_pria" class="form-control input-sm" type="text" placeholder="Nomor KTP" value="<?= $_SESSION['post']['no_ktp_pria']?>">
										</div>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_pria" id="tempatlahir_pria" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_pria']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tanggallahir_pria" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_pria']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_pria" id="wn_pria" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_pria" id="agama_pria" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_pria" id="pekerjaan_pria" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Pendidikan Terakhir</strong></label>
										<div class="col-sm-8">
											<input name="pendidikan_pria" class="form-control input-sm" type="text" placeholder="Pendidikan Terakhir" value="<?= $_SESSION['post']['pendidikan_pria']?>">
										</div>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_pria" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_pria']?>">
										</div>
									</div>
									<div class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Jika pria, terangkan jejaka, duda atau beristri</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm select2 required" name="status_kawin_pria" id="status_kawin_pria" style ="width:100%;" onchange="status_beristri($(this).val())">
												<option value="">-- Pilih Status Kawin --</option>
												<?php foreach ($kode['status_kawin_pria'] as $data): ?>
													<option value="<?= $data?>" <?= selected($data['nama'], $_SESSION['post']['status_kawin_pria']); ?>><?= ucwords($data)?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div id="" class="form-group pria_luar_desa">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Jika beristri, istri ke-</strong></label>
										<div class="col-sm-4">
											<input name="istri_ke" class="form-control input-sm" type="number" min="1" max="10" placeholder="Istri Keberapa" value="<?= $_SESSION['post']['istri_ke'] ?? 1; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-3 control-label"><strong>Anak ke-</strong></label>
										<div class="col-sm-4">
											<input name="anak_ke_pria" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Anak Keberapa" value="<?= $_SESSION['post']['anak_ke_pria'] ?? 1; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-3 control-label"><strong>Perkawinan ke-</strong></label>
										<div class="col-sm-4">
											<input name="kawin_ke_pria" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Perkawinan Keberapa" value="<?= $_SESSION['post']['kawin_ke_pria'] ?? 1; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Paspor</strong></label>
										<div class="col-sm-4">
											<input name="paspor_pria" class="form-control input-sm" type="number"  placeholder="Paspor" value="<?= $_SESSION['post']['paspor_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_pria" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_pria" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Kebangsaan (Bagi WNA)</strong></label>
										<div class="col-sm-4">
											<input name="bangsa_pria" class="form-control input-sm" type="text"  placeholder="Kebangsaan" value="<?= $_SESSION['post']['bangsa_pria']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($pria): ?>
									<div class="form-group">
										<label for="status_kawin_pria" class="col-sm-3 control-label"><strong>Jika pria, terangkan jejaka, duda atau beristri</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm select2" disabled style ="width:100%;">
												<option value="">-- Pilih Status Kawin --</option>
												<?php foreach ($kode['status_kawin_pria'] as $data): ?>
													<option value="<?= $data?>" <?= selected($data, $pria['status_kawin_pria']); ?>><?= ucwords($data)?></option>
												<?php endforeach;?>
											</select>
										</div>
										<p class="help-block">(Status kawin: <?= $pria['status_kawin']?>)</p>
										<input type="hidden" name="status_kawin_pria" id="status_kawin_pria" value="<?= $pria['status_kawin_pria']?>">
									</div>
									<?php if ($pria['status_kawin']=="KAWIN"): ?>
										<div class="form-group">
											<label for="istri_ke" class="col-sm-3 control-label"><strong>Jika beristri, istri ke-</strong></label>
											<div class="col-sm-4">
												<input name="istri_ke" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Istri Keberapa" value="<?= $_SESSION['post']['istri_ke'] ?? 1; ?>">
											</div>
										</div>
									<?php endif; ?>

									<div class="form-group">
										<label for="" class="col-sm-3 control-label"><strong>Anak ke-</strong></label>
										<div class="col-sm-4">
											<input name="anak_ke_pria" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Anak Keberapa" value="<?= $_SESSION['post']['anak_ke_pria'] ?? 1; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="" class="col-sm-3 control-label"><strong>Perkawinan ke-</strong></label>
										<div class="col-sm-4">
											<input name="kawin_ke_pria" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Perkawinan Keberapa" value="<?= $_SESSION['post']['kawin_ke_pria'] ?? 1; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Paspor</strong></label>
										<div class="col-sm-4">
											<input name="paspor_pria" class="form-control input-sm" type="number"  placeholder="Paspor" value="<?= $_SESSION['post']['paspor_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_pria" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_pria" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_pria']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Kebangsaan (Bagi WNA)</strong></label>
										<div class="col-sm-4">
											<input name="bangsa_pria" class="form-control input-sm" type="text"  placeholder="Kebangsaan" value="<?= $_SESSION['post']['bangsa_pria']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($ayah_pria['id']): ?>
									<div class="form-group">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="padding-top:10px;padding-bottom:10px"><strong>A.2 DATA AYAH PASANGAN PRIA</strong></label>
									</div>
									<div class="form-group">
										<label for="pria_luar_desa" class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-5">
											<input class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $ayah_pria['nama']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Lahir" value="<?= $ayah_pria['tempatlahir']." / ".tgl_indo_out($ayah_pria['tanggallahir'])?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" placeholder="Warganegara" value="<?= $ayah_pria['wn']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Agama" value="<?= $ayah_pria['agama']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Pekerjaan" value="<?= $ayah_pria['pek']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $ayah_pria['alamat_wilayah']?>">
										</div>
									</div>
								<?php else: ?>
									<div class="form-group ayah_pria">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>A.2 DATA AYAH PASANGAN PRIA (Isi jika ayah bukan warga <?= strtolower($this->setting->sebutan_desa)?> ini)</strong></label>
									</div>
									<div class="form-group ayah_pria">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ayah_pria" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ayah_pria'] ?: $ayah_pria['nama']?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="bin_ayah_pria" class="form-control input-sm" type="text" placeholder="Bin Ayah Pria" value="<?= $_SESSION['post']['bin_ayah_pria']?>"v>
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ayah_pria" class="form-control input-sm" type="text" placeholder="Nomor KTP Ayah Pria" value="<?= $_SESSION['post']['noktp_ayah_pria'] ?: $ayah_pria['nik']?>">
										</div>
									</div>
									<div class="form-group ayah_pria">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ayah_pria" id="tempatlahir_ayah_pria" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ayah_pria']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ayah_pria" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ayah_pria']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ayah_pria">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ayah_pria" id="wn_ayah_pria" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ayah_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ayah_pria" id="agama_ayah_pria" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ayah_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ayah_pria" id="pekerjaan_ayah_pria" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ayah_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ayah_pria">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ayah_pria" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ayah_pria']?>">
										</div>
									</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_ayah_pria" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ayah_pria']; ?>">
									</div>
								</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_ayah_pria" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ayah_pria']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($ibu_pria['id']): ?>
									<div class="form-group">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="padding-top:10px;padding-bottom:10px"><strong>A.3 DATA IBU PASANGAN PRIA</strong></label>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $ibu_pria['nama']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Lahir" value="<?= $ibu_pria['tempatlahir']." / ".tgl_indo_out($ibu_pria['tanggallahir'])?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" placeholder="Warganegara" value="<?= $ibu_pria['wn']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Agama" value="<?= $ibu_pria['agama']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Pekerjaan" value="<?= $ibu_pria['pek']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $ibu_pria['alamat_wilayah']?>">
										</div>
									</div>
								<?php else: ?>
									<div class="form-group ibu_pria">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>A.3 DATA IBU PASANGAN PRIA (Isi jika ibu bukan warga <?= strtolower($this->setting->sebutan_desa)?> ini)</strong></label>
									</div>
									<div class="form-group ibu_pria">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ibu_pria" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ibu_pria'] ?: $ibu_pria['nama']?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="binti_ibu_pria" class="form-control input-sm" type="text" placeholder="Binti Ibu Pria" value="<?= $_SESSION['post']['binti_ibu_pria']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ibu_pria" class="form-control input-sm" type="text" placeholder="Nomor KTP Ibu Pria" value="<?= $_SESSION['post']['noktp_ibu_pria'] ?: $ibu_pria['nik']?>">
										</div>
									</div>
									<div class="form-group ibu_pria">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ibu_pria" id="tempatlahir_ibu_pria" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ibu_pria']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ibu_pria" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ibu_pria']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ibu_pria">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ibu_pria" id="wn_ibu_pria" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ibu_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ibu_pria" id="agama_ibu_pria" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ibu_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ibu_pria" id="pekerjaan_ibu_pria" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ibu_pria']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ibu_pria">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ibu_pria" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ibu_pria']?>">
										</div>
									</div>
								<?php endif; ?>

								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_ibu_pria" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ibu_pria']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
									<div class="col-sm-4">
										<input name="penghayat_ibu_pria" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ibu_pria']; ?>">
									</div>
								</div>
								<?php if (empty($pria) OR $pria['status_kawin']=="CERAI MATI"): ?>
									<div class="form-group istri_dulu">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>A.4 DATA ISTRI TERDAHULU </strong></label>
									</div>
									<div class="form-group istri_dulu">
										<label class="col-sm-3 control-label"><strong>Nama <?= ucwords($jenis_pasangan)?> Terdahulu / Binti</strong></label>
										<div class="col-sm-3">
											<input name="istri_dulu" class="form-control input-sm istri_dulu" type="text" placeholder="Nama Istri Terdahulu" value="<?= $_SESSION['post']['istri_dulu']?>">
										</div>
										<div class="col-sm-3">
											<input name="binti" class="form-control input-sm istri_dulu" type="text" placeholder="Binti" value="<?= $_SESSION['post']['binti']?>">
										</div>
										<div class="col-sm-2">
											<input name="noktp_istri_dulu" class="form-control input-sm istri_dulu" type="text" placeholder="No KTP Istri Dulu" value="<?= $_SESSION['post']['noktp_istri_dulu']?>">
										</div>
									</div>
									<div class="form-group istri_dulu">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm istri_dulu" type="text" name="tempatlahir_istri_dulu" id="tempatlahir_istri_dulu" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_istri_dulu']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker istri_dulu" name="tanggallahir_istri_dulu" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_istri_dulu']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group istri_dulu">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2 istri_dulu" name="wn_istri_dulu" id="wn_istri_dulu" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_istri_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2 istri_dulu" name="agama_istri_dulu" id="agama_istri_dulu" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_istri_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2 istri_dulu" name="pek_istri_dulu" id="pek_istri_dulu" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pek_istri_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group istri_dulu">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_istri_dulu" class="form-control input-sm istri_dulu" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_istri_dulu']?>">
										</div>
									</div>
									<div class="form-group istri_dulu">
										<label class="col-sm-3 control-label">Meninggal Dunia Pada Tanggal / Tempat</label>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker istri_dulu" name="tanggalmeninggal_istri_dulu" type="text" placeholder="Tgl. Meninggal" value="<?= $_SESSION['post']['tanggalmeninggal_istri_dulu']?>"/>
											</div>
										</div>
										<div class="col-sm-6">
											<input name="tempatmeninggal_istri_dulu" class="form-control input-sm istri_dulu" type="text" placeholder="Tempat Meninggal" value="<?= $_SESSION['post']['tempatmeninggal_istri_dulu']?>">
										</div>
									</div>
								<?php endif; ?>
								<!-- CALON PASANGAN WANITA -->
								<?php $jenis_pasangan = "Suami"; ?>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">B. CALON PASANGAN WANITA</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label for="calon_wanita_1" class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($wanita)): ?>active<?php endif ?>">
											<input id="calon_wanita_1" type="radio" name="calon_wanita" class="form-check-input" type="radio" value="1" <?php if (!empty($wanita)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_wanita_asal(this.value);"> Warga Desa
										</label>
										<label id="label_calon_wanita_2" for="calon_wanita_2" class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($wanita)): ?>active<?php endif; ?>">
											<input id="calon_wanita_2" type="radio" name="calon_wanita" class="form-check-input" type="radio" value="2" <?php if (empty($wanita)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_wanita_asal(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group wanita_desa" <?php if (empty($wanita)): ?>style="display: none;"<?php endif; ?>>
									<label for="wanita_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.1 DATA CALON PASANGAN WANITA WARGA DESA</strong></label>
								</div>
								<div class="form-group wanita_desa" <?php if (empty($wanita)): ?>style="display: none;"<?php endif; ?>>
									<label for="id_wanita" class="col-sm-3 control-label"><strong>NIK / Nama :</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2" id="id_wanita" name="id_wanita" style ="width:100%;" onchange="submit_form_ambil_data(this.id);">
											<option value="">-- Cari NIK / Nama--</option>
											<?php foreach ($perempuan as $data): ?>
												<option value="<?= $data['id']?>" <?= selected($wanita['nik'], $data['nik']); ?>>NIK : <?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php if ($wanita): ?>
									<?php if ($wanita): //bagian info setelah terpilih
										$individu = $wanita;
										include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
									endif; ?>
									<div class="form-group">
										<label for="status_kawin_wanita" class="col-sm-3 control-label"><strong>Jika wanita, terangkan perawan atau janda</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm select2 required" style ="width:100%;" disabled>
												<option value="">-- Pilih Status Kawin --</option>
												<?php foreach ($kode['status_kawin_wanita'] as $data): ?>
													<option value="<?= $data; ?>" <?= selected($data, $wanita['status_kawin_wanita']); ?>><?= ucwords($data); ?></option>
												<?php endforeach;?>
											</select>
										</div>
										<p class="help-block">(Status kawin: <?= $wanita['status_kawin']?>)</p>
										<input type="hidden" name="status_kawin_wanita" id="status_kawin_wanita" value="<?= $wanita['status_kawin_wanita']?>">
									</div>
								<?php endif; ?>
								<?php if (empty($wanita)): ?>
									<div class="form-group wanita_luar_desa">
										<label for="wanita_luar_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.1 DATA CALON PASANGAN WANITA LUAR DESA</strong></label>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="wanita_luar_desa" class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-6">
											<input name="nama_wanita" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="no_ktp_wanita" class="form-control input-sm" type="text" placeholder="Nomor KTP Wanita" value="<?= $_SESSION['post']['no_ktp_wanita']?>">
										</div>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="tempatlahir_wanita" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_wanita" id="tempatlahir_wanita" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_wanita" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_wanita']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="tempatlahir_wanita" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_wanita" id="wn_wanita" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_wanita" id="agama_wanita" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_wanita" id="pekerjaan_wanita" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="wanita_luar_desa" class="col-sm-3 control-label"><strong>Pendidikan Terakhir</strong></label>
										<div class="col-sm-8">
											<input name="pendidikan_wanita" class="form-control input-sm" type="text" placeholder="Pendidikan Terakhir" value="<?= $_SESSION['post']['pendidikan_wanita']?>">
										</div>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="wanita_luar_desa" class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_wanita" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_wanita']?>">
										</div>
									</div>
									<div class="form-group wanita_luar_desa">
										<label for="wanita_luar_desa" class="col-sm-3 control-label"><strong>Jika wanita, terangkan perawan atau janda</strong></label>
										<div class="col-sm-4">
											<select class="form-control input-sm select2" name="status_kawin_wanita" id="status_kawin_wanita" onchange="cerai_mati($(this).val())" style ="width:100%;">
												<option value="">-- Pilih Status Kawin --</option>
												<?php foreach ($kode['status_kawin_wanita'] as $data): ?>
													<option value="<?= $data; ?>" <?= selected($data, $_SESSION['post']['status_kawin_wanita']); ?>><?= ucwords($data); ?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label"><strong>Anak ke-</strong></label>
									<div class="col-sm-4">
										<input name="anak_ke_wanita" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Anak Keberapa" value="<?= $_SESSION['post']['anak_ke_wanita'] ?? 1; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-3 control-label"><strong>Perkawinan ke-</strong></label>
									<div class="col-sm-4">
										<input name="kawin_ke_wanita" class="form-control input-sm required" type="number" min="1" max="10" placeholder="Perkawinan Keberapa" value="<?= $_SESSION['post']['kawin_ke_wanita'] ?? 1; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Paspor</strong></label>
									<div class="col-sm-4">
										<input name="paspor_wanita" class="form-control input-sm" type="number"  placeholder="Paspor" value="<?= $_SESSION['post']['paspor_wanita']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_wanita" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_wanita']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
									<div class="col-sm-4">
										<input name="penghayat_wanita" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_wanita']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Kebangsaan (Bagi WNA)</strong></label>
									<div class="col-sm-4">
										<input name="bangsa_wanita" class="form-control input-sm" type="text"  placeholder="Kebangsaan" value="<?= $_SESSION['post']['bangsa_wanita']; ?>">
									</div>
								</div>
								<?php if ($ayah_wanita['id']): ?>
									<div class="form-group">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.2 DATA AYAH PASANGAN WANITA</strong></label>
									</div>
									<!-- <div class="form-group">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $ayah_wanita['nama']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Lahir" value="<?= $ayah_wanita['tempatlahir']." / ".tgl_indo_out($ayah_wanita['tanggallahir'])?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" placeholder="Warganegara" value="<?= $ayah_wanita['wn']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Agama" value="<?= $ayah_wanita['agama']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Pekerjaan" value="<?= $ayah_wanita['pek']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $ayah_wanita['alamat_wilayah']?>">
										</div>
									</div> -->
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ayah_wanita" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ayah_wanita'] ?: $ayah_wanita['nama']; ?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="bin_ayah_wanita" class="form-control input-sm" type="text" placeholder="Bin Ayah Wanita" value="<?= $_SESSION['post']['bin_ayah_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ayah_wanita" class="form-control input-sm" type="text" placeholder="Nomor KTP Ayah Wanita" value="<?= $_SESSION['post']['noktp_ayah_wanita'] ?: $ayah_wanita['nik'];?>">
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ayah_wanita" id="tempatlahir_ayah_wanita" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ayah_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ayah_wanita" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ayah_wanita']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ayah_wanita" id="wn_ayah_wanita" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ayah_wanita" id="agama_ayah_wanita" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ayah_wanita" id="pekerjaan_ayah_wanita" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ayah_wanita" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ayah_wanita']?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_ayah_wanita" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ayah_wanita']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_ayah_wanita" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ayah_wanita']; ?>">
										</div>
									</div>
								<?php else: ?>
									<div class="form-group ayah_wanita">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.2 DATA AYAH PASANGAN WANITA (Isi jika ayah bukan warga <?= strtolower($this->setting->sebutan_desa)?> ini)</strong></label>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ayah_wanita" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ayah_wanita'] ?: $ayah_wanita['nama']; ?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="bin_ayah_wanita" class="form-control input-sm" type="text" placeholder="Bin Ayah Wanita" value="<?= $_SESSION['post']['bin_ayah_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ayah_wanita" class="form-control input-sm" type="text" placeholder="Nomor KTP Ayah Wanita" value="<?= $_SESSION['post']['noktp_ayah_wanita'] ?: $ayah_wanita['nik'];?>">
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ayah_wanita" id="tempatlahir_ayah_wanita" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ayah_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ayah_wanita" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ayah_wanita']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ayah_wanita" id="wn_ayah_wanita" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ayah_wanita" id="agama_ayah_wanita" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ayah_wanita" id="pekerjaan_ayah_wanita" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ayah_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ayah_wanita">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ayah_wanita" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ayah_wanita']?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_ayah_wanita" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ayah_wanita']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_ayah_wanita" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ayah_wanita']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($ibu_wanita['id']): ?>
									<div class="form-group">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="padding-top:10px;padding-bottom:10px"><strong>B.3 DATA IBU PASANGAN WANITA</strong></label>
									</div>
									<!-- <div class="form-group">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $ibu_wanita['nama']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Lahir" value="<?= $ibu_wanita['tempatlahir']." / ".tgl_indo_out($ibu_pria['tanggallahir'])?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label for="tempatlahir_pria" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<input class="form-control input-sm" type="text" placeholder="Warganegara" value="<?= $ibu_wanita['wn']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Agama" value="<?= $ibu_wanita['agama']?>" disabled>
										</div>
										<div class="col-sm-3">
											<input class="form-control input-sm" type="text" placeholder="Pekerjaan" value="<?= $ibu_wanita['pek']?>" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $ibu_wanita['alamat_wilayah']?>">
										</div>
									</div> -->
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ibu_wanita" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ibu_wanita'] ?: $ibu_wanita['nama']?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="binti_ibu_wanita" class="form-control input-sm" type="text" placeholder="Binti Ibu Wanita" value="<?= $_SESSION['post']['binti_ibu_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ibu_wanita" class="form-control input-sm" type="text" placeholder="Nomor KTP Ibu Wanita" value="<?= $_SESSION['post']['noktp_ibu_wanita'] ?: $ibu_wanita['nik']?>">
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ibu_wanita" id="tempatlahir_ibu_wanita" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ibu_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ibu_wanita" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ibu_wanita']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ibu_wanita" id="wn_ibu_wanita" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ibu_wanita" id="agama_ibu_wanita" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ibu_wanita" id="pekerjaan_ibu_wanita" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ibu_wanita" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ibu_wanita']?>">
										</div>
									</div>

									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_ibu_wanita" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ibu_wanita']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_ibu_wanita" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ibu_wanita']; ?>">
										</div>
									</div>
								<?php else: ?>
									<div class="form-group ibu_wanita">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.3 DATA IBU PASANGAN WANITA (Isi jika ibu bukan warga <?= strtolower($this->setting->sebutan_desa)?> ini)</strong></label>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
										<div class="col-sm-3">
											<input name="nama_ibu_wanita" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_ibu_wanita'] ?: $ibu_wanita['nama']?>">
										</div>
										<div class="col-sm-3 col-lg-3">
											<input name="binti_ibu_wanita" class="form-control input-sm" type="text" placeholder="Binti Ibu Wanita" value="<?= $_SESSION['post']['binti_ibu_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="noktp_ibu_wanita" class="form-control input-sm" type="text" placeholder="Nomor KTP Ibu Wanita" value="<?= $_SESSION['post']['noktp_ibu_wanita'] ?: $ibu_wanita['nik']?>">
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_ibu_wanita" id="tempatlahir_ibu_wanita" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_ibu_wanita']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_ibu_wanita" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_ibu_wanita']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_ibu_wanita" id="wn_ibu_wanita" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_ibu_wanita" id="agama_ibu_wanita" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_ibu_wanita" id="pekerjaan_ibu_wanita" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_ibu_wanita']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group ibu_wanita">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_ibu_wanita" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_ibu_wanita']?>">
										</div>
									</div>

								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_ibu_wanita" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_ibu_wanita']; ?>">
									</div>
								</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_ibu_wanita" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_ibu_wanita']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if (empty($wanita) OR strtolower($wanita['status_kawin'])=="cerai mati"): ?>
									<div class="form-group cerai_mati">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.4 DATA SUAMI TERDAHULU </strong></label>
									</div>
									<div class="form-group cerai_mati">
										<label class="col-sm-3 control-label"><strong>Nama <?= ucwords($jenis_pasangan)?> Terdahulu / Bin</strong></label>
										<div class="col-sm-3">
											<input name="nama_suami_dulu" class="form-control input-sm suami_dulu" type="text" placeholder="Nama Suami Terdahulu" value="<?= $_SESSION['post']['nama_suami_dulu']?>">
										</div>
										<div class="col-sm-3">
											<input name="bin_suami_dulu" class="form-control input-sm suami_dulu" type="text" placeholder="Bin" value="<?= $_SESSION['post']['binti_suami_dulu']?>">
										</div>
										<div class="col-sm-2">
											<input name="noktp_suami_dulu" class="form-control input-sm suami_dulu" type="text" placeholder="No KTP Suami Dulu" value="<?= $_SESSION['post']['noktp_suami_dulu']?>">
										</div>
									</div>
									<div class="form-group cerai_mati">
										<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm suami_dulu" type="text" name="tempatlahir_suami_dulu" id="tempatlahir_suami_dulu" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_suami_dulu']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker suami_dulu" name="tanggallahir_suami_dulu" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_suami_dulu']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group cerai_mati">
										<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2 suami_dulu" name="wn_suami_dulu" id="wn_suami_dulu" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_suami_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2 suami_dulu" name="agama_suami_dulu" id="agama_suami_dulu" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_suami_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2 suami_dulu" name="pek_suami_dulu" id="pek_suami_dulu" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pek_suami_dulu']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group cerai_mati">
										<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_suami_dulu" class="form-control input-sm suami_dulu" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_suami_dulu']?>">
										</div>
									</div>
									<div class="form-group cerai_mati">
										<label class="col-sm-3 control-label">Meninggal Dunia Pada Tanggal / Tempat</label>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker suami_dulu" name="tanggalmeninggal_suami_dulu" type="text" placeholder="Tgl. Meninggal" value="<?= $_SESSION['post']['tanggalmeninggal_suami_dulu']?>"/>
											</div>
										</div>
										<div class="col-sm-6">
											<input name="tempatmeninggal_suami_dulu" class="form-control input-sm suami_dulu" type="text" placeholder="Tempat Meninggal" value="<?= $_SESSION['post']['tempatmeninggal_suami_dulu']?>">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">B. SAKSI 1</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label for="calon_saksi1_1" class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($saksi1)): ?>active<?php endif ?>">
											<input id="calon_saksi1_1" type="radio" name="calon_saksi1" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi1)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_saksi1_asal(this.value);"> Warga Desa
										</label>
										<label for="calon_saksi1_2" class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($saksi1)): ?>active<?php endif; ?>">
											<input id="calon_saksi1_2" type="radio" name="calon_saksi1" class="form-check-input" type="radio" value="2" <?php if (empty($saksi1)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_saksi1_asal(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<label for="saksi1_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.1 DATA SAKSI 1 WARGA DESA</strong></label>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<input id="calon_saksi1" name="calon_saksi1" type="hidden" value=""/>

									<label for="saksi1_desa" class="col-sm-3 control-label"><strong>NIK / Nama :</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2" id="id_saksi1" name="id_saksi1" style ="width:100%;" onchange="submit_form_ambil_data(this.id);">
											<option value="">-- Cari NIK / Nama--</option>
											<?php foreach ($laki as $data): ?>
												<option value="<?= $data['id']?>" <?= selected($saksi1['nik'], $data['nik']); ?>>NIK :<?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php if (empty($saksi1)): ?>
									<div class="form-group saksi1_luar_desa">
										<label for="saksi1_luar_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.1 DATA SAKSI 1 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="saksi1_luar_desa" class="col-sm-3 control-label"><strong>Nama Lengkap / NIK KTP</strong></label>
										<div class="col-sm-5 col-lg-6">
											<input name="nama_saksi1" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_saksi1']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="no_ktp_saksi1" class="form-control input-sm" type="text" placeholder="Nomor KTP" value="<?= $_SESSION['post']['no_ktp_saksi1']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="tempatlahir_saksi1" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_saksi1" id="tempatlahir_saksi1" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_saksi1']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tanggallahir_saksi1" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_saksi1']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="tempatlahir_saksi1" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_saksi1" id="wn_saksi1" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_saksi1']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_saksi1" id="agama_saksi1" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_saksi1']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_saksi1" id="pekerjaan_saksi1" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_saksi1']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="saksi1_luar_desa" class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_saksi1" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_saksi1']?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_saksi1" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi1']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_saksi1" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi1']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($saksi1): ?>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_saksi1" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi1']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_saksi1" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi1']; ?>">
										</div>
									</div>
								<?php endif; ?>

								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">B. DATA SAKSI 2</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label for="calon_saksi2_1" class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($saksi2)): ?>active<?php endif ?>">
											<input id="calon_saksi2_1" type="radio" name="calon_saksi2" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi2)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_saksi2_asal(this.value);"> Warga Desa
										</label>
										<label for="calon_saksi2_2" class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($saksi2)): ?>active<?php endif; ?>">
											<input id="calon_saksi2_2" type="radio" name="calon_saksi2" class="form-check-input" type="radio" value="2" <?php if (empty($saksi2)): ?>checked<?php endif; ?> autocomplete="off" onchange="calon_saksi2_asal(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<label for="saksi2_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.2 DATA SAKSI 2 WARGA DESA</strong></label>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<input id="calon_saksi2" name="calon_saksi2" type="hidden" value=""/>

									<label for="saksi2_desa" class="col-sm-3 control-label"><strong>NIK / Nama :</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2" id="id_saksi2" name="id_saksi2" style ="width:100%;" onchange="submit_form_ambil_data(this.id);">
											<option value="">-- Cari NIK / Nama--</option>
											<?php foreach ($laki as $data): ?>
												<option value="<?= $data['id']?>" <?= selected($saksi2['nik'], $data['nik']); ?>>NIK :<?= $data['nik']." - ".$data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php if (empty($saksi2)): ?>
									<div class="form-group saksi2_luar_desa">
										<label for="saksi2_luar_desa" class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>B.2 DATA SAKSI 2 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="saksi2_luar_desa" class="col-sm-3 control-label"><strong>Nama Lengkap / NIK KTP</strong></label>
										<div class="col-sm-5 col-lg-6">
											<input name="nama_saksi2" class="form-control input-sm" type="text" placeholder="Nama Lengkap" value="<?= $_SESSION['post']['nama_saksi2']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<input name="no_ktp_saksi2" class="form-control input-sm" type="text" placeholder="Nomor KTP" value="<?= $_SESSION['post']['no_ktp_saksi2']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="tempatlahir_saksi2" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-5 col-lg-6">
											<input class="form-control input-sm" type="text" name="tempatlahir_saksi2" id="tempatlahir_saksi2" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_saksi2']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tanggallahir_saksi2" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_saksi2']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="tempatlahir_saksi2" class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
										<div class="col-sm-2">
											<select class="form-control input-sm select2" name="wn_saksi2" id="wn_saksi2" style ="width:100%;">
												<option value="">-- Pilih warganegara --</option>
												<?php foreach ($warganegara as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_saksi2']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="agama_saksi2" id="agama_saksi2" style ="width:100%;">
												<option value="">-- Pilih Agama --</option>
												<?php foreach ($agama as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_saksi2']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-3">
											<select class="form-control input-sm select2" name="pekerjaan_saksi2" id="pekerjaan_saksi2" style ="width:100%;">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pekerjaan_saksi2']); ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="saksi2_luar_desa" class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
										<div class="col-sm-8">
											<input name="alamat_saksi2" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_saksi2']?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_saksi2" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi2']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_saksi2" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi2']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<?php if ($saksi2): ?>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
										<div class="col-sm-4">
											<input name="telepon_saksi2" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi2']; ?>">
										</div>
									</div>
									<div id="" class="form-group ">
										<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
										<div class="col-sm-4">
											<input name="penghayat_saksi2" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi2']; ?>">
										</div>
									</div>
								<?php endif; ?>
								<!-- <div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.5 DATA SAKSI 1 </strong></label>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
									<div class="col-sm-3">
										<input name="nama_saksi1" class="form-control input-sm" type="text" placeholder="Nama" value="<?= $_SESSION['post']['nama_saksi1']?>">
									</div>
									<div class="col-sm-3">
										<input name="bin_saksi1" class="form-control input-sm" type="text" placeholder="Bin" value="<?= $_SESSION['post']['bin_saksi1']?>">
									</div>
									<div class="col-sm-2">
										<input name="noktp_saksi1" class="form-control input-sm" type="text" placeholder="No KTP" value="<?= $_SESSION['post']['noktp_saksi1']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-5 col-lg-6">
										<input class="form-control input-sm" type="text" name="tempatlahir_saksi1" id="tempatlahir_saksi1" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_saksi1']?>">
									</div>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_saksi1" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_saksi1']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
									<div class="col-sm-2">
										<select class="form-control input-sm select2" name="wn_saksi1" id="" style ="width:100%;">
											<option value="">-- Pilih warganegara --</option>
											<?php foreach ($warganegara as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_saksi1']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control input-sm select2" name="agama_saksi1" id="agama_saksi1" style ="width:100%;">
											<option value="">-- Pilih Agama --</option>
											<?php foreach ($agama as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_saksi1']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control input-sm select2" name="pek_saksi1" id="pek_saksi1" style ="width:100%;">
											<option value="">-- Pekerjaan --</option>
											<?php foreach ($pekerjaan as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pek_saksi1']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group saksi1">
									<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
									<div class="col-sm-8">
										<input name="alamat_saksi1" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_saksi1']?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_saksi1" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi1']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
									<div class="col-sm-4">
										<input name="penghayat_saksi1" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi1']; ?>">
									</div>
								</div> -->

								<!-- <div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>B.6 DATA SAKSI 2 </strong></label>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Nama Lengkap</strong></label>
									<div class="col-sm-3">
										<input name="nama_saksi2" class="form-control input-sm" type="text" placeholder="Nama" value="<?= $_SESSION['post']['nama_saksi2']?>">
									</div>
									<div class="col-sm-3">
										<input name="bin_saksi2" class="form-control input-sm" type="text" placeholder="Bin" value="<?= $_SESSION['post']['bin_saksi2']?>">
									</div>
									<div class="col-sm-2">
										<input name="noktp_saksi2" class="form-control input-sm" type="text" placeholder="No KTP" value="<?= $_SESSION['post']['noktp_saksi2']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
									<div class="col-sm-5 col-lg-6">
										<input class="form-control input-sm" type="text" name="tempatlahir_saksi2" id="tempatlahir_saksi2" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_saksi2']?>">
									</div>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker" name="tanggallahir_saksi2" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggallahir_saksi2']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Warganegara / Agama / Pekerjaan</label>
									<div class="col-sm-2">
										<select class="form-control input-sm select2" name="wn_saksi2" id="" style ="width:100%;">
											<option value="">-- Pilih warganegara --</option>
											<?php foreach ($warganegara as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['wn_saksi2']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control input-sm select2" name="agama_saksi2" id="agama_saksi2" style ="width:100%;">
											<option value="">-- Pilih Agama --</option>
											<?php foreach ($agama as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_saksi2']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
									<div class="col-sm-3">
										<select class="form-control input-sm select2" name="pek_saksi2" id="pek_saksi2" style ="width:100%;">
											<option value="">-- Pekerjaan --</option>
											<?php foreach ($pekerjaan as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['pek_saksi2']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group saksi2">
									<label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
									<div class="col-sm-8">
										<input name="alamat_saksi2" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" value="<?= $_SESSION['post']['alamat_saksi2']?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Telepon</strong></label>
									<div class="col-sm-4">
										<input name="telepon_saksi2" class="form-control input-sm" type="number"  placeholder="Telepon" value="<?= $_SESSION['post']['telepon_saksi2']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
									<div class="col-sm-4">
										<input name="penghayat_saksi2" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_saksi2']; ?>">
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>C. DATA PERKAWINAN </strong></label>
								</div>
								<div class="form-group wali">
									<label class="col-sm-3 control-label">Hari, Tanggal, Jam, Melapor</label>
									<div class="col-sm-3 col-lg-4">
										<input class="form-control input-sm required hari" type="text" name="hari_nikah" id="hari_nikah" readonly="readonly" placeholder="Hari Nikah" value="<?= $_SESSION['post']['hari_nikah']?>">
									</div>
									<div class="col-sm-3 col-lg-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker data_hari required" name="tanggal_nikah" type="text" placeholder="Tgl. Nikah" value="<?= $_SESSION['post']['tanggal_nikah']?>"/>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input class="form-control input-sm required" name="jam_nikah" id="jammenit_1" type="text" placeholder="Jam Nikah" value="<?= $_SESSION['post']['jam_nikah']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Tanggal Pemberkatan Perkawinan</strong></label>
									<div class="col-sm-8">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker data_hari required" name="tanggal_pemberkatan" type="text" placeholder="Tgl. Nikah" value="<?= $_SESSION['post']['tanggal_pemberkatan']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Agama/Penghayat Kepercayaan</strong></label>
									<div class="col-sm-8">
										<select class="form-control input-sm select2" name="agama_kawin" id="agama_pria" style ="width:100%;">
											<option value="">-- Pilih Agama --</option>
											<?php foreach ($agama as $data): ?>
												<option value="<?= $data['nama']?>" <?= selected($data['nama'], $_SESSION['post']['agama_kawin']); ?>><?= $data['nama']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Organisasi Penghayat Kepercayaan</strong></label>
									<div class="col-sm-8">
										<input name="penghayat_kawin" class="form-control input-sm" type="text"  placeholder="Nama Organisasi Penghayat Kepercayaan" value="<?= $_SESSION['post']['penghayat_kawin']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nama Badan Peradilan</strong></label>
									<div class="col-sm-8">
										<input name="badan_peradilan" class="form-control input-sm" type="text"  placeholder="Nama Badan Peradilan" value="<?= $_SESSION['post']['badan_peradilan']; ?>">
									</div>
								</div>
								<div id="" class="form-group ">
									<label for="" class="col-sm-3 control-label"><strong>Nomor Putusan Penetapan Pengadilan</strong></label>
									<div class="col-sm-8">
										<input name="nomor_putusan" class="form-control input-sm" type="text"  placeholder="Nomor Putusan" value="<?= $_SESSION['post']['nomor_putusan']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Tanggal Putusan Penetapan Pengadilan</strong></label>
									<div class="col-sm-8">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker data_hari " name="tanggal_putusan" type="text" placeholder="Tgl. Nikah" value="<?= $_SESSION['post']['tanggal_putusan']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Nama Pemuka Agama/Pghyt Kepercayaan</strong></label>
									<div class="col-sm-8">
										<input name="nama_pemuka" class="form-control input-sm " type="text" placeholder="Nama Pemuka Agama/Pghyt Kepercayaan" value="<?= $_SESSION['post']['nama_pemuka']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Ijin Perwakilan bagi WNA / Nomor</strong></label>
									<div class="col-sm-8">
										<input name="ijin_putusan" class="form-control input-sm " type="text" placeholder="Ijin Perwakilan bagi WNA / Nomor" value="<?= $_SESSION['post']['ijin_putusan']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Jumlah Anak Yang Telah Diakui dan Disahkan</strong></label>
									<div class="col-sm-8">
										<input name="jumlah_anak" class="form-control input-sm " type="text" placeholder="Jumlah Anak Yang Telah Diakui dan Disahkan" value="<?= $_SESSION['post']['jumlah_anak']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>Nama Anak</strong></label>
									<div class="col-sm-8">
										<input name="nama_anak1" class="form-control input-sm " type="text" placeholder="Nama Anak 1" value="<?= $_SESSION['post']['nama_anak1']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-8">
										<input name="nama_anak2" class="form-control input-sm " type="text" placeholder="Nama Anak 2" value="<?= $_SESSION['post']['nama_anak2']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-8">
										<input name="nama_anak3" class="form-control input-sm " type="text" placeholder="Nama Anak 3" value="<?= $_SESSION['post']['nama_anak3']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-8">
										<input name="nama_anak4" class="form-control input-sm " type="text" placeholder="Nama Anak 4" value="<?= $_SESSION['post']['nama_anak4']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-8">
										<input name="nama_anak5" class="form-control input-sm " type="text" placeholder="Nama Anak 5" value="<?= $_SESSION['post']['nama_anak5']?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-8">
										<input name="nama_anak6" class="form-control input-sm " type="text" placeholder="Nama Anak 6" value="<?= $_SESSION['post']['nama_anak6']?>">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label"><strong>No, Tgl. Akta Kelahiran</strong></label>
									<div class="col-sm-4">
										<input name="no_akta_anak1" class="form-control input-sm " type="text" placeholder="1. No." value="<?= $_SESSION['post']['no_akta_anak1']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak1" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak1']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-4">
										<input name="no_akta_anak2" class="form-control input-sm " type="text" placeholder="2. No." value="<?= $_SESSION['post']['no_akta_anak2']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak2" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak2']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-4">
										<input name="no_akta_anak3" class="form-control input-sm " type="text" placeholder="3. No." value="<?= $_SESSION['post']['no_akta_anak3']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak3" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak3']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-4">
										<input name="no_akta_anak4" class="form-control input-sm" type="text" placeholder="4. No." value="<?= $_SESSION['post']['no_akta_anak4']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak4" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak4']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-4">
										<input name="no_akta_anak5" class="form-control input-sm" type="text" placeholder="5. No." value="<?= $_SESSION['post']['no_akta_anak5']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak5" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak5']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"></label>
									<div class="col-sm-4">
										<input name="no_akta_anak6" class="form-control input-sm" type="text" placeholder="6. No." value="<?= $_SESSION['post']['no_akta_anak6']?>">
									</div>
									<div class="col-sm-4">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control datepicker input-sm" name="tgl_akta_anak6" type="text" placeholder="Tgl." value="<?= $_SESSION['post']['tgl_akta_anak6']?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>D. PENANDA TANGAN </strong></label>
								</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
				<div class='modal fade' id='dialog' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
								<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Perhatian</h4>
							</div>
							<div class='modal-body btn-info'>
								Salah satu calon pasangan, pria atau wanita, harus warga desa.
							</div>
							<div class='modal-footer'>
								<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
