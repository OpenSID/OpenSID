<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script src="<?= base_url()?>assets/js/script.js"></script>

<style type="text/css">
	.horizontal {
		padding-left: 0px;
		width: auto;
		padding-right: 30px;
	}
	.table-responsive
	{
		min-height:275px;
	}
	#atur_bagan .notif-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
	}
</style>

<form action="<?= $form_action?>" method="post" id="validasi" class="form-horizontal">
	<input type="hidden" name="list_id" value="">
	<div id="atur_bagan" class='modal-body'>
		<div class="pengurus alert notif-info">
		  <p>Perubahan pengaturan bagan di form ini akan dilakukan untuk setiap staf yang dipilih pada daftar Pemerintahan Desa.</p>
		  <p>Petunjuk pengisian:</p>
		  <ul>
		  	<li>Kosongkan kolom jika tidak akan diubah</li>
		  	<li>Isi nilai -1 jika akan dikembalikan ke nilai default</li>
		  </ul>
		</div>
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<div class="row">
						<div class="form-group">
							<label class="col-sm-3 control-label bagan" for="atasan">Atasan</label>
							<div class="col-sm-9">
								<select class="form-control select3 input-sm" name="atasan">
									<option value="">Pilih Atasan</option>
									<option value="-1">-- Tidak ada atasan (keluarkan dari bagan) --</option>
									<?php foreach ($atasan as $data): ?>
										<option value="<?= $data['id']?>" <?php selected($pamong['atasan'], $data['id']); ?>><?= $data['nama']?> (<?= $data['jabatan']?>)</option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label bagan" for="jabatan">Tingkat</label>
							<div class="col-sm-9">
								<input name="bagan_tingkat" class="form-control input-sm number" type="text" placeholder="Angka menunjukkan tingkat di bagan organisasi. Contoh: 2" value="<?= $pamong['bagan_tingkat']?>" ></input>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Warna</label>
							<div class="col-sm-9">
								<div class="input-group my-colorpicker2">
									<input type="text" name="bagan_warna" class="form-control input-sm" placeholder="#FFFFFF" value="">
									<div class="input-group-addon input-sm">
										<i></i>
									</div>
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

<script>
	$('document').ready(function()
	{
		var list_id = [];
		var list = $("input[name='id_cb[]']:checked");
		var i;
		for (i = 0; i < list.length; i++) {
		  list_id.push(list[i].value);
		}
		$("input[name='list_id']").val(list_id);
	});
</script>
