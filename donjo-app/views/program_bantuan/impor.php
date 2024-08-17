<?php if (can('u')): ?>
	<div class="modal fade" id="impor">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Impor Program Bantuan</h4>
				</div>
				<form id="mainform" action="<?= site_url('program_bantuan/impor'); ?>" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label for="file" class="control-label">File Program Bantuan : </label>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" name="userfile" required>
								<input type="file" class="hidden" id="file" name="userfile"  accept=".xls,.xlsx,.xlsm">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
							<br/>
							<label class="control-label">Impor Program :
								<p class="help-block">&emsp;<input type="checkbox" name="ganti_program" value="1"/> Ganti data lama jika data ditemukan sama</p>
							</label>
							<br/>
							<label class="control-label">Impor Peserta :
								<p class="help-block">&emsp;<input type="checkbox" name="kosongkan_peserta" value="1"/> Kosongkan data peserta program bantuan</p>
								<p class="help-block">&emsp;<input type="checkbox" name="ganti_peserta" value="1"/> Ganti data lama jika data ditemukan sama</p>
								<p class="help-block">&emsp;<input type="checkbox" name="rand_kartu_peserta" value="1"/> Acak No. Kartu Peserta Jika Kosong</p>
							</label>
							<br/>
							<a target="_blank" href="<?= site_url('unduh/' . encrypt('assets/import/format_impor_program_bantuan.xlsx')) ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor Program Bantuan</a>
						</div>
					</div>
					<div class="modal-footer">
						<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif ?>
