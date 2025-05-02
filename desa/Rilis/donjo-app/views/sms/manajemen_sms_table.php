<div class="content-wrapper">
	<section class="content-header">
		<h1>SMS</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">SMS</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-3">
					<?php $this->load->view('sms/navigasi'); ?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<?php if (can('u')): ?>
								<a href="<?= site_url('sms/form/1') ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tulis Pesan Baru"class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tulis Pesan Baru</a>
							<?php endif; ?>
							<?php if (can('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url('sms/deleteAll/1') ?>')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<?php if (can('h')): ?>
													<th><input type="checkbox" id="checkall"/></th>
													<?php endif; ?>
													<th>No</th>
													<?php if (can('u')): ?>
														<th>Aksi</th>
													<?php endif; ?>
													<th>Nama</th>
													<?php if ($o == 2): ?>
														<th><a href="<?= site_url("sms/index/{$p}/1") ?>">Nomor HP <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($o == 1): ?>
														<th><a href="<?= site_url("sms/index/{$p}/2") ?>">Nomor HP <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th><a href="<?= site_url("sms/index/{$p}/1") ?>">Nomor HP <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
													<th>Isi Pesan</th>
													<?php if ($o == 6): ?>
														<th nowrap><a href="<?= site_url("sms/index/{$p}/5") ?>">Diterima <i class='fa fa-sort-asc fa-sm'></i></a></th>
													<?php elseif ($o == 5): ?>
														<th nowrap><a href="<?= site_url("sms/index/{$p}/6") ?>">Diterima <i class='fa fa-sort-desc fa-sm'></i></a></th>
													<?php else: ?>
														<th nowrap><a href="<?= site_url("sms/index/{$p}/5") ?>">Diterima <i class='fa fa-sort fa-sm'></i></a></th>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php if ($main) : ?>
													<?php foreach ($main as $data): ?>
														<tr>
															<?php if (can('h')): ?>
																<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['ID'] ?>" /></td>
															<?php endif; ?>
															<td class="padat"><?= $data['no'] ?></td>
															<?php if (can('u')): ?>
																<td class="aksi">
																	<a href="<?= site_url("sms/form/1/{$data['ID']}") ?>" class="btn bg-orange btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Lihat Pesan" title="Tampilkan dan Balas"><i class="fa fa-reply"></i></a>
																	<?php if (can('h')): ?>
																		<a href="#" data-href="<?= site_url("sms/delete/1/{$data['ID']}") ?>" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	<?php endif; ?>
																</td>
															<?php endif; ?>
															<td class="padat"><?= $data['nama'] ?></td>
															<td class="padat"><?= $data['SenderNumber'] ?></td>
															<td><?= $data['TextDecoded'] ?></td>
															<td class="padat"><?= tgl_indo2($data['ReceivingDateTime']) ?></td>
														</tr>
													<?php endforeach; ?>
												<?php else : ?>
													<tr>
														<td class="padat" colspan="7">Data belum tersedia</td>
													</tr>
												<?php endif ?>
											</tbody>
										</table>
									</div>
								</form>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_length">
											<form id="paging" action="<?= site_url('sms')?>" method="post" class="form-horizontal">
												<label>
													Tampilkan
													<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
														<option value="20" <?php selected($per_page, 20); ?> >20</option>
														<option value="50" <?php selected($per_page, 50); ?> >50</option>
														<option value="100" <?php selected($per_page, 100); ?> >100</option>
													</select>
													Dari
													<strong><?= $paging->num_rows?></strong>
													Total Data
												</label>
											</form>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTables_paginate paging_simple_numbers">
											<ul class="pagination">
												<?php if ($paging->start_link): ?>
													<li><a href="<?= site_url("sms/index/{$paging->start_link}/{$o}") ?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
												<?php endif; ?>
												<?php if ($paging->prev): ?>
													<li><a href="<?= site_url("sms/index/{$paging->prev}/{$o}") ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
												<?php endif; ?>
												<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
													<li <?=jecho($p, $i, "class='active'") ?>><a href="<?= site_url("sms/index/{$i}/{$o}") ?>"><?= $i?></a></li>
												<?php endfor; ?>
												<?php if ($paging->next): ?>
													<li><a href="<?= site_url("sms/index/{$paging->next}/{$o}") ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
												<?php endif; ?>
												<?php if ($paging->end_link): ?>
													<li><a href="<?= site_url("sms/index/{$paging->end_link}/{$o}") ?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
												<?php endif; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>