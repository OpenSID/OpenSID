<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Pembangunan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel"method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('pembangunan/form') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
								<i class="fa fa-plus"></i>Tambah Data
							</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-12">
											<div class="row">
												<div class="col-sm-2">
												<select class="form-control input-sm select2" id="tahun" name="tahun" style="width:100%;">
													<option selected value="semua">Semua Tahun</option>
													<?php foreach ($list_tahun as $list) : ?>
														<option value="<?= $list->tahun_anggaran ?>"><?= $list->tahun_anggaran ?></option>
													<?php endforeach; ?>
												</select>
												</div>
											</div>
											<hr>
											<div class="table-responsive">
												<table id="tabel-pembangunan" class="table table-bordered dataTable table-hover">
													<thead class="bg-gray">
														<tr>
															<th class="text-center">No</th>
															<th width="230px" class="text-center">Aksi</th>
															<th class="text-center">Nama Kegiatan</th>
															<th class="text-center">Sumber Dana</th>
															<th class="text-center">Anggaran</th>
															<th class="text-center">Persentase</th>
															<th class="text-center">Volume</th>
															<th class="text-center">Tahun</th>
															<th class="text-center">Pelaksana</th>
															<th class="text-center">Lokasi</th>
															<th class="text-center">Gambar</th>
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
	$(document).ready(function() {
		let tabelPembangunan = $('#tabel-pembangunan').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [
				[6, 'desc'],
				[2, 'asc'],
				[5, 'asc']
			],
			'columnDefs': [{
				'orderable': false,
				'targets': [0, 1, 6, 10],
			}],
			'ajax': {
				'url': "<?= site_url('pembangunan') ?>",
				'method': 'POST',
				'data': function(d) {
					d.tahun = $('#tahun').val();
				}
			},
			'columns': [
				{
					'data': null,
				},
				{
					'data': function(data) {
						let status;
						if (data.status == 1) {
							status = `<a href="<?= site_url('pembangunan/lock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Pembangunan"><i class="fa fa-unlock"></i></a>`
						} else {
							status = `<a href="<?= site_url('pembangunan/unlock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Pembangunan"><i class="fa fa-lock"></i></a>`
						}

						return `
							<a href="<?= site_url('pembangunan/form/'); ?>${data.id}" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
							<a href="<?= site_url('pembangunan/lokasi_maps/'); ?>${data.id}" class="btn bg-olive btn-flat btn-sm" title="Lokasi Pembangunan"><i class="fa fa-map"></i></a>
							<a href="<?= site_url('pembangunan_dokumentasi/show/'); ?>${data.id}" class="btn bg-purple btn-flat btn-sm" title="Rincian Dokumentasi Kegiatan"><i class="fa fa-list-ol"></i></a>
							${status}
							<a href="#" data-href="<?= site_url('pembangunan/delete/'); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
							<a href="<?= site_url('pembangunan/info_pembangunan/'); ?>${data.id}" target="_blank" class="btn bg-blue btn-flat btn-sm" title="Lihat Summary"><i class="fa fa-eye"></i></a>
							`
					}
				},
				{
					'data': 'judul'
				},
				{
					'data': 'sumber_dana'
				},
				{
					'data': 'anggaran',
					'render': $.fn.dataTable.render.number( ',', '.', 0, 'Rp ' )
				},
				{
					'data': 'max_persentase'
				},
				{
					'data': 'volume'
				},
				{
					'data': 'tahun_anggaran'
				},
				{
					'data': 'pelaksana_kegiatan'
				},
				{
					'data': 'alamat'
				},
				{
					'data': function (data) {
						return `<div class="user-panel">
									<div class="image2">
										<img src="<?= base_url(LOKASI_GALERI) ?>${data.foto}" class="img-circle" alt="Gambar Dokumentasi">
									</div>
								</div>`
					}
				},
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabelPembangunan.on('draw.dt', function() {
			let PageInfo = $('#tabel-pembangunan').DataTable().page.info();
			tabelPembangunan.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#tahun').on('select2:select', function (e) {
			tabelPembangunan.ajax.reload();
		});
	});
</script>
