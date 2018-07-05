<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group">
					<div class="col-sm-12">
						<label for="nama">Rentang Umur</label>
					</div>
					<div class="col-xs-6">
						<input class="form-control input-sm required" type="text" placeholder="Dari" id="dari" name="dari" value="<?= $rentang['dari']?>"></input>
					</div>
					<div class="col-xs-6">
						<input id="sampai" class="form-control input-sm required" type="text" placeholder="Sampai" name="sampai" value="<?= $rentang['sampai']?>"></input>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-times'></i>  Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>