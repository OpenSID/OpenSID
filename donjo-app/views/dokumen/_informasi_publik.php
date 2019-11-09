<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Kategori Informasi Publik</label>
	<div class="col-sm-6">
		<select name="attr[kategori_publik]" class="form-control select2 input-sm required">
			<option value="">Pilih Kategori Informasi Publik</option>
			<?php foreach ($list_kategori_publik AS $key => $value): ?>
				<option value="<?= $key ?>" <?php selected($dokumen['attr']['kategori_publik'], $key) ?>><?= $value ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tahun</label>
	<div class="col-sm-6">
		<input name="tahun" class="form-control input-sm required" type="text" value="<?=$dokumen['tahun']?>"></input>
	</div>
</div>
