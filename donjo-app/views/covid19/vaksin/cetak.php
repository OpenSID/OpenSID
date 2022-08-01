<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label class="col-sm-3 control-label required" style="text-align:left;" for="penandatangan_pdf">Tanda Tangan</label>
			<div class="col-sm-9">
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
</div>