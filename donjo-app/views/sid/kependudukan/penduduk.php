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
	.table-responsive
	{
		min-height:275px;
	}
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Penduduk</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Penduduk</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url('penduduk/form')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data"><i class="fa fa-plus"></i> Penduduk Domisili</a>
						<?php if ($this->CI->cek_hak_akses('h')): ?>
							<a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '<?=site_url("penduduk/delete_all/$p/$o")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<?php endif; ?>
						<div class="btn-group-vertical">
							<a class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="<?= site_url("penduduk/cetak")?>" class="btn btn-social btn-flat btn-block btn-sm" title="Cetak Data" target="_blank"><i class="fa fa-print"></i> Cetak</a>
								</li>
								<li>
									<a href="<?= site_url("penduduk/excel/$o")?>" class="btn btn-social btn-flat btn-block btn-sm" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
								</li>
								<li>
									<a href="<?= site_url("penduduk/ajax_adv_search")?>" class="btn btn-social btn-flat btn-block btn-sm" title="Pencarian Spesifik" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik"><i class="fa fa-search"></i> Pencarian Spesifik</a>
								</li>
								<li>
									<a href="<?= site_url("penduduk_log/clear")?>" class="btn btn-social btn-flat btn-block btn-sm" title="Log Data Penduduk"><i class="fa fa-book"></i> Log Penduduk</a>
								</li>
							</ul>
						</div>
						<a href="<?= site_url("penduduk/clear")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<select class="form-control input-sm" name="filter" onchange="formAction('mainform', '<?=site_url('penduduk/filter')?>')">
													<option value="">Semua</option>
													<option value="1" <?php if ($filter==1): ?>selected<?php endif ?>>Aktif</option>
													<option value="2" <?php if ($filter==2): ?>selected<?php endif ?>>Tidak Aktif</option>
												</select>
												<select class="form-control input-sm" name="status_dasar" onchange="formAction('mainform', '<?=site_url('penduduk/status_dasar')?>')">
													<option value="">Status</option>
													<?php foreach ($list_status_dasar AS $data): ?>
														<option value="<?= $data['id']?>" <?php if ($status_dasar == $data['id']): ?>selected<?php endif ?>><?= $data['nama']?></option>
													<?php endforeach; ?>
												</select>
												<select class="form-control input-sm" name="sex" onchange="formAction('mainform', '<?=site_url('penduduk/sex')?>')">
													<option value="">Jenis Kelamin</option>
													<option value="1" <?php if ($sex==1 ): ?>selected<?php endif ?>>Laki-Laki</option>
													<option value="2" <?php if ($sex==2 ): ?>selected<?php endif ?>>Perempuan</option>
												</select>
												<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url('penduduk/dusun')?>')">
													<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun)?></option>
													<?php foreach ($list_dusun AS $data): ?>
														<option value="<?= $data['dusun']?>" <?php if ($dusun == $data['dusun']): ?>selected<?php endif ?>><?= strtoupper($data['dusun'])?></option>
													<?php endforeach; ?>
												</select>
												<?php if ($dusun): ?>
													<select class="form-control input-sm" name="rw" onchange="formAction('mainform','<?= site_url('penduduk/rw')?>')" >
														<option value="">RW</option>
														<?php foreach ($list_rw AS $data): ?>
															<option value="<?= $data['rw']?>" <?php if ($rw == $data['rw']): ?>selected<?php endif ?>><?= $data['rw']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
												<?php if ($rw): ?>
													<select class="form-control input-sm" name="rt" onchange="formAction('mainform','<?= site_url('penduduk/rt')?>')">
														<option value="">RT</option>
														<?php foreach ($list_rt AS $data): ?>
															<option value="<?= $data['rt']?>" <?php if ($rt == $data['rt']): ?>selected<?php endif ?>><?= $data['rt']?></option>
														<?php endforeach; ?>
													</select>
												<?php endif; ?>
											</div>
											<div class="col-sm-3">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("penduduk/search")?>');$('#'+'mainform').submit();}">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("penduduk/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
															<th >Foto</th>
															<?php if ($o==2): ?>
																<th><a href="<?= site_url("penduduk/index/$p/1")?>">NIK <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o==1): ?>
																<th><a href="<?= site_url("penduduk/index/$p/2")?>">NIK <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th><a href="<?= site_url("penduduk/index/$p/1")?>">NIK <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<th>Tag ID Card</th>
															<?php if ($o==4): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/3")?>">Nama <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o==3): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/4")?>">Nama <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/3")?>">Nama <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<?php if ($o==6): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/5")?>">No. KK <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o==5): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/6")?>">No. KK <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/5")?>">No. KK <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<!-- tambah kolom orang tua-->
															<th>Nama Ayah</th>
															<th>Nama Ibu</th>
															<!-- tambah kolom orang tua-->
															<th>No. Rumah Tangga</th>
															<th>Alamat</th>
															<th><?= ucwords($this->setting->sebutan_dusun)?></th>
															<th>RW</th>
															<th>RT</th>
															<th>Pendidikan dalam KK</th>
															<?php if ($o==8): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/7")?>">Umur <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o==7): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/8")?>">Umur <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/7")?>">Umur <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
															<th >Pekerjaan</th>
															<th>Kawin</th>
															<?php if ($o==10): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/9")?>">Tgl Terdaftar <i class='fa fa-sort-asc fa-sm'></i></a></th>
															<?php elseif ($o==9): ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/10")?>">Tgl Terdaftar <i class='fa fa-sort-desc fa-sm'></i></a></th>
															<?php else: ?>
																<th nowrap><a href="<?= site_url("penduduk/index/$p/10")?>">Tgl Terdaftar <i class='fa fa-sort fa-sm'></i></a></th>
															<?php endif; ?>
														</tr>
														</thead>
														<tbody>
														<?php foreach ($main as $data): ?>
															<tr>
																<td><input type="checkbox" name="id_cb[]" value="<?= $data['id']?>" /></td>
																<td><?= $data['no']?></td>
																<td nowrap>
																	<div class="btn-group">
																		<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi</button>
																		<ul class="dropdown-menu" role="menu">
																			<li>
																				<a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-list-ol"></i> Lihat Detail Biodata Penduduk</a>
																			</li>
																			<?php if ($data['status_dasar']==9): ?>
																				<li>
																					<a href="#" data-href="<?= site_url("penduduk/kembalikan_status/$p/$o/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#confirm-status"><i class="fa fa-undo"></i> Kembalikan ke Status HIDUP</a>
																				</li>
																			<?php endif; ?>
																			<?php if ($data['status_dasar']==1): ?>
																				<li>
																					<a href="<?= site_url("penduduk/form/$p/$o/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Ubah Biodata Penduduk</a>
																				</li>
																				<li>
																					<a href="<?= site_url("penduduk/ajax_penduduk_maps/$p/$o/$data[id]/0")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" title="Lokasi <?= $data['nama']?>" data-title="Lokasi <?= $data['nama']?>" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-map-marker'></i> Lihat Lokasi Tempat Tinggal</a>
																				</li>
																				<li>
																					<a href="<?= site_url("penduduk/edit_status_dasar/$p/$o/$data[id]")?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Status Dasar" class="btn btn-social btn-flat btn-block btn-sm"><i class='fa fa-sign-out'></i> Ubah Status Dasar</a>
																				</li>
																				<li>
																					<a href="<?= site_url("penduduk/dokumen/$data[id]")?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-upload"></i> Upload Dokumen Penduduk</a>
																				</li>
																				<li>
																					<a href="<?= site_url("penduduk/cetak_biodata/$data[id]")?>"  target="_blank" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-print"></i> Cetak Biodata Penduduk</a>
																				</li>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<li>
																						<a href="#" data-href="<?= site_url("penduduk/delete/$p/$o/$data[id]")?>"  class="btn btn-social btn-flat btn-block btn-sm" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i> Hapus</a>
																					</li>
																				<?php endif; ?>
																			<?php endif; ?>
																		</ul>
																	</div>
																</td>
																<td nowrap>
																	<div class="user-panel">
																		<div class="image2">
																			<img src="<?= !empty($data['foto']) ? AmbilFoto($data['foto']) : base_url('assets/files/user_pict/kuser.png') ?>" class="img-circle" alt="Foto Penduduk"/>
																		</div>
																	</div>
																</td>
																<td>
																	<a href="<?= site_url("penduduk/detail/$p/$o/$data[id]")?>" id="test" name="<?= $data['id']?>"><?= $data['nik']?></a>
																</td>
																<td nowrap><?= $data['tag_id_card']?></td>
																<td nowrap><?= strtoupper($data['nama'])?></td>
																<td><a href="<?= site_url("keluarga/kartu_keluarga/$p/$o/$data[id_kk]")?>"><?= $data['no_kk']?> </a></td>
																<!-- tambah kolom orang tua-->
																<td><?= $data['nama_ayah']?></td>
																<td><?= $data['nama_ibu']?></td>
																<!-- tambah kolom orang tua-->
																<td><a href="<?= site_url("rtm/anggota/$p/$o/$data[id_rtm]")?>"><?= $data['no_rtm']?></a></td>
																<td><?= strtoupper($data['alamat'])?></td>
																<td><?= strtoupper($data['dusun'])?></td>
																<td><?= $data['rw']?></td>
																<td><?= $data['rt']?></td>
																<td><?= $data['pendidikan']?></td>
																<td><?= $data['umur']?></td>
																<td><?= $data['pekerjaan']?></td>
																<td><?= $data['kawin']?></td>
																<td><?= tgl_indo($data['created_at'])?></td>
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
												<form id="paging" action="<?= site_url("penduduk")?>" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="50" <?php selected($per_page, 50); ?> >50</option>
															<option value="100" <?php selected($per_page, 100); ?> >100</option>
															<option value="200" <?php selected($per_page, 200); ?> >200</option>
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
														<li><a href="<?=site_url("penduduk/index/$paging->start_link/$o")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
													<?php endif; ?>
													<?php if ($paging->prev): ?>
														<li><a href="<?=site_url("penduduk/index/$paging->prev/$o")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
													<?php endif; ?>
													<?php for ($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
														<li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("penduduk/index/$i/$o")?>"><?= $i?></a></li>
													<?php endfor; ?>
													<?php if ($paging->next): ?>
														<li><a href="<?=site_url("penduduk/index/$paging->next/$o")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
													<?php endif; ?>
													<?php if ($paging->end_link): ?>
														<li><a href="<?=site_url("penduduk/index/$paging->end_link/$o")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
