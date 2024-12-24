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
								<a href="<?= site_url('sms/form/4') ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tulis Pesan Baru"class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i>Tulis Pesan Baru</a>
							<?php endif; ?>
							<?php if (can('u')): ?>
								<a href="<?= site_url('sms/broadcast/0/0/2')?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tulis Pesan Broadcast"  class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-weixin'></i> Tulis Pesan Ke Banyak</a>
							<?php endif; ?>
						</div>
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover tabel-daftar">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No</th>
															<th>Nama</th>
															<?php if ($o == 2): ?>
																<th><a href="<?= site_url("sms/outbox/{$p}/1") ?>">Nomor HP <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o == 1): ?>
																<th><a href="<?= site_url("sms/outbox/{$p}/2") ?>">Nomor HP <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th><a href="<?= site_url("sms/outbox/{$p}/1") ?>">Nomor HP <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<th>Isi Pesan</th>
															<?php if ($o == 6): ?>
																<th nowrap><a href="<?= site_url("sms/outbox/{$p}/5") ?>">Diterima <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o == 5): ?>
																<th nowrap><a href="<?= site_url("sms/outbox/{$p}/6") ?>">Diterima <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th nowrap><a href="<?= site_url("sms/outbox/{$p}/5") ?>">Diterima <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
														</tr>
													</thead>
													<tbody>
														<?php if ($main) : ?>
															<?php foreach ($main as $data): ?>
																<tr>
																	<td class="padat"><?= $data['no'] ?></td>
																	<td class="padat"><?= $data['nama'] ?></td>
																	<td class="padat"><?= $data['DestinationNumber'] ?></td>
																	<td><?= $data['TextDecoded'] ?></td>
																	<td class="padat"><?= tgl_indo2($data['SendingDateTime']) ?></td>
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
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_length">
											<form id="paging" action="<?= site_url('sms/outbox')?>" method="post" class="form-horizontal">
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
													<li><a href="<?= site_url("sms/outbox/{$paging->start_link}/{$o}") ?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
												<?php endif; ?>
												<?php if ($paging->prev): ?>
													<li><a href="<?= site_url("sms/outbox/{$paging->prev}/{$o}") ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
												<?php endif; ?>
												<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
													<li <?=jecho($p, $i, "class='active'") ?>><a href="<?= site_url("sms/outbox/{$i}/{$o}") ?>"><?= $i?></a></li>
												<?php endfor; ?>
												<?php if ($paging->next): ?>
													<li><a href="<?= site_url("sms/outbox/{$paging->next}/{$o}") ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
												<?php endif; ?>
												<?php if ($paging->end_link): ?>
													<li><a href="<?= site_url("sms/outbox/{$paging->end_link}/{$o}") ?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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

