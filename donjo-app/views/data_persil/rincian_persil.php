<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Persil</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Rincian Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?=site_url('data_persil/clear')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar PersilA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Persil</a>
			</div>
			<div class="box-body">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" method="post">
						<input type="hidden" name="id" value="<?= $this->uri->segment(4) ?>">
						<div class="row">
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Rincian Persil</h3>
								</div>
								<div class="box-body">
									<table class="table table-bordered table-striped table-hover tabel-rincian">
										<tbody>
											<tr>
												<td width="20%">No. Persil : No. Urut Bidang</td>
												<td width="1%">:</td>
												<td><?= $persil['nomor'] . ' : ' . $persil['nomor_urut_bidang']?></td>
											</tr>
											<tr>
												<th>Kelas Tanah</td>
												<td>:</td>
												<td><?= $persil['kode'] . ' - ' . $persil['ndesc']?></td>
											</tr>
											<tr>
												<th>Alamat</td>
												<td>:</td>
												<td><?= $persil['alamat'] ?: $persil['lokasi']?></td>
											</tr>
											<?php if ($persil['cdesa_awal']): ?>
												<tr>
													<td>C-Desa Pemilik Awal</td>
													<td>:</td>
													<td><a href="<?= site_url("cdesa/mutasi/{$persil['cdesa_awal']}/{$persil['id']}")?>"><?= $persil['nomor_cdesa_awal']?></a></td>
												</tr>
											<?php endif; ?>
											<?php if ($persil['path'] != null || $persil['path'] != ''): ?>
												<tr>
													<td colspan="3" id="map">
														<input type="hidden" id="path" name="path" value="<?= $persil['path'] ?>">
														<input type="hidden" id="zoom" name="zoom" value="">
													</td>
												</tr>
											<?php endif ?>

										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Daftar Mutasi Persil</h3>
								</div>
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-bordered table-striped dataTable table-hover">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th>No</th>
													<th>C-Desa Masuk</th>
													<th>C-Desa Keluar</th>
													<th>No. Bidang Mutasi</th>
													<th>Luas (M2)</th>
													<th>NOP</th>
													<th>Tanggal Mutasi</th>
													<th>Keterangan</th>
												</tr>
											</thead>
											<tbody>
												<?php $nomer = 0; ?>
												<?php foreach ($mutasi as $key => $item): $nomer++; ?>
													<tr>
														<td class="text-center"><?= $nomer?></td>
														<td><a href="<?= site_url('cdesa/rincian/' . $item['id_cdesa_masuk'])?>"><?= $item['cdesa_masuk']?></a></td>
														<td><a href="<?= site_url('cdesa/rincian/' . $item['cdesa_keluar'])?>"><?= $item['cdesa_keluar']?></a></td>
														<td><?= $item['no_bidang_persil']?></td>
														<td><?= $item['luas']?></td>
														<td><?= $item['no_objek_pajak']?></td>
														<td><?= tgl_indo_out($item['tanggal_mutasi'])?></td>
														<td><?= $item['keterangan']?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// tampilkan map
		<?php if (! empty($desa['lat']) && ! empty($desa['lng'])): ?>
  		var posisi = [<?=$desa['lat'] . ',' . $desa['lng']?>];
  		var zoom = <?=$desa['zoom'] ?: 18?>;
  	<?php else: ?>
  		var posisi = [-1.0546279422758742,116.71875000000001];
  		var zoom = 4;
  	<?php endif; ?>
  	var peta_area = L.map('map').setView(posisi, zoom);

		//Menampilkan BaseLayers Peta
		var baseLayers = getBaseLayers(peta_area, '');

		if ($('input[name="path"]').val() !== '' ) {
			var wilayah = JSON.parse($('input[name="path"]').val());
 			showCurrentArea(wilayah, peta_area);
		}

		//Import Peta dari file SHP
		//eximShp(peta_area);

		//Geolocation IP Route/GPS
		geoLocation(peta_area);

		//Menambahkan Peta wilayah
		addPetaPoly(peta_area);

		// end tampilkan map
	});
</script>
<style type="text/css">
	#map {
    width:100%;
    height:310px
  }
</style>