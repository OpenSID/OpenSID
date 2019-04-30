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
		<h1>Wilayah Administratif <?= ucwords($this->setting->sebutan_dusun)?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar <?= ucwords($this->setting->sebutan_dusun)?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('sid_core/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Tambah Dusun</a>
						<a href="<?= site_url('sid_core/cetak')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url('sid_core/excel')?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa  fa-download"></i> Unduh</a>
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
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action','<?= site_url('sid_core/search')?>');$('#'+'mainform').submit();};">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action','<?= site_url("sid_core/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th width="25%"> <?= ucwords($this->setting->sebutan_dusun)?></th>
																<th width="35%">Kepala <?= ucwords($this->setting->sebutan_dusun)?></th>
																<th>RW</th>
																<th>RT</th>
																<th>KK</th>
																<th>L+P</th>
																<th>L</th>
																<th>P</th>

															</tr>
														</thead>
														<tbody>
															<?php
																$total = array();

																$total['total_rw'] = 0;
																$total['total_rt'] = 0;
																$total['total_kk'] = 0;
																$total['total_warga'] = 0;
																$total['total_warga_l'] = 0;
																$total['total_warga_p'] = 0;

																foreach ($main as $data):
															?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																		<td class="no_urut"><?= $data['no']?></td>
																		<td nowrap>
																			<a href="<?= site_url("sid_core/sub_rw/$data[id]")?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian Sub Wilayah"><i class="fa fa-list"></i></a>
																			<a href="<?= site_url("sid_core/form/$data[id]")?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
																			<?php if ($this->CI->cek_hak_akses('h')): ?>
																				<a href="#" data-href="<?= site_url("sid_core/delete/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php endif; ?>
																		</td>
																		<td><?= strtoupper($data['dusun'])?></td>
																		<td nowrap><strong><?= strtoupper($data['nama_kadus'])?></strong> - <?= $data['nik_kadus']?></td>
																		<td class="bilangan"><a href="<?= site_url("sid_core/sub_rw/$data[id]")?>" title="Rincian Sub Wilayah"><?= $data['jumlah_rw']?></a></td>
																		<td class="bilangan"><?= $data['jumlah_rt']?></td>
																		<td class="bilangan"><a href="<?= site_url("sid_core/warga_kk/$data[id]")?>"><?= $data['jumlah_kk']?></a></td>
																		<td class="bilangan"><a href="<?= site_url("sid_core/warga/$data[id]")?>"><?= $data['jumlah_warga']?></a></td>
																		<td class="bilangan"><a href="<?= site_url("sid_core/warga_l/$data[id]")?>"><?= $data['jumlah_warga_l']?></a></td>
																		<td class="bilangan"><a href="<?= site_url("sid_core/warga_p/$data[id]")?>"><?= $data['jumlah_warga_p']?></a></td>
																	</tr>
																	<?php
																		$total['total_rw'] += $data['jumlah_rw'];
																		$total['total_rt'] += $data['jumlah_rt'];
																		$total['total_kk'] += $data['jumlah_kk'];
																		$total['total_warga'] += $data['jumlah_warga'];
																		$total['total_warga_l'] += $data['jumlah_warga_l'];
																		$total['total_warga_p'] += $data['jumlah_warga_p'];

																endforeach;
															?>
														</tbody>
														<tfoot>
															<tr>
																<th colspan="5"><label>TOTAL</label></th>
																<th class="bilangan"><?= $total['total_rw']?></th>
																<th class="bilangan"><?= $total['total_rt']?></th>
																<th class="bilangan"><?= $total['total_kk']?></th>
																<th class="bilangan"><?= $total['total_warga']?></th>
																<th class="bilangan"><?= $total['total_warga_l']?></th>
																<th class="bilangan"><?= $total['total_warga_p']?></th>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="<?= site_url("sid_core")?>" method="post" class="form-horizontal">
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
                            <li><a href="<?= site_url("sid_core/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?= site_url("sid_core/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("sid_core/index/$i/$o")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?= site_url("sid_core/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?= site_url("sid_core/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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

