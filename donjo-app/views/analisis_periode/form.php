<script>
	function reset_form() {
		<?php if ($analisis_periode['aktif'] == '1' || $analisis_periode['aktif'] == null) : ?>
			$("#ss3").addClass('active');
			$("#ss4").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_periode['aktif'] == '2') : ?>
			$("#ss4").addClass('active');
			$("#ss3").removeClass("active");
		<?php endif ?>

		$("#ss2").addClass('active');
		$("#ss1").removeClass("active");

		<?php if ($analisis_periode['id_state'] == '1' || $analisis_periode['id_state'] == null) : ?>
			$("#sx1").addClass('active');
			$("#sx2").removeClass("active");
			$("#sx3").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_periode['id_state'] == '2') : ?>
			$("#sx2").addClass('active');
			$("#sx1").removeClass("active");
			$("#sx3").removeClass("active");
		<?php endif ?>
		<?php if ($analisis_periode['id_state'] == '3') : ?>
			$("#sx3").addClass('active');
			$("#sx2").removeClass("active");
			$("#sx1").removeClass("active");
		<?php endif ?>
	};
</script>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Priode Analisis <?= empty($analisis_master['nama']) ? '' : "[ {$analisis_master['nama']} ]" ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?= site_url('analisis_master/clear') ?>"> Master Analisis</a></li>
			<li><a href="<?= site_url('analisis_indikator') ?>">Indikator Analisis</a></li>
			<li><a href="<?= site_url('analisis_periode') ?>">Pengaturan Priode</a></li>
			<li class="active">Tambah Priode</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data); ?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('analisis_periode') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Priode Analisis</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="nama">Nama Periode</label>
										<div class="col-sm-8">
											<input id="nama" class="form-control input-sm required nomor_sk" type="text" placeholder="Nama Priode" name="nama" value="<?= $analisis_periode['nama'] ?>">
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="id_state">Tahap Pendataan</label>
										<div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
											<label id="sx1" class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-4 col-lg-3 form-check-label <?php if ($analisis_periode['id_state'] == '1' || $analisis_periode['id_state'] == null) : ?>active<?php endif ?>">
												<input id="group1" type="radio" name="id_state" class="form-check-input" type="radio" value="1" <?php if ($analisis_periode['id_state'] == '1' || $analisis_periode['id_state'] == null) : ?>checked <?php endif ?> autocomplete="off">Belum Pendataan
											</label>
											<label id="sx2" class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-4 col-lg-3 form-check-label <?php if ($analisis_periode['id_state'] == '2') : ?>active<?php endif ?>">
												<input id="group2" type="radio" name="id_state" class="form-check-input" type="radio" value="2" <?php if ($analisis_periode['id_state'] == '2') : ?>checked <?php endif ?> autocomplete="off">Sedang Pendataan
											</label>
											<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-12 col-sm-4 col-lg-3 form-check-label <?php if ($analisis_periode['id_state'] == '3') : ?>active<?php endif ?>">
												<input id="group3" type="radio" name="id_state" class="form-check-input" type="radio" value="3" <?php if ($analisis_periode['id_state'] == '3') : ?>checked <?php endif ?> autocomplete="off">Selesai Pelaksanaan
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="tahun_pelaksanaan">Tahun Pelaksanaan</label>
										<div class="col-sm-2">
											<input id="tahun_pelaksanaan" class="form-control input-sm required bilangan" maxlength="4" type="text" placeholder="Tahun" name="tahun_pelaksanaan" value="<?= $analisis_periode['tahun_pelaksanaan'] ?>">
										</div>
									</div>
								</div>
								<?php if ($analisis_periode == null) : ?>
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" for="act_analisis">Duplikat data pendataan sebelumnya</label>
											<div class="btn-group col-xs-12 col-sm-7" data-toggle="buttons">
												<label id="ss1" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label">
													<input id="g1" type="radio" name="duplikasi" class="form-check-input" type="radio" value="1" autocomplete="off"> Ya
												</label>
												<label id="ss2" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label active">
													<input id="g2" type="radio" name="duplikasi" class="form-check-input" type="radio" value="2" checked autocomplete="off"> Tidak
												</label>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="col-sm-12">
									<div class="form-group" id="delik">
										<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
										<div class="col-sm-8">
											<textarea id="keterangan" class="form-control input-sm" placeholder="Keterangan" name="keterangan"><?= $analisis_periode['keterangan'] ?></textarea>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="act_analisis">Status</label>
										<div class="btn-group col-xs-12 col-sm-7" data-toggle="buttons">
											<label id="ss3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($analisis_periode['aktif'] == '1' || $analisis_periode['aktif'] == null) : ?>active<?php endif ?>">
												<input id="g3" type="radio" name="aktif" class="form-check-input" type="radio" value="1" <?php if ($analisis_periode['aktif'] == '1' || $analisis_periode['aktif'] == null) : ?>checked <?php endif ?> autocomplete="off"> Aktif
											</label>
											<label id="ss4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($analisis_periode['aktif'] == '2') : ?>active<?php endif ?>">
												<input id="g4" type="radio" name="aktif" class="form-check-input" type="radio" value="2" <?php if ($analisis_periode['aktif'] == '2') : ?>checked<?php endif ?> autocomplete="off"> Tidak AKtif
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>