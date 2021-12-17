<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<!-- TODO : Pindahkan ke admin-style.css -->
	<style type="text/css">
		p {
			margin-bottom: 30px;
		}
	</style>
	<form id="validasi" action="<?= $form_action?>" method="POST">
		<div class="modal-body">
			<div class="form-group">
				<p>Pecah semua anggota keluarga dan masukkan ke keluarga baru. Misalnya, pada kasus kepala keluarga meninggal, di mana perlu dibuat kartu keluarga baru untuk anggota keluarga yang ditinggal.</p>
			</div>
			<div class="form-group">
				<h5><b>Keluarga Lama</b></h5>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover tabel-rincian">
						<tbody>
							<tr>
								<td class='padat'>Nomor Kartu Keluarga</td>
								<td width="1%">:</td>
								<td><?= $kk['no_kk']?></td>
							</tr>
							<tr>
								<td>Kepala Keluarga</td>
								<td>:</td>
								<td><?= $kk['nik'] . ' - ' . $kk['nama']?></td>
							</tr>
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td><?= $kk['alamat_wilayah']?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class='form-group'>
				<h5><b>Keluarga Baru</b></h5>
				<label for="no_kk"> Nomor Kartu Keluarga<code id="tampil_nokk" style="display: none;"> (Sementara) </code></label>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">
						<input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara" <?= jecho($cek_nokk, '0', 'checked ="checked"') ?>>
					</span>
					<input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK" value="<?= $no_kk?>" <?= jecho($cek_nokk, '0', 'readonly') ?>></input>
				</div>
			</div>
			<div class="form-group">
				<label>Kepala Keluarga Baru</label>
				<select name="nik_kepala" class="form-control input-sm required" style="width:100%;">
					<option value=""> ----- Pilih Kepala Keluarga ----- </option>
					<?php foreach ($anggota as $data): ?>
						<option value="<?= $data['id']?>"><?= $data['nik'] . ' - ' . $data['nama'] . ' (' . $data['hubungan'] . ')'?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#nokk_sementara').change(function() {
				var cek_nokk = '<?= $cek_nokk ?>';
				var nokk_sementara_berikut = '<?= $nokk_sementara; ?>';
				var nokk_asli = '<?= $no_kk; ?>';
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