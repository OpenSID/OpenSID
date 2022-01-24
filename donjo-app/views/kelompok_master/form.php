<?php if ($this->CI->cek_hak_akses('u')): ?>
	<?= $tipe = ucfirst(str_replace('_master', '', $this->controller)); ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Pengelolaan Kategori <?= $tipe; ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
				<li><a href="<?= site_url(strtolower($tipe)); ?>"> Daftar <?= $tipe; ?></a></li>
				<li><a href="<?= site_url($this->controller); ?>"> Daftar Ketegori <?= $tipe; ?></a></li>
				<li class="active">Pengelolaan Kategori <?= $tipe; ?></li>
			</ol>
		</section>
		<section class="content">
			<div class="box box-info">
				<div class="box-header with-border">
					<a href="<?= site_url($this->controller); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Kategori <?= $tipe; ?></a>
				</div>
				<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label" for="nama">Klasifikasi/Kategori <?= $tipe; ?></label>
							<div class="col-sm-8">
								<input id="kelompok" class="form-control input-sm required" type="text" placeholder="Kategori <?= $tipe; ?>" name="kelompok" value="<?= $kelompok_master->kelompok; ?>"></input>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="Deskripsi">Deskripsi <?= $tipe; ?></label>
							<div class="col-sm-8">
								<textarea name="deskripsi" class="form-control input-sm" placeholder="Deskripsi <?= $tipe; ?>" rows="5"><?= $kelompok_master->deskripsi; ?></textarea>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
					</div>
				</form>
			</div>
		</section>
	</div>
<?php endif; ?>