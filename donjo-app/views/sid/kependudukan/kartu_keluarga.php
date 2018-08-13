<div class="content-wrapper">
	<section class="content-header">
		<h1>Salinan Kartu Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
			<li><a href="<?= site_url("keluarga/anggota/$p/$o/$id_kk")?>"> Daftar Anggota Keluarga</a></li>
			<li class="active">Kartu Keluarga</li>
		</ol>
	</section>
	<section class="content"  id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("keluarga/form_a/$p/$o/$id_kk")?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Anggota</a>
							<a href="<?= site_url("keluarga/cetak_kk/$id_kk")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("keluarga/doc_kk/$id_kk")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?=site_url("keluarga/anggota/$p/$o/$id_kk")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Anggota Keluarga">
								<i class="fa fa-arrow-circle-left "></i>Daftar Anggota Keluarga
							</a>
							<a href="<?=site_url("keluarga")?>" class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Anggota Keluarga">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
							</a>
						</div>
						<div class="box-header">
							<h3 class="text-center"><strong>SALINAN KARTU KELUARGA</strong></h3>
							<h5 class="text-center"><strong>No.  <?= unpenetration($kepala_kk['no_kk'])?> </strong></h5>
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
											<p class="text-muted">: <?= strtoupper(unpenetration($desa['nama_desa'])) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">KECAMATAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?= strtoupper(unpenetration($desa['nama_kecamatan'])) ?></p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-sm-5 control-label">KABUPATEN</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= strtoupper(unpenetration($desa['nama_kabupaten'])) ?></p>
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
											<p class="text-muted">: <?= strtoupper(unpenetration($desa['nama_propinsi'])) ?></p>
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
													<th>No</th>
													<th>Nama Lengkap</th>
													<th>NIK</th>
													<th>Jenis Kelamin</th>
													<th>Tempat Lahir</th>
													<th>Tanggal Lahir</th>
													<th>Agama</th>
													<th>Pendidikan</th>
													<th>Jenis Pekerjaan</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $key => $data): ?>
												<tr>
													<td><?= $key+1?></td>
													<td><?= strtoupper(unpenetration($data['nama']))?></td>
													<td><?= $data['nik']?></td>
													<td><?= $data['sex']?></td>
													<td><?= $data['tempatlahir']?></td>
													<td><?= tgl_indo_out($data['tanggallahir'])?></td>
													<td><?= $data['agama']?></td>
													<td><?= $data['pendidikan']?></td>
													<td><?= $data['pekerjaan']?></td>
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
													<th>No</th>
													<th>Status Perkawinan</th>
													<th>Status Hubungan Dalam Keluarga</th>
													<th>Kewarganegaraan</th>
													<th>No. Paspor</th>
													<th>No. KITAS / KITAP</th>
													<th>Ayah</th>
													<th>Ibu</th>
													<th>Golongan Darah</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $key => $data): ?>
												<tr>
													<td><?= $key+1?></td>
													<td><?= $data['status_kawin']?></td>
													<td><?= $data['hubungan']?></td>
													<td><?= $data['warganegara']?></td>
													<td><?= $data['dokumen_pasport']?></td>
													<td><?= $data['dokumen_kitas']?></td>
													<td><?= strtoupper($data['nama_ayah'])?></td>
													<td><?= strtoupper($data['nama_ibu'])?></td>
													<td><?= $data['golongan_darah']?></td>
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
													<td class="text-center" width="25%"><?= unpenetration($desa['nama_desa']) ?>, <?= tgl_indo(date("Y m d"))?></td>
												</tr>
												<tr>
													<td class="text-center">KEPALA KELUARGA</td>
													<td>&nbsp;</td>
													<td class="text-center">KEPALA <?= strtoupper($this->setting->sebutan_desa)?> <?= strtoupper($desa['nama_desa']) ?></td>
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

