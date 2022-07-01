<form id="mainform" name="mainform" method="post" class="">
	<div class="box box-info">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-sm-2">
					<select class="form-control input-sm select2" name="tahun" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/tahun') ?>')">
						<option value="semua" selected>Semua Tahun</option>
						<?php if ($min_tahun): ?>
							<?php for ($i = date('Y'); $i >= $min_tahun; $i--) : ?>
								<option value="<?= $i ?>" <?= selected($tahun, $i) ?>><?= $i ?></option>
							<?php endfor; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="col-sm-10">
					<a href="#" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#cetakBox" data-title="Cetak Inventaris">
						<i class="fa fa-print"></i>Cetak
					</a>
					<a href="#" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#unduhBox" data-title="Unduh Inventaris">
						<i class="fa fa-download"></i>Unduh
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="table-responsive">
							<table id="tabelpermendagri" class="table table-bordered table-hover">
								<thead class="bg-gray">
									<tr>
										<th class="text-center" rowspan="3">No</th>
										<th class="text-center" rowspan="3">Jenis Barang/Bangunan</th>
										<th class="text-center" rowspan="1" colspan="5">Asal Barang/Bangungan</th>
										<th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan AWal Tahun</th>
										<th class="text-center" rowspan="1" colspan="4">Penghapusan Barang Dan Bangunan</th>
										<th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan Akhir Tahun</th>
										<th class="text-center" rowspan="3">Ket</th>
									</tr>
									<tr>
										<th class="text-center" rowspan="2">Dibeli Sendiri</th>
										<th class="text-center" rowspan="1" colspan="3">Bantuan</th>
										<th class="text-center" rowspan="2">Sumbangan</th>
										<th class="text-center" rowspan="2" width="70px">Baik</th>
										<th class="text-center" rowspan="2" width="70px">Rusak</th>
										<th class="text-center" rowspan="2">Rusak</th>
										<th class="text-center" rowspan="2">Dijual</th>
										<th class="text-center" rowspan="2">Disumbangkan</th>
										<th class="text-center" rowspan="2">Tgl Penghapusan</th>
										<th class="text-center" rowspan="2" width="70px">Baik</th>
										<th class="text-center" rowspan="2" width="70px">Rusak</th>
									</tr>
									<tr>
										<th>Pemerintah</th>
										<th>Provinsi</th>
										<th>Kab/Kota</th>
									</tr>
									<tr>
										<th class="text-center">1</th>
										<th class="text-center">2</th>
										<th class="text-center">3</th>
										<th class="text-center">4</th>
										<th class="text-center">5</th>
										<th class="text-center">6</th>
										<th class="text-center">7</th>
										<th class="text-center">8</th>
										<th class="text-center">9</th>
										<th class="text-center">10</th>
										<th class="text-center">11</th>
										<th class="text-center">12</th>
										<th class="text-center">13</th>
										<th class="text-center">14</th>
										<th class="text-center">15</th>
										<th class="text-center">16</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1 ?>
									<?php foreach ($data as $uraian => $asset) : ?>
										<tr>
											<td><?= $i ?></td>
											<td><?= $uraian ?></td>
											<td class="text-center"><?= count($asset['Pembelian Sendiri']) ?></td>
											<td class="text-center"><?= count($asset['Bantuan Pemerintah']) ?></td>
											<td class="text-center"><?= count($asset['Bantuan Provinsi']) ?></td>
											<td class="text-center"><?= count($asset['Bantuan Kabupaten']) ?></td>
											<td class="text-center"><?= count($asset['Sumbangan']) ?></td>
											<td class="text-center"><?= count($asset['awal_baik']) ?></td>
											<td class="text-center"><?= count($asset['awal_rusak']) ?></td>
											<td class="text-center"><?= count($asset['hapus_rusak']) ?></td>
											<td class="text-center"><?= count($asset['hapus_jual']) ?></td>
											<td class="text-center"><?= count($asset['hapus_sumbang']) ?></td>
											<td class="text-center"><?= tgl_indo($asset['tgl_hapus']) ?></td>
											<td class="text-center"><?= count($asset['akhir_baik']) ?></td>
											<td class="text-center"><?= count($asset['akhir_rusak']) ?></td>
											<td>
												<ul>
													<?php foreach ($asset['keterangan'] as $ket) : ?>
														<li><?= $ket ?></li>
													<?php endforeach ?>
												</ul>
											</td>
										</tr>
										<?php $i++ ?>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php $this->load->view('bumindes/umum/permen47_cetak') ?>
<?php $this->load->view('bumindes/umum/permen47_unduh') ?>

<script>
	$("#form_cetak").click(function(event) {
		var link = '<?= site_url('laporan_inventaris/permendagri_47_cetak'); ?>' + '/' + $('#kades').val() + '/' + $('#sekdes').val();
		window.open(link, '_blank');
	});
	$("#form_download").click(function(event) {
		var link = '<?= site_url('laporan_inventaris/permendagri_47_excel'); ?>' + '/' + $('#kades_unduh').val() + '/' + $('#sekdes_unduh').val();
		window.open(link, '_blank');
	});

	$(document).ready(function() {
		$('#tabelpermendagri').DataTable({
			lengthChange: false,
			searching: false,
			info: false,
			ordering: false
		});
	});
</script>