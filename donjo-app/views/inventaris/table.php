<div class="content-wrapper">
	<section class="content-header">
		<h1>Inventaris dan Kekayaan Desa</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Inventaris dan Kekayaan Desa</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php	$this->load->view('inventaris/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?=site_url("{$this->controller}/form_jenis/")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Data Baru">
								<i class="fa fa-plus"></i>Tambah Jenis Barang Baru
            	</a>
							<a href="<?= site_url("inventaris/dialog_cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Inventaris">
								<i class="fa fa-print"></i>Cetak
            	</a>
							<a href="<?= site_url("inventaris/dialog_excel/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Inventaris">
								<i class="fa fa-download"></i>Unduh
            	</a>
						</div>
						<div class="box-header with-border">
							<div class="mailbox-attachment-info">
								<p class="text-red">Modul ini akan dihilangkan pada rilis September 2018 karena telah diganti dengan modul Inventaris baru. Pastikan memindahkan semua data ke format Inventaris baru sebelum rilis tersebut.</p>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=$cari?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("$this->controller/search_jenis")?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("$this->controller/search_jenis")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped table-hover nowrap">
															<thead class="bg-gray">
																<tr>
																	<th class="text-center" rowspan="3">No</th>
																	<th class="text-center" rowspan="3">Aksi</th>
																	<?php if ($o==2): ?>
                                    <th class="text-center" rowspan="3"><a href="<?= site_url("{$this->controller}/index/$p/1")?>">Jenis Barang <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o==1): ?>
                                    <th class="text-center" rowspan="3"><a href="<?= site_url("{$this->controller}/index/$p/2")?>">Jenis Barang <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th class="text-center" rowspan="3"><a href="<?= site_url("{$this->controller}/index/$p/1")?>">Jenis Barang <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																	<th class="text-center" rowspan="3" >Keterangan</th>
																	<th class="text-center" colspan="5">Asal Barang</th>
																	<th class="text-center" colspan="2">Keadaan Barang</th>
																</tr>
																<tr>
																	<th class="text-center" rowspan="2">Dibeli Sendiri</th>
																	<th class="text-center" colspan="3">Bantuan</th>
																	<th class="text-center" rowspan="2">Sumbangan</th>
																	<th class="text-center" rowspan="2">Baik</th>
																	<th class="text-center" rowspan="2">Rusak</th>
																</tr>
																<tr>
																	<th class="text-center">Pemerintah</th>
																	<th class="text-center">Provinsi</th>
																	<th class="text-center">Kabupaten</th>
																</tr>
															</thead>
															<tbody>
																<?php $i = 0; foreach ($main as $data): $i++;?>
																	<tr>
																		<td><?= $i+$paging->offset?></td>
																		<td nowrap>
																			<a href="<?= site_url("{$this->controller}/form_jenis/$p/$o/$data[id]")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																			<a href="<?= site_url('inventaris/rincian/'.$data["id"].'/')?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian"><i class="fa fa-bars"></i></a>
																			<a href="#" data-href="<?= site_url("{$this->controller}/delete_jenis/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	  </td>
																	  <td><?= $data['nama']?></td>
																		<td><?= $data['keterangan']?></td>
																		<td><?= $data['asal_sendiri']?></td>
																		<td><?= $data['asal_pemerintah']?></td>
																		<td><?= $data['asal_provinsi']?></td>
																		<td><?= $data['asal_kab']?></td>
																		<td><?= $data['asal_sumbangan']?></td>
																		<td><?= $data['status_baik']?></td>
																		<td><?= $data['status_rusak']?></td>
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
                          <form id="paging" action="<?= site_url($this->controller.'/index/')?>" method="post" class="form-horizontal">
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
                              <li><a href="<?= site_url("{$this->controller}/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?= site_url("{$this->controller}/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
											<h4 class='modal-title' id='myModalLabel'><i class='fa fa-text-width text-yellow'></i> Konfirmasi</h4>
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

