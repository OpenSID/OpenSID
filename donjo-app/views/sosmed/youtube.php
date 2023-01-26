<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="col-sm-3 control-label">Link Akun Youtube</label>
				<div class="col-sm-9">
					<!-- pattern/regex: https://regex101.com/r/EipVTZ/3 -->
					<input id="link" pattern="^((https?:\/\/)?(www\.)?youtube\.com\/((channel\/[\w-]+)|@[\w-]+)|@[\w-]+|[\w-]+)" name="link" class="form-control input-lg" placeholder="UCvZuSYtrWYuE8otM4SsdT0Q" value="<?= ($main ? $main['link'] : '') ?>" />
					<small class="form-text text-muted">
						Contoh : https://www.youtube.com/channel/UCvZuSYtrWYuE8otM4SsdT0Q
						<br>atau UCvZuSYtrWYuE8otM4SsdT0Q
						<br>atau https://www.youtube.com/@KomunitasOpenSID-OpenDesa
						<br>atau @KomunitasOpenSID-OpenDesa
					</small>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
				<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
					<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($main['enabled'], '1', 'active') ?>">
						<input id="g1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?= selected($main['enabled'], '1', true) ?> autocomplete="off"> Aktif
					</label>
					<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?= jecho($main['enabled'], '3', 'active') ?>">
						<input id="g2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?= selected($main['enabled'], '2', true) ?> autocomplete="off"> Tidak Aktif
					</label>
				</div>
			</div>
		</div>
	</div>
</div>