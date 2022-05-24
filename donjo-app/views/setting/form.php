<?php foreach ($this->list_setting as $setting): ?>
	<?php $key = ucwords(str_replace('_', ' ', $setting->key)); ?>
	<?php if ($setting->key != 'penggunaan_server' && $setting->jenis != 'upload' && in_array($setting->kategori, $kategori)): ?>
		<?php $setting->kategori = ($setting->kategori == 'setting_analisis' && config_item('demo_mode')) ? 'readonly' : $setting->kategori; ?>
		<?php $setting->value    = ($setting->key == 'layanan_opendesa_token' && config_item('demo_mode')) ? '' : $setting->value; ?>
		<div class="form-group" id="form_<?= $setting->key ?>">
			<label class="col-sm-12 col-md-3" for="nama"><?= $key; ?></label>
			<?php if ($setting->jenis == 'option'): ?>
				<div class="col-sm-12 col-md-4">
					<?php if ($setting->key == 'tampilan_anjungan_slider'): ?>
						<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
							<?php foreach ($daftar_album as $option): ?>
							<option value="<?= $option['id']; ?>" <?= selected($setting->value, $option['id']); ?>><?= $option['nama']; ?></option>
							<?php endforeach ?>
						</select>
					<?php else: ?>
						<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
							<?php foreach ($setting->options as $option): ?>
							<option value="<?= $option->id ?>" <?= selected($setting->value, $option->id); ?>><?= $option->value ?></option>
							<?php endforeach ?>
						</select>
					<?php endif; ?>
				</div>
			<?php elseif ($setting->jenis == 'option-kode'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
						<?php foreach ($setting->options as $option): ?>
						<option value="<?= $option->kode ?>" <?= selected($setting->value, $option->kode); ?>><?= $option->value ?></option>
						<?php endforeach ?>
					</select>
				</div>
			<?php elseif ($setting->jenis == 'option-value'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" id="<?= $setting->key ?>" name="<?= $setting->key?>">
						<?php foreach ($setting->options as $option): ?>
						<option value="<?= $option->value ?>" <?= selected($setting->value, $option->value); ?>><?= $option->value ?></option>
						<?php endforeach ?>
					</select>
				</div>
			<?php elseif ($setting->key == 'timezone'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" name="<?= $setting->key?>" >
						<option value="Asia/Jakarta" <?= selected($setting->value, 'Asia/Jakarta'); ?>>Asia/Jakarta</option>
						<option value="Asia/Makassar" <?= selected($setting->value, 'Asia/Makassar'); ?>>Asia/Makassar</option>
						<option value="Asia/Jayapura" <?= selected($setting->value, 'Asia/Jayapura'); ?>>Asia/Jayapura</option>
					</select>
				</div>
			<?php elseif ($setting->key == 'sumber_gambar_slider'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" id="<?= $setting->key?>" name="<?= $setting->key?>">
						<option value="1" <?= selected($setting->value, 1); ?>>Gambar utama artikel terbaru</option>
						<option value="2" <?= selected($setting->value, 2); ?>>Gambar utama artikel terbaru yang masuk ke slider atas</option>
						<option value="3" <?= selected($setting->value, 3); ?>>Gambar dalam album galeri yang dimasukkan ke slider</option>
					</select>
				</div>
			<?php elseif ($setting->jenis == 'boolean'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" id="<?= $setting->key?>" name="<?= $setting->key?>">
					<option value="1" <?= selected($setting->value, 1); ?>>Ya</option>
						<option value="0" <?= selected($setting->value, 0); ?>>Tidak</option>
					</select>
				</div>
			<?php elseif ($setting->key == 'web_theme'): ?>
				<div class="col-sm-12 col-md-4">
					<select class="form-control input-sm" name="<?= $setting->key?>" >
						<?php foreach ($list_tema as $tema): ?>
							<option value="<?= $tema?>" <?= selected($setting->value, $tema); ?>><?= $tema?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php elseif ($setting->jenis == 'datetime'): ?>
				<div class="col-sm-12 col-md-4">
					<div class="input-group input-group-sm date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input class="form-control input-sm pull-right tgl_1" id="<?= $setting->key?>" name="<?= $setting->key?>" type="text" value="<?= $setting->value?>">
					</div>
				</div>
			<?php elseif ($setting->jenis == 'textarea'): ?>
				<div class="col-sm-12 col-md-4">
					<textarea <?= jecho($setting->kategori, 'readonly', 'disabled'); ?> class="form-control input-sm" name="<?= $setting->key?>" placeholder="<?= $setting->keterangan?>" rows="5"><?= $setting->value; ?> </textarea>
				</div>
			<?php else : ?>
				<div class="col-sm-12 col-md-4">
					<input id="<?= $setting->key?>" name="<?= $setting->key?>" class="form-control input-sm <?php ($setting->jenis != 'int') || print 'digits'?>" type="text" value="<?= $setting->value?>" <?= jecho($setting->kategori, 'readonly', 'disabled'); ?>></input>
				</div>
			<?php endif; ?>
			<label class="col-sm-12 col-md-5 pull-left" for="nama"><?= $setting->keterangan?></label>
		</div>
	<?php endif; ?>
<?php endforeach; ?>
