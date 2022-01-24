<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="col-sm-3 control-label">Link Akun Instagram</label>
				<div class="col-sm-9">
					<!-- pattern/regex: https://regex101.com/r/kppBa2/1 -->
					<input id="link" pattern="^((?:http|https):\/\/(?:|www).instagram.com\/[a-zA-Z0-9._]+|(?:@)[a-zA-Z0-9._]+|[a-zA-Z0-9._]+)" name="link" class="form-control input-lg" placeholder="@OpenDesa" value="<?= ($main ? $main['link'] : '') ?>" />
					<small class="form-text text-muted">Contoh : https://www.instagram.com/OpenDesa, OpenDesa atau @OpenDesa</small>
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