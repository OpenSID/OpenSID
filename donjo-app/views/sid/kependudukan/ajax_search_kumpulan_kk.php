<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<style>
	.form-group
	{
		margin-bottom: 10px;
	}
</style>
<form method="post" action="<?= $form_action?>" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<label for="nama">Kumpulan KK</label>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<textarea class="form-control  input-sm kumpulan_nik_kk" maxlength="340" type="text" placeholder="Gunakan Pemisah koma (,)" id="kumpulan_kk" name="kumpulan_kk"  rows="4"><?= $kumpulan_kk?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>