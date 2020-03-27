<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
	.aksi a { padding-right: 0px; }
</style>
<section class="content">
	<div class="row">
		<input type="hidden" id="tab" value="<?= $tab?>">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#daftar_rekam" data-toggle="tab">Daftar rekam cetak surat</a>
			</li>
			<li>
				<a href="#permohonan_surat" data-toggle="tab">Status permohonan surat</a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane" id="permohonan_surat" style="margin-top: 20px;">
				<p><strong>DAFTAR PERMOHONAN SURAT</strong></>
				<table class="table table-striped datatable-polos" id="list-permohonan">
					<thead>
						<tr>
							<th>No</th>
							<th nowrap>Aksi</th>
							<th>Nama Penduduk</th>
							<th>Jenis Surat</th>
							<th>Status</th>
							<th>Tanggal Kirim</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1; ?>
						<?php foreach($permohonan as $data): ?>
							<tr>
								<td align="center" width="2"><?= $no++ ?></td>
								<td nowrap class="aksi">
									<?php if ($data['status_id'] == 1): ?>
										<a href="<?= site_url("first/mandiri_surat/$data[id]")?>" title="Lengkapi Surat" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
									<?php endif; ?>								
									<?php if (in_array($data['status_id'], array('0', '1'))): ?>
										<a href="<?= site_url("permohonan_surat/batalkan/$data[id]")?>" title="Batalkan" class="btn bg-red btn-flat btn-sm"><i class="fa fa-trash"></i></a>
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
 			<div class="tab-pane active" id="daftar_rekam" style="margin-top: 20px;">
				<p><strong>DAFTAR REKAM CETAK SURAT</strong></>
				<table class="table table-striped datatable-polos" id="list-rekam">
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
						<?php $no = 1 ?>
						<?php foreach($surat_keluar as $data): ?>
							<tr>
								<td align="center" width="2"><?= $no++ ?></td>
								<td><?= $data['no_surat']?></td>
								<td><?= $data['format']?></td>
								<td><?= $data['pamong']?></td>
								<td><?= tgl_indo2($data['tanggal'])?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$('document').ready(function()
	{
		if ($('#tab').val() == 2)
		{
			$('.nav-tabs a[href="#permohonan_surat"]').tab('show') 
		}
	});
	
</script>
