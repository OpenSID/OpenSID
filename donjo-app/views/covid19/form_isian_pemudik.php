<div class="form-group">
	<label class="col-sm-3 control-label" for="pantau">Pantau Warga</label>
	<div class="col-sm-8">
		<select class="form-control input-sm required" name="pantau" id="pantau" onchange="pemudik(this.value);">
			<option value="1" <?= selected($pantau, '1'); ?>>Warga Pemudik</option>
			<option value="2" <?= selected($pantau, '2'); ?>>Warga <?= ucwords($this->setting->sebutan_desa) ?></option>
		</select>
	</div>
</div>

<div class="pemudik">
	<div class="form-group">
		<label for="asal_pemudik" class="col-sm-3 control-label">Asal Pemudik (kota) / Tiba Tanggal</label>
		<div class="col-sm-4">
			<input class="form-control input-sm required" type="text" name="asal_pemudik" id="asal_pemudik" value="<?= $asal_mudik; ?>" placeholder="Kota">
		</div>

		<div class="col-sm-4">
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" class="form-control input-sm pull-right required" id="tanggal_tiba" name="tanggal_tiba" value="<?= $tanggal_datang; ?>">
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="tujuan_pemudik" class="col-sm-3 control-label">Tujuan Mudik / Durasi Mudik</label>
		<div class="col-sm-4">
			<select class="form-control input-sm" name="tujuan_pemudik" id="tujuan_pemudik">
				<option value="">-- Pilih Tujuan Mudik --</option>
				<?php foreach ($select_tujuan_mudik as $id => $nama): ?>
				<option value="<?= $id?>" <?= selected($tujuan_mudik, $nama); ?> > <?= strtoupper($nama)?> </option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm number" type="text" name="durasi_pemudik" id="durasi_pemudik" value="<?= $durasi_mudik; ?>" placeholder="Jumlah Hari (angka)">
		</div>
	</div>

	<div class="form-group">
		<label for="hp_pemudik" class="col-sm-3 control-label">Kontak Pemudik (HP/Email)</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" name="hp_pemudik" id="hp_pemudik" value="<?= $no_hp; ?>" placeholder="No HP">
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" name="email_pemudik" id="email_pemudik" value="<?= $email; ?>" placeholder="Email">
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="status_covid">Status Covid-19</label>
	<div class="col-sm-8">
		<select class="form-control input-sm required" name="status_covid" id="status_covid">
			<option value="">-- Pilih Status Covid-19 --</option>
			<?php foreach ($select_status_covid as $covid): ?>
				<option value="<?= $covid['id']; ?>" <?= selected($covid_id, $covid['id']); ?>><?= $covid['nama']; ?> </option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="wajib_pantau">Apakah Wajib Dipantau</label>
	<div class="col-sm-8">
		<select class="form-control input-sm" name="wajib_pantau" id="wajib_pantau">
			<option value="1" <?= selected($is_wajib_pantau, '1'); ?> >Ya</option>
			<option value="0" <?= selected($is_wajib_pantau, '0'); ?> >Tidak</option>
		</select>
		<span id="wajib_pantau_plus_msg" class="help-block">
			<code>Jika ya, daftar warga ini masuk dalam daftar warga yang dipantau di menu Pemantauan</code>
		</span>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="keluhan">Keluhan Kesehatan</label>
	<div class="col-sm-8">
		<textarea name="keluhan" id="keluhan" class="form-control input-sm" placeholder="Keluhan Kesehatan" rows="5" style="resize:none;"><?= $keluhan_kesehatan; ?></textarea>
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
	<div class="col-sm-8">
		<textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan" rows="5" style="resize:none;"><?= $keterangan; ?></textarea>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		//https://momentjs.com/docs/#/parsing/string-format/
		$('#tanggal_tiba').datetimepicker( {
			format: 'YYYY-MM-DD'
		});

		$("#pantau").change();
	});

	function pemudik(asal) {
		if (asal == 1) {
			$('.pemudik').show();
			$('#asal_pemudik').addClass('required');
		} else {
			$('.pemudik').hide();
			$('#asal_pemudik').removeClass('required');
		}
	}
</script>