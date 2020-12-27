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
		<h1>Permohonan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Permohonan Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-3">
									<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?=site_url("{$this->controller}/filter")?>')">
										<option value="">Status</option>
										<?php foreach ($list_status_permohonan AS $id => $nama): ?>
											<option value="<?= $id?>" <?php if ($filter != '' and $filter == $id): ?>selected<?php endif ?>><?= $nama?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-sm-3  pull-right">
									<div class="input-group input-group-sm">
										<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>NIK</th>
																	<th>Nama Penduduk</th>
																	<th>No HP Aktif</th>
																	<th>Jenis Surat</th>
																	<th>Status</th>
																	<th><?= url_order($o, "{$this->controller}/{$func}/$p", 1, 'Tanggal Kirim'); ?></th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr>
																		<td><?=$data['no']?></td>
																		<td nowrap>
																			<?php if (in_array($data['status_id'], array(0, 1))): ?>
																				<a href="<?=site_url("{$this->controller}/periksa/$data[id]")?>" class="btn bg-green btn-flat btn-sm"  title="Periksa"><i class="fa fa-question-circle"></i></a>
																			<?php endif; ?>
																			<?php if ($data['status_id'] == 2): ?>
																				<a href="<?= site_url("{$this->controller}/update_status/$data[id]/3")?>" title="Status siap diambil" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-arrow-circle-right"></i></a>
																			<?php elseif ($data['status_id'] == 3): ?>
																				<a href="<?= site_url("{$this->controller}/update_status/$data[id]/4")?>" title="Status sudah diambil" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-arrow-circle-right"></i></a>
																			<?php endif; ?>
																			<?php if (in_array($data['status_id'], array(0, 1))): ?>
																				<a href="#" data-href="<?=site_url("{$this->controller}/delete/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td nowrap><?=$data['nik'];?></td>
																		<td><?=$data['nama']?></td>
																		<td><?=$data['no_hp_aktif']?></td>
																		<td><?=$data['jenis_surat']?></td>
																		<td><?=$data['status']?></td>
																		<td nowrap><?=tgl_indo2($data['created_at'])?></td>
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
                          <form id="paging" action="<?= site_url("{$this->controller}")?>" method="post" class="form-horizontal">
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
                              <li><a href="<?=site_url("{$this->controller}/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->prev): ?>
                              <li><a href="<?=site_url("{$this->controller}/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                            <?php endif; ?>
                            <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                              <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("{$this->controller}/index/$i/$o")?>"><?= $i?></a></li>
                            <?php endfor; ?>
                            <?php if ($paging->next): ?>
                              <li><a href="<?=site_url("{$this->controller}/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?=site_url("{$this->controller}/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                            <?php endif; ?>
                          </ul>
                        </div>
                      </div>
                    </div>
									</div>
								</div>
							</div>
							<div  class="modal fade" id="pinBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header btn-info'>
											<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
											<h4 class='modal-title' id='myModalLabel'> PIN Warga</h4>
										</div>
										<div class='modal-body'>
											Berikut adalah kode pin yang baru saja di hasilkan, silakan dicatat atau di ingat dengan baik, kode pin ini sangat rahasia, dan hanya bisa dilihat sekali ini lalu setelah itu hanya bisa di reset saja. <br /> <h4>Kode PIN : <?=$_SESSION['pin'];?>
										</div>
										<div class='modal-footer'>
											<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
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
<?php $this->load->view('global/confirm_delete');?>