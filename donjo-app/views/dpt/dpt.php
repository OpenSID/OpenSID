<script>
	$(document).ready(function()
	{
		$('#tglform').validate();
	});
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
<style>
	.input-sm
	{
		padding: 4px 4px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Calon Pemilih</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Calon Pemilih</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<div class="col-sm-8 col-lg-9">
							<div class="row">
								<a href="<?= site_url("dpt/cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
								<a href="<?= site_url("dpt/excel/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
								<a href="<?= site_url("dpt/ajax_adv_search")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Pencarian Spesifik"><i class='fa fa-search'></i> Pencarian Spesifik</a>
								<a href="<?= site_url("dpt/clear")?>" class="btn btn-social btn-flat btn-default btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan Pencarian"><i class="fa fa-refresh"></i>Bersihkan</a>
							</div>
						</div>
						<div class="col-sm-4 col-md-3">
							<form id="tglform" name="tglform" action="<?= site_url('dpt/index/1/'.$o)?>" method="post">
								<div class="row">
									<div class="input-group">
										<span class="input-group-addon input-sm">Tanggal Pemilihan</span>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control input-sm datepicker pull-right" onchange="$('#tglform').submit()" name="tanggal_pemilihan" type="text" value="<?= $_SESSION['tanggal_pemilihan']?>">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="box-header">
						<h4 class="text-center"><strong>DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <?= $_SESSION['tanggal_pemilihan']?></strong></h4>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">

									<form id="mainform" name="mainform" action="" method="post">
										<input type="hidden" name="rt" value="">

										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?= site_url('dpt/sex/1/'.$o)?>')">
													<option value="">Jenis Kelamin</option>
													<option value="1" <?php if ($sex==1 ): ?>selected<?php endif ?>>Laki-Laki</option>
													<option value="2" <?php if ($sex==2 ): ?>selected<?php endif ?>>Perempuan</option>
												</select>
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('dpt/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach;?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('dpt/rw')?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('dpt/rt')?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-3">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("dpt/search")?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("dpt/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<?php if ($o==2): ?>
                                  <th><a href="<?= site_url("dpt/index/$p/1")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                 <?php elseif ($o==1): ?>
                                  <th><a href="<?= site_url("dpt/index/$p/2")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th><a href="<?= site_url("dpt/index/$p/1")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
                                <?php if ($o==4): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==3): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
																<?php if ($o==6): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/5")?>">No. KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==5): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/6")?>">No. KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/5")?>">No. KK <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
																<th>Alamat</th>
																<th><?= ucwords($this->setting->sebutan_dusun)?></th>
																<th>RW</th>
																<th>RT</th>
																<th nowrap>Pendidikan dalam KK</th>
																<?php if ($o==8): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/7")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==7): ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/8")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th nowrap><a href="<?= site_url("dpt/index/$p/7")?>">Umur Pada <?= $_SESSION['tanggal_pemilihan']?> <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
																<th nowrap>Pekerjaan</th>
																<th nowrap>Kawin</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data): ?>
																<tr>
																	<td><?= $data['no']?></td>
																	<td>
																		<a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?= $data['id']?>"><?= $data['nik']?></a>
																	</td>
																	<td><?= strtoupper($data['nama'])?></td>
																	<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?= $data['no_kk']?> </a></td>
																	<td><?= strtoupper($data['alamat'])?></td>
																	<td><?= strtoupper($data['dusun'])?></td>
																	<td><?= $data['rw']?></td>
																	<td><?= $data['rt']?></td>
																	<td><?= $data['pendidikan']?></td>
																	<td><?= $data['umur_pada_pemilihan']?></td>
																	<td><?= $data['pekerjaan']?></td>
																	<td><?= $data['kawin']?></td>
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
												<form id="paging" action="<?= site_url("dpt")?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="50" <?php selected($per_page,50); ?> >50</option>
															<option value="100" <?php selected($per_page,100); ?> >100</option>
															<option value="200" <?php selected($per_page,200); ?> >200</option>
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
														<li><a href="<?= site_url("dpt/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
													<?php endif; ?>
													<?php if ($paging->prev): ?>
														<li><a href="<?= site_url("dpt/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
													<?php endif; ?>
													<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
														<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("dpt/index/$i/$o")?>"><?= $i?></a></li>
													<?php endfor; ?>
													<?php if ($paging->next): ?>
														<li><a href="<?= site_url("dpt/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
													<?php endif; ?>
													<?php if ($paging->end_link): ?>
														<li><a href="<?= site_url("dpt/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
					<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
									<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
								</div>
								<div class='modal-body btn-info'>
									Apakah Anda yakin ingin mengembalikan status data penduduk ini?
								</div>
								<div class='modal-footer'>
									<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
									<a class='btn-ok'>
										<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-status"><i class='fa fa-check'></i> Ya</button>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

