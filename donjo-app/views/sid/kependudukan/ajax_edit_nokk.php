<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<script src="<?= base_url()?>assets/js/script.js"></script>

<script type="text/javascript">
	$('#tgl_1').datetimepicker({
		format: 'DD-MM-YYYY'
	});

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
</script>

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
</style>

<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="no_kk">Nomor KK</label>
					<input class="form-control input-sm required nik" type="text" placeholder="Nomor KK" name="no_kk" value="<?= $kk['no_kk']?>"></input>
					<input name="id" type="hidden" value="<?= $kk['id']; ?>">
					<input name="id_cluster_lama" type="hidden" value="<?= $kk['id_cluster']; ?>">
				</div>
				<div class="form-group">
					<label for="alamat">Alamat </label>
					<textarea id="alamat" name="alamat" class="form-control input-sm alamat" maxlength="200" placeholder="Alamat Jalan/Perumahan" rows="3" style="resize:none;"><?= $kk['alamat']?></textarea>
				</div>
				<div class="row">
					<div class='form-group col-sm-6'>
						<label for="dusun"><?= ucwords($this->setting->sebutan_dusun)?> </label>
						<select id="dusun" name="dusun" class="form-control input-sm required">
							<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
							<?php foreach ($dusun as $data): ?>
								<option value="<?= $data['dusun']?>" <?php selected($kk['dusun'], $data['dusun']) ?>><?= $data['dusun']?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class='form-group col-sm-3'>
						<label for="rw">RW</label>
						<select id="rw" name="rw" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rw/')?>" data-valueKey="rw" data-displayKey="rw" >
							<option class="placeholder" value="">Pilih RW</option>
							<?php foreach ($rw as $data): ?>
								<option value="<?= $data['rw']?>" <?php selected($kk['rw'], $data['rw']) ?>><?= $data['rw']?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div id="isi_rt" class='form-group col-sm-3'>
						<label for="rt">RT</label>
						<select id="id_cluster" name="id_cluster" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rt/')?>" data-valueKey="id" data-displayKey="rt">
							<option class="placeholder" value="">Pilih RT </option>
							<?php foreach ($rt as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($kk['id_cluster'], $data['id']) ?>><?= $data['rt']?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tgl_cetak_kk">Tanggal Cetak Kartu Keluarga <code> (Exp: 31/12/1980 )</code> </label>
					<div class="input-group input-group-sm date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input id="tgl_1" name="tgl_cetak_kk" class="form-control input-sm" type="text" value="<?= $kk['tgl_cetak_kk']?>"/>
					</div>
				</div>
				<div class="form-group">
					<label for="kelas_sosial">Kelas Sosial</label>
					<select id="kelas_sosial" name="kelas_sosial" class="form-control input-sm">
						<option value="">Pilih Tingkatan Keluarga Sejahtera</option>
						<?php foreach ($keluarga_sejahtera as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($kk['kelas_sosial'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
