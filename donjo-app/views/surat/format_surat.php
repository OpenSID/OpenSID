<div class="content-wrapper">
	<section class="content-header">
		<h1>Cetak Layanan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Cetak Layanan Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<form id="main" name="main" action="<?= site_url('surat/search'); ?>" method="post">
					<div class="text-center">
						<select class="form-control select2" id="nik" name="nik" onchange="formAction('main')">
							<option selected="selected">-- Cari Judul Surat --</option>
							<?php foreach ($menu_surat2 as $data): ?>
								<option value="<?= $data['url_surat']?>"><?= strtoupper($data['nama'])?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</form>
			</div>
			<div class="box-body">
				<?php if ($data['favorit'] = 1): ?>
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th>No</th>
									<th>Aksi</th>
									<th>Layanan Administrasi Surat (Daftar Favorit)</th>
									<th>Kode Surat</th>
									<th>Lampiran</th>
								</tr>
							</thead>
							<tbody>
								<?php if (count($surat_favorit) > 0): ?>
									<?php foreach ($surat_favorit AS $key => $data): ?>
										<tr <?= ($data['jenis'] == 1) ?: 'class="select_row"'; ?>>
											<td class="padat"><?= ($key + 1); ?></td>
											<td class="aksi">
												<a href="<?= site_url("surat/form/$data[url_surat]"); ?>" class="btn btn-social btn-flat bg-olive btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a>
												<a href="<?= site_url("surat/favorit/$data[id]/$data[favorit]"); ?>" class="btn btn-flat bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a>
											</td>
											<td width="50%"><?= $data['nama']?></td>
											<td class="padat"><?= $data['kode_surat']?></td>
											<td><?= $data['nama_lampiran']?></td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="5" class="box box-warning box-solid">
											<div class="box-body text-center">
												<span>Belum ada surat favorit</span>
											</div>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<hr>
				<?php endif; ?>
				<div class="table-responsive">
					<table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
						<thead class="bg-gray disabled color-palette">
							<tr>
								<th>No</th>
								<th>Aksi</th>
								<th>Layanan Administrasi Surat</th>
								<th>Kode Surat</th>
								<th>Lampiran</th>
							</tr>
						</thead>
						<tbody>
							<?php $nomer=1; foreach ($menu_surat2 AS $set2 => $data): ?>
								<?php if ($data['favorit'] != 1): ?>
									<tr <?= ($data['jenis'] == 1) ?: 'class="select_row"'; ?>>
										<td class="padat"><?= ($nomer); ?></td>
										<td class="aksi">
											<a href="<?= site_url("surat/form/$data[url_surat]"); ?>" class="btn btn-social btn-flat bg-purple btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a>
											<a href="<?= site_url("surat/favorit/$data[id]/$data[favorit]"); ?>" class="btn btn-flat bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a>
										</td>
										<td width="50%"><?= $data['nama']?></td>
										<td class="padat"><?= $data['kode_surat']?></td>
										<td><?= $data['nama_lampiran']?></td>
									</tr>
								<?php $nomer++; endif; ?>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>

