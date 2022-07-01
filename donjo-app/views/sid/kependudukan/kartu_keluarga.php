<div class="content-wrapper">
	<section class="content-header">
		<h1>Salinan Kartu Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
			<li><a href="<?= site_url("keluarga/anggota/{$p}/{$o}/{$id_kk}")?>"> Daftar Anggota Keluarga</a></li>
			<li class="active">Kartu Keluarga</li>
		</ol>
	</section>
	<section class="content"  id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("keluarga/cetak_kk/{$id_kk}")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("keluarga/doc_kk/{$id_kk}")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?=site_url("keluarga/anggota/{$p}/{$o}/{$id_kk}")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Rincian Anggota Keluarga">
								<i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Anggota Keluarga
							</a>
							<a href="<?=site_url('keluarga')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Anggota Keluarga">
								<i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Keluarga
							</a>
						</div>
						<div class="box-header">
							<h3 class="text-center"><strong>SALINAN KARTU KELUARGA</strong></h3>
							<h5 class="text-center"><strong>No.  <?= get_nokk($kepala_kk['no_kk'])?> </strong></h5>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-8">
									<div class="form-group">
										<label class="col-sm-3 control-label">ALAMAT</label>
										<div class="col-sm-8">
											<p class="text-muted">: <?= strtoupper($kepala_kk['alamat_plus_dusun'])?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">RT/RW</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?=$kepala_kk['rt']  ?> / <?= $kepala_kk['rw']  ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">DESA / KELURAHAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?= strtoupper($desa['nama_desa']) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">KECAMATAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?= strtoupper($desa['nama_kecamatan']) ?></p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-sm-5 control-label">KABUPATEN</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= strtoupper($desa['nama_kabupaten']) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">KODE POS</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= $desa['kode_pos'] ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">PROVINSI</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= strtoupper($desa['nama_propinsi']) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">JUMLAH ANGGOTA</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= count($main)?></p>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered table-hover ">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th class="text-center">No</th>
													<th class="text-center">Nama Lengkap</th>
													<th class="text-center">NIK</th>
													<th class="text-center">Jenis Kelamin</th>
													<th class="text-center">Tempat Lahir</th>
													<th class="text-center">Tanggal Lahir</th>
													<th class="text-center">Agama</th>
													<th class="text-center">Pendidikan</th>
													<th class="text-center">Jenis Pekerjaan</th>
													<th class="text-center">Golongan Darah</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $key => $data): ?>
												<tr>
													<td class="text-center" ><?= $key + 1?></td>
													<td><?= strtoupper($data['nama'])?></td>
													<td><?= get_nik($data['nik']) ?></td>
													<td><?= $data['sex']?></td>
													<td><?= $data['tempatlahir']?></td>
													<td><?= tgl_indo_out($data['tanggallahir'])?></td>
													<td><?= $data['agama']?></td>
													<td><?= $data['pendidikan']?></td>
													<td><?= $data['pekerjaan']?></td>
													<td><?= $data['golongan_darah']?></td>
												</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered table-hover ">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th class="text-center">No</th>
													<th class="text-center">Status Perkawinan</th>
													<th class="text-center">Tanggal Perkawinan</th>
													<th class="text-center">Status Hubungan Dalam Keluarga</th>
													<th class="text-center">Kewarganegaraan</th>
													<th class="text-center">No. Paspor</th>
													<th class="text-center">No. KITAS / KITAP</th>
													<th class="text-center">Nama Ayah</th>
													<th class="text-center">Nama Ibu</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $key => $data): ?>
												<tr>
													<td class="text-center" ><?= $key + 1?></td>
													<td><?= $data['status_kawin']?></td>
													<td class="text-center"><?= tgl_indo_out($data['tanggalperkawinan'])?></td>
													<td><?= $data['hubungan']?></td>
													<td><?= $data['warganegara']?></td>
													<td><?= $data['dokumen_pasport']?></td>
													<td><?= $data['dokumen_kitas']?></td>
													<td><?= strtoupper($data['nama_ayah'])?></td>
													<td><?= strtoupper($data['nama_ibu'])?></td>
												</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
									<div class="table-responsive">
										<table class="table no-border">
											<tbody>
												<tr>
													<td width="25%">&nbsp;</td>
													<td width="50%">&nbsp;</td>
													<td class="text-center" width="25%"><?= $desa['nama_desa'] ?>, <?= tgl_indo(date('Y m d'))?></td>
												</tr>
												<tr>
													<td class="text-center">KEPALA KELUARGA</td>
													<td>&nbsp;</td>
													<td class="text-center"><?= strtoupper($this->setting->sebutan_kepala_desa . ' ' . $desa['nama_desa']); ?></td>
												</tr>
												<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
												<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
												<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
												<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
												<tr>
													<td class="text-center"><?= strtoupper($kepala_kk['nama'])?></td>
													<td width="50%">&nbsp;</td>
													<td class="text-center"><?= strtoupper($desa['nama_kepala_desa']) ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>



