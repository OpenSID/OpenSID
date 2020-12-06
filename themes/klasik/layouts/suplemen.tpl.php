<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	h3.subtitle {
		margin-top: 5px;
		margin-bottom: 15px;
	}
	p.info {
		margin-bottom: 15px;
	}
</style>
<?php $this->load->view($folder_themes.'/layouts/header.php');?>
<div id="contentwrapper">
	<div id="contentcolumn">
		<div class="innertube">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title"> Data Suplemen - <?= $main['suplemen']['nama']; ?></h3>
				</div>
				<div class="box-body">
					<h3 class="subtitle">Rincian Data Suplemen</h3>
					<div class="table-responsive">
						<table class="table table-striped table-bordered dataTable table-hover nowrap">
							<tbody>
								<tr>
									<td width="20%">Nama Data</td>
									<td width="1%">:</td>
									<td><?= $main['suplemen']['nama']; ?></td>
								</tr>
								<tr>
									<td>Sasaran Terdata</td>
									<td>:</td>
									<td><?= $sasaran[$main['suplemen']['sasaran']]; ?></td>
								</tr>
								<tr>
									<td>Keterangan</td>
									<td>:</td>
									<td><?= $main['suplemen']['keterangan']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="box-body">
					<h3 class="subtitle">Daftar Terdata</h3>
					<div class="table-responsive">
						<table class="table table-striped table-bordered dataTable table-hover nowrap" id="tabel-data">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Tempat Lahir</th>
									<th>Jenis-kelamin</th>
									<th>Alamat</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($main['terdata'] as $key => $data): ?>
									<tr>
										<td class="text-center"><?= ($key + 1); ?></td>
										<td><?= $data['terdata_nama']; ?></td>
										<td><?= $data["tempat_lahir"]; ?></td>
										<td><?= $data["sex"]; ?></td>
										<td><?= $data["info"];?></td>
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
