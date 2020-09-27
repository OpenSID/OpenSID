<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
		<input type="hidden" id="tab" value="<?= $tab?>">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#daftar_rekam" data-toggle="tab">Daftar rekam cetak surat</a>
				</li>
				<li>
					<a href="#permohonan_surat" data-toggle="tab">Status permohonan surat</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane" id="permohonan_surat">
				<h5><strong>DAFTAR PERMOHONAN SURAT</strong></h5>
				<div class="table-responsive">
					<table class="table table-bordered table-striped datatable-polos tabel-daftar" id="list-permohonan">
						<thead>
							<tr>
								<th>No</th>
								<th>Aksi</th>
								<th>Nama Penduduk</th>
								<th>Jenis Surat</th>
								<th>Status</th>
								<th>Tanggal Kirim</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($permohonan as $key => $data): ?>
								<tr>
									<td class="padat"><?= ($key + 1); ?></td>
									<td class="aksi">
										<?php if ($data['status_id'] == 1): ?>
											<a href="<?= site_url("mandiri_web/mandiri_surat/$data[id]")?>" title="Lengkapi Surat" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
										<?php endif; ?>
										<?php if (in_array($data['status_id'], array('0', '1'))): ?>
											<a href="<?= site_url("permohonan_surat/batalkan/$data[id]")?>" title="Batalkan" class="btn bg-maroon btn-flat btn-sm"><i class="fa fa-times"></i></a>
										<?php endif; ?>
									</td>
									<td><?=$data['nama']?></td>
									<td><?=$data['jenis_surat']?></td>
									<td><?=$data['status']?></td>
									<td nowrap><?=tgl_indo2($data['created_at'])?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane active" id="daftar_rekam">
				<h5><strong>DAFTAR REKAM CETAK SURAT</strong></h5>
				<div class="table-responsive">
					<table class="table table-bordered table-striped datatable-polos tabel-daftar" id="list-rekam">
						<thead>
							<tr>
								<th>No</th>
								<th align="left">Nomor Surat</th>
								<th align="left">Jenis Surat</th>
								<th align="left">Nama Staf</th>
								<th align="left">Tanggal</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($surat_keluar as $key => $data): ?>
								<tr>
									<td class="padat"><?= ($key + 1); ?></td>
									<td><?= $data['no_surat']?></td>
									<td><?= $data['format']?></td>
									<td><?= $data['pamong']?></td>
									<td nowrap><?= tgl_indo2($data['tanggal'])?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$('document').ready(function() {
		if ($('#tab').val() == 2) {
			$('.nav-tabs a[href="#permohonan_surat"]').tab('show');
		}
	});
</script>
