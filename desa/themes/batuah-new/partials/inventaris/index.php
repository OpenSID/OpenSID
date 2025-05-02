<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="center-head bggrad-color2 flexcenter">
						<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M21 10V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.9 21 5 21H11V19.13L19.39 10.74C19.83 10.3 20.39 10.06 21 10M14 4.5L19.5 10H14V4.5M22.85 14.19L21.87 15.17L19.83 13.13L20.81 12.15C21 11.95 21.33 11.95 21.53 12.15L22.85 13.47C23.05 13.67 23.05 14 22.85 14.19M19.13 13.83L21.17 15.87L15.04 22H13V19.96L19.13 13.83Z" /></svg></div>
						<h1>Inventaris</h1>
					</div>
					<div class="pagebox gridstyle artikelpage">
						<div class="statishead"><h1>Data Inventaris <?= ucwords(setting('sebutan_desa')) ?></h1></div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-striped table-bordered" id="inventaris">
											<thead class="bg-gray">
												<tr>
													<th class="text-center" rowspan="3" style="vertical-align: middle;">No</th>
													<th class="text-center" rowspan="3" style="vertical-align: middle;">Jenis Barang</th>
													<th class="text-center" width="340%" rowspan="3" style="vertical-align: middle;">Keterangan</th>
													<th class="text-center" colspan="5" style="vertical-align: middle;">Asal barang</th>
													<th class="text-center" rowspan="3" style="vertical-align: middle;">Aksi</th>
												</tr>
												<tr>
													<th class="text-center" rowspan="2">Dibeli Sendiri</th>
													<th class="text-center" colspan="3">Bantuan</th>
													<th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
												</tr>
												<tr>
													<th class="text-center" >Pemerintah</th>
													<th class="text-center" >Provinsi</th>
													<th class="text-center" >Kabupaten</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td nowrap>Tanah Kas Desa</td>
													<td>Informasi mengenai segala yang menyangkut dengan tanah (dalam hal ini tanah yang digunakan dalam instansi tersebut).</td>
													<td>
														<?= $inventaris_tanah_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_tanah_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_tanah_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_tanah_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_tanah_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/tanah'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
												<tr>
													<td>2</td>
													<td nowrap>Peralatan dan Mesin</td>
													<td>Informasi mengenai peralatan dan mesin</td>
													<td>
														<?= $inventaris_peralatan_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_peralatan_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_peralatan_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_peralatan_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_peralatan_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/peralatan-dan-mesin'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
												<tr>
													<td>3</td>
													<td nowrap>Gedung dan Bangunan</td>
													<td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
													<td>
														<?= $inventaris_gedung_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_gedung_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_gedung_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_gedung_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_gedung_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/gedung-dan-bangunan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
												<tr>
													<td>4</td>
													<td nowrap> Jalan Irigasi dan Jaringan</td>
													<td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
													<td>
														<?= $inventaris_jalan_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_jalan_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_jalan_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_jalan_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_jalan_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/jalan-irigasi-dan-jaringan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
												<tr>
													<td>5</td>
													<td nowrap> Asset Tetap Lainnya</td>
													<td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
													<td>
														<?= $inventaris_asset_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_asset_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_asset_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_asset_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_asset_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/asset-tetap-lainnya'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
												<tr>
													<td>6</td>
													<td nowrap>Konstruksi Dalam Pengerjaan</td>
													<td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
													<td>
														<?= $inventaris_kontruksi_pribadi->total?>
													</td>
													<td>
														<?= $inventaris_kontruksi_pemerintah->total?>
													</td>
													<td>
														<?= $inventaris_kontruksi_provinsi->total?>
													</td>
													<td>
														<?= $inventaris_kontruksi_kabupaten->total?>
													</td>
													<td>
														<?= $inventaris_kontruksi_sumbangan->total?>
													</td>
													<td>
														<div class="btn-group" role="group" aria-label="...">
															<a href="<?= site_url('inventaris/konstruksi-dalam-pengerjaan'); ?>" title="Lihat Data" type="button" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
														</div>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="3" class="text-center">Total</th>
													<th><?= $inventaris_tanah_pribadi->total + $inventaris_peralatan_pribadi->total + $inventaris_gedung_pribadi->total + $inventaris_jalan_pribadi->total + $inventaris_asset_pribadi->total + $inventaris_kontruksi_pribadi->total?></th>
													<th><?= $inventaris_tanah_pemerintah->total + $inventaris_peralatan_pemerintah->total + $inventaris_gedung_pemerintah->total + $inventaris_jalan_pemerintah->total + $inventaris_asset_pemerintah->total + $inventaris_kontruksi_pemerintah->total?></th>
													<th><?= $inventaris_tanah_provinsi->total + $inventaris_peralatan_provinsi->total + $inventaris_gedung_provinsi->total + $inventaris_jalan_provinsi->total + $inventaris_asset_provinsi->total + $inventaris_kontruksi_provinsi->total?></th>
													<th><?= $inventaris_tanah_kabupaten->total + $inventaris_peralatan_kabupaten->total + $inventaris_gedung_kabupaten->total + $inventaris_jalan_kabupaten->total + $inventaris_asset_kabupaten->total + $inventaris_kontruksi_kabupaten->total?></th>
													<th><?= $inventaris_tanah_sumbangan->total + $inventaris_peralatan_sumbangan->total + $inventaris_gedung_sumbangan->total + $inventaris_jalan_sumbangan->total + $inventaris_asset_sumbangan->total + $inventaris_kontruksi_sumbangan->total?></th>
													<th></th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php $this->load->view("$folder_themes/partials/inventaris/script") ?>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>