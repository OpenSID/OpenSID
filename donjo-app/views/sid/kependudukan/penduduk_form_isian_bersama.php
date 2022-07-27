<div class="row">
	<?php if ($jenis_peristiwa == 5 && ! $penduduk['tgl_peristiwa']) : ?>
		<div class='col-sm-4'>
			<div class='form-group'>
				<label for="tgl_peristiwa">Tanggal Pindah Masuk</label>
				<div class="input-group input-group-sm date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input class="form-control input-sm pull-right tgl_sekarang required" name="tgl_peristiwa" type="text" value="<?= $penduduk['tgl_peristiwa'] ? rev_tgl($penduduk['tgl_peristiwa']) : date('d-m-Y'); ?>">
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if (! $penduduk['tgl_lapor']) : ?>
		<div class='col-sm-4'>
			<div class='form-group'>
				<label for="tgl_lapor">Tanggal Lapor</label>
				<div class="input-group input-group-sm date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input class="form-control input-sm pull-right tgl_sekarang required" name="tgl_lapor" type="text" value="<?= $penduduk['tgl_lapor'] ? rev_tgl($penduduk['tgl_lapor']) : date('d-m-Y'); ?>">
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>DATA DIRI :</strong></label>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="nik">NIK <code id="tampil_nik" style="display: none;"> (Sementara) </code></label>
			<div class="input-group input-group-sm">
				<span class="input-group-addon">
					<input type="checkbox" title="Centang jika belum memiliki NIK" id="nik_sementara" <?= jecho($cek_nik, '0', 'checked ="checked"') ?>>
				</span>
				<input id="nik" name="nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK" value="<?= $penduduk['nik'] ?>" <?= jecho($cek_nik, '0', 'readonly') ?>></input>
			</div>
		</div>
	</div>
	<div class='col-sm-8'>
		<div class='form-group'>
			<label for="nama">Nama Lengkap <code> (Tanpa Gelar) </code> </label>
			<input id="nama" name="nama" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Lengkap" value="<?= strtoupper($penduduk['nama']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="row">
			<div class="col-sm-12">
				<div class='form-group'>
					<label for="nama">Status Kepemilikan Identitas</label>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" style="margin-bottom: 0px;">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th width='25%'>Wajib Identitas</th>
									<th>Identitas Elektronik</th>
									<th>Status Rekam</th>
									<th>Tag ID Card</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php if ($penduduk['wajib_ktp'] != null) : ?>
										<td width='25%'><?= strtoupper($penduduk['wajib_ktp']) ?></td>
									<?php else : ?>
										<td width='25%'><label id="wajib_ktp"></label></td>
									<?php endif; ?>
									<td>
										<select name="ktp_el" id="ktp_el" class="form-control input-sm wajib_identitas" onchange="show_hide_ktp_el($(this).find(':selected').val())">
											<option value="">Pilih Identitas-EL</option>
											<?php foreach ($ktp_el as $id => $nama) : ?>
												<option value="<?= $id ?>" <?php selected(strtolower($penduduk['ktp_el']), $nama); ?>><?= strtoupper($nama) ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td width='25%'>
										<select name="status_rekam" class="form-control input-sm wajib_identitas">
											<option value="">Pilih Status Rekam</option>
											<?php foreach ($status_rekam as $id => $nama) : ?>
												<option value="<?= $id ?>" <?php selected(strtolower($penduduk['status_rekam']), $nama); ?>><?= strtoupper($nama) ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td width='25%'>
										<input id="tag_id_card" name="tag_id_card" class="form-control input-sm digits" type="text" minlength="10" maxlength="17" placeholder="Tag Id Card" value="<?= $penduduk['tag_id_card'] ?>"></input>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="section_ktp_el">
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="tempat_cetak_ktp">Tempat Penerbitan KTP</label>
					<input id="tempat_cetak_ktp" name="tempat_cetak_ktp" class="form-control input-sm" maxlength="150" type="text" placeholder="Tempat Penerbitan KTP" value="<?= $penduduk['tempat_cetak_ktp'] ?>"></input>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="tanggal_cetak_ktp">Tanggal Penerbitan KTP</label>
					<div class="input-group input-group-sm date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input class="form-control input-sm pull-right" id="tanggal_cetak_ktp" name="tanggal_cetak_ktp" type="text" value="<?= $penduduk['tanggal_cetak_ktp'] ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="no_kk_sebelumnya">Nomor KK Sebelumnya</label>
			<input id="no_kk_sebelumnya" name="no_kk_sebelumnya" class="form-control input-sm no_kk" maxlength="30" type="text" placeholder="No KK Sebelumnya" value="<?= strtoupper($penduduk['no_kk_sebelumnya']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<?php if (! empty($penduduk)) : ?>
				<input type="hidden" name="kk_level_lama" value="<?= $penduduk['kk_level'] ?>">
			<?php endif; ?>
			<label for="kk_level">Hubungan Dalam Keluarga</label>
			<select class="form-control input-sm select2 required" name="kk_level">
				<option value="">Pilih Hubungan Keluarga</option>
				<?php foreach ($hubungan as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['kk_level'], $data['id']); ?> <?= ($data['id'] == 1 && $keluarga['status_dasar'] == '2') ? 'disabled' : ''; ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="sex">Jenis Kelamin </label>
			<select class="form-control input-sm required" name="sex" onchange="ubah_sex($(this).find(':selected').val());">
				<option value="">Jenis Kelamin</option>
				<option value="1" <?php selected($penduduk['id_sex'], '1'); ?>>Laki-Laki</option>
				<option value="2" <?php selected($penduduk['id_sex'], '2'); ?>>Perempuan</option>
			</select>
		</div>
	</div>
	<div class='col-sm-7'>
		<div class='form-group'>
			<label for="agama_id">Agama</label>
			<select class="form-control input-sm required" name="agama_id">
				<option value="">Pilih Agama</option>
				<?php foreach ($agama as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['agama_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-5'>
		<div class='form-group'>
			<label for="status">Status Penduduk </label>
			<select class="form-control input-sm required" id="status_penduduk" name="status" onchange="show_hide_penduduk_tidak_tetap($(this).find(':selected').val())" <?php ($penduduk['no_kk']) && print 'disabled' ?>>
				<option value="">Pilih Status Penduduk</option>
				<?php foreach ($status_penduduk as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['id_status'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div id="section_penduduk_tidak_tetap">
		<div class='col-sm-12'>
			<div class="form-group subtitle_head">
				<label class="text-right"><strong>DATA PENDUDUK TIDAK TETAP :</strong></label>
			</div>
		</div>
		<div class='col-sm-8'>
			<div class='form-group'>
				<label for="maksud_tujuan_kedatangan">Maksud dan Tujuan Kedatangan</label>
				<textarea id="maksud_tujuan_kedatangan" name="maksud_tujuan_kedatangan" class="form-control input-sm" style="resize: none" placeholder="Maksud dan Tujuan Kedatangan"><?= $penduduk['maksud_tujuan_kedatangan'] ?></textarea>
			</div>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>DATA KELAHIRAN :</strong></label>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="akta_lahir">Nomor Akta Kelahiran </label>
			<input id="akta_lahir" name="akta_lahir" class="form-control input-sm nomor_sk" type="text" maxlength="40" placeholder="Nomor Akta Kelahiran" value="<?= $penduduk['akta_lahir'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-8'>
		<div class='form-group'>
			<label for="tempatlahir">Tempat Lahir</label>
			<input id="tempatlahir" name="tempatlahir" class="form-control input-sm required" maxlength="100" type="text" placeholder="Tempat Lahir" value="<?= strtoupper($penduduk['tempatlahir']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="tanggallahir">Tanggal Lahir</label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right required" id="tgl_lahir" name="tanggallahir" type="text" value="<?= $penduduk['tanggallahir'] ?>" onchange="myFunction()">
			</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="waktulahir">Waktu Kelahiran </label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right" id="jammenit_1" name="waktu_lahir" type="text" value="<?= $penduduk['waktu_lahir'] ?>">
			</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="tempat_dilahirkan">Tempat Dilahirkan</label>
			<select class="form-control input-sm" name="tempat_dilahirkan">
				<option value="">Pilih Tempat Dilahirkan</option>
				<?php foreach ($tempat_dilahirkan as $id => $nama) : ?>
					<option value="<?= $id ?>" <?php selected($penduduk['tempat_dilahirkan'], $id); ?>><?= strtoupper($nama) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class='row'>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="jenis_kelahiran">Jenis Kelahiran</label>
					<select class="form-control input-sm" name="jenis_kelahiran">
						<option value="">Pilih Jenis Kelahiran</option>
						<?php foreach ($jenis_kelahiran as $id => $nama) : ?>
							<option value="<?= $id ?>" <?php selected($penduduk['jenis_kelahiran'], $id); ?>><?= strtoupper($nama) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="kelahiran_anak_ke">Anak Ke <code>(Isi dengan angka)</code></label>
					<input id="kelahiran_anak_ke" name="kelahiran_anak_ke" class="form-control input-sm number" min="1" max="20" type="number" placeholder="Anak Ke-" value="<?= $penduduk['kelahiran_anak_ke'] ?>"></input>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="penolong_kelahiran">Penolong Kelahiran</label>
					<select class="form-control input-sm" name="penolong_kelahiran">
						<option value="">Pilih Penolong Kelahiran</option>
						<?php foreach ($penolong_kelahiran as $id => $nama) : ?>
							<option value="<?= $id ?>" <?php selected($penduduk['penolong_kelahiran'], $id); ?>><?= strtoupper($nama) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class='row'>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="berat_lahir">Berat Lahir <code>( Gram )</code></label>
					<input id="berat_lahir" name="berat_lahir" class="form-control input-sm number" maxlength="6" type="text" placeholder="Berat Lahir" value="<?= strtoupper($penduduk['berat_lahir']) ?>"></input>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="panjang_lahir">Panjang Lahir <code>( cm )</code></label>
					<input id="panjang_lahir" name="panjang_lahir" class="form-control input-sm number" maxlength="3" type="text" placeholder="Panjang Lahir" value="<?= strtoupper($penduduk['panjang_lahir']) ?>"></input>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>PENDIDIKAN DAN PEKERJAAN :</strong></label>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="pendidikan_kk_id">Pendidikan Dalam KK </label>
			<select class="form-control input-sm required" name="pendidikan_kk_id">
				<option value="">Pilih Pendidikan (Dalam KK) </option>
				<?php foreach ($pendidikan_kk as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['pendidikan_kk_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh </label>
			<select class="form-control input-sm" name="pendidikan_sedang_id">
				<option value="">Pilih Pendidikan</option>
				<?php foreach ($pendidikan_sedang as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['pendidikan_sedang_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="pekerjaan_id">Pekerjaaan</label>
			<select class="form-control input-sm required" name="pekerjaan_id">
				<option value="">Pilih Pekerjaan</option>
				<?php foreach ($pekerjaan as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['pekerjaan_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>DATA KEWARGANEGARAAN :</strong></label>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class='form-group'>
			<label for="etnis">Suku/Etnis</label>
			<select class="form-control input-sm select2-tags nama_suku" data-url="<?= site_url() . 'penduduk/ajax_penduduk_suku/' ?>" data-placeholder="Pilih Suku/Etnis" id="suku" name="suku">
				<option value="">Pilih Suku/Etnis</option>
				<?php if ($suku['penduduk']) : ?>
					<?php foreach ($suku['penduduk'] as $ref_suku) : ?>
						<option value="<?= $ref_suku['suku'] ?>" <?php selected($penduduk['suku'], $ref_suku['suku']); ?>><?= $ref_suku['suku'] ?></option>
					<?php endforeach ?>
					<option disabled>----------------------</option>
				<?php endif; ?>
				<?php foreach ($suku['ref'] as $ref_suku) : ?>
					<option value="<?= $ref_suku['suku'] ?>" <?php selected($penduduk['suku'], $ref_suku['suku']); ?>><?= $ref_suku['suku'] ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="warganegara_id">Status Warga Negara</label>
			<select class="form-control input-sm required" id="warganegara_id" name="warganegara_id" onchange="show_hide_status_warga_negara($(this).find(':selected').val())">
				<option value="">Pilih Warga Negara</option>
				<?php foreach ($warganegara as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['warganegara_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-8'>
		<div class='form-group'>
			<label for="dokumen_pasport">Nomor Paspor </label>
			<input id="dokumen_pasport" name="dokumen_pasport" class="form-control input-sm nomor_sk" maxlength="45" type="text" placeholder="Nomor Paspor" value="<?= strtoupper($penduduk['dokumen_pasport']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="tanggal_akhir_paspor">Tgl Berakhir Paspor</label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right" id="tgl_2" name="tanggal_akhir_paspor" type="text" value="<?= $penduduk['tanggal_akhir_paspor'] ?>">
			</div>
		</div>
	</div>
	<div class='col-sm-8' id='field_dokumen_kitas'>
		<div class='form-group'>
			<label for="dokumen_kitas">Nomor KITAS/KITAP </label>
			<input id="dokumen_kitas" name="dokumen_kitas" class="form-control input-sm number" maxlength="45" type="text" placeholder="Nomor KITAS/KITAP" value="<?= strtoupper($penduduk['dokumen_kitas']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-4' id='field_negara_asal'>
		<div class='form-group'>
			<label for="negara_asal">Negara Asal</label>
			<input id="negara_asal" name="negara_asal" class="form-control input-sm" maxlength="10" type="text" placeholder="Negara Asal" value="<?= strtoupper($penduduk['negara_asal']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>DATA ORANG TUA :</strong></label>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="ayah_nik"> NIK Ayah </label>
					<input id="ayah_nik" name="ayah_nik" class="form-control input-sm nik" type="text" placeholder="Nomor NIK Ayah" value="<?= $penduduk['ayah_nik'] ?>"></input>
				</div>
			</div>
			<div class='col-sm-8'>
				<div class='form-group'>
					<label for="nama_ayah">Nama Ayah </label>
					<input id="nama_ayah" name="nama_ayah" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Ayah" value="<?= strtoupper($penduduk['nama_ayah']) ?>"></input>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="ibu_nik"> NIK Ibu </label>
			<input id="ibu_nik" name="ibu_nik" class="form-control input-sm nik" type="text" placeholder="Nomor NIK Ibu" value="<?= $penduduk['ibu_nik'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-8'>
		<div class='form-group'>
			<label for="nama_ibu">Nama Ibu </label>
			<input id="nama_ibu" name="nama_ibu" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Ibu" value="<?= strtoupper($penduduk['nama_ibu']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>ALAMAT :</strong></label>
		</div>
	</div>
	<?php if (! empty($penduduk['no_kk']) || $kk_baru) : ?>
		<div class='col-sm-12'>
			<div class='form-group'>
				<label for="alamat">Alamat KK </label>
				<input id="alamat" name="alamat" class="form-control input-sm nomor_sk" maxlength="200" type="text" placeholder="Alamat di Kartu Keluarga" value="<?= $penduduk['alamat'] ?>"></input>
			</div>
		</div>
	<?php endif; ?>
	<?php if (empty($id_kk)) : ?>
		<div class="row">
			<div class="col-sm-12">
				<div class='form-group col-sm-3'>
					<label for="dusun"><?= ucwords($this->setting->sebutan_dusun) ?> <?php (empty($penduduk['no_kk']) && empty($kk_baru)) || print 'KK' ?></label>
					<select id="dusun" name="dusun" class="form-control input-sm required">
						<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun) ?></option>
						<?php foreach ($dusun as $data) : ?>
							<option value="<?= $data['dusun'] ?>" <?php selected($penduduk['dusun'], $data['dusun']) ?>><?= set_ucwords($data['dusun']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id='isi_rw' class='form-group col-sm-2'>
					<label for="rw">RW <?php (empty($penduduk['no_kk']) && empty($kk_baru)) || print 'KK' ?></label>
					<select id="rw" name="rw" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rw/') ?>" data-valueKey="rw" data-displayKey="rw">
						<option class="placeholder" value="">Pilih RW</option>
						<?php foreach ($rw as $data) : ?>
							<option value="<?= $data['rw'] ?>" <?php selected($penduduk['rw'], $data['rw']) ?>><?= $data['rw'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id='isi_rt' class='form-group col-sm-2'>
					<label for="id_cluster">RT <?php (empty($penduduk['no_kk']) && empty($kk_baru)) || print 'KK' ?></label>
					<select id="id_cluster" name="id_cluster" class="form-control input-sm required" data-source="<?= site_url('wilayah/list_rt/') ?>" data-valueKey="id" data-displayKey="rt">
						<option class="placeholder" value="">Pilih RT </option>
						<?php foreach ($rt as $data) : ?>
							<option value="<?= $data['id'] ?>" <?php selected($penduduk['id_cluster'], $data['id']) ?>><?= $data['rt'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class='col-sm-12'>
		<div class='form-group'>
			<label for="alamat_sebelumnya">Alamat Sebelumnya </label>
			<input id="alamat_sebelumnya" name="alamat_sebelumnya" class="form-control input-sm nomor_sk <?= jecho($jenis_peristiwa, 5, 'required') ?>" maxlength="200" type="text" placeholder="Alamat Sebelumnya" value="<?= $penduduk['alamat_sebelumnya'] ?>"></input>
		</div>
	</div>
	<?php if (! $penduduk['no_kk'] && ! $kk_baru) : ?>
		<div class='col-sm-12'>
			<div class='form-group'>
				<label for="alamat_sekarang">Alamat Sekarang </label>
				<input id="alamat_sekarang" name="alamat_sekarang" class="form-control input-sm" maxlength="200" type="text" placeholder="Alamat Sekarang" value="<?= $penduduk['alamat_sekarang'] ?>"></input>
			</div>
		</div>
	<?php endif; ?>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="telepon"> Nomor Telepon </label>
			<input id="telepon" name="telepon" class="form-control input-sm number" type="text" maxlength="20" placeholder="Nomor Telepon" value="<?= $penduduk['telepon'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="email"> Email </label>
			<input id="email" name="email" class="form-control input-sm email" maxlength="50" placeholder="Alamat Email" value="<?= $penduduk['email'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="telegram"> Telegram </label>
			<input name="telegram" class="form-control input-sm number" maxlength="100" type="text" placeholder="Akun Telegram" value="<?= $penduduk['telegram'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="status_kawin"> Cara Hubung Warga </label>
			<select class="form-control input-sm required" name="hubung_warga">
				<option value="">Pilih Cara Hubungi</option>
				<?php foreach (['SMS', 'Email', 'Telegram'] as $value) : ?>
					<?php
                        if ((bool) $this->setting->aktifkan_sms === false && $value === 'SMS') {
                            continue;
                        }
				    ?>
					<option value="<?= $value ?>" <?= selected($penduduk['hubung_warga'], $value); ?>><?= $value ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>STATUS PERKAWINAN :</strong></label>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="status_kawin">Status Perkawinan</label>
			<select class="form-control input-sm required" name="status_kawin" onchange="disable_kawin_cerai($(this).find(':selected').val())" id="status_perkawinan">
				<option value="">Pilih Status Perkawinan</option>
				<?php foreach ($kawin as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['status_kawin'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<?php if ($penduduk['agama_id'] == 0 || null === $penduduk['agama_id']) : ?>
				<label for="akta_perkawinan">No. Akta Nikah (Buku Nikah)/Perkawinan </label>
			<?php elseif ($penduduk['agama_id'] == 1) : ?>
				<label for="akta_perkawinan">No. Akta Nikah (Buku Nikah) </label>
			<?php else : ?>
				<label for="akta_perkawinan">No. Akta Perkawinan </label>
			<?php endif; ?>
			<input id="akta_perkawinan" name="akta_perkawinan" class="form-control input-sm nomor_sk" type="text" maxlength="40" placeholder="Nomor Akta Perkawinan" value="<?= $penduduk['akta_perkawinan'] ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="tanggalperkawinan">Tanggal Perkawinan <code>(Wajib diisi apabila status KAWIN)</code></label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right" id="tgl_3" name="tanggalperkawinan" type="text" value="<?= $penduduk['tanggalperkawinan'] ?>">
			</div>
		</div>
	</div>
	<div class='col-sm-8'>
		<div class='form-group'>
			<label for="akta_perceraian">Akta Perceraian </label>
			<input id="akta_perceraian" name="akta_perceraian" class="form-control input-sm nomor_sk" maxlength="40" type="text" placeholder="Akta Perceraian" value="<?= strtoupper($penduduk['akta_perceraian']) ?>"></input>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="tanggalperceraian">Tanggal Perceraian <code>(Wajib diisi apabila status CERAI)</code></label>
			<div class="input-group input-group-sm date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input class="form-control input-sm pull-right" id="tgl_4" name="tanggalperceraian" type="text" value="<?= $penduduk['tanggalperceraian'] ?>">
			</div>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>DATA KESEHATAN :</strong></label>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="row">
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="golongan_darah_id">Golongan Darah</label>
					<select class="form-control input-sm required" name="golongan_darah_id">
						<option value="">Pilih Golongan Darah</option>
						<?php foreach ($golongan_darah as $data) : ?>
							<option value="<?= $data['id'] ?>" <?php selected($penduduk['golongan_darah_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="cacat_id">Cacat</label>
					<select class="form-control input-sm" name="cacat_id">
						<option value="">Pilih Jenis Cacat</option>
						<?php foreach ($cacat as $data) : ?>
							<option value="<?= $data['id'] ?>" <?php selected($penduduk['cacat_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="sakit_menahun_id">Sakit Menahun</label>
					<select class="form-control input-sm" name="sakit_menahun_id">
						<option value="">Pilih Sakit Menahun</option>
						<?php foreach ($sakit_menahun as $data) : ?>
							<option value="<?= $data['id'] ?>" <?php selected($penduduk['sakit_menahun_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-4' id="akseptor_kb">
		<div class='form-group'>
			<label for="cara_kb_id">Akseptor KB</label>
			<select class="form-control input-sm" name="cara_kb_id">
				<option value="">Pilih Cara KB Saat Ini</option>
				<?php foreach ($cara_kb as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['cara_kb_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div id='isian_hamil' class='col-sm-4'>
		<div class='form-group'>
			<label for="hamil">Status Kehamilan </label>
			<select class="form-control input-sm" name="hamil">
				<option value="">Pilih Status Kehamilan</option>
				<?php foreach ($kehamilan as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['hamil'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class='col-sm-4'>
		<div class='form-group'>
			<label for="id_asuransi">Asuransi Kesehatan</label>
			<select class="form-control input-sm" name="id_asuransi" onchange="show_hide_asuransi($(this).find(':selected').val());">
				<option value="">Pilih Asuransi</option>
				<?php foreach ($pilihan_asuransi as $data) : ?>
					<option value="<?= $data['id'] ?>" <?php selected($penduduk['id_asuransi'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div id='asuransi_pilihan' class='col-sm-4'>
		<div class='form-group'>
			<label id="label-no-asuransi" for="no_asuransi">No Asuransi </label>
			<input id="no_asuransi" name="no_asuransi" class="form-control input-sm nomor_sk" type="text" maxlength="100" placeholder="Nomor Asuransi" value="<?= $penduduk['no_asuransi'] ?>"></input>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-4">
				<div class='form-group'>
					<label id="label-no-bpjs-ketenagakerjaan" for="bpjs_ketenagakerjaan">Nomor BPJS Ketenagakerjaan</label>
					<input id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" class="form-control input-sm nomor_sk" type="text" maxlength="100" placeholder="Nomor BPJS Ketenagakerjaan" value="<?= $penduduk['bpjs_ketenagakerjaan'] ?>"></input>
				</div>
			</div>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="form-group subtitle_head">
			<label class="text-right"><strong>LAINNYA :</strong></label>
		</div>
	</div>
	<div class='col-sm-12'>
		<div class="row">
			<div class='col-sm-4'>
				<div class='form-group'>
					<label for="bahasa_id">Dapat Membaca Huruf</label>
					<select class="form-control input-sm" id="bahasa_id" name="bahasa_id">
						<option value="0">Pilih Isian</option>
						<?php foreach ($bahasa as $data) : ?>
							<option value="<?= $data['id'] ?>" <?php selected($penduduk['bahasa_id'], $data['id']); ?>><?= strtoupper($data['nama']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class='col-sm-8'>
				<div class='form-group'>
					<label for="ket">Keterangan</label>
					<textarea id="ket" name="ket" class="form-control input-sm" rows="3" placeholder="Keterangan"><?= $penduduk['ket'] ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function() {
		$('#tgl_lahir').datetimepicker({
			format: 'DD-MM-YYYY',
			locale: 'id',
			maxDate: 'now',
		});

		var addOrRemoveRequiredAttribute = function() {
			var tglsekarang = new Date();
			var tgllahir = parseInt($('#tgl_lahir').val().substring(6, 10));
			var selisih = tglsekarang.getFullYear() - tgllahir;
			var wajib_identitas = $('.wajib_identitas');
			var status_perkawinan = document.getElementById("status_perkawinan").value;
			if (selisih > 16 || (status_perkawinan != '' && status_perkawinan > 1)) {
				$('#wajib_ktp').text('WAJIB');
			} else {
				$('#wajib_ktp').text('BELUM WAJIB');
			}
		};

		$("#tgl_lahir").on('change keyup paste click keydown', addOrRemoveRequiredAttribute);
		$("#status_perkawinan").on('change keyup paste click keydown select', addOrRemoveRequiredAttribute);
		$(".form-control").on('change keyup paste click keydown select', addOrRemoveRequiredAttribute);

		$('#tag_id_card').focus();

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

		$("select[name='sex']").change();
		$("select[name='status_kawin']").change();
		$("select[name='id_asuransi']").change();

		$('#nik_sementara').change(function() {
			var cek_nik = '<?= $cek_nik ?>';
			var nik_sementara_berikut = '<?= $nik_sementara; ?>';
			var nik_asli = '<?= $penduduk['nik']; ?>';
			if ($('#nik_sementara').prop('checked')) {
				$('#nik').removeClass('nik');
				if (cek_nik != '0') $('#nik').val(nik_sementara_berikut);
				$('#nik').prop('readonly', true);
				$('#tampil_nik').show();
			} else {
				$('#nik').addClass('nik');
				$('#nik').val(nik_asli);
				$('#nik').prop('readonly', false);
				$('#tampil_nik').hide();
			}
		});

		$('#nik_sementara').change();

		show_hide_penduduk_tidak_tetap($('#status_penduduk').val());
		show_hide_status_warga_negara($('#warganegara_id').val());
		show_hide_ktp_el($('#ktp_el').val());

	});

	$('#mainform').on('reset', function(e) {
		setTimeout(function() {
			$("select[name='sex']").change();
			$("select[name='status_kawin']").change();
			$("select[name='id_asuransi']").change();
		});
	});

	function ubah_sex(sex) {
		var old_foto = $('#old_foto').val();

		(sex == '2') ? $("#isian_hamil").show(): $("#isian_hamil").hide();

		if (old_foto == '') {
			$('#foto').attr("src", AmbilFoto(old_foto, 'kecil_', sex))
		}
	};

	function reset_hamil() {
		setTimeout(function() {
			$('select[name=sex]').change();
		});
	};

	function show_hide_asuransi(asuransi) {
		if (asuransi == '1' || asuransi == '') {
			$('#asuransi_pilihan').hide();
		} else {
			if (asuransi == '99') {
				$('#label-no-asuransi').text('Nama/nomor Asuransi');
			} else {
				$('#label-no-asuransi').text('No Asuransi');
			}

			$('#asuransi_pilihan').show();
		}
	}

	function AmbilFoto(foto, ukuran = "kecil_", sex) {
		//Jika penduduk ada foto, maka pakai foto tersebut
		//Jika tidak, pakai foto default
		if (foto) {
			ukuran_foto = ukuran || null
			file_foto = '<?= base_url() . LOKASI_USER_PICT; ?>' + ukuran_foto + foto;
		} else {
			file_foto = sex == '2' ? '<?= FOTO_DEFAULT_WANITA ?>' : '<?= FOTO_DEFAULT_PRIA ?>';
		}

		return file_foto;
	}

	function disable_kawin_cerai(status) {
		// Status 1 = belum kawin, 2 = kawin, 3 = cerai hidup, 4 = cerai mati
		switch (status) {
			case '1':
				$("#akta_perkawinan").attr('disabled', true);
				$("input[name=tanggalperkawinan]").attr('disabled', true);
				$("#akta_perceraian").attr('disabled', true);
				$("input[name=tanggalperceraian]").attr('disabled', true);
				$('#wajib_ktp').text('BELUM WAJIB');
				break;
			case '2':
				$("#akta_perkawinan").attr('disabled', false);
				$("input[name=tanggalperkawinan]").attr('disabled', false);
				$("#akta_perceraian").attr('disabled', true);
				$("input[name=tanggalperceraian]").attr('disabled', true);
				$('#wajib_ktp').text('WAJIB');
				break;
			case '3':
				$("#akta_perkawinan").attr('disabled', false);
				$("input[name=tanggalperkawinan]").attr('disabled', false);
				$("#akta_perceraian").attr('disabled', false);
				$("input[name=tanggalperceraian]").attr('disabled', false);
				$('#wajib_ktp').text('WAJIB');
				break;
			case '4':
				$("#akta_perkawinan").attr('disabled', false);
				$("input[name=tanggalperkawinan]").attr('disabled', false);
				$("#akta_perceraian").attr('disabled', true);
				$("input[name=tanggalperceraian]").attr('disabled', true);
				$('#wajib_ktp').text('WAJIB');
				break;
		}
	}

	function show_hide_penduduk_tidak_tetap(status) {
		// status 1 = TETAP, 2 = TIDAK TETAP
		if (status == 2) {
			$('#section_penduduk_tidak_tetap').fadeIn();
		} else {
			$('#section_penduduk_tidak_tetap').fadeOut();
		}
	}

	function show_hide_status_warga_negara(warganegaraId) {
		// warganegara_id 1 = WNI, 2 = WNA, 3 = DUA KEWARGANEGARAAN
		if (warganegaraId == 2 || warganegaraId == 3) {
			$('#field_negara_asal').fadeIn();
			$('#field_dokumen_kitas').fadeIn();
		} else {
			$('#field_negara_asal').fadeOut();
			$('#field_dokumen_kitas').fadeOut();
		}
	}

	function show_hide_ktp_el(status) {
		// status 1 = BELUM MEMILIKI KTP-EL, 2 = SUDAH MEMILIKI KTP-EL
		if (status == 2) {
			$('#section_ktp_el').fadeIn();
		} else {
			$('#section_ktp_el').fadeOut();
		}
	}
</script>