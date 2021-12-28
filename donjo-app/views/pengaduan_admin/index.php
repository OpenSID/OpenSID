<div class="content-wrapper">
	<section class="content-header">
		<h1>
			PENGADUAN
			<small>Daftar Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Data</li>
		</ol>
	</section>
	<section class="content">
		<?php $this->load->view("{$this->controller}/navigasi", $navigasi); ?>
		<div id="maincontent"></div>
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('h')): ?>
					<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("{$this->controller}/pengaduan_delete_all"); ?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<?php endif; ?>
			</div>
			<form id="mainform" name="mainform" method="post">
				<div class="box-header with-border form-inline">
					<div class="row">
						<div class="col-sm-2">
							<select class="form-control input-sm select2" id="status" name="status">
								<option value="">Semua Status</option>
								<option value="1">Menunggu Diproses</option>
								<option value="2">Sedang Diproses</option>
								<option value="3">Selesai Diproses</option>
							</select>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-pengaduan">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th><input type="checkbox" id="checkall"/></th>
									<th>No</th>
									<th>Aksi</th>
									<th>Nama</th>
									<th>Judul</th>
									<th>Tanggal</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(document).ready(function() {
		let tabel_produk = $('#tabel-pengaduan').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [[3, 'desc']],
			'columnDefs': [
				{ 'orderable': false, 'targets': [0, 1, 2] },
				{ 'className' : 'padat', 'targets': [0, 1, 5, 6] },
				{ 'className' : 'aksi', 'targets': [2] }
			],
			'ajax': {
				'url': "<?= site_url($this->controller); ?>",
				'method': 'POST',
				'data': function(d) {
					d.status = $('#status').val();
				}
			},
			'columns': [
				{
					'data': function(data) {
							return `<input type="checkbox" name="id_cb[]" value="${data.id}"/>`
					}
				},
				{ 'data': null },
				{
					'data': function(data) {
						let status;
							status = `<a href="<?= site_url("{$this->controller}/pengaduan_form_detail/"); ?>${data.id}" title="Tampilkan Detail" class="btn bg-blue btn-flat btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Tampilkan Detail"><i class="fa fa-eye"></i></a>`

						let hapus;
							hapus = `<a href="#" data-href="<?= site_url("{$this->controller}/pengaduan_delete/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>`
						return `
						<?php if ($this->CI->cek_hak_akses('u')): ?>
							<a href="<?= site_url("{$this->controller}/pengaduan_form/"); ?>${data.id}" title="Tanggapi Pengaduan" class="btn bg-orange btn-flat btn-sm" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Tanggapi pengaduan"><i class="fa fa-mail-forward"></i></a>
							${status}
						<?php endif; ?>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							${hapus}
						<?php endif; ?>
						`
					}
				},
				{ 'data': 'nama' },
				{ 'data': 'judul' },
				{ 'data': 'created_at' },
				{
					'data': function(data) {
						if (data.status == 1) {
							return `<span class="label label-danger">Menunggu Diproses</span>`
						} else if (data.status == 2) {
							return `<span class="label label-info">Sedang Diproses</span>`
						}if (data.status == 3) {
							return `<span class="label label-success">Selesai Diproses</span>`
						}
					}
				},
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabel_produk.on('draw.dt', function() {
			let PageInfo = $('#tabel-pengaduan').DataTable().page.info();
			tabel_produk.column(1, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#status').on('select2:select', function (e) {
			tabel_produk.ajax.reload();
		});
	});
</script>
