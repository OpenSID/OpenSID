<div class="box box-solid">
	<div class="box-header with-border bg-green">
		<h4 class="box-title">Surat</h4>
	</div>
	<div class="box-body box-line">
		<a href="<?= site_url("layanan-mandiri/surat/buat-surat"); ?>" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pencil-square-o"></i>Buat Surat</a>
		<a href="<?= site_url("layanan-mandiri/arsip-surat"); ?>" class="btn btn-social btn-primary visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file-zip-o"></i>Arsip Surat</a>
		<a href="<?= site_url("layanan-mandiri/permohonan-surat"); ?>" class="btn btn-social bg-purple visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file-word-o"></i>Permohonan Surat</a>
	</div>
	<div class="box-body box-line">
		<h4><b>DAFTAR <?= strtoupper($judul); ?></b></h4>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<?php if($kat == 1): ?>
				<table class="table table-bordered table-hover table-data datatable-polos">
					<thead>
						<tr>
							<th>No</th>
							<th>Aksi</th>
							<th width="30%">Nama Penduduk</th>
							<th width="30%">Jenis Surat</th>
							<th width="20%">Status</th>
							<th width="20%">Tanggal Kirim</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main):
							foreach ($main as $key => $data): ?>
								<tr class="<?= jecho($data['status_id'], 1, 'bg-red'); jecho($data['status_id'], 3, 'bg-green'); ?>">
									<td class="padat"><?= ($key + 1); ?></td>
									<td class="padat">
										<?php if ($data['status_id'] == 1): ?>
											<a href="<?= site_url("layanan_mandiri/surat/form/$data[id]")?>" title="Lengkapi Surat" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a>
										<?php endif; ?>
										<?php if (in_array($data['status_id'], array('0', '1'))): ?>
											<a href="<?= site_url("layanan_mandiri/surat/batalkan/$data[id]")?>" title="Batalkan" class="btn bg-maroon btn-sm"><i class="fa fa-times"></i></a>
										<?php endif; ?>
									</td>
									<td><?=$data['nama']; ?></td>
									<td><?=$data['jenis_surat']; ?></td>
									<td class="<?= jecho($data['status_id'], 1, 'perhatian'); ?><?= jecho($data['status_id'], 3, 'siap'); ?>"><?=$data['status']; ?></td>
									<td nowrap><?= tgl_indo2($data['created_at']); ?></td>
								</tr>
							<?php endforeach;
						else: ?>
							<tr>
								<td class="text-center" colspan="6">Data tidak tersedia</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			<?php else: ?>
				<table class="table table-bordered table-hover table-data datatable-polos">
					<thead>
						<tr>
							<th class="padat">No</th>
							<th width="30%">Nomor Surat</th>
							<th width="20%">Jenis Surat</th>
							<th width="30%">Nama Staf</th>
							<th width="20%">Tanggal</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($main):
							foreach ($main as $key => $data): ?>
								<tr>
									<td class="padat"><?= ($key + 1); ?></td>
									<td><?= $data['no_surat']; ?></td>
									<td><?= $data['format']; ?></td>
									<td><?= $data['pamong']; ?></td>
									<td nowrap><?= tgl_indo2($data['tanggal']); ?></td>
								</tr>
							<?php endforeach;
						else: ?>
							<tr>
								<td class="text-center" colspan="5">Data tidak tersedia</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>
