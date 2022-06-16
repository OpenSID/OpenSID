<script type="text/javascript">
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
		<h1>Daftar Gambar Album</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('gallery')?>"><i class="fa fa-dashboard"></i> Daftar Album</a></li>
			<li class="active">Daftar Gambar Album</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
							<?php if ($this->CI->cek_hak_akses('u')): ?>
								<a href="<?= site_url("gallery/form_sub_gallery/{$gallery}")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Album">
									<i class="fa fa-plus"></i> Tambah Gambar Baru
	            	</a>
	            <?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('h')): ?>
								<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("gallery/delete_all_sub_gallery/{$gallery}")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<?php endif; ?>
							<a href="<?= site_url('gallery')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Album">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Album
            	</a>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title">Nama Album : <strong><?= $sub['nama']?></strong></h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" method="post">
											<div class="row">
												<div class="col-sm-6">
													<select class="form-control input-sm " name="filter" onchange="formAction('mainform', '<?= site_url("gallery/filter/{$gallery}")?>')">
														<option value="">Semua</option>
														<option value="1" <?php if ($filter == 1): ?>selected<?php endif ?>>Aktif</option>
														<option value="2" <?php if ($filter == 2): ?>selected<?php endif ?>>Tidak Aktif</option>
													</select>
												</div>
												<div class="col-sm-6">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform').attr('action', '<?= site_url('gallery/search/$gallery')?>');$('#'+'mainform').submit();endif">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("gallery/search/{$gallery}")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																	<?php if ($this->CI->cek_hak_akses('h')): ?>
																		<th><input type="checkbox" id="checkall"/></th>
																	<?php endif; ?>
																	<th>No</th>
																	<?php if ($this->CI->cek_hak_akses('u')): ?>
																		<th>Aksi</th>
																	<?php endif; ?>
																	<?php if ($o == 2): ?>
                                    <th><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/1")?>">Nama Gambar <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 1): ?>
                                    <th><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/2")?>">Nama Gambar <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/1")?>">Nama Gambar <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
                                  <?php if ($o == 4): ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/3")?>">Aktif <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 3): ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/4")?>">Aktif <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/3")?>">Aktif <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																	<?php if ($o == 6): ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/5")?>">Dimuat Pada <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                  <?php elseif ($o == 5): ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/6")?>">Dimuat Pada <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                  <?php else: ?>
                                    <th nowrap><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$p}/5")?>">Dimuat Pada <i class='fa fa-sort fa-sm'></i></a></th>
                                  <?php endif; ?>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($sub_gallery as $data): ?>
																	<tr>
																		<?php if ($this->CI->cek_hak_akses('h')): ?>
																			<td><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
																		<?php endif; ?>
																		<td><?=$data['no']?></td>
																		<?php if ($this->CI->cek_hak_akses('u')): ?>
																			<td nowrap>
																				<?php if ($this->CI->cek_hak_akses('u')): ?>
																					<a href="<?=site_url("gallery/urut/{$data['id']}/1/{$sub['id']}")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
		                    									<a href="<?=site_url("gallery/urut/{$data['id']}/2/{$sub['id']}")?>" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
																					<a href="<?= site_url("gallery/form_sub_gallery/{$gallery}/{$data['id']}")?>" class="btn btn-warning btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
	                                        <?php if ($data['enabled'] == '2'): ?>
	                                          <a href="<?= site_url('gallery/gallery_lock/' . $data['id'] . "/{$gallery}")?>" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan Gambar"><i class="fa fa-lock">&nbsp;</i></a>
	                                        <?php elseif ($data['enabled'] == '1'): ?>
	                                          <a href="<?= site_url('gallery/gallery_unlock/' . $data['id'] . "/{$gallery}")?>" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan Gambar"><i class="fa fa-unlock"></i></a>
	                                        <?php endif; ?>
	                                      <?php endif; ?>
																				<?php if ($this->CI->cek_hak_akses('h')): ?>
																					<a href="#" data-href="<?= site_url("gallery/delete_sub_gallery/{$gallery}/{$data['id']}")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
	                                      <?php endif; ?>
	                                    </td>
	                                  <?php endif; ?>
                                    <td width="60%">
																			<label data-rel="popover" data-content="<img width=200 height=134 src=<?= AmbilGaleri($data['gambar'], 'kecil') ?>>"><?= $data['nama']?></label>
																		</td>
																		<td><?= $data['aktif']?></td>
																		<td nowrap><?= tgl_indo2($data['tgl_upload'])?></td>
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
                          <form id="paging" action="<?= site_url("gallery/sub_gallery/{$gallery}")?>" method="post" class="form-horizontal">
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
                              <li><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$paging->start_link}/{$o}")?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->prev): ?>
                              <li><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$paging->prev}/{$o}")?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                            <?php endif; ?>
                            <?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++): ?>
                              <li <?=jecho($p, $i, "class='active'")?>><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$i}/{$o}")?>"><?= $i?></a></li>
                            <?php endfor; ?>
                            <?php if ($paging->next): ?>
                              <li><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$paging->next}/{$o}")?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                            <?php endif; ?>
                            <?php if ($paging->end_link): ?>
                              <li><a href="<?= site_url("gallery/sub_gallery/{$gallery}/{$paging->end_link}/{$o}")?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
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
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>