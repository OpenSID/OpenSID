<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class="modal-body">
			<div class="form-group">
				<label for="no_kk">Nomor KK <code id="tampil_nokk" style="display: none;"> (Sementara) </code></label>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">
						<input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara" <?= jecho($cek_nokk, '0', 'checked ="checked"') ?>>
					</span>
					<input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK" value="<?= $kk['no_kk']?>" <?= jecho($cek_nokk, '0', 'readonly') ?>></input>
				</div>
				<input name="id" type="hidden" value="<?= $kk['id']; ?>">
				<input name="id_cluster_lama" type="hidden" value="<?= $kk['id_cluster']; ?>">
			</div>
			<div class="form-group">
				<label for="alamat">Alamat </label>
				<textarea id="alamat" name="alamat" class="form-control input-sm alamat" maxlength="200" placeholder="Alamat Jalan/Perumahan" rows="3"><?= $kk['alamat']?></textarea>
			</div>
			<div class="row">
				<div class="form-group col-sm-6">
					<label for="dusun"><?= ucwords($this->setting->sebutan_dusun)?> </label>
					<select id="dusun" name="dusun" class="form-control input-sm required">
						<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
						<?php foreach ($dusun as $data): ?>
							<option value="<?= $data['dusun']?>" <?= selected($kk['dusun'], $data['dusun']) ?>><?= set_ucwords($data['dusun'])?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id="isi_rw" class="form-group col-sm-3">
					<label for="rw">RW</label>
					<select id="rw" name="rw" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rw/')?>" data-valueKey="rw" data-displayKey="rw" >
						<option class="placeholder" value="">Pilih RW</option>
						<?php foreach ($rw as $data): ?>
							<option value="<?= $data['rw']?>" <?= selected($kk['rw'], $data['rw']) ?>><?= $data['rw']?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id="isi_rt" class="form-group col-sm-3">
					<label for="rt">RT</label>
					<select id="id_cluster" name="id_cluster" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rt/')?>" data-valueKey="id" data-displayKey="rt">
						<option class="placeholder" value="">Pilih RT </option>
						<?php foreach ($rt as $data): ?>
							<option value="<?= $data['id']?>" <?= selected($kk['id_cluster'], $data['id']) ?>><?= $data['rt']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="tgl_cetak_kk">Tanggal Cetak Kartu Keluarga <code> (Contoh : 31/12/1980 )</code> </label>
				<div class="input-group input-group-sm date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input id="tgl_1" name="tgl_cetak_kk" class="form-control input-sm" type="text" value="<?= $kk['tgl_cetak_kk']; ?>"/>
				</div>
			</div>
			<div class="form-group">
				<label for="kelas_sosial">Kelas Sosial</label>
				<select id="kelas_sosial" name="kelas_sosial" class="form-control input-sm">
					<option value="">Pilih Tingkatan Keluarga Sejahtera</option>
					<?php foreach ($keluarga_sejahtera as $data): ?>
						<option value="<?= $data['id']?>" <?= selected($kk['kelas_sosial'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<?php if ($kk['status_dasar'] == 1): ?>
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
			<?php else: ?>
				<button id="tutup" type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<?php endif; ?>
		</div>
	</form>
	<script type="text/javascript">
		$(document).ready(function()
		{
			<?php if ($kk['status_dasar'] != 1): ?>
				$("#validasi :input").prop("disabled", true);
				$("#tutup").prop("disabled", false);
			<?php endif; ?>

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

			$('#nokk_sementara').change(function() {
				var cek_nokk = '<?= $cek_nokk ?>';
				var nokk_sementara_berikut = '<?= $nokk_sementara; ?>';
				var nokk_asli = '<?= $kk['no_kk'] ?>';

				if ($('#nokk_sementara').prop('checked')) {
					$('#no_kk').removeClass('no_kk');
					if (cek_nokk != '0') $('#no_kk').val(nokk_sementara_berikut);
					$('#no_kk').prop('readonly', true);
					$('#tampil_nokk').show();
				} else {
					$('#no_kk').addClass('no_kk');
					$('#no_kk').val(nokk_asli);
					$('#no_kk').prop('readonly', false);
					$('#tampil_nokk').hide();
				}
			});

			$('#nokk_sementara').change();

		});
	</script>
<?php endif; ?>
