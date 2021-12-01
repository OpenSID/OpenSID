<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Layanan Pelanggan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Info Layanan Pelanggan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<?php if (is_null($response)) : ?>
			<div class="box box-danger">
				<div class="box-header with-border">
					<i class="icon fa fa-ban"></i>
					<h3 class="box-title"><?= "{$this->session->error_status_langganan}" ?></h3>
				</div>
				<div class="box-body">
					<div class="callout callout-danger">
						<h5>Data Gagal Dimuat, Harap Periksa Dibawah Ini</h5>
						<h5>Fitur ini khusus untuk pelanggan Layanan OpenDesa (hosting, Fitur Premium, dll) untuk menampilkan status langganan.</h5>
						<li>Periksa log error terakhir di folder <b>logs</b></a></strong></li>
						<li>Token pelanggan tidak terontentikasi. Periksa [Layanan Opendesa Token] di <a href="#" style="text-decoration:none;" data-remote="false" data-toggle="modal" data-title="Pengaturan <?= ucwords($this->controller); ?>" data-target="#pengaturan"><strong>Pengaturan Pelanggan&nbsp;(<i class="fa fa-gear"></i>)</strong></a></li>
						<li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-blue">
						<div class="inner">
							<h4>PEMESANAN LAYANAN</h4>
							<h6>
								<?php foreach ($response->body->pemesanan as $pemesanan) : ?>
									<?php foreach ($pemesanan->layanan as $layanan) : ?>
										<li><?= $layanan->nama ?></li>
									<?php endforeach ?>
								<?php endforeach ?>
							</h6>
						</div>
						<div class="icon">
							<i class="ion ion-card"></i>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-yellow">
						<div class="inner">
							<h4>STATUS PELANGGAN</h4>
							<h5><?= ucwords($response->body->status_langganan); ?></h5>
						</div>
						<div class="icon">
							<i class="ion-person-add"></i>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-green">
						<div class="inner">
							<h4>MULAI BERLANGGANAN</h4>
							<h5><?= tgl_indo($response->body->tanggal_berlangganan->mulai); ?></h5>
						</div>
						<div class="icon">
							<i class="ion ion-unlocked"></i>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="small-box bg-red">
						<div class="inner">
							<h4>AKHIR BERLANGGANAN</h4>
							<h5><?= tgl_indo($response->body->tanggal_berlangganan->akhir); ?></h5>
						</div>
						<div class="icon">
							<i class="ion ion-locked"></i>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-info">
				<div class="box-body">
					<h5 class="text-bold">Rincian Pelanggan</h5>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover tabel-rincian">
							<tbody>
								<tr>
									<td width="20%">ID Pelanggan</td>
									<td width="1">:</td>
									<td><?= $response->body->id ?></td>
								</tr>
								<tr>
									<td>KODE <?= strtoupper($this->setting->sebutan_desa) ?></td>
									<td> : </td>
									<td><?= $response->body->desa->kode_desa ?></td>
								</tr>
								<tr>
									<td><?= strtoupper($this->setting->sebutan_desa) ?></td>
									<td> : </td>
									<td><?= "Desa {$response->body->desa->nama_desa}, Kecamatan {$response->body->desa->nama_kec}, Kabupaten {$response->body->desa->nama_kab}, Provinsi {$response->body->desa->nama_prov}" ?></td>
								</tr>
								<tr>
									<td>Domain Desa</td>
									<td> : </td>
									<td><?= $response->body->domain ?></td>
								</tr>
								<tr>
									<td>Nama Kontak</td>
									<td> : </td>
									<td>
										<?php foreach ($response->body->kontak as $kontak) : ?>
											<li><?= $kontak->nama ?></li>
										<?php endforeach ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<hr />
					<div class="row">
						<div class="col-sm-12">
							<h5 class="text-bold">Rincian Pemesanan</h5>
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-hover tabel-daftar">
									<thead class="bg-gray">
										<tr>
											<th>No</th>
											<th>Aksi</th>
											<th>Layanan</th>
											<th>Tanggal Mulai</th>
											<th>Tanggal Berakhir</th>
											<th>Status Pemesanan</th>
											<th>Status Pembayaran</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($response->body->pemesanan as $number => $pemesanan) : ?>
											<tr>
												<td class="padat"><?= ($number + 1) ?></td>
												<td class="aksi">
													<?php
														$server = config_item('server_layanan');
														$token = $this->setting->layanan_opendesa_token;
													?>
													<a target="_blank" href="<?= "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" ?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Nota Faktur"><i class="fa fa-print"></i>Cetak Nota Faktur</a>
													<a href="#" data-toggle="modal" data-target="<?= "#{$pemesanan->id}" ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bukti Pembayaran"><i class="fa fa-file"></i>Bukti Pembayaran</a>
												</td>
												<td>
													<?php foreach ($pemesanan->layanan as $key => $layanan) : ?>
														<li>
															<a href="#" data-parent="#layanan" data-target="<?= "#" . url_title($layanan->nama, 'dash', true) ?>" data-toggle="collapse"><?= $layanan->nama; ?></a>
														</li>
													<?php endforeach; ?>
												</td>
												<td class="padat"><?= tgl_indo($pemesanan->tgl_mulai); ?></td>
												<td class="padat"><?= tgl_indo($pemesanan->tgl_akhir); ?></td>
												<td class="padat">
													<span class="label label-<?= $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' ?>"><?= $pemesanan->status_pemesanan ?></span>
												</td>
												<td class="padat">
													<span class="label label-<?= $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' ?>"><?= $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' ?></span>
												</td>
												<div class="modal fade" id="<?= $pemesanan->id ?>" style="display: none;">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">×</span>
																</button>
																<h4 class="modal-title">Bukti Pembayaran</h4>
															</div>
															<div class="modal-body">
																<img class="img-thumbnail" src="<?= $pemesanan->bukti ?>" alt="<?= $pemesanan->bukti ?>">
															</div>
															<div class="modal-footer">
																<a target="_blank" href="<?= $pemesanan->bukti ?>" role="button" class="btn btn-flat btn-sm bg-navy" download="<?= $pemesanan->bukti ?>">Simpan</a>
																<button type="button" class="btn btn-flat btn-sm btn-info" data-dismiss="modal">Tutup</button>
															</div>
														</div>
													</div>
												</div>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="layanan">
				<?php foreach ($response->body->pemesanan as $num1 => $pemesanan) : ?>
					<?php foreach ($pemesanan->layanan as $num2 => $layanan) : ?>
						<div id="<?= url_title($layanan->nama, 'dash', true) ?>" class="collapse">
							<div class="box box-success">
								<div class="box-header with-border">
									<div class="text-center"><b>Ketentuan <?= $layanan->nama ?> ( <?= rupiah($layanan->harga) ?> )</b></div>
									<div class="box-tools pull-right">
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
									</div>
								</div>
								<div class="box-body">
									<?= $layanan->ketentuan ?? "Belum tersedia"; ?>
								</div>
							</div>
						</div>
					<?php endforeach ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</section>
</div>