<form id="form_penduduk" action="<?= $form_action_penduduk ?>" method="POST">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="nik">NIK</label>
			<input name="nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK" value="<?= $penduduk['nik']?>"></input>
			<input name="nik_lama" type="hidden" value="<?= $_SESSION['nik_lama']?>"/>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="i_nama">Nama Lengkap</label>
			<input type="text" class="form-control input-sm required nama" name="nama" placeholder="Nama Lengkap Tanpa Gelar" value="<?= strtoupper($penduduk['nama'])?>">
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="sex">Jenis Kelamin</label>
			<select class="form-control input-sm required" name="sex" >
				<option value="">-- Pilih Jenis Kelamin --</option>
				<?php foreach ($jenis_kelamin as $data): ?>
					<option <?php selected($penduduk['id_sex'], $data['id']); ?> value="<?= $data['id']?>"> <?= strtoupper($data['nama'])?> </option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="agama_id">Agama</label>
			<select class="form-control input-sm required" name="agama_id" >
				<option value="">-- Pilih Agama --</option>
				<?php foreach ($agama as $data): ?>
					<option <?php selected($penduduk['agama_id'], $data['id']); ?> value="<?= $data['id']?>"> <?= strtoupper($data['nama'])?> </option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="tempatlahir">Tempat Lahir</label>
			<input type="text" class="form-control input-sm" name="tempatlahir" placeholder="Tempat Lahir" value="<?= strtoupper($penduduk['tempatlahir'])?>">
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="tanggallahir">Tanggal Lahir</label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right" id="tanggallahir" name="tanggallahir" type="text" value="<?= $penduduk['tanggallahir']?>">
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="golongan_darah_id">Golongan Darah</label>
			<select class="form-control input-sm required" name="golongan_darah_id">
				<option value="">Pilih Golongan Darah</option>
				<?php foreach ($golongan_darah as $data): ?>
					<option <?php selected($penduduk['golongan_darah_id'], $data['id']); ?> value="<?= $data['id']?>"> <?= strtoupper($data['nama'])?> </option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="status">Status Penduduk </label>
			<select class="form-control input-sm required" name="status">
				<?php foreach ($status_penduduk as $data): ?>
					<?php if ($data['id'] != '1'): ?>
						<option <?php selected($penduduk['id_status'], $data['id']); ?> value="<?= $data['id']?>"> <?= strtoupper($data['nama'])?> </option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="col-sm-12">
		<div class="form-group">
			<label for="alamat">Alamat</label>
			<textarea name="alamat_sekarang" class="form-control input-sm" rows="2"><?= strtoupper($penduduk['alamat_sekarang'])?></textarea>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="form-group">
			<label> <?= ucwords($this->setting->sebutan_dusun)?> </label>
			<select id="dusun" name="dusun" class="form-control input-sm required">
				<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
				<?php foreach ($dusun as $data): ?>
					<option <?php selected($penduduk['dusun'], $data['dusun']) ?> value="<?= $data['dusun']?>"> <?= $data['dusun']?> </option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="form-group">
			<label>RW</label>
			<select
			id="rw"
			class="form-control input-sm required"
			name="rw"
			data-source="<?= site_url()?>wilayah/list_rw/"
			data-valueKey="rw"
			data-displayKey="rw" >
				<option class="placeholder" value="">Pilih RW</option>
				<?php foreach ($rw as $data): ?>
					<option <?php selected($penduduk['rw'], $data['rw']) ?> value="<?= $data['rw']?>"> <?= $data['rw']?> </option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="col-sm-4">
		<div id='isi_rt' class="form-group">
			<label>RT</label>
			<select
			id="id_cluster"
			class="form-control input-sm required"
			name="id_cluster"
			data-source="<?= site_url()?>wilayah/list_rt/"
			data-valueKey="id"
			data-displayKey="rt">
				<option class="placeholder" value="">Pilih RT </option>
				<?php foreach ($rt as $data): ?>
					<option <?php selected($penduduk['id_cluster'], $data['id']) ?> value="<?= $data['id']?>"> <?= $data['rt']?> </option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

</form>

<script type="text/javascript">
	$(document).ready(function()
	{
		$("#dusun").change(function()
		{
			let dusun = $(this).val();
			$('#isi_rt').hide();
			var rw = $('#rw');
			select_options(rw, urlencode(dusun));
		});

		$("#rw").change(function()
		{
			let dusun = $("#dusun").val();
			let rw = $(this).val();

			$('#isi_rt').show();
			var rt = $('#id_cluster');
			var params = urlencode(dusun) + '/' + urlencode(rw);
			select_options(rt, params);
		});

		$('#tanggallahir').datetimepicker(
		{
			format: 'DD-MM-YYYY'
		});

		$("#form_penduduk").validate(
		{
			submitHandler: function(form)
			{
				form.submit();
			}
		});
	});
</script>