<div class="box box-info">
	<div class="box-header with-border">
		<a href="<?= site_url('bumindes_tanah_desa/form')?>"
			class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
			title="Tambah Data Baru">
			<i class="fa fa-plus"></i>Tambah Data
		</a>
		<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Tanah di Desa" data-remote="false" data-toggle="modal" data-target="#cetakBox" data-title="Cetak Buku Tanah di Desa"><i class="fa fa-print "></i> Cetak</a>
		<a href="#" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Tanah di Desa" data-remote="false" data-toggle="modal" data-target="#unduhBox" data-title="Unduh Buku Tanah di Desa"><i class="fa fa-download"></i> Unduh</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="table-responsive">
							<table id="tabel-tanahdesa" class="table table-bordered dataTable table-hover">
								<thead class="bg-gray">
									<tr>
										<th class="text-center">No</th>
										<th width="120" class="text-center">Aksi</th>
										<th class="text-center">Nama Perorangan &nbsp/ <br> Badan Hukum</th>
										<th class="text-center">Luas Total (M<sup>2</sup>)</th>
										<th class="text-center">Mutasi</th>
										<th class="text-center">Keterangan</th>
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
		<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Cetak Buku Tanah di Desa</h4>
					</div>
					<formtarget="_blank" class="form-horizontal" method="get">
						<div class="modal-body">
							<div class="form-group">
								<div class="container-fluid">
									<label class="control-label required" for="tgl_cetak">Tanggal Cetak</label>
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm required" id="tgl_1" name="tgl_cetak" type="text" value="<?= date('d-m-Y');?>">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"
								data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak"
								name="form_cetak" data-dismiss="modal"><i class='fa fa-check'></i> Cetak</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Unduh Buku Tanah di Desa</h4>
					</div>
					<formtarget="_blank" class="form-horizontal" method="get">
						<div class="modal-body">
							<div class="form-group">
								<div class="container-fluid">
									<label class="control-label required" for="tgl_cetak">Tanggal Unduh</label>
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm required" id="tgl_2" name="tgl_cetak" type="text" value="<?= date('d-m-Y');?>">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"
								data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_download"
								name="form_download" data-dismiss="modal"><i class='fa fa-check'></i> Unduh</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('global/confirm_delete');?>
<script>
	$(document).ready(function() {
		let tabelTanahDesa = $('#tabel-tanahdesa').DataTable({
			'processing': true,
			'serverSide': true,
			'autoWidth': false,
			'pageLength': 10,
			'order': [],
			'columnDefs': [{
				'orderable': false,
				'targets': [0, 1,2, 3, 4, 5],
			}],
			'ajax': {
				'url': "<?= site_url('bumindes_tanah_desa') ?>",
				'method': 'POST',
				'data': function(d) {
				}
			},
			'columns': [
				{
					'data': null,
				},
				{
					'data': function(data) {
						return `
							<a href="<?= site_url('bumindes_tanah_desa/view_tanah_desa/') ?>${data.id}" title="Lihat Data" class="btn bg-info btn-flat btn-sm"><i class="fa fa-eye"></i></a>
							<a href="<?= site_url('bumindes_tanah_desa/form/') ?>${data.id}" title="Edit Data" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
							<a href="#" data-href="<?= site_url('bumindes_tanah_desa/delete_tanah_desa/') ?>${data.id}" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
							`
					}
				},
				{
					'data': function(data) {
						if(data.nama_pemilik_asal)
						{
							return data.nama_pemilik_asal;
						}
						return data.nama;

					}
				},
				{
					'data': 'luas'
				},
				{
					'data': 'mutasi'
				},
				{
					'data': 'keterangan'
				},
			],
			'language': {
				'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
			}
		});

		tabelTanahDesa.on('draw.dt', function() {
			let PageInfo = $('#tabel-tanahdesa').DataTable().page.info();
			tabelTanahDesa.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
	});

	$("#form_cetak").click(function (event) {
		var link = '<?= site_url("bumindes_tanah_desa/cetak_tanah_desa"); ?>'+ '/' + $('#tgl_1').val()+ '/cetak';
		window.open(link, '_blank');
	});
	$("#form_download").click(function (event) {
		var link = '<?= site_url("bumindes_tanah_desa/cetak_tanah_desa"); ?>'+ '/' + $('#tgl_2').val()+ '/unduh';
		window.open(link, '_blank');
	});
</script>