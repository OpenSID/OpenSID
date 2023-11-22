<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="nama">Nama Kategori</label>
							<select class="form-control input-sm required"  id="kategori" name="kategori" style="width:100%;">
								<option option value="">-- Pilih Kategori --</option>
								<?php foreach ($list_kategori as $kategori) : ?>
									<option <?= selected($kategori_sekarang['id_kategori'], $kategori['id']) ?> value="<?= $kategori['id']?>"><?= $kategori['kategori'] ?></option>
									<?php foreach ($kategori['submenu'] as $sub_kategori) : ?>
										<option <?= selected($kategori_sekarang['id_kategori'], $sub_kategori['id']) ?> value="<?= $sub_kategori['id']?>">&emsp;<?= $sub_kategori['kategori'] ?></option>
									<?php endforeach; ?>
								<?php endforeach; ?>
							</select>
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
