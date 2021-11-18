<div class="content-wrapper">
	<section class="content-header">
		<h1>Anjungan Layanan Mandiri</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Anjungan Layanan Mandiri</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<?php if ($this->CI->cek_hak_akses('u')): ?>
					<a href="<?=site_url('anjungan/form')?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Anjungan Layanan Mandiri"><i class="fa fa-plus"></i> Tambah Anjungan Layanan Mandiri</a>
				<?php endif; ?>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
						<thead class="bg-gray disabled color-palette">
							<tr>
								<th>No</th>
								<?php if ($this->CI->cek_hak_akses('u') || $this->CI->cek_hak_akses('h')): ?>
									<th>Aksi</th>
								<?php endif; ?>
								<th>IP Address</th>
								<th>IP Address Printer & Port</th>
								<th>Mac Address</th>
								<th>Virtual Keyboard</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($main): ?>
								<?php foreach ($main as $key => $data): ?>
									<tr <?= jecho($data['status'] == 1, false, 'class="select-row"'); ?>>
										<td class="padat"><?= ($key + 1); ?></td>
										<?php if ($this->CI->cek_hak_akses('u') || $this->CI->cek_hak_akses('h')): ?>
											<td class="aksi">
												<?php if ($this->CI->cek_hak_akses('u')): ?>
													<a href="<?= site_url("anjungan/form/{$data['id']}"); ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah Data"><i class='fa fa-edit'></i></a>
													<?php if ($data['status'] == '1'): ?>
														<a href="<?= site_url("anjungan/lock/{$data['id']}/2"); ?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
													<?php else: ?>
														<a href="<?= site_url("anjungan/lock/{$data['id']}/1"); ?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
													<?php endif; ?>
												<?php endif; ?>
												<?php if ($this->CI->cek_hak_akses('h')): ?>
													<a href="#" data-href="<?=site_url('anjungan/delete/' . $data[id]); ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
												<?php endif; ?>
											</td>
										<?php endif; ?>
										<td class="padat"><?= $data['ip_address']; ?></td>
										<td class="padat"><?= "{$data['printer_ip']}:{$data['printer_port']}" ?></td>
										<td class="padat"><?= $data['mac_address']; ?></td>
										<td class="padat"><?= ($data['keyboard'] == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>'; ?></td>
										<td><?= $data['keterangan']; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td class="text-center" colspan="10">Data Tidak Tersedia</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>

