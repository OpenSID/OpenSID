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
<style>
	.input-sm
	{
		padding: 4px 4px;
	}
	@media (max-width:780px)
	{
		.btn-group-vertical
		{
			display: block;
		}
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Keluarga</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<div class="btn-group btn-group-vertical">
							<a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah KK Baru</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="<?= site_url('keluarga/form')?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data KK Baru"><i class="fa fa-plus"></i> Tambah Penduduk Baru</a>
								</li>
								<li>
									<a href="<?= site_url('keluarga/form_old')?>" class="btn btn-social btn-flat btn-block btn-sm" title="Tambah Data KK dari keluarga yang sudah ter-input" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Kepala Keluarga"><i class="fa fa-plus"></i> Dari Penduduk Sudah Ada</a>
								</li>
							</ul>
						</div>
						<a href="<?= site_url("keluarga/cetak/$o")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
						<a href="<?= site_url("keluarga/excel/$o")?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa  fa-download"></i> Unduh</a>
						<div class="btn-group btn-group-vertical">
							<a class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Aksi Data Terpilih</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="" class="btn btn-social btn-flat btn-block btn-sm" title="Cetak Kartu Keluarga" onclick="formAction('mainform','<?= site_url("keluarga/cetak_kk_all")?>', '_blank'); return false;"><i class="fa fa-print"></i> Cetak Kartu Keluarga</a>
								</li>
								<li>
									<a href="" class="btn btn-social btn-flat btn-block btn-sm" title="Unduh Kartu Keluarga" onclick="formAction('mainform','<?= site_url("keluarga/doc_kk_all")?>'); return false;"><i class="fa fa-download"></i> Unduh Kartu Keluarga</a>
								</li>
								<?php if ($this->CI->cek_hak_akses('h')): ?>
									<li>
										<a href="#confirm-delete" class="btn btn-social btn-flat btn-block btn-sm hapus-terpilih" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url("keluarga/delete_all/$p/$o")?>')"><i class="fa fa-trash-o"></i> Hapus Data Terpilih</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?=site_url('keluarga/status_dasar')?>')">
													<option value="">Semua KK</option>
													<option value="1" <?php if ($status_dasar == 1): ?>selected<?php endif ?>>KK Aktif</option>
													<option value="2" <?php if ($status_dasar == 2): ?>selected<?php endif ?>>KK Hilang/Pindah/Mati</option>
													<option value="3" <?php if ($status_dasar == 3): ?>selected<?php endif ?>>KK Kosong</option>
												</select>
												<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?=site_url('keluarga/sex')?>')">
													<option value="">Jenis Kelamin</option>
													<option value="1" <?php if ($sex==1 ): ?>selected<?php endif ?>>Laki-Laki</option>
													<option value="2" <?php if ($sex==2 ): ?>selected<?php endif ?>>Perempuan</option>
												</select>
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('keluarga/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach;?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('keluarga/rw')?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('keluarga/rt')?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
														<?php endforeach;?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-3">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("keluarga/search")?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("keluarga/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<?php if ($judul_statistik): ?>
														<h5 class="box-title text-center"><b><?= $judul_statistik; ?></b></h5>
													<?php endif; ?>
													<table class="table table-bordered table-striped dataTable table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th><input type="checkbox" id="checkall"/></th>
																<th>No</th>
																<th >Aksi</th>
																<?php if ($o==2): ?>
                                  <th><a href="<?= site_url("keluarga/index/$p/1")?>">Nomor KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                 <?php elseif ($o==1): ?>
                                  <th><a href="<?= site_url("keluarga/index/$p/2")?>">Nomor KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th><a href="<?= site_url("keluarga/index/$p/1")?>">Nomor KK <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
                                <?php if ($o==4): ?>
                                  <th nowrap><a href="<?= site_url("keluarga/index/$p/3")?>">Kepala Keluarga <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==3): ?>
                                  <th nowrap><a href="<?= site_url("keluarga/index/$p/4")?>">Kepala Keluarga <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
                                  <th nowrap><a href="<?= site_url("keluarga/index/$p/3")?>">Kepala Keluarga <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
																<th>NIK</th>
																<th>Tag ID Card</th>
																<th>Jumlah Anggota</th>
																<th>Jenis Kelamin</th>
																<th>Alamat</th>
																<th><?= ucwords($this->setting->sebutan_dusun)?></th>
																<th>RW</th>
																<th>RT</th>
                                <?php if ($o==6): ?>
																	<th nowrap><a href="<?= site_url("keluarga/index/$p/5")?>">Tanggal Terdaftar <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                <?php elseif ($o==5): ?>
																	<th nowrap><a href="<?= site_url("keluarga/index/$p/6")?>">Tanggal Terdaftar <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                <?php else: ?>
																	<th nowrap><a href="<?= site_url("keluarga/index/$p/6")?>">Tanggal Terdaftar <i class='fa fa-sort fa-sm'></i></a></th>
                                <?php endif; ?>
																<th nowrap>Tanggal Cetak KK</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($main as $data): ?>
																<tr>
																	<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																	<td><?= $data['no']?></td>
																	<td nowrap>
																		<a href="<?= site_url("keluarga/anggota/$p/$o/$data[id]")?>" class="btn bg-purple btn-flat btn-sm"  title="Rincian Anggota Keluarga (KK)"><i class="fa fa-list-ol"></i></a>
																		<a href="<?= site_url("keluarga/form_a/$p/$o/$data[id]")?>" class="btn btn-success btn-flat btn-sm " title="Tambah Anggota Keluarga" ><i class="fa fa-plus"></i> </a>
																		<a href="<?= site_url("keluarga/edit_nokk/$p/$o/$data[id]")?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data KK" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<a href="#" data-href="<?= site_url("keluarga/delete/$p/$o/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus/Keluar Dari Daftar Keluarga" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		<?php endif; ?>
																	</td>
																	<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id]")?>"><?= $data['no_kk']?></a></td>
																	<td nowrap><?= strtoupper($data['kepala_kk'])?></td>
																	<td><a href="<?= site_url("penduduk/detail/1/0/$data[id_pend]")?>"><?= strtoupper($data['nik'])?></a></td>
																	<td><?= $data['tag_id_card']?></td>
																	<td><a href="<?= site_url("keluarga/anggota/$p/$o/$data[id]")?>"><?= $data['jumlah_anggota']?></a></td>
																	<td><?= strtoupper($data['sex'])?></td>
																	<td><?= strtoupper($data['alamat'])?></td>
																	<td><?= strtoupper($data['dusun'])?></td>
																	<td><?= strtoupper($data['rw'])?></td>
																	<td><?= strtoupper($data['rt'])?></td>
																	<td><?= tgl_indo($data['tgl_daftar'])?></td>
																	<td><?= tgl_indo($data['tgl_cetak_kk'])?></td>
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
												<form id="paging" action="<?= site_url("keluarga")?>" method="post" class="form-horizontal">
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
                            <li><a href="<?=site_url("keluarga/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->prev): ?>
                            <li><a href="<?=site_url("keluarga/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                          <?php endif; ?>
                          <?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
               	            <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("keluarga/index/$i/$o")?>"><?= $i?></a></li>
                          <?php endfor; ?>
                          <?php if ($paging->next): ?>
                            <li><a href="<?=site_url("keluarga/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                          <?php endif; ?>
                          <?php if ($paging->end_link): ?>
                            <li><a href="<?=site_url("keluarga/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
		</div>
	</section>
</div>

