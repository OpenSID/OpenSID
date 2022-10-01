<?php if ($this->CI->cek_hak_akses('u')) : ?>
	<?php $this->load->view('global/validasi_form'); ?>
	<?php foreach ($pengaduana as $key => $value) : ?>
		<?php if (empty($value['id_pengaduan'])) : ?>
			<div class="modal-body">
				<form role="form">
					<!-- text input -->
					<div class="form-group">
						<label>NIK</label>
						<input type="text" class="form-control" disabled value="<?= $value['nik']; ?>">
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" class="form-control" disabled value="<?= $value['nama']; ?>">
					</div>

					<div class="form-group">
						<label>Email</label>
						<input type="text" class="form-control" disabled value="<?= $value['email']; ?>">
					</div>

					<div class="form-group">
						<label>Telepon</label>
						<input type="text" class="form-control" disabled value="<?= $value['telepon']; ?>">
					</div>

					<div class="form-group">
						<label>Judul</label>
						<input type="text" class="form-control" disabled value="<?= $value['judul']; ?>">
					</div>

					<div class="form-group">
						<label>Tanggal</label>
						<input type="text" class="form-control" disabled value="<?= $value['created_at']; ?>">
					</div>

					<div class="form-group">
						<label>Isi</label>
						<textarea class="form-control" rows="5" disabled><?= $value['isi']; ?></textarea>
					</div>

					<div class="form-group">
						<?php if ($value['foto']) : ?>
							<label>Gambar</label><br>
							<img class="img-responsive" src="<?= base_url(LOKASI_PENGADUAN . $value['foto']); ?>">
						<?php endif; ?>
					</div>

					<?php foreach ($pengaduana as $keyna => $valuena) : ?>
						<?php if ($valuena['id_pengaduan'] && $valuena['id_pengaduan'] == $value['id']) : ?>
							<hr>
							<div class="row support-content-comment">
								<div class="col-md-12">
									<p>Ditanggapi oleh <a href="#"><?= $valuena['nama'] ?></a> | <?= $valuena['created_at'] ?></p>
									<p><?= $valuena['isi'] ?></p>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endforeach ?>
<?php endif; ?>