<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="single_page_area">
	<h2 class="post_titile" >Data Kelompok - <?= $detail['nama']; ?></h2>
	<?= $detail['keterangan']?>
	<h3 class="post_titile">Daftar Pengurus</h3>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th>Jabatan</th>
						<th>Nama</th>
						<th>Alamat</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($pengurus as $key => $data): ?>
					<tr>
						<td><?= $key + 1?></td>
						<td><?= $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$data['jabatan']]?></td>
						<td nowrap><?= $data['nama']?></td>
						<td><?= $data['alamat']?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<h3 class="post_titile">Daftar Anggota</h3>
		<div class="table-responsive">
			<table class="table table-striped table-bordered" id="tabel-data">
				<thead>
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
						<td><?= $data['no_anggota'] ?:'-'; ?></td>
						<td nowrap><?= $data['nama']; ?></td>
						<td><?= $data['alamat']; ?></td>
						<td><?= $data['sex']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
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
