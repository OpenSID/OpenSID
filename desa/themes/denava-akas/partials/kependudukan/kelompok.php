<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="statistikstyle">
		<div class="container-page mb-20">
          	<div class="headingpage border-grey-soft bg-gradient-hor flexleft">
				<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/check.svg") ?>" alt=""/></div>
				<div class="articlehome-head flexleft"><h1><?= $title ?></h1></div>
          	</div>
			<div class="box-article mb-30 bg-white">
				<div class="relative-border p-15 border-grey-soft">
					<div class="head-content" style="text-align:center;">
						<?php if (IS_PREMIUM && IS_240710) : ?>
						<div width="20%" rowspan="5" style="text-align: center; vertical-align: middle;">
							<img src="<?= gambar_desa($detail['logo']) ?>" alt="Logo <?= ucwords($detail['tipe']) ?>" height="120px;">
						</div>
						<?php endif; ?>
						<h1 style="margin-bottom:10px !important;"><?= $detail['kode']; ?></h1>
					</div>
					<table class="table" style="width:100%;border:none;">
						<tbody style="bborder:none;">
							<tr>
								<td style="vertical-align:top;text-align:center;border:none;"><?= $detail['keterangan']?></td>
							</tr>
						</tbody>
					</table>
					<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:10px;margin-top:30px;">
						<h2>Daftar Anggota</h2>
					</div>
					<div class="table-responsive">
						<table class="table" id="tabel-data">
							<thead>
						<tr>
							<th>No. Anggota</th>
							<th>Nama</th>
							<th>Jabatan</th>
							<th>Jenis Kelamin</th>
							<th>Umur</th>
							<th><?= ucwords($this->setting->sebutan_dusun); ?></th>
							<th>RW</th>
							<th>RT</th>
						</tr>
					</thead>
					<tbody>
					    <?php foreach ($pengurus as $key => $data): ?>
						<tr>
							<td><?= $data['no_anggota'] ?:'-'; ?></td>
							<td nowrap><?= $data['nama']?></td>
							<td><?= $data['jabatan']?></td>
							<td><?= $data['sex']; ?></td>
							<td><?= $data['umur']; ?></td>
							<td><?= $data['dusun']?></td>
							<td><?= $data['rw']?></td>
							<td><?= $data['rt']?></td>
						</tr>
					    <?php endforeach; ?>
					    <?php foreach ($anggota as $key => $data): ?>
						<tr>
							<td><?= $data['no_anggota'] ?:'-'; ?></td>
							<td nowrap><?= $data['nama']; ?></td>
							<td><?= $data['jabatan']; ?></td>
							<td><?= $data['sex']; ?></td>
							<td><?= $data['umur']; ?></td>
							<td><?= $data['dusun']; ?></td>
							<td><?= $data['rw']; ?></td>
							<td><?= $data['rt']; ?></td>
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
