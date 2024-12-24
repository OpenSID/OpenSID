<form action="<?= $form_action ?>" method="post" id="validasi">
	<div class="modal-body">
		<div class="form-group">
			<label for="hp">Telepon Tujuan</label>
			<select class="form-control input-sm select2 required" id="DestinationNumber" name="DestinationNumber" style="width:100%;">
				<option option value="">-- Silakan Cari Telepon Tujuan --</option>
				<?php foreach ($kontakPenduduk as $penduduk): ?>
					<option value="<?= $penduduk->telepon ?>">Penduduk : <?= $penduduk->nama . ' - ' . $penduduk->telepon ?></option>
				<?php endforeach; ?>
				<?php foreach ($kontakEksternal as $eksternal): ?>
					<option value="<?= $eksternal->telepon ?>">Eksternal : <?= $eksternal->nama . ' - ' . $eksternal->telepon ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" for="pesan">Isi Pesan</label>
			<textarea id="TextDecoded" name="TextDecoded" class="form-control input-sm required" placeholder="Isi Pesan" rows="5"></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-envelope-o"></i> Kirim</button>
	</div>
</form>
<?php $this->load->view('global/validasi_form'); ?>