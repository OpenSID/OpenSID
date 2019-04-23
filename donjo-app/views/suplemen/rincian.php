<style>
	.input-sm
	{
		padding: 4px 4px;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Rincian Data Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('suplemen')?>"> Data Suplemen</a></li>
			<li class="active">Rincian Data Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("suplemen/form_terdata/".$suplemen['id'])?>" title="Tambah Data Warga" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Warga Terdata</a>
						<a href="<?= site_url("suplemen/unduhsheet/$suplemen[id]")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa  fa-download"></i> Unduh</a>
						<a href="<?= site_url("suplemen")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Data Suplemen">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Data Suplemen
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<tbody>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Nama Data</td>
												<td> : <?= strtoupper($suplemen["nama"])?></td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Sasaran Terdata</td>
												<td> : <?= $sasaran[$suplemen["sasaran"]]?></td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
												<td> : <?= $suplemen["keterangan"]?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
																<th><?= $suplemen["judul_terdata_nama"]?></th>
																<th><?= $suplemen["judul_terdata_info"]?></th>
																<th>Tempat Lahir</th>
																<th>Tanggal Lahir</th>
																<th>Jenis-kelamin</th>
																<th>Alamat</th>
																<th>Keterangan</th>
															</tr>
														</thead>
														<tbody>
															<?php
																$nomer = $paging->offset;
																if (is_array($terdata)):
																	foreach ($terdata as $key=>$item):
																		$nomer++;
															?>
																<tr>
																	<td align="center" width="2"><?= $nomer; ?></td>
																	<td nowrap>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<a href="<?= site_url("suplemen/edit_terdata_form/$item[id]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Terdata" title="Ubah Terdata" class="btn btn-warning btn-flat btn-sm"><i class="fa fa-edit"></i></a>
																			<a href="#" data-href="<?= site_url('suplemen/hapus_terdata/'.$suplemen["id"].'/'.$item["id"])?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																	</td>
																	<td nowrap><a href="<?= site_url('suplemen/terdata/'.$suplemen["sasaran"].'/'.$item["id_terdata"].'/')?>" title="Daftar suplemen untuk terdata"><?= $item["terdata_nama"] ?></a></td>
																	<td nowrap><a href="<?= site_url('suplemen/data_terdata/'.$item["id"])?>" title="Data terdata"><?= $item['terdata_info'];?></a></td>
																	<td><?= $item["tempat_lahir"] ?></td>
																	<td><?= $item["tanggal_lahir"] ?></td>
																	<td><?= strtoupper($item['sex'])?></td>
																	<td><?= $item["info"];?></td>
																	<td width="25%"><?= $item["keterangan"];?></td>
																</tr>
																	<?php
																	endforeach;
																endif;
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="<?= site_url('suplemen/rincian/1/'.$suplemen['id'])?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="10" <?php selected($per_page,10); ?> >10</option>
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
                            <li><a href="<?=site_url('suplemen/rincian/'.$paging->start_link.'/'.$suplemen['id'])?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url('suplemen/rincian/'.$paging->prev.'/'.$suplemen['id'])?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url('suplemen/rincian/'.$i.'/'.$suplemen['id'])?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url('suplemen/rincian/'.$paging->next.'/'.$suplemen['id'])?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url('suplemen/rincian/'.$paging->end_link.'/'.$suplemen['id'])?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
										Apakah Anda yakin ingin memecah Data Keluarga ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-delete"><i class='fa fa-check'></i> Simpan</button>
										</a>
									</div>
								</div>
							</div>
						</div>
						<div  class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'></h4>
									</div>
									<div class="fetched-data"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

