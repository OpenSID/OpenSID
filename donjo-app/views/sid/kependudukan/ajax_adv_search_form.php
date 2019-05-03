
<style>
	.form-group
	{
    margin-bottom: 10px;
	}
</style>
<form method="post" action="<?= $form_action?>">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="nama">Umur</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input class="form-control  input-sm" type="text" placeholder="Dari" id="umur_min" name="umur_min"  value="<?= $umur_min?>"></input>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input id="umur_max" class="form-control  input-sm" type="text" placeholder="Sampai" name="umur_max" value="<?= $umur_max?>"></input>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="status_dasar">Pekerjaan</label>
									<select class="form-control input-sm"  id="pekerjaan_id"  name="pekerjaan_id">
										<option value=""> -- </option>
										<?php foreach ($pekerjaan AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pekerjaan_id,$data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="status_dasar">Status Perkawinan</label>
									<select class="form-control input-sm"  id="status"  name="status" >
										<option value=""> -- </option><option value="1">BELUM KAWIN</option><option value="2">KAWIN</option><option value="3">CERAI HIDUP</option><option value="4">CERAI MATI</option><option value="5">TIDAK KAWIN</option>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="agama">Agama</label>
									<select class="form-control  input-sm"  id="agama"  name="agama" >
										<option value=""> -- </option>
										<?php foreach ($list_agama AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($agama,$data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh</label>
									<select class="form-control  input-sm"  id="pendidikan_sedang_id"  name="pendidikan_sedang_id" >
										<option value=""> -- </option>
										<?php foreach ($pendidikan AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pendidikan_sedang_id,$data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
									<select class="form-control  input-sm"  id="pendidikan_kk_id"  name="pendidikan_kk_id" >
										<option value=""> -- </option>
										<?php foreach ($pendidikan_kk AS $data): ?>
											<option value="<?= $data['id']?>" <?php selected($pendidikan_kk_id,$data['id']); ?> ><?= $data['nama']?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="status_penduduk">Status Penduduk</label>
									<select class="form-control input-sm"  id="status_penduduk"  name="status_penduduk">
										<option value=""> -- </option><option value="1">AKTIF</option><option value="2">TIDAK AKTIF</option>
									</select>
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
