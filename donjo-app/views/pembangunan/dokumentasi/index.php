<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Dokumentasi Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Jenis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel"method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('pembangunan_dokumentasi/form') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
								<i class="fa fa-plus"></i>Tambah Data
							</a>
							<a href="<?= site_url("pembangunan/dialog_daftar/{$pembangunan->id}/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Pembangunan" title="Cetak Data Pembangunan <?= $pembangunan->judul ?> "><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("pembangunan/dialog_daftar/{$pembangunan->id}/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Pembangunan" title="Unduh Data Pembangunan <?= $pembangunan->judul ?> "><i class="fa fa-download "></i> Unduh</a>
							<a href="<?= site_url('pembangunan') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Pembagunan"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
						</div>
						<div class="box-body">
							<h5 class="text-bold">Rincian Dokumentasi Pembangunan</h5>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover tabel-rincian">
									<tbody>
										<tr>
											<td width="20%">Nama Kegiatan</td>
											<td width="1">:</td>
											<td><?= $pembangunan->judul ?></td>
										</tr>
										<tr>
											<td>Sumber Dana</td>
											<td> : </td>
											<td><?= $pembangunan->sumber_dana ?></td>
										</tr>
										<tr>
											<td>Lokasi Pembangunan</td>
											<td> : </td>
											<td><?= $pembangunan->alamat ?></td>
										</tr>
										<tr>
											<td>Keterangan</td>
											<td> : </td>
											<td><?= $pembangunan->keterangan ?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table id="tabel-dokumentasi" class="table table-bordered dataTable table-hover">
													<thead class="bg-gray">
														<tr>
															<th width="20px" class="text-center">No</th>
															<th width="80px" class="text-center">Aksi</th>
															<th class="text-center">Gambar</th>
															<th class="text-center">Persentase</th>
															<th class="text-center">Keterangan</th>
															<th class="text-center">Tgl Rekam</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
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
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(function() {
		let tabelDokumentasi = $('#tabel-dokumentasi').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[3, 'asc']
			],
			'columnDefs': [{
				'orderable': false,
				'targets': [0,1,2]
			}],

			'ajax': {
				'url': "<?= site_url("pembangunan_dokumentasi/show/{$pembangunan->id}") ?>",
				'method': 'POST'
			},
			'columns': [
				{'data': null},
				{
					'data': function(data) {
						return `<a href="<?= site_url("pembangunan_dokumentasi/form/"); ?>${data.id}" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
								<a href="#" data-href="<?= site_url("pembangunan_dokumentasi/delete/{$pembangunan->id}/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
							   `
					}, 'class': 'text-center'
				},
				{
					'data': function (data) {
						return `<div class="user-panel">
									<div class="image2">
										<img src="<?= base_url(LOKASI_GALERI) ?>${data.gambar}" class="img-circle" alt="Foto Dokumentasi">
									</div>
								</div>`
					}
				},
				{'data': 'persentase'},
				{'data': 'keterangan'},
				{'data': 'created_at'}
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabelDokumentasi.on('draw.dt', function() {
			let PageInfo = $('#tabel-dokumentasi').DataTable().page.info();
			tabelDokumentasi.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
	});
</script>
