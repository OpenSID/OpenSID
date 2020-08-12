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
								<label for="nama">Umur</label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input class="form-control  input-sm bilangan" maxlength="3" type="text" placeholder="Dari" id="umur_min" name="umur_min"  value="<?= $umur_min?>"></input>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input id="umur_max" class="form-control input-sm bilangan" maxlength="3" type="text" placeholder="Sampai" name="umur_max" value="<?= $umur_max?>"></input>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="pekerjaan">Pekerjaan</label>
									<select class="form-control input-sm" id="pekerjaan_id" name="pekerjaan_id">
										<option value=""> -- </option>
										<?php foreach ($list_pekerjaan AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pekerjaan_id, $data['id']); ?>><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="status_dasar">Status Perkawinan</label>
									<select class="form-control input-sm" id="status" name="status">
										<option value=""> -- </option>
										<?php foreach ($list_status_kawin AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($status, $data['id']); ?>><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="agama">Agama</label>
									<select class="form-control input-sm" id="agama" name="agama">
										<option value=""> -- </option>
										<?php foreach ($list_agama AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($agama, $data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh</label>
									<select class="form-control input-sm" id="pendidikan_sedang_id"  name="pendidikan_sedang_id">
										<option value=""> -- </option>
										<?php foreach ($list_pendidikan AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pendidikan_sedang_id, $data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
									<select class="form-control input-sm" id="pendidikan_kk_id" name="pendidikan_kk_id">
										<option value=""> -- </option>
										<?php foreach ($list_pendidikan_kk AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pendidikan_kk_id, $data['id']); ?>><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="status_penduduk">Status Penduduk</label>
									<select class="form-control input-sm" id="status_penduduk" name="status_penduduk">
										<option value=""> -- </option>
										<?php foreach ($list_status_penduduk AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($status_penduduk, $data['id']); ?>><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
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
