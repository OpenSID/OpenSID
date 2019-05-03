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
		<h1>Log Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
			<li class="active">Log Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="mainform" name="mainform" action="" method="post">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-sm-12">
									<a href="#confirm-status" title="Kembalikan Status" onclick="aksiBorongan('mainform','<?= site_url("penduduk_log/kembalikan_status_all")?>')" class="btn btn-social btn-flat	btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-undo'></i> Kembalikan Status</a>
									<a href="<?= site_url("penduduk_log/cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
									<a href="<?= site_url("penduduk_log/excel/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa  fa-download"></i> Unduh</a>
									<a href="<?= site_url('penduduk/clear')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Penduduk</a>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?=site_url('penduduk_log/status_dasar')?>')">
													<option value="">Semua</option>
													<?php foreach ($list_status_dasar as $data): ?>
                            <?php if (strtolower($data['nama']) != 'hidup'): ?>
                              <option value="<?= $data['id']?>" <?php if ($status_dasar==$data['id']): ?>selected<?php endif; ?>><?= ucwords(strtolower($data['nama']))?></option>
                            <?php endif; ?>
                          <?php endforeach; ?>
												</select>
												<select class="form-control input-sm" name="sex" onchange="formAction('mainform','<?= site_url('penduduk_log/sex')?>')">
                          <option value="">Jenis Kelamin</option>
                          <option value="1" <?php if ($sex==1 ): ?>selected<?php endif ?>>Laki-Laki</option>
                 	        <option value="2" <?php if ($sex==2 ): ?>selected<?php endif ?>>Perempuan</option>
                        </select>
                        <select class="form-control input-sm" name="agama" onchange="formAction('mainform','<?= site_url('penduduk_log/agama')?>')">
                          <option value="">Agama</option>
                 					<?php foreach ($list_agama AS $data): ?>
                            <option value="<?= $data['id']?>" <?php if ($agama == $data['id']): ?>selected<?php endif ?>><?= $data['nama']?></option>
                					<?php endforeach; ?>
                        </select>
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('penduduk_log/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('penduduk_log/rw')?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('penduduk_log/rt')?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-3">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url('penduduk_log/search')?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url('penduduk_log/search')?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
                              <tr>
                             		<th>No</th>
                    	     			<th><input type="checkbox" class="checkall"/></th>
                           			<th width="85">Aksi</th>
                           			<?php if ($o==2): ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/1")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                          			<?php elseif ($o==1): ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/2")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></span></a></th>
                          			<?php else: ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/1")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
                          			<?php endif; ?>
                           			<?php if ($o==4): ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
                          			<?php elseif ($o==3): ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
                          			<?php else: ?>
                             			<th><a href="<?= site_url("penduduk_log/index/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
                          			<?php endif; ?>
	                          		<?php if ($o==6): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/5")?>">No. KK / Nama KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==5): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/6")?>">No. KK / Nama KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
                               	<?php else: ?>
																 	<th><a href="<?= site_url("penduduk_log/index/$p/5")?>">No. KK / Nama KK <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
                            		<th><?= ucwords($this->setting->sebutan_dusun)?></th>
                            		<th>RW</th>
                            		<th>RT</th>

                                <?php if ($o==8): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/7")?>">Umur <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==7): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/8")?>">Umur <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/7")?>">Umur <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>

                            		<th>Status Menjadi</th>
                            		<?php if ($o==10): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/9")?>">Tanggal Peristiwa <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==9): ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/10")?>">Tanggal Peristiwa <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
																	<th><a href="<?= site_url("penduduk_log/index/$p/9")?>">Tanggal Peristiwa <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
                                <th>Tanggal Rekam</th>
                              	<th>Catatan Peristiwa</th>
                            	</tr>
                        		</thead>
                              <tbody>
                                <?php foreach ($main as $data): ?>
                               		<tr>
                                    <td><?= $data['no']?></td>
                                    <td>
                                      <input type="checkbox" name="id_cb[]" value="<?= $data['id_log']?>" />
                                    </td>
                                    <td nowrap>
                                      <a href="<?= site_url("penduduk_log/edit/$p/$o/$data[id_log]")?>" class="btn bg-orange btn-flat btn-sm"  title="Edit Log Penduduk" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Log Penduduk" ><i class="fa fa-edit"></i></a>
                                      <a href="#" data-href="<?= site_url("penduduk_log/kembalikan_status/$data[id_log]")?>" class="btn bg-olive btn-flat btn-sm" title="Kembalikan Status"  data-remote="false"  data-toggle="modal" data-target="#confirm-status"><i class="fa fa-undo"></i></a>
                                    </td>
                                    <td>
                                      <a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?= $data['id']?>"><?= $data['nik']?></a>
                                    </td>
                                    <td>
                                      <a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>"><?= strtoupper($data['nama'])?></a>
                                    </td>
                                    <td>
                                      <a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?= $data['no_kk']?> </a> <br>
                                      <?= $data['nama_kk']?>
                                    </td>
                                    <td><?= $data['dusun']?></td>
                                    <td><?= $data['rw']?></td>
                                    <td><?= $data['rt']?></td>
                                    <td><?= $data['umur_pada_peristiwa']?></td>
                                    <td><?= $data['status_dasar']?></td>
                                    <td><?= tgl_indo($data['tgl_peristiwa'])?></td>
                                    <td><?= tgl_indo($data['tanggal'])?></td>
                                    <td><?= $data['catatan']?></td>
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
												<form id="paging" action="<?= site_url("penduduk_log")?>" method="post" class="form-horizontal">
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
                            <li><a href="<?=site_url("penduduk_log/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("penduduk_log/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("penduduk_log/index/$i/$o")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("penduduk_log/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("penduduk_log/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
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
											<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-status"><i class='fa fa-check'></i> Simpan</button>
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

