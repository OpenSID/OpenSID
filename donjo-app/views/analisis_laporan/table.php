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
<?php
	$subjek = $_SESSION['subjek_tipe'];
	switch ($subjek):
		case 1: $sql = $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
		case 2: $sql = $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
		case 3: $sql = $nama="Kepala Rumah Tangga"; $nomor="Nomor Rumah Tangga";$asubjek="Rumah Tangga"; break;
		case 4: $sql = $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
		default: return null;
	endswitch;
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Hasil Analisis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('analisis_master') ?>"> Master Analisis</a></li>
			<li><a href="<?= site_url() ?>analisis_laporan/leave"><?= $analisis_master['nama'] ?></a></li>
			<li class="active">Laporan Hasil Klasifikasi</li>
		</ol>
	</section>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left',$data);?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("analisis_laporan/cetak/$o") ?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
								<i class="fa fa-print"></i>Cetak
            	</a>
						  <a href="<?= site_url("analisis_laporan/excel/$o") ?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank">
								<i class="fa fa-download"></i>Unduh
            	</a>
							<a href="<?= site_url("analisis_laporan/ajax_multi_jawab") ?>" class="btn btn-social btn-flat bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Filter Indikator" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Filter Indikator">
								<i class="fa fa-search"></i>Filter Indikator
            	</a>
							<a href="<?= site_url() ?>analisis_laporan/leave" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar RW">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke <?= $analisis_master['nama'] ?>
							</a>
						</div>
						<div class="box-header with-border">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover" >
									<tr>
										<td width="150">Nama Analisis</td>
										<td width="1">:</td>
										<td><a href="<?= site_url() ?>analisis_master/menu/<?= $_SESSION['analisis_master'] ?>"><?= $analisis_master['nama'] ?></a></td>
									</tr>
									<tr>
										<td>Subjek Analisis</td>
										<td>:</td>
										<td><?= $asubjek?></td>
									</tr>
									<tr>
										<td>Priode</td>
										<td>:</td>
										<td><?= $analisis_periode?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-8">
													<select class="form-control input-sm" name="klasifikasi" onchange="formAction('mainform', '<?= site_url('analisis_laporan/klasifikasi') ?>')">
														<option value=""> --- Klasifikasi --- </option>
														<?php foreach ($list_klasifikasi AS $data): ?>
															<option value="<?= $data['id'] ?>" <?php if ($klasifikasi == $data['id']): ?>selected<?php endif ?>><?= $data['nama'] ?></option>
														<?php endforeach;?>
													</select>
													<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('analisis_laporan/dusun') ?>')">
														<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun) ?></option>
														<?php foreach ($list_dusun AS $data): ?>
															<option value="<?= $data['dusun'] ?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun']) ?></option>
														<?php endforeach;?>
													</select>
													<?php if ($dusun): ?>
														<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('analisis_laporan/rw') ?>')" >
															<option value="">RW</option>
															<?php foreach ($list_rw AS $data): ?>
																<option value="<?= $data['rw'] ?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw'] ?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
													<?php if ($rw): ?>
														<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('analisis_laporan/rt') ?>')">
															<option value="">RT</option>
															<?php foreach ($list_rt AS $data): ?>
																<option value="<?= $data['rt'] ?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt'] ?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
												</div>
												<div class="col-sm-4">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?= site_url("analisis_laporan/search") ?>');$('#'+'mainform').submit();endif">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("analisis_laporan/search") ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover nowrap">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>Aksi</th>
																	<?php if ($o==2): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/1") ?>"><?= $nomor?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==1): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/2") ?>"><?= $nomor?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/1") ?>"><?= $nomor?> <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>

																	<?php if ($o==4): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/3") ?>"><?= $nama?> <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==3): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/4") ?>"><?= $nama?> <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/3") ?>"><?= $nama?> <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>L/P</th>
																	<th>Dusun</th>
																	<th>RW</th>
																	<th>RT</th>
																	<?php if ($o==6): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/5") ?>">Nilai <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/6") ?>">Nilai <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/5") ?>">Nilai <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o==6): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/5") ?>">Klasifikasi <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/6") ?>">Klasifikasi <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_laporan/index/$p/5") ?>">Klasifikasi <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td><?= $data['no'] ?></td>
																		<td nowrap>
																			<a href="<?= site_url("analisis_laporan/kuisioner/$p/$o/$data[id]") ?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian"><i class='fa fa-list'></i></a>
																		</td>
																		<td><?= $data['uid'] ?></td>
																		<td nowrap><?= $data['nama'] ?></td>
																		<td><?= $data['jk'] ?></td>
																		<td><?= $data['dusun'] ?></td>
																		<td><?= $data['rw'] ?></td>
																		<td><?= $data['rt'] ?></td>
																		<td><?= $data['nilai'] ?></td>
																		<td><?= $data['klasifikasi'] ?></td>
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
													<form id="paging" action="<?= site_url("analisis_laporan") ?>" method="post" class="form-horizontal">
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
															<li><a href="<?= site_url("analisis_laporan/index/$paging->start_link/$o") ?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?= site_url("analisis_laporan/index/$paging->prev/$o") ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
															<li <?=jecho($p, $i, "class='active'") ?>><a href="<?= site_url("analisis_laporan/index/$i/$o") ?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?= site_url("analisis_laporan/index/$paging->next/$o") ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?= site_url("analisis_laporan/index/$paging->end_link/$o") ?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
		</form>
	</section>
</div>

