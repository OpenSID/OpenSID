<div class="content-wrapper">
	<section class="content-header">
		<h1>Dokumentasi Pembangunan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Dokumentasi Pembangunan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainformexcel" name="mainformexcel"method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("{$this->controller}/dokumentasi_form") ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru"><i class="fa fa-plus"></i>Tambah Data</a>
							<?php endif; ?>
							<a href="<?= site_url("{$this->controller}/dialog_daftar/{$pembangunan->id}/cetak") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Pembangunan" title="Cetak Data Pembangunan <?= $pembangunan->judul ?> "><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("{$this->controller}/dialog_daftar/{$pembangunan->id}/unduh") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Pembangunan" title="Unduh Data Pembangunan <?= $pembangunan->judul ?> "><i class="fa fa-download "></i> Unduh</a>
							<a href="<?= site_url($this->controller) ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Pembagunan"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Pembangunan</a>
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
															<?php if ($this->CI->cek_hak_akses('u')): ?>
																<th width="80px" class="text-center">Aksi</th>
															<?php endif; ?>
															<th class="text-center">Gambar</th>
															<th class="text-center">Persentase</th>
															<th class="text-center">Keterangan</th>
															<th class="text-center">Tanggal Rekam</th>
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
				'url': "<?= site_url("{$this->controller}/dokumentasi/{$pembangunan->id}") ?>",
				'method': 'POST'
			},
			'columns': [
				{'data': null},
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					{
						'data': function(data) {
							return `
								<?php if ($this->CI->cek_hak_akses('u')): ?>
									<a href="<?= site_url("{$this->controller}/dokumentasi_form/"); ?>${data.id}" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
								<?php endif; ?>
								<?php if ($this->CI->cek_hak_akses('u')): ?>
									<a href="#" data-href="<?= site_url("{$this->controller}/dokumentasi_delete/{$pembangunan->id}/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
								<?php endif; ?>
							`
						}, 'class': 'aksi'
					},
				<?php endif; ?>
				{
					'data': function (data) {
						if (data.gambar) {
							return `<img src="<?= base_url(LOKASI_GALERI) ?>${data.gambar}" class="penduduk_kecil" alt="Foto Dokumentasi">`
						}
						return null
					}, 'class': 'padat'
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
