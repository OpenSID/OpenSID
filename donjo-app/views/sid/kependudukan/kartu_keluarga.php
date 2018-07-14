<div class="content-wrapper">
	<section class="content-header">
		<h1>Salinan Kartu Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
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
							<a href="<?php echo site_url("keluarga/form_a/$p/$o/$id_kk")?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Anggota</a>
							<a href="<?php echo site_url("keluarga/cetak_kk/$id_kk")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?php echo site_url("keluarga/doc_kk/$id_kk")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
							<a href="<?=site_url("keluarga/anggota/$p/$o/$id_kk")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Anggota Keluarga">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Anggota Keluarga
							</a>
						</div>
						<div class="box-header">
							<h3 class="text-center"><strong>SALINAN KARTU KELUARGA</strong></h3>
							<h5 class="text-center"><strong>No.  <?php echo unpenetration($kepala_kk['no_kk'])?> </strong></h5>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-8">
									<div class="form-group">
										<label class="col-sm-3 control-label">ALAMAT</label>
										<div class="col-sm-8">
											<p class="text-muted">: <?php echo strtoupper($kepala_kk['alamat_plus_dusun'])?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">RT/RW</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?php echo$kepala_kk['rt']  ?> / <?php echo $kepala_kk['rw']  ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">DESA / KELURAHAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?php echo strtoupper(unpenetration($desa['nama_desa'])) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">KECAMATAN</label>
										<div class="col-sm-9">
											<p class="text-muted">: <?php echo strtoupper(unpenetration($desa['nama_kecamatan'])) ?></p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-sm-5 control-label">KABUPATEN</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?php echo strtoupper(unpenetration($desa['nama_kabupaten'])) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">KODE POS</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?php echo $desa['kode_pos'] ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">PROVINSI</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?php echo strtoupper(unpenetration($desa['nama_propinsi'])) ?></p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">JUMLAH ANGGOTA</label>
										<div class="col-sm-7">
											<p class="text-muted">: <?php echo count($main)?></p>
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
												<?php  foreach($main as $key => $data): ?>
												<tr>
													<td><?php echo $key+1?></td>
													<td><?php echo strtoupper(unpenetration($data['nama']))?></td>
													<td><?php echo $data['nik']?></td>
													<td><?php echo $data['sex']?></td>
													<td><?php echo $data['tempatlahir']?></td>
													<td><?php echo tgl_indo_out($data['tanggallahir'])?></td>
													<td><?php echo $data['agama']?></td>
													<td><?php echo $data['pendidikan']?></td>
													<td><?php echo $data['pekerjaan']?></td>
												</tr>
												<?php  endforeach; ?>
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
												<?php  foreach($main as $key => $data): ?>
												<tr>
													<td><?php echo $key+1?></td>
													<td><?php echo $data['status_kawin']?></td>
													<td><?php echo $data['hubungan']?></td>
													<td><?php echo $data['warganegara']?></td>
													<td><?php echo $data['dokumen_pasport']?></td>
													<td><?php echo $data['dokumen_kitas']?></td>
													<td><?php echo strtoupper($data['nama_ayah'])?></td>
													<td><?php echo strtoupper($data['nama_ibu'])?></td>
													<td><?php echo $data['golongan_darah']?></td>
												</tr>
												<?php  endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<p class="pull-right">Dikeluarkan Tanggal : <?php echo tgl_indo(date("Y m d"))?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

