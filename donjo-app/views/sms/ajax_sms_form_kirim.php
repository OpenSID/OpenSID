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
							<label for="hp">No HP Tujuan</label>
							<select class="form-control input-sm select2 required" id="DestinationNumber" name="DestinationNumber" style="width:100%;">
								<option option value="">-- Silakan Cari No HP Tujuan --</option>
								<?php foreach ($kontak as $data): ?>
									<option value="<?=$data['no_hp']?>">NIK :<?=$data['id_kontak']." - ".$data['nama']." - ".$data['no_hp']?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label" for="pesan">Isi Pesan</label>
							<textarea id="TextDecoded" name="TextDecoded" class="form-control input-sm required" placeholder="Isi Pesan"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-envelope-o'></i> Kirim</button>
		</div>
	</div>
</form>
