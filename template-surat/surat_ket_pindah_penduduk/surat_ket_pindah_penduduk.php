<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border tdk-permohonan tdk-periksa">
						<a href="<?= site_url("surat") ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form id="main" name="main" method="POST" class="form-horizontal">
							<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
						</form>
						<form id="validasi" action="<?= $form_action ?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat') ?>">
							<div class="row jar_form">
								<label for="nomor" class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id'] ?>">
								</div>
							</div>
							<input id="kode_format" type="hidden" name="kode_format" value="f125">
							<?php if ($individu) : ?>
								<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
							<?php endif; ?>
							<div class="form-group">
								<label for="telpon" class="col-sm-3 control-label">Telepon Pemohon</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-4">
										<input name="telepon" id="telepon" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="Nomor Telepon"></input>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="pakai_format" class="col-sm-3 control-label">Gunakan Format</label>
								<div class="col-sm-4">
									<select class="form-control input-sm" id="pakai_format" name="pakai_format" style="width:100%;" onchange="pilih_format_surat($(this).val());">
										<option value="">-- Pilih Format Lampiran Surat --</option>
										<option value="f108">F-1.08 (pindah pergi)</option>
										<option value="f125" selected>F-1.23, F-1.25, F-1.29, F-1.34 (sesuai tujuan)</option>
										<option value="f103">F-1.03 (pindah datang)</option>
										<option value="f127">F-1.27, F-1.31, F-1.39 (sesuai tujuan)</option>
									</select>
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
							<div id="group_jenis_permohonan" class="form-group" style="display:none;">
								<label for="jenis_permohonan_id" class="col-sm-3 control-label">Jenis Permohonan</label>
								<div class="col-sm-4">
									<select class="form-control input-sm select2 required" style="width:100%;" id="jenis_permohonan_id" name="jenis_permohonan_id" onchange="pilih_jenis_permohonan(this.value)" disabled>
										<option value="">-- Pilih Jenis Permohonan --</option>
										<?php foreach ($kode['jenis_permohonan'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="alasan_pindah_id" class="col-sm-3 control-label">Alasan Pindah</label>
								<div class="col-sm-4">
									<select class="form-control input-sm select2 required" style="width:100%;" id="alasan_pindah_id" name="alasan_pindah_id" onchange=get_alasan(this.value)>
										<option value="">-- Pilih Alasan Pindah --</option>
										<?php foreach ($kode['alasan_pindah'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div id="sebut_alasan" class="col-sm-5" style="display:none;">
									<input class="form-control input-sm" type="text" placeholder="Sebut Alasan Lainnya" name="sebut_alasan">
								</div>
							</div>
							<div class="form-group" id="pindah">
								<label for="klasifikasi_pindah_id" class="col-sm-3 control-label">Klasifikasi Pindah</label>
								<div class="col-sm-4">
									<select class="form-control input-sm" id="klasifikasi_pindah_id" name="klasifikasi_pindah_id" onchange="urus_klasifikasi_pindah($(this).val());">
										<option value="">-- Pilih Klasifikasi Pindah --</option>
										<?php foreach ($kode['klasifikasi_pindah'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="alamat_tujuan" class="col-sm-3 control-label">Alamat Tujuan</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-12">
										<input id="alamat_tujuan" name="alamat_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Alamat Tujuan"></input>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="rt_tujuan" class="col-sm-3 control-label">RT/RW/Dusun Tujuan</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-3">
										<input id="rt_tujuan" name="rt_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="RT"></input>
									</div>
									<div class="col-sm-1"></div>
									<div class="col-sm-3">
										<input id="rw_tujuan" name="rw_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="RW"></input>
									</div>
									<div class="col-sm-1"></div>
									<div class="col-sm-4">
										<input id="dusun_tujuan" name="dusun_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Dusun"></input>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="desa_tujuan" class="col-sm-3 control-label">Desa/Kelurahan Tujuan</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-12">
										<input id="desa_tujuan" name="desa_tujuan" class="form-control input-sm" type="hidden" data-awal="<?= $lokasi['nama_desa']; ?>">
										<input id="desa_tujuan_show" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Desa/Kelurahan" onchange="$('#desa_tujuan').val($(this).val());">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="kecamatan_tujuan" class="col-sm-3 control-label">Kec/Kab/Prop Tujuan</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-3">
										<input id="kecamatan_tujuan" name="kecamatan_tujuan" type="hidden" data-awal="<?= $lokasi['nama_kecamatan']; ?>" />
										<input id="kecamatan_tujuan_show" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Kecamatan " onchange="$('#kecamatan_tujuan').val($(this).val());">
									</div>
									<div class="col-sm-1"></div>
									<div class="col-sm-3">
										<input id="kabupaten_tujuan" name="kabupaten_tujuan" type="hidden" data-awal="<?= $lokasi['nama_kabupaten']; ?>" />
										<input id="kabupaten_tujuan_show" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Kabupaten" onchange="$('#kabupaten_tujuan').val($(this).val());">
									</div>
									<div class="col-sm-1"></div>
									<div class="col-sm-4">
										<input id="provinsi_tujuan" name="provinsi_tujuan" type="hidden" data-awal="<?= $lokasi['nama_propinsi']; ?>" />
										<input id="provinsi_tujuan_show" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Provinsi" onchange="$('#provinsi_tujuan').val($(this).val());">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="kode_pos_tujuan" class="col-sm-3 control-label">Kode Pos/ Telpon</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-3">
										<input id="kode_pos_tujuan" name="kode_pos_tujuan" class="form-control input-sm <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="Kode Pos">
									</div>
									<div class="col-sm-1"></div>
									<div class="col-sm-3">
										<input id="telepon_tujuan" name="telepon_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="Telpon">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="jenis_kepindahan_id" class="col-sm-3 control-label">Jenis Kepindahan</label>
								<div class="col-sm-4">
									<select class="form-control input-sm required" id="jenis_kepindahan_id" name="jenis_kepindahan_id" onchange="urus_anggota($(this).val());">
										<option value="">-- Pilih Jenis Kepindahan --</option>
										<?php foreach ($kode['jenis_kepindahan'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<input id='status_kk_tidak_pindah' type="hidden" name="status_kk_tidak_pindah_id" />
							<div class="form-group">
								<label for="status_kk_tidak_pindah" class="col-sm-3 control-label">Status KK Bagi Yang Tidak Pindah</label>
								<div class="col-sm-4">
									<select id="status_kk_tidak_pindah_show" class="form-control input-sm" onchange="$('#status_kk_tidak_pindah').val($(this).val());">
										<option value="">-- Pilih Status KK Tidak Pindah --</option>
										<?php foreach ($kode['status_kk_tidak_pindah'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
									<select id="status_kk_tidak_pindah_f108_show" style="display: none" class="form-control input-sm" onchange="$('#status_kk_tidak_pindah').val($(this).val());">
										<option value="">-- Pilih Status KK Tidak Pindah --</option>
										<?php foreach ($kode['status_kk_tidak_pindah_f108'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
									<select id="status_kk_tidak_pindah_f103_show" style="display: none" class="form-control input-sm" onchange="$('#status_kk_tidak_pindah').val($(this).val());">
										<option value="">-- Pilih Status KK Tidak Pindah --</option>
										<?php foreach ($kode['status_kk_tidak_pindah_f103'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<input id='status_kk_pindah' type="hidden" name="status_kk_pindah_id" />
							<div class="form-group">
								<label for="status_kk_pindah" class="col-sm-3 control-label">Status KK Bagi Yang Pindah</label>
								<div class="col-sm-4">
									<select class="form-control input-sm" id='status_kk_pindah_show' onchange="pilih_status_kk_pindah($(this).val());">
										<option value="">-- Pilih Status KK Pindah --</option>
										<?php foreach ($kode['status_kk_pindah'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
									<select class="form-control input-sm" id='status_kk_pindah_f103_show' style="display: none" onchange="$('#status_kk_pindah').val($(this).val());">
										<option value="">-- Pilih Status KK Pindah --</option>
										<?php foreach ($kode['status_kk_pindah_f103'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group numpang_kk" style="display: none">
								<label for="no_kk_baru" class="col-sm-3 control-label">Nomor Kartu Keluarga Bagi Yang Pindah</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-12">
										<input id="no_kk_baru" name="no_kk_baru" class="form-control input-sm <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="Nomor Kartu Keluarga Bagi Yang Pindah"></input>
									</div>
								</div>
							</div>
							<div class="form-group numpang_kk" style="display: none">
								<label for="nik_kk_baru" class="col-sm-3 control-label">NIK Kepala Keluarga Bagi Yang Pindah</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-12">
										<input id="nik_kk_baru" name="nik_kk_baru" class="form-control input-sm <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" type="text" placeholder="NIK Kepala Keluarga Bagi Yang Pindah"></input>
									</div>
								</div>
							</div>
							<div class="form-group numpang_kk" style="display: none">
								<label for="no_kk_baru" class="col-sm-3 control-label">Nama Kepala Keluarga Bagi Yang Pindah</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-12">
										<input id="nama_kk_baru" name="nama_kk_baru" class="form-control input-sm <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Nama Kepala Keluarga Bagi Yang Pindah"></input>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="pengikut" class="col-sm-3 control-label">Pengikut</label>
								<div class="col-sm-8">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover nowrap">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th>&nbsp;</th>
													<th>No</th>
													<th>NIK</th>
													<th>KTP Berlaku S/D</th>
													<th>Nama</th>
													<th>Jenis Kelamin</th>
													<th>Umur</th>
													<th>Status Kawin</th>
												</tr>
											</thead>
											<tbody>
												<?php if ($anggota != NULL) : ?>
													<input id='jumlah_anggota' type='hidden' disabled='disabled' value="<?= count($anggota); ?>">
													<?php $i = 0; ?>
													<?php foreach ($anggota as $data) : $i++; ?>
														<tr>
															<td>
																<?php if ($data['kk_level'] == "1") : ?>
																	<input id='kk' type="hidden" name="id_cb[]" value="'<?= $data['id'] ?>'" />
																	<input id='kk_show' disabled='disabled' type="checkbox" onchange="urus_masa_ktp($(this).is(':unchecked'),'<?= $i; ?>');" />
																<?php else : ?>
																	<input id='anggota<?= $i ?>' type="hidden" name="id_cb[]" disabled="disabled" value="'<?= $data['id'] ?>'" />
																	<input id='anggota_show<?= $i ?>' type="checkbox" value="'<?= $data['nik'] ?>'" onchange="urus_masa_ktp($(this).is(':unchecked'),'<?= $i; ?>');" />
																<?php endif; ?>
															</td>
															<td><?= $i ?></td>
															<td><?= $data['nik'] ?></td>
															<td>
																<input id="ktp_berlaku<?= ($i) ?>" type="hidden" name="ktp_berlaku[]" type="text" value="Seumur Hidup" />
																<input disabled="disabled" type="text" value="Seumur Hidup" class="inputbox" size="20" />
															</td>
															<td><?= $data['nama'] ?></td>
															<td><?= $data['sex'] ?></td>
															<td><?= $data['umur'] ?></td>
															<td><?= $data['status_kawin'] ?></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="form-group" id="group_nama_sponsor" style="display:none;">
								<label for="nama_sponsor" class="col-sm-3 control-label">Nama Sponsor</label>
								<div class="col-sm-4">
									<input id="nama_sponsor" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Nama Sponsor" name="nama_sponsor" disabled>
								</div>
							</div>
							<div class="form-group" id="group_tipe_sponsor" style="display:none;">
								<label for="tipe_sponsor_id" class="col-sm-3 control-label">Tipe Sponsor</label>
								<div class="col-sm-4">
									<select class="form-control input-sm select2 required" style="width:100%;" id="tipe_sponsor_id" name="tipe_sponsor_id" onchange=get_alasan(this.value) disabled>
										<option value="">-- Pilih Tipe Sponsor --</option>
										<?php foreach ($kode['tipe_sponsor'] as $key => $value) : ?>
											<option value="<?= $key ?>"><?= strtoupper($value) ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group" id="group_alamat_sponsor" style="display:none;">
								<label for="alamat_sponsor" class="col-sm-3 control-label">Alamat Sponsor</label>
								<div class="col-sm-8">
									<input id="alamat_sponsor" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Alamat Sponsor" name="alamat_sponsor" disabled>
								</div>
							</div>
							<div class="form-group" id="group_nomor_itas" style="display:none;">
								<label for="nomor_itas" class="col-sm-3 control-label">Nomor dan Tanggal ITAS & ITAP</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-6">
										<input id="nomor_itas" name="nomor_itas" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Nomor ITAS & ITAP" disabled></input>
									</div>
									<div class="col-sm-6">
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input title="Pilih Tanggal" class="form-control input-sm datepicker required" id="tanggal_itas" name="tanggal_itas" type="text" placeholder="Tanggal Masa Berlaku" disabled />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group" id="group_negara_tujuan" style="display:none;">
								<label for="negara_tujuan" class="col-sm-3 control-label">Negara Tujuan</label>
								<div class="input-group col-sm-8">
									<div class="col-sm-6">
										<input id="negara_tujuan" name="negara_tujuan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Negara Tujuan" disabled></input>
									</div>
									<div class="col-sm-6">
										<input id="kode_negara" name="kode_negara" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Kode Negara" disabled></input>
									</div>
								</div>
							</div>
							<div class="form-group" id="group_alamat_tujuan" style="display:none;">
								<label for="alamat_tujuan_luar_negeri" class="col-sm-3 control-label">Alamat Tujuan (Luar Negeri)</label>
								<div class="col-sm-8">
									<input id="alamat_tujuan_luar_negeri" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Alamat Tujuan (Luar Negeri)" name="alamat_tujuan_luar_negeri" disabled>
								</div>
							</div>
							<div class="form-group" id="group_penanggungjawab" style="display:none;">
								<label for="penanggungjawab" class="col-sm-3 control-label">Penanggung Jawab</label>
								<div class="col-sm-8">
									<input id="penanggungjawab" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Penanggungjawab" name="penanggungjawab" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="tanggal_pindah" class="col-sm-3 control-label">Tanggal Pindah</label>
								<div class="col-sm-3 col-lg-2">
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input title="Pilih Tanggal" class="form-control input-sm datepicker required" name="tanggal_pindah" placeholder="Rencana Tanggal Pindah" type="text" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-8">
									<input id="keterangan" class="form-control input-sm required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" type="text" placeholder="Keterangan" name="keterangan">
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	function pilih_format_surat(kode_format) {		
		$('#kode_format').val(kode_format);
		if (kode_format == 'f103') {
			$('#kode_format').val('F-1.03');
			$('#status_kk_tidak_pindah_f103_show').show();
			$('#status_kk_tidak_pindah_f108_show').hide();
			$('#status_kk_tidak_pindah_show').hide();
			$('#status_kk_pindah_f103_show').show();
			$('#status_kk_pindah_show').hide();
			$('#group_jenis_permohonan').show();
			$('#jenis_permohonan_id').removeAttr('disabled');

			// Klasifikasi pindah tidak untuk F-1.03
			$('#pindah').hide();
			$('#klasifikasi_pindah_id').removeClass('required');
		} else if (kode_format == 'f108') {
			$('#kode_format').val('F-1.08');
			$('#status_kk_tidak_pindah_f103_show').hide();
			$('#status_kk_tidak_pindah_f108_show').show();
			$('#status_kk_tidak_pindah_show').hide();
			$('#status_kk_pindah_f103_show').hide();
			$('#status_kk_pindah_show').show();
			$('#group_jenis_permohonan').hide();
			$('#jenis_permohonan_id').attr('disabled', 'disabled');

			$('#group_nama_sponsor').hide();
			$('#group_tipe_sponsor').hide();
			$('#group_alamat_sponsor').hide();
			$('#group_nomor_itas').hide();
			$('#group_negara_tujuan').hide();
			$('#group_alamat_tujuan').hide();
			$('#group_penanggungjawab').hide();

			// Klasifikasi pindah tidak untuk F-1.08
			$('#pindah').show();
			$('#klasifikasi_pindah_id').addClass('required');

			$('#nama_sponsor').attr('disabled', 'disabled');
			$('#tipe_sponsor_id').attr('disabled', 'disabled');
			$('#alamat_sponsor').attr('disabled', 'disabled');
			$('#nomor_itas').attr('disabled', 'disabled');
			$('#tanggal_itas').attr('disabled', 'disabled');
			$('#negara_tujuan').attr('disabled', 'disabled');
			$('#kode_negara').attr('disabled', 'disabled');
			$('#alamat_tujuan_luar_negeri').attr('disabled', 'disabled');
			$('#penanggungjawab').attr('disabled', 'disabled');
		} else {
			// kode format berdasarkan tujuan
			$('#status_kk_tidak_pindah_f103_show').hide();
			$('#status_kk_tidak_pindah_f108_show').hide();
			$('#status_kk_tidak_pindah_show').show();
			$('#status_kk_pindah_f103_show').hide();
			$('#status_kk_pindah_show').show();
			$('#group_jenis_permohonan').hide();
			$('#jenis_permohonan_id').attr('disabled', 'disabled');
			$('#pindah').show();
			$('#klasifikasi_pindah_id').addClass('required');
			
			$('#group_nama_sponsor').hide();
			$('#group_tipe_sponsor').hide();
			$('#group_alamat_sponsor').hide();
			$('#group_nomor_itas').hide();
			$('#group_negara_tujuan').hide();
			$('#group_alamat_tujuan').hide();
			$('#group_penanggungjawab').hide();

			$('#nama_sponsor').attr('disabled', 'disabled');
			$('#tipe_sponsor_id').attr('disabled', 'disabled');
			$('#alamat_sponsor').attr('disabled', 'disabled');
			$('#nomor_itas').attr('disabled', 'disabled');
			$('#tanggal_itas').attr('disabled', 'disabled');
			$('#negara_tujuan').attr('disabled', 'disabled');
			$('#kode_negara').attr('disabled', 'disabled');
			$('#alamat_tujuan_luar_negeri').attr('disabled', 'disabled');
			$('#penanggungjawab').attr('disabled', 'disabled');
		}
		// Reset klasifikasi pindah
		$('#klasifikasi_pindah_id').val('');
	}

	function pilih_jenis_permohonan(jenis_permohonan) {
		if (jenis_permohonan == 3) {
			$('#group_negara_tujuan').show();
			$('#group_alamat_tujuan').show();
			$('#group_penanggungjawab').show();

			$('#group_nama_sponsor').hide();
			$('#group_tipe_sponsor').hide();
			$('#group_alamat_sponsor').hide();
			$('#group_nomor_itas').hide();

			$('#negara_tujuan').removeAttr('disabled');
			$('#kode_negara').removeAttr('disabled');
			$('#alamat_tujuan_luar_negeri').removeAttr('disabled');
			$('#penanggungjawab').removeAttr('disabled');

			$('#nama_sponsor').attr('disabled', 'disabled');
			$('#tipe_sponsor_id').attr('disabled', 'disabled');
			$('#alamat_sponsor').attr('disabled', 'disabled');
			$('#nomor_itas').attr('disabled', 'disabled');
			$('#tanggal_itas').attr('disabled', 'disabled');
		} else if (jenis_permohonan == 4) {
			$('#group_nama_sponsor').show();
			$('#group_tipe_sponsor').show();
			$('#group_alamat_sponsor').show();
			$('#group_nomor_itas').show();

			$('#group_negara_tujuan').hide();
			$('#group_alamat_tujuan').hide();
			$('#group_penanggungjawab').hide();

			$('#nama_sponsor').removeAttr('disabled');
			$('#tipe_sponsor_id').removeAttr('disabled');
			$('#alamat_sponsor').removeAttr('disabled');
			$('#nomor_itas').removeAttr('disabled');
			$('#tanggal_itas').removeAttr('disabled');

			$('#negara_tujuan').attr('disabled', 'disabled');
			$('#kode_negara').attr('disabled', 'disabled');
			$('#alamat_tujuan_luar_negeri').attr('disabled', 'disabled');
			$('#penanggungjawab').attr('disabled', 'disabled');
		} else {
			$('#group_nama_sponsor').hide();
			$('#group_tipe_sponsor').hide();
			$('#group_alamat_sponsor').hide();
			$('#group_nomor_itas').hide();
			$('#group_negara_tujuan').hide();
			$('#group_alamat_tujuan').hide();
			$('#group_penanggungjawab').hide();

			$('#nama_sponsor').attr('disabled', 'disabled');
			$('#tipe_sponsor_id').attr('disabled', 'disabled');
			$('#alamat_sponsor').attr('disabled', 'disabled');
			$('#nomor_itas').attr('disabled', 'disabled');
			$('#tanggal_itas').attr('disabled', 'disabled');
			$('#negara_tujuan').attr('disabled', 'disabled');
			$('#kode_negara').attr('disabled', 'disabled');
			$('#alamat_tujuan_luar_negeri').attr('disabled', 'disabled');
			$('#penanggungjawab').attr('disabled', 'disabled');
		}
	}

	function get_alasan(alasan) {
		if (alasan == 7) {
			$('#sebut_alasan').show();
		} else {
			$('#sebut_alasan').hide();
		}
	}

	function enable_anggota() {
		jumlah_anggota = $("#jumlah_anggota").val();
		for (i = 1; i <= jumlah_anggota; i++) {
			anggota = $("#anggota_show" + i);
			if (anggota.length > 0) {
				anggota.removeAttr('disabled');
			}
		}
	}

	function anggota_pindah(ya_atau_tidak) {
		jumlah_anggota = $("#jumlah_anggota").val();
		for (i = 1; i <= jumlah_anggota; i++) {
			anggota = $("#anggota_show" + i);
			if (anggota.length > 0) {
				anggota.attr("checked", ya_atau_tidak);
				anggota.trigger("onchange");
				anggota.attr('disabled', 'disabled');
			}
		}
	}

	function urus_anggota(jenis_pindah) {
		if ($('#kode_format').val() == "f108") {
			status_kk_tidak_pindah = "#status_kk_tidak_pindah_f108_show";
		} else {
			status_kk_tidak_pindah = "#status_kk_tidak_pindah_show";
		}

		// Hanya anggota yang pindah
		if (jenis_pindah == 4) {
			$('#kk_show').attr("checked", false);
			$("#kk").attr('disabled', 'disabled');
			if ($('#kode_format').val() == "f108") {
				$(status_kk_tidak_pindah).val("4");
			} else {
				$(status_kk_tidak_pindah).val("3");
			}
			$(status_kk_tidak_pindah).trigger("onchange");
			$(status_kk_tidak_pindah).attr('disabled', 'disabled');
			$("#status_kk_pindah_show").removeAttr('disabled');
			enable_anggota();
			
			// Reset Pilihan Status KK Bagi Yang Pindah
			pilih_status_kk_pindah(3);
		} else {
			$('#kk_show').attr("checked", true);
			$("#kk").removeAttr('disabled');
			if ($('#klasifikasi_pindah_id').val() < 3) {
				// Jika pindah di satu kecamatan, nomor KK tetap.
				// Jika pindah ke luar kecamatan, nomor KK ganti.
				$("#status_kk_pindah_show").val("3");
				$("#status_kk_pindah_show").trigger("onchange");
				$("#status_kk_pindah_show").attr('disabled', 'disabled');
			} else {
				$("#status_kk_pindah_show").removeAttr('disabled');
			}

			$(status_kk_tidak_pindah).removeAttr('disabled');
			// KK and semua anggota pindah
			if (jenis_pindah == 2) {
				if ($('#kode_format').val() == "f108") {
					$(status_kk_tidak_pindah).val("3");
				} else {
					$(status_kk_tidak_pindah).val(" ");
				}
				$(status_kk_tidak_pindah).trigger("onchange");
				$(status_kk_tidak_pindah).attr('disabled', 'disabled');
				anggota_pindah(true);
			}

			// KK dan sebagian anggota pindah
			if (jenis_pindah == 3) {
				enable_anggota();
			}

			// Hanya KK yang pindah
			if (jenis_pindah == 1) {
				anggota_pindah(false);
			}
		};

		$('#kk_show').trigger("onchange");
	}

	function urus_masa_ktp(centang, urut) {
		// ktp_berlaku sekarang selalu 'Seumur Hidup' dan tidak diubah
		if (centang) {
			$('#anggota' + urut).attr('disabled', 'disabled');
		} else {
			$('#anggota' + urut).removeAttr('disabled');
		}
	}

	function set_wilayah(tingkat_wilayah) {
		wilayah = $('#' + tingkat_wilayah);
		wilayah_show = $('#' + tingkat_wilayah + '_show');
		wilayah.val(wilayah.attr('data-awal'));
		wilayah_show.val(wilayah.attr('data-awal'));
		wilayah_show.attr('disabled', 'disabled');
	}

	function urus_klasifikasi_pindah(klasifikasi_pindah) {
		if (klasifikasi_pindah >= 1) {
			set_wilayah('desa_tujuan');
			set_wilayah('kecamatan_tujuan');
			set_wilayah('kabupaten_tujuan');
			set_wilayah('provinsi_tujuan');
			$('#desa_tujuan-opener').hide();
			$('#kecamatan_tujuan-opener').hide();
			$('#kabupaten_tujuan-opener').hide();
			$('#provinsi_tujuan-opener').hide();
		}

		if (klasifikasi_pindah > 1) {
			$('#kode_format').val('F-1.25');
			if ($('#pakai_format').val() == 'f127') {
				$('#kode_format').val('F-1.27');
			}
			$('#desa_tujuan_show').removeAttr('disabled');
			$('#desa_tujuan-opener').show();
		} else {
			$('#kode_format').val('F-1.23');
			if ($('#pakai_format').val() == 'f127') {
				$('#kode_format').val('F-1.27');
			}
		}

		if (klasifikasi_pindah > 2) {
			$('#kode_format').val('F-1.29');
			if ($('#pakai_format').val() == 'f127') {
				$('#kode_format').val('F-1.31');
			}
			$('#kecamatan_tujuan_show').removeAttr('disabled');
			$('#kecamatan_tujuan-opener').show();
		}

		if (klasifikasi_pindah > 3) {
			$('#kode_format').val('F-1.34');
			if ($('#pakai_format').val() == 'f127') {
				$('#kode_format').val('F-1.39');
			}
			$('#kabupaten_tujuan_show').removeAttr('disabled');
			$('#kabupaten_tujuan-opener').show();
		}

		if (klasifikasi_pindah > 4) {
			$('#kode_format').val('F-1.34');
			if ($('#pakai_format').val() == 'f127') {
				$('#kode_format').val('F-1.39');
			}
			$('#provinsi_tujuan_show').removeAttr('disabled');
			$('#provinsi_tujuan-opener').show();
		}

		$('#jenis_kepindahan_id').trigger('onchange');
	}

	function pilih_status_kk_pindah(status) {
		if ($('#kode_format').val() == 'f127' && status == 1) {
			$('.numpang_kk').show();
			$('#no_kk_baru').addClass('required');
			$('#nik_kk_baru').addClass('required');
			$('#nama_kk_baru').addClass('required');
			$('#no_kk_baru').addClass('no_kk');
			$('#nik_kk_baru').addClass('nik');
		} else {
			$('.numpang_kk').hide();
			$("#no_kk_baru").removeClass('required');
			$("#nik_kk_baru").removeClass('required');
			$("#nama_kk_baru").removeClass('required');
			$("#no_kk_baru").removeClass('no_kk');
			$("#nik_kk_baru").removeClass('nik');
		}
		$('#status_kk_pindah').val(status);
	}
</script>