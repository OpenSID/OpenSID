<script>
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Arsip Layanan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Arsip Layanan Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('keluar/perorangan_clear')?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-archive"></i> Rekam Surat Perorangan</a>
						<a href="<?= site_url('keluar/graph')?>" class="btn btn-social btn-flat bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pie-chart"></i> Pie Surat Keluar</a>
						<a href="<?= site_url('keluar/cetak')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print"></i> Cetak</a>
						<a href="<?= site_url('keluar/excel')?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-download"></i> Unduh</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?= site_url($this->controller.'/filter')?>')">
													<option value="">Tahun</option>
													<?php foreach ($tahun_surat as $tahun): ?>
														<option value="<?= $tahun['tahun']?>" <?php selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
													<?php endforeach; ?>
												</select>
												<select class="form-control input-sm select2" name="jenis" onchange="formAction('mainform','<?= site_url($this->controller.'/jenis')?>')">
													<option value="">Pilih Jenis Surat</option>
													<?php foreach ($jenis_surat as $data): ?>
														<option value="<?= $data['nama_surat']?>" <?php selected($jenis, $data['nama_surat']) ?>><?= $data['nama_surat']?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th >Aksi</th>
																<?php if ($o==2): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/1")?>">Nomor Surat <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==1): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/2")?>">Nomor Surat <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/1")?>">Nomor Surat <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>Jenis Surat</th>
																<?php if ($o==4): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/3")?>">Nama Penduduk <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==3): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/4")?>">Nama Penduduk <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/3")?>">Nama Penduduk <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th nowrap>Ditandatangani Oleh</th>
																<?php if ($o==6): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/5")?>">Tanggal <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==5): ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/6")?>">Tanggal <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("keluar/index/$p/5")?>">Tanggal <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>User</th>
															</tr>
														</thead>
														<tbody>
															<?php	foreach ($main as $data):
																if ($data['nama_surat']):
																	$berkas = $data['nama_surat'];
																else:
																	$berkas = $data["berkas"]."_".$data["nik"]."_".date("Y-m-d").".rtf";
																endif;

																$theFile = FCPATH.LOKASI_ARSIP.$berkas;
																$lampiran = FCPATH.LOKASI_ARSIP.$data['lampiran'];
															?>
																<tr>
																	<td><?= $data['no']?></td>
																	<td nowrap>
																		<?php
																			if (is_file($theFile)): ?>
																				<a href="<?= base_url(LOKASI_ARSIP.$berkas)?>" class="btn btn-social btn-flat bg-purple btn-sm" title="Unduh Surat" target="_blank"><i class="fa fa-file-word-o"></i> Surat</a>
																			<?php	endif; ?>
																		<?php
																			if (is_file($lampiran)): ?>
																				<a href="<?= base_url(LOKASI_ARSIP.$data['lampiran'])?>" target="_blank" class="btn btn-social btn-flat bg-olive btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip"></i> Lampiran</a>
																			<?php	endif; ?>
																		<a href="#" data-href="<?= site_url("keluar/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td>
																	<td><?= $data['no_surat']?></td>
																	<td><?= $data['format']?></td>
																	<td>
																		<?php if ($data['nama']): ?>
																			<?= $data['nama']; ?>
																		<?php elseif ($data['nama_non_warga']): ?>
																			<strong>Non-warga: </strong><?= $data['nama_non_warga']; ?><br>
																			<strong>NIK: </strong><?= $data['nik_non_warga']; ?>
																		<?php endif; ?>
																	</td>
																	<td><?= $data['pamong']?></td>
																	<td nowrap><?= tgl_indo2($data['tanggal'])?></td>
																	<td><?= $data['nama_user']?></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="<?= site_url("keluar")?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="20" <?php selected($per_page,20); ?> >20</option>
															<option value="50" <?php selected($per_page,50); ?> >50</option>
															<option value="100" <?php selected($per_page,100); ?> >100</option>
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
                            <li><a href="<?=site_url("keluar/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("keluar/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("keluar/index/$i/$o")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("keluar/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("keluar/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
									</div>
								</div>
							</div>
						</div>
						<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
									</div>
									<div class='modal-body btn-info'>
										Apakah Anda yakin ingin menghapus data ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class='fa fa-trash-o'></i> Hapus</button>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

