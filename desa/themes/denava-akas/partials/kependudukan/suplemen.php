<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="statistikstyle">
		<div class="container-page mb-20">
          	<div class="headingpage border-grey-soft bg-gradient-hor flexleft">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/check.svg") ?>" alt=""/></div>
				<div class="articlehome-head flexleft"><h1>DATA SUPLEMEN</h1></div>
          	</div>
			<div class="box-article mb-30 bg-white">
				<div class="relative-border p-15 border-grey-soft">
					<table class="table" style="width:100%;">
						<tbody class="border-grey-soft">
							<tr>
								<td class="border-grey-soft" style="vertical-align:top;">Sasaran Terdata</td>
								<td class="border-grey-soft" width="10px" style="vertical-align:top;">:</td>
								<td class="border-grey-soft" style="vertical-align:top;"><?= $sasaran[$main['suplemen']['sasaran']]; ?></td>
							</tr>
							<tr>
								<td class="border-grey-soft" style="vertical-align:top;">Keterangan</td>
								<td class="border-grey-soft" width="10px" style="vertical-align:top;">:</td>
								<td class="border-grey-soft" style="vertical-align:top;"><?= $main['suplemen']['keterangan']; ?></td>
							</tr>
						</tbody>
					</table>
					<div class="head-module-center flexcenter border-grey-soft" style="margin-bottom:10px;margin-top:30px;"><h2>Daftar <?= $main['suplemen']['nama']; ?></h2></div>
					<div class="table-responsive">
						<table class="table table-striped table-bordered dataTable table-hover" id="tabel-data">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th style="width:5%;text-align:center;vertical-align:middle;">No</th>
									<th style="text-align:center;vertical-align:middle;">Penduduk</th>
									<th style="text-align:center;vertical-align:middle;">Jenis Kelamin</th>
									<th style="text-align:center;vertical-align:middle;">RT</th>
									<th style="text-align:center;vertical-align:middle;">RW</th>
									<th style="text-align:center;vertical-align:middle;">Alamat</th>
									<th style="text-align:center;vertical-align:middle;">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($main['terdata'] as $key => $data): ?>
								<tr>
									<td class="text-center"><?= ($key + 1); ?></td>
									<td><?= $data['terdata_nama']; ?></td>
									<td><?= $data["sex"]; ?></td>
									<td><?= $data["rt"];?></td>
									<td><?= $data["rw"];?></td>
									<td><?= $data["alamat"];?></td>
									<td><?= $data["keterangan"];?></td>
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
<script>
	$(document).ready(function(){
		$('#tabel-data').DataTable({
			'processing': true,
			"pageLength": 10,
			'order': [],
			"info": false,
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
