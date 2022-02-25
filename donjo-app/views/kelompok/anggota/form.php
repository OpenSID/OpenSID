<?php if ($this->CI->cek_hak_akses('u')) : ?>
	<?= $tipe = ucfirst($this->controller); ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Data Anggota </h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
				<li><a href="<?= site_url($this->controller); ?>"> Daftar <?= $tipe; ?></a></li>
				<li class="active">Data Anggota <?= $tipe; ?></li>
			</ol>
		</section>
		<section class="content" id="maincontent">
			<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="row">
					<div class="col-md-3">
						<?php $this->load->view('global/ambil_foto', ['id_sex' => $pend['id_sex'], 'foto' => $pend['foto']]); ?>
					</div>
					<div class="col-md-9">
						<div class="box box-primary">
							<div class="box-header with-border">
								<a href="<?= site_url("{$this->controller}/anggota/{$kelompok}"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Anggota <?= $tipe; ?></a>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label class="col-sm-3 control-label" for="id_penduduk">Nama Anggota</label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-non-auto required" <?= jecho($pend, true, 'disabled') ?> id="id_penduduk" name="id_penduduk">
											<option value="">-- Silakan Masukan NIK / Nama --</option>
											<?php foreach ($list_penduduk as $data) : ?>
												<option value="<?= $data['id']; ?>" <?= selected($data['id'], $pend['id_penduduk']); ?>>NIK :<?= $data['nik'] . ' - ' . $data['nama'] . ' - ' . $data['alamat']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="no_anggota">Nomor Anggota</label>
									<div class="col-sm-5">
										<input id="no_anggota" class="form-control input-sm number" type="text" placeholder="Nomor Anggota" name="no_anggota" value="<?= $pend['no_anggota']; ?>">
									</div>
								</div>
								<div class="form-group">
									<?php if (! empty($pend)) : ?>
										<input type="hidden" name="jabatan_lama" value="<?= $pend['jabatan'] ?>">
									<?php endif; ?>
									<label class="col-sm-3 control-label" for="jabatan">Jabatan</label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-tags required" id="jabatan" name="jabatan">
											<option option value="">-- Silakan Pilih Jabatan --</option>
											<?php foreach ($list_jabatan1 as $key => $value) : ?>
												<option value="<?= $key; ?>" <?= selected($key, $pend['jabatan']); ?>><?= $value; ?></option>
											<?php endforeach; ?>
											<?php foreach ($list_jabatan2 as $value) : ?>
												<option value="<?= $value['jabatan']; ?>" <?= selected($value['jabatan'], $pend['jabatan']); ?>><?= $value['jabatan']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="no_sk_jabatan">Nomor SK Jabatan</label>
									<div class="col-sm-5">
										<input id="no_sk_jabatan" class="form-control input-sm nomor_sk" type="text" placeholder="Nomor SK Jabatan" name="no_sk_jabatan" value="<?= $pend['no_sk_jabatan']; ?>">
									</div>
								</div>
								<?php if ($this->controller == 'lembaga') : ?>
									<div class="form-group">
										<label class="col-sm-3 control-label">Nomor SK Pengangkatan</label>
										<div class="col-sm-5">
											<input name="nmr_sk_pengangkatan" class="form-control input-sm" type="text" maxlength="30" placeholder="Nomor SK Pengangkatan" value="<?= $pend['nmr_sk_pengangkatan'] ?>"></input>
										</div>
									</div>
									<div class='form-group'>
										<label class="col-sm-3 control-label">Tanggal SK Pengangkatan</label>
										<div class="col-sm-5">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pengangkatan" type="text" value="<?= tgl_indo_out($pend['tgl_sk_pengangkatan']) ?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Nomor SK Pemberhentian</label>
										<div class="col-sm-5">
											<input name="nmr_sk_pemberhentian" class="form-control input-sm" type="text" placeholder="Nomor SK Pemberhentian" value="<?= $pend['nmr_sk_pemberhentian'] ?>"></input>
										</div>
									</div>
									<div class='form-group'>
										<label class="col-sm-3 control-label">Tanggal SK Pemberhentian</label>
										<div class="col-sm-5">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pemberhentian" type="text" value="<?= tgl_indo_out($pend['tgl_sk_pemberhentian']) ?>">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">Masa Jabatan (Usia/Periode)</label>
										<div class="col-sm-5">
											<input name="periode" class="form-control input-sm" type="text" placeholder="Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)" value="<?= $pend['periode'] ?>"></input>
										</div>
									</div>
								<?php endif ?>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
									<div class="col-sm-5">
										<textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="5"><?= $pend['keterangan']; ?></textarea>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
	</div>
<?php endif; ?>
<?php $this->load->view('global/capture'); ?>