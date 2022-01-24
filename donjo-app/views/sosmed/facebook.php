<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="col-sm-3 control-label">Tipe</label>
				<div class="col-sm-9">
					<select class="form-control input-sm required" id="tipe" name="tipe" style="width:50%" ; onchange="ubah_pesan(this.value);">
						<option value="1" <?= selected($main['tipe'], '1') ?>>Personal / Halaman</option>
						<option value="2" <?= selected($main['tipe'], '2') ?>>Group</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Link Username Facebook</label>
				<div class="col-sm-9">
					<!-- pattern/regex: https://regex101.com/r/Qz85JL/3 -->
					<input id="link" name="link" pattern="^((?:https?:\/\/)?(?:|web\.|m\.|www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:[\w\-]*\/)*([\w\-\.]*)|\w+)" class="form-control input-lg" value="<?= ($main ? $main['link'] : '') ?>" />
					<small class="form-text text-muted" id="ex_facebook"></small>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
				<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
					<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($main['enabled'], '1', 'active') ?>">
						<input id="g1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?= selected($main['enabled'], '1', true) ?> autocomplete="off" /> Aktif
					</label>
					<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($main['enabled'], '2', 'active') ?>">
						<input id="g2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?= selected($main['enabled'], '2', true) ?> autocomplete="off" /> Tidak Aktif
					</label>
				</div>
			</div>
		</div>
	</div>
</div>