<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian C-DESA</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('cdesa')?>"> Daftar C-DESA</a></li>
			<li class="active">Rincian C-DESA</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?=site_url('cdesa/create_mutasi/' . $cdesa['id'])?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Persil">
					<i class="fa fa-plus"></i>Tambah Mutasi Persil
				<?php endif; ?>
				</a>
				<a href="<?=site_url('cdesa')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-DESA</a>
				<a href="<?= site_url('cdesa/form_c_desa/' . $cdesa['id'])?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
					<i class="fa fa-print"></i>Cetak C-DESA
				</a>
			</div>
			<div class="box-body">
				<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<form id="mainform" name="mainform" method="post">
						<input type="hidden" name="id" value="<?= $this->uri->segment(4) ?>">
						<div class="box-header with-border">
							<h3 class="box-title">Rincian C-DESA</h3>
						</div>
						<div class="box-body">
							<table class="table table-bordered table-striped table-hover tabel-rincian">
								<tbody>
									<tr>
										<td width="20%">Nama Pemilik</td>
										<td width="1%">:</td>
										<td><?= $pemilik['namapemilik']?></td>
									</tr>
									<tr>
										<td>NIK</td>
										<td>:</td>
										<td><?= $pemilik['nik']?></td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td>:</td>
										<td><?= $pemilik['alamat']?></td>
									</tr>
									<tr>
										<td>Nomor C-DESA</td>
										<td>:</td>
										<td><?= sprintf('%04s', $cdesa['nomor'])?></td>
									</tr>
									<tr>
										<td>Nama Pemilik Tertulis di C-Desa</td>
										<td>:</td>
										<td><?= $cdesa['nama_kepemilikan']?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title">Daftar Persil C-Desa</h3>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped dataTable table-hover">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th>No</th>
											<th>Aksi</th>
											<th>No. Persil : No. Urut Bidang</th>
											<th>Kelas Tanah</th>
											<th>Lokasi</th>
											<th>Luas Keseluruhan Persil (M2)</th>
											<th>Jumlah Mutasi</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($persil as $key => $item): ?>
											<tr>
												<td class="text-center padat"><?= ($key + 1); ?></td>
												<td nowrap class="padat">
													<a href='<?= site_url("cdesa/mutasi/{$cdesa['id']}/{$item['id']}")?>' class="btn bg-maroon btn-flat btn-sm"  title="Daftar Mutasi"><i class="fa fa-exchange"></i></a>
													<?php if ($this->CI->cek_hak_akses('u', 'data_persil')): ?>
														<a href="<?= site_url("data_persil/form/{$item['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
													<?php endif; ?>
													<a href="#" data-path="<?=  $item['path']?>" class="btn bg-olive btn-flat btn-sm area-map" title="Lihat Map" data-toggle="modal" data-target="#map-modal" ><i class="fa fa-map"></i></a>
												</td>
												<td>
													<a href="<?= site_url('data_persil/rincian/' . $item['id'])?>">
														<?= $item['nomor'] . ' : ' . $item['nomor_urut_bidang']?>
														<?php if ($cdesa['id'] == $item['cdesa_awal']): ?>
															<code>( Pemilik awal )</code>
														<?php endif; ?>
													</a>
												</td>
												<td><?= $item['kelas_tanah']?></td>
												<td><?= $item['alamat'] ?: $item['lokasi']?></td>
												<td><?= $item['luas_persil']?></td>
												<td><?= $item['jml_mutasi']?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- modal -->
<div id="map-modal" class="modal fade" role="dialog" style="padding-top:30px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Lokasi Tanah</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<input type="hidden" name="path" id="path" value="">
						<input type="hidden" name="zoom" id="zoom" value="8">
						<div id="map" style="width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// deklarasi variable diluar fungsi agar terbaca di semua fungsi
	var peta_area;
		<?php if (! empty($desa['lat']) && ! empty($desa['lng'])): ?>
  		var posisi = [<?=$desa['lat'] . ',' . $desa['lng']?>];
  		var zoom = <?=$desa['zoom'] ?: 18?>;
  	<?php else: ?>
  		var posisi = [-1.0546279422758742,116.71875000000001];
  		var zoom = 4;
  	<?php endif; ?>

	$(document).ready(function() {
		$(document).on('shown.bs.modal','#map-modal', function(event) {
			if (L.DomUtil.get('map')._leaflet_id  == undefined) {
				peta_area = L.map('map').setView(posisi, zoom);

				//Menampilkan BaseLayers Peta
				var baseLayers = getBaseLayers(peta_area, '');

				//Import Peta dari file SHP
				//eximShp(peta_area);

				//Geolocation IP Route/GPS
				geoLocation(peta_area);

				//Menambahkan Peta wilayah
				addPetaPoly(peta_area);
				// end tampilkan map
			}

			var wilayah = $(event.relatedTarget).data('path');
			clearMap(peta_area);
			showCurrentArea(wilayah, peta_area)
		});
	});
</script>