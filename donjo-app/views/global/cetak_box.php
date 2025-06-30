		<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">
							<span class="aksi">Cetak</span>
							<span class="title">Buku Tanah di Desa</span>
						</h4>
					</div>
					<form id="cetak_form" target="_blank" class="form-horizontal" method="post">
						<div class="modal-body">
							<div class="form-group">
								<div class="container-fluid">
									<label class="control-label required" for="tgl_cetak">
										<span>Tanggal </span>
										<span class="aksi">Cetak</span>
									</label>
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm required" id="tgl_1" name="tgl_cetak" type="text" value="<?= date('d-m-Y'); ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"
								data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak"
								name="form_cetak" onclick="$('#cetak_form').submit();" data-dismiss="modal"><i class='fa fa-check'></i> <span class="aksi">Cetak</span></button>
						</div>
					</form>
				</div>
			</div>
		</div>
