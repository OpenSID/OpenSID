<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<script>
		$(function () {
			$('.select2').select2()
		})
	</script>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="form-group">
				<label for="nik">Kepala Rumah Tangga</label>
				<select class="form-control input-sm select2 required"  id="nik_kepala" name="nik_kepala" style="width:100%;">
					<option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
					<?php foreach ($penduduk as $data): ?>
						<option value="<?= $data['id']?>">NIK :<?= $data['nik'] . ' - ' . $data['nama']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
				Silakan cari nama / NIK dari data penduduk yang sudah terinput.
				Penduduk yang dipilih otomatis berstatus sebagai Kepala Rumah Tangga baru tersebut.
			</p>

			<div class="form-group">
				<label for="bdt">BDT</label>
				<input class="form-control input-sm angka" type="text" placeholder="BDT" name="bdt" value="<?= $kk['bdt']; ?>" minlength="16" maxlength="16"/>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>

