<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-outline card-danger">
					<div class="card-body">
						<div class="form-group">
							<label class="control-label" for="nama_grup">Nama Group</label>
							<input name="nama_grup" class="form-control form-control-sm required" type="text" value="<?=$grup['nama_grup']?>" />
							<input name="nama_grup_awal" type="hidden" value="<?=$grup['nama_grup']?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-flat btn-danger btn-xs" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-flat btn-info btn-xs" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
