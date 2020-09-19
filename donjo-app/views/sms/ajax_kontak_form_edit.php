<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script>
	$(function ()
	{
		$('.select2').select2()
	})
</script>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="nama">Nama</label>
							<input name="no_hp" class="form-control input-sm" type="text" value="<?=$kontak['nama'];?>" disabled=""></input>
							<input name="id_kontak" type="hidden" value="<?=$kontak['id_kontak']?>"></input>
						</div>
						<div class="form-group">
							<label class="control-label" for="hp">No HP</label>
							<input name="no_hp" class="form-control input-sm required bilangan" minlength="8" maxlength="15" type="text" value="<?=$kontak['no_hp']?>"></input>
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
