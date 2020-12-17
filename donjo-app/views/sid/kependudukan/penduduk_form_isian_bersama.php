<script type="text/javascript">
	$(document).ready(function()
	{
		$("select[name='sex']").change();
		$("select[name='status_kawin']").change();
		$("select[name='id_asuransi']").change();
	});
	$('#mainform').on('reset', function(e)
	{
	 setTimeout(function() {
			$("select[name='sex']").change();
			$("select[name='status_kawin']").change();
			$("select[name='id_asuransi']").change();
	 });
	});
	function show_hide_hamil(sex)
	{
		if (sex == '2')
		{
			$("#isian_hamil").show();
		}
		else
		{
			$("#isian_hamil").hide();
		}
	};
	function reset_hamil()
	{
		setTimeout(function()
		{
			$('select[name=sex]').change();
		});
	};
	function show_hide_asuransi(asuransi)
	{
		if (asuransi == '1' || asuransi == '')
		{
			$('#asuransi_pilihan').hide();
		}
		else
		{
			if (asuransi == '99')
			{
				$('#label-no-asuransi').text('Nama/nomor Asuransi');
			}
			else
			{
				$('#label-no-asuransi').text('No Asuransi');
			}

			$('#asuransi_pilihan').show();
		}
	}
	function disable_kawin_cerai(status)
	{
		// Status 1 = belum kawin, 2 = kawin, 3 = cerai hidup, 4 = cerai mati
		switch (status)
		{
			case '1':
			case '4':
				$("#akta_perkawinan").attr('disabled', true);
				$("input[name=tanggalperkawinan]").attr('disabled', true);
				$("#akta_perceraian").attr('disabled', true);
				$("input[name=tanggalperceraian]").attr('disabled', true);
				break;
			case '2':
				$("#akta_perkawinan").attr('disabled', false);
				$("input[name=tanggalperkawinan]").attr('disabled', false);
				$("#akta_perceraian").attr('disabled', true);
				$("input[name=tanggalperceraian]").attr('disabled', true);
				break;
			case '3':
				$("#akta_perkawinan").attr('disabled', true);
				$("input[name=tanggalperkawinan]").attr('disabled', true);
				$("#akta_perceraian").attr('disabled', false);
				$("input[name=tanggalperceraian]").attr('disabled', false);
				break;
		}
	}
	function ubah_dusun(dusun)
	{
		$('#isi_rt').hide();
		var rw = $('#rw');
		select_options(rw, urlencode(dusun));
	}
	function ubah_rw(dusun, rw)
	{
		$('#isi_rt').show();
		var rt = $('#id_cluster');
		var params = urlencode(dusun) + '/' + urlencode(rw);
		select_options(rt, params);
	}
</script>

			<div class="row">
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="nik">NIK </label>
						<input id="nik" name="nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK" value="<?= $penduduk['nik']?>"></input>
						<input name="nik_lama" type="hidden" value="<?= $_SESSION['nik_lama']?>"/>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="nama">Nama Lengkap <code> (Tanpa Gelar) </code> </label>
						<input id="nama" name="nama" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Lengkap" value="<?= strtoupper($penduduk['nama'])?>"></input>
					</div>
				</div>
				<div class='col-sm-12'>
					<div class='form-group'>
						<label for="nama">Status Kepemilikan KTP</label>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th width='25%'>Wajib KTP</th>
										<th>KTP Elektrtonik</th>
										<th>Status Rekam</th>
										<th>Tag ID Card</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width='25%'><?= strtoupper($penduduk['wajib_ktp'])?></td>
										<td>
										 <select name="ktp_el" class="form-control input-sm">
											<option value="">Pilih KTP-EL</option>
											<?php foreach ($ktp_el as $id => $nama): ?>
											 <option value="<?= $id?>" <?php selected(strtolower($penduduk['ktp_el']), $nama); ?>><?= strtoupper($nama)?></option>
											<?php endforeach;?>
										 </select>
										</td>
										<td width='25%'>
										 <select name="status_rekam" class="form-control input-sm">
											<option value="">Pilih Status Rekam</option>
											<?php foreach ($status_rekam as $id => $nama): ?>
											 <option value="<?= $id?>" <?php selected(strtolower($penduduk['status_rekam']), $nama); ?>><?= strtoupper($nama)?></option>
											<?php endforeach;?>
										 </select>
										</td>
										<td width='25%'>
										 <input name="tag_id_card" class="form-control input-sm digits" type="text" minlength="10" maxlength="15" placeholder="Tag Id Card" value="<?= $penduduk['tag_id_card']?>"></input>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="no_kk_sebelumnya">Nomor KK Sebelumnya</label>
						<input id="no_kk_sebelumnya" name="no_kk_sebelumnya" class="form-control input-sm nik" maxlength="30" type="text" placeholder="No KK Sebelumnya" value="<?= strtoupper($penduduk['no_kk_sebelumnya'])?>"></input>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<?php if (!empty($penduduk)): ?>
							<input type="hidden" name="kk_level_lama" value="<?= $penduduk['kk_level']?>">
						<?php endif; ?>
						<label for="kk_level">Hubungan Dalam Keluarga</label>
						<select class="form-control input-sm <?= jecho($id_kk, true, 'required'); ?>" name="kk_level">
							<option value="">Pilih Hubungan Keluarga</option>
							<?php foreach ($hubungan as $data): ?>
								<option value="<?= $data['id']?>"<?php selected($penduduk['kk_level'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="sex">Jenis Kelamin </label>
						<select class="form-control input-sm required" name="sex" onchange="show_hide_hamil($(this).find(':selected').val());">
							<option value="">Jenis Kelamin</option>
							<option value="1" <?php selected($penduduk['id_sex'], '1'); ?>>Laki-Laki</option>
							<option value="2" <?php selected($penduduk['id_sex'], '2'); ?> >Perempuan</option>
						</select>
					</div>
				</div>
				<div class='col-sm-7'>
					<div class='form-group'>
						<label for="agama_id">Agama</label>
						<select class="form-control input-sm required" name="agama_id">
							<option value="">Pilih Agama</option>
							<?php foreach ($agama as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['agama_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-5'>
					<div class='form-group'>
						<label for="status">Status Penduduk </label>
						<select class="form-control input-sm required" name="status" <?php ($penduduk['no_kk']) and print('disabled') ?>>
							<option value="">Pilih Status Penduduk</option>
							<?php foreach ($status_penduduk as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['id_status'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
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
						<input id="akta_lahir" name="akta_lahir" class="form-control input-sm nomor_sk" type="text" maxlength="40" placeholder="Nomor Akta Kelahiran" value="<?= $penduduk['akta_lahir']?>"></input>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="tempatlahir">Tempat Lahir</label>
						<input id="tempatlahir" name="tempatlahir" class="form-control input-sm required" maxlength="100" type="text" placeholder="Tempat Lahir" value="<?= strtoupper($penduduk['tempatlahir'])?>"></input>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="tanggallahir">Tanggal Lahir</label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right required" id="tgl_1" name="tanggallahir" type="text" value="<?= $penduduk['tanggallahir']?>">
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
							<input class="form-control input-sm pull-right" id="jammenit_1" name="waktu_lahir" type="text" value="<?= $penduduk['waktu_lahir']?>">
						</div>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="tempat_dilahirkan">Tempat Dilahirkan</label>
						<select class="form-control input-sm" name="tempat_dilahirkan">
							<option value="">Pilih Tempat Dilahirkan</option>
							<?php foreach ($tempat_dilahirkan as $id => $nama): ?>
								<option value="<?= $id?>" <?php selected($penduduk['tempat_dilahirkan'], $id); ?>><?= strtoupper($nama)?></option>
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
									<?php foreach ($jenis_kelahiran as $id => $nama): ?>
										<option value="<?= $id?>" <?php selected($penduduk['jenis_kelahiran'], $id); ?>><?= strtoupper($nama)?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="kelahiran_anak_ke">Anak Ke <code>(Isi dengan angka)</code></label>
								<input id="kelahiran_anak_ke" name="kelahiran_anak_ke" class="form-control input-sm number" maxlength="2" type="text" placeholder="Anak Ke" value="<?= strtoupper($penduduk['kelahiran_anak_ke'])?>"></input>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="penolong_kelahiran">Penolong Kelahiran</label>
								<select class="form-control input-sm" name="penolong_kelahiran">
									<option value="">Pilih Penolong Kelahiran</option>
									<?php foreach ($penolong_kelahiran as $id => $nama): ?>
										<option value="<?= $id?>" <?php selected($penduduk['penolong_kelahiran'], $id); ?>><?= strtoupper($nama)?></option>
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
								<input id="berat_lahir" name="berat_lahir" class="form-control input-sm number" maxlength="6" type="text" placeholder="Berat Lahir" value="<?= strtoupper($penduduk['berat_lahir'])?>"></input>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="panjang_lahir">Panjang Lahir <code>( cm )</code></label>
								<input id="panjang_lahir" name="panjang_lahir" class="form-control input-sm number" maxlength="3" type="text" placeholder="Panjang Lahir" value="<?= strtoupper($penduduk['panjang_lahir'])?>"></input>
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
							<?php foreach ($pendidikan_kk as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['pendidikan_kk_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach?>
						</select>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh </label>
						<select class="form-control input-sm" name="pendidikan_sedang_id" >
							<option value="">Pilih Pendidikan</option>
							<?php foreach ($pendidikan_sedang as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['pendidikan_sedang_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="pekerjaan_id">Pekerjaaan</label>
						<select class="form-control input-sm required" name="pekerjaan_id">
							<option value="">Pilih Pekerjaan</option>
							<?php foreach ($pekerjaan as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['pekerjaan_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-12'>
					<div class="form-group subtitle_head">
						<label class="text-right"><strong>DATA KEWARGANEGARAAN :</strong></label>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="warganegara_id">Status Warga Negara</label>
						<select class="form-control input-sm required" name="warganegara_id">
							<option value="">Pilih Warga Negara</option>
							<?php foreach ($warganegara as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['warganegara_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="dokumen_pasport">Nomor Paspor </label>
						<input id="dokumen_pasport" name="dokumen_pasport" class="form-control input-sm nomor_sk" maxlength="45" type="text" placeholder="Nomor Paspor" value="<?= strtoupper($penduduk['dokumen_pasport'])?>"></input>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="tanggal_akhir_paspor">Tgl Berakhir Paspor</label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right" id="tgl_2" name="tanggal_akhir_paspor" type="text" value="<?= $penduduk['tanggal_akhir_paspor']?>">
						</div>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="dokumen_kitas">Nomor KITAS/KITAP </label>
						<input id="dokumen_kitas" name="dokumen_kitas" class="form-control input-sm number" maxlength="10" type="text" placeholder="Nomor KITAS/KITAP" value="<?= strtoupper($penduduk['dokumen_kitas'])?>"></input>
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
								<input id="ayah_nik" name="ayah_nik" class="form-control input-sm nik" type="text" placeholder="Nomor NIK Ayah" value="<?= $penduduk['ayah_nik']?>"></input>
							</div>
						</div>
						<div class='col-sm-8'>
							<div class='form-group'>
								<label for="nama_ayah">Nama Ayah </label>
								<input id="nama_ayah" name="nama_ayah" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Ayah" value="<?= strtoupper($penduduk['nama_ayah'])?>"></input>
							</div>
						</div>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="ibu_nik"> NIK Ibu </label>
						<input id="ibu_nik" name="ibu_nik" class="form-control input-sm nik" type="text" placeholder="Nomor NIK Ibu" value="<?= $penduduk['ibu_nik']?>"></input>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="nama_ibu">Nama Ibu </label>
						<input id="nama_ibu" name="nama_ibu" class="form-control input-sm required nama" maxlength="100" type="text" placeholder="Nama Ibu" value="<?= strtoupper($penduduk['nama_ibu'])?>"></input>
					</div>
				</div>
				<div class='col-sm-12'>
					<div class="form-group subtitle_head">
						<label class="text-right"><strong>ALAMAT :</strong></label>
					</div>
				</div>
				<?php if (!empty($penduduk['no_kk']) or $kk_baru) : ?>
					<div class='col-sm-12'>
						<div class='form-group'>
							<label for="telepon">Alamat KK </label>
							<input id="alamat" name="alamat" class="form-control input-sm" maxlength="200" ype="text" placeholder="Alamat di Kartu Keluarga" size="20" value="<?= $penduduk['alamat']?>"></input>
						</div>
					</div>
				<?php endif; ?>
				<?php if (empty($id_kk)): ?>
					<div class="row">
						<div class="col-sm-12">
							<div class='form-group col-sm-3'>
								<label><?= ucwords($this->setting->sebutan_dusun)?> <?php (empty($penduduk['no_kk']) and empty($kk_baru)) or print('KK')?></label>
								<select name="dusun" class="form-control input-sm required" onchange="ubah_dusun($(this).val())">
									<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
									<?php foreach ($dusun as $data): ?>
										<option value="<?= $data['dusun']?>" <?php selected($penduduk['dusun'], $data['dusun']) ?>><?= $data['dusun']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class='form-group col-sm-2'>
								<label>RW <?php (empty($penduduk['no_kk']) and empty($kk_baru)) or print('KK')?></label>
								<select id="rw" class="form-control input-sm required" name="rw" data-source="<?= site_url()?>wilayah/list_rw/" data-valueKey="rw" data-displayKey="rw" onchange="ubah_rw($('select[name=dusun]').val(), $(this).val())">
									<option class="placeholder" value="">Pilih RW</option>
									<?php foreach ($rw as $data): ?>
										<option value="<?= $data['rw']?>" <?php selected($penduduk['rw'], $data['rw']) ?>><?= $data['rw']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div id='isi_rt' class='form-group col-sm-2'>
								<label>RT <?php (empty($penduduk['no_kk']) and empty($kk_baru)) or print('KK')?></label>
								<select id="id_cluster" class="form-control input-sm required" name="id_cluster" data-source="<?= site_url()?>wilayah/list_rt/" data-valueKey="id" data-displayKey="rt">
									<option class="placeholder" value="">Pilih RT </option>
									<?php foreach ($rt as $data): ?>
										<option value="<?= $data['id']?>" <?php selected($penduduk['id_cluster'], $data['id']) ?>><?= $data['rt']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="lokasi">Lokasi Tempat Tinggal </label>
						<div class='row'>
							<div class='col-sm-12'>
								<a href="<?=site_url("penduduk/ajax_penduduk_maps/$p/$o/$penduduk[id]/1")?>" title="Lokasi <?= $penduduk['nama']?>" class="btn btn-social btn-flat bg-navy btn-sm"><i class='fa fa-map-marker'></i> Cari Lokasi Tempat Tinggal</a>
							</div>
						</div>
					</div>
				</div>
				<div class='col-sm-12'>
					<div class='form-group'>
						<label for="telepon"> Nomor Telepon </label>
						<input id="telepon" name="telepon" class="form-control input-sm" type="text" placeholder="Nomor Telepon" size="20" value="<?= $penduduk['telepon']?>"></input>
					</div>
				</div>
					<div class='col-sm-12'>
					<div class='form-group'>
						<label for="email"> Alamat Email </label>
						<input id="email" name="email" class="form-control input-sm email" maxlength="50" placeholder="Alamat Email" size="20" value="<?= $penduduk['email']?>"></input>
					</div>
				</div>
				<div class='col-sm-12'>
					<div class='form-group'>
						<label for="alamat_sebelumnya">Alamat Sebelumnya </label>
						<input id="alamat_sebelumnya" name="alamat_sebelumnya" class="form-control input-sm" maxlength="200" type="text" placeholder="Alamat Sebelumnya" value="<?= strtoupper($penduduk['alamat_sebelumnya'])?>"></input>
					</div>
				</div>
				<?php if (!$penduduk['no_kk'] and !$kk_baru): ?>
					<div class='col-sm-12'>
						<div class='form-group'>
							<label for="alamat_sekarang">Alamat Sekarang </label>
							<input id="alamat_sekarang" name="alamat_sekarang" class="form-control input-sm" maxlength="200" type="text" placeholder="Alamat Sekarang" value="<?= strtoupper($penduduk['alamat_sekarang'])?>"></input>
						</div>
					</div>
				<?php endif; ?>
				<div class='col-sm-12'>
					<div class="form-group subtitle_head">
						<label class="text-right"><strong>STATUS PERKAWINAN :</strong></label>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="status_kawin">Status Perkawinan</label>
						<select class="form-control input-sm required" name="status_kawin" onchange="disable_kawin_cerai($(this).find(':selected').val())">
							<option value="">Pilih Status Perkawinan</option>
							<?php foreach ($kawin as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['status_kawin'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<?php if ($penduduk['agama_id']==0 OR is_null($penduduk['agama_id'])): ?>
							<label for="akta_perkawinan">No. Akta Nikah (Buku Nikah)/Perkawinan </label>
						<?php elseif ($penduduk['agama_id']==1): ?>
							<label for="akta_perkawinan">No. Akta Nikah (Buku Nikah) </label>
						<?php else: ?>
							<label for="akta_perkawinan">No. Akta Perkawinan </label>
						<?php endif; ?>
							<input id="akta_perkawinan" name="akta_perkawinan" class="form-control input-sm nomor_sk" type="text" maxlength="40" placeholder="Nomor Akta Perkawinan" value="<?= $penduduk['akta_perkawinan']?>"></input>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="tanggalperkawinan">Tanggal Perkawinan <code>(Wajib diisi apabila status KAWIN)</code></label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right" id="tgl_3" name="tanggalperkawinan" type="text" value="<?= $penduduk['tanggalperkawinan']?>">
						</div>
					</div>
				</div>
				<div class='col-sm-8'>
					<div class='form-group'>
						<label for="akta_perceraian">Akta Perceraian </label>
						<input id="akta_perceraian" name="akta_perceraian" class="form-control input-sm nomor_sk" maxlength="40" type="text" placeholder="Akta Perceraian" value="<?= strtoupper($penduduk['akta_perceraian'])?>"></input>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="tanggalperceraian">Tanggal Perceraian <code>(Wajib diisi apabila status CERAI)</code></label>
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input class="form-control input-sm pull-right" id="tgl_4" name="tanggalperceraian" type="text" value="<?= $penduduk['tanggalperceraian']?>">
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
									<?php foreach ($golongan_darah as $data): ?>
										<option value="<?= $data['id']?>" <?php selected($penduduk['golongan_darah_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="cacat_id">Cacat</label>
								<select class="form-control input-sm" name="cacat_id" >
									<option value="">Pilih Jenis Cacat</option>
									<?php foreach ($cacat as $data): ?>
										<option value="<?= $data['id']?>" <?php selected($penduduk['cacat_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class='col-sm-4'>
							<div class='form-group'>
								<label for="sakit_menahun_id">Sakit Menahun</label>
								<select class="form-control input-sm" name="sakit_menahun_id">
									<option value="">Pilih Sakit Menahun</option>
									<?php foreach ($sakit_menahun as $data): ?>
										<option value="<?= $data['id']?>" <?php selected($penduduk['sakit_menahun_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class='col-sm-4' id="akseptor_kb">
					<div class='form-group'>
						<label for="cara_kb_id">Akseptor KB</label>
						<select class="form-control input-sm" name="cara_kb_id" >
							<option value="">Pilih Cara KB Saat Ini</option>
							<?php foreach ($cara_kb as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['cara_kb_id'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div id='isian_hamil' class='col-sm-4'>
					<div class='form-group'>
						<label for="hamil">Status Kehamilan </label>
						<select class="form-control input-sm" name="hamil">
							<option value="">Pilih Status Kehamilan</option>
							<option value="0" <?php selected($penduduk['hamil'], '0'); ?>>Tidak Hamil</option>
							<option value="1" <?php selected($penduduk['hamil'], '1'); ?> >Hamil</option>
						</select>
					</div>
				</div>
				<div class='col-sm-4'>
					<div class='form-group'>
						<label for="id_asuransi">Asuransi </label>
						<select class="form-control input-sm" name="id_asuransi" onchange="show_hide_asuransi($(this).find(':selected').val());">
							<option value="">Pilih Asuransi</option>
							<?php foreach ($pilihan_asuransi as $data): ?>
								<option value="<?= $data['id']?>" <?php selected($penduduk['id_asuransi'], $data['id']); ?>><?= strtoupper($data['nama'])?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
				<div id='asuransi_pilihan' class='col-sm-4'>
					<div class='form-group'>
						<label id="label-no-asuransi" for="no_asuransi">No Asuransi </label>
						<input id="no_asuransi" name="no_asuransi" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor Asuransi" value="<?= $penduduk['no_asuransi']?>"></input>
					</div>
				</div>
			</div>
