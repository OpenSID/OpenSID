<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan <?= $kat_nama?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php if (in_array($kat, array('2', '3'))): ?>
				<li><a href="<?= $kembali_ke ?: site_url("$this->controller/peraturan_desa/$kat"); ?>"><i class="fa fa-dashboard"></i> Daftar <?= $kat_nama?></a></li>
			<?php else: ?>
				<li><a href="<?= site_url("$this->controller/index/$kat"); ?>"><i class="fa fa-dashboard"></i> Daftar <?= $kat_nama?></a></li>
			<?php endif; ?>
			<li class="active">Pengaturan <?= $kat_nama?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="box box-info">
				<div class="box-header with-border">
					<?php if (in_array($kat, array('2', '3'))): ?>
						<a href="<?= $kembali_ke ?: site_url("$this->controller/peraturan_desa/$kat"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar <?= $kat_nama?>
						</a>
					<?php else: ?>
						<a href="<?= site_url("$this->controller/index/$kat"); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar <?= $kat_nama?>
						</a>
					<?php endif; ?>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
						<div class="col-sm-6">
							<input name="nama" class="form-control input-sm nomor_sk required" type="text" maxlength="200" value="<?=$dokumen['nama']?>"></input>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="nama">Nama Dokumen</label>
						<div class="col-sm-6">
							<input name="nama" class="form-control input-sm nomor_sk required" type="text" maxlength="100" value="<?=$dokumen['nama']?>"></input>
						</div>
					</div>
					<?php if ($dokumen['satuan']): ?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Dokumen</label>
							<div class="col-sm-4">
								<input type="hidden" name="old_file" value="<?= $dokumen['satuan']?>">
								<img class="attachment-img img-responsive img-circle" src="<?= base_url().LOKASI_DOKUMEN.$dokumen['satuan']?>" alt="<?= $dokumen['satuan']?>">
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label col-sm-4" for="upload">Unggah Dokumen</label>
						<div class="col-sm-6">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control <?php empty($dokumen) and print('required')?>" id="file_path" name="satuan">
								<input id="file" type="file" class="hidden" name="satuan">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<?php if ($dokumen): ?>
								<p class="small">(Kosongkan jika tidak ingin mengubah dokumen)</p>
							<?php endif; ?>
						</div>
					</div>
					<input name="kategori" type="hidden" value="<?= $dokumen['kategori'] ?: $kat;?>">
					<?php
						if ($kat == 2 or $dokumen['kategori'] == 2)
							include ("donjo-app/views/dokumen/_sk_kades.php");
						elseif ($kat == 3 or $dokumen['kategori'] == 3)
							include ("donjo-app/views/dokumen/_perdes.php");
						else
							include ("donjo-app/views/dokumen/_informasi_publik.php");
					?>
				</div>
				<div class='box-footer'>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
				</div>
			</div>
		</form>
	</section>
</div>

