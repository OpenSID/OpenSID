<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $judul ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"><?= $judul ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= site_url('setting/update'); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
				<?php if ($atur_latar) : ?>
					<div class="col-md-3">
						<?php if (in_array('sistem', $kategori)) : ?>
							<div class="box box-primary">
								<div class="box-header with-border">
									<b>Latar Website</b>
								</div>
								<div class="box-body box-profile text-center">
									<img class="img-responsive" src="<?= asset($latar_website ?: 'assets/front/css/images/latar_website.jpg?v', false); ?>" alt="Latar Halaman Website" width="100%">
									<p class="text-muted text-center text-red">(Kosongkan, jika latar website <?= 'tema ' . $this->theme; ?> tidak berubah)</p>
									<div class="input-group">
										<input type="text" class="form-control input-sm" id="file_path" name="latar_website">
										<input type="file" class="hidden" id="file" name="latar_website">
										<input type="text" class="hidden" name="lokasi" value="<?= $lokasi; ?>">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser"><i class="fa fa-search"></i>&nbsp;</button>
										</span>
									</div>
								</div>
							</div>
							<div class="box box-primary">
								<div class="box-header with-border">
									<b>Latar Login Admin</b>
								</div>
								<div class="box-body box-profile text-center">
									<img class="img-responsive" src="<?= asset($latar_login ?: 'assets/css/images/latar_login.jpg?v', false); ?>" alt="Latar Halaman Login" width="100%">
									<p class="text-muted text-center text-red">(Kosongkan, jika latar login tidak berubah)</p>
									<div class="input-group">
										<input type="text" class="form-control input-sm" id="file_path1" name="latar_login">
										<input type="file" class="hidden" id="file1" name="latar_login">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser1"><i class="fa fa-search"></i>&nbsp;</button>
										</span>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if (in_array('setting_mandiri', $kategori)) : ?>
							<div class="box box-primary">
								<div class="box-header with-border">
									<b>Latar Login Mandiri</b>
								</div>
								<div class="box-body box-profile text-center">
									<img class="img-responsive" src="<?= asset($latar_login_mandiri ?: 'assets/css/images/latar_login_mandiri.jpg?v', false); ?>" alt="Latar Halaman Login" width="100%">
									<p class="text-muted text-center text-red">(Kosongkan, jika latar login tidak berubah)</p>
									<div class="input-group">
										<input type="text" class="form-control input-sm" id="file_path2" name="latar_login_mandiri">
										<input type="file" class="hidden" id="file2" name="latar_login_mandiri">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser2"><i class="fa fa-search"></i>&nbsp;</button>
										</span>
									</div>
								</div>
							</div>
							<div class="box box-primary">
								<div class="box-header with-border">
									<b>Pintasan</b>
								</div>
								<div class="box-body box-profile">
									<div class="small-box bg-aqua">
										<div class="inner">
											<h4>Pengaturan Surat</h4><br>
										</div>
										<div class="icon">
											<i class="ion-ios-paper"></i>
										</div>
										<a href="<?= site_url('surat_master') ?>" class="small-box-footer">Lihat Pengaturan <i class="fa fa-arrow-circle-right"></i></a>
									</div>
									<div class="small-box bg-blue">
										<div class="inner">
											<h4>Syarat Surat</h4><br>
										</div>
										<div class="icon">
											<i class="ion-ios-paper"></i>
										</div>
										<a href="<?= site_url('surat_mohon') ?>" class="small-box-footer">Lihat Pengaturan <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-md-9">
					<?php else : ?>
						<div class="col-md-12">
						<?php endif; ?>
						<div class="box box-primary">
							<div class="box-header with-border">
								<b>Pengaturan Dasar</b>
							</div>
							<div class="box-body">
								<?php include 'donjo-app/views/setting/form.php'; ?>
							</div>
							<div class="box-footer">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<?php if ($this->CI->cek_hak_akses_url('u', $aksi_controller)) : ?>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								<?php endif; ?>
							</div>
						</div>
						</div>
			</form>
		</div>
	</section>
</div>

<script type="text/javascript">
	$("#form_tampilan_anjungan_video").hide();
	var e = document.getElementById("tampilan_anjungan");

	function show() {
		var as = document.forms[0].tampilan_anjungan.value;
		var strUser = e.options[e.selectedIndex].value;
		if (as == 1) {
			$('#form_tampilan_anjungan_slider').show();
			$('#form_tampilan_anjungan_audio').hide();
			$('#form_tampilan_anjungan_video').hide();
			$('#form_tampilan_anjungan_waktu').show();
		} else if (as == 2) {
			$('#form_tampilan_anjungan_slider').hide();
			$('#form_tampilan_anjungan_audio').show();
			$('#form_tampilan_anjungan_video').show();
			$('#form_tampilan_anjungan_waktu').show();
		} else {
			$('#form_tampilan_anjungan_slider').hide();
			$('#form_tampilan_anjungan_video').hide();
			$('#form_tampilan_anjungan_waktu').hide();
		}
	}
	e.onchange = show;
	show();
</script>