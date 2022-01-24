<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="col-sm-3 control-label">Link Akun Twitter</label>
				<div class="col-sm-9">
					<!-- patern/regex: https://regex101.com/r/JNylMq/5 -->
					<input id="link" pattern="^(?:|@|(https?:\/\/)?(|www|mobile|m)[.]?(twitter\.com)\/)[a-zA-Z0-9_]{4,15}$" name="link" class="form-control input-lg" placeholder="@opendesa" value="<?= ($main ? $main['link'] : '') ?>" />
					<small class="form-text text-muted">Contoh : https://twitter.com/opendesa, opendesa atau @opendesa</small>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
				<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
					<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label<?= ($main['enabled'] === '1' ? ' active' : '') ?>">
						<input id="g1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?= ($main['enabled'] === '1' ? 'checked' : '') ?> autocomplete="off"> Aktif
					</label>
					<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label<?= ($main['enabled'] === '2' ? ' active' : '') ?>">
						<input id="g2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?= ($main['enabled'] === '2' ? 'checked' : '') ?> autocomplete="off"> Tidak Aktif
					</label>
				</div>
			</div>
		</div>
	</div>
</div>