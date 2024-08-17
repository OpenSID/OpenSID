<?php if (can('u')) : ?>
	<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action ?>" method="post" id="validasi">
		<div class="modal-body">
			<input type="hidden" name="id_kk" value="<?= $id_kk ?>">
			<div class=" form-group">
				<label for="dusun"><?= ucwords($this->setting->sebutan_dusun) ?> </label>
				<select id="dusun" name="dusun" class="form-control input-sm required">
					<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun) ?></option>
					<?php foreach ($dusun as $data) : ?>
						<option value="<?= $data['dusun'] ?>" <?= selected($kk['dusun'], $data['dusun']) ?>><?= set_ucwords($data['dusun']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div id="isi_rw" class="form-group">
				<label for="rw">RW</label>
				<select id="rw" name="rw" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rw/') ?>" data-valueKey="rw" data-displayKey="rw">
					<option class="placeholder" value="">Pilih RW</option>
					<?php foreach ($rw as $data) : ?>
						<option value="<?= $data['rw'] ?>" <?= selected($kk['rw'], $data['rw']) ?>><?= $data['rw'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div id="isi_rt" class="form-group">
				<label for="rt">RT</label>
				<select id="id_cluster" name="id_cluster" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rt/') ?>" data-valueKey="id" data-displayKey="rt">
					<option class="placeholder" value="">Pilih RT </option>
					<?php foreach ($rt as $data) : ?>
						<option value="<?= $data['id'] ?>" <?= selected($kk['id_cluster'], $data['id']) ?>><?= $data['rt'] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</form>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#isi_rt').hide();
			$('#isi_rw').hide();

			$("#dusun").change(function() {
				let dusun = $(this).val();
				$('#isi_rt').hide();

				if (dusun) {
					var rw = $('#rw');
					$('#isi_rw').show();
					select_options(rw, urlencode(dusun));
				} else {
					$('#isi_rw').hide();
				}
			});

			$("#rw").change(function() {
				let dusun = $("#dusun").val();
				let rw = $(this).val();

				if (dusun && rw) {
					var rt = $('#id_cluster');
					var params = urlencode(dusun) + '/' + urlencode(rw);
					$('#isi_rt').show();
					select_options(rt, params);
				} else {
					$('#isi_rt').hide();
				}
			});
		});
	</script>
<?php endif; ?>