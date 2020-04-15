<div class="content-wrapper">
	<section class="content-header">
		<h1>Rekam Surat Perseorangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluar')?>"> Arsip Layanan Surat</a></li>
			<li class="active">Rekam Surat Perseorangan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("keluar")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Arsip Layanan Surat
           	</a>
					</div>
					<div class="box-header with-border">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" >
								<tbody>
									<form id="main" name="main" action="" method="post">
										<tr>
											<td style="padding-top : 10px;padding-bottom : 10px;width:15%;" >Nama Penduduk </td>
											<td>
												<div class="form-group">
              						<div class="col-sm-6 col-lg-6">
              							<select class="form-control required input-sm select2-nik-ajax" id="nik" name="nik" data-url="<?= site_url('surat/list_penduduk_bersurat_ajax')?>" onchange="formAction('main')">
															<?php if ($individu): ?>
																<option value="<?= $individu['id']?>" selected><?= $individu['nik'].' - '.$individu['nama']?></option>
															<?php endif;?>
                						</select>
      	    							</div>
           	 						</div>
											</td>
										</tr>
									</form>
									<?php if ($individu): //bagian info setelah terpilih?>
										<tr>
											<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Tempat/ Tanggal Lahir (Umur)</td>
											<td> <?= $individu['tempatlahir']?> / <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)</td>
										</tr>
										<tr>
											<td style="padding-top : 10px;padding-bottom : 10px;" >Alamat</td>
											<td> <?= $individu['alamat_wilayah']; ?></td>
										</tr>
										<tr>
											<td style="padding-top : 10px;padding-bottom : 10px;" >Pendidikan</td>
											<td> <?= $individu['pendidikan']?></td>
										</tr>
										<tr>
											<td style="padding-top : 10px;padding-bottom : 10px;" >Warganegara / Agama</td>
											<td> <?= $individu['warganegara']?> / <?= $individu['agama']?></td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
																<th>Kode Surat</th>
																<?php if ($o==2): ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/1")?>">No Urut <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==1): ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/2")?>">No Urut <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/1")?>">No Urut <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>Jenis Surat</th>
																<?php if ($o==4): ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/3")?>">Nama Penduduk <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==3): ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/4")?>">Nama Penduduk <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/3")?>">Nama Penduduk <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>Keterangan</th>
																<th>Ditandatangani Oleh</th>
																<?php if ($o==6): ?>
																	<th nowrap><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/5")?>">Tanggal <i class='fa fa-sort-asc fa-sm'></i></a></th>
																<?php elseif ($o==5): ?>
																	<th nowrap><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/6")?>">Tanggal <i class='fa fa-sort-desc fa-sm'></i></a></th>
																<?php else: ?>
																	<th nowrap><a href="<?= site_url("keluar/perorangan/{$nik['no']}/$p/5")?>">Tanggal <i class='fa fa-sort fa-sm'></i></a></th>
																<?php endif; ?>
																<th>User</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data): ?>
																<?php
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
																				<a href="<?= base_url(LOKASI_ARSIP.$data['lampiran'])?>" target="_blank" class="btn btn-social btn-flat bg-olive btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip"></i>  Lampiran</a>
																			<?php	endif; ?>
																		<a href="<?= site_url("keluar/edit_keterangan/$data[id]")?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
																		<a href="#" data-href="<?= site_url("keluar/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																	</td>
																	<td><?= $data['kode_surat']?></td>
																	<td><?= $data['no_surat']?></td>
																	<td><?= $data['format']?></td>
																	<td><?= $data['nama']?></td>
																	<td><?= $data['keterangan']?></td>
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
												<form id="paging" action="<?= site_url("keluar/perorangan/$nik[no]")?>" method="post" class="form-horizontal">
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
                            <li><a href="<?=site_url("keluar/perorangan/$nik[no]/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("keluar/perorangan/$nik[no]/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("keluar/perorangan/$nik[no]/$i/$o")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("keluar/perorangan/$nik[no]/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("keluar/perorangan/$nik[no]/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
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
<?php $this->load->view('global/confirm_delete');?>