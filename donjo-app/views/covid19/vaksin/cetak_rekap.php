<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label class=" control-label " style="text-align:left;" for="penandatangan_pdf">Tanda Tangan</label>
			<div class="">
				<select name="sekdes" id="sekdes" class="form-control input-sm">
					<?php foreach ($sekdes as $data) : ?>
						<option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['jabatan']) ?>" <?= selected($data['jabatan'], 'Sekretaris Desa') ?>>
							<?= $data['nama'] ?> (<?= $data['jabatan'] ?>)
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<label class="control-label" style="text-align:left;" for="penandatangan_pdf">Rentang Umur</label>
			<div class="">
				<input name="umur" id="umur" class="form-control input-sm" placeholder="Masukan rentang umur" title="Contoh : 20-30" type="text" autocomplete="off">
			</div>
		</div>
	</div>
</div>