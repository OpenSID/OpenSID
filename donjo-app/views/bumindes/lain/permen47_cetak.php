<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cetak Inventaris</h4>
			</div>
			<form target="_blank" class="form-horizontal" method="get">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan_pdf">Kepala Desa</label>
								<div class="col-sm-9">
									<select name="kades" id="kades" class="form-control input-sm">
										<?php foreach ($kades as $data) : ?>
											<option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['jabatan']) ?>" <?= selected($data['jabatan'], 'Kepala Desa') ?>>
												<?= $data['nama'] ?> (<?= $data['jabatan'] ?>)
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan_pdf">Sekretaris Desa</label>
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

				</div>
				<div class="modal-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak" name="form_cetak" data-dismiss="modal"><i class='fa fa-check'></i> Cetak</button>
				</div>
			</form>
		</div>
	</div>
</div>