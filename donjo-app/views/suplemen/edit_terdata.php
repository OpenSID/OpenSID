<form action="<?= $form_action?>" method="post" id="validasi" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<table class="table table-bordered table-striped table-hover" >
							<tbody>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" ><?= $judul_terdata_nama?></td>
									<td> : <?= $terdata_nama?></td>
								</tr>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" ><?= $judul_terdata_info?></td>
									<td> :  <?= $terdata_info?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="keterangan">Keterangan</label>
							<input type="hidden" name="id_suplemen" value="<?= $id_suplemen?>"/>
							 <textarea name="keterangan" id="keterangan" class="form-control input-sm" maxlength="100" placeholder="Keterangan"  rows="3"><?= $keterangan?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
