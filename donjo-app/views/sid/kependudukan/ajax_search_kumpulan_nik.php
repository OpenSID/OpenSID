<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<!-- TODO: Pindahkan ke external css -->
<style>
	.form-group
	{
		margin-bottom: 10px;
	}
</style>
<form method="post" action="<?= $form_action?>" id="validasi">
	<div class='modal-body'>
		<div class="box box-danger">
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<label for="nama">Kumpulan NIK</label>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<textarea class="form-control  input-sm kumpulan_nik_kk" maxlength="340" type="text" placeholder="Isi maks 20 NIK, dengan pemisah koma (,). Contoh: 5201142005716996, 5201144609786995" name="kumpulan_nik"  rows="4"><?= $kumpulan_nik?></textarea>
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