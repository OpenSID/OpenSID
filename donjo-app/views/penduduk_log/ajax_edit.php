<?php if (can('u')) : ?>
	<?php $this->load->view('global/validasi_form'); ?>
	<?php
    $sekarang = $log_status_dasar['tgl_peristiwa'] != '' ? $log_status_dasar['tgl_peristiwa'] : date('d-m-Y');
    ?>
	<form action="<?= $form_action ?>" method="post" id="validasi" class="tgl_lapor_peristiwa" enctype="multipart/form-data">
		<div class='modal-body'>
			<div class="box box-danger">
				<div class="box-body">
					<div class="form-group">
						<label>Status dasar penduduk</label>
						<label>: <?= $log_status_dasar['status'] ?></label>
					</div>
					<?php if ($log_status_dasar['kode_peristiwa'] == 2) : ?>
						<div class="form-group mati">
							<label for="meninggal_di">Tempat Meninggal</label>
							<input name="meninggal_di" class="form-control input-sm required" type="text" maxlength="50" placeholder="Tempat Meninggal" value="<?= $log_status_dasar['meninggal_di'] ?>"></input>
						</div>
						<div class="form-group mati">
							<label for="jam_mati">Jam Kematian</label>
							<div class="input-group input-group-sm ">
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								<input name="jam_mati" id="jammenit_1" class="form-control input-sm" type="text" maxlength="50" placeholder="Jam Kematian" value="<?= $log_status_dasar['jam_mati'] ?>"></input>
							</div>
						</div>
						<div class="form-group mati">
							<label for="sebab">Penyebab Kematian</label>
							<select id="sebab" name="sebab" class="form-control select2 input-sm required">
								<option value="">Pilih Penyebab Kematian</option>
								<?php foreach ($sebab as $key => $value) : ?>
									<option value="<?= $key ?>" <?= selected($key, $log_status_dasar['sebab']) ?>><?= $value ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="form-group mati">
							<label for="penolong_mati">Yang menerangkan kematian</label>
							<select id="penolong_mati" name="penolong_mati" class="form-control select2 input-sm required">
								<option value="">Pilih Yang menerangkan kematian</option>
								<?php foreach ($penolong_mati as $key => $value) : ?>
									<option value="<?= $key ?>" <?= selected($key, $log_status_dasar['penolong_mati']) ?>><?= $value ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="form-group mati">
							<label for="anak_ke">Anak Ke</label>
							<input name="anak_ke" class="form-control input-sm" type="number" min="1" max="20" placeholder="Anak Ke" value="<?= $log_status_dasar['anak_ke'] ?>"></input>
						</div>
						<div class="form-group mati">
							<label for="akta_mati">Nomor Akta Kematian</label>
							<input name="akta_mati" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor Akta Kematian" value="<?= $log_status_dasar['akta_mati'] ?>"></input>
						</div>
					<?php endif; ?>
					<div class="form-group mati">
						<label for="file">File Akta Kematian <code>(.jpg, .jpeg, .png, .pdf)</code></label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" id="file_path" name="satuan">
							<input type="file" class="hidden" id="file" name="nama_file" accept=".jpg,.jpeg,.png,.pdf">
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Cari</button>
							</span>
						</div>
						<span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong><?= max_upload() ?> MB</strong>.</code></span>
					</div>
					<?php if ($log_status_dasar['kode_peristiwa'] == 3) : ?>
						<div class="form-group pindah">
							<label for="ref_pindah">Tujuan Pindah</label>
							<select name="ref_pindah" class="form-control select2 input-sm required">
								<option value="">Pilih Tujuan Pindah</option>
								<?php foreach ($list_ref_pindah as $data) : ?>
									<option value="<?= $data['id'] ?>" <?= selected($data['id'], $log_status_dasar['ref_pindah']) ?>><?= $data['nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="alamat_tujuan">Alamat Tujuan</label>
							<textarea id="alamat_tujuan" name="alamat_tujuan" class="form-control input-sm required" placeholder="Alamat Tujuan" rows="5"><?= $log_status_dasar['alamat_tujuan']; ?></textarea>
						</div>
					<?php endif; ?>
					<?php if ($log_status_dasar['kode_peristiwa'] == 5) : ?>
						<div class="form-group">
							<label for="alamat_sebelumnya">Alamat Sebelumnya</label>
							<textarea id="alamat_sebelumnya" name="alamat_sebelumnya" class="form-control input-sm required" placeholder="Alamat Sebelumnya" rows="5"><?= $log_status_dasar['alamat_sebelumnya']; ?></textarea>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label for="tgl_peristiwa">Tanggal Peristiwa</label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="<?= $log_status_dasar['tgl_peristiwa']; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="tgl_lapor">Tanggal Lapor</label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="<?= $log_status_dasar['tgl_lapor']; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="catatan">Catatan Peristiwa</label>
						<textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" rows="5" style="resize:none;"><?= $log_status_dasar['catatan'] ?></textarea>
						<span class="help-block"><code>*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</code></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		$('#tgl_1').datetimepicker({
			format: 'DD-MM-YYYY',
			locale: 'id'
		});

		$('#tgl_lapor').datetimepicker({
			format: 'DD-MM-YYYY',
			locale: 'id'
		});

		setTimeout(function() {
			$("#tgl_lapor").rules('add', {
				tgl_lebih_besar: "input[name='tgl_peristiwa']",
				messages: {
					tgl_lebih_besar: "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa."
				}
			})
		}, 500);
	</script>
<?php endif; ?>