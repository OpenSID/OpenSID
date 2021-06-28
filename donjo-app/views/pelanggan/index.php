<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Layanan Pelanggan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Info Layanan Pelanggan</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-body">
				<?php if (is_null($response)) : ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-ban"></i><?= "{$this->session->error_status_langganan}"?></h4>
						<h5>Data Gagal Dimuat, Harap Periksa Dibawah Ini</h5>
						<h5>Fitur ini khusus untuk pelanggan Layanan OpenDesa (hosting, Fitur Premium, dll), untuk menampilkan status langganan.</h5>
						<li>Periksa logs error terakhir di menu <strong>Pengaturan > Info Sistem > Logs</strong></li>
						<li>Token pelanggan tidak terontentikasi. Periksa [Layanan Opendesa Token] di menu <strong>Pengaturan > Aplikasi</strong></li>
						<li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.</h5>
					</div>
				<?php else : ?>
					<div class="row">
						<div class="col-lg-3 col-xs-6">
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
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-yellow">
								<div class="inner">
									<h4>STATUS PELANGGAN</h4>
									<h4><?= $response->body->status_langganan ?></h4>
								</div>
								<div class="icon">
									<i class="ion-person-add"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-green">
								<div class="inner">
									<h4>MULAI BERLANGGANAN</h4>
									<h4><?= $response->body->tanggal_berlangganan->mulai ?></h4>
								</div>
								<div class="icon">
									<i class="ion ion-unlocked"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-red">
								<div class="inner">
									<h4>AKHIR BERLANGGANAN</h4>
									<h4><?= $response->body->tanggal_berlangganan->akhir ?></h4>
								</div>
								<div class="icon">
									<i class="ion ion-locked"></i>
								</div>
							</div>
						</div>
					</div>
					<h5 class="text-bold">Rincian Pelanggan Desa</h5>
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
								<tr>
									<td>Token Pelanggan</td>
									<td> : </td>
									<td><textarea disabled cols="140" rows="4"><?= $this->setting->demo_mode ? '' : $response->body->token ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<h5 class="text-bold">Rincian Pemesanan Layanan</h5>
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-hover">
									<thead class="bg-gray">
										<tr>
											<th width="20px">No</th>
											<th>Nota. Faktur</th>
											<th>Tanggal Mulai</th>
											<th>Tanggal Berakhir</th>
											<th>Status Pemesanan</th>
											<th>Bukti Pembayaran</th>
										</tr>
									</thead>
									<tbody>
										<?php $number = 1 ?>
										<?php foreach ($response->body->pemesanan as $pemesanan) : ?>
											<tr>
												<td><?= $number ?></td>
												<td>
													<a href="<?= "#" . str_replace('/', '-', $pemesanan->faktur) ?>" data-toggle="modal" data-target="<?= "#" . str_replace('/', '-', $pemesanan->faktur) ?>"><?= $pemesanan->faktur ?></a>
													<div class="modal fade" id="<?= str_replace('/', '-', $pemesanan->faktur) ?>" style="display: none;">
														<div class="modal-dialog modal-lg">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">×</span>
																	</button>
																	<h4 class="modal-title">Rincian Pemesanan Layanan <strong><?= $pemesanan->faktur ?></strong></h4>
																</div>
																<div class="modal-body">
																	<div class="row">
																		<div class="col-sm-12">
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover">
																					<thead class="bg-gray">
																						<tr>
																							<th width="20px">No</th>
																							<th>Layanan</th>
																							<th>Harga (Rp)</th>
																							<th>Keterangan</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php $numberLayanan = 1 ?>
																						<?php foreach ($pemesanan->layanan as $layanan) : ?>
																							<tr>
																								<td><?= $numberLayanan ?></td>
																								<td><?= $layanan->nama ?></td>
																								<td><?= number_format($layanan->harga, 2, ',', '.') ?></td>
																								<td><?= $layanan->deskripsi ?></td>
																							</tr>
																							<?php $numberLayanan++ ?>
																						<?php endforeach ?>
																					</tbody>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-flat btn-sm btn-info" data-dismiss="modal">Tutup</button>
																</div>
															</div>
														</div>
													</div>
												</td>
												<td><?= $pemesanan->tgl_mulai ?></td>
												<td><?= $pemesanan->tgl_akhir ?></td>
												<td class="text-center">
													<span class="label label-<?= $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' ?>"><?= $pemesanan->status_pemesanan ?></span>
												</td>
												<td>
													<a href="<?= "#{$pemesanan->id}" ?>" data-toggle="modal" data-target="<?= "#{$pemesanan->id}" ?>"><?= parse_url($pemesanan->bukti, PHP_URL_PATH) ?></a>
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
																	<button type="button" class="btn btn-flat btn-sm btn-info" data-dismiss="modal">Tutup</button>
																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
											<?php $number++ ?>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</section>
</div>