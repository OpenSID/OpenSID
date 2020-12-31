<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	h3.subtitle {
		margin-top: 5px;
		margin-bottom: 15px;
	}
</style>
<?php $this->load->view($folder_themes.'/layouts/header.php');?>
	<div id="contentwrapper">
		<div id="contentcolumn">
			<div class="innertube">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title"> Data Kelompok - <?= $detail['nama']; ?></h3>
					</div>
					<div class="box-body">
						<h3 class="subtitle">Rincian & Pengurus</h3>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<tbody>
									<tr>
										<td width="20%">Nama Kelompok</td>
										<td width="1"> : </td>
										<td><?= $detail['nama']?></td>
									</tr>
									<tr>
										<td>Ketua Kelompok</td>
										<td> : </td>
										<td><?= $detail['nama_ketua']?></td>
									</tr>
									<tr>
										<td>Keterangan</td>
										<td> : </td>
										<td><?= $detail['keterangan']?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<h3 class="subtitle">Daftar Anggota</h3>
						<div class="table-responsive">
							<table class="table table-striped table-bordered dataTable table-hover nowrap" id="tabel-data">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>No. Anggota</th>
										<th>Nama</th>
										<th>Alamat</th>
										<th>Jenis Kelamin</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($anggota as $key => $data): ?>
										<tr>
											<td><?= $key + 1?></td>
											<td><?= $data['no_anggota'] ?:'-'?></td>
											<td nowrap><?= $data['nama']?></td>
											<td><?= $data['alamat']?></td>
											<td><?= ($data['sex'] == 1) ? 'LAKI-LAKI' : 'PEREMPUAN'?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="rightcolumn">
		<div class="innertube">
			<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
		</div>
	</div>
	<div id="footer">
		<?php $this->load->view($folder_themes.'/partials/copywright.tpl.php'); ?>
	</div>
	</div>
	<script>
		$(document).ready(function(){
			$('#tabel-data').DataTable({
				'processing': true,
				"pageLength": 10,
				'order': [],
				'columnDefs': [
					{
						'searchable': false,
						'targets': 0
					},
					{
						'orderable': false,
						'targets': 0
					}
				],
				'language': {
					'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
				},
			});
		});
	</script>
</body>
</html>
