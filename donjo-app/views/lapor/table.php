<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Masuk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Laporan Masuk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("lapor/delete_all/$p/$o")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform','<?=site_url('lapor/filter')?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Sudah Tindak Lanjut</option>
														<option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Belum Tindak Lanjut</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action','<?=site_url('lapor/search')?>');$('#'+'mainform').submit();endif;">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("lapor/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
                                  <th>Pengirim</th>
                                  <th>NIK</th>
																	<?php if ($o==2): ?>
                                    <th><a href="<?= site_url("lapor/index/$p/1")?>">Isi Pesan <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o==1): ?>
                                    <th><a href="<?= site_url("lapor/index/$p/2")?>">Isi Pesan <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th><a href="<?= site_url("lapor/index/$p/1")?>">Isi Pesan <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>

                                  <?php if ($o==6): ?>
                                    <th nowrap><a href="<?= site_url("lapor/index/$p/3")?>">Sudah Tindak Lanjut <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o==5): ?>
                                    <th nowrap><a href="<?= site_url("lapor/index/$p/4")?>">Sudah Tindak Lanjut <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th nowrap><a href="<?= site_url("lapor/index/$p/3")?>">Sudah Tindak Lanjut <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>

                                  <?php if ($o==4): ?>
                                    <th><a href="<?= site_url("lapor/index/$p/5")?>">Dilaporkan Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o==3): ?>
                                    <th><a href="<?= site_url("lapor/index/$p/6")?>">Dilaporkan Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th><a href="<?= site_url("lapor/index/$p/5")?>">Dilaporkan Pada <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($main as $data): ?>
																	<tr <?php if ($data['enabled']!=1): ?>style='background-color:#ffeeaa;'<?php endif; ?>>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<td><?=$data['no']?></td>
																		<td nowrap>
                                      <a href="#" data-href="<?=site_url("lapor/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                      <?php if ($data['enabled'] == '2'): ?>
                                        <a href="<?=site_url('lapor/komentar_lock/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Tindak-lanjuti laporan"><i class="fa fa-lock">&nbsp;</i></a>
                                      <?php elseif ($data['enabled'] == '1'): ?>
                                        <a href="<?=site_url('lapor/komentar_unlock/'.$data['id'])?>" class="btn bg-navy btn-flat btn-sm" title="Kembalikan ke status awal"><i class="fa fa-unlock"></i></a>
                                      <?php endif; ?>
                                    </td>
                                    <td nowrap><?=$data['owner']?></td>
                                    <td><?=$data['email']?></td>
                                    <td width="40%"><?=$data['komentar']?></td>
                                    <td><?=$data['aktif']?></td>
                                    <td nowrap><?=tgl_indo2($data['tgl_upload'])?></td>
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
                          <form id="paging" action="<?= site_url("lapor")?>" method="post" class="form-horizontal">
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
                              <li><a href="<?=site_url("lapor/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->prev): ?>
                              <li><a href="<?=site_url("lapor/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                            <?php endif; ?>
                            <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
                              <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("lapor/index/$i/$o")?>"><?= $i?></a></li>
                            <?php endfor; ?>
                            <?php if ($paging->next): ?>
                              <li><a href="<?=site_url("lapor/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?=site_url("lapor/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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

