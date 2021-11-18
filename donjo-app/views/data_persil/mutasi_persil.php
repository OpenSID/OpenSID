<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Mutasi C-DESA</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('cdesa')?>"> Daftar C-DESA</a></li>
			<li><a href="<?= site_url('cdesa/rincian/' . $cdesa[id])?>"> Rincian C-DESA</a></li>
			<li class="active">Mutasi C-Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?=site_url('cdesa/create_mutasi/' . $cdesa['id']) . '/' . $persil['id']?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Persil">
						<i class="fa fa-plus"></i>Tambah Mutasi Persil
					</a>
				<?php endif; ?>
				<a href="<?=site_url('cdesa')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar C-DESA</a>
				</a>
				<a href="<?=site_url('cdesa/rincian/' . $cdesa[id])?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar C-DESA"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian C-DESA</a>
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
										<th>NIK</td>
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
										<td><?= $cdesa['nomor']?></td>
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
										<td>Kelas Tanah</td>
										<td>:</td>
										<td><?= $persil['kode'] . ' - ' . $persil['ndesc']?></td>
									</tr>
									<tr>
										<td>Luas Keseluruhan (M2)</td>
										<td>:</td>
										<td><?= $persil['luas_persil']?></td>
									</tr>
									<tr>
										<td>Lokasi</td>
										<td>:</td>
										<td><?= $persil['alamat'] ?: $persil['lokasi']?></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="box-header with-border">
							<h3 class="box-title">Daftar Mutasi Persil <?= $persil['nomor']?></h3>
						</div>
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped dataTable table-hover">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th class="padat">No</th>
											<?php if ($this->CI->cek_hak_akses('u')): ?>
												<th class="padat">Aksi</th>
											<?php endif; ?>
											<th>No. Bidang Mutasi</th>
											<th>Luas Masuk (M2)</th>
											<th>Luas Keluar (M2)</th>
											<th>NOP</th>
											<th>Tanggal Mutasi</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tbody>
										<?php $nomer = $paging->offset; ?>
										<?php foreach ($mutasi as $key => $item): $nomer++; ?>
											<tr>
												<td class="text-center"><?= $nomer?></td>
												<?php if ($this->CI->cek_hak_akses('u')): ?>
													<td nowrap class="text-center">
														<a href="<?= site_url("cdesa/create_mutasi/{$item['id_cdesa_masuk']}/{$item['id_persil']}/{$item['id']}")?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
														<a href="#" data-path="<?=  $item['path']?>" class="btn bg-olive btn-flat btn-sm area-map" title="Lihat Map" data-toggle="modal" data-target="#map-modal" ><i class="fa fa-map"></i></a>
														<?php if ($item['jenis_mutasi'] != '9'): ?>
															<?php if ($this->CI->cek_hak_akses('h')): ?>
																<a href="#" data-href="<?= site_url("cdesa/hapus_mutasi/{$cdesa['id']}/{$item['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
															<?php endif; ?>
														<?php else: ?>
															<a href="#" data-href="<?= site_url('cdesa/awal_persil/' . $cdesa['id'] . '/' . $persil['id'] . '/1')?>" class="btn bg-maroon btn-flat btn-sm"  title="Bukan pemilik awal" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														<?php endif; ?>
													</td>
												<?php endif; ?>
												<td><?= $item['no_bidang_persil']?></td>
												<td><?= $item['luas_masuk']?>
													<?php if ($item['cdesa_keluar'] && $item['id_cdesa_masuk'] == $cdesa['id']): ?>
														dari <a href="<?= site_url("cdesa/mutasi/{$item['cdesa_keluar']}/{$item['id_persil']}")?>">C-Desa ini</a>
													<?php endif; ?>
												</td>
												<td><?= $item['luas_keluar']?>
													<?php if ($item['id_cdesa_masuk'] != $cdesa['id']): ?>
														ke <a href="<?= site_url("cdesa/mutasi/{$item['id_cdesa_masuk']}/{$item['id_persil']}")?>">C-Desa ini</a>
													<?php endif; ?>
												</td>
												<td><?= $item['no_objek_pajak']?></td>
												<td><?= tgl_indo_out($item['tanggal_mutasi'])?></td>
												<td><?= $item['keterangan']?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
				<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
								<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
							</div>
							<div class='modal-body btn-info'>
								Apakah Anda yakin ingin menghapus data ini?
							</div>
							<div class='modal-footer'>
								<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
								<a class='btn-ok'>
									<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
								</a>
							</div>
						</div>
					</div>
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
				<h4 class="modal-title">Lokasi </h4>
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
		$(document).on('shown.bs.modal','#map-modal', function(event)
		{
			if (L.DomUtil.get('map')._leaflet_id  == undefined)
			{
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