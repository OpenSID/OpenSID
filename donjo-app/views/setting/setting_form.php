<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $judul ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"><?= $judul ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?=site_url('setting/update'); ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
				<?php if ($atur_latar): ?>
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-header with-border">
							<b>Latar Website</b>
						</div>
						<div class="box-body box-profile text-center">
							<img class="img-responsive" src="<?= base_url($latar_website ? $latar_website : 'assets/front/css/images/latar_website.jpg'); ?>" alt="Latar Halaman Website" width="100%">
							<p class="text-muted text-center text-red">(Kosongkan, jika latar website <?= 'tema ' . $this->theme; ?> tidak berubah)</p>
							<div class="input-group">
								<input type="text" class="form-control input-sm" id="file_path" name="latar_website">
								<input type="file" class="hidden" id="file" name="latar_website">
								<input type="text" class="hidden" name="lokasi" value="<?=$lokasi;?>">
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
							<img class="img-responsive" src="<?= base_url($latar_login ? $latar_login : 'assets/css/images/latar_login.jpg'); ?>" alt="Latar Halaman Login" width="100%">
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
					<div class="box box-primary">
						<div class="box-header with-border">
							<b>Latar Login Mandiri</b>
						</div>
						<div class="box-body box-profile text-center">
							<img class="img-responsive" src="<?= base_url($latar_login_mandiri ? $latar_login_mandiri : 'assets/css/images/latar_login_mandiri.jpg'); ?>" alt="Latar Halaman Login" width="100%">
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
				</div>
				<div class="col-md-9">
				<?php else: ?>
					<div class="col-md-12">
				<?php endif; ?>
					<div class="box box-primary">
						<div class="box-header with-border">
							<b>Pengaturan Dasar</b>
						</div>
						<div class="box-body">
							<?php include("donjo-app/views/setting/form.php"); ?>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<?php if ($this->CI->cek_hak_akses_url('u', $aksi_controller)): ?>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</form> 
		</div>
	</section>
</div>
