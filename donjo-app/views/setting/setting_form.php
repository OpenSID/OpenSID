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
			<form id="validasi" action="<?=site_url('setting/update')?>" method="POST" class="form-horizontal">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-body">
							<?php foreach ($this->$list_setting as $setting): ?>
								<?php if ($setting->kategori != 'development' OR ($this->config->item("environment") == 'development' )): ?>
									<div class="form-group">
										<label class="col-sm-12 col-md-3" for="nama"><?= $setting->key?></label>
										<?php if ($setting->jenis == 'option'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
													<?php foreach ($setting->options as $option): ?>
													<option value="<?= $option->id ?>" <?php ($setting->value == $option->id) and print('selected') ?>><?= $option->value ?></option>
													<?php endforeach ?>
												</select>
											</div>
										<?php elseif ($setting->jenis == 'option-kode'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
													<?php foreach ($setting->options as $option): ?>
													<option value="<?= $option->kode ?>" <?php ($setting->value == $option->kode) and print('selected') ?>><?= $option->value ?></option>
													<?php endforeach ?>
												</select>
											</div>
										<?php elseif ($setting->jenis == 'option-value'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
													<?php foreach ($setting->options as $option): ?>
													<option value="<?= $option->value ?>" <?php ($setting->value == $option->value) and print('selected') ?>><?= $option->value ?></option>
													<?php endforeach ?>
												</select>
											</div>
										<?php elseif ($setting->key == 'timezone'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" name="<?= $setting->key?>" >
													<option value="Asia/Jakarta" <?php selected($setting->value, 'Asia/Jakarta') ?>>Asia/Jakarta</option>
													<option value="Asia/Makassar" <?php selected($setting->value, 'Asia/Makassar') ?>>Asia/Makassar</option>
													<option value="Asia/Jayapura" <?php selected($setting->value, 'Asia/Jayapura') ?>>Asia/Jayapura</option>
												</select>
											</div>
										<?php elseif ($setting->key == 'sumber_gambar_slider'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" id="<?= $setting->key?>" name="<?= $setting->key?>">
													<option value="1" <?php selected($setting->value, 1) ?>>Gambar utama artikel terbaru</option>
													<option value="2" <?php selected($setting->value, 2) ?>>Gambar utama artikel terbaru yang masuk ke slider atas</option>
													<option value="3" <?php selected($setting->value, 3) ?>>Gambar dalam album galeri yang dimasukkan ke slider</option>
												</select>
											</div>
										<?php elseif ($setting->jenis == 'boolean'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" id="<?= $setting->key?>" name="<?= $setting->key?>">
												<option value="1" <?php selected($setting->value, 1) ?>>Ya</option>
													<option value="0" <?php selected($setting->value, 0) ?>>Tidak</option>
												</select>
											</div>
										<?php elseif ($setting->key == 'web_theme'): ?>
											<div class="col-sm-12 col-md-4">
												<select class="form-control input-sm" name="<?= $setting->key?>" >
													<?php foreach ($list_tema as $tema): ?>
														<option value="<?= $tema?>" <?php selected($setting->value, $tema) ?>><?= $tema?></option>
													<?php endforeach;?>
												</select>
											</div>
										<?php else : ?>
											<div class="col-sm-12 col-md-4">
												<input id="<?= $setting->key?>" name="<?= $setting->key?>" class="form-control input-sm <?php ($setting->jenis != 'int') or print 'digits'?>" type="text" value="<?= $setting->value?>" <?php ($setting->kategori != 'readonly') or print 'disabled'?>></input>
											</div>
										<?php endif; ?>
										<label class="col-sm-12 col-md-5 pull-left" for="nama"><?= $setting->keterangan?></label>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
