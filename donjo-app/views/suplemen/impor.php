<?php if ($this->CI->cek_hak_akses('u')): ?>
	<div class="modal fade" id="impor">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Impor Data Suplemen</h4>
				</div>
				<form id="mainform" action="<?= site_url('suplemen/impor'); ?>" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label for="file" class="control-label">File Data Suplemen : </label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="userfile" required>
								<input type="file" class="hidden" id="file" name="userfile">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<input type="hidden" id="id_suplemen" name="id_suplemen">
							<label>Data yang dimasukkan adalah data yang baru</label>
							<br/>
							<br/>
							<a href="<?= base_url('assets/import/format_impor_suplemen.xlsx'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor Data Suplemen</a>
						</div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>