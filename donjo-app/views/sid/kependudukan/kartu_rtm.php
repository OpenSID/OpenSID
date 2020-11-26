<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Kartu Rumah Tangga
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fas fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('rtm/clear')?>"> Daftar Rumah Tangga</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url("rtm/anggota/$id_kk")?>"> Daftar Anggota Rumah Tangga</a></li>
						<li class="breadcrumb-item active">Kartu Rumah Tangga</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-outline card-info">
						<div class="card-header with-border">
							<a href="<?= site_url("rtm/cetak_kk/$id_kk")?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?=site_url("rtm/anggota/$id_kk")?>" class="btn btn-flat btn-info btn-xs btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Kembali ke Daftar Anggota Rumah Tangga">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Anggota Rumah Tangga
							</a>
						</div>
						<div class="card-header">
							<h3 class="text-center"><strong>KARTU RUMAH TANGGA</strong></h3>
							<h5 class="text-center"><strong>No. <?= $kepala_kk['no_kk']?> </strong></h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-8">
									<div class="row mb-2">
										<label class="col-sm-3 control-label">ALAMAT</label>
										<div class="col-sm-8">
											<p class="text-muted">: <?= strtoupper($kepala_kk['dusun'])?></p>
										</div>
									</div>
									<div class="row mb-2">
										<label class="col-sm-3 control-label">RT/RW</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?=$kepala_kk['rt'] ?> / <?= $kepala_kk['rw'] ?></p>
										</div>
									</div>
									<div class="row mb-2">
										<label class="col-sm-3 control-label">DESA / KELURAHAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?= strtoupper($desa['nama_desa']) ?></p>
										</div>
									</div>
									<div class="row mb-2">
										<label class="col-sm-3 control-label">KECAMATAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?= strtoupper($desa['nama_kecamatan']) ?></p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="row mb-2">
										<label class="col-sm-5 control-label">KABUPATEN</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= strtoupper($desa['nama_kabupaten']) ?></p>
										</div>
									</div>
									<div class="row mb-2">
										<label class="col-sm-5 control-label">KODE POS</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= $desa['kode_pos'] ?></p>
										</div>
									</div>
									<div class="row mb-2">
										<label class="col-sm-5 control-label">PROVINSI</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?= strtoupper($desa['nama_propinsi']) ?></p>
										</div>
									</div>
									<div class="row mb-2">
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
													<th>Nomor KK</th>
													<th>Jenis Kelamin</th>
													<th>Tempat Lahir</th>
													<th>Tanggal Lahir</th>
													<th>Agama</th>
													<th>Pendidikan</th>
													<th>Pekerjaan</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $key => $data): ?>
												<tr>
													<td><?= $data['no']?></td>
													<td><?= strtoupper($data['nama'])?></td>
													<td><?= $data['nik']?></td>
													<td><?= $data['no_kk']?></td>
													<td><?= $data['sex']?></td>
													<td><?= $data['tempatlahir']?></td>
													<td><?= $data['tanggallahir']?></td>
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
													<th>Status Hubungan Dalam Rumah Tangga</th>
													<th>Kewarganegaraan</th>
													<th>Nama Ayah</th>
													<th>Nama Ibu</th>
													<th>Golongan Darah</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($main as $data): ?>
												<tr>
													<td><?= $data['no']?></td>
													<td><?= $data['status_kawin']?></td>
													<td><?= $data['hubungan']?></td>
													<td><?= $data['warganegara']?></td>
													<td><?= strtoupper($data['nama_ayah'])?></td>
													<td><?= strtoupper($data['nama_ibu'])?></td>
													<td><?= $data['golongan_darah']?></td>
												</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<p class="pull-right">Dikeluarkan Tanggal : <?= tgl_indo(date("Y m d"))?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
