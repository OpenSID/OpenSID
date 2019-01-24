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
		<h1>Laporan Statistik Jawaban</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('analisis_master')?>"> Master Analisis</a></li>
			<li><a href="<?=site_url()?>analisis_statistik_jawaban/leave"><?= $analisis_master['nama']?></a></li>
			<li class="active">Laporan Per Indikator</li>
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
							<a href="<?=site_url("analisis_statistik_jawaban/cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
								<i class="fa fa-print"></i>Cetak
            	</a>
						  <a href="<?=site_url("analisis_statistik_jawaban/excel/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank">
								<i class="fa fa-download"></i>Unduh
            	</a>
							<a href="<?= site_url()?>analisis_laporan/leave" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar RW">
								<i class="fa fa-arrow-circle-left "></i>Kembali Ke <?= $analisis_master['nama']?>
							</a>
						</div>
						<div class="box-header with-border">
							<h5>Analisis Statistik Jawaban - <a href="<?= site_url()?>analisis_master/menu/<?= $_SESSION['analisis_master']?>"><a href="<?= site_url()?>analisis_master/menu/<?= $_SESSION['analisis_master']?>"><?= $analisis_master['nama']?></a></a></h5>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-9">
													<select class="form-control input-sm" name="tipe" onchange="formAction('mainform', '<?=site_url('analisis_statistik_jawaban/tipe')?>')">
														<option value="">Tipe Indikator</option>
														<?php foreach ($list_tipe AS $data): ?>
															<option value="<?= $data['id']?>" <?php if ($tipe == $data['id']): ?>selected<?php endif ?>><?= $data['tipe']?></option>
														<?php endforeach;?>
													</select>
													<select class="form-control input-sm" name="kategori" onchange="formAction('mainform', '<?=site_url('analisis_statistik_jawaban/kategori')?>')">
														<option value="">Tipe Kategori</option>
														<?php foreach ($list_kategori AS $data): ?>
															<option value="<?= $data['id']?>" <?php if ($kategori == $data['id']): ?>selected<?php endif ?>><?= $data['kategori']?></option>
														<?php endforeach;?>
													</select>
													<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?=site_url('analisis_statistik_jawaban/filter')?>')">
														<option value="">Aksi Analisis</option>
														<option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Ya</option>
														<option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Tidak</option>
													</select>
													<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('analisis_statistik_jawaban/dusun')?>')">
														<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
														<?php foreach ($list_dusun AS $data): ?>
															<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper(unpenetration(ununderscore($data['dusun'])))?></option>
														<?php endforeach;?>
													</select>
													<?php if ($dusun): ?>
														<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('analisis_statistik_jawaban/rw')?>')" >
															<option value="">RW</option>
															<?php foreach ($list_rw AS $data): ?>
																<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
													<?php if ($rw): ?>
														<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('analisis_statistik_jawaban/rt')?>')">
															<option value="">RT</option>
															<?php foreach ($list_rt AS $data): ?>
																<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
															<?php endforeach;?>
														</select>
													<?php endif; ?>
												</div>
												<div class="col-sm-3">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("analisis_statistik_jawaban/search")?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("analisis_statistik_jawaban/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																	<th>No</th>
																	<?php if ($o==4): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/3")?>">Pertanyaan/Indikator <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==3): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/4")?>">Pertanyaan/Indikator <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/3")?>">Pertanyaan/Indikator <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th>Total</th>
																	<?php if ($o==2): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/1")?>">Kode <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==1): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/2")?>">Kode <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/1")?>">Kode <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<th colspan="2">Jawaban</th>
																	<th>Responden</th>
																	<th>Jumlah</th>
																	<?php if ($o==6): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/5")?>">Tipe Pertanyaan <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/6")?>">Tipe Pertanyaan <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/5")?>">Tipe Pertanyaan <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o==6): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/5")?>">Kategori/Variabel <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==5): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/6")?>">Kategori/Variabel <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/5")?>">Kategori/Variabel <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																	<?php if ($o==2): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/1")?>">Aksi Analisis <i class='fa fa-sort-asc fa-sm'></i></a></th>
																	<?php elseif ($o==1): ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/2")?>">Aksi Analisis <i class='fa fa-sort-desc fa-sm'></i></a></th>
																	<?php else: ?>
																		<th><a href="<?= site_url("analisis_statistik_jawaban/index/$p/1")?>">Aksi Analisis <i class='fa fa-sort fa-sm'></i></a></th>
																	<?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php $total = 0; foreach ($main as $data): ?>
																	<tr>
																		<td><?= $data['no']?></td>
																		<td width="30%"><?= $data['pertanyaan']?></a></td>
																		<td><a href="<?= site_url("analisis_statistik_jawaban/grafik_parameter/$data[id]")?>" ><?= $data['bobot']?></a></td>
																		<td><?= $data['nomor']?></td>
																		<td  width="3px">
																			<?php foreach ($data['par'] as $par): ?>
																			<?= $par['kode_jawaban']?>.<br>
																			<?php endforeach; ?>
																		</td>
																		<td width="30%">
																			<?php foreach ($data['par'] as $par): ?>
																			<?= $par['jawaban']?><br>
																			<?php endforeach; ?>
																		</td>
																		<td>
																			<?php foreach ($data['par'] as $par): ?>
																				<a href="<?= site_url("analisis_statistik_jawaban/subjek_parameter/$data[id]/$par[id]")?>" ><?= $par['jml_p']?></a><br>
																			<?php endforeach; ?>
																		</td>
																		<td><?= $data['jumlah']?></td>
																		<td><?= $data['tipe_indikator']?></td>
																		<td><?= $data['kategori']?></td>
																		<td><?= $data['act_analisis']?></td>
																	</tr>
																	<?php
																		if ($data['jumlah'] != "-"):
																			$total += $data['jumlah'];
																		endif;
																	?>
																<?php endforeach; ?>
															</tbody>
															<?php if ($total != 0): ?>
																<tfooty>
																	<tr>
																		<td colspan="7"><b>TOTAL</b></td>
																		<td><b><?= $total; ?></b></td>
																		<td colspan="3"></td>
																	</tr>
																</tfoot>
															<?php endif; ?>
														</table>
													</div>
												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-sm-6">
												<div class="dataTables_length">
													<form id="paging" action="<?= site_url("analisis_statistik_jawaban")?>" method="post" class="form-horizontal">
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
															<li><a href="<?=site_url("analisis_statistik_jawaban/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
														<?php endif; ?>
														<?php if ($paging->prev): ?>
															<li><a href="<?=site_url("analisis_statistik_jawaban/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
														<?php endif; ?>
														<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
															<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("analisis_statistik_jawaban/index/$i/$o")?>"><?= $i?></a></li>
														<?php endfor; ?>
														<?php if ($paging->next): ?>
															<li><a href="<?=site_url("analisis_statistik_jawaban/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
														<?php endif; ?>
														<?php if ($paging->end_link): ?>
															<li><a href="<?=site_url("analisis_statistik_jawaban/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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

