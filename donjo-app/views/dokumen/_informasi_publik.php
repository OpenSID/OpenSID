<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Kategori Publik</label>
	<div class="col-sm-6">
		<select name="attr[kategori_publik]" class="form-control select2 input-sm required">
			<option value="">Pilih Kategori Dokumen</option>
			<?php foreach($kategori as $s): ?>
				<option value="<?= $s['id'] ?>" <?php selected($s['id'], $kategori_dokumen) ?>><?= $s['nama'] ?></option>
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
