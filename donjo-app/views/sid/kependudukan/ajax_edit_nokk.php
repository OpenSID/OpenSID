<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script>
	$('#tgl_1').datetimepicker(
	{
		format: 'DD-MM-YYYY'
	});
</script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="no_kk">Nomor KK</label>
							<input class="form-control input-sm required" type="text" placeholder="Nomor KK" name="no_kk" value="<?= $kk['no_kk']?>"></input>
							<input name="id" type="hidden" value="<?= $kk['id']; ?>">
						</div>
						<div class="form-group">
							<label for="tgl_cetak_kk">Tanggal Cetak Kartu Keluarga <code> (Exp: 31/12/1980 )</code> </label>
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm required" name="tgl_cetak_kk" id="tgl_1" type="text" value="<?= $kk['tgl_cetak_kk']?>"/>
							</div>
						</div>
						<div class="form-group">
							<label>Kelas SosiaL</label>
							<select class="form-control input-sm"  id="kelas_sosial" name="kelas_sosial">
								<option value="">Pilih Tingkatan Keluarga Sejahtera</option>
								<?php foreach ($keluarga_sejahtera as $data): ?>
									<option value="<?= $data['id']?>" <?php if ($kk['kelas_sosial']==$data['id']): ?>selected<?php endif; ?>><?= strtoupper($data['nama'])?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div class="form-group">
							<label>Peserta Program Bantuan Keluarga</label>
							<?php foreach ($program as $bantuan): ?>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="id_program[]" value="<?= $bantuan['id']?>"/<?php if ($bantuan['peserta'] != ''): ?>checked<?php endif; ?>>
										<a href="<?= site_url('program_bantuan/detail/1/'.$bantuan['id'])?>" target="_blank"><?= $bantuan['nama']?></a>
									</label>
								</div>
							<?php endforeach; ?>
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
