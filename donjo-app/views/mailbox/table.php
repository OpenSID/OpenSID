<div class="content-wrapper">
	<section class="content-header">
		<h1>Kotak Pesan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Kotak Pesan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<?php $this->load->view('mailbox/menu_mailbox') ?>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('mailbox/form') ?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tulis Pesan"><i class="fa fa-plus"></i> Tulis Pesan</a>
							<a href="#confirm-delete" title="Arsipkan Data" <?php if(!$filter_status) : ?>onclick="deleteAllBox('mainform','<?=site_url("mailbox/archive_all")?>')"<?php endif ?> class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih" <?php $filter_status and print('disabled') ?>><i class='fa fa-file-archive-o'></i> Arsipkan Data Terpilih</a>
							<a href="<?= site_url("mailbox/clear/$kat/$p/$o") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-9">
													<div class="form-group">
														<select class="form-control input-sm select2-nik-ajax" id="nik" style="width:100%" name="nik" data-url="<?= site_url('mailbox/list_pendaftar_mandiri_ajax')?>" onchange="formAction('mainform', '<?=site_url("mailbox/filter_nik/$kat")?>')">
														<?php if ($individu): ?>
															<option value="<?= $individu['nik']?>" selected><?= $individu['nik'] .' - '.$individu['nama']?></option>
														<?php else : ?>
															<option value="0" selected>Semua Pendaftar Layanan Mandiri</option>
														<?php endif;?>
														</select>
													</div>
													<div class="form-group">
														<select class="form-control input-sm " name="baca" onchange="formAction('mainform','<?=site_url("mailbox/filter/$kat")?>')">
															<option value="">Semua</option>
															<option value="1" <?php if ($filter_baca==1): ?>selected<?php endif ?>>Sudah Dibaca</option>
															<option value="2" <?php if ($filter_baca==2): ?>selected<?php endif ?>>Belum Dibaca</option>
															<option value="3" <?php if ($filter_archived): ?>selected<?php endif ?>>Diarsipkan</option>
														</select>
													</div>						
												</div>
												<div class="col-sm-3">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action','<?=site_url("mailbox/search/$kat")?>');$('#'+'mainform').submit();endif;">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("mailbox/search/$kat")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th><input type="checkbox" id="checkall"/></th>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o==2): ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/1")?>"><?= $owner ?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==1): ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/2")?>"><?= $owner ?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/1")?>"><?= $owner ?> <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o==4): ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/3")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==3): ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/4")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/3")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Subjek Pesan</th>
																	<?php if ($o==8): ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/7")?>">Status Pesan <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==7): ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/8")?>">Status Pesan <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th nowrap><a href="<?= site_url("mailbox/index/$kat/$p/7")?>">Status Pesan <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>

																	<?php if ($o==10): ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/9")?>">Dikirimkan Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==9): ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/10")?>">Dikirimkan Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("mailbox/index/$kat/$p/9")?>">Dikirimkan Pada <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
															<?php foreach ($main as $data): ?>
																<tr <?php if ($data['status']!=1): ?>style='background-color:#ffeeaa;'<?php endif; ?>>
																	<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																	<td><?=$data['no']?></td>
																	<td nowrap>
																	<?php if($data['status'] == 0) : ?>
																		<a href="#" data-href="<?=site_url("mailbox/archive/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Arsipkan" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-file-archive-o"></i></a>
																	<?php endif ?>
																		<a href="<?=site_url("mailbox/baca_pesan/{$kat}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm" title="Lihat detail pesan"><i class="fa fa-list">&nbsp;</i></a>
																	<?php if($kat != 2 AND $data['status'] != 1) : ?>
																		<?php if ($data['baca'] == 1): ?>
																			<a href="<?=site_url('mailbox/baca/'.$data['id'].'/2')?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai belum dibaca"><i class="fa fa-envelope-o"></i></a>
																		<?php else : ?>
																			<a href="<?=site_url('mailbox/baca/'.$data['id'].'/1')?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai sudah dibaca"><i class="fa fa-envelope-open-o"></i></a>
																		<?php endif; ?>
																	<?php endif ?>
																	</td>
																	<td nowrap><?=$data['nama']?></td>
																	<td><?=$data['nik']?></td>
																	<td width="40%"><?=$data['subjek']?></td>
																	<td><?=$data['baca'] == 1 ? 'Sudah Dibaca' : 'Belum Dibaca' ?></td>
																	<td nowrap><?=tgl_indo2($data['created_at'])?></td>
																</tr>
															<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
										<?php $this->load->view('global/paging_table');?>
									</div>
								</div>
							</div>
							<?php $this->load->view('global/confirm_arsipkan');?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>